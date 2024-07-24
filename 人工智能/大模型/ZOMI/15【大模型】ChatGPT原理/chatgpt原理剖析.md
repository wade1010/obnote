1、bert模型与GPT模型系列

2、强化学习加入人类反馈 RLHF模式（Reinforcement Learning Human Feedback）

3、强化学习PT和PPO算法

4、InstructGPT原理深度剖析

其实instructGPT是chatGPT的一个原生，当然现在chatGPT的论文没有公布，但是InstructGPT是现在为止跟chatGPT的算法原理是最接近的。

一、从GPT1/2到GPT3

微调到prompt学习的过渡

 ChatGPT1-2大部分都是用微调的方式，到ChatGPT3就引入的prompt learning的方式，

![](https://gitee.com/hxc8/images1/raw/master/img/202407172117195.jpg)

三代都是以transformer为核心去构造网络模型，不同的就在于他们的一个模型层，还有词向量的长度，最大的不同就是学习的方式。

GPT1:基于transformer Decoder预训练+微调finetune

generative pre-training gpt ，主要指NLP生成式的预训练模型，训练模式分为2个阶段：

1 利用语言模型LLM进行预训练学习

2 通过微调fine tuning解决下游任务，finetuning的工作就会可能是加几层layer层，或者加几层其它head，然后进行一个微调的，微调的工作确实会带来一些新的入参、新的模型层。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172117415.jpg)

上图是gpt-1里面的一个图，左边就是transformer的一个结构，用了transformer的结构之后呢，针对不同的下游任务（右边列了4个下游任务）。输了不同的数据之后，通过不同的堆叠方式，然后去组成欣的下游任务处理方式。

GPT-1的这种方式确实跟bert非常类似，

![](https://gitee.com/hxc8/images1/raw/master/img/202407172117937.jpg)

上面两个图层数基本上是一样的，通过词向量传进去变成embedding，然后给transformer的层， transformer层最后输出的是一些token。

看到最大的区别就是，E2（第二个embedding层）它会向左边有一个箭头，像embedding最后一层，向左边也有一个箭头，但是反观openai的GPT，他基本上只会向右边的箭头，它没有左边的箭头。

两者之间最大的区别就是对任务的处理不太一样。Bert预测的时候根据左边，右边，根据上下文去做一个预测的，但是像GPT，基本上只会根据前文的信息，去预测下一个单词是什么，做一个后向的预测。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172117995.jpg)

上图总结三个区别：1语言模型到底是单向还是双向的；2采用transformer的哪个部分去组成的；3在某一个具体的结构上面到底是multi-head attention还是masked multi-head attention

![](https://gitee.com/hxc8/images1/raw/master/img/202407172117110.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172117410.jpg)

下图标题不对，是zero-shot learning

![](https://gitee.com/hxc8/images1/raw/master/img/202407172117796.jpg)

gpt-2采用了一种zero-shot learning的方式，其实最大的区别就是因为我们引用了zero-shot learning ，就需要更多的参数，更大的网络模型，记录数据的特征，那这个时候最有效的办法就是增大网络模型，还有更大的数据量。

从上图可以看出GPT-2提供了4种不同的模型的结构，那不同的模型结构更多的是通过decoder来不断的去堆叠。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172117319.jpg)

把zero-shot精度进一步的提升，其实最开始prompt-tuning的动机旨在解决目前传统fine-tuning的两个痛点问题：

1 降低语义差异：我们的预训练模型跟下游任务其实有一个比较大的区别的，需要引入新的参数或者新的网络的模型层，针对不同的下游任务，可能需要重新调参，进行一个训练。这个时候呢，预训练模型跟下游任务，其实脱节比较严重，就像现在的CV一样。为什么CV没有那么多大模型就是因为他们现在没有一个很好的统一的范式，当然现在慢慢的出现了，不能所完全没有。

2 避免过拟合：因为在fine tuning的阶段，会引入新的一些参数，会重新训练，这个时候对预训练模型来说就有可能造成过拟合的问题

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118648.jpg)

上图最上面就是原始的一种预训练的方式，通过prompt tuning，就是我有一句话，去指引我下面的那句话到底预测是什么。我每一次训练的时候都会赛一句prompt tuning，就是赛一句指示性的一个语言或者指示性的一个句子进去，更好的对后面的数据，进行一个预测或者训练。后面训练的时候就可能把自己里面做一个mask（mask如下图）

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118036.jpg)

预训练可能简单的只有后面，那现在加了一句更好的句子或者更好的上下文，对它进行一个指导。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118984.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118041.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118094.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118279.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118457.jpg)

二、强化学习加入人类反馈 RLHF 模式

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118091.jpg)

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118353.jpg)

智能体：感知环境状态（state），根据反馈奖励（reward）选择合适动作（action）最大化长期收益，在交互过程中进行学习；

环境：接收智能体执行的一系列动作，对这一系列动作进行评价并转换为一种可量化的信号，最终反馈给智能体。

上图，首先会中智能体出发，智能体执行一个动作，在环境当中执行一个动作，那环境就会反馈一个新的状态和新的一个reward，就是反馈奖励给agent，agent根据新的状态，还有新的一个奖励，再去选择新的动作，虽然看上去这么简单的一个交互，怎么去求解，怎么变成数学范式。在强化学习里面，主要是把刚才的一个交互方式，变成了一个马尔科夫链，通过马尔科夫链，就对强化学习这个范式有了一个数学的表示，最后就是求解马尔科夫链的最优值。这个就完完全全变成数学等价的公式，就可以求强化学习。

![](https://gitee.com/hxc8/images1/raw/master/img/202407172118812.jpg)