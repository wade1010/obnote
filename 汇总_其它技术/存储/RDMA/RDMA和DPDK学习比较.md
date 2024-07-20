DPDK是网络层，硬件终端->放弃中断流程

DPDK是用户层通过设备映射获取数据包->进入用户层协议栈->逻辑层->业务层;

DPDK核心技术：

- 将协议栈从内核中搬到用户态，利用UIO技术直接将设备数据映射拷贝到用户态；

- 利用大页技术，降低TLB cache miss，提高TLB访问命中率（Translation Lookaside Buffer，专门用于改进虚拟地址到物理地址转换速度的缓存）

- 通过CPU亲和性，绑定网卡、线程固定到core，减少CPU切换开销

- 通过无锁队列，减少资源的竞争

DPDK的优点：

- 减少中断次数

- 减少内存拷贝次数

- 绕开Linux内核协议栈，用户获得协议栈的控制权，能够定制化协议栈以降低复杂度；

DPDK的缺点：

- 协议栈从内核中搬到用户态，增加了开发成本

- 低负荷服务器不适用，会造成CPU空转

RDMA

网卡硬件收发包并进行协议栈封装和解析，然后将数据存放到指定内存地址，而不需要CPU干预。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001569.jpg)

RDMA核心技术：

协议栈卸载到硬件（协议栈硬件offload）

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001726.jpg)

RDMA的优点：

- 协议栈offloadd，解放CPU

- 减少了中断和内存拷贝，降低时延

- 高带宽

RDMA的缺点：

- 特定网卡才支持，成本开销相对较大

- RDMA提供了完全不同于传统网络编程的API，一般需要对现有APP进行改造，引入额外的开发成本。

RDMA和DPDK的相同点：

- 两者都是kernel bypass技术，可以减少中断次数，消除内核态到用户态的内存拷贝

RDMA和DPDK的不同点：

- DPDK是将协议栈从内核态上移到用户态，而RDMA是将协议栈下沉到网卡硬件，DPDK仍然会消耗CPU资源；

- DPDK的并发度取决于CPU核数，而RDMA的收包速率完全取决于网卡硬件转发能力；

- DPDK在低负载场景下会造成CPU的无谓空转，RDMA不存在此问题；

- DPDK用户可以获得协议栈的控制权，可自主定制协议栈，RDMA则无法定制协议栈。

RDMA通过kernel-bypass和协议栈offload两大核心技术，实现了远高于传统TCP/IP的网络通信性能。尽管RDMA的性能要远好于TCP/IP，但目前RDMA的实际落地业务场景却寥寥无几，这其中制约RDMA技术大规模上线应用的主要原因有两点：

- 主流互联网公司普遍选择RoCE（RDMA over Converged Ethernet）作为RDMA部署方案，而RoCE本质上是RDMA over UDP，在网络上无法保证不丢包。因此RoCE部署方案需要额外的拥塞控制机制来保证底层的无损网络，如PFC、ECN等，这给大规模的上线部署带来挑战。而且目前各大厂商对硬件拥塞控制的支持均还不完善，存在兼容性问题。

- RDMA提供了完全不同于socket的编程接口，因此要想使用RDMA，需要对现有应用进行改造。而RDMA原生编程API（verbs/RDMA_CM）比较复杂，需要对RDMA技术有深入理解才能做好开发，学习成本较高。