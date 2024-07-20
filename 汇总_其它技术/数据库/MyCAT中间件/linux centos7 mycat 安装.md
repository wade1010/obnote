### 必备

MYSQL

必须 JDK7 或更高版本 http://www.oracle.com/technetwork/java/javase/downloads/jdk7-downloads-1880260.html


### 安装

官网地址

http://mycat.org.cn/

我选择 http://dl.mycat.org.cn/1.6.7.4/Mycat-server-1.6.7.4-release/Mycat-server-1.6.7.4-release-20200105164103-linux.tar.gz

下载

> wget http://dl.mycat.org.cn/1.6.7.4/Mycat-server-1.6.7.4-release/Mycat-server-1.6.7.4-release-20200105164103-linux.tar.gz


> tar -zxvf Mycat-server-1.6.7.4-release-20200105164103-linux.tar.gz


> 官方建议放在 usr/local/Mycat 目录下

> cp -r mycat /usr/local/mycat

> useradd mycat

> chown -R mycat.mycat /usr/local/mycat

> passwd mycat  修改密码


###  服务启动与启动设置

MyCAT 在 Linux 中部署启动时，首先需要在 Linux 系统的环境变量中配置 MYCAT_HOME,操作方式如下：

1) vi /etc/profile,在系统环境变量文件中增加 MYCAT_HOME=/usr/local/mycat。

2) 执行 source /etc/profile 命令，使环境变量生效。
如果是在多台 Linux 系统中组建的 MyCAT 集群，那需要在 MyCAT Server 所在的服务器上配置对其他 ip 和
主机名的映射，配置方式如下：

>vi /etc/hosts

例如：我有 4 台机器，配置如下：

IP 主机名：


```
192.168.100.2 sam_server_1
192.168.100.3 sam_server_2
192.168.100.4 sam_server_3
192.168.100.5 sam_server_4
```


编辑完后，保存文件。

经过以上两个步骤的配置，就可以到/usr/local/mycat/bin 

目录下执行：

./mycat start

即可启动 mycat 服务！


