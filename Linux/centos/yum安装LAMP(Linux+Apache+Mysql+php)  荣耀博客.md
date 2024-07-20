环境：CentOS 6.5

更新系统内核< >

yum -y update

注报错尝试修复：

rpm –import /etc/pki/rpm-gpg/RPM-GPG-KEY*

安装Apahce、Mysql、PHP 及其基础扩展

yum -y install httpd php php-mysql mysql mysql-server

安装PHP的其他常用扩展

yum -y install php-gd php-xml php-mbstring php-ldap php-pear php-xmlrpc

安装Apache的扩展

yum -y install httpd-manual mod_ssl mod_perl mod_auth_mysql

设置开机自动启动

# 设置apache为开机自启动

/sbin/chkconfig httpd on

# 添加mysql服务

/sbin/chkconfig --add mysqld

# 设置mysql为开机自启动

/sbin/chkconfig mysqld on

启动apache与msyql

service httpd start

如果报错（httpd: Could not reliably determine the server's fully qualified）

则vi /etc/httpd/conf/httpd.conf

加入句 ServerName localhost:80

service mysqld start

设置MySQL root密码

mysqladmin -u root password 'password'

安装phpmyadmin

下载最新的phpmyadmin安装包，下载到网站目录下默认在/var/www/html/下。解压phpmyadmin压缩包后，找到 config.sample.inc.php 重命名为 config.inc.php，修改配置，就安装完成了。

如果phpmyadmin报错“mcrypt组件缺失”

[root@bogon liry]# yum install php-mcrypt

Setting up Install Process

No package php-mcrypt available.

Error: Nothing to do

打开 http://mirrors.sohu.com/fedora-epel/6/i386/ 搜索epel-release

[root@bogon liry]# rpm -ivh http://mirrors.sohu.com/fedora-epel/6/i386/epel-release-6-8.noarch.rpm

执行命令：yum update 更行系统

[root@bogon liry]# yum update

查看安装结果

[root@bogon liry]# yum repolist

安装mcrypt扩展包

[root@bogon liry]# yum install php-mcrypt

重启apache

[root@bogon liry]# service httpd restart

查看Apache是否安装成功，打开网页http://localhost测试

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/A79301D27328430A96D82F1CF424072120140115215236.png)

看到这里呢安装就完成了，当然了这个安装方法有很多，这里只介绍安装的其中一种。