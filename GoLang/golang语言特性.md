1 、垃圾回收

1. 内存自动回收，再也不需要开发人员管理内存

1. 开发人员专注业务实现，降低心智负担

1. 只需要new分配内存，不需要释放



2、天然并发

1. 从语言层面支持并发，非常简单

1. goroute，轻量级线程，创建成千上万个goroute成为可能

1. 基于CSP（Communicating Sequential Process ）模型实现  中文大概就是 通信序列进程



3、channel

1. 管道，类似unix/linux中的pipe

1. 多个goroutine自荐通过channel进行通信

1. 支持任何类型

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754216.jpg)





4、多返回值



1. 一个函数返回多个值

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754514.jpg)

















































