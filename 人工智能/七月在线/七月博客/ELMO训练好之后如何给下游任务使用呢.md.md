![image.png](https://gitee.com/hxc8/images10/raw/master/img/202407231053213.png)
ELMO这个网络结构其实在NLP中是很常用的。使用这个网络结构利用大量语料做语言模型任务就能预先训练好这个网络，如果**训练好这个网络后，再输入一个新句子，句子中每个单词都能得到对应的三个Embedding：**
第一个Embedding，是单词的Word Embedding
第二个Embedding，是双层双向LSTM中第一层LSTM对应单词位置的Embedding，这层编码单词的句法信息更多一些
第三个Embedding，是双层双向LSTM中第二层LSTM对应单词位置的Embedding，这层编码单词的语义信息更多一些
也就是说，ELMO的预训练过程不仅仅学会单词的Word Embedding，还学会了一个双层双向的LSTM网络结构，而这两者后面都有用

预训练好网络结构后，如何给下游任务使用呢？下图展示了下游任务的使用过程，比如我们的下游任务仍然是QA问题，此时对于问句X

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202407231052112.png)

1. 可以先将句子X作为预训练好的ELMO网络的输入
2. 这样句子X中每个单词在ELMO网络中都能获得对应的三个Embedding
3. 之后给予这三个Embedding中的每一个Embedding一个权重a，这个权重可以学习得来
4. 根据各自权重累加求和，将三个Embedding整合成一个
5. 然后将整合后的这个Embedding作为X句在自己任务的那个网络结构中对应单词的输入，以此作为补充的新特征给下游任务使用

对于上图所示下游任务QA中的回答句子Y来说也是如此处理。因为ELMO给下游提供的是每个单词的特征形式，所以这一类预训练的方法被称为“Feature-based Pre-Training”。

技术迭代的长河永不停歇，那么站在现在这个时间节点看，ELMO有什么值得改进的缺点呢？

1. 首先，一个非常明显的缺点在特征抽取器选择方面，ELMO使用了LSTM而不是新贵Transformer，毕竟很多研究已经证明了Transformer提取特征的能力是要远强于LSTM
2. 另外一点，ELMO采取双向拼接这种融合特征的能力可能比BERT一体化的融合特征方式弱

此外，不得不说除了以ELMO为代表的这种“Feature-based Pre-Training + 特征融合(将预训练的参数与特定任务的参数进行融合)”的方法外，NLP里还有一种典型做法，称为“预训练 + 微调(Fine-tuning)的模式”，而GPT就是这一模式的典型开创者

                        
原文链接：https://blog.csdn.net/v_JULY_v/article/details/127411638