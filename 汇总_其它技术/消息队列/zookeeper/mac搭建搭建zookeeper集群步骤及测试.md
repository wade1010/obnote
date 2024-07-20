1、安装zk，brew直接装就行这里就不介绍了

2、找到zoo_sample.cfg

```javascript
find / -name 'zoo_sample.cfg' 2> /dev/null
```

我的是 /usr/local/etc/zookeeper/zoo_sample.cfg

里面的内容可以看下，除去注释也就下面几行,为了方便，后面操作就不复制这个zoo_sample.cfg文件了，而是对下面几行内容做些修改后直接用echo命令写入到指定文件，具体见后面操作

> tickTime=2000

> initLimit=10

> syncLimit=5

> dataDir=/usr/local/var/run/zookeeper/data

> clientPort=2181



3、开始整目录



```javascript
mkdir zookeeper-cluster
cd zookeeper-cluster
mkdir -p {zk1,zk2,zk3}/data
mkdir -p {zk1,zk2,zk3}/log
```



4、配置myid

cat << EOF > zk1/data/myid

1

EOF



cat << EOF > zk2/data/myid

2

EOF



cat << EOF > zk3/data/myid

3

EOF



5、配置cfg文件

cat << EOF > zk1/zoo.cfg

tickTime=2000

initLimit=10

syncLimit=5

dataDir= /Users/bob/workspace/service/zookeeper-cluster/zk1/data

dataLogDir= /Users/bob/workspace/service/zookeeper-cluster/zk1/log

clientPort= 2181

server.1= 0.0.0.0:2887:3887

server.2= 0.0.0.0:2888:3888

server.3= 0.0.0.0:2889:3889

EOF



cat << EOF > zk2/zoo.cfg

tickTime=2000

initLimit=10

syncLimit=5

dataDir=/Users/bob/workspace/service/zookeeper-cluster/zk2/data

dataLogDir=/Users/bob/workspace/service/zookeeper-cluster/zk2/log

clientPort=2182

server.1=127.0.0.1:2887:3887

server.2=127.0.0.1:2888:3888

server.3=127.0.0.1:2889:3889

EOF



cat << EOF > zk3/zoo.cfg

tickTime=2000

initLimit=10

syncLimit=5

dataDir=/Users/bob/workspace/service/zookeeper-cluster/zk3/data

dataLogDir=/Users/bob/workspace/service/zookeeper-cluster/zk3/log

clientPort=2183

server.1=127.0.0.1:2887:3887

server.2=127.0.0.1:2888:3888

server.3=127.0.0.1:2889:3889

EOF





> 需要注意的是clientPort这个端口如果你是在1台机器上部署多个server,那么每台机器都要不同的clientPort，比如我server1是2181,server2是2182，server3是2183，dataDir和dataLogDir也需要区分下。

> 

> 最后几行唯一需要注意的地方就是 server.X 这个数字就是对应 data/myid中的数字。你在3个server的myid文件中分别写入了1，2，3，那么每个server中的zoo.cfg都配server.1,server.2,server.3就OK了。因为在同一台机器上，后面连着的2个端口3个server都不要一样，否则端口冲突，其中第一个端口用来集群成员的信息交换，第二个端口是在leader挂掉时专门用来进行选举leader所用。





6、查看下zkServer在哪里

find / -name '*zkServer*' 2> /dev/null

我的是 /usr/local/bin/zkServer



7、启动集群  如果我们按顺序启动 zk1  zk2 zk3  猜猜哪个会成为leader呢？哈哈

/usr/local/bin/zkServer start /Users/bob/workspace/service/zookeeper-cluster/zk1/zoo.cfg

/usr/local/bin/zkServer start /Users/bob/workspace/service/zookeeper-cluster/zk2/zoo.cfg

/usr/local/bin/zkServer start /Users/bob/workspace/service/zookeeper-cluster/zk3/zoo.cfg



8、查看状态

/usr/local/bin/zkServer status /Users/bob/workspace/service/zookeeper-cluster/zk1/zoo.cfg 

/usr/local/bin/zkServer status /Users/bob/workspace/service/zookeeper-cluster/zk2/zoo.cfg 

/usr/local/bin/zkServer status /Users/bob/workspace/service/zookeeper-cluster/zk3/zoo.cfg 



```javascript
zookeeper-cluster % /usr/local/bin/zkServer status /Users/bob/workspace/service/zookeeper-cluster/zk1/zoo.cfg 
ZooKeeper JMX enabled by default
Using config: /Users/bob/workspace/service/zookeeper-cluster/zk1/zoo.cfg
Client port found: 2181. Client address: localhost. Client SSL: false.
Mode: follower
zookeeper-cluster % /usr/local/bin/zkServer status /Users/bob/workspace/service/zookeeper-cluster/zk2/zoo.cfg 
ZooKeeper JMX enabled by default
Using config: /Users/bob/workspace/service/zookeeper-cluster/zk2/zoo.cfg
Client port found: 2182. Client address: localhost. Client SSL: false.
Mode: leader
zookeeper-cluster % /usr/local/bin/zkServer status /Users/bob/workspace/service/zookeeper-cluster/zk3/zoo.cfg 
ZooKeeper JMX enabled by default
Using config: /Users/bob/workspace/service/zookeeper-cluster/zk3/zoo.cfg
Client port found: 2183. Client address: localhost. Client SSL: false.
Mode: follower
```



没错 zk2是leader





9、测试

zkCli -server 127.0.0.1:2181  这里连接zk1



查看所有命令输入help即可查看

```javascript
[0] % zkCli -server 127.0.0.1:2181
Connecting to 127.0.0.1:2181
Welcome to ZooKeeper!
JLine support is enabled

WATCHER::

WatchedEvent state:SyncConnected type:None path:null
[zk: 127.0.0.1:2181(CONNECTED) 0] help
ZooKeeper -server host:port -client-configuration properties-file cmd args
	addWatch [-m mode] path # optional mode is one of [PERSISTENT, PERSISTENT_RECURSIVE] - default is PERSISTENT_RECURSIVE
	addauth scheme auth
	close 
	config [-c] [-w] [-s]
	connect host:port
	create [-s] [-e] [-c] [-t ttl] path [data] [acl]
	delete [-v version] path
	deleteall path [-b batch size]
	delquota [-n|-b] path
	get [-s] [-w] path
	getAcl [-s] path
	getAllChildrenNumber path
	getEphemerals path
	history 
	listquota path
	ls [-s] [-w] [-R] path
	printwatches on|off
	quit 
	reconfig [-s] [-v version] [[-file path] | [-members serverID=host:port1:port2;port3[,...]*]] | [-add serverId=host:port1:port2;port3[,...]]* [-remove serverId[,...]*]
	redo cmdno
	removewatches path [-c|-d|-a] [-l]
	set [-s] [-v version] path data
	setAcl [-s] [-v version] [-R] path acl
	setquota -n|-b val path
	stat [-w] path
	sync path
	version 
```





create -e /temp hello



ls -s /



然后关闭leader节点，这里是zk2

/usr/local/bin/zkServer stop /Users/bob/workspace/service/zookeeper-cluster/zk2/zoo.cfg



发现zk3变成leader了



连接zk1的cli 执行 ls -s /   发现还是以用的



继续关闭leader节点，也就是zk3

发现不能用了,读写都不行



[zk: 127.0.0.1:2181(CONNECTING) 12] ls -s /

KeeperErrorCode = ConnectionLoss for /

[zk: 127.0.0.1:2181(CONNECTING) 13] create -e /temp2 hello

KeeperErrorCode = ConnectionLoss for /temp2







重新启动zk2，发现zk1变成了leader



且读写都正常了



这是再关闭zk2  也就是关闭follower，之前剩余两个是关闭leader



发现也是不能读写的





















