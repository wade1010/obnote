#### 前提

把 A 服务器(本机6379)的rdb 文件 迁移到 B 服务器(本机6380)

#### 关闭B服务器的redis的aof日志功能（如果不关闭aof，默认用aof文件来恢复数据） 将dbfilename 改成dump-6382.rdb

> vim reids.conf

```
appendonly no
dbfilename dump-6382.rdb
```


#### A 服务器 利用 save  命令 生成最新rdb 


```
➜redis-cli -h 127.0.0.1 -p 6379
127.0.0.1:6379> keys *
1) "test"
2) "test2"
127.0.0.1:6379> save
OK
127.0.0.1:6379> quit
```

> pkill -9 redis  

这一步很重要 要不然复制的rdb文件不能被 B服务器使用,因为redis进程使用过程中句柄是打开的


#### 复制rdb 文件

> cp dump-6379.rdb dump-6380.rdb

#### 启动B服务器redis


```
➜   redis-server  redis-6382.conf
➜   redis-cli -h 127.0.0.1 -p 6382      
127.0.0.1:6382> keys *
1) "test2"
2) "test"
```

就看到了 6379 的数据了


### 开启aof

rdb文件如果导入成功后，开启aof功能会在数据库目录下生成一个appendonly.aof文件


