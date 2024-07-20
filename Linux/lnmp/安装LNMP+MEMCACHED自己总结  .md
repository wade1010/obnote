1、到 http://lnmp.org/ 一键安装LNMP，不过时间比较长需要60-70分钟；

默认装好的目录如下图：

![](D:/download/youdaonote-pull-master/data/Technology/Linux/lnmp/images/8CB5B0860654476285949CBCF3AF3441lnmp_info_pic.png)

2、先下载安装所需要的软件

我这里已经打包，供大家下载：【点击下载】

解压后你会发现有四个软件包，这里给大家进行说明：

libevent-2.0.21-stable.tar.gz   安装 Memcached 服务器所依赖的软件包

libmemcached-0.42.tar.gz  是一个 memcached 的库

memcached-1.4.15.tar.gz  Memcached 服务器软件包

memcached-1.0.2.tar.gz  PHP开启 Memcached 扩展的软件包

注：大家会奇怪为什么这里会有两个 memcached 包，是这样的，这两个包一个较大，一个较小。较大的是 memcached 服务器软件包；较小的用于整合 PHP memcached 扩展，较小的包会生成一个 memcached.so 的 extension 文件。

2、安装软件包

这里请注意安装软件包的顺序。

首先，请把这四个软件全部解包，解包的命令如下（四个都一样）：

[plain]  view plaincopy

1. [root@gamejzy lamp]# tar zxvf libevent-2.0.21-stable.tar.gz  



安装 libevent

[plain]  view plaincopy

1. [root@gamejzy lamp]# cd libevent-2.0.21-stable/  

1. [root@gamejzy libevent-2.0.21-stable]# ./configure --prefix=/usr/local/libevent/  

1. [root@gamejzy libevent-2.0.21-stable]# make && make install  



--prefix 设置安装路径

安装 memcached

[plain]  view plaincopy

1. [root@gamejzy memcached-1.4.15]# cd memcached-1.4.15  

1. [root@gamejzy memcached-1.4.15]# ./configure --prefix=/usr/local/memcache/ --with-libevent=/usr/local/libevent/  

1. [root@gamejzy memcached-1.4.15]# make && make install  



--with-libevent 指定 libevent 的安装位置

安装 libmemcached

[plain]  view plaincopy

1. [root@gamejzy lamp]# cd libmemcached-0.42  

1. [root@gamejzy libmemcached-0.42]# ./configure --prefix=/usr/local/libmemcached  --with-memcached  

1. [root@gamejzy libmemcached-0.42]# make && make install  



开启 PHP 的 memcached 扩展

[plain]  view plaincopy

1. [root@gamejzy lamp]# cd memcached-1.0.2  

1. [root@gamejzy memcached-1.0.2]# /usr/local/php/bin/phpize  

/usr/local/php 是我机器 php 的安装目录

[plain]  view plaincopy

1. [root@gamejzy memcached-1.0.2]#./configure --enable-memcached --with-php-config=/usr/local/php/bin/php-config --with-libmemcached-dir=/usr/local/libmemcached/ 

1. [root@gamejzy memcached-1.0.2]# make && make install



编译完成之后会出现如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/Linux/lnmp/images/873161AA7FCF4A3D9FB9FA67A4D915C04867504_7254.png)

这时会生成一个 memcached.so 文件，放置在红圈的位置（可能因机器而不同）

打开 php.ini 文件，添加一条“extension=/usr/local/php/lib/php/extensions/no-debug-non-zts-20090626/memcached.so”

重启 Apache（如果是nginx不但要重启nginx还要重启php-fpm   命令/etc/init.d/php-fpm restart）看是否出现下图所示内容

![](D:/download/youdaonote-pull-master/data/Technology/Linux/lnmp/images/9036FDF2034C422282C1648C0EB1E81E4867797_5180.png)

出现说明成功。

Linux 下启动 Memcached

 # /usr/local/bin/memcached -d -m 10  -u root -l 192.168.0.200 -p 12000 -c 256 -P /tmp/memcached.pid

/usr/local/bin/memcached -d -m 128 -l localhost -p 11211 -u root

 -d 以守护程序（daemon）方式运行 memcached；

 -m 设置 memcached 可以使用的内存大小，单位为 M；

 -l 设置监听的 IP 地址，如果是本机的话，通常可以不设置此参数；

 -p 设置监听的端口，默认为 11211，所以也可以不设置此参数；

 -u 指定用户;

 -t <num>       number of threads to use, default 4

如果有此项，说明已经支持了线程，就可以在启动的时候使用 -t 选项来启动多线程

然后启动的时候必须加上你需要支持的线程数量：

/usr/local/memcache/bin/memcached -t 1024

设置MEMCACHED 开机启动 http://blog.snsgou.com/post-729.html