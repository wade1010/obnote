### ### LLaMA的模型架构：改造Transformer——RMSNorm/SwiGLU/RoPE
#### 工具包：
fairscale是用来做GPU分布的，一般是当使用DDP仍然遇到超显存的问题时使用fairscale
fire，fire是一个命令行工具，用或者不用他都可以
sentencepiece，sentencepiece是用于tokenizer的工具包
「 _SentencePiece 实现了subword单元（例如，字节对编码(BPE)和 unigram语言模型），并可以直接从原始句子训练字词模型(subword model)，这是对SentencePiece的解读：[大模型词表扩充必备工具SentencePiece](https://zhuanlan.zhihu.com/p/630696264 "大模型词表扩充必备工具SentencePiece")_ 」


#### RMSNorm：对每个Transformer子层的输入进行归一化。
为了提高训练的稳定性，对每个transformer层的输入进行归一化，而不是对输出进行归一化，RMSNorm是一般layerNorm的一种辩题，可以在梯度下降时令损失更加平滑，与layerNorm相比，RMSNorm的主要区别在于删除了减去均值的部分，只保留了方差部分。

#### SwiGLU替代ReLU
ReLU函数的输出都是0，对于所有正的输入值，ReLU函数的输出等于输入值本身
GLU 的基本思想是引入一种称为“门”机制，该机制可以动态地控制信息的流动
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408061024125.png)
####  Attention计算的总过程
1. 输入![x](https://latex.csdn.net/eq?x)，分别经过三个Linear得到![x_q, x_k, x_v](https://latex.csdn.net/eq?x_q%2C%20x_k%2C%20x_v)
2. 在 ![x_q](https://latex.csdn.net/eq?x_q) 和![x_k](https://latex.csdn.net/eq?x_k)中加入旋转位置编码，<font color="#c00000">只针对q和k</font>
3. 缓存 ![x_q](https://latex.csdn.net/eq?x_q) 和 ![x_k](https://latex.csdn.net/eq?x_k) 
4. 计算![softmax(\frac {QK^T} {\sqrt{d_k}})V](https://latex.csdn.net/eq?softmax%28%5Cfrac%20%7BQK%5ET%7D%20%7B%5Csqrt%7Bd_k%7D%7D%29V)

其中有一个细节就是缓存机制，它设计的目的是在generate时减少token的重复计算。简单解释一下，就是在计算第n个token特征的时候，需要用到第![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408061036608.png)个token，即每次生成时，需要知道前面所有的过往信息，如果每次都从头算的话，那就会造成极大的浪费，所以就每算一个位置的信息，就把它缓存下来。

