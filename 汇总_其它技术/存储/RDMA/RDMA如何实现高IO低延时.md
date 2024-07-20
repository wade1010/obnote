需要配置支持RDMA技术的网卡，最具代表性的是CX-5、CX-6

 1.零拷贝指的是不需要内核和应用层进行拷贝数据，从而降低内核态和用户态切换

2.kernal by pass 指的是传统网络数据包需要使用linux内核协议栈进行层层解封装，并且需要大量中断给cpu造成很大的负担，而kernal by pass可以绕过linux协议栈让用户层直接来解封装，常见的解决方案如DPDK

zerocopy物理上说绕开Cpu不太准确，应该是数据不经过CPU的运算处理，他还是得借用CPU的总线来进行IO。所以不同BUS设计CPU进行DMAIO的速度也会有差异。

也不是完全不进行内核态和用户态的切换，sendfile的IO是必须的，但这个过程和时机可以优化，SGDMA现在最新的算法是两次状态切换。我司在RMDA的应用是DBHA。

RDMA技术概述：

英文全称 remote direct memory access,意思是远程直接内存访问，这种技术是一种最早用于高性能计算领域的网络通讯协议，目前已在数据中心当中得到广泛应用。

RDMA允许用户程序绕过操作系统内核CPU，直接和网卡交互进行网络通信，从而提供搞宽带和极小时延。

DMA工作示意图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001733.jpg)

上图红框标出来的，当两个应用程序实现内存交互的时候，一般通过CPU来控制。

当你有DMA这个进程的时候，不用通过CPU，而是通过DMA Engine来实现两个程序之间的内存交互

RDMA工作示意图

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001386.jpg)

 

RDMA优势分析

传统TCP/IP在数据包处理时，要经过OS及其它软件层，需要占用大量的CPU资源和内存总线带宽，数据在系统内存、CPU缓存和网络控制器缓存之间来回进行复制移动，给服务器的CPU和内存造成了沉重的负担。尤其是网络带宽、处理器速度与内存带宽三者的严重“不匹配性”，更加剧了网络延迟效应。

RDMA实际上是一种智能网卡与软件架构充分优化的远端内存直接高速访问技术，通过将RDMA协议固化于硬件（即网卡）上，以及支持Zero-Copy和Kernel Bypass 这两种途径来达到其高性能的远程直接数据存取的目标。

 1.零拷贝指的是不需要内核和应用层进行拷贝数据，从而降低内核态和用户态切换

2.kernal by pass 指的是传统网络数据包需要使用linux内核协议栈进行层层解封装，并且需要大量中断给cpu造成很大的负担，而kernal by pass可以绕过linux协议栈让用户层直接来解封装，常见的解决方案如DPDK

zerocopy物理上说绕开Cpu不太准确，应该是数据不经过CPU的运算处理，他还是得借用CPU的总线来进行IO。所以不同BUS设计CPU进行DMAIO的速度也会有差异。

也不是完全不进行内核态和用户态的切换，sendfile的IO是必须的，但这个过程和时机可以优化，SGDMA现在最新的算法是两次状态切换。我司在RMDA的应用是DBHA。

![](D:/download/youdaonote-pull-master/data/Technology/存储/RDMA/images/WEBRESOURCE93bc155fb4ad423c9d8d5c6d889ed2f5截图.png)

上图，设备的CPU除了在连接建立、注册调用等之外，在这个RDMA数据传输过程中并不提供服务，因此没有带来任何负载。

RDMA三种网络协议

RDMA本身指的是一种技术，具体协议层面，包含Infiniband(IB，无限带宽技术),RDMA over Convergred Ethernet(RoCE)和internet Wide Area RDMA Protocol(iWARP).三种协议都符合RDMA标准，使用相同的上层接口，在不同层次上有一些差别。

但是InfiniBand网络对于大部分人来说像珠穆朗玛峰一样高不可攀,InfiniBand网络有多厉害？2020年就已经达到了1.2Tb/s速率，但是这个网络就是超级贵！毕竟需要专用的网卡和交换机设备。

SOCKET:

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001385.jpg)

RDMA:

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001158.jpg)

RDMA是一种高带宽、低延迟、低CPU消耗的网络互联技术

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001711.jpg)