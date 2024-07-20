https://developer.ibm.com/articles/j-zerocopy/



传统I/O方式



为了更好的理解零拷贝解决的问题，我们需要了解一下传统 I/O 方式存在的问题。



不过了解传统I/O前，我们了解下DMA。

在没有 DMA 技术前，I/O 的过程是这样的：

![](https://note.youdao.com/yws/res/63686/00BABF516FB2451C9B37452650473E74)



通过上图可以看到，整个数据的传输过程，都要需要 CPU 亲自参与搬运数据的过程，而且这个过程，CPU 是不能做其他事情的。

简单的搬运几个字符数据那没问题，但是如果我们用千兆网卡或者硬盘传输大量数据的时候，都用 CPU 来搬运的话，肯定忙不过来。

计算机科学家们发现了事情的严重性后，于是就发明了 DMA 技术，也就是直接内存访问（Direct Memory Access） 技术。

什么是 DMA 技术？简单理解就是，在进行 I/O 设备和内存的数据传输的时候，数据搬运的工作全部交给 DMA 控制器，而 CPU 不再参与任何与数据搬运相关的事情，这样 CPU 就可以去处理别的事务。

那使用 DMA 控制器进行数据传输的过程究竟是什么样的呢？下面我们来具体看看。

![](https://note.youdao.com/yws/res/63693/33E6DF51D6F34C43B62CFEFC749EE083)

具体过程：

- 用户进程调用 read 方法，向操作系统发出 I/O 请求，请求读取数据到自己的内存缓冲区中，进程进入阻塞状态；

- 操作系统收到请求后，进一步将 I/O 请求发送 DMA，然后让 CPU 执行其他任务；

- DMA 进一步将 I/O 请求发送给磁盘；

- 磁盘收到 DMA 的 I/O 请求，把数据从磁盘读取到磁盘控制器的缓冲区中，当磁盘控制器的缓冲区被读满后，向 DMA 发起中断信号，告知自己缓冲区已满；

- DMA 收到磁盘的信号，将磁盘控制器缓冲区中的数据拷贝到内核缓冲区中，此时不占用 CPU，CPU 可以执行其他任务；

- 当 DMA 读取了足够多的数据，就会发送中断信号给 CPU；

- CPU 收到 DMA 的信号，知道数据已经准备好，于是将数据从内核拷贝到用户空间，系统调用返回；

可以看到， 整个数据传输的过程，CPU 不再参与数据搬运的工作，而是全程由 DMA 完成，但是 CPU 在这个过程中也是必不可少的，因为传输什么数据，从哪里传输到哪里，都需要 CPU 来告诉 DMA 控制器。

早期 DMA 只存在在主板上，如今由于 I/O 设备越来越多，数据传输的需求也不尽相同，所以每个 I/O 设备里面都有自己的 DMA 控制器。

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/AD6FC33C77144A458441AE8CE90B6861image.png)





接下来就可以真正了解一下传统 I/O 方式存在的问题：



在 Linux 系统中，传统的访问方式是通过 write() 和 read() 两个系统调用实现的，

通过 read() 函数读取文件到到缓存区中，

然后通过 write() 方法把缓存中的数据输出到网络端口，

伪代码如下：

```javascript
read(file_fd, tmp_buf, len);
write(socket_fd, tmp_buf, len);
```

虽然就两行代码，但是这里面发生了不少的事情。

![](https://note.youdao.com/yws/res/63670/DDF89DE62133476C8416D8ABD6CB4E6F)

首先，期间共发生了 4 次用户态与内核态的上下文切换，因为发生了两次系统调用，一次是 read() ，一次是 write()，每次系统调用都得先从用户态切换到内核态，等内核完成任务后，再从内核态切换回用户态。

上下文切换到成本并不小，一次切换需要耗时几十纳秒到几微秒，虽然时间看上去很短，但是在高并发的场景下，这类时间容易被累积和放大，从而影响系统的性能。

其次，还发生了 4 次数据拷贝，其中两次是 DMA 的拷贝，另外两次则是通过 CPU 拷贝的，下面说一下这个过程：

第1次拷贝，把磁盘上的数据拷贝到操作系统内核的缓冲区里，这个拷贝的过程是通过 DMA 搬运的。

第2次拷贝，把内核缓冲区的数据拷贝到用户的缓冲区里，于是我们应用程序就可以使用这部分数据了，这个拷贝到过程是由 CPU 完成的。

第3次拷贝，把刚才拷贝到用户的缓冲区里的数据，再拷贝到内核的 socket 的缓冲区里，这个过程依然还是由 CPU 搬运的。

第4次拷贝，把内核的 socket 缓冲区里的数据，拷贝到网卡的缓冲区里，这个过程又是由 DMA 搬运的。



看到这个文件传输的过程，我们只是搬运一份数据，结果却搬运了 4 次，过多的数据拷贝无疑会消耗 CPU 资源，大大降低了系统性能。

这种简单又传统的文件传输方式，存在冗余的上文切换和数据拷贝，在高并发系统里是非常糟糕的，多了很多不必要的开销，会严重影响系统性能。

所以，要想提高文件传输的性能，就需要减少「用户态与内核态的上下文切换」和「内存拷贝」的次数。





# 零拷贝



在 Linux 中零拷贝技术主要有 3 个实现思路：用户态直接 I/O、减少数据拷贝次数以及写时复制技术。



- 用户态直接 I/O：应用程序可以直接访问硬件存储，操作系统内核只是辅助数据传输。这种方式依旧存在用户空间和内核空间的上下文切换，硬件上的数据直接拷贝至了用户空间，不经过内核空间。因此，直接 I/O 不存在内核空间缓冲区和用户空间缓冲区之间的数据拷贝。

- 减少数据拷贝次数：在数据传输过程中，避免数据在用户空间缓冲区和系统内核空间缓冲区之间的CPU拷贝，以及数据在系统内核空间内的CPU拷贝，这也是当前主流零拷贝技术的实现思路。

- 写时复制技术：写时复制指的是当多个进程共享同一块数据时，如果其中一个进程需要对这份数据进行修改，那么将其拷贝到自己的进程地址空间中，如果只是数据读取操作则不需要进行拷贝操作。



![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/F6514FB2085544F7BE1D2C31CE611BF8image.png)



1、用户态直接I/O

用户态直接 I/O 使得应用进程或运行在用户态（user space）下的库函数直接访问硬件设备，数据直接跨过内核进行传输，内核在数据传输过程除了进行必要的虚拟存储配置工作之外，不参与任何其他工作，这种方式能够直接绕过内核，极大提高了性能。

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/6D2B84E4131A4087BBB4465D03AB99A4v2-4cb0f465ebeb7ff0f5e31e8d3f790c80_1440w.jpg)

缺点：

- 这种方法只能适用于那些不需要内核缓冲区处理的应用程序，这些应用程序通常在进程地址空间有自己的数据缓存机制，称为自缓存应用程序，如数据库管理系统就是一个代表。

- 这种方法直接操作磁盘 I/O，由于 CPU 和磁盘 I/O 之间的执行时间差距，会造成资源的浪费，解决这个问题需要和异步 I/O 结合使用。



2、mmap + write

一种零拷贝方式是使用 mmap + write 代替原来的 read + write 方式，减少了 1 次 CPU 拷贝操作。mmap 是 Linux 提供的一种内存映射文件方法，即将一个进程的地址空间中的一段虚拟地址映射到磁盘文件地址，mmap + write 的伪代码如下：

```javascript
tmp_buf = mmap(file_fd, len);
write(socket_fd, tmp_buf, len);
```

使用 mmap 的目的是将内核中读缓冲区(read buffer)的地址与用户空间的缓冲区(user buffer)进行映射，从而实现内核缓冲区与应用程序内存的共享，省去了将数据从内核读缓冲区(read buffer)拷贝到用户缓冲区(user buffer)的过程，然而内核读缓冲区(read buffer)仍需将数据到内核写缓冲区(socket buffer)，大致的流程如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/0A5F0001471444A6BDA8D40D349A8ADB007S8ZIlgy1ggj9hkdcw0j30i50bv405.jpg)





基于 mmap + write 系统调用的零拷贝方式，整个拷贝过程会发生 4 次上下文切换，1 次 CPU 拷贝和 2 次 DMA 拷贝，用户程序读写数据的流程如下：

1. 用户进程通过 mmap() 函数向内核 (kernel) 发起系统调用，上下文从用户态 (user space) 切换为内核态(kernel space)；

1. 将用户进程的内核空间的读缓冲区 (read buffer) 与用户空间的缓存区 (user buffer) 进行内存地址映射；

1. CPU 利用 DMA 控制器将数据从主存或硬盘拷贝到内核空间 (kernel space) 的读缓冲区 (read buffer)；

1. 上下文从内核态 (kernel space) 切换回用户态 (user space)，mmap 系统调用执行返回；

1. 用户进程通过write() 函数向内核 (kernel) 发起系统调用，上下文从用户态 (user space) 切换为内核态(kernel space)；

1. CPU 将读缓冲区 (read buffer) 中的数据拷贝到的网络缓冲区 (socket buffer) ；

1. CPU 利用 DMA 控制器将数据从网络缓冲区 (socket buffer) 拷贝到网卡进行数据传输；

1. 上下文从内核态 (kernel space) 切换回用户态 (user space) ，write 系统调用执行返回；



缺点：

mmap 主要的用处是提高 I/O 性能，特别是针对大文件。对于小文件，内存映射文件反而会导致碎片空间的浪费，因为内存映射总是要对齐页边界，最小单位是 4 KB，一个 5 KB 的文件将会映射占用 8 KB 内存，也就会浪费 3 KB 内存。

另外 mmap 隐藏着一个陷阱，当使用 mmap 映射一个文件时，如果这个文件被另一个进程所截获，那么 write 系统调用会因为访问非法地址被 SIGBUS 信号终止，SIGBUS 默认会杀死进程并产生一个 coredump，如果服务器被这样终止那损失就可能不小。

解决这个问题通常使用文件的租借锁：首先为文件申请一个租借锁，当其他进程想要截断这个文件时，内核会发送一个实时的 RT_SIGNAL_LEASE 信号，告诉当前进程有进程在试图破坏文件，这样 write 在被 SIGBUS 杀死之前，会被中断，返回已经写入的字节数，并设置 errno 为 success。

通常的做法是在 mmap 之前加锁，操作完之后解锁。



3、sendfile

sendfile 系统调用在 Linux 内核版本 2.1 中被引入，目的是简化通过网络在两个通道之间进行的数据传输过程。sendfile 系统调用的引入，不仅减少了 CPU 拷贝的次数，还减少了上下文切换的次数，它的伪代码如下：

```javascript
sendfile(socket_fd, file_fd, len);
```



通过 sendfile 系统调用，数据可以直接在内核空间内部进行 I/O 传输，从而省去了数据在用户空间和内核空间之间的来回拷贝。与 mmap 内存映射方式不同的是， sendfile 调用中 I/O 数据对用户空间是完全不可见的。也就是说，这是一次完全意义上的数据传输过程。

![](https://note.youdao.com/yws/res/63765/4A5A9321BB3C431E8890D6C6AEDD5406)



基于 sendfile 系统调用的零拷贝方式，整个拷贝过程会发生 2 次上下文切换，1 次 CPU 拷贝和 2 次 DMA 拷贝，用户程序读写数据的流程如下：

1. 用户进程通过 sendfile() 函数向内核 (kernel) 发起系统调用，上下文从用户态 (user space) 切换为内核态(kernel space)。

1. CPU 利用 DMA 控制器将数据从主存或硬盘拷贝到内核空间 (kernel space) 的读缓冲区 (read buffer)。

1. CPU 将读缓冲区 (read buffer) 中的数据拷贝到的网络缓冲区 (socket buffer)。

1. CPU 利用 DMA 控制器将数据从网络缓冲区 (socket buffer) 拷贝到网卡进行数据传输。

1. 上下文从内核态 (kernel space) 切换回用户态 (user space)，sendfile 系统调用执行返回。



相比较于 mmap 内存映射的方式，sendfile 少了 2 次上下文切换，但是仍然有 1 次 CPU 拷贝操作。sendfile 存在的问题是用户程序不能对数据进行修改，而只是单纯地完成了一次数据传输过程。



缺点：

只能适用于那些不需要用户态处理的应用程序。



4、sendfile + DMA gather copy

常规 sendfile 还有一次内核态的拷贝操作，能不能也把这次拷贝给去掉呢？

答案就是这种 DMA 辅助的 sendfile。



从 Linux 内核 2.4 版本开始起，对于支持网卡支持 SG-DMA 技术的情况下， sendfile() 系统调用的过程发生了点变化，



PS:你可以在你的 Linux 系统通过下面这个命令，查看网卡是否支持 scatter-gather 特性：

```javascript
$ ethtool -k eth0 | grep scatter-gather
scatter-gather: on
```



具体过程如下：

第一步，通过 DMA 将磁盘上的数据拷贝到内核缓冲区里；

第二步，缓冲区描述符和数据长度传到 socket 缓冲区，这样网卡的 SG-DMA 控制器就可以直接将内核缓存中的数据拷贝到网卡的缓冲区里，此过程不需要将数据从操作系统内核缓冲区拷贝到 socket 缓冲区中，这样就减少了一次数据拷贝；

所以，这个过程之中，只进行了 2 次数据拷贝，如下图：

![](https://note.youdao.com/yws/res/63784/201B8ECBBE6F47C0A99F74601C96E88F)



整个拷贝过程会发生 2 次上下文切换、0 次 CPU 拷贝以及 2 次 DMA 拷贝，用户程序读写数据的流程如下：

1. 用户进程通过 sendfile()函数向内核 (kernel) 发起系统调用，上下文从用户态 (user space) 切换为内核态(kernel space)。

1. CPU 利用 DMA 控制器将数据从主存或硬盘拷贝到内核空间 (kernel space) 的读缓冲区 (read buffer)。

1. CPU 把读缓冲区 (read buffer) 的文件描述符(file descriptor)和数据长度拷贝到网络缓冲区(socket buffer)。

1. 基于已拷贝的文件描述符 (file descriptor) 和数据长度，CPU 利用 DMA 控制器的 gather/scatter 操作直接批量地将数据从内核的读缓冲区 (read buffer) 拷贝到网卡进行数据传输。

1. 上下文从内核态 (kernel space) 切换回用户态 (user space)，sendfile 系统调用执行返回。



sendfile + DMA gather copy 拷贝方式同样存在用户程序不能对数据进行修改的问题，而且本身需要硬件的支持，它只适用于将数据从文件拷贝到 socket 套接字上的传输过程。



5、splice

sendfile 只适用于将数据从文件拷贝到 socket 套接字上，同时需要硬件的支持，这也限定了它的使用范围。Linux 在 2.6.17 版本引入 splice 系统调用，不仅不需要硬件支持，还实现了两个文件描述符之间的数据零拷贝。splice 的伪代码如下：

```javascript
splice(fd_in, off_in, fd_out, off_out, len, flags);
```



splice 系统调用可以在内核空间的读缓冲区（read buffer）和网络缓冲区（socket buffer）之间建立管道（pipeline），从而避免了两者之间的 CPU 拷贝操作。

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/B61E74BDFF504F92AF08C273C5ECA302007S8ZIlgy1ggj9hqoqm7j30i60btabi.jpg)



基于 splice 系统调用的零拷贝方式，整个拷贝过程会发生 2 次上下文切换，0 次 CPU 拷贝以及 2 次 DMA 拷贝，用户程序读写数据的流程如下：

1. 用户进程通过 splice() 函数向内核(kernel)发起系统调用，上下文从用户态 (user space) 切换为内核态(kernel space)；

1. CPU 利用 DMA 控制器将数据从主存或硬盘拷贝到内核空间 (kernel space) 的读缓冲区 (read buffer)；

1. CPU 在内核空间的读缓冲区 (read buffer) 和网络缓冲区(socket buffer)之间建立管道 (pipeline)；

1. CPU 利用 DMA 控制器将数据从网络缓冲区 (socket buffer) 拷贝到网卡进行数据传输；

1. 上下文从内核态 (kernel space) 切换回用户态 (user space)，splice 系统调用执行返回。



splice 拷贝方式也同样存在用户程序不能对数据进行修改的问题。除此之外，它使用了 Linux 的管道缓冲机制，可以用于任意两个文件描述符中传输数据，但是它的两个文件描述符参数中有一个必须是管道设备。	



6、写时复制

在某些情况下，内核缓冲区可能被多个进程所共享，如果某个进程想要这个共享区进行 write 操作，由于 write 不提供任何的锁操作，那么就会对共享区中的数据造成破坏，写时复制的引入就是 Linux 用来保护数据的。

写时复制指的是当多个进程共享同一块数据时，如果其中一个进程需要对这份数据进行修改，那么就需要将其拷贝到自己的进程地址空间中。这样做并不影响其他进程对这块数据的操作，每个进程要修改的时候才会进行拷贝，所以叫写时拷贝。这种方法在某种程度上能够降低系统开销，如果某个进程永远不会对所访问的数据进行更改，那么也就永远不需要拷贝。

缺点：

需要 MMU 的支持，MMU 需要知道进程地址空间中哪些页面是只读的，当需要往这些页面写数据时，发出一个异常给操作系统内核，内核会分配新的存储空间来供写入的需求。



7、缓冲区共享

缓冲区共享方式完全改写了传统的 I/O 操作，传统的 Linux I/O 接口支持数据在应用程序地址空间和操作系统内核之间交换，这种交换操作导致所有的数据都需要进行拷贝。

如果采用 fbufs 这种方法，需要交换的是包含数据的缓冲区，这样就消除了多余的拷贝操作。应用程序将 fbuf 传递给操作系统内核，这样就能减少传统的 write 系统调用所产生的数据拷贝开销。

同样的应用程序通过 fbuf 来接收数据，这样也可以减少传统 read 系统调用所产生的数据拷贝开销。

fbuf 的思想是每个进程都维护着一个缓冲区池，这个缓冲区池能被同时映射到用户空间 (user space) 和内核态 (kernel space)，内核和用户共享这个缓冲区池，这样就避免了一系列的拷贝操作。



![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/FC8803819D764261A7EC1462A530E064v2-939270196a39a141cd89d0b3ca827275_1440w.jpg)



缺点：

缓冲区共享的难度在于管理共享缓冲区池需要应用程序、网络软件以及设备驱动程序之间的紧密合作，而且如何改写 API 目前还处于试验阶段并不成熟。



# Linux零拷贝对比

无论是传统 I/O 拷贝方式还是引入零拷贝的方式，2 次 DMA Copy 是都少不了的，因为两次 DMA 都是依赖硬件完成的。下面从 CPU 拷贝次数、DMA 拷贝次数以及系统调用几个方面总结一下上述几种 I/O 拷贝方式的差别。

| 拷贝方式 | CPU拷贝 | DMA拷贝 | 系统调用 | 上下文切换 |
| - | - | - | - | - |
| 传统方式(read + write) | 2 | 2 | read / write | 4 |
| 内存映射(mmap + write) | 1 | 2 | mmap / write | 4 |
| sendfile | 1 | 2 | sendfile | 2 |
| sendfile + DMA gather copy | 0 | 2 | sendfile | 2 |
| splice | 0 | 2 | splice | 2 |




大佬专门写过程序测试过，在同样的硬件条件下，传统文件传输和零拷拷贝文件传输的性能差异，你可以看到下面这张测试数据图，

使用了零拷贝能够缩短 65% 的时间，大幅度提升了机器传输数据的吞吐量。

![](https://note.youdao.com/yws/res/63839/9BFB999B051D4586AD7E8D47201D5F3A)

