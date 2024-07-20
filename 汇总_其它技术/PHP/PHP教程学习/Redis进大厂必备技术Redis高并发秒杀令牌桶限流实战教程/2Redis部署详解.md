数据类型：

String,hash,list,set,zset  geo(3.2+)地图空间 storm(5.0) bitmap (O2O(位置功能)-地图)





nosql 数据库对比



redis(nosql) 存储大 数据类型丰富 单机并发（2-3 万台） 每秒 10 万次读写操作

（大规模的 K-V） 持久化（磁盘）、自带集群、单线程（多路 I/O 复用）

memcache 单个 key 存储小

mongodb 索引 数据量 索引量



线程之间通信有消耗

多个线程之间有锁机制 也有IO损耗



单线程就是不能充分发挥CPU的性能，但是可以通过构建集群来解决这个问题