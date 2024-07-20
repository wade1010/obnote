Django缓存都缓存到redis中

例如下面的 cache.set('tutu', 'hobby', 'film')。用的是Django的缓存，不是用的conn.hset。（下面这五段话可以忽略不看）

django的缓存很高级，它可以缓存python中所有的数据类型，包括对象，但是redis只有五大数据类型，缓存对象类型很显然是不支持的，那它是怎么做到的呢？

本质原理(源码)把python数据类型通过pickle序列化成二进制，以字符串的形式缓存到redis中。拿出来后再通过pickle 反序列化回来就是对象。pickle 是 python 语言的一个标准模块，安装 python 的同时就已经安装了 pickle 库，因此它不需要再单独安装，使用 import 将其导入到程序中，就可以直接使用。

pickle能够实现任意对象与文本之间的相互转化，也可以实现任意对象与二进制之间的相互转化。也就是说，pickle 可以实现 Python 对象的存储及恢复。它通过提供4个函数来实现，其中 dumps 和 loads 实现基于内存的 Python 对象与二进制互转；dump 和 load 实现基于文件的 Python 对象与二进制互转。

用cache比用conn.set ，conn.get等命令要强得太多，只管写只管取，它自动地给你转，不需要管它是什么类型了。

以前缓存是缓存到内存中，重启后数据就没了，现在缓存到redis中后，再重启数据也不会丢失，这是缓存到内存中和缓存到redis的区别。

[https://www.cnblogs.com/tully/p/16907435.html](https://www.cnblogs.com/tully/p/16907435.html)

[https://blog.csdn.net/m0_63953077/article/details/128065065](https://blog.csdn.net/m0_63953077/article/details/128065065)