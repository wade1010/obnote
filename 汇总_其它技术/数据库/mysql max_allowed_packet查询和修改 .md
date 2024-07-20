http://www.2cto.com/database/201303/195830.html

mysql根据配置文件会限制server接受的数据包大小。

有时候大的插入和更新会被max_allowed_packet 参数限制掉，导致失败。

查看目前配置 

show VARIABLES like '%max_allowed_packet%';

显示的结果为：

 

+--------------------+---------+

| Variable_name      | Value   |

+--------------------+---------+

| max_allowed_packet | 1048576 |

+--------------------+---------+

 

以上说明目前的配置是：1M

 

修改方法

一、 方法1

可以编辑my.cnf来修改（windows下my.ini）,在[mysqld]段或者mysql的server配置段进行修改。

max_allowed_packet = 20M

如果找不到my.cnf可以通过

mysql --help | grep my.cnf

去寻找my.cnf文件。

[root@localhost usr]# mysql --help | grep my.cnf

                      order of preference, my.cnf, $MYSQL_TCP_PORT,

/etc/my.cnf /etc/mysql/my.cnf /usr/etc/my.cnf ~/.my.cnf

在linux下会发现上述文件可能都不存在。

1)先确定出使用的配置文件的路径（如果未启动，可先启动）

[root@localhost usr]# ps aux |grep mysql

root     14688  0.0  0.0  11336  1404 pts/0    S    19:07   0:00 /bin/sh /usr/bin/mysqld_safe --datadir=/var/lib/mysql --pid-file=/var/lib/mysql/localhost.localdomain138.pid

mysql    14791  0.0 15.4 1076700 451336 pts/0  Sl   19:07   0:00 /usr/sbin/mysqld --basedir=/usr --datadir=/var/lib/mysql --plugin-dir=/usr/lib64/mysql/plugin --user=mysql --log-error=/var/lib/mysql/localhost.localdomain138.err --pid-file=/var/lib/mysql/localhost.localdomain138.pid

root     14835  0.0  0.0 201584  2504 pts/0    S+   19:09   0:00 mysql -u root -p

root     15143  0.0  0.0 103244   828 pts/1    S+   19:40   0:00 grep mysql

 找见mysqld或mysqld_safe的那一行，看下basedir=/path/file ，那个/path/file就是配置文件路径；

2)也可以直接创建 /etc/my.cnf, 或者从你安装的mysql的相关目录中(可能是/usr/include/mysql或/usr/share/mysql)找一个my.cnf 或 my-small.cnf 拷贝为/etc/my.cnf,mysql启动时会优先使用这个配置文件。

可以用如下命令在/etc目录下查找my.cnf类似的文件名：

[root@localhost usr]# find -name "my*.cnf"

./my.cnf

./share/mysql/my-default.cnf

./share/doc/MySQL-server-5.6.16/my-default.cnf

./my-new.cnf

3)有了配置文件，在配置文件中的[mysqld]下边加些常用的配置参数。重启mysql服务器后，该参数即可生效。

   max_allowed_packet=32M

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058220.jpg)





二、 方法2

（很妥协，很纠结的办法）

进入mysql server

在mysql 命令行中运行

set global max_allowed_packet = 2*1024*1024*10

退出mysql命令行，然后重新登录。

show VARIABLES like '%max_allowed_packet%';

查看下max_allowed_packet是否编辑成功

注：方法2中，如果重启mysql服务，max_allowed_packet的值会还原成默认的初始值，命令行里设定的值不会生效。



其他参考资料：

http://zhidao.baidu.com/link?url=nIy0O1xj1kJWuvdKi8Aeo1UcHRtCx6EtrFFbkCwIsduE1mwVNWXpLUKf_izyyhd3fu7Hknp5bG1lbCuiG8s-tK