![](https://gitee.com/hxc8/images0/raw/master/img/202407172104300.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104412.jpg)

上图进行混合精度的计算，真正线程执行的时候，是把A矩阵的一行乘以B矩阵的一列 ，再加上C矩阵的一个元素，得到D矩阵里面的其中一个元素。这个就是tensor core里面的FMA指令执行的具体内容。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104582.jpg)

上图是支持的格式  数据类型  数据格式

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104889.jpg)

上图左边是SM原来的架构图，一个SM里面有4个子核，叫做sub core

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104077.jpg)

是L1的指令缓存，对应有4个sub core，sub core下面又有对应的L1的缓存还有shared memory

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104842.jpg)

首先L1 Cache里面会把具体的指令发到SUB core里面，这里面的指令或者箭头是单向的，sub core 计算完之后，它的箭头是双向的，也就是我们可以获取下一批计算数据或者对数据进行重复的计算。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104033.jpg)

首先SM主要是负责对寄存器里面的整体逻辑进行读和写和计算的，这里面每一个sub core就包括了tensor core、FP64、FP32、INT8，当然还有特殊的处理单元，是把CUDA Core\tensor core\RT core都包在sub core里面，

在鼠标划线的位置其实还有个warp schedule(如下图)，专门针对线程的warp进行调度的，数据就存储在L1或者L0 的cache里面

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104444.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104324.jpg)

针对第一代tensor core，每一个sub core都有一个4X4X4的tensor core

在这里的warp schedule向tensor core发送具体的矩阵乘法，也就是GEMM，WMMA（也不知道对不对)的运算指令，tensor core就会从寄存器去读取或者去接收ABC矩阵，A和B矩阵是FP16，C矩阵是FP32或者FP16，执行多次的4X4矩阵乘法，直到完成整个矩阵运算之后，将所得的矩阵写回寄存器或者shared memory里面。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104491.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104389.jpg)

上图是对上上张SM图，进行再打开的微架构，每个时钟周期可以执行4个warp instruction，下属4个sub core是独立的，里面的数据是不进行缓存的，但sub core 里面的两个tensor core的数据是可以共享的，再往下有一个共享内存，这个共享内存每个时钟周期可以传输128B的数据。

当所有SMEM（应该是system memory）计算完这个全数矩阵之后就会把数据回传到L2cache里面，最后就会返回到host cpu

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104596.jpg)

L1 Cache是在上面，L1CAche里面具体的执行的就会到L0 Cache或者叫register file,把一些数据传输到这，具体的指令分发是通过warp schedule的

针对通用的计算是通过Math Dispatch Unit分发到具体的FP64\FP32、BP32还有MUFU，这几个具体的执行单元器计算，但是如果调用的是WMMA相关的API或者相关的指令，warp schedule就直接去触发或者去执行tensor core里面的计算（截于上图，红色框部分）

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104761.jpg)

tensor core里面就有两个4X4X4的tensor去每个时钟周期去执行，最后就把数据回存到register file，也就是寄存器里面。寄存器再通过MIO的datapath跟shared memory进行通讯。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104715.jpg)

这里面有大量的数据传输，数据存在哪里非常关键，现在看下L1缓存和共享内存之间的关系。VOLTA架构对比起P100有一个很大的改进点，就是把L1的缓存跟共享内存的合并成为同一块空间。这里面共享内存SMEM可以为整个SM提供高达96KB的存储空间。针对L2也有一个5%-15%的提升。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104485.jpg)

volta架构直接提供了一个tensor core的指令，提供给warp schedule，warp schedule直接去执行，不需要通过math dispatch unit去进行分发。除了tensor core是专门针对AI框的矩阵进行计算之外，volta架构还减少饿了指令的延迟。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172104894.jpg)

直接看Sub core，tensor core除了原先的FP16，其实还增加了int8 int4多种类型（上图左边Tesnsor Core的绿色框），另外还支持FP16 ，每个时钟周期可以执行32次，在4-8个时钟周期内，可以执行单个多线程GEMM的计算。也就是计算频率或者计算吞吐就更高了。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105056.jpg)

最下面是NVLINK，针对单节点多卡之间进行数据互联，再往上,L2和DRAM就是针对每一款GPU卡里面的系统内存。

再往上就是每一个SM里面的内存，首先有共享内存，有共享内存/L1，针对具体的计算有Math，包括tensor core或者cuda core都取决于math。

而真正的A100，它最重要的就是改变就是movement efficiently，就是数据搬运更加的快，有一个三倍的提升，下面看看它是怎么做的。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105139.jpg)

在ampere架构之前，包括turing\volta，如果tensor core想要使用共享内存，就必须先把数据从全局内存加载到寄存器（register file），然后再写入共享内存。整体说是数据要搬来搬去。

除了增加数据的搬运，其实还影响了时延

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105327.jpg)

具体看看怎么做。上图就是V100原来的方式，如果想要使用共享内存，就需要从L1把共享内存的数据搬运到寄存器堆里面，也就是register file里面，然后共享内存，就会搬到RF里面，具体给我们tensor core去执行。每一次数据搬运都会非常的占用时延。

在A100里面，就提出了一个软件的Async Copy，异步的拷贝机制，通过这个新的指令，可以把L2 Cache里面的全局内存直接搬到SMEM共享内存里面，然后给register file，直接的去执行，每次数据搬运都会增加时延、功耗 

上图其实还有一点，漏掉了，就是V100里面，需要4个读取的数据给到warp, A100只需要读取两次。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105832.jpg)

一个warp就提供了32个线程，这32个线程可以共享数据，而volta架构里面，整个rensor core只有8个线程。

这样的好处，可以在线程之间，减少矩阵的数据搬运。

因此数据搬运会比V100更少。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105874.jpg)

看一下具体的FFMA（fused float multiply and add）计算矩阵乘加的操作

绿色的这些小块叫做support（如下图）

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105166.jpg)

而连续的蓝色的框，就表示寄存器register（如下图）

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105175.jpg)

当我们寄存器纯粹使用CUDA Core的时候，会把所有的数据都放在寄存器register里面，每个register，针对一个CUDA Core的数据进行传输，所以使用CUDA Core，是算得非常慢的。

在V100里面，每个tensor core可以处理8个线程， 每个线程都有自己的寄存器，所以在8个时钟周期内，可以执行1024个MAC的操作

在A100里面的TC（tensor core）的指令，每个tensor core 可以32条线程， 因此可以在8个时钟周期内，去计算2048个MAC，每个时钟周期处理其中一块的数据。

第三代tensor core除了制作工艺提升之外，还提供了更多的线程使得硬件执行更快，数据搬运的更少，每个时钟的吞吐更大。

为什么出现tensor core之后，会比单纯的使用cuda core执行的更快？

针对矩阵计算，具体呢，是因为线程，每一次针对tensor core都可以处理得更快，处理得更多，吞吐是不一样的。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105363.jpg)

A100里面做了软件的异步加载之后，其实它还是基于warp level进行编程的，简单地说，就是把数据从HBM（就是全局内存）加载到寄存器，在通过warp schedule去调用tensor core来完成矩阵乘法， 最后再把数据回传到寄存器，再不断地去运算。

这个过程存在两个问题，如上图Pros所述。

1、数据的搬运和计算是严重的耦合的，线程去加载矩阵数据的时候，其实会独立地去获取矩阵块地址，因此会非常消耗寄存器的数量，还有存储的带宽

2、可扩展性受到约束，因为单个warp里面的线程有限，有限就会导致矩阵计算的规格受到约束。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105390.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105663.jpg)

上做事A100，右边就是H100，可以看到基本上没有太多的差别，除了因为工艺制程的原因导致这里cuda core和tensor core密度更高之外，最重要的就是多了一个tensor memory accelerator

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105069.jpg)

在A100里面，提出过一个软件的数据异步加载，这里TMA把它硬件化了，非常方便把全局内存的数据，异步加载到共享内存，直接给register file 寄存器去读写。 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105334.jpg)

H100之前的架构图，大部分都是上图左所示，只有grid、block之分，线程是没有办法控制的，因此针对硬件，分别对应的是一个SM，SM对应的是block。而grid对应整个device，也就是单个GPU。局部的数据只能通过shared memory在SM内进行共享，跨SM之间，是不能处的。

而Hopper架构直接在硬件上面引入了一个交叉互联网络，也就是直接把数据拓展到4个SM，SM之间可以互相通讯。于是在软件上或者cuda上面，引入了一个新的概念叫做TBC（thread block cluster），也就是把4个SM聚合起来。

有限的SM跟SM之间，可以高效访问他们互相之间的内存，所以这种叫做分布式共享内存。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105809.jpg)

既然硬件有所改变，所以软件有改变，就提出warp group这种编程的模式。对应的就引入了thread block cluster。

更直观的从软件层面看一下有什么区别。上图左A100就是没有进行分布式共享内存的，每个thread block，就是对应的SM，里面可以共享自己的局部内存，但是SM与SM之间没有办法进行数据交互，于是只能通过全局内存进行交互。

但是在H100里面，引入了SM的cluster（TBC），通过硬件来实现分布式共享内存，SM与SM之间的数据可以互联，不需要再次把数据放在HBM里面再进行交互，这样的话，可以减少寄存器的数量的利用，还可以减少数据传输的时延

#### tensor core的应用

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105960.jpg)

H100里面主要针对大模型或者transformer的架构进行堆叠的这种，像GPT，ChatGPT这种大模型，但是这种大模型输入的时候，有非常多的词汇，会把词汇embedded成具体的一些向量，然后呢，输出的时候，还是以一个向量为主，经过softmax层就会输出一个比词表更大的一个向量。这个时候词向量的表就会变得非常非常的大，或者矩阵变得非常的大，在整个transformer计算的时候，也就变得非常的大。

之前提到tensor core 的数量是有限的，在V100里面，它是4X4X4，但是在软件上面扩展到16X16X16，只是数据不断地从局部进行搬运。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105191.jpg)

这个时候不是说随随便便的就能够从软件上面去处理所有的embedded或者所有的大矩阵的。

更多的看下刚刚的例子，

如果pad到8的倍数，整体的性能，会比没有pad到8的倍数里面高很多，这个时候就要求软件编程的时候，其实也需要注意到硬件怎么样才能实现的更加高效，这个叫做padding vocabulary 试着，对矩阵进行padding的操作。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172105399.jpg)

 