后期补充(2019-3-24 16:12:24)：

跨大版本，单独升级PHP，看了upgrade.sh脚本，不能指定升级的版本，你安装的是5.6版本，脚本只会升级到5.6中最高的版本。所以这里需要改下lamp目录下的include中的upgrade_php.sh脚本

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756298.jpg)

我之前安装的是5.6版本，想要升级到PHP7.所以将5.6.40改成最新版本的7.3.3

最新版本可以从http://php.net/downloads.php获取

改好后 安装 ./upgrade.sh php  即可



补充：

升级后重启Apache可能会报错

[root@vmware40 ~]# /etc/init.d/httpd -k start

/etc/init.d/httpd: line 95: 113288 Segmentation fault      (core dumped) $HTTPD $ARGV

这个是因为两个php扩展，一个是php-7的，一个是php-5.6.5的，然后看了httpd的配置文件，结果配置了两个php模块：

LoadModule php5_module        modules/libphp5.so

LoadModule php7_module        modules/libphp7.so

去掉一个扩展后，apache启动正常。

应该是安装了php7后，又往httpd的配置文件中加入了php7的扩展。

---



安装时间：

Start time     : 2018-03-04 22:17:53

Completion time: 2018-03-04 22:50:52 (Use: 32 minutes)



系统需求

- 系统支持：CentOS 6+/Debian 7+/Ubuntu 12+

- 内存要求：≥ 512MB

- 硬盘要求：至少 5GB 以上的剩余空间

- 服务器必须配置好 软件源 和 可连接外网

- 必须具有系统 root 权限

- 强烈建议使用全新系统来安装

支持组件

- 支持 PHP 自带几乎所有组件

- 支持 MySQL、MariaDB、Percona Server数据库

- 支持 Redis（可选安装）

- 支持 XCache （可选安装）

- 支持 Swoole （可选安装）

- 支持 Memcached （可选安装）

- 支持 ImageMagick （可选安装）

- 支持 GraphicsMagick （可选安装）

- 支持 ionCube Loader （可选安装）

- 自助升级 Apache，PHP，phpMyAdmin，MySQL/MariaDB/Percona Server至最新版本

- 命令行新增虚拟主机（使用 lamp 命令），操作简便

- 支持一键卸载

安装步骤

1. 事前准备（安装 wget、screen、unzip，创建 screen 会话）

注意：双斜杠//后的内容不要复制输入

yum -y install wget screen git      // for CentOS
apt-get -y install wget screen git  // for Debian/Ubuntu


1. git clone 并赋予脚本执行权限

git clone https://github.com/teddysun/lamp.git
cd lamp
chmod +x *.sh


1. 开始安装

screen -S lamp
./lamp.sh


组件安装

关于本脚本支持的所有组件，都可以在脚本交互里可选安装。

使用提示

lamp add      创建虚拟主机
lamp del      删除虚拟主机
lamp list     列出虚拟主机


如何升级

注意：双斜杠//后的内容不要复制输入

git pull                 // Get latest version
./upgrade.sh             // Select one to upgrade
./upgrade.sh apache      // Upgrade Apache
./upgrade.sh db          // Upgrade MySQL/MariaDB/Percona
./upgrade.sh php         // Upgrade PHP
./upgrade.sh phpmyadmin  // Upgrade phpMyAdmin


如何卸载

./uninstall.sh 


程序目录

- MySQL 安装目录: /usr/local/mysql

- MySQL 数据库目录：/usr/local/mysql/data（默认，安装时可更改路径）

- MariaDB 安装目录: /usr/local/mariadb

- MariaDB 数据库目录：/usr/local/mariadb/data（默认，安装时可更改路径）

- Percona 安装目录: /usr/local/percona

- Percona 数据库目录：/usr/local/percona/data（默认，安装时可更改路径）

- PHP 安装目录: /usr/local/php

- Apache 安装目录： /usr/local/apache

命令一览

- MySQL 或 MariaDB 或 Percona 命令

/etc/init.d/mysqld (start|stop|restart|status)


- Apache 命令

/etc/init.d/httpd (start|stop|restart|status)


- Memcached 命令（可选安装）

/etc/init.d/memcached (start|stop|restart|status)


- Redis 命令（可选安装）

/etc/init.d/redis-server (start|stop|restart|status)


网站根目录

默认的网站根目录： /data/www/default







------------------------- Install Overview --------------------------



Apache: httpd-2.4.29

Apache Location: /usr/local/apache





MySQL: mysql-5.6.39

MySQL Location: /usr/local/mysql

MySQL Data Location: /usr/local/mysql/data

MySQL Root Password: root



PHP: php-5.6.34

PHP Location: /usr/local/php

PHP Additional Modules:

xcache-3.2.0

redis-2.2.8

mongodb-1.3.4



phpMyAdmin: do_not_install