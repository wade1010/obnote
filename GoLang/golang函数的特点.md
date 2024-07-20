不支持重载，一个包不能有两个名字一样的函数

函数是一等公民，函数也是一种类型，一个函数可以赋值给变量

匿名函数

多返回值





map、slice、chan、指针、interface默认是以引用的方式传递的





defer



1. 当函数返回时，执行defer语句，因此可以用来做资源清理

1. 多个defer语句，按先进后出的方式执行

1. defer语句中的变量，在defer声明是就决定了



defer的用途

1. 关闭文件句柄

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753621.jpg)

2.释放资源锁

![](https://gitee.com/hxc8/images7/raw/master/img/202407190753563.jpg)

3.数据库连接的释放

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754525.jpg)

