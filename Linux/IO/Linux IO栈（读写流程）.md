IO 栈概述

![](D:/download/youdaonote-pull-master/data/Technology/Linux/IO/images/WEBRESOURCE1d187e07628fbde3414303e90aac94f9截图.png)

1 应用程序通过系统调用访问文件（无论是块设备文件，还是各种文件系统中的文件）。可以通过open系统调用，也可以通过memory map的方式调用来打开文件。

2 Linux内核收到系统调用的软中断，通过参数检查后，会调用虚拟文件系统（Virtual File System，VFS），虚拟文件系统会根据信息把相应的处理交给具体的文件系统，如ext2/3/4等文件系统，接着相应的文件I/O命令会转化成bio命令进入通用的块设备层，把针对文件的基于offset的读/写转化成基于逻辑区块地址（Logical Block Address，LBA）的读/写，并最终翻译成每个设备对应的可识别的地址，通过Linux的设备驱动对物理设备，如硬盘驱动器（Harddisk Drive，HDD）或固态硬盘进行相关的读/写。

3 用户态文件系统的管理。Linux文件系统的实现都是在内核进行的，但是用户态也有一些管理机制可以对块设备文件进行相应的管理。例如，使用parted命令进行分区管理，使用mkfs工具进行文件系统的管理，使用逻辑卷管理器（Logical Volume Manager，LVM）命令把一个或多个磁盘的分区进行逻辑上的集合，然后对磁盘上的空间进行动态管理。

4 当然在用户态也有一些用户态文件系统的实现，但是一般这样的系统性能不是太高，因为文件系统最终是建立在实际的物理存储设备上的，且这些物理设备的驱动是在内核态实现的。那么即使文件系统放在用户态，I/O的读和写也还是需要放到内核态去完成的。除非相应的设备驱动也被放到用户态，形成一套完整的用户态I/O栈的解决方案，就可以降低I/O栈的深度。另外采用一些无锁化的并行机制，就可以提高I/O的性能。例如，由英特尔开源的SPDK（Storage Performance Development Kit）软件库，就可以利用用户态的NVMe SSD（Non-Volatile Memory express）驱动，从而加速那些使用NVMe SSD的应用，如iSCSI Target或NVMe-oF Target等。

linux IO 存储栈分为7层:

VFS 虚拟文件层: 在各个具体的文件系统上建立一个抽象层，屏蔽不同文件系统的差异。

PageCache 层: 为了缓解内核与磁盘速度的巨大差异。

映射层 Mapping Layer: 内核必须从块设备上读取数据，Mapping layer 要确定在物理设备上的位置。

通用块层: 通用块层处理来自系统其他组件发出的块设备请求。包含了块设备操作的一些通用函数和数据结构。

IO 调度层： IO 调度层主要是为了减少磁盘IO 的次数，增大磁盘整体的吞吐量，队列中多个bio 进行排序和合并。

块设备驱动层: 每一类设备都有其驱动程序，负责设备的读写。

物理设备层: 物理设备层有 HDD,SSD，Nvme 等磁盘设备。

PageCache 层： 两种策略: write back : 写入PageCache 便返回，不等数据落盘。

write through: 同步等待数据落盘。

读流程

（1）系统调用read（）会触发相应的VFS（Virtual Filesystem Switch）函数，传递的参数 有文件描述符和文件偏移量。

（2）VFS确定请求的数据是否已经在内存缓冲区中；若数据不在内存中，确定如何执行读 操作。

（3）假设内核必须从块设备上读取数据，这样内核就必须确定数据在物理设备上的位置。 这由映射层（Mapping Layer）来完成。

（4）此时内核通过通用块设备层（Generic Block Layer）在块设备上执行读操作，启动I/O 操作，传输请求的数据。

（5）在通用块设备层之下是I/O调度层（I/O Scheduler Layer），根据内核的调度策略，对 等待的I/O等待队列排序。

（6）最后，块设备驱动（Block Device Driver）通过向磁盘控制器发送相应的命令，执行 真正的数据传输。

写流程

write()—>sys_write()—>vfs_write()—>通用块层—>IO调度层—>块设备驱动层—>块设备

块设备

系统中能够随机访问固定大小数据片（chunk）的设备称为块设备，这些数据片就称作 块。最常见的块设备是硬盘，除此之外，还有CD-ROM驱动器和SSD等。它们通常安装文 件系统的方式使用。

————————————————

版权声明：本文为CSDN博主「bosh_rong」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/weixin_39802680/article/details/117707098](https://blog.csdn.net/weixin_39802680/article/details/117707098)