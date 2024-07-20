# 一、认识NVME和NVME-of

NVMe全称是Nonvolatile Memory Express(非易失性内存标准)，NVMe是一种基于性能并从头开始创建新存储协议，简化了协议复杂性，显著提高了SSD的读写性能，充分利用PCIe通道的低延时以及并行性，通过降低协议交互时延，增加协议并发能力，并且精简操作系统协议堆栈。

目前主流的PCIe已经升级到了PCIe4.0,NVMe也是和PCIe一样，技术一直在迭代更新。 

目前PCIe4.0单块NVMe的盘它是占用X4这种PCIe通道，单盘性能理论可以达到，读7GB/s 写3GB/s

 

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001197.jpg)

上图比较形象的展示，底层是一个PCIe接口，上面是一个ssd的卡，直接插在PCIe上，就可以通过PCIe通道直接和CPU访问，这一点和普通的SATA、固态不同，它们要通过南桥芯片，这种板载的控制器和CPU互联或者通过各种卡，它中间相对来说要有一些瓶颈，而NVMe的盘直接通过PCIe这种链路，这种通道直接访问CPU，所以这一块NVMe是相对精简的。

NVME-of的相关概念

NVMe-oF(NVMe over Fabrics)扩展了NVMe规范在PCIe总线上的实现，把NVMe映射到多种物理网络传输通道，实现高性能的存储设备网络共享访问。

NVMe-oF 定义了使用各种通用的传输层协议来实现NVMe功能的方式。

支持把NVMe映射到多个Fabrics传输选项，主要包括FC、InfiniBand、RoCE v2、iWARP和TCP。

 NVMe-of主要是实现了跨设备的高性能访问，NVMe是实现单台设备内部的性能提升，而NVMe-of是将这种高性能扩展到了多个设备之间，它是一个存储协议。

它可以和FC、ISCSI去做对应和对比，FC和ISCSI组成这种FCSAN或者IPSAN 实现了服务器到存储之间的网络访问。而NVMe-of同样的，它是基于NVMe的这种存储，像全闪存储啊，都可以基于NVMe-of协议来实现对前端服务器呢提供访问。

这样的特点呢基于NVMe盘和PCIe总线，它的性能非常高。

 

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001460.jpg)

图片右边 JBOD的框可以理解为全闪的存储，后端是NVMe的SSD，NVMe的盘是通过PCIe进行扩展的。

前端的话是通过智能网卡对前端的服务器提供服务，中间链路可以通过Ethernet 交换机 or InfiniBand交换机 or FC交换机，去提供一个数据传输。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001794.jpg)

上图，上端是NVMe的盘，对于主机而言通过内部PCIe Switch进行互联的

下端，NVMe-oF是存储网络，它实现的是前端的服务器（下端Network左边部分）到后端存储设备（下端Network右边部分）这样的一个互联，中间的网络我们称之为NVMe over Fabrics，简称为NVMe-oF

# 二、技术优势

 它对标的是FC或者ISCSI

NVMe-oF协议支持NVMe主机启动器与存储系统之间同时存在多条路径，能够一次从许多主机和存储子系统发送和接收命令（相当于1对多），与RDMA、FC或TCP一起使用，可以形成完整的端到端的NVMe传输方案。

NVMe存储解决方案在显著提升性能的同时，通过NVMe实现极低时延

特点：

- 网络延迟低，协议层做了优化（因为NVMe协议本身就是低延时），但是还要基于不同的链路，像IB网络、IP网络以及FC网络，链路不同，网络延时可能也不同。

- 能够处理并行请求（这个也是基于NVMe协议的天然属性）

- 提高存储性能，例如目前的全闪阵列，要想发挥全闪的性能，肯定要基于NVMe-oF技术

- 减少服务器端OS存储堆栈的长度，基于RDMA网络直接就可以通过智能网卡来进行访问，减少了CPU的负载，来实现效率的提升。

- 支持NVMe主机启动器与存储系统之间同时存在多条路径。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001340.jpg)

上图，VNM子系统可以理解为存储系统，NVMe主机可以理解为服务器，中间是各种互联技术。

# 三、三种NVMe-oF方案

1、NVMe-oF over RDMA

NVMe-oF over RDMA(NVMe/RDMA)使用TCP传输协议在IP网络上传递数据，其中使用较多的是RoCE、IB和iWARP。RDMA可以绕过CPU直接读取远端内存，直接访问远端内存来实现存储的访问。

特点：

- 提供了低延迟、低抖动和低CPU使用率的传输层协议

- 最大限度利用硬件加速，避免软件协议栈的开销。

- RDMA定义了丰富可异步访问的接口机制，大幅提高IO性能。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001146.jpg)

2、FC-NVMe（NVMe oF using Fibre Channel）

NVMe over Fibre Chanel 使用了16GB FC或32GB FC的HBA和SAN交换机，可以将NVMe协议封在FC架构中，是SAN基础设施追加解决方案

条件如下：

	- 服务器支持HBA卡：支持16Gbps或32 Gbps FC

	- FC网络交换机支持FC-NVMe协议

3、NVMe over TCP/IP (NVMe/TCP)

用NVMe-oF和TCP协议在以太网传输数据，与RDMA和FC相比，是一种更便宜、更灵活的替代方案。

NVM Express在2019年发布了NVMe-oF 1.1规范，增加了对TCP传输绑定的支持。基于TCP的NVMe是的通过标准的以太网网络使用NVMe-oF成为可能，同时无需进行配置更改或任何特殊设备。

fabri指的是多个服务器的数据互联结构，许多公司已经给服务器换上了NVMe盘，但传统NVMe最大的又是，只能惠及插着硬盘的本地主机，而非隔着网络的其它机器，这就是NVMe-oF的改进点，它允许NVMe通过网络连接运行，并不局限于某台服务器的内部数据总线