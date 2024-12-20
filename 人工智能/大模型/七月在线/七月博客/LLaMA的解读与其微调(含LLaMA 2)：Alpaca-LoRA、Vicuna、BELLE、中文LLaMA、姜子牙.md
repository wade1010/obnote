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
#### 构建self-instruct数据
1、人工设计175个任务，每个任务都有对应的指令{指令 输入 输出/实例}或{指令 输出/实例}，将这175个任务数据作为种子集。
2、然后提示模型比如GPT3对应的API，使用种子集作为上下文示例来生成更多新的指令
3、判断该模型生成的指令是否为分类任务
 如果是分类任务，就通过模型输出 Class_label 和 Input（Output-first，即先输出分类的标签，再输出Input内容）；如果不是分类任务，就通过模型输出 Input 和 Output（Input-first，即先输出Input，再输出Output）。
4、使用模型生成实例
5、对上述模型生成的数据，过滤掉低质量和相似度高的
6、经过过滤和后处理的数据添加到种子池中
 为了数据的多样性，新生成的指令只有与种子池中的指令的 ROUGE-L 小于0.7时才会添加进入种子池；排除一些无法被语言模型处理的指令，比如涉及图像、图片、图形的指令；在给指令生成实例时，会过滤掉输入相同但是输出不同的实例。

一直重复上述2-6直到种子池有足够多的数据。


#### 为什么需要梯度累计这个操作？
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

##### 词表扩充中文数据

![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408062056054.png)
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408062107440.png)

至于如果自己扩建词汇表后，新的词汇表里面还是会存在没有意义的字符文字，有什么办法可以优化吗？这个还是要先看原因的，可能有几个方面

可能是根本的语料问题，新词是根据语料统计得到的，反过来说是语料中的字符频率信息决定新词的发掘，可能要看语料本身有没有问题
可能是具体实现问题，实施的时候vocabsize一类的阈值参数设置太大，导致连带一些确实没什么意义的长尾词都纳入进新词中了
也可能是对“无意义”的定义问题，如果你用的是BPE，也比较容易得到一些看上去不太合乎语言学层面的新词，但实际并没有什么影响。

##### 加入中文数据的预训练

在预训练阶段，使用约20G左右的通用中文语料（与[中文BERT-wwm](https://github.com/ymcui/Chinese-BERT-wwm "中文BERT-wwm")、[MacBERT](https://github.com/ymcui/MacBERT "MacBERT")、[LERT](https://github.com/ymcui/LERT "LERT")、[PERT](https://github.com/ymcui/PERT "PERT")中使用的语料一致）在原版LLaMA权重的基础上进一步进行预训练。该过程又分为两个阶段：
第一阶段：冻结transformer参数，仅训练embedding，在尽量不干扰原模型的情况下适配新增的中文词向量
第二阶段：使用LoRA技术，为模型添加LoRA权重（adapter），训练embedding的同时也更新LoRA参数
##### 指令精调
针对一些任务上效果不好，可能有以下几个原因：
1、本身LLaMA对中文支持不是很好，大多数相关衍生工作时直接在原版上进行pretrain/fine-tune的，而我们采取了更大胆的策略-增加中文词表，可能进一步加剧中文训练不充分的问题，但从长远看是否有利于后续进一步预训练就得靠时间检验了；
2、指令数据的质量有待进一步提升；
3、训练时间、超参等方面还有很大的调整空间；
4、没有RLHF；
5、4-bit量化后效果可能会下降，因此可以尝试加载BF16模型，效果相对更好一些（也更慢）。
#### 姜子牙系列模型Ziya-LLaMA-13B-v1
##### 模型的预训练与微调：预训练、SFT、HFFT
**继续预训练 Continual pretraining**
1、数据方面：
原始数据包含英文和中文，其中英文数据来自openwebtext、Books、Wikipedia和Code，中文数据来自清洗后的悟道数据集、自建的中文数据集。在对原始数据进行去重、模型打分、数据分桶、规则过滤、敏感主题过滤和数据评估后，最终得到125B tokens的有效数据
2、分词方面：
为了解决LLaMA原生对中文编解码效果低下的问题，我们在LLaMA词表的基础上增加了7k+个常见的中文字，通过和LLaMA原生的词表去重，最终得到一个39410大小的词表，并通过复用transformer里LLaMA Tokenizer来实现这一效果
3、训练过程
在增量训练过程中，我们使用了160张40GB的A100，采用2.6M token的训练集采样本数量和FP16的混合精度，吞吐量达到118TFLOP per GPU per second.

训练期间，虽然遇到了机器宕机、底层框架bug、loss spike等各种问题，但我们通过快速调整，保证了增量训练的稳定性。我们也放出训练过程的loss曲线，让大家了解可能出现的问题
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408062136079.png)
##### 多任务有监督微调 Supervised finetuning
在多任务有监督微调阶段，采用了课程学习(curiculum learning)和增量训练( incremental training)的策略，用大模型辅助划分已有的数据难度，然后通过“Easy To Hard”的方式，分多个阶段进行SFT训练。
SFT训练数据包含多个高质量的数据集，均经过人工筛选和校验：
- Self-Instruct构造的数据（约2M）：BELLE、Alpaca、Alpaca-GPT4等多个数据集
- 内部收集Code数据（300K）：包含leetcode、多种Code任务形式
- 内部收集推理/逻辑相关数据（500K）：推理、申论、数学应用题、数值计算等
- 中英平行语料（2M）：中英互译语料、COT类型翻译语料、古文翻译语料等
- 多轮对话语料（500K）：Self-Instruct生成、任务型多轮对话、Role-Playing型多轮对话等


##### 人类反馈学习 Human-Feedback training
为了进一步提升模型的综合表现，使其能够充分理解人类意图、减少“幻觉”和不安全的输出，基于指令微调后的模型，进行了人类反馈训练（Human-Feedback Training，HFT）。在训练中，我们采用了以人类反馈强化学习（RM、PPO）为主，结合多种其他手段联合训练的方法用来弥补PPO方法的短板、加速训练，具体包括

人类反馈微调(Human-Feedback Fine-tuning，HFFT)
后见链微调(Chain-of-Hindsight Fine-tuning，COHFT）
AI反馈(AI Feedback)
基于规则的奖励系统(Rule-based Reward System，RBRS)等
我们在内部自研的框架上实现了HFT的训练流程，该框架可以利用最少8张40G的A100显卡完成Ziya-LLaMA-13B-v1的全参数训练。在PPO训练中，我们没有限制生成样本的长度，以确保长文本任务的奖励准确性。每次训练的总经验池尺寸超过100k样本，确保了训练的充分性。

##### 基于LLaMA微调的各模型对比
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408062144296.png)


## 第三部分 更强的LLaMA 2开源，可直接商用

####  LLaMA 2 70B之分组查询注意力——Grouped-Query Attention
自回归解码的标准做法是缓存序列中先前标记的键 (K) 和值 (V) 对，从而加快注意力计算速度
然而，随着上下文窗口或批量大小的增加，多头注意力 (MHA)模型中与 KV 缓存大小相关的内存成本显着增长

​​​​​​​对于较大的模型，KV 缓存大小成为瓶颈，键和值投影可以在多个头之间共享，而不会大幅降低性能，可以使用

1、具有单个 KV 投影的原始多查询格式(MQA)
ChatGLM2-6B即用的这个。
不过，多查询注意(Multi-query attention，简称MQA)只使用一个键值头，虽大大加快了解码器推断的速度，但MQA可能导致质量下降，而且仅仅为了更快的推理而训练一个单独的模型可能是不可取的
2、或具有多个 KV 投影的分组查询注意力(grouped-query attention，简称GQA)，速度快 质量高
23年，还是Google的研究者们提出了一种新的方法，即分组查询注意(GQA，_论文地址为：[GQA: Training Generalized Multi-Query Transformer Models from Multi-Head Checkpoints](https://arxiv.org/pdf/2305.13245 "GQA: Training Generalized Multi-Query Transformer Models from Multi-Head Checkpoints")​​​​​​​_​​​​​​​)，这是一种多查询注意的泛化，它通过折中(多于一个且少于查询头的数量，比如4个键值头的数量，使得经过强化训练的GQA以与MQA相当的速度达到接近多头注意力的质量。
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202408062150459.png)
经实验论证，GQA 变体在大多数评估任务上的表现与 MHA 基线相当，并且平均优于 MQA 变体。

### Llama 2-Chat中的RLHF：依然是三阶段训练方式
#### 监督微调(SFT)
**在SFT的数据上**
1、他们先是重点收集了几千个高质量 SFT 数据示例 (注意：很多新闻稿会说SFT的数据达到百万以上，这就是没仔细看论文的结果，论文之意是胜过百万低质量的数据。
2、之后发现几万次的SFT标注就足以获得高质量的结果，最终总共收集了27540条用于SFT的标注数据。

**在微调过程中**

1、每个样本都包括一个prompt和一个response(说白了，就是问题-答案对，和instructGPT/ChatGPT本身的监督微调是一个本质)，且为确保模型序列长度得到正确填充，Meta 将训练集中的所有prompt和response连接起来。他们使用一个特殊的 token 来分隔prompt和response片段，利用自回归目标，将来自用户提示的 token 损失归零，因此只对答案 token 进行反向传播，最后对模型进行了 2 次微调
2、微调过程中的参数则如此设置：we use a cosine learning rate schedule with an initiallearning rate of 2 ×10−5 , a weight decay of 0.1, a batch size of 64, and a sequence length of 4096 token
微调过程中的参数设置如下：我们使用余弦学习率调度，初始学习率为 2×10−5，权重衰减为 0.1，批次大小为 64，序列长度为 4096 个令牌。
#### 训练两个奖励模型：一个偏实用 一个偏安全
关于奖励数据

prompt和response中的标记数因文本领域而异，比如摘要和在线论坛数据的prompt通常较长，而对话式的prompt通常较短。与现有的开源数据集相比，本文的偏好数据具有更多的对话回合，平均长度也更长
奖励模型将模型响应及其相应的提示(包括前一轮的上下文)作为输入，并输出一个标量分数来表示模型生成的质量(例如有用性和安全性)，利用这种作为奖励的响应得分，Meta 在 RLHF 期间优化了 Llama 2-Chat，以更好地与人类偏好保持一致，并提高有用性和安全性
在每一批用于奖励建模的人类偏好标注中，Meta 都拿出 1000 个样本作为测试集来评估模型，并将相应测试集的所有prompt的集合分别称为实用性和安全性。
#### 具体的策略迭代：PPO与拒绝采样
此处使用两种主要算法对 RLHF 进行了微调：
- 近端策略优化(PPO)

- 拒绝采样(Rejection Sampling)
即在模型生成多个回复后，选择最佳的回复作为模型的输出，过程中，如果生成的回复不符合预期，就会被拒绝，直到找到最佳回复
从而帮助提高模型的生成质量，使其更符合人类的期望。