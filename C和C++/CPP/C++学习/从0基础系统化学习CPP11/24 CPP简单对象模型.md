24 CPP简单对象模型

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223421.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223495.jpg)

 

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223902.jpg)

上图 左边是对象的指针表，右边是对象的成员，右边所有的成员列在一起，但并不意味着它们物理上在一起。除了非静态成员变量，其它成员的地址都是分开的。

但是不管怎么分开，成员函数和成员地址都是有地址的。

左边是对象的指针表，表中存放了成员与成员地址的对应关系，通过对象指针表可以找到对象成员的地址，对象的成员内存空间不一定是连续的，但是指针表的内存空间一定是连续的，并且指针表大小是固定的。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172223601.jpg)