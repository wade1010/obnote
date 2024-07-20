## 前言

*前面我们已经介绍了无监督学习的主要内容，这篇文章将会介绍另外一种学习方式，自监督学习(self-supervised Learning).*

## 一、self-supervised Learning概述

### 1.为什么需要self-supervised Learning？

监督学习中由于需要标注标签，而标注成本却是十分昂贵。而非监督学习中，聚类的方式有可能导致最后的归类出现重叠，准确性会有一定程度的下降。在这种情况下，self-supervised Learning就营运而生了。 self-supervised Learning希望能够学习到一种通用的特征表达用于下游任务。其主要的方式就是通过自己监督自己，在无标签的数据上完成训练。

### 2.什么是self-supervised Learning？

最简单的一个self-supervised Learning是这样的:

![](https://gitee.com/hxc8/images0/raw/master/img/202407172043093.jpg)

在这里插入图片描述

将原本的数据集x分解成x'跟x''，将x'通过模型生成y，然后调整参数，使x''与y越接近越好，这样就是一个简单的self-supervised Learning。

## 二、BERT(Bidirectional Encoder Representation from Transformers)

### 1.什么是BERT?

BERT是Transformer中衍生出来的一种预训练模型，在结构模型上就是将 transformer 的 encoder 作为一个基本单元，把 N 个这样的基本单元顺序连接起来，由于transformer 的 encoder是一个双向模型，所以可以遍历到所有的token。 输入一排向量，输出另外一排向量，输入和输出的维度是一致的。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172043947.jpg)

在这里插入图片描述

我们可以看到，中间有多个Transformers所堆叠在一起。

### 2.BERT的优缺点

1. **优点:**

1. 模型具备迁移能力,不同的遮罩会衍生出不同的结果，综合起来的模型十分良好。

1. 使用 Transformer 作为特征提取器，其特征提取能力远强于 LSTM；

1. Bert 的双向特征融合能力也强于 ELMo，而 ELMo 只是采用拼接形式融合两个方向特征；

1. **缺点:**

1. 由于在训练阶段使用了 MASK 标志，造成了预训练和微调之间的不匹配；

1. 训练数据中只有 15% 的token被预测，所以 BERT 收敛慢，导致模型需要更多的训练步骤来收敛。

1. 可复现性差，基本没法做，只能拿来主义直接用

### 3.Fine-tuning(微调)

### 1.迁移学习简介

迁移学习(Transfer learning) 顾名思义就是把已训练好的模型（预训练模型）参数迁移到新的模型来帮助新模型训练。考虑到大部分数据或任务都是存在相关性的，所以通过迁移学习我们可以将已经学到的模型参数（也可理解为模型学到的知识）通过某种方式来分享给新模型从而加快并优化模型的学习效率不用像大多数网络那样从零学习。

### 2.迁移学习的手段

1. Transfer Learning：冻结预训练模型的全部卷积层，只训练自己定制的全连接层。

1. Extract Feature Vector：先计算出预训练模型的卷积层对所有训练和测试数据的特征向量，然后抛开预训练模型，只训练自己定制的简配版全连接网络。

1. Fine-tuning：冻结预训练模型的部分卷积层（通常是靠近输入的多数卷积层，因为这些层保留了大量底层信息）甚至不冻结任何网络层，训练剩下的卷积层（通常是靠近输出的部分卷积层）和全连接层。

### 3.为什么要使用Fine-tuning?

1. 站在巨人的肩膀上：前人花很大精力训练出来的模型在大概率上会比你自己从零开始搭的模型要强悍，没有必要重复造轮子。

1. 训练成本可以很低：如果采用导出特征向量的方法进行迁移学习，后期的训练成本非常低，用CPU都完全无压力，没有深度学习机器也可以做。

1. 适用于小数据集：对于数据集本身很小（几千张图片）的情况，从头开始训练具有几千万参数的大型神经网络是不现实的，因为越大的模型对数据量的要求越大，过拟合无法避免。这时候如果还想用上大型神经网络的超强特征提取能力，只能靠迁移学习。

### 4.BERT的实现过程

### 1.Embedding

BERT的Embedding与Transformer的Embedding有所不同，其Embedding包括:**Word Embedding,Segment Embedding,Position Embedding**

![](https://gitee.com/hxc8/images0/raw/master/img/202407172043704.jpg)

- Word Embedding：是词向量，第一个单词是CLS标志，可以用于之后的分类任务，中间有SEP标志，用于句子分割任务

- Segment Embedding：将句子分为两段，用来区别两种句子，因为预训练不光做LM还要做以两个句子为输入的分类任务

- Position Embedding：和之前文章中的Transformer不一样，是学习出来的

### 2.Masking input

![](https://gitee.com/hxc8/images0/raw/master/img/202407172043386.jpg)

在这里插入图片描述

随机mask每一个句子中15%的词，用其上下文来做预测。采用非监督学习的方法预测mask位置的词。在这15%中，80%是采用[mask]，10%是随机取一个词来代替mask的词，10%保持不变。 再用该位置对应的结果去预测出原来的token（输入到全连接，然后用softmax输出每个token的概率，最后用交叉熵计算loss） **做完这一步，BERT的预训练模型就拥有了预测盖住的词是什么的功能**

### 3.Next Sentence Prediction(NSP)

BERT使用了NSP任务来预训练，简单来说就是预测两个句子是否连在一起。具体的做法是：对于每一个训练样例，我们在语料库中挑选出句子A和句子B来组成，50%的时候句子B就是句子A的下一句（标注为IsNext），剩下50%的时候句子B是语料库中的随机句子（标注为NotNext）。接下来把训练样例输入到BERT模型中，用[CLS]对应的C信息去进行二分类的预测。 **做完这一步，BERT的预训练模型就拥有了判断两句子是否相连的功能**

![](https://gitee.com/hxc8/images0/raw/master/img/202407172043057.jpg)

在这里插入图片描述

### 5.BERT的用途

事实上，经过刚刚BERT预训练完成之后，就可以投入使用了，但是为了能在下游任务中迸发更大的能量，我们只需要进行微调(fine-tuning),就可以完成各种任务。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172043840.jpg)

在这里插入图片描述

下面进行举例:

1. **句子分类**

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044540.jpg)

在这里插入图片描述

一般来说句子分类可以分为多分类问题和二分类问题，二分类问题的典型场景就是垃圾邮件，多分类问题的典型场景是情感分类。 SST-2：电影评价的情感分析。 CoLA：句子语义判断，是否是可接受的（Acceptable）

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044479.jpg)

在这里插入图片描述

BERT没法凭空解决句子分析的问题，还需要提供一些标注资料（提供大量句子，每个句子是正面还是负面）才能够训练BERT的模型。linear部分的参数是随机初始化的，BERT的初始参数是把可以做填空题的BERT的参数拿来当做初始化的参数。

1. 实体提取

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044260.jpg)

在这里插入图片描述

典型的场景就是从句子中，提取人名，或词性。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044908.jpg)

在这里插入图片描述

BERT处理词性标注的问题，输入三个字，每个字对应一个输出向量，把三个输出向量分别做linear transformer乘上一个矩阵，在做softmax判断属于哪一个类别，BERT本体的参数不是随机初始化的参数。

1. 基于句子对的分类任务

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044572.jpg)

在这里插入图片描述

输入两个句子，输出一个类别，在这里举自然语言处理的例子，机器要做的事情就是判断前提和假设是否矛盾。 BERT对这个问题的处理，给它两个句子，句子中间用SEP分隔开，只取CLS的输出，丢到linear transformer（乘一个矩阵）里面，决定输出类别（判断两个句子是否矛盾）

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044386.jpg)

在这里插入图片描述

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044409.jpg)

在这里插入图片描述

1. 问答

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044221.jpg)

在这里插入图片描述

SQuAD v1.1：给定一个句子（通常是一个问题）和一段描述文本，输出这个问题的答案，类似于做阅读理解的简答题。SQuAD的输入是问题和描述文本的句子对。输出是特征向量，通过在描述文本上接一层激活函数为softmax的全连接来获得输出文本的条件概率，全连接的输出节点个数是语料中Token的个数。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044138.jpg)

在这里插入图片描述

输入有文章和问题，把输入丢到QA模型里面,如上图，输出两个正整数s,e，表示从文章的第S个字到第e个字串起来就是正确答案。把文章和问题用SEP隔开作为BERT输入，需要从头开始训练的东西只有两个向量，两个向量的输出和BERT的输出长度是一样的，把橙色的向量和文章的输出向量做inner product，算出三个数值，做softmax得到三个数，得分最高的就是s的输出。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044830.jpg)

在这里插入图片描述

蓝色部分代表答案结束的位置，蓝色向量和对应的每个黄色向量做inner product，算出三个数值，做softmax得到三个数，d3对应的向量得到的分数最高

## 总结

本文介绍了BERT，并且最后附上本文章的思维导图，希望能帮助大家快速地熟悉BERT的运作规律。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172044548.jpg)