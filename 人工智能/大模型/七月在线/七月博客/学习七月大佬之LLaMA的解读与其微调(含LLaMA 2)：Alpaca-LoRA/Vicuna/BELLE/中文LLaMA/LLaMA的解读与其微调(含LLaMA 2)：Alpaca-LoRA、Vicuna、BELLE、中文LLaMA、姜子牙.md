## LLaMA的模型架构：改造Transformer——RMSNorm/SwiGLU/RoPE
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

SA部分代码实现步骤：
1、先初始化注意力计算中的三个query、key和value向量的产生。
2、后forward
3、基于SwiGLU的前馈网络FFN
    BART中的FFN用的是fc->act->fc，用了两层全连接
    GPT中的FFN，用的是conv1D->act->conv1D,也是只用了两层
    而LLaMA中的FFN采用三个全连接层以实现FFN SwiGLU。

4、将SA和FFN这两部分拼接在一起，形成transformer block
    最后利用torch的module list将transformer block进行堆叠，拼上最前头的embedding部分，就是一个完整的transformer decoder结构了。
5、预测下一个token
    5.1、对prompt进行tokenize，得到token_ids
    5.2、计算当前batch的最大长度token_len，用来创建输入的token_tensor，最大长度不能超过前文所述的缓存的大小
    5.3、从当前batch中，最短的一个prompt的位置，作为生成的开始位置，开始生成。
    5.4、输入的token Tensor 传入到transformer模型，计算logits，得到形状为（batch_size，hidden_size）的logits（即transformer的最后一层的输出）。
    5.5、softmax+top_p采样，得到预测的token，并更新当前位置，准备预测下一个token。
    5.6、解码得到生成的文本

### LLaMA的Optimizer设计、模型加速优化与微型版本
在optimizer的设计上，该模型使用AdamW优化器进行训练，超参数设置为β1=0.9，β2=0.95，此外是用cosine淤血学习率的方式，使最终学习率等于最大学习率的10%，以及使用0.1的权重衰减和1.0的梯度剪裁，还有2000个warm up策略，是的可以根据模型的大小改变学习率和批次大小。
模型加速的设计：首先是，因果多头注意力，可以有效减少内存的使用和计算，具体原理是通过不存储注意力权重和不计算由于语言建模任务的因果性质而被遮盖的键/查询分数来实现的。其次是，减少了check pint的后向传递中重新计算的激活量。最后是，尽可能的重叠激活的计算和GPU之间的网络上的通信。

## 第二部分 各种微调LLaMA：Alpaca(self-instruct)、Vicuna(shareGPT)、BELLE(self-instruct)
### 构建self-instruct数据
1、人工设计175个任务，每个任务都有对应的指令{指令 输入 输出/实例}或{指令 输出/实例}，将这175个任务数据作为种子集。
2、然后提示模型比如GPT3对应的API，使用种子集作为上下文示例来生成更多新的指令
3、判断该模型生成的指令是否为分类任务
 如果是分类任务，就通过模型输出 Class_label 和 Input（Output-first，即先输出分类的标签，再输出Input内容）；如果不是分类任务，就通过模型输出 Input 和 Output（Input-first，即先输出Input，再输出Output）。
4、使用模型生成实例
5、对上述模型生成的数据，过滤掉低质量和相似度高的
6、经过过滤和后处理的数据添加到种子池中
 为了数据的多样性，新生成的指令只有与种子池中的指令的 ROUGE-L 小于0.7时才会添加进入种子池；排除一些无法被语言模型处理的指令，比如涉及图像、图片、图形的指令；在给指令生成实例时，会过滤掉输入相同但是输出不同的实例。

一直重复上述2-6直到种子池有足够多的数据。