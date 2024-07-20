#### 前提
已经安装了单击版redis

6379作为master

6380 6381 作为slave
 
#### Master配置:
 
1:关闭rdb快照(备份工作交给slave)
2:可以开启aof

#### slave配置:
1. 声明slave-of
2. 配置密码[如果master有密码]
3. [某1个]slave打开 rdb快照功能
4. 配置是否只读[slave-read-only]

#### redis主从复制的缺陷

每次salave断开后,(无论是主动断开,还是网络故障)
再连接master

都要master全部dump出来rdb,再aof,即同步的过程都要重新执行1遍.

所以要记住---多台slave不要一下都启动起来,否则master可能IO剧增




## 开始动手

找到redis.conf

> cp redis.conf redis-6380.conf

先只复制一个，修改好后再复制6380.conf 全局替换端口为6381 这样感觉比较方便

修改 6380

> vim redis-6380.conf

```
port 6380
pidfile /var/run/redis-6380.pid
dbfilename dump-6380.rdb  关掉master的rdb 用1号slave开启rdb  分担master的压力
logfile /usr/local/redis/var/redis-6380.log
slaveof 127.0.0.1 6379
```

> cp redis-6380.conf redis-6381.conf

> vim redis-6381.conf

> :%s/6380/6381/g

关闭 6381 rdb


```
#save 900 1
#save 300 10
#save 60 10000
```

打开 6379 aof (可以不打开)


```
appendonly yes
```

## 开启服务

> redis-server redis.conf

> redis-server redis-6380.conf 

> redis-server redis-6381.conf 

查看进程

```
➜  redis ps -aux|grep redis
root      13737  0.1  0.9 162596  9936 ?        Rsl  17:41   0:00 redis-server 127.0.0.1:6379
root      13760  0.1  0.9 162596  9872 ?        Rsl  17:41   0:00 redis-server 127.0.0.1:6380
root      13782  0.1  0.7 156452  7820 ?        Ssl  17:41   0:00 redis-server 127.0.0.1:6381
```

## 验证


```
➜ redis-cli -h 127.0.0.1 -p 6379
127.0.0.1:6379> keys *
(empty list or set)
127.0.0.1:6379> set test 1
OK

```


```
➜redis-cli -h 127.0.0.1 -p 6380
127.0.0.1:6380> keys *
1) "test"
```

```
➜ redis-cli -h 127.0.0.1 -p 6381   
127.0.0.1:6381> get test
"1"
```



## 其他设置
### 密码

> vim redis.conf

```
requirepass a123456
```

> vim redis-6380.conf

```
masterauth a123456
```

> vim redis-6381.conf

```
masterauth a123456
```

##### 重启

> pkill -9 redis

> redis-server redis.conf

> redis-server redis-6380.conf 

> redis-server redis-6381.conf 