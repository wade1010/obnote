centos 6 第一二步省略，直接yum安装

1. 安装第三方软件库

wget http://dag.wieers.com/rpm/packages/rpmforge-release/rpmforge-release-0.5.2-2.rf.src.rpm

rpm -ivh rpmforge-release-0.5.2-2.rf.src.rpm

2. 查找Memcached

yum search memcached

3. 安装Memcached

yum -y install memcached

4. 验证安装

memcached -h

应该会输出一些帮助信息

5. 将memcache加入启动列表

启动/etc/rc.d/init.d/memcached start

加入列表 chkconfig --level 2345 memcached on

6. 配置Memcache

vi /etc/sysconfig/memcached

文件中内容如下

PORT=”11211″ 端口

USER=”root” 使用的用户名

MAXCONN=”1024″ 同时最大连接数

CACHESIZE=”64″ 使用的内存大小

OPTIONS=”" 附加参数

7. 查看memcache状态

memcached-tool [Memcache Server IP]:[Memcache Server Port] stats

如：memcached-tool 127.0.0.1:11211 stats

=====至此，我们将Memcache服务配置完毕，接着我们配置PHP的扩展，以便在程序中来调用=====

PHP共有2种Memcache扩展，一个叫Memcache（2002年发布），另一个叫Memcached（2008年发布）

Memcached比较新，它依赖于limemcached库才能运行，不过它能完成基于Memcache服务的几乎所有功能，比如：Memcached::getResultCode ，它能返回上一次操作Memcache的结果，而Memcache则没有这个功能

Memcache（没有d）不依赖任何库就能运行，安装相对简单，同时也能完成Memcache服务的大部分主要功能。

下面分别介绍2者的安装方式。

8. 安装PHP的Memcache扩展（yum安装php已安装此扩展）

wget http://pecl.php.net/get/memcache-2.2.6.tgz

wget http://pecl.php.net/get/memcache-3.0.6.tgz



tar vxzf memcache-2.2.6.tgz

cd memcache-2.2.6

/usr/bin/phpize （如果不知道phpize在什么位置，可以用find / -name phpize查找）

./configure –enable-memcache –with-php-config=/usr/bin/php-config –with-zlib-dir

make

make install

记录下安装成功后的提示，类似于：

Installing shared extensions: /usr/lib/php/modules/

把这个地址记录下来

增加扩展extension配置文件

查看是否存在 /etc/php.d/memcache.ini 这个文件，如果不存在，我们就自己建立一个

vi /etc/php.d/memcache.ini

增加1行

extension=memcache.so

最后验证一下是否安装完成

php -m|grep memcache

应该会显示memcache

如果出现类似的错误：PHP Warning: Module ‘memcache’ already loaded in Unknown on line 0

那可以把php.ini新增加的extension=/usr/lib/php/modules/memcache.so注释掉

9. PHP的Memcached扩展安装(没有实验)

首先确认一下json头文件是否正确配置

ls /usr/include/php/ext/json/php_json.h (这是默认路径，如果安装php是指定了其他路径，则查看其他路径)

如果文件不存在，那我们需要先配置json头文件；如果已经存在，那就不需要下面这步操作了

确认一下json的版本，我们在phpinfo()中可以查看，

wget wget http://pecl.php.net/get/json-1.2.1.tgz (确认下载了正确的版本)

tar xzvf json-1.2.1.tgz

mkdir -R /usr/include/php/ext/json/

cp json-1.2.1/php_json.h /usr/include/php/ext/json/

安装libmemached

wget http://launchpad.net/libmemcached/1.0/0.50a/+download/libmemcached-0.50.tar.gz  （不要下载0.51版，它无法完成编译）

launchpad.net/libmemcached/1.0/1.0.10/+download/libmemcached-1.0.10.tar.gz这个是最新版

tar -xzvf libmemcached-0.50.tar.gz

cd libmemcached-0.50.tar.gz

./configure –prefix=/usr/local/libmemcached  –with-memcached

make

make install

再安装Memcached扩展

wget http://pecl.php.net/get/memcached-1.0.2.tgz

tar xzvf memcached-1.0.2.tgz

cd memcached-1.0.2

./configure –enable-memcached –with-php-config=/usr/bin/php-config –with-zlib-dir –with-libmemcached-dir=/usr/local/libmemcached –prefix=/usr/local/phpmemcached

make

make install

记录下安装成功后的提示，类似于：

Installing shared extensions:     /usr/lib/php/modules/

增加扩展extension配置文件

vi /etc/php.d/memcached.ini

增加1行

extension=memcached.so

最后验证一下是否安装完成

php -m|grep memcached

应该会显示memcached

如果在添加扩展so文件时，直接修改php.ini，那么可能会出现下面的错误：

/usr/lib/php/modules/memcached.so: undefined symbol: php_json_encode in Unknown on line 0

这个错误是因为在 memcached.so 加载之前必须加载 json.so ，而json.so是在/etc/php.d/json.ini中加载，这样会导致json.so在memcached.so之后加载；可以删除/etc/php.d/json.ini文件，而在php.ini中直接添加extensino=json.so来解决

=====PHP中配置MemCache就是这些======

10. 配置selinux

selinux是一套linux的安全系统，它指定了应用程序可以访问的磁盘文件、网络端口等等。

如果装有selinux，那么默认的selinux会阻止memcache程序访问11211端口，所以必须对selinux进行配置才行。

方法1： 临时降低selinux运行级别，以便我们进行测试

命令：setenforce [Enforcing | Permissive]

Enforcing表示不允许违反策略的操作

Permissive表示允许违反策略的操作，但会记录下来

我们使用 setenforce Permissive

方法2： 修改selinux配置文件，关闭selinux

编辑 /etc/selinux/config 文件，将 SELINUX=enforcing 改为 SELINUX=disabled

方法3： 修改selinux的http策略，使得httpd进程可以访问网络，这样也就可以使用memcache了

命令：setsebool -P httpd_can_network_connect true

参数P的意思是保持设置的有效性，这样在重启之后这个设置依然有效，不会改变

11. 查看selinux状态

sestatus -bv

12. 配置防火墙

如果Memcache和Web服务器不是同一台服务器，那么或许还需要配置iptables

登录Memcache服务器，并取得root权限

vi /etc/sysconfig/iptables

-A RH-Firewall-1-INPUT -p tcp -s Web服务器1的IP地址 –dport 11211 -j ACCEPT

-A RH-Firewall-1-INPUT -p tcp -s Web服务器2的IP地址 –dport 11211 -j ACCEPT

……

:wq

/etc/init.d/iptables restart

附：在Windows开发环境中，也能使用Memcached

1. 到 http://splinedancer.com/memcached-win32/ 下载memcache，如果无法打开链接，可在本文最后下载；

2. 解压后在运行框内执行以下命令：

e:\memcached\memcached.exe -d install

e:\memcached\memcached.exe -d start

3. 然后将 php_memcache.dll（在本文最后下载） 复制到 system32 和 PHP安装目录下的ext文件夹内；

4. 在php.ini文件中增加 extension=php_memcache.dll；

5. 重启Apache