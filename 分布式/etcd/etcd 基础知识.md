**etcd 基础知识**

一、etcd 功能模块组成

etcd 可分为Client层、API 层、Raft 算法层、逻辑层与存储层

Client 层:

Client 层包括client v2 与v3 两个大版本API 客户端库，提供了简洁易用的API，同时支持负载均衡、节点故障自动转移，可极大降低业务使用etcd 复杂度，提升开发效率、服务可用性。

API 层:

API 网络层主要包括client 访问server 与server 节点之间的通信协议。 节点间通过Raft 算法实现数据赋值和Leader 选举等功能时使用的HTTP协议

Raft 算法层:

Raft 算法层实现了Leader 选举、日志复制、ReadIndex 等核心算法特性，用于保障etcd 多个节点间数据一致性、提升服务可用性等，是etcd 的基石和亮点

功能逻辑层:

etcd 核心特性实现层，如典型的KVServer 模块、 MVCC模块、Auth 鉴权模块、Lease租约模块、 Compactor压缩模块等，其中MVCC模块主要由boltdb模块组成。

存储层

存储层包预写日志(WAL)模块、快照(Snapshot)模块、boltdb模块。其中WAL 可保障etcd crash后数据不丢失，boltdb则保存了集群元数据和用户写入的数据。

二、etcd 读

串行读

1、当client 发起一个更新hello 为world 请求后，若leader 收到写请求，它会将此请求持久到WAL 日志，并广播给各个节点，若一半以上节点持久化成功，则该请求对应的日志条目被标识为已提交，etcdserver 模块异步从Raft 模块获取已提交的日志条目，应用到状态机(boltdb).

2、此时若client 发起一个读取hello 的请求，假设此请求从状态机中读取，如果连接到是C节点，若C 节点磁盘I/O 出现波动，可能导致它们已提交的日志 条目很慢，则会出现更新hello 为world的写命令，在client 读hello 的时候还未被提交到状态机，因为就可能读取到旧数据。

3、由状态机数据返回、无需通过Raft 协议与集群交互的模式，在etcd里叫做串行读，他具有低延迟、高吞吐量的特点，适合对数据一致性要求不高的场景

线性读

1、当收到一个线性请求时，它首先会从Leader获取集群最新的已提交的日志索引(committed  index)

2、Leader 收到ReadIndex 请求时，为防止脑裂等异常场景，会向Flower 节点发送心跳确认，一半以上节点确认Leader 身份证后才能将已提交的索引(committed index)返回给节点C

3、C节点会等待，直到状态机应用索引(applied index)大于Leader 的已提交索引时(committed index)然后去通知读请求，数据已赶上Leader，你可以去状态机中访问数据

Leader选举

1、当etcd server 收到client 发起的put hello 写请求后，KV 模块会向Raft 模块提交一个put 提案，我们知道只有集群Leader 才能处理写提案，如果此集群中无Leader，整个请求就会超时

2、首先在Raft 协议中它定义了集群中如下节点状态，任何时刻，每个节点肯定处于其中一个状态：

Follower， 跟随者，同步从Leader 收到的日志，etcd 启动的时候默认为此状态：

Candidate，竞选者，可以发起Leader 选举

Leader， 集群领导者，唯一性，拥有同步日志的的特权，需定时广播心跳给Follower 节点，以维持领导者身份

3、当Follwer 节点接收到Leader 节点心跳信息超时后，它会转成Candidate 节点，并发起竞选Leader 投票，若获得集群多数节点的支持后，它就可转变成Leader 节点