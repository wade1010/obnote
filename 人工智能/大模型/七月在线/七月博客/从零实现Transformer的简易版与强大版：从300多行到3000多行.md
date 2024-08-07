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
