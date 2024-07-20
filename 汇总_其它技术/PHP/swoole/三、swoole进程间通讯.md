进程间通信（IPC，Inter-Process Communication），指至少两个进程或线程间传送数据或信号的一些技术或方法。每个进程都有自己的一部分独立的系统资源，彼此是隔离的。为了能使不同的进程互相访问资源并进行协调工作，才有了进程间通信。

 

进程通信有如下的目的：

数据传输，一个进程需要将它的数据发送给另一个进程，发送的数据量在一个字节到几M之间；

共享数据，多个进程想要操作共享数据，一个进程对数据的修改，其他进程应该立刻看到；

进程控制，有些进程希望完全控制另一个进程的执行（如Debug进程），此时控制进程希望能够拦截另一个进程的所有异常，并能够及时知道它的状态改变。

 

系统进行进程间通信（IPC）的时候，可用的方式包括管道、命名管道、消息队列、信号、信号量、共享内存、套接字(socket)等形式。





3.1消息队列

消息队列实际上就是一个链表，而消息就是链表中具有特定格式和优先级的记录，对消息队列有写权限的进程可以根据一定规则在消息链表中添加消息，对消息队列有读权限的进程则可以从消息队列中获得所需的信息。



在某个进程往一个消息队列写入消息之前，并不需要另外某个进程在该队列上等待消息的到达。对于消息队列来说，除非显式删除，否则其一直存在



php实现消息队列操作



在php中通过这两句话就可以创建一个消息队列。 ftok 函数，是可以将一个路径转换成 消息队列 可用的key值。 msg_get_queue函数的第一个参数 是消息队列的key，第二个参数是消息队列的读写权限，这个权限跟文件类似

 

注意：需要开启sysvmsg



msg_send函数，向指定消息队列写入信息



msg_send ( resource $queue , int $msgtype , mixed $message [, bool $serialize = true [, bool $blocking = true [, int &$errorcode ]]] )



第1个参数 ： resource $queue 表示要写入的消息队列资源。

第2个参数 ： int $msgtype 表示写入消息队列的 消息类型，这个参数是 配合 msg_receive读取消息队列函数 使用的，下面会说。

第3个参数 ： mixed $message 你要发送的信息，最大为 65536 个字节。

第4个参数 ： bool $serialize = true 为可选项，是否序列化你发送的消息。

第5个参数 ： bool $blocking = true 是否阻塞，当你发送的消息很大，而此时的消息队列无法存入的时候，此时消息队列就会阻塞，除非等到有别的进程从消息队列中读取了别的消息，然后消息队列有足够的空间存储你要发送的信息，才能继续执行。你可以设置这个参数为false，这样你发送信息就会失败，此时错误信息会在 第6个参数 $errorcode中体现，错误码为 MSG_EAGAIN ，你可以根据这个错误码，重新发送你的消息。

第6个参数 ： int &$errorcode 记录写入中出现的一系列错误。



读取函数 msg_receive



msg_receive ( resource $queue , int $desiredmsgtype , int &$msgtype , int $maxsize , mixed &$message [, bool $unserialize = true [, int $flags = 0 [, int &$errorcode ]]] )



第1个参数：resource $queue 表示要读取的消息队列资源。

第2个参数 ：int $desiredmsgtype 读取的消息类型。这个参数为 0 的时候，你可以读取 msg_send 以任意 消息类型 发送的消息。 如果此参数和你发送的某个消息类型相同，比如你有 2个消息，一个是通过 1类型发送的，一个是通过2 类型发送的。你用 0 可以接收这两种消息 ，而你用 1 只能接收到 以1类型发送的消息。

第3个参数 ： int &$msgtype 你读取到的信息，它发送时的消息类型会存储在该参数中。

第4个参数 ： int $maxsize 你以多大的字节去读取消息，如果这个值小于你要读取的内容的长度，你会读取失败。

第5个参数 ：mixed &$message 读取的内容。

第6个参数 ： bool $unserialize = true 内容是否序列化

第7个参数 ：int $flags = 0 读取标识。除了默认的0 之外，还有3个参数可选 MSG_IPC_NOWAIT 这个参数表示如果没有从消息队列中读取到信息，会立马返回，并返回错误码 MSG_ENOMSG.



MSG_EXCEPT 这个参数 是配合 第2个参数使用的，如果使用这个参数，你读取到的第一个参数，不是你第一个发送的参数。(队列先进先出)

MSG_NOERROR 如果读取的内容过大，而你指定的第4个参数又不够的时候，它会截断这个消息，并且不报错。



销毁消息队列的方法 ： msg_remove_queue($msg_queue);



注意:

  如果其他语言,或者是传统的php-fpm也希望投递消息到task当中处理,也是可行的

方法请参考下面链接:

    https://wiki.swoole.com/wiki/page/212.html



我们可以利用消息队列在自己的网络服务器实现swoole的task方法

```javascript
<?php

//父进程跟子进程实现消息发送

$msg_key=ftok(__DIR__,'u'); //注意在php创建消息队列，第二个参数会直接转成字符串，可能会导致通讯失败
$msg_queue=msg_get_queue($msg_key);


$pid=pcntl_fork();

if($pid==0){
    //子进程发送消息
    msg_send($msg_queue,10,"我是子进程发送的消息");
    //msg_receive($msg_queue,10,$message_type,1024,$message);
    //var_dump($message);
    exit();
}elseif ($pid){

   msg_receive($msg_queue,10,$message_type,1024,$message);
   var_dump($message);
    //父进程接收消息
    pcntl_wait($status);
    msg_remove_queue($msg_queue);

}


//while (){
//
//
//}

```





3.2共享内存



1、进程间通讯-共享内存方式

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108514.jpg)







在系统内存中开辟一块内存区，分别映射到各个进程的虚拟地址空间中，任何一个进程操作了内存区都会反映到其他进程中，各个进程之间的通信并没有像copy数据一样从内核到用户，再从用户到内核的拷贝。这种方式可以像访问自己的私有空间一样访问共享内存区，是速度最快的一种通信方式。



但是这事这种特性加大了共享内存的编程难度，比如多个进程同时读取到一个数据做操作，容易造成数据的混乱。



2、swoole_table

swoole_table一个基于共享内存和锁实现的超高性能，并发数据结构。用于解决多进程/多线程数据共享和同步加锁问题,应用代码无需加锁，swoole_table内置行锁自旋锁，所有操作均是多线程/多进程安全。用户层完全不需要考虑数据同步问题



2.1、创建一个共享内存的table

      使用swoole共享table非常简单,简单几步就可以创建

 

  1、实例化table并且设置最大行数

     

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108837.jpg)

  2、指定表格字段，指定表格类型以及长度

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108172.jpg)

  3、创建表格

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108870.jpg)



4、设置一行数据



![](https://gitee.com/hxc8/images8/raw/master/img/202407191108592.jpg)



注意事项：

在swoole_server->start()之前创建swoole_table对象。并存入全局变量或者类静态变量/对象属性中，在worker/task进程中获取table对象，并使用。

只有在swoole_server->start()之前创建的table对象才能在子进程中使用

swoole_table构造方法中指定了最大容量，一旦超过此数据容量将无法分配内存导致set操作失败。所以使用swoole_table之前一定要规划好数据容量。



