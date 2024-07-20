#### 目标
搭建两台MySQL服务器,实现主主复制做双机热备份
#### 环境
主数据库1： 192.168.1.39

主数据库2： 192.168.1.40

mysql版本都是5.7.25, for linux
#### 数据库准备
1. 保证两个数据库中的库和数据是一致的
#### 要点
主主复制中必须要解决的事情就是自增主键的问题。如果服务器1的数据库表1主键id增加到10了，此时二进制数据还没到达服务器2，如果服务器2对应的表恰好要插入数据，那么新数据主键id也是10，那就乱套了。
#### 修改配置文件
1.修改 主机1的配置文件

vim /etc/my.cnf
```
# 主从复制 #
log-bin=mysql-bin
server-id=1
replicate-do-db=test  #要同步多个数据库，就多加几个replicate-db-db=数据库名 
#binlog-ignore-db=mysql //要忽略的数据库
innodb_flush_log_at_trx_commit=1
sync_binlog=1
auto_increment_increment=2   #步进值auto_imcrement。一般有n台主MySQL就填n
auto_increment_offset=1   #起始值。一般填第n台主MySQL。此时为第一台主MySQL

```
重启主机1的mysql
```
systemctl restart mysql
```
2.修改 主机2的配置文件

vim /etc/my.cnf
```
server-id=2
log-bin=mysql-bin
auto_increment_increment=2
auto_increment_offset=2
replicate-do-db=test
```
重启主机2的mysql
```
systemctl restart mysql
```

#### 配置主库1的数据备份到主库2
1. 登录主库1，在主库1中添加一个主库2可以登录的备份账号
```msyql
mysql>GRANT REPLICATION SLAVE ON *.* TO 'master_from_ser39'@'%' IDENTIFIED BY '123456';
mysql>FLUSH PRIVILEGES;
```
2. 在主库1中查看master 状态
```msyql
mysql> show master status;
+------------------+----------+--------------+------------------+-------------------+
| File             | Position | Binlog_Do_DB | Binlog_Ignore_DB | Executed_Gtid_Set |
+------------------+----------+--------------+------------------+-------------------+
| mysql-bin.000002 |     1168 | test      |                  |                   |
+------------------+----------+--------------+------------------+-------------------+
1 row in set (0.00 sec)
```
==**注意：记录好File和Position，主库2要用**==

3. 登录到主库2,change master
```
mysql> stop slave;                          #这里要是之前有配置就先关闭
mysql> CHANGE MASTER TO
-> MASTER_HOST='192.168.1.39',
-> MASTER_USER='master_from_ser39',
-> MASTER_PASSWORD='123456',
-> MASTER_PORT=3306,
-> MASTER_LOG_FILE='mysql-bin.000002',      #主库1中的File
-> MASTER_LOG_POS=1168;                     #主库1中的Position
mysql> start slave;                         #change后要start slave
```
下面是可以直接复制使用的命令

```
CHANGE MASTER TO
MASTER_HOST='192.168.1.39',
MASTER_USER='master_from_ser39',
MASTER_PASSWORD='123456',
MASTER_PORT=3306,
MASTER_LOG_FILE='mysql-bin.000002',
MASTER_LOG_POS=1168;
```
4. 查看状态
```
mysql> show slave status\G;
*************************** 1. row ***************************
               Slave_IO_State: Waiting for master to send event
                  Master_Host: 192.168.1.39
                  Master_User: master_from_ser39
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000002
          Read_Master_Log_Pos: 1168
               Relay_Log_File: vmware40-relay-bin.000002
                Relay_Log_Pos: 320
        Relay_Master_Log_File: mysql-bin.000002
             Slave_IO_Running: Yes
            Slave_SQL_Running: Yes
              Replicate_Do_DB: test
            ....
            Seconds_Behind_Master: 0 #表示已同步
            ....

```
### ==至此 主库1备份到主库2的配置完成了, 下面是重复步骤，这里我也贴出来==

---
#### 配置主库2的数据备份到主库1
1. 登录主库2，在主库2中添加一个主库1可以登录的备份账号
```msyql
mysql>GRANT REPLICATION SLAVE ON *.* TO 'master_from_ser40'@'%' IDENTIFIED BY '123456';
mysql>FLUSH PRIVILEGES;
```
2. 在主库2中查看master 状态
```msyql
mysql> show master status;
+------------------+----------+--------------+------------------+-------------------+
| File             | Position | Binlog_Do_DB | Binlog_Ignore_DB | Executed_Gtid_Set |
+------------------+----------+--------------+------------------+-------------------+
| mysql-bin.000001 |      886 |              |                  |                   |
+------------------+----------+--------------+------------------+-------------------+
1 row in set (0.00 sec)
```
==**注意：记录好File和Position，主库1要用**==

3. 登录到主库1,change master
```
mysql> stop slave;                          #这里要是之前有配置就先关闭
mysql> CHANGE MASTER TO
-> MASTER_HOST='192.168.1.40',
-> MASTER_USER='master_from_ser40',
-> MASTER_PASSWORD='123456',
-> MASTER_PORT=3306,
-> MASTER_LOG_FILE='mysql-bin.000001',      #主库2中的File
-> MASTER_LOG_POS=886;                      #主库2中的Position
mysql> start slave;                         #change后要start slave
```
下面是可以直接复制使用的命令

```
CHANGE MASTER TO
MASTER_HOST='192.168.1.40',
MASTER_USER='master_from_ser40',
MASTER_PASSWORD='123456',
MASTER_PORT=3306,
MASTER_LOG_FILE='mysql-bin.000001',
MASTER_LOG_POS=886;
```
4. 查看状态
```
mysql> show slave status\G;
*************************** 1. row ***************************
               Slave_IO_State: Waiting for master to send event
                  Master_Host: 192.168.1.40
                  Master_User: master_from_ser40
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000001
          Read_Master_Log_Pos: 886
               Relay_Log_File: vmware39-relay-bin.000002
                Relay_Log_Pos: 320
        Relay_Master_Log_File: mysql-bin.000001
             Slave_IO_Running: Yes
            Slave_SQL_Running: Yes
              Replicate_Do_DB: test

            ....
            Seconds_Behind_Master: 0 #表示已同步
            ....

```
### 至此 主库2备份到主库1的配置完成了

---


> #### 注意事项和常见问题
> ##### 注意事项:
>  1. 主主复制配置文件中auto_increment_increment和auto_increment_offset只能保证主键不重复，却不能保证主键有序。
> 2. 当配置完成Slave_IO_Running、Slave_SQL_Running不全为YES时，show slave status\G信息中有错误提示，可根据错误提示进行更正。
> 3. Slave_IO_Running、Slave_SQL_Running不全为YES时，大多数问题都是数据不统一导致。
> ##### 常见问题:
>1. 两台数据库都存在db数据库，而第一台MySQL db中有tab1，第二台MySQL db中没有tab1，那肯定不能成功。
>2. 已经获取了数据的二进制日志名和位置，又进行了数据操作，导致POS发生变更。在配置CHANGE MASTER时还是用到之前的POS。
>3. stop slave后，数据变更，再start slave。出错。
>4. 终极更正法：重新执行一遍CHANGE MASTER就好了。


## 问题

如果后期需要加服务器,这个办法就有限制了.
我们可以在业务逻辑上来解决,
比如在oracle 有sequnce,序列.
序列每次访问,生成递增/递减的数据.

1、以redis为例, 我们可以专门构建一个 global:userid
每次PHP插入Mysql前,先 incr->global:userid, 得到一个不重复的userid.

2、预先设置increment和offset 设置大点