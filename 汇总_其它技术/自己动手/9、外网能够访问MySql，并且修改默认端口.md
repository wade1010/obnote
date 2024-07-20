目的：将mysql端口修改为3506，并且能够外网访问



1. 华为云安全规则添加3506端口,远端IP后续修改成指定IP能访问，不需要用到安全组则跳过此步骤

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756676.jpg)

1. ssh登录到服务器，将mysql默认端口修改成3506，然后重启

1. vim /usr/local/mysql/my.cnf        增加端口3506

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756957.jpg)

1. 重启mysql          /etc/init.d/mysqld start

1. 测试          mysql -h127.0.0.1 -u root -P3506 -p    输入正确的密码，能进入则配置正确，如出现ERROR 2003 (HY000): Can't connect to MySQL server on '127.0.0.1' (111)  则配置失败

1. 服务器本地测试成功后，则外网测试一遍，一般会出现ERROR 1130 (HY000): Host '223.72.69.107' is not allowed to connect to this MySQL server，这是Mysql没有允许远程登录,msyql里面%代表所有

1.     1、在/etc/mysql/my.cnf中的[mysqld]段注释掉bind-address = 127.0.0.1,没有这个字段就跳过

2、用mysql -uroot -p 登陆mysql，然后采用以下方法开启远程访问权限：

方法1：mysql>use mysql;

     mysql>select host,user from user;

     mysql>UPDATE user SET Host= '%' WHERE User= 'root' LIMIT 1;

    mysql>FLUSH PRIVILEGES;

方法2：mysql>GRANT ALL PRIVILEGES ON *.* TO 'myuser'@'%' IDENTIFIED BY 'mypassword' WITH GRANT OPTION;

mysql>FLUSH RIVILEGES；

授权格式：grant 权限 on 数据库.* to 用户名@登录主机 identified by "密码";　

