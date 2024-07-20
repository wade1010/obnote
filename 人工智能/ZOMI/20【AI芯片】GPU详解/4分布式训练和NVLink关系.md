![](https://gitee.com/hxc8/images0/raw/master/img/202407172100012.jpg)

大模型的好处：

1、引入自监督学习方法

2、木星参数规模越来越大，精度越来越高。

3、解决了模型碎片话，提供预训练方案。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100377.jpg)

模型越来越大，没办法把以个单独的模型放在一款芯片或者一款GPU NPU里面，于是就出现了各种各样的并行策略，整体上说，不管哪种并行，它主要是对模型进行切分，有横着切有竖着切，那横着切就是把模型的层数单独的切出来放在不同的机器，竖着切就是把模型像三个并行就是竖着切，把张量切出来放在不同的芯片上面或者不同的GPU上面，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100908.jpg)

有了这些基础的并行算法之后，整个系统确实要支持这种计算节点的跨机。原来只是一个网络模型，现在要要把这一个网络模型，分布在不同的机器上，这里面就涉及到整体的系统怎么去通讯，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100120.jpg)

现在节点越来越多，分开不同的权重，所以参数量也放在不同的模型里面，不同的去进行一个交互，这种方式就是计算图，整个计算图跨节点同步数据进行交互，这种就是分布式训练。

上面都是软件层面做了一些相关的策略和算法，接下来看下硬件层面，在通讯硬件需要有哪些不一样的东西。首先是机器内的通讯，简单说就是一款机器里面有多款GPU或者AI加速芯片这种，就是机器内的通讯，而机器间的通讯主要指不同的机器之间，不同的服务器主机之间如何进行通讯。在机器间通讯是通过共享内存的方式，也可以通过PCIe，NVLink（直连模式）把GPU跟GPU之间直接互相连起来。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100337.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100474.jpg)

如上图，机器之间通讯，上面是一排机柜，机柜之间可能有一台华为阿特拉斯，下面也有一台华为阿特拉斯，一个机柜可以在8台服务器，机器间的通信，有TCP/IP、RDMA，现在经常谈到的IB网络是RDMA的其中一种。Rocky也是RDMA网络其中一种。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100911.jpg)

硬件要实现通信，这里面就离不开提供集合通讯的一些库了，集合通讯最常用的MPI，在cpu上面用的非常多，最英伟达GPU上用的最多的就是NCCL，华为的HCCL，

Communication-Connected-layer

通过NCCL这个库，英伟达就使能了NVSwitch或者NVLink，把不同的GPU与GPU之间互联起来，而是通过算法层面对外提供对应的api。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101074.jpg)

### 2、NVLink&NVSwitch的发展

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101198.jpg)

现在最新一代使用nvlink是H100，还有之前IBM的power系列。

NVSwitch可以理解为具体的模块芯片，而NVLink就是一种总线和通讯的协议。

NVLINK和NVSWITCH具体差别可以看如下地址的视频

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101321.jpg)

NV-Link是一种高速互连技术，旨在提供低延迟、高带宽的连接方式，用于连接多个 NVIDIA GPU 之间或连接 GPU 与主机之间。NV-Link 可以直接连接 GPU 之间的内存，实现高速的数据交换与共享，从而加快多个 GPU 协同计算的性能。NV-Link 还支持内存一致性，使得多个 GPU 可以共享内存数据并进行高效的协同计算。

NV-Switch则是一种专用的交换芯片，用于构建高性能的 GPU 互连网络。NV-Switch 能够将多个 NV-Link 连接聚合在一起，形成一个高带宽、低延迟的互连网络。它使得多个 GPU 可以直接通信，共享数据和执行协同计算，从而提供更高的整体性能和扩展性。

简而言之，NV-Link 是一种互连技术，用于直接连接多个 NVIDIA GPU 之间或连接 GPU 与主机之间，提供高速的数据交换和共享。而 NV-Switch 是一种交换芯片，用于构建高性能的 GPU 互连网络，通过聚合多个 NV-Link 连接，实现多 GPU 的直接通信和协同计算。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101231.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101211.jpg)

 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101364.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101795.jpg)

NVSwitch就是NVLink具体的承载的芯片模组。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101078.jpg)

一开始从Pascal架构，在DGX1里面只有一个CubeMesh，也就是没有NVSwitch的，只有NVLink,每一款GPU里面有4路，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101922.jpg)

只能跟4条链路进行互联

DGX2里面，每一款V100，就可以跟NVSwitch里面另外一款V100进行一个互联

到了DGX A100，NVSwitch模组经过一个更新，每一款A100可以跟任何一款A100进行一个互联的，这个时候对于硬件来说节省了非常多的链路（相比V100）

到了最新的DGX H100里面

![](https://gitee.com/hxc8/images0/raw/master/img/202407172101072.jpg)