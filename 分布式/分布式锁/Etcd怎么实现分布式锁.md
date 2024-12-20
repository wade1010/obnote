首先思考下Etcd是什么？可能很多人第一反应可能是一个键值存储仓库，却没有重视官方定义的后半句，用于配置共享和服务发现。

A highly-available key value store for shared configuration and service discovery.

实际上，etcd 作为一个受到 ZooKeeper 与 doozer 启发而催生的项目，除了拥有与之类似的功能外，更专注于以下四点。



1. 简单：基于 HTTP+JSON 的 API 让你用 curl 就可以轻松使用。

1. 安全：可选 SSL 客户认证机制。

1. 快速：每个实例每秒支持一千次写操作。

1. 可信：使用 Raft 算法充分实现了分布式。



但是这里我们主要讲述Etcd如何实现分布式锁?

因为 Etcd 使用 Raft 算法保持了数据的强一致性，某次操作存储到集群中的值必然是全局一致的，所以很容易实现分布式锁。锁服务有两种使用方式，一是保持独占，二是控制时序。



1. 保持独占即所有获取锁的用户最终只有一个可以得到。etcd 为此提供了一套实现分布式锁原子操作 CAS（CompareAndSwap）的 API。通过设置prevExist值，可以保证在多个节点同时去创建某个目录时，只有一个成功。而创建成功的用户就可以认为是获得了锁。



2. 控制时序，即所有想要获得锁的用户都会被安排执行，但是获得锁的顺序也是全局唯一的，同时决定了执行顺序。etcd 为此也提供了一套 API（自动创建有序键），对一个目录建值时指定为POST动作，这样 etcd 会自动在目录下生成一个当前最大的值为键，存储这个新的值（客户端编号）。同时还可以使用 API 按顺序列出所有当前目录下的键值。此时这些键的值就是客户端的时序，而这些键中存储的值可以是代表客户端的编号。





在这里Ectd实现分布式锁基本实现原理为：

1.在ectd系统里创建一个key

2.如果创建失败，key存在，则监听该key的变化事件，直到该key被删除，回到1

3.如果创建成功，则认为我获得了锁