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


### 为什么需要梯度累计这个操作？
原因在于batch_size越大，局部数据求得的梯度方向越接近全局的梯度优化方向。那怎么增大batch_size呢？一：可以增加硬件资源，二：通过梯度累积。
举例说明：假如我们有1000个样本的数据集，将其分成10个小批次，每个小批次包含100个样本。
- 梯度累积：在每个小批次的训练中，我们会计算出模型参数的梯度，然后将这些梯度累加起来
- 参数更新：假设有一个数值类型的参数gradient_accumulation_steps，用来指定我们想要累积多少个批次的梯度，而不是立即用上一步的梯度累积去更新模型参数。当我们处理完gradient_accumulation_steps个小批次后，我们就使用累积的梯度来更新模型的参数。（此示例gradient_accumulation_steps取5）
- 梯度清零：在每次更新完参数后，我们都会将累积的梯度清零，以便于开始下一个梯度累积和参数更新的周期。这里第一次处理了5个批次，还需要处理剩余的5个批次即可处理完。（开头分成了10个小批次）

值得一提的是，通常情况，我们会进行多个epoch的训练，而且每次进行新的epoch时，数据打乱，每个epoch后都会对模型的性能进行评估，并根据评估结果调整学习率等超参数。

#### Alpaca-LoRA：通过PEFT库在消费级GPU上微调「基于LLaMA的Alpaca」
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408061329707.png)
LoRA核心思想：用一种低秩的方式来调整参数矩阵。在数学上，低秩以为这一个矩阵可以用两个较小的矩阵相乘来近似。
##### 步骤
1、选择目标层：比如选注意力机制中的查询Q和键K矩阵
2、初始化映射矩阵和逆映射矩阵（较小的矩阵A和B）
    2.1、A是映射矩阵(一般用随机高斯分布初始化，当然实际代码实现时，比如微软的deepspeed chat在用到LoRA时，一开始通过0矩阵占位，然后调用搭配ReLU激活函数的kaiming均匀分布初始化，虽与LoRA原始定义所用的正态分布初始化不同，但此两种初始化方式都可以工作，更多介绍见下面deepspeed chat的代码 )，维度上是降维。
    2.2、B是逆映射矩阵，维度上是升维。
    其中，矩阵的大小由LoRA的秩(rank)和alpha值来确定
3、参数变换：将目标层的原始参数矩阵W通过映射矩阵A和逆映射矩阵B进行变换。![W' = W + A * B](https://latex.csdn.net/eq?W%27%20%3D%20W%20&plus;%20A%20*%20B)，这里W'是变换后的参数矩阵。
4、微调模型：使用新的参数矩阵![W'](https://latex.csdn.net/eq?W%27)替换目标层的原始参数矩阵![W](https://latex.csdn.net/eq?W)，然后在特定任务的训练数据上对模型进行微调。
5、梯度更新：在微调过程中，计算损失函数关于映射矩阵A和逆映射矩阵B的梯度，并使用优化算法，如Adam、SGD等，对A和B进行更新。
注意，在更新过程中，原始参数矩阵W保持不变。其实就是训练的时候固定原始PLM的参数，只训练降维矩阵A和升维矩阵B。
6、重复更新：在训练的每个批次中，重复步骤3-5，知道达到预定的训练轮次（epoch）或者满足收敛条件。

总之，LoRA的详细步骤包括选择目标层、初始化映射矩阵和逆映射矩阵、进行参数变换和模型微调。在微调过程中，模型会通过更新映射矩阵A和逆映射矩阵B来学习特定任务的知识，从而提高模型在该任务上的性能

相当于在训练期间，较小的权重矩阵(下图中的A和B)是分开的，但一旦训练完成，权重可以合并到一个新权重矩阵中 』``
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408061349101.png)
在代码中体现为

```
F.linear(input, self.weight, self.bias) + (self.lora_dropout(input) @ self.lora_right_weight @ self.lora_left_weight) * self.lora_scaling
```
@ 运算符用于矩阵乘法（matrix multiplication）。
加号左侧为原结构支路，加号右侧为新增支路，_self.lora_right_weight_ 和_self.lora_left_weight_ 分别为两个新引入线性层的参数


#### UC Berkeley的Vicuna/FastChat：通过ShareGPT.com的7万条对话数据微调LLaMA
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408061716380.png)
在数据规模上，Vicuna从ShareGPT.com 的公共 API 收集了大约 70K 用户共享对话，且为了确保数据质量，原作者们将 HTML 转换回 markdown 并过滤掉一些不合适或低质量的样本。此外，将冗长的对话分成更小的部分，以适应模型的最大上下文长度，并做了以下改进：
1、内存优化，为了是vicuna能够理解长上下文，将最大上下文长度从羊驼alpaca中的512扩展到2048，这大大增加了GPU显存的需求，对此通过利用"梯度检查点"和"闪存注意力"来解决显存的压力。 (_We tackle the memory pressure by utilizing [gradient checkpointing](https://arxiv.org/pdf/1604.06174 "gradient checkpointing") and [flash attention](https://arxiv.org/pdf/2205.14135 "flash attention")_)
2、多轮对话：调整训练损失以考虑多轮对话，并仅根据聊天机器人的输出计算微调损失。
3、通过spot instance降低训练成本。

####  Chinese-LLaMA/Chinese-Alpaca：通过中文数据预训练/指令微调

![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408062056054.png)
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408062107440.png)

至于如果自己扩建词汇表后，新的词汇表里面还是会存在没有意义的字符文字，有什么办法可以优化吗？这个还是要先看原因的，可能有几个方面

可能是根本的语料问题，新词是根据语料统计得到的，反过来说是语料中的字符频率信息决定新词的发掘，可能要看语料本身有没有问题
可能是具体实现问题，实施的时候vocabsize一类的阈值参数设置太大，导致连带一些确实没什么意义的长尾词都纳入进新词中了
也可能是对“无意义”的定义问题，如果你用的是BPE，也比较容易得到一些看上去不太合乎语言学层面的新词，但实际并没有什么影响。

