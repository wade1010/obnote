一、Swoole的多进程模块

1.1 介绍

 

Swoole是有自己的一个进程管理模块，用来替代PHP的pcntl扩展。

需要注意Process进程在系统是非常昂贵的资源，创建进程消耗很大。另外创建的进程过多会导致进程切换开销大幅上升。

 

 

     1.2 为什么不使用pcntl

  

u pcntl没有提供进程间通信的功能

u pcntl不支持重定向标准输入和输出

u pcntl只提供了fork这样原始的接口，容易使用错误

1.3 swoole是怎么解决的

u swoole_process提供了基于unixsock的进程间通信，使用很简单只需调用write/read或者push/pop即可

u swoole_process支持重定向标准输入和输出，在子进程内echo不会打印屏幕，而是写入管道，读键盘输入可以重定向为管道读取数据

u swoole_process提供了exec接口，创建的进程可以执行其他程序，与原PHP父进程之间可以方便的通信



