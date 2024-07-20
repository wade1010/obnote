昨天下班前站会讨论了下zookeeper，开始是想通过zk来获取每个节点的状态，经过一些讨论，我们放弃使用zk来获取节点的状态，改用调用pacemaker命令来获取状态。后来晚上回来研究了下zk，主要是作为状态管理方面，希望对我们项目有所帮助。



场景：

我们现在应该是某个程序里面通过zk client向server注册一个ephemeral（短暂的,客户端和服务器端断开连接后，创建的节点自己删除）类型的节点，同时zk client会注册一个监听。

原理：

大概就是zk client 创建两个线程，一个负责网络通信，一个负责监听，通过网络通信线程间注册的监听事件发送给zk，zk收到注册监听后，会把注册的监听事件放到一个列表中。zk监听到有数据或者路径变化，就会将消息发送给zk client的监听线程，然后监听线程调用process()方法处理



我觉得需要注意的问题：

1、session失效的问题

zk client和zk建立长连接，每隔10秒向服务端发送一次心跳（之前有讨论说不是心跳，但是我有看zk源码，确实是有心跳，后面会附上源码），服务端会返回响应。这就是一个Session连接。

一般来说client主动关闭连接认为就是一次session失效。另外也有可能因为其它原因，如zk client 和zk之间的网络抖动超时导致的session失效，在zk看来，无法区分session失效是何种情况，发生session失效后，那么zk以为客户端挂了，就会删除ephemeral节点。

如过我们只是用zk进行简单查询服务，session失效后，zk client只需要重新建立连接即可。而对于需要处理ephemeral节点以及各种watcher的服务来说，应用程序需要处理session失效或者重连带来的副作用。

session重连其实很简单，难的就是ephemeral节点被删除后对我们的业务会不会有影响。

举个例子(不一定是我们的业务场景)：

高并发场景下，需要获取分布式锁，假如是用zk这个ephemeral节点特性来实现。由于网络抖动，客户端session连接断了，那么zk以为客户端挂了，就会删除临时节点，这时候其他客户端就可以获取到分布式锁了,就可能产生并发问题。

2、zk本身不是为高可用设计的，master撑不住高流量容易导致系统crash，选举leader过程中，系统不可用，由于zab协议是对Paxos的改进，我知道的一点是Paxos发送proposal的节点可以是多个(这一点可能导致选举过程中无限循环)，而zab只允许一个。

3、zk不保证'跨session'一致性，比如session-A写入数据，session-B未必能读到。

同一session：顺序一致性，保证版本不回退；

跨session：最终一致性，你最终会读到新数据的；

4、watch是一次性的，这里我知道的常用方法是，监听到变化后，重新再注册一次监听。



附：

1、Apache How should I handle SESSION_EXPIRED?

https://cwiki.apache.org/confluence/display/HADOOP2/ZooKeeper+FAQ#ZooKeeperFAQ-3



2、zk client 心跳 源码  下面连接的1178行开始

https://github.com/apache/zookeeper/blob/master/zookeeper-server/src/main/java/org/apache/zookeeper/ClientCnxn.java



3、2、zk处理 心跳 源码  下面连接的147行开始

https://github.com/apache/zookeeper/blob/master/zookeeper-server/src/main/java/org/apache/zookeeper/server/FinalRequestProcessor.java