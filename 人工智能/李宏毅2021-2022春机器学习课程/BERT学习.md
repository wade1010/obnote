BERT怎么用？两个任务

1、盖住一些词汇，它可以把盖住的部分补回来，填空。

2、预测两个句子是不是应该被接在一起（李老师有说这招没什么用）

神奇的地方，可以被用在其它的地方，这些任务跟填空题不一定有关，甚至根本没有关系。但是bert可以被用在这些任务上。这些真正被使用的任务叫做downstream tasks（下游任务）。downstream task的意思就是哪些你实际真正在意的任务，但是我们要bert学会这些任务的时候，其实我们海狮需要有一些标注的资料的。

总之bert它只学会做填空题，但接下来，它可以被拿来做各式各样你感兴趣的downstream task。

它有点像是胚胎里面的干细胞，它具有各式各样无限的潜能，虽然现在还没有发挥它的能力，只会做填空题，但是接下来它有能力去解各式各样的任务。只要给它一点资料，刺激它，他就可以想胚胎干细胞一样可以粉化成各式各样不同的细胞，或者是给它一点有标注的资料，它就可以分化成各式各样的任务，那bert分化成各式各样任务这件事情叫做fine-tune。把bert拿来做微调，让它可以去做某一种任务。相对于fine-tune，在fine-tune之前产生这个bert的过程叫做pre-train。所以产生bert这个过程，叫它self-supervised learning，你也可以叫它pre-train。 

（弹幕上看到一种理解，相当于做英语完形填空做得很好之后，再去做阅读题或者协作很快可以上手，因为已经训练很好的英文基础）

评价bert能力使用GLUE（General Language Understanding Evaluation）

GLUE总共有9个任务，如下。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051672.jpg)

bert到底是怎么被使用的？（4个例子）

1、下游任务是输入一个sequence，输出一个类别。比如：sentiment analysis。 (this is good ->positive)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051976.jpg)

在训练的时候，linear和bert都会用gradient descent去update参数。只是现在linear部分参数是随机初始化的，bert部分初始参数是从学习了做填空题的那个bert来的。

bert整个使用过程合起来就是pre-train和fine-tune合起来算是semi-supervised （上游自监督，下游监督，合起来半监督）

2、输入一个sequence，输出同样长度的sequence

词性标注

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051196.jpg)

3、输入两个sequence，输出一个类别。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051219.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051216.jpg)

4、问答系统（稍微有点限制的问答，答案一定出现在文章里面）

 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051249.jpg)

把D和Q丢到QA Model里面会输出两个正整数，根据这两个正整数，直接从文章里面截一段出来就是答案。 从s位置开始截取到e结束。这是当今QA，标准的做法。

PLMs（pre-trained language model(fine-tuning)）的问题

1、数据稀缺性

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051496.jpg)

在当前任务里面，有label的training data 其实不会这么多。现实中下游任务要收集几千条数据会花费很多的时间和金钱。

2、PLM太大了

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051570.jpg)

而且还越来越大。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051663.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051934.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051975.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051900.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051118.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051164.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172051234.jpg)

设计一些东西让model知道你在做什么。

当我们把这些data point，就是dataset里面的data转换成自然语言的prompt，那么model可能会比较知道他要做什么事情。

就是要用一个natural language去提示这个language model它现在要做什么，还要回答什么东西。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052444.jpg)

在prompt tuning里面我们需要3个东西：

1、a prompt template：把data points转换成自然语言prompt的一个template

2、a PLM

3、a verbalizer

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052744.jpg)

在上面例子里面，就是要把premise跟hypothesis用某一种自然语言的方式结合起来。那就设计一个template，这个template告诉我们，把premise跟hypothesis中间连接起来。

中间怎么接呢？中间打一个问号，然后放一个mask，这个mask功能就是让pre-trained language model去填充这个mask应该要填什么字，所以我们才会学到一个比较好的网络。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052808.jpg)

output是over整个vocabulary set的，bert大概是30522个。可是我们真正要在意的只是他们的关系到底是entailment、neutral、contradiction，所以我们需要有某一种方法可以把entailment、neutral、contradiction转换成vocabulary set里面的某些特定的单次。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052993.jpg)

那verbalizer做的事情就是他要去转换这个label跟vocabulary set之间的关系，它是一个mapping，它把这个label set里面的每一个label去map到vocabulary set某一个特定的单字。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052429.jpg)

verbalizer索要做的事情就是它要去转换这个label跟vocabularyset之间的关系，也就是它是一个mapping，它把这个label set里面的每一个label map到这个vocabulary set里面某一个特定的单子那现在定义说entailment对应到vocabulary set里面的yes，neutral就对应到vocabulary set里面的maybe，contradiction对应no。

所以只要说当model predict出来这个Probabilistic solutions ,我们就是要找yes、no、maybe这三个字，然后通过softmax得到最终的概率分布。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052650.jpg)

我们需要一个prompt template、PLM、verbalizer把这个vocabulary转换成我们的label，那在fine-tune的时候，我们就整个model一起tune

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052761.jpg)

prompt tuning和standard fine-tuning有什么差别呢？

standard的使用一个[SEP]把premise和hypothesis分开

在prompt里面我们会用一个人设计的这个prompt template，然后把它丢到模型里面，这个模型其实也不太一样，在standard fine-tuning的时候，我们会把原本的language model，这个LM Head直接丢掉，重新initialize一个classifier Head,然后finetune整个model，可在prompt tuning的时候我们就是要利用language model，然后就有了language model的能力，所以我们会保留这个language model head，我们不会另外再加一个classifier head。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052829.jpg)

上图label data scarcity也是几千笔以下，当如果更少的话，要怎么做？单单做prompt-tuning 可能就不够了。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052155.jpg)

假设labeled training data只有10几笔，只有10几笔，做prompt-tuning，当然也是做得起来，但是我们希望它可以做得更好。 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052260.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052663.jpg)

demonstration:示范

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052009.jpg)

 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052220.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052481.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052909.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052062.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052530.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052285.jpg)

解决的问题，当我们label data太少的时候，用一些特殊方法，这些方法需要把这个dataset转成prompt的形式，转换之后，我们视不同的情况增加一些scenario-specific设计。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172052459.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053803.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053071.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053382.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053825.jpg)

Lora

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053178.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053334.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053763.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053948.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053027.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053196.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053603.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053977.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053209.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053469.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053579.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172053831.jpg)

prompt tuning是为了解决训练数据少的问题。