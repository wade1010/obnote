过去，同样的token，那它就会有同样的向量。像这样的模型是不考虑它的上下文的，并不在意token它上下文接了什么样其它的token，同样的token就是同样的embedding，就对应到同样的向量。这样的技术有word2vec，Glove

如果你的token是英文的word，那你可能会遇到一个问题，就是英文的word实在太多了，你不可能穷举所有英文的词汇，每一个词汇都给它一个向量，因为太多的词汇被创造出来，如果你只是有一个table,里面存了所有英文词汇的向量，那你永远会有新的词汇，那你永远会有table里面找不到对应向量的词汇，所以怎么办呢？也许我们可以把model改成输入是英文的character，输出就是一个向量，也许你期待你的 model就可以读这个英文词汇的字首字根，可以从英文词汇的字首字根判断一个没有看过的词汇的意思。具有代表性的模型就是大名鼎鼎的fasttext。

中午其实每个字就像一个图像，能不能够久把中文一个token当做一个image，比如60x60的image，然后把image丢到cn里面（这个是过去的做法）这样的模型是不会考虑每个token的context，养只狗的狗跟单身狗的狗他们所对应的向量就是一样。

后来有了contextualized的embedding这样的概念。比如ELMO、BERT，他们就是contextualized embedding

过去的wordembedding、glove，fasttext等等，这些模型就是吃一个token要吐一个embedding，现在这些contextualized word embedding他们都是吃一整个句子，把一整个句子看过以后，再给每一个token embedding。

how to fine-tune?

input有两种可能

one sentence和multiple sentences

output有4种可能

one class、class for each token、copy from input和general sequence

2种input和4中output搭配起来，一共8种可能。

 假设你有一些task-specific label data，你怎么来fine-tune你的模型呢？

这里有两个做法

一、你的pre-trained model，他训练完以后就固定住了，他变成一个feature的extractor，你输入一个token sequence，抽出一大堆feature（抽出一大堆embedding），他们代表某些feature，把这些feature丢到task-specific的部分，我们只fine-tune task-specific部分。

二、我们把pre-trained的model跟task-specific部分接在一起，在fine-tune的时候，不止会调task-specific部分，我们也会fine-tune pre-trained的model，也就是把pre-trained部分和task-specific部分合起来当做是一个巨大的model，用这个巨大的model来解决我们想要解的nlp任务。但是你想直接train这个巨大的model,往往会很容易overfitting，今天因为你这个主体（pre-trained）部分已经pretrained了，它的参数不是随机初始化的，task-specific部分参数是随机初始化的，所以也许就没那么容易overfitting。

在文献上看来，这个finetune整个model的performance会比把pre-trained固定住，只train task-specific model的performance还要好。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050073.jpg)

但是如果我们采取finetune整个model的话，会遇到多个task需要多个pre-trained model，每个都行都要保存，导致模型过大。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050491.jpg)

所以有了adaptor的概念。也就是说，我们想要调这个pre-trained 的 model，但我们能不能只调pre-trained model的一部分就好？我们在pre-trained model里面加入一些layer，这些layer叫做adaptor，我们在finetune的时候，你也许会想要调pre-trained的模型， 但是我们只调adaptor的部分，所以pre-trained的模型大部分是维持原样的，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050726.jpg)

你发现它颜色没有变，但是只有adaptor颜色变，那你到时候再处理模型的时候，假设你现有3个任务要紧，但是你并不需要所有的参数都要存下来，你只需要存原来模型里面不会被调的部分，还有每一个pre-trained model里面的adaptor就可以了。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050001.jpg)

所以这样你需要存储的参数量就会比每一个任务都要存一个完成的pre-trained model还要少得多。

现在NPL，不是一个任务给它一个模型，而是线pre-train好一个模型以后，然后再把这个pretrain好的模型，finetune在不同的任务上。

我们希望可以有一个pre-train的模型，是把一串token吃进去，接下来他把每一串token变成一个embedding的vector，而且我们希望这些embedding的vector，它是contextualized 考虑上下文的。现在训练这样的model，多数时候你用的方法是un supervised。

BERT和ELMO相较于原来的word2vector 不同的地方它是contextualized

翻译任务，收集大量的pair data终究是比较困难的，能不能用没有标注的文字直接去训练处一个模型？

使用self-supervised learning ，就是用部分的输入去预测另外一部分的输入，也就是说我们输入的资料，有一部分被拿来提供supervised，有一部分被拿来预测别人。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050391.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050924.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050005.jpg)

如上图，在预测next token的时候，需要注意设计一下你的模型，不让它看到不该看的答案。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050049.jpg)

这样的预测下一个token是最早的unsupervised pretrain model，最早期的unsupervised pretrain model都是用这样的技术。

上面所讲的pretrain model其实就是一个language model，那要用什么样的network 架构来训练这个language model，来预测下一个token呢？最早我们会想到LSTM，很多知名的使用LSTM的pretrain的模型，其中最知名的就是大家知道的ELMo。另外一个不是很知名的就是TLMFiT（上图右）

 

现在人们不那么喜欢LSTM了，人们把LSTM换成self-attention，但是用self-attention的话，你就要稍微小心点，就是要控制你的attention的范围，我们知道说这个self-attention layer，他做得事情就是把整个sequence平行的读进去，然后每一个位置都可以attention到其它位置，就general的self-attention，是每一个位置都可以attention到其它的位置，所以你把一般的self-attention用在这个地方的话，那是不对的，对你的network来说，它只要attention到下一个位置，他就可以得到它想要的信息，他就可以成功预测下一个token， 这个显然不是我们要的。

今天如果你是用self-attention来做predict next token的话，你要注意下，给你的self-attention设置一个constraint，告诉他只有某些位置可以去attention的，某些位置是不能去attention的，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172050432.jpg)

上图表格里面有涂色的表示可以attention的，从列看，w1只能attention w1,w4只能attention w1-w4。那这样就可以避免在predict netxt token的时候，你的model看到未来的答案。

BERT不再是predict next token了，它做的事情是它会把输入的一些token盖住，这个盖住其实有两种做法，一个做法事有一个特别的符号叫做mask，不表示任何意思，就表示说我要把它盖住，你把原来的token换成mask token，就代表把原来的token盖住。另外一个做法是可以随机sample一个token。

接下来要做的事情就是根据这个位置所输出的embedding，去预测被盖起来的那个token原来是什么。

而BERT里面用的事transformer，用的时候是没有任何限制的，没有任何限制的意思是，每个word都可以attention到其它所有word，所以在你预测w2的时候，你是看了一整个完整的sentence，完整的token sequence才来预测w2，而w3,w4可以通过self-attention看到w1的资讯，w1也可以通过self-attention看到w3,w4的资讯。w2已经被mask起来，所以不怕被偷看到w2的资讯。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051575.jpg)

 跟很多年前的CBOW很像

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051778.jpg)

BERT缺乏generation的能力。不太适合sequence to sequence的pretrain model。

所以假设你要解的事需要sequence to sequence demo的nlp的任务，那bert呢可能只能当做encoder，decoder的地方就没有pretrain到

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051967.jpg)