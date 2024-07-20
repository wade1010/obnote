Nginx---->php-fpm之间的优化



![](https://gitee.com/hxc8/images7/raw/master/img/202407190801462.jpg)



如上图,在很多个nginx来访问fpm时, fpm的进程要是不够用, 会生成子进程.

 

生成子进程需要内核来调度,比较耗时,

如果网站并发比较大,

我们可以用静态方式一次性生成若干子进程,保持在内存中.

 

方法 -- 修改php-fpm.conf

Pm = static  让fpm进程始终保持,不要动态生成

Pm.max_children= 32  始终保持的子进程数量



Php-mysql的优化

 

Linux机器下 ,php 通过IP连接其他mysql服务器时,容易出的问题

能ping能,但connect不到.

 

 

一般是由:mysql服务器的防火墙影响的.

 

并发1万连接,响应时间过长.

 

优化思路: 同上的nginx

1: 内核层面,加大连接数,并加快tcp回收

2: mysql层面,增大连接数

3: php层面,用长连接,节省连接数

4: 用memcached缓存,减轻mysql负担

 

具体:

1.1  , PHP服务器增大 ulimint -n选项

1.2 mysql服务器内核配置

添加或修改如下选项

net.ipv4.tcp_tw_recycle = 1

net.ipv4.tcp_tw_reuse = 1

net.ipv4.tcp_syncookies = 0

 

# syscttl -p 使修改立即生效

 

2.1  修改mysql.cnf

Vi  /etc/my.conf

# service mysqld restart 重启mysql

 

3.1 PHP层面 ,用长连接

Mysql_connect ---> mysql_pconnect

注: pconnect 在PHP以apache模块的形式存在时,无效果.



Nginx+phjp+mysql+nginx

在引入memcached后,性能提升不明显,甚至还略有下降

 

memcached使50%的请求变快了,但是一部分,反倒慢了.

原因在于--PHP->memcached也要建立tcp连接,代价挺高,

但缓存了数据之后,就省去了mysql的查询时间.

 

总结: memcached适合存复杂的sql,尤其是连接查询/模糊查询的sql结果

 

Memcached服务器的优化(集中在内核的ipv4设置上,不再重复)



