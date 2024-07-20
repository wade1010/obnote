p3522 192.168.100.13

p3722 192.168.100.10

p3822 192.168.100.11

p3922 192.168.100.12



vim /etc/hosts

```javascript
192.168.100.13 node1
192.168.100.10 node2
192.168.100.11 node3
192.168.100.12 node4
```



docker pull zookeeper



mkdir /opt/zookeeper&&mkdir -p /opt/zookeeper/data && mkdir -p /opt/zookeeper/conf



cd /opt/zookeeper



配置myid  3台服务器分别执行

```javascript
echo "0" > data/myid

echo "1" > data/myid

echo "2" > data/myid
```



配置zoo.cfg



vim conf/zoo.cfg

```javascript
clientPort=2181
dataDir=/data
dataLogDir=/data/log
tickTime=2000
initLimit=5
syncLimit=2
autopurge.snapRetainCount=3
autopurge.purgeInterval=0
maxClientCnxns=60
server.0=192.168.100.10:2888:3888
server.1=192.168.100.11:2888:3888
server.2=192.168.100.12:2888:3888
```





启动

docker run --network host -v /opt/zookeeper/data:/data -v /opt/zookeeper/conf:/conf --name zk -d zookeeper

测试是否成功

选一台执行

docker exec -it zk /bin/sh

./bin/zkServer.sh status







