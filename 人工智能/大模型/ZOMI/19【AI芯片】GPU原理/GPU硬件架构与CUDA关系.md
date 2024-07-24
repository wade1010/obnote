![](https://gitee.com/hxc8/images0/raw/master/img/202407172108729.jpg)

#### 硬件的基本概念：

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108888.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108126.jpg)

上图，每个GPC里面又有非常多的TPC，每个TPC里面又分多个SM，多个SM里面又分block和thread，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108378.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108682.jpg)

G80也就是2006年提出的。上图主要核心就是CUDA Core,就是计算核心，另外还会有一个共享内促，还有寄存器，里面SM最核心的模块，就是线程的执行单元，所以这里叫做CUDA core。上图可以看到不管是int32还是fp32还是fp64，还是tensor core，它最终都归根于执行单元或者最小的执行单位。因此说SM是英伟达的整个的核心，里面SM是非常多的计算单元，所有的计算单元都藏在SM里面，SM里面可以并发的执行数百个线程，每一个线程都会执行对应的指令，或者在对应的硬件数据上面去进行处理的。

当然这些都是硬件上的概念，而从软件上面来看，特别是从CUDA的角度来看，可以通过SM并发的执行数百个线程。

之前提到，线程其实是有等级的，它有块、网络和最小的单位线程。而每个线程块是放在同一个SM去执行的，而SM 里面通过Register或者Cache去约束每个块线程的大小。

处理的数据就这么多了，硬件单元就这么多了，所以线程不能无限制的扩充。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108086.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108120.jpg)

上面1 2 3主要是针对具体的计算、执行 来去看待的。

4会把线程下发到具体的计算单元里面，针对线程，它是个软件爱你的概念，实际上硬件是执行指令，因此就有了5，专门针对指令进行分发，分发到上面123的具体的执行单位。

最后就是访存和IO了，就是register file 寄存器堆，还有load、store，访问IO的一些单元，正门针对数据进行处理。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108297.jpg)

上面SP是CUDA Core的前身。CUDA Core后来也慢慢消亡了。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108333.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108547.jpg)

上图是其中一个CUDA core，一个SM里面有两组各16个CUDA core，每个CUDA core就是上图右边的小模块，包含一个浮点运算单元和一个整点运算单元。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108771.jpg)

CUDA core慢慢被取代，变成多个INT32单元、FP32单元，后来还出现了tensor core。去掉CUDA core的概念，是因为每个SM支持FP32、int32的并发执行，更好的提升我们运算的吞吐量，提升系统的利用率。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108963.jpg)

从逻辑上来说，所有线程都是并行或者并发来执行的，但是从硬件角度来说，不是所有线程，我们都能够在同一时刻去执行的，因此英伟达就引入了warp的概念，通过warp去控制线程，通过warp堆线程进行锁同步，然后拆解成具体的指令，给我们计算单元去执行。

#### CUDA 基本概念

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109991.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109225.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109253.jpg)

 

#### CUDA 线程层次结构

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109797.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109079.jpg)

什么是kernel

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109379.jpg)

这个程序只要是CUDA执行的部分，统一都叫做kernel

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109609.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109713.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109060.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109235.jpg)

上图是CUDA跟NVIDIA硬件架构的关系图

从软件，我们看到执行的是具体的线程，线程又包括block、grid，而在硬件上执行，我们的线程，实际上是执行在CUDA core里面的，每个线程对应我们的CUDA core,当然线程的数量是超配的，软件上的超配，硬件上我们执行是有限的，通过warp进行一个调度和同步。

然后看下第二层，就是线程块，线程块就是SM去执行。

最后一层就是Grid,网格就是有大量SM进行堆叠，堆叠起来就变成TPC还有GPC。

#### 算力的计算

![](https://gitee.com/hxc8/images0/raw/master/img/202407172109303.jpg)