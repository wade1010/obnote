进程：

![](https://gitee.com/hxc8/images8/raw/master/img/202407191107844.jpg)



共享内存：

![](https://gitee.com/hxc8/images8/raw/master/img/202407191107875.jpg)





查看系统共享内存



ipcs -m



```javascript
ipcs -m                                                                                                                                            
IPC status from <running system> as of Fri Nov 27 09:45:48 CST 2020
T     ID     KEY        MODE       OWNER    GROUP
Shared Memory:
```







swoole结构



![](https://gitee.com/hxc8/images8/raw/master/img/202407191107915.jpg)



有很多初学者对于swoole扩展并不十分了解，他们并不知道swoole是怎样的一个构成。我们知道在过去PHP写web应用开发的时候，就需要依赖Nginx这样的外部服务器，并且依赖于FPM的解析的，FPM我们知道，它同样也是一个多进程的PHP解析器，那么当一个新的请求过来的时候，FPM会创建一个新的进程去处理这个请求，这样的话在很大程度上，系统的开销是用于创建和销毁进程，导致了整个程序的响应效率并不是非常的高，那么在swoole当中呢？swoole采用了和FPM完全不同的架构。如上图所示，整个swoole扩展可以分为三层，第一层，master进程，这个进程是swoole的主进程，在这个进程当中，这个进程是用于处理swoole的核心的事件驱动的，那么在这个进程当中可以看到它拥有若干个reactor线程，这里面每一个蓝色的方框就代表一个独立的线程，那么reactor线程用于干什么的呢？在这个线程当中，在每一个reactor子线程当中都运行了一个epoll函数的实例，那么swoole所有的对于事件的监听都会在这些线程当中实现，比如来自于客户端的连接，本地通讯用的管道，以及异步操作用的文件，文件描述符都会出现的这些epoll当中。



再往下一层是manager进程，manager进程是一个管理进程，这个进程的作用是用于创建管理更下一层的worker进程和taskworker进程，在manager进行当中，不会运行任何用户层的业务逻辑，他仅仅只做进程的管理和分配。



再往下一层是工作进程啊，那么它分为两个类型，一个类型是worker进程，这个这一类进程是swoole的主逻辑进程，它用于处理来自客户端的请求。



再往下一层是taskworker进程，这一层是swoole提供的异步工作进程。这些进程主要用于处理一些耗时较长的同步任务



那么在swoole当中，进程与进程之间通讯是基于管道来实现的。可以看到在master进行当中当reactor接收到了来自客户端的数据的时候，这些数据会通过管道发送的worker进程,由worker进程进行处理。那么当worker进程需要投递任务到taskworker进程当中时，也是通过管道来实现整个数据的投递。



另外需要说明的是，我们可以通过设置swoole的配置参数来使得worker进程task进程之间的通讯走系统的消息队列。



希望这样的讲解能够帮上大家对swoole的整体结构有一个基本的了解。也就是。当一个新的客户端连接来到时，首先会被main reactor线程接收到，然后将这个连接的读写操作的监听注册到对应的reactor线程当中。并通知worker进程处理对应的on connect，也就是接收到连接的回调，那么当客户端发送数据之后，有reactor会收到这些数据，并通过管道发送给worker进程去进行处理，worker进程如果需要投递任务，那么他就会将数据同样通过管道发送给taskworker进程。task进程处理完成之后返回给worker，worker再通知reactor线程发送数据交回给客户端，那么完成了整个请求流程。



那么当worker进程出现意外，或者处理一定的请求次数关闭之后， manager经常会重新拉起一个新的worker进程，保证整个系统当中的worker进程数目是固定的，这样一来就完成了整个所扩展的结构。





task 简介



![](https://gitee.com/hxc8/images8/raw/master/img/202407191108973.jpg)





task的使用



task-常见问题



1、task传递数据大小

数据小于8K，直接通过管道传递，

数据大于8K，数据超过 8K 时会启用临时文件来保存。当临时文件内容超过 server->package_max_length 时底层会抛出一个警告。此警告不影响数据的投递，过大的 Task 可能会存在性能问题。



2、task传递对象

可以通过序列化传递一个对象的拷贝；

task中对对象的改变不会反映到worker进程中

数据库连接、网络连接对象不可传递



3、task的onFinish回调



task的onfinish回调 会发回 调用task方法的worker进程



在onworkerstart中判断是woker进程还是task进程



![](https://gitee.com/hxc8/images8/raw/master/img/202407191108998.jpg)







timer定时器



毫秒级定时器 定时器是内存操作，无 IO 消耗



定时器使用epoll的timeout  在swoole当中定时器是通过在reactor线程中注册epoll实例的timeout回调，当epoll在指定的毫秒内没处理到指定的事件的时候就会中断，并回调一个指定的函数，这个函数会去检查我们存储在内存当中的所有的定时器是否可以被运行。





毫秒精度的定时器。底层基于 epoll_wait 和 setitimer 实现，数据结构使用最小堆，可支持添加大量定时器。

- 在同步 IO 进程中使用 setitimer 和信号实现，如 Manager 和 TaskWorker 进程

- 在异步 IO 进程中使用 epoll_wait/kevent/poll/select 超时时间实现



![](https://gitee.com/hxc8/images8/raw/master/img/202407191108916.jpg)



堆是最小堆，它的存放的索引，是每个timer定时器距离下次响应剩余的时间，这个剩余时间越小，timer在堆中所放的位置就离堆顶越近，每次遍历的时候就从堆顶往下检索，从上往下，每次下沉搜索引，就会检测到剩余时间越长的timer，当最上面的timer可以运行的时候，只需要遍历少量的timer就可以将所有运行的timer从堆中取出来，提高了检索效率。







tick （循环）  after (一次)





timer常见问题

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108680.jpg)





