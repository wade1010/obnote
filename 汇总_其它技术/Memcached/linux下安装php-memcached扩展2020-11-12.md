1:准备编译环境

在 linux 编译,需要 gcc,make,cmake,autoconf,libtool 等工具,

这几件工具, 以后还要编译 redis 等使用,所以请先装.

在 linux 系统联网后,用如下命令安装

#yum install gcc make cmake autoconf libtool



可以通过 yum list installed | grep "libtool" 查询下是否安装

2: 编译 memcached 服务器

memcached 依赖于 libevent 库,因此我们需要先安装 libevent.

分别到 libevent.org 和 memcached.org 下载最新的 stable 版本(稳定版).

先编译 libevent ,再编译 memcached,

编译 memcached 时要指定 libevent 的路径.

过程如下: 假设源码在/usr/local/src 下, 安装在/usr/local 下



```javascript
wget https://github.com/libevent/libevent/releases/download/release-2.1.12-stable/libevent-2.1.12-stable.tar.gz

tar -zxvf libevent-2.1.12-stable.tar.gz

cd libevent-2.1.12-stable

./configure --prefix=/usr/local/libevent

make && make install

wget http://memcached.org/files/memcached-1.6.8.tar.gz

tar -zxvf memcached-1.6.8.tar.gz

cd  memcached-1.6.8

./configure --prefix=/usr/local/memcached --with-libevent=/usr/local/libevent

make && make install

//修改php.init增加如下代码
extension=memcached.so
```





注意: 在虚拟机下练习编译,一个容易碰到的问题---虚拟机的时间不对,

导致的 gcc 编译过程中,检测时间通不过,一直处于编译过程.

解决:

# date -s ‘yyyy-mm-dd hh:mm:ss’

# clock -w # 把时间写入 cmos



/usr/local/memcached --help 就能查看怎么使用memcached服务器了



3: 编译安装php-fpm的memcached扩展





https://pecl.php.net/package/memcached



```javascript
wget https://pecl.php.net/get/memcached-3.1.5.tgz

tar zxvf memcached-3.1.5.tgz

cd  memcached-3.1.5

phpize

./configure --with-php-config=/usr/local/php/bin/php-config --with-libmemcached-dir=/usr/local/libmemcached


```



报错



```javascript
error: memcached support requires libmemcached. Use --with-libmemcached-dir=<DIR> to specify the prefix where libmemcached headers and library are located
```





下载地址 https://launchpad.net/libmemcached/+download



3-1:安装 libmemcached

```javascript
wget https://launchpad.net/libmemcached/1.0/1.0.18/+download/libmemcached-1.0.18.tar.gz
tar -zxvf libmemcached-1.0.18.tar.gz
cd libmemcached-1.0.18
./configure --prefix=/usr/local/libmemcached
make && make install
```





再冲新进入到目录 memcached-3.1.5



```javascript
./configure --with-php-config=/usr/local/php/bin/php-config --with-libmemcached-dir=/usr/local/libmemcached
make && make install
```





检查是否安装 

```javascript
➜  php -m|grep mem
memcached
```

