Qt核心特点：

- Qt对标准C++进行了扩展，引入了一些型的概念和功能。

- 元对象编译器（Meta-Object Compiler , MOC）是一个预处理器

- 先将Qt的特性程序转换为标准C++程序，再由标准C++编译器进行编译

使用信号与槽机制，只有添加Q_OBJECT宏，MOC才能对类里的信号与槽进行预处理

Qt为C++语言增加的特性在Qt Core模块里实现，由Qt的元对象系统实现。

包括：信号与槽机制、属性系统、动态类型转换等。

元对象系统（Meta-Object System）

- QObject类似所有使用元对象系统的类的基类

- 在一个类的private部分声明Q_OBJECT宏

- MOC(元对象编译器)为每个QObject的子类提供必要的代码

![](images/WEBRESOURCEd836ec6be96dd7325ced1f98b55f7aa0截图.png)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172217901.jpg)