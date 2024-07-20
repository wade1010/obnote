![](https://gitee.com/hxc8/images0/raw/master/img/202407172058587.jpg)

首先我们会使用CUDA来驱动我们的整个硬件，真正去把我们计算的内容执行起来，那这个时候CUDA就写了非常多的线程，而gpu会把实际的线程的工作分配给每个gpc里面的SM进行真正的执行（也就是上图白色的那条线）。那这个时候每个cuda core tensor core,会利用HBM的带宽，也就是显存保存的内容，里面的数据进行计算，但是HBM是一个高带宽的memory，他不能够直接放在我们的SM旁边进行计算，还是有一定的距离的，于是HBM就会把数据搬运到L2的cache里面， L2的cache会直接把数据搬运到SM旁边的寄存器组里面真正执行计算。所以这里面就出现了两级的数据搬运，从HBM到L2 cache,L2 Cache到L1 Cache,L1 Cache可能会搬运到寄存器里面。

假设数据量非常大的时候，我希望通过一个CPU挂两个GPU同时去处理非常多的并行的数据的时候，也就是如下图

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058845.jpg)

这个时候GPU跟GPU之间数据的交互就严重依赖CPU进行一个分配和调度，而CPU就会把两个GPU通过pcie，两个pcie的插槽进行数据的传输，而这个时候pcie的带宽就约束了GPU跟GPU之间互联的速率。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058159.jpg)

后来使用了NVLink，GPC就可以访问卡间的HBM，也就是通过上图下面绿色的这一个线，有多条nvlink从GPU0到GPU1里面的HBM数据进行访问，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058465.jpg)

后来GPU服务器出现后，一台机器的CPU可能会挂1-2个甚至到4个，可以使多个GPU之间进行互联互通，单个GPU的驱动进程就可以控制非常多的其它的GPU进行工作，假设写了一个CUDA在GPU8里面去运行，实际上可以把任务发到GPU9、GPU10、GPU11、GPU1、GPU2、GPU3里面同时进行并行的计算，这个时候因为有了nvlink，hbm的内存可以不受到其它进程的方式进行互相的访问，而整个xbar作为调节器进行独立的演进，提供更高的带宽，这里面nvlink xbar就是我们nv-switch的原型。通过提供更多的nvlink的对外的链路的接口，去让我们更多的GPU连接在一起。

### 2、NV-Switch的出现

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058690.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058673.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058043.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058284.jpg)

 DGX-2  。nvlink的第2代，也就是对应nv-swich的第一代。

在第二代的nvlink里面的一款GPU，里面支持6路的nvlink，所以一台主机里面可以挂八台GPU，但是每台GPU里面只能支持6路的nvlink，因此我们可以挂6个nv-switch，nv-switch跟nv-switch之间进行一个互联互通。因此可以把8个GPU里面的同时互联起来。通过这种方式提升我们互联的带宽。这个时候已经没有了像左边第一代的那种拓扑。而是变成了一个互联互通。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058477.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058752.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058163.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172058591.jpg)

 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059962.jpg)

一个nvswitch有18个nvlink，所以上图从nvlink0到nvlin17。实际上在控制的时候，只控制一款GPU就可以实现多块GPU之间进行并行的计算，是因为数据包在多块NVSwitch里面不断的去传输，而这里传输控制的逻辑就是刚才讲到的逻辑控制电路，它是用来控制和分发数据包的。

每个port，可以做一些路由的分发、包的分类和传输、转换、错误的校正。更多的是对数据进行处理

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059081.jpg)

结合整个GPU看下nvswitch和GPU之间的交互方式，既然要对数据进行传输和分发，就需要知道数据的地址，实际上获取的是物理地址，这个物理地址实际上在程序员控制的时候是不知道的，我们看到的都是虚拟地址，所以虚拟地址和物理地址的转换，是由计算单元（GPC或者SM里面）进行一个完成的。

nvlink最后实际传输的就是我们对应的物理地址，而非虚拟地址，通过这种方式才能够更快的去索引到我们对应的数据到底放在哪里。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059125.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059085.jpg)

8个GPU最少可以通过3个nvswitch进行构建，因为v100架构里面nvlink的链路一共有6条，而每个nvswitch对外最大能接受18个。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059631.jpg)

TSMC 4N process（采用最先进的台积电的TSMC 4 纳米 制程工艺）

而且引入了sharp的加速

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059231.jpg)

上图右边，就是新的nvlink模块，一共有64个nvlink的对外连接，所以英伟达新一代dgx服务器，一块nvswitch就能够解决8台服务器问题了（是不是应该是8个GPU？）

### DGX服务器

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059940.jpg)

NV-Switch和PCIe-Switch是两种不同的交换技术，用于连接和管理计算机系统中的多个设备。它们具有一些异同之处：

1. 功能：NV-Switch是由NVIDIA开发的专有交换技术，用于连接多个GPU（图形处理单元），以实现高性能计算和数据传输。它通过高速通道连接多个GPU，以实现低延迟和高带宽的数据交换。PCIe-Switch是基于PCI Express（PCIe）标准的交换技术，用于连接和管理多个PCIe设备，例如GPU、网卡、存储设备等。

1. 性能：NV-Switch提供了极高的带宽和低延迟，适用于需要大规模数据并行处理的应用场景，如机器学习和深度学习。它可以实现高效的GPU间通信和协同计算。PCIe-Switch的性能取决于所使用的PCIe版本和配置，通常用于连接和管理多个设备，但在带宽和延迟方面可能不如NV-Switch。

1. 支持的设备：NV-Switch主要用于连接和管理多个GPU，以构建GPU集群或超级计算机。它被广泛应用于高性能计算和人工智能等领域。PCIe-Switch则可以连接和管理各种类型的PCIe设备，包括GPU、网卡、存储设备和其他扩展卡。

1. 厂商支持：NV-Switch是由NVIDIA开发和推广的技术，主要用于其自家的GPU产品线。PCIe-Switch是一种通用的交换技术，由多家厂商提供支持和产品。

需要注意的是，NV-Switch和PCIe-Switch在性能、功能和应用领域上有所不同。选择哪种技术取决于具体的需求和应用场景。对于需要高性能计算和大规模数据并行处理的应用，NV-Switch可能是更好的选择。而对于普通的设备连接和管理需求，PCIe-Switch可能更适合。

NV-Switch和NVLink是两种相关但不同的技术，都是由NVIDIA开发的。

1. NV-Switch：NV-Switch是NVIDIA开发的专有交换技术，用于连接和管理多个GPU（图形处理单元）。它通过高速通道连接多个GPU，在GPU之间实现低延迟和高带宽的数据交换。NV-Switch主要用于构建GPU集群或超级计算机，用于高性能计算和人工智能等领域。

1. NVLink：NVLink是NVIDIA开发的高速互连技术，用于直接连接多个GPU，实现高速的GPU间通信。NVLink提供了比PCIe更高的带宽和更低的延迟，提升了多个GPU之间的数据传输效率。NVLink可以在支持的NVIDIA GPU之间建立点对点的直接连接，以实现更高效的并行计算和数据传输。

关于两者的关系，可以这样理解：NV-Switch是一种交换技术，用于连接和管理多个GPU，而NVLink是一种互连技术，用于直接连接多个GPU。NV-Switch通常使用NVLink作为其底层连接技术，以实现高性能的GPU间通信和协同计算。通过使用NVLink作为连接介质，NV-Switch可以提供更高的带宽和更低的延迟，以满足高性能计算和数据处理的需求。

总结起来，NV-Switch和NVLink是NVIDIA提供的两种技术，它们在连接和管理多个GPU以及实现高性能计算方面发挥着重要作用。