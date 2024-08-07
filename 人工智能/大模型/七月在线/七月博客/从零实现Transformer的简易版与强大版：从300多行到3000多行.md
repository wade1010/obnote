![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070904923.png)
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070904980.png)
### 关于输入的处理：针对输入做embedding，然后加上位置编码
#### 针对输入做embedding
对于模型来说了，输入的每一句话，在模型中都是一个词向量，但是每句话都是输入的时候才去生成对应的项链，这处理起来无疑是会费时费力，所以在实际应用中，我们会实现训练好各种embedding矩阵，这些embedding矩阵包含常用领域常用单词的向量化表示，且提前做好分词。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070909777.png)
当模型接收到我们输入的内容后，便会到对应的embedding矩阵里查找对应的词向量，最终把整句输入转换为对应的向量表示。
#### 位置编码的实现

```
        # 使用正弦和余弦函数生成位置编码，对于d_model的偶数索引，使用正弦函数；对于奇数索引，使用余弦函数。
        pe[:, 0::2] = torch.sin(position * div_term)
        pe[:, 1::2] = torch.cos(position * div_term)
```
###  经过「embedding + 位置编码」后乘以三个权重矩阵得到三个向量Q K V
从下图可知，经过「embedding + 位置编码」得到的输入![X](https://latex.csdn.net/eq?X)，会乘以「三个权重矩阵：![W^Q](https://latex.csdn.net/eq?W%5EQ) ![W^K](https://latex.csdn.net/eq?W%5EK) ![W^V](https://latex.csdn.net/eq?W%5EV)」得到查询向量**Q**、键向量**K**、值向量**V**
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070914724.png)
举个例子，针对「我想吃酸菜鱼」这句话，经过embedding + 位置编码后，可得(_注：可以512维，也可以是768维，但由于transformer论文中作者设置的512维，所以除了这个酸菜鱼的例子暂为768维外，其他地方均统一为512维_)
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070914918.png)
然后乘以三个权重矩阵得
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070915402.png)
为此，我们可以先创建4个相同的线性层，每个线性层都具有 d_model 的输入维度和 d_model 的输出维度

        
```
self.linears = clones(nn.Linear(d_model, d_model), 4) 
```

前三个线性层分别用于对 Q向量、K向量、V向量进行线性变换(至于这第4个线性层在随后的第3点)
### 对输入和Multi-Head Attention做Add&Norm，再对上步输出和Feed Forward做Add&Norm
聚焦transformer原图，输入通过embedding和位置编码后，先后做了两个步骤
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070938964.png)
1、针对query向量做MHA，得到的结果与原query向量，先相加，然后做归一化。
这个相加，就是residual connection（残差连接）。是为了解决多层神经网络训练困难的问题，通过将前一层的信息无差的传递到下一层，可以有效的仅关注差异部分，这一方法之前在图像处理结构，如ResNet等中常常用到。
具体编码时是通过SublayerConnection函数实现此功能的

```
"""一个残差连接（residual connection），后面跟着一个层归一化(layer normalization)操作"""
class SublayerConnection(nn.Module):
    # 初始化函数，接收size（层的维度大小）和dropout（dropout率）作为输入参数
    def __init__(self, size, dropout):
        super(SublayerConnection, self).__init__()  # 调用父类nn.Module的构造函数
        self.norm = LayerNorm(size)                 # 定义一个层归一化(Layer Normalization)操作，使用size作为输入维度
        self.dropout = nn.Dropout(dropout)          # 定义一个dropout层
 
    # 定义前向传播函数，输入参数x是输入张量，sublayer是待执行的子层操作
    def forward(self, x, sublayer):  
        # 将残差连接应用于任何具有相同大小的子层
        # 首先对输入x进行层归一化，然后执行子层操作（如self-attention或前馈神经网络）
        # 接着应用dropout，最后将结果与原始输入x相加。
        return x + self.dropout(sublayer(self.norm(x)))
```
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071006444.png)

layer nomalization，通过对层的激活值(通常指的是神经网络中各个神经元的输出。这些输出是通过将输入数据与权重相乘并加上偏置后，再通过一个激活函数（如ReLU、Sigmoid或Tanh）计算得到的。)的归一化,可以加速模型的训练过程，使其更快的收敛。
#### 缩放点积注意力(Scaled Dot-Product Attention)
整体实现步骤：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071015778.png)
1、计算每个单词与其它单词之间的相似度，会拿<font color="#c00000">每个单词/token的Q向量</font>与包括自身在内的所有单词/token的K