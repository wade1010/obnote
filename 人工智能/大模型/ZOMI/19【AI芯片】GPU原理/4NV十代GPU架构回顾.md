 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107207.jpg)

主要聚焦下面几个

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107512.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107875.jpg)

上图，从这个版本开始取消SP这个概念，引入CUDA Core的概念。

因为计算核心太多了，所以这里面L2 cache会放在中间，数据可以快速的传输到上面的CUDA core还有下面的CUDA core。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107316.jpg)

上图，每个SM有32个CUDA core。

中间的部分，首先计算指令或者线程会给到CUDA core里面去真正的执行，而这里执行可以选择FP unit。这里面具体的是指FP32去执行，也可以选择int unit去执行。但这里面执行不能是并行的，在CUDA core里面只能选择FP unit去执行，或者选择int unit去执行。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107576.jpg)

有了具体的硬件，肯定会有对应的软件，这里对应的软件就是CUDA，CUDA里面就分层，之前提到分成线程、block（线程块）还有grid（网格）三个层次。每个层次都有对应不同的硬件。例如thread，它可以共享局部的memory，而线程块用的是shared memory共享内存，最后到grid也是SM之间可以共享全局内存

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107864.jpg)

这里面SM改成SMX，但是意义变化不大。硬件上拥有了双精度运算单元，也就是从kepler架构开始，英伟达GPU慢慢地进入到了hpc这个领域，就是高性能计算机这个领域。例如我国的太湖之光、天河这种。

还提出来GPU direct ，传统的GPU数据处理，需要多次经过cpu的内存拷贝，为了降低内存的延迟，还有数据重新搬运的问题，就出现了GPU direct，绕过了CPU或者host里面的system memory，不再必须跟CPU里面的内存进行交互，而是直接通过GPU跟GPU之间进行直接的数据交互，进一步提升了数据的处理和数据的带宽。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107375.jpg)

高达192个核心，整体的核心多了非常多，它的并行处理能力也是多了很多很多。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107366.jpg)

SMX又改回了SM，而整体核心数有变回了128个，英伟达发现核心数没必要太多，但是线程数可以超配可以更多。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107786.jpg)

SM进行了一个精简，每个SM包含的内容也是越来越少。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172107346.jpg)

总体来说，因为纳米的制程提升了，所以他里面的SM也会增加。单个SM里面就有64个FP32的CUDA core,这里面CUDA core非常多，相比其maxwell里面的128个CUDA core ,还有kepler架构里面的192个，确实Pascal架构里面的CUDA core少了很多，这里面分开两个区域，一个是左边的区域，一个是右边的区域，每个区域有32个CUDA core

为什么把CUDA core分成两块呢？**是因为这里register file，就是寄存器的数量保持不变，分开两个，这样就可以保证CUDA core，每个执行具体的线程，可以使用更多的寄存器。单个SM可以并发的去执行更多的线程、更多的warp、更多的block，进一步加强了英伟达GPU的并行处理能力**

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108461.jpg)

首次实现了单个节点里面GPU可以进行数据的互联，减少了数据传输的延迟，也减少了数据之间的大量的通过pcie回传到CPU的内存里面，进行重复的搬运性的工作。实现了整个网络的拓扑互联。这个是非常非常重要的。

带宽成为分布式训练系统里面的主要瓶颈。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108668.jpg)

1、将CUDA core 进行拆分（上图第一个），把FPU和ALU拆分，这一代开始取消CUDA core的概念，也就是以后没有CUDA core这个整体的硬件的概念，但是CUDAcore这个软件的概念，都可以保留下来。这样的好处就可以实现一条指令可以同时执行不同的计算。

2、提出了独立的线程调度（上图第四个），改进了整个SIMT的模型，也就是单指令多线程的架构，使得每个线程都有自己独立的programming counter，就是独立的PC，还有自己的stack。

3、针对AI提出来的Tensor core第一代的张量核心，针对深度学习，提出了专门针对卷积或者矩阵乘进行计算加速。

4、MPS（上图3）多进程服务更好的去适配到云厂商里面，或者多用户的处理和多用户的排队。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108253.jpg)

SM 里面的内容比之前多了很多，因为把CUDA core 拆分出来了。一个SM里面就有4个warp scheduler 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108893.jpg)

这里面把FP32和INT两种运算单元独立出来，出现在整个流水线里面，就是的SM可以同时执行FP16，同时执行INT、同时执行FP32、同时还有tensor core，使得吞吐进一步的增加，每个cycle都可以同时执行上面的这些指令。也就是每个时钟周期可以执行的数量更多了，可以执行的计算量更大了。

解释：

同时执行多个指令类型的好处是可以充分利用GPU的计算资源，提高计算的并行度和效率。例如，某个时钟周期内，SM可以同时执行FP32的浮点计算、INT的整数计算以及Tensor Core的特殊计算，从而在相同的时间内完成更多的计算任务。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108295.jpg)

**在AI里面最常见的就是卷积或者矩阵乘的方式，其实在之前的GPU里面需要编码成FMA(Fused-Multiply-Add 融合乘加运算)把乘加运算合并起来，而这个时候其实硬件层面，需要把数据来回的搬运，从寄存器搬到ALU执行一个乘法，然后再从寄存器搬到ALU执行一个加法，然后再返回寄存器里面，整个数据是来回的搬运的。**

**现在一个tensor core一个执行就自行4X4X4的一个GEMM，也就是64个FMA，极大地减少了系统内存的开销、硬件的开销。**

**虽然这里面的矩阵数是FP16，但是输出可以是FP32，相当于就提供了，64个FP32的ALU的计算能力，能耗上也是非常的具有优势，一个时钟周期内可以执行更多的矩阵的运算，**

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108558.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172108016.jpg)