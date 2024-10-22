一、什么是UIO

UIO，即Userspace I/O内核驱动，它在Linux kernel的世界比较小众，主要服务于一些定制设备。UIO负责将中断和设备内存暴露给用户空间，然后再由UIO用户态驱动实现具体的业务。

二、为什么需要UIO

硬件设备可以按照不同维度进行分类，比如从功能维度而言，可分为网络设备、块设备等。现实中还存在一些设备无法划分到里头，比如I/O卡或定制的FPGA，而这些设备驱动主要是以字符驱动方式进行开发，必须编写一个完整的内核驱动，而且还得一直维护这些代码。如果把这种设备的驱动放入Linux内核中，不但增大了内核的负担，也没人愿意花费大量精力去维护。

假如采取UIO驱动方式，则将简单很多。我们不仅可以利用所有用户空间的应用程序开发工具和库，而且当内核发生变化时，只需更改UIO框架与内核程序交互的接口即可，不需要更改UIO框架的驱动。除此之外，UIO可以使用C++、Java等高级语言进行开发（内核驱动仅能使用C语言和汇编代码），而且我们还能够参考内核驱动已实现的代码，极大提高我们的开发效率。最后还有一个优势就是，调测用户态程序比内核态程序简单得多，即便用户空间驱动程序挂了，也不会影响系统的正常运行。

三、UIO的工作原理

设备驱动主要完成2件事：处理设备产生的硬中断和存取设备内存。硬中断处理必须在内核空间进行，而设备内存的存取可以在用户空间进行。

UIO框架分为2部分，内核空间驱动和用户空间驱动，内核部分主要实现硬件寄存器的内存映射及读写操作，而用户空间部分负责将UIO设备的uio_mem映射到本地，实现用户空间程序能够访问硬件设备寄存器。UIO驱动模型请参考图1所示。接下来将详细介绍UIO内核部分和用户空间部分的实现流程。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001429.jpg)

**1、UIO内核部分**

***UIO内核驱动***做的事情相对来说比较少，***主要的任务是分配和记录设备所需的资源***（使能PCI设备、申请资源、读取并记录配置信息）、***注册UIO设备***（uio_register_device()）***以及实现硬中断处理函数。***

2、UIO用户空间部分

用户空间驱动主要完成2个关键任务，响应硬件中断和从存取设备内存。下面将使用理论 + 代码对用户空间驱动的能力进行介绍。

响应硬中断通常有2种处理方式，1）调用read()，阻塞/dev/uioX，当设备产生中断时，read()操作立即返回；2）调用poll()，使用select()等待中断发生（select()有一个超时参数可用来实现有限时间内等待中断）。下面用一段代码说明如何完成硬中断响应处理（阻塞/dev/uio0，调用read()处理硬中断信号）。

对于如何存取设备内存呢？我们可以通过读写/sys/class/uioX下的各个文件（注册的UIO设备在该目录下），完成对设备内存的读写。比如UIO设备为uio0，那么映射的设备内存将在/sys/class/uio/uio0/maps下，对该文件的读写就是对设备内存的读写。下面用一段代码说明如何存取设备内存（将设备信息mmap到用户空间，用户空间程序便可直接操作设备内存空间）