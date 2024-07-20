1. 选择一个目录创建redis-cluster目录（名字你随便取）
> mkdir redis-cluster
2. cd redis-cluster
3. 创建 redis-cluster.tmpl 插入内容如下 
> vim redis-cluster.tmpl
```
# redis端口
port ${PORT}
# 关闭保护模式
protected-mode no
# 开启集群
cluster-enabled yes
# 集群节点配置
cluster-config-file nodes-${PORT}.conf
# 超时
cluster-node-timeout 5000
# 集群节点IP host模式为宿主机IP
cluster-announce-ip 192.168.1.10
# 集群节点端口 7001 - 7006
cluster-announce-port ${PORT}
cluster-announce-bus-port 1${PORT}
# 开启 appendonly 备份模式
appendonly yes
# 每秒钟备份
appendfsync everysec
# 对aof文件进行压缩时，是否执行同步操作
no-appendfsync-on-rewrite no
# 当目前aof文件大小超过上一次重写时的aof文件大小的100%时会再次进行重写
auto-aof-rewrite-percentage 100
# 重写前AOF文件的大小最小值 默认 64mb
auto-aof-rewrite-min-size 64mb

```

### 注意上面cluster-announce-ip 是自己宿主机的IP  我的是在同一个宿主机上安装 IP是192.168.1.10

4. 创建 redis-cluster-config.sh 插入如下内容
> vim redis-cluster-config.sh

```
for port in `seq 7001 7006`; do \
  mkdir -p ./redis-cluster/${port}/conf \
  && PORT=${port} envsubst < ./redis-cluster.tmpl > ./redis-cluster/${port}/conf/redis.conf \
  && mkdir -p ./redis-cluster/${port}/data; \
done
```
5 . 创建 docker-redis-cluster.yml 插入如下内容
> vim docker-redis-cluster.yml

```
version: '3.7'

services:
  redis7001:
    image: 'redis'
    container_name: redis7001
    command:
      ["redis-server", "/usr/local/etc/redis/redis.conf"]
    volumes:
      - ./redis-cluster/7001/conf/redis.conf:/usr/local/etc/redis/redis.conf
      - ./redis-cluster/7001/data:/data
    ports:
      - "7001:7001"
      - "17001:17001"
    environment:
      # 设置时区为上海，否则时间会有问题
      - TZ=Asia/Shanghai


  redis7002:
    image: 'redis'
    container_name: redis7002
    command:
      ["redis-server", "/usr/local/etc/redis/redis.conf"]
    volumes:
      - ./redis-cluster/7002/conf/redis.conf:/usr/local/etc/redis/redis.conf
      - ./redis-cluster/7002/data:/data
    ports:
      - "7002:7002"
      - "17002:17002"
    environment:
      # 设置时区为上海，否则时间会有问题
      - TZ=Asia/Shanghai


  redis7003:
    image: 'redis'
    container_name: redis7003
    command:
      ["redis-server", "/usr/local/etc/redis/redis.conf"]
    volumes:
      - ./redis-cluster/7003/conf/redis.conf:/usr/local/etc/redis/redis.conf
      - ./redis-cluster/7003/data:/data
    ports:
      - "7003:7003"
      - "17003:17003"
    environment:
      # 设置时区为上海，否则时间会有问题
      - TZ=Asia/Shanghai


  redis7004:
    image: 'redis'
    container_name: redis7004
    command:
      ["redis-server", "/usr/local/etc/redis/redis.conf"]
    volumes:
      - ./redis-cluster/7004/conf/redis.conf:/usr/local/etc/redis/redis.conf
      - ./redis-cluster/7004/data:/data
    ports:
      - "7004:7004"
      - "17004:17004"
    environment:
      # 设置时区为上海，否则时间会有问题
      - TZ=Asia/Shanghai


  redis7005:
    image: 'redis'
    container_name: redis7005
    command:
      ["redis-server", "/usr/local/etc/redis/redis.conf"]
    volumes:
      - ./redis-cluster/7005/conf/redis.conf:/usr/local/etc/redis/redis.conf
      - ./redis-cluster/7005/data:/data
    ports:
      - "7005:7005"
      - "17005:17005"
    environment:
      # 设置时区为上海，否则时间会有问题
      - TZ=Asia/Shanghai


  redis7006:
    image: 'redis'
    container_name: redis7006
    command:
      ["redis-server", "/usr/local/etc/redis/redis.conf"]
    volumes:
      - ./redis-cluster/7006/conf/redis.conf:/usr/local/etc/redis/redis.conf
      - ./redis-cluster/7006/data:/data
    ports:
      - "7006:7006"
      - "17006:17006"
    environment:
      # 设置时区为上海，否则时间会有问题
      - TZ=Asia/Shanghai
```

6. 执行 redis-cluster-config.sh
> sh redis-cluster-config.sh

7. 使用docker-compose启动 未安装命令的 执行Google安装

```
docker-compose -f docker-redis-cluster.yml up -d
```
terminal 结果
```
% docker-compose -f docker-redis-cluster.yml up -d
Creating network "redis-cluster_default" with the default driver
Creating redis7003 ... done
Creating redis7006 ... done
Creating redis7004 ... done
Creating redis7001 ... done
Creating redis7002 ... done
Creating redis7005 ... done
```

8. 执行下面命令

```
docker exec -it redis7001 redis-cli -p 7001 --cluster create 192.168.1.10:7001 192.168.1.10:7002 192.168.1.10:7003 192.168.1.10:7004 192.168.1.10:7005 192.168.1.10:7006 --cluster-replicas 1
```

terminal 结果

```
% docker exec -it redis7001 redis-cli -p 7001 --cluster create 192.168.1.10:7001 192.168.1.10:7002 192.168.1.10:7003 192.168.1.10:7004 192.168.1.10:7005 192.168.1.10:7006 --cluster-replicas 1
>>> Performing hash slots allocation on 6 nodes...
Master[0] -> Slots 0 - 5460
Master[1] -> Slots 5461 - 10922
Master[2] -> Slots 10923 - 16383
Adding replica 192.168.1.10:7005 to 192.168.1.10:7001
Adding replica 192.168.1.10:7006 to 192.168.1.10:7002
Adding replica 192.168.1.10:7004 to 192.168.1.10:7003
>>> Trying to optimize slaves allocation for anti-affinity
[WARNING] Some slaves are in the same host as their master
M: 9abe35769fa2f4329694fa69f5f8832b6518693f 192.168.1.10:7001
   slots:[0-5460] (5461 slots) master
M: 88bb010e555fdaec7ff594bcab24815fe2f31e0d 192.168.1.10:7002
   slots:[5461-10922] (5462 slots) master
M: bc114a288d92287b6635981335b274dffdd54fe6 192.168.1.10:7003
   slots:[10923-16383] (5461 slots) master
S: 99b604fd4dd6b9ba58812796c956f0d1f2d2d9e7 192.168.1.10:7004
   replicates 9abe35769fa2f4329694fa69f5f8832b6518693f
S: 696eb88f4755241f00a5e865c80b40660fe7306e 192.168.1.10:7005
   replicates 88bb010e555fdaec7ff594bcab24815fe2f31e0d
S: 7507ea21d47a04611446831385cca7d1c9c6a5b2 192.168.1.10:7006
   replicates bc114a288d92287b6635981335b274dffdd54fe6
Can I set the above configuration? (type 'yes' to accept): yes
>>> Nodes configuration updated
>>> Assign a different config epoch to each node
>>> Sending CLUSTER MEET messages to join the cluster
Waiting for the cluster to join

>>> Performing Cluster Check (using node 192.168.1.10:7001)
M: 9abe35769fa2f4329694fa69f5f8832b6518693f 192.168.1.10:7001
   slots:[0-5460] (5461 slots) master
   1 additional replica(s)
S: 99b604fd4dd6b9ba58812796c956f0d1f2d2d9e7 192.168.1.10:7004
   slots: (0 slots) slave
   replicates 9abe35769fa2f4329694fa69f5f8832b6518693f
S: 696eb88f4755241f00a5e865c80b40660fe7306e 192.168.1.10:7005
   slots: (0 slots) slave
   replicates 88bb010e555fdaec7ff594bcab24815fe2f31e0d
M: 88bb010e555fdaec7ff594bcab24815fe2f31e0d 192.168.1.10:7002
   slots:[5461-10922] (5462 slots) master
   1 additional replica(s)
S: 7507ea21d47a04611446831385cca7d1c9c6a5b2 192.168.1.10:7006
   slots: (0 slots) slave
   replicates bc114a288d92287b6635981335b274dffdd54fe6
M: bc114a288d92287b6635981335b274dffdd54fe6 192.168.1.10:7003
   slots:[10923-16383] (5461 slots) master
   1 additional replica(s)
[OK] All nodes agree about slots configuration.
>>> Check for open slots...
>>> Check slots coverage...
[OK] All 16384 slots covered.
```

### 会问你要不要分配slots 输入yes回车就行

至此就完成了。

我是mac使用docker安装，按流程下来就很顺利

到Linux安装 需要开放端口 否则会一直 Waiting for the cluster to join


```
firewall-cmd --zone=public --add-port=7001/tcp --permanent
firewall-cmd --zone=public --add-port=17001/tcp --permanent
firewall-cmd --zone=public --add-port=7002/tcp --permanent
firewall-cmd --zone=public --add-port=17002/tcp --permanent
firewall-cmd --zone=public --add-port=7003/tcp --permanent
firewall-cmd --zone=public --add-port=17003/tcp --permanent
firewall-cmd --zone=public --add-port=7004/tcp --permanent
firewall-cmd --zone=public --add-port=17004/tcp --permanent
firewall-cmd --zone=public --add-port=7005/tcp --permanent
firewall-cmd --zone=public --add-port=17005/tcp --permanent
firewall-cmd --zone=public --add-port=7006/tcp --permanent
firewall-cmd --zone=public --add-port=17006/tcp --permanent
firewall-cmd --reload
```

### 测试
```
[@cmbp:~/workspace/redis-cluster]
% redis-cli -h 127.0.0.1 -p 7001 -c
127.0.0.1:7001> set aa 1111
OK
127.0.0.1:7001> 
[@cmbp:~/workspace/redis-cluster]
% redis-cli -h 127.0.0.1 -p 7002 -c
127.0.0.1:7002> get aa
-> Redirected to slot [1180] located at 192.168.1.10:7001
"1111"
192.168.1.10:7001>
```