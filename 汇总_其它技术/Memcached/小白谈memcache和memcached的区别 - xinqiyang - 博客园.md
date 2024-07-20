看到下面很多人评论说这个文章没有用,先略去.可以不看.

写这个的时候是2011年,转眼3年过去了. 很多人看了文章之后,进行了评论,批评了我也伤害了我了,写文章分享,也有误导,但是不同的人看的效果尽然是不同的.

所以这个文章先略去先. 我在在这里强调下以下:   

其实关于这2个的区别

首先, 这篇文章讲的是 php客户端上的 2个 memcached 客户端的区别.   而且现在主要用的都是 Memcached的客户端扩展.

两个不同版本的php的memcached的客户端

new memcache是pecl扩展库版本

new memcached是libmemcached版本

第二, 对于过时的东西,总是会被新东西替代的, 为什么不用新的东西呢?

这篇文章写的很清楚,程序的原理先理解了,纠结于这个东西叫什么名字有什么意义呢? 首先得理解原理吧!  

先去看看wiki吧

https://code.google.com/p/memcached/wiki/NewStart

http://www.php.net/manual/en/book.memcached.php

http://www.php.net/manual/en/book.memcache.php

还有说明的是,哥3年前也是小白..............有这么说小白的么? 小白说小白.......嗨......

------------------------------------------------- 分割线 -------------------------------------------------------------------------------

用了段时间的memcache和memcached总结下认识，看很多人在用cache的时候，刚刚都没有搞清楚memcache和 memcached的区别，还有就是使用的时候基本都是 get/set  用了memcached之后其实可以发现getMulti/setMulti 是多么好用,这篇写个那些刚刚使用memcache缓存的新人，老鸟请略过。

关于memcached就不用多说了，就是a distributed memory object  caching system 。既然是一个用来存东西的系统，那么一定要有个存放的地方吧，我们就叫它服务器端吧，然后谁把东西存放在上面就叫它客户端吧，那怎么放呢，肯定是 客户端 -- 连接服务器端 -- 把东西发送给服务器端 -- 实现了东西的存放么，要去取的时候也是一样的，先连接，在取东西回来了。所有就有了memcached的服务器端，安装请见 http://www.cnblogs.com/scotoma/archive/2010/05/27/1745011.html 这个是WIN下的，*unix下的请到 http://memcached.org/ 去下载然后编译安装了，这里我就不多说安装的配置了，网上已经有很多了。

安装完成后看下自己的进程里面memcached的服务是否在跑着的？ 好了进程在跑着呢，那就看看客户端吧

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/DA8A029DB55940CE96EB4000E7CA75712011021521230093.png)

我是做PHP开发的，所有就安装了PHP的客户端扩展，有memcache和memcached扩展2种，安装我也不说了自己去动手，安装完成后查看phpinfo会发现如下页面就说明你的扩展安装成功了，如果不成功请自己检查php.ini里面的配置是否正确

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/9AF4A06887E149ED8E933DB8FD71A9D82011021521232039.png)

服务器端和客户端都弄好了看看示例代码可以跑起来的么,如图

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/702002B15168456CAAC84D5BA64F04012011021521233658.png)

结果如图：

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/8E77D0C6C5A64102A9EBE0689CA5045E2011021521240566.png)

都跑起来了，看看memcache和memcached的使用的区别，那就好好的翻看下PHP手册吧，其实手册是最好的东西了

memcache扩展的方法

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/E553CCE62B8A472F94B1DBC9E7F04D2D2011021521241941.png)

memcached扩展的方法

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/427E7C468E5148DD8B15493BC494E9D02011021521244078.png)

完成了，其实2个可以理解成2个扩展历史原因也不想多说了，就是尽量使用memcached就好了，不过也会出现一些很奇怪的Bug，比如使用memcached扩展的适合设置的session（session存放到memcached中，使用的是memcached扩展存放的就会发现不会过期）。

在实践中用了之后才会知道什么和什么的,动手是最好的学习方式.