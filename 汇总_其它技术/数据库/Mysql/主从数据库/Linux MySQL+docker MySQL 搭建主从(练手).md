#### 目标
搭建两台MySQL服务器，宿主机Linux作为主服务器，Linux上用docker安装一个MySQL作为从服务器，实现主从复制
#### 环境
主数据库： 192.168.1.52:3306

从数据库： 192.168.1.52:13306

mysql版本都是5.6.49, for linux
#### 数据库准备
1. 保证两个数据库中的库和数据是一致的，自己从主数据库复制一个数据库就行啦
2. 在主数据中创建一个同步账号（可不创建使用现有的），如果仅仅为了主从复制创建账号，只需要授予REPLICATION SLAVE权限。
```Mysql
CREATE USER 'slave_user'@'%' IDENTIFIED BY '123456';
GRANT REPLICATION SLAVE ON *.* TO 'slave_user'@'%' IDENTIFIED BY '123456' WITH GRANT OPTION;
```
#### 配置主数据库
1. 主库开启二进制日志(binary logging),并添加唯一的server-id(该id必须是1到2^23-1范围内的唯一值)
    2. 如果主服务器的二进制日志已经启用，关闭并重新启动之前应该对以前的二进制日志进行备份。重新启动后，应使用RESET MASTER语句清空以前的日志。 
    3. 原因：master上对数据库的一切操作都记录在日志文件中，然后会把日志发给slave，slave接收到master传来的日志文 件之后就会执行相应的操作，使slave中的数据库做和master数据库相同的操作。所以为了保持数据的一致性，必须保证日志文件没有脏数据。
4. vim /etc/my.cnf
5. 在 [mysqld]下方添加如下配置
```
# 主从复制 #
log-bin=mysql-bin
server-id=1
binlog-do-db=test  #要同步多个数据库，就多加几个binlog-do-db=数据库名 
binlog-do-db=test2  #要同步多个数据库，就多加几个binlog-do-db=数据库名 
#binlog-ignore-db=mysql //要忽略的数据库
innodb_flush_log_at_trx_commit=1
sync_binlog=1
```
4. 重启mysql      

> systemctl restart mysql

5. 查看主服务器状态,登录主mysql后执行  show master status;查看


```
mysql> show master status;
+------------------+----------+--------------+------------------+-------------------+
| File             | Position | Binlog_Do_DB | Binlog_Ignore_DB | Executed_Gtid_Set |
+------------------+----------+--------------+------------------+-------------------+
| mysql-bin.000001 |     2460 | test      |                  |                   |
+------------------+----------+--------------+------------------+-------------------+
1 row in set (0.00 sec)

```
==**注意：记录好File和Position，后面要用**==

#### 配置从数据库
1. 在宿主机某个目录创建个docker.cnf 我这里只加了server-id=2

```
[mysqld]
server-id=2
```



2. 启动docker MySQL  设置数据库密码  123456 自定义

> docker run -d --name mysql -p 13306:3306 -v /home/xxxx/Desktop/data/docker/mysql/conf:/etc/mysql/conf.d --env MYSQL_ROOT_PASSWORD=123456 mysql:5.6 --character-set-server=utf8 --collation-server=utf8_unicode_ci


/home/xxxx/Desktop/data/docker/mysql/conf  是你宿主机文件目录

3. 宿主机上输入命令，登录mysql,这里就要用到上面====记录好File和Position====，其实这一步也可以放在my.cnf里面

宿主机上输入命令

>  mysql -u root -h 127.0.0.1 -P 13306 -p 

> stop slave;

> CHANGE MASTER TO
MASTER_HOST='192.168.1.52',
MASTER_USER='slave_user',
MASTER_PASSWORD='123456',
MASTER_PORT=3306,
MASTER_LOG_FILE='mysql-bin.000001',
MASTER_LOG_POS=120;

> start slave;

3. 查看状态
```
mysql> show slave status\G;
*************************** 1. row ***************************
               Slave_IO_State: Waiting for master to send event
                  Master_Host: 192.168.1.39
                  Master_User: slave_user
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000001
          Read_Master_Log_Pos: 2460
               Relay_Log_File: vmware40-relay-bin.000007
                Relay_Log_Pos: 597
        Relay_Master_Log_File: mysql-bin.000001
             Slave_IO_Running: Yes
            Slave_SQL_Running: Yes
            ....
            Seconds_Behind_Master: 0 #表示已同步
            ....

```

### 错误
1. Slave_SQL_Running: no 请重复执行以下内容，直至yes

```
stop slave;
set GLOBAL SQL_SLAVE_SKIP_COUNTER=1;
start slave;

```
2. 主从同步报错Fatal error: The slave I/O thread stops because master and slave have equal MySQL server
    - 原因：
        - mysql 5.6的复制引入了uuid的概念，各个复制结构中的server_uuid得保证不一样，但是查看到直接copy  data文件夹后server_uuid是相同的，show variables like '%server_uuid%'; 
    - 解决方法：
        - 找到data文件夹下的auto.cnf文件，修改里面的uuid值，保证各个db的uuid不一样，重启db即可（一般在/usr/local/mysql/data下，没有就自己find找下）



