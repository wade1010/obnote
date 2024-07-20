Swoole开发不同于传统的开发模式，因为是异步多进程多线程模式，并且是常驻内存的，这里将一些注意事项，进行一些整理，要有一个清晰的认识。

 一、本节课知识点

    1、对象的生命周期

      2、内存管理机制

一、swoole_server中对象的4层生命周期

没必要重新整理一次，直接引用swoole官网的文档去了解下既可以

https://wiki.swoole.com/wiki/page/354.html

子进程当中修改变量,不会影响父进程

 

 二、swoole_server中内存管理机制

进程隔离之前已经讲过了，修改全局变量的值，为什么不生效，原因就是全局变量在不同的进程，内存空间是隔离的，所以无效。所以使用Swoole开发Server程序需要了解进程隔离问题。

 

https://wiki.swoole.com/wiki/page/p-zend_mm.html 

 

三、捕获Server运行期致命错误

Swoole已经提供解决方案，也可以自行修改，全局注册也没有问题

 

https://wiki.swoole.com/wiki/page/305.html

 