linux下

一、导出数据库用mysqldump命令（注意mysql的安装路径，即此命令的路径）：

1、导出数据和表结构：

mysqldump -u用户名 -p密码 数据库名 > 数据库名.sql

#/usr/local/mysql/bin/   mysqldump -uroot -p abc > abc.sql

敲回车后会提示输入密码

2、只导出表结构

mysqldump -u用户名 -p密码 -d 数据库名 > 数据库名.sql

#/usr/local/mysql/bin/   mysqldump -uroot -p -d abc > abc.sql

注：/usr/local/mysql/bin/  --->  mysql的data目录



二、导入数据库

1、首先建空数据库

mysql>create database abc;

2、导入数据库

方法一：

（1）选择数据库

mysql>use abc;

（2）设置数据库编码

mysql>set names utf8;

（3）导入数据（注意sql文件的路径）

mysql>source /home/abc/abc.sql;

方法二：

mysql -u用户名 -p密码 数据库名 < 数据库名.sql

#mysql -uabc_f -p abc < abc.sql