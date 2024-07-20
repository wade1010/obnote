io_uring使用前提，内核版本5.10+ 

io user ring 这两者也是io_uring机制的核心。io_uring的高效性就是建立在使用用户态(user-space)可访问的无锁环形队列(ring)的基础之上的。

采用read/write/recv/send 对IO就行操作，比如读的时候，不管有没有数据，read返回，程序才能往下走，

那么io_uring就是一种异步的方案，

ubuntu21.10

uname -a 查看内核版本

io_uring  内核实现了io_uring的机制，为应用层提供了3个系统调用

liburing   是在应用层实现了一个库，供我们写的代码调用，而不是直接从这3个系统调用去实现的。   

三次握手与应用程序没有关系，是内核协议栈被动实现的。

io_uring做得异步接口为什么需要跟POSIXAPI一致？

原理与结构

io_uring的原理是让用户态进程与内核通过一个共享内存的无锁环形队列进行高效交互。相关的技术原理其实与DPDK/SPDK中的rte_ring以及virtio的vring是差不多的，只是这些技术不涉及用户态和内核态的共享内存。高性能网络IO框架netmap与io_uring技术原理更加接近，都是通过共享内存和无锁队列技术实现用户态和内核态高效交互。但上述的这些技术都是在特定场景或设备上应用的，io_uring第一次将这类技术应用到了通用的系统调用上。

共享内存

为了最大程度的减少系统调用过程中的参数内存拷贝，io_uring采用了将内核态地址空间映射到用户态的方式。通过在用户态对io_uring fd进行mmap，可以获得io_uring相关的两个内核队列（IO请求和IO完成事件）的用户态地址。用户态程序可以直接操作这两个队列来向内核发送IO请求，接收内核完成IO的事件通知。IO请求和完成事件不需要通过系统调用传递，也就完全避免了copy_to_user/copy_from_user的开销。

无锁环形队列

io_uring使用了单生产者单消费者的无锁队列来实现用户态程序与内核对共享内存的高效并发访问，生产者只修改队尾指针，消费者只修改队头指针，不会互相阻塞。对于IO请求队列来说，用户态程序是生产者内核是消费者，完成事件队列则相反。需要注意的是由于队列是单生产者单消费者的，因此如果用户态程序需要并发访问队列，需要自己保证一致性（锁/CAS）。

[https://blog.csdn.net/dillanzhou/article/details/109459544](https://blog.csdn.net/dillanzhou/article/details/109459544)

[https://blog.csdn.net/lianhunqianr1/article/details/121782497](https://blog.csdn.net/lianhunqianr1/article/details/121782497)