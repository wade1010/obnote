![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070904923.png)
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408070904980.png)
## 从零实现Transformer编码器模块
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

前三个线性层分别用于对 Q向量、K向量、V向量进行线性变换(至于这第4个线性层在随后的第3点用到（输出线性层，其实就是多头初一里的输出进行整合，得到最终多头注意力的输出）)
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
1、计算每个单词与其它单词之间的相似度，会拿<font color="#c00000">每个单词/token的Q向量</font>与<font color="#00b050">包括自身在内的所有单词/token的K向量</font>一一做点积（两个向量之间的点积结果可以代表两个向量的相似度）。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071018445.png)
对应到矩阵的形式上，则是矩阵Q与K矩阵的转置做相乘  
还是拿上面那个例子：「我想吃酸菜鱼」，则Q乘以K的转置![K^T](https://latex.csdn.net/eq?K%5ET)如下图所示
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071019976.png)
最终得到的![QK^T](https://latex.csdn.net/eq?QK%5ET)矩阵由6行6列，从上往下，逐行来看的花，每一个格子里都会有一个数值，每一个数值一次代表：
单词我与「我 想 吃 酸 菜 鱼」各自的点积结果或相似度，比如可能是0.3 0.2 0.2 0.1 0.1 0.1，代表编码1时放在「我 想 吃 酸 菜 鱼」上面的注意力大小
同时，可以看到模型在对当前位置的信息进行编码时，会过度的将注意力集中于自身的位置(当然 这无可厚非，毕竟自己与自己最相似嘛)，而可能忽略了其它位置。很快你会看到，作者采取的一种解决方案就是采用多头注意力机制(Multi-Head Attention)
  想与「我 想 吃 酸 菜 鱼」各自的点积结果或相似度
  吃与「我 想 吃 酸 菜 鱼」各自的点积结果或相似度
  酸与「我 想 吃 酸 菜 鱼」各自的点积结果或相似度
  菜与「我 想 吃 酸 菜 鱼」各自的点积结果或相似度
  鱼与「我 想 吃 酸 菜 鱼」各自的点积结果或相似度​

然后做缩放（除以![\sqrt{d_k}](https://latex.csdn.net/eq?%5Csqrt%7Bd_k%7D)）。

接着使用softmax，如下公式
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071022894.png)
使用softmax计算每一个单词对包括自身在内所有单词的attention值，这些值加起来和为1（相当于起到一个归一化的效果），如下图：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071025815.png)
最后再乘以![V](https://latex.csdn.net/eq?V)矩阵，即对所有values（v1 v2 v3 v4），根据不同的attention值（![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071030340.png)
），做加权平均，如下图：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071031683.png)
对应到"我想吃酸菜鱼"这个例子上，则如下图：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071032746.png)

最终得到单词的输出，如下图所示（图中V矩阵的4行分别代表v1 v2 v3 v4）：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071032238.png)
最终的每个单词都包含了上下文相关单词的语义信息，不再只是attention计算之前的状态（每个单词只有它自身的信息，和上下文没有关联）。

另外，这里面还有一点值得注意的是，可能有同学疑问：当我们计算x1与x2、x3、x4的相似度之后，x2会再与x1、x3、x4再依次计算一遍相似度，这两个过程中，前者算过了x1和x2的相似度，后者则再算一遍x2与x1的相似度，这不是重复计算么？其实不然，这是两码事，原因很简单，正如你喜欢一个人 你会觉得她对你很重要，但那个人不一定喜欢你，她不会觉得你对她有多重要。

#### 多头注意力(Multi-Head Attention)
公式不会写，这里就直接截图学习了。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071051008.png)
如果是更多的头，比如8个头，计算步骤也是一样的，最终把每个头得到的结果直接concat，最后经过一个linear变换，得到最终的输出，整体结果如下所示：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071100601.png)
#### Position-wise前馈网络的实现
前面，逐一实现了embedding、位置编码、缩放点积/多头注意力，以及Add和Norm，整个编码器部分还剩下最后一个模块，即下图框里的Feed Forward Network(FFN)
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071112831.png)
其中包括两个线性层变换：维度上先扩大后缩小，最终输入和输出的维度数为$d_{model}=512$ ,内层维度为$d_{ff} = 2048$，过程中使用ReLU作为激活函数。

### 对整个transformer  block复制N份最终成整个encode模块
N可以是多份，比如6。

## 从零实现Transformer解码器模块
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071139899.png)
回顾下解码器，从底至上：
- 输入包括2部分，下方是前一个时间步的输出的embedding再加上一个表示位置的positional encoding
- 接着是masked multi-head self-attention
![](https://gitee.com/hxc8/images10/raw/master/img/202408071140971.png)
然后做Add&Norm
- 再往上是一个不带mask的mult-head attention层，它的key、value矩阵使用encoder的编码信息矩阵，而query使用'上一个'Decode Block的输出。然后再做一Add&Norm
- 经过FFN层，也做一下Add&Norm
- 最后做下linear变换后，通过softmax层计算下一个目标单词的概率。

### Masked Multi-Head Self-attention
1、输入经过embedding+位置编码后，还是乘以三个不同的权重矩阵：$W^Q$、$W^K$、$W^V$，依次得到三个不同的矩阵输入：Q、K、V。
2、Q矩阵乘以K矩阵的装置$K^T$，得到$Q⋅K^T$，注意，紧接着$Q⋅K^T$会乘以一个mask矩阵，得到masked attention矩阵
3、masked attention矩阵经过softmax后，乘以V矩阵得到$Z_i$矩阵
4、比如多头取2，最终把$Z_1、Z_2$拼接之后，在做一个linear变换得到最终的$Z$矩阵。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408071202946.png)
### 编码器与解码器的协同
1、encoder中的QKV全部来自于上一层单元的输出，而decoder中只有Q来自上一层decoder单元输出，K与V都来自于encoder最后一层的输出，也就是说，decoder是要通过当前状态与encoder的输出算出权重后（计算query与各个key的相似度），最后encoder的编码加权得到下一层的状态。

比如当我们要把“hello world”翻译为"你好,世界"时，decoder会计算“你好”这个query分别于"hello"、“world”这两个key的相似度，很明显，"你好"与“hello”更相似，从而给“Hello”更大的权重，从而把“你好”对应到“hello”，达到的效果就是“hello”翻译为”你好“

2、且在解码器中因为加了masked机制，自注意力层只允许关注已输出位置的信息，实现方法是在自注意力层的softmax之前进行mask，将未输出位置的权重设置为一个非常大的负数（进一步softmax之后基本变为0，相当于直接屏蔽了未输出位置的信息）


## 第三部分 Transformer的整个训练过程：预处理与迭代
#### Adam优化器：自动调整学习率并具有动量效应
优化器用于在训练过程中更新模型参数以使最小化损失函数，而Adam(Adaptive Moment Estimation)是一种常用的优化器，它结合了两种传统优化算法的优点：Momentum和RMSprop

通俗易懂理解Adam，可以将其比作一个赛车手，模型训练就像是找到一辆赛车在赛道上的最佳形式速度和路径，以达到最快的速度并取得优异的成绩。在这个过程中，速度的调整（即学习率）非常重要。
1、首先，adam想momentum一样，具有动量效应。这意味着赛车手（模型）会累积动量，使其在下坡时更快，而在上坡时减速。这有助于模型更快地穿越平坦区域，并避免在最低点附近摆动。
2、其次，adam想RMSprop一样，会自适应地调整每个参数的学习率，在我们的赛车比喻中，就像赛车手针对每个轮胎的摩擦系数（赛道状况）做出相应的速度调整，这有助于模型更快递收敛到最优解。

总之，adam可以自动调整学习率，并具有动量效应。总的来说，它能帮助我们的“赛车手”在不同的赛道状况下更快地找到最佳行驶速度和路径，从而更快地训练出高效的模型。

