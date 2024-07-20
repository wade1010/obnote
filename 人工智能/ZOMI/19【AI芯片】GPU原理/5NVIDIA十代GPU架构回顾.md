![](https://gitee.com/hxc8/images0/raw/master/img/202407172106619.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106864.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106235.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106773.jpg)

新增了第三代的tensor core（第二个），这里面包括新增了一个数据位，就是TF32，专门针对AI进行加速。

MIG（第四个）mult instance GPU,多实例的GPU，将单个的A100 GPU，划分乘多个独立的GPU，为不同的用户提供不同的算力，这个更多的是为云服务器厂商提供一种更好的算力切分方案。

第三代NV-Link和NV-Switch(第五个)，NV-Switch就是把多台机器通过NV-Switch进行互联，单卡之间也就单机多卡之间通过NV-Link进行互联。

稀疏性加速（第三个）利用数学的稀疏性对AI的矩阵乘进行加速。

![](D:/download/youdaonote-pull-master/data/Technology/人工智能/ZOMI/19【AI芯片】GPU原理/images/WEBRESOURCE9b878486404ec0410ca1c8a992adc47dimage.png)

里面有6912个cuda的内核

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106829.jpg)

ampere架构很重要的就是tensor core的新一代，引入了RF32 BF16 还有FP64的支持，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172106147.jpg)

在tensor core里面很重要的就是BF16和TF32

平时用的更多的是FP32和FP16,

FP16在指数位有5个，

后来发现在训练的时候FP16其实是够用的。但是会遇到部分情况下，动态范围其实表示的不是很大，于是英伟达就推出了TF32，指数位保持跟FP132相同，而小数位，也就是后面的小数位，跟FPP16相同。

后来又出现了BF16。

其实听坊间很多传言说，FP16在训练大模型的时候不够用，更多的是用BF16，其实我在训练大模型的时候，用的很多FP16是够用的，如果可以肯定用FP32更好，但是其实发现用FP16，至少我现在训练Llama还有GPT-3是没有遇到精度不收敛的问题，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107646.jpg)

tensor core除了执行乘加操作以外，还可以支持稀疏的结构化的矩阵。

假设现在有一个稠密的矩阵是在训练的时候得到的，但是在真正推理的时候，做了一个简单的剪枝，剪枝它是有比例的，剪枝完之后，会做一个fine-tune的剪枝，然后得到一个稀疏的矩阵或者稀疏的权重，接着在英伟达架构里面，就会对矩阵进行压缩，变成一个稠密的矩阵，稠密的矩阵有一个很有意思的点，就是除了矩阵的数据之外，它还有一个indices,所以去把那些 压缩过的数据，进行检索记录，最后进行一个矩阵乘，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107853.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107283.jpg)

这里不再是通过PCIe插进去的，而是直接焊死在主板上面的

它的内存高达1TB，很多时候可以直接把数据全部加载到CPU的内存里面，然后再不断的回传到GPU里面，这样可以很好的去加速大模型的训练。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107788.jpg)

hopper架构除了GPU外，还有一个grace CPU，因此整体它叫做 grace hopper superchip，整个异构架构将英伟达的hopper的GPU的突破性，跟英伟达的grace CPU连在一起，在整个superchip里面CPU跟GPU之间通过NVLink进行连接，GPU和GPU之间也是通过NVLink进行连接的，而跨机之间通过PCIe5进行连接，可以看到CPU跟GPU以前是通过PCIe进行连接的，现在直接通过NVLink进行传输的，数据传输速率高达900GB每秒，GPU和GPU之间传输速率也高达900GB每秒。

这个东西使得GPU跟CPU之间的数据传输，时延和搬运不再是问题，变成一个c to c 也就是chip to chip 相互互联。

但是这个不是所有用户都能用的到，因为训练大模型的用户确实没有那么多，更多的是用大模型进行一个下游任务的微调，然后适配到它具体的任务，右边这个就是具体的grace hopper整体的3D的渲染图，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107696.jpg)

第四代tensor core，引入了transformer engine，专门针对大模型进行加速的。另外在GPU里面的内存，已经高达了300GB每秒的速度

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107174.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107720.jpg)

上图右边，相比A100，H100多了一个Tensor memory accelerator,专门针对张量进行数据的传输的，以前张量都是放在L2或者L1的cache，会更多的，可能有些数据放在register file，现在有了张量memory accelerator更好的对大矩阵大模型进行加速。