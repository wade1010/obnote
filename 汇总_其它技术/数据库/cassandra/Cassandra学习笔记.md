# https://docs.datastax.com/en/cassandra-oss/3.x/

# https://docs.datastax.com/en/archived/cql/3.0/index.html

https://docs.datastax.com/en/cql-oss/3.x/index.html

https://community.datastax.com/index.html

# 架构介绍

p2p分布式系统，集群中各节点平等，数据分布于集群中各节点，各节点间每秒交换一次信息（gossip协议）。

每个节点的commit log捕获写操作来确保数据持久性。数据先被写入memtable内存中的数据结构，待该结构满后数据被写入SSTable-硬盘中的数据文件。

所有的写内容被自动在集群中分区并复制。

授权用户可连接至任意数据中心的任意节点，并通过类似SQL的CQL查询数据。

集群中，一个应用一般包含一个keyspace，一个keyspace中包含多个表。

客户端连接到某一节点发起读或写请求时，该节点充当客户端应用与拥有相应数据的节点间的协调者(coordinator）以根据集群配置确定环中的哪个节点当获取这个请求。



#### gossip简单学习

又称反熵算法(在杂乱无章中寻求一致)，同步信息是该协议的核心，数据结构用到merkle tree(可以理解为一棵hash树),这样一旦修改某个文件，修改时间和信息就会迅速传播到根，需要同步的系统只需要不断查询根节点的hash,一旦有变化，顺着树状结构就能找到发生变化的内容，马上同步（时间复杂度LogN）



Gossip 节点的通信方式及收敛性

Gossip 两个节点（A、B）之间存在三种通信方式（push、pull、push&pull）

1. push: A 节点将数据(key,value,version)及对应的版本号推送给 B 节点，B 节点更新 A 中比自己新的数据。

2. pull：A 仅将数据 key,version 推送给 B，B 将本地比 A 新的数据（Key,value,version）推送给 A，A 更新本地。

3. push/pull：与 pull 类似，只是多了一步，A 再将本地比 B 新的数据推送给 B，B 更新本地。

如果把两个节点数据同步一次定义为一个周期，则在一个周期内，push 需通信 1 次，pull 需 2 次，push/pull 则需 3 次，从效果上来讲，push/pull 最好，理论上一个周期内可以使两个节点完全一致。直观上也感觉，push/pull 的收敛速度是最快的。



- Cluster

- Data center(s)

- Rack(s)

- Server(s)

- Node (more accurately, a vnode)

- Node（节点）：一个运行cassandra的实例

- Rack（机架）：一组nodes的集合

- DataCenter（数据中心）：一组racks的集合

- Cluster（集群）：映射到拥有一个完整令牌圆环所有节点的集合





![](https://gitee.com/hxc8/images7/raw/master/img/202407190806035.jpg)



协调者（Coordinator)

客户端连接到某一节点发起读写请求时，该节点充当客户端应用与集群中拥有相应数据节点间的桥梁，称为协调者，以根据集群配置确定环(ring)中的哪个节点应当获取这个请求。

使用CQL连接指定的-h节点就是协调节点

- 集群中任何一个节点都可能成为协调者

- 每个客户端请求都可能由不同的节点来协调

- 由协调者管理复制因子（复制因子：一条新数据应该被复制到多少个节点）

- 协调者申请一致性级别（一致性级别：集群中有多少节点必须相应读写的请求）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806220.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190806501.jpg)

# Cassandra中数据存放规则



data：存储真正的数据文件，既后面的SStable文件，可以指定多个目录。

commitlog：存储未写入SSTable中的数据（在每次写入之前先放入日志文件）。

cache：存储系统中的缓存数据（在服务重启的时候从这个目录中加载缓存数据）。



### 分区器(partitioners)

分区器用来决定数据在群集的那些节点进行存储(包含副本数据)，Cassandra提供三种partitioners：

1、Murmur3Partitioner(默认): 基于MurmurHash hash值将数据均匀的分布在集群

2、RandomPartitioner: 基于MD5 hash值将数据均匀的分布在集群中

3、ByteOrderedPartitioner: 通过键的字节来保持数据词汇的有序分布

Murmur3Partitioner使用MurmurHash hash算法，而RandomPartitioner使用加密hash算法，因此Murmur3Partitioner比RandomPartitioner有3至5倍的性能提升。

ByteOrderedPartitioner使得数据按照主键有序排列，运行通过主键进行有序扫描，该功能可以通过index来实现，而ByteOrderedPartitioner分区器很难实现负载均衡和数据热点问题，因此不建议使用。



复制（Replication）

当前有两种可用的复制策略：

- SimpleStrategy：仅用于单数据中心，将第一个replica放在由partitioner确定的节点中，其余的replicas放在上述节点顺时针方向的后续节点中。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806700.jpg)

- NetworkTopologyStrategy：可用于较复杂的多数据中心。可以指定在每个数据中心分别存储多少份replicas。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806447.jpg)



# Cassandra一致性实现

大部分读写是能保证强一致性的,极端情况下不符合（3副本写入,只有1副本成功,读2副本正好读到包含写入成功的那个，最后写入胜出原则返回最新值，一般解决办法，业务层重试到写入成功为止，或通过Cassandra提供的tool修复），优先保证可用性,所以Cassandra一般归类为AP系统

允许用户自动以副本数量和读写一致性级别

常用读写一致性级别:

| ONE | 读或写要求返回成功至少1个副本 |
| - | - |
| TWO | 读或写要求返回成功至少2个副本 |
| QUORUM | 读或写要求返回成功超过半数副本 |
| SERIAL | CAS原子更新(强一致性) |






# Cassandra 的数据模型图

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806638.jpg)



Cluster：Cassandra 的节点实例，它可以包含多个Keyspace。

Keyspace:：用于存放 ColumnFamily 的容器，相当于关系数据库中的 Schema 或 database。

ColumnFamily:：用于存放 Column 的容器，类似关系数据库中的 table 的概念 。

SuperColumn:：它是一个特列殊的 Column, 它的 Value 值可以包函多个Column。

Column:：Cassandra 的最基本单位。由name , value , timestamp组成。



Cassandra数据模型的模型结构可以理解为嵌套的map

Map<RowKey,SortedMap<ColumnKey, ColumnValue>>



也可以通过关系型数据库大致理解成如下图

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806992.jpg)



# 数据读请求和后台修复

1. 协调者首先与一致性级别确定的所有 replica 联系，被联系的节点返回请求的数据。

2. 若多个节点被联系，则来自各 replica 的 row 会在内存中作比较，若不一致，则协调者使用含最新数据的 replica 向 client 返回结果。那么比较操作过程中只需要传递时间戳就可以,因为要比较的只是哪个副本数据是最新的。

3. 协调者在后台联系和比较来自其余拥有对应 row 的 replica 的数据，若不一致，会向过时的replica 发写请求用最新的数据进行更新 read repair。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806202.jpg)







# Cassandra索引



一级索引就是Primary Key. 因为查询的时候，可以直接根据Key算出token然后直接获取对应的记录。

而二级索引，作为辅助索引就是为了找到一级索引。然后再通过一级索引找到真正的值





实际上是自动新创建了一张表，同时将原始表格之中的索引字段作为新索引表的Primary Key！并且存储的值为原始数据的Primary Key





Do not use an index in these situations:

- On high-cardinality columns because you then query a huge volume of records for a small number of results. 官方

- In tables that use a counter column

- On a frequently updated or deleted column.

- To look for a row in a large partition unless narrowly queried



不适合 用索引



1、High-cardinality 列。 相当于这一列的值很多很多的时候(因为查询了很多结果只能取出一小部分数据集)

2、counter 类型的列

3、删除、更新太过频繁的列

4、在大分区中查找行，除非进行狭窄查询（对大型集群中的索引列的查询通常需要整理来自多个数据分区的响应。随着更多的机器被添加到集群中，查询响应速度变慢。通过缩小搜索范围，可以避免在大型分区中查找行时的性能降低）



# 二级索引

在 Cassandra 中，数据都是以 Key-value 的形式保存的。

Cassandra：一致Hash虚拟节点+Gossip协议+数据复制存储读写+索引

KeysIndex 所创建的二级索引也被保存在一张 ColumnFamily 中。在插入数据时，对需要进行索引的 value进行摘要，生成独一无二的key，将其作为 RowKey保存在索引的 ColumnFamily 中；同时在 RowKey 上添加一个 Column，将插入数据的 RowKey 作为 name 域的值，value 域则赋空值，timestamp 域则赋为插入数据的时间戳。

如果有相同的 value 被索引了，则会在索引 ColumnFamily 中相同的 RowKey 后再添加新的Column。如果有新的 value 被索引，则会在索引 ColumnFamily 中添加新的 RowKey 以及对应新的 Column。

当对 value 进行查询时，只需计算该 value 的 RowKey，在索引 ColumnFamily 中的查找该RowKey，对其 Columns 进行遍历就能得到该 value 所有数据的 RowKey。



# 数据读写

### 写请求

当写事件发生时，首先由Commit Log捕获写事件并持久化，保证数据的可靠性。之后数据也会被写入到内存中，叫Memtable，当内存满了之后写入数据文件，叫SSTable，它是Log-Structured Storage Table的简称。 如果客户端配置了Consistency Level是ONE，意味着只要有一个节点写入成功，就由代理节点(Coordinator)返回给客户端写入完成。当然这中间有可能会出现其它节点写入失败的情况，Cassandra自己会通过Hinted Handoff或Read Repair 或者Anti-entropy Node Repair方式保证数据最终一致性。

对于多数据中心的写入请求，Cassandra做了优化，每个数据中心选取一个Coordinator来完成它所在数据中心的数据复制，这样客户端连接的节点只需要向数据中心的一个节点转发复制请求即可，由这个数据中心的Coordinator来完成该数据中心内的数据复制。

Cassandra的存储结构类似LSM树（Log-Structured Merge Tree）这种结构，不像传统数据一般都使用B+树，存储引擎以追加的方式顺序写入磁盘连续存储数据，写入是可以并发写入，不像B+树一样需要加锁，写入速度非常高，LevelDB、Hbase都是使用类似的存储结构。

Commit Log记录每次写请求的完整信息，此时并不会根据主键进行排序，而是顺序写入，这样进行磁盘操作时没有随机写导致的磁盘大量寻道操作，对于提升速度有极大的帮助，号称最快的本地数据库LevelDB也是采用这样的策略。Commit Log会在Memtable中的数据刷入SSTable后被清除掉，因此它不会占用太多磁盘空间，Cassandra的配置时也可以单独设置存储区，这为使用高性能但容量小价格昂贵的SSD硬盘存储Commit Log，使用速度慢但容量大价格非常便宜的传统机械硬盘存储数据的混合布局提供了便利。

写入到Memtable时，Cassandra能够动态地为它分配内存空间，你也可以使用工具自己调整。当达到阀值后，Memtable中的数据和索引会被放到一个队列中，然后flush到磁盘，可以使用memtableflushqueue_size参数来指定队列的长度。当进行flush时，会停止写请求。也可以使用nodetool flush工具手动刷新 数据到磁盘，重启节点之前最好进行此操作，以减少Commit Log回放的时间。为了刷新数据，会根据partition key对Memtables进行重排序，然后顺序写入磁盘。这个过程是非常快的，因为只包含Commit Log的追加和顺序的磁盘写入。

当memtable中的数据刷到SSTable后，Commit Log中的数据将被清理掉。每个表会包含多个Memtable和SSTable，一般刷新完毕，SSTable是不再允许写操作的。因此，一个partition一般会跨多个SSTable文件，后续通过Compaction对多个文件进行合并，以提高读写性能。

这里所述的写请求不单指Insert操作，Update操作也是如此，Cassandra对Update操作的处理和传统关系数据库完全不一样，并不立即对原有数据进行更新，而是会增加一条新的记录，后续在进行Compaction时将数据再进行合并。Delete操作也同样如此，要删除的数据会先标记为Tombstone，后续进行Compaction时再真正永久删除。



### 读请求

读取数据时，首先检查Bloom filter，每一个SSTable都有一个Bloom filter用来检查partition key是否在这个SSTable，这一步是在访问任何磁盘IO的前面就会做掉。如果存在，再检查partition key cache，然后再做如下操作：



如果在cache中能找到索引，到compression offset map中找拥有这个数据的数据块，从磁盘上取得压缩数据并返回结果集。如果在cache中找不到索引，搜索partition summary确定索引在磁盘上的大概位置，然后获取索引入口，在SSTable上执行一次单独的寻道和一个顺序的列读取操作，下面也是到compression offset map中找拥有这个数据的数据块，从磁盘上取得压缩数据并返回结果集。读取数据时会合并Memtable中缓存的数据、多个SSTable中的数据，才返回最终的结果。比如更新用户Email后，用户名、密码等还在老的SSTable中，新的EMail记录到新的SSTable中，返回结果时需要读取新老数据并进行合并。



2.0之后的Bloom filter,compression offset map,partition summary都不放在Heap中了，只有partition key cache还放在Heap中。Bloom filter增长大约1~2G每billion partitions。partition summary是partition index的样本，你可以通过index_interval来配置样本频率。compression offset map每TB增长1~3G。对数据压缩越多，就会有越多个数的压缩块，和越大compression offset table。



读请求(Read Request)分两种，一种是Rirect Read Request，根据客户端配置的Consistency Level读取到数据即可返回客户端结果。一种是Background Read Repair Request,除了直接请求到达的节点外，会被发送到其它复制节点，用于修复之前写入有问题的节点，保证数据最终一致性。客户端读取时，Coordinator首先联系Consistency Level定义的节点，发送请求到最快响应的复制节点上，返回请求的数据。如果有多个节点被联系，会在内存比较每个复制节点传过的数据行，如果不一致选取最近的数据（根据时间戳）返回给客户端，并在后台更新过期的复制节点，这个过程被称作Read Repair 。



下面是Consistency Level 为ONE的读取过程，Client连接到任意一个节点上，该节点向实际拥有该数据的节点发出请求，响应最快的节点数据回到Coordinator后，就将数据返回给Client。如果其它节点数据有问题，Coordinator会将最新的数据发送有问题的节点上，进行数据的修复。



### 数据写入和更新（数据追加）

Cassandra 的设计思路与这些系统不同，无论是 insert 还是 remove 操作，都是在已有的数据后面进行追加，而不修改已有的数据。这种设计称为 Log structured 存储，顾名思义就是系统中的数据是以日志的形式存在的，所以只会将新的数据追加到已有数据的后面。Log structured 存储系统有两个主要优点：

1、数据的写和删除效率极高

传统的存储系统需要更新元信息和数据，因此磁盘的磁头需要反复移动，这是一个比较耗时的操作，而 Log structured 的系统则是顺序写，可以充分利用文件系统的 cache，所以效率很高。

2、错误恢复简单

由于数据本身就是以日志形式保存，老的数据不会被覆盖，所以在设计 journal 的时候不需要考虑 undo，简化了错误恢复。



### 读的复杂度高

但是，Log structured 的存储系统也引入了一个重要的问题：读的复杂度和性能。理论上说，读操作需要从后往前扫描数据，以找到某个记录的最新版本。相比传统的存储系统，这是比较耗时的。



### 数据删除（column 的墓碑）

如果一次删除操作在一个节点上失败了（总共 3 个节点，副本为 3， RF=3).整个删除操作仍然被认为成功的（因为有两个节点应答成功，使用 CL.QUORUM 一致性）。接下来如果读发生在该节点上就会变的不明确，因为结果返回是空，还是返回数据，没有办法确定哪一种是正确的。

Cassandra 总是认为返回数据是对的，那就会发生删除的数据又出现了的事情，这些数据可以叫“僵尸”，并且他们的表现是不可预见的。



### 墓碑

删除一个 column 其实只是插入一个关于这个 column 的墓碑（tombstone），并不直接删除原有的 column。该墓碑被作为对该 CF 的一次修改记录在 Memtable 和 SSTable 中。墓碑的内容是删除请求被执行的时间，该时间是接受客户端请求的存储节点在执行该请求时的本地时间（local delete time），称为本地删除时间。需要注意区分本地删除时间和时间戳，每个 CF 修改记录都有一个时间戳，这个时间戳可以理解为该 column 的修改时间，是由客户端给定的。



### 数据读取（memtable+SStables）

为了满足读 cassandra 读取的数据是 memtable 中的数据和 SStables 中数据的合并结果。读取SSTables 中的数据就是查找到具体的哪些的 SSTables 以及数据在这些 SSTables 中的偏移量(SSTables 是按主键排序后的数据块)。首先如果 row cache enable 了话，会检测缓存。缓存命中直接返回数据，没有则查找 Bloom filter，查找可能的 SSTable。然后有一层 Partition key cache，找 partition key 的位置。如果有根据找到的 partition 去压缩偏移量映射表找具体的数据块。如果缓存没有，则要经过 Partition summary,Partition index 去找 partition key。然后经过压缩偏移量映射表找具体的数据块。

1. 检查 memtable

2. 如果 enabled 了,检查 row cache

3. 检查 Bloom filter

4. 如果 enable 了,检查 partition key 缓存

5. 如果在 partition key 缓存中找到了 partition key,直接去 compression offset 命中，如果没有，检查 partition summary

6. 根据 compression offset map 找到数据位置

7. 从磁盘的 SSTable 中取出数据



### Cassandra数据维护

#### 垃圾回收/数据整理 compaction

更新操作不会立即更新，这样会导致随机读写磁盘，效率不高，Cassandra会把数据顺序写入到一个新的SSTable，并打上一个时间戳以标明数据的新旧。它也不会立马做删除操作，而是用Tombstone来标记要删除的数据。Compaction时，将多个SSTable文件中的数据整合到新的SSTable文件中，当旧SSTable上的读请求一完成，会被立即删除，空余出来的空间可以重新利用。虽然Compcation没有随机的IO访问，但还是一个重量级的操作，一般在后台运行，并通过限制它的吞吐量来控制，`compactionthroughputmbpersec参数可以设置，默认是16M/s。另外，如果key cache显示整理后的数据是热点数据，操作系统会把它放入到page cache里，以提升性能。它的合并的策略有以下两种：

- SizeTieredCompactionStrategy:每次更新不会直接更新原来的数据，这样会造成随机访问磁盘，性能不高，而是在插入或更新直接写入下一个sstable，这样是顺序写入速度非常快，适合写敏感的操作。但是，因为数据分布在多个sstable，读取时需要多次磁盘寻道，读取的性能不高。为了避免这样情况，会定期在后台将相似大小的sstable进行合并，这个合并速度也会很快，默认情况是4个sstable会合并一次，合并时如果没有过期的数据要清理掉，会需要一倍的空间，因此最坏情况需要50%的空闲磁盘。

- LeveledCompactionStrategy：创建固定大小默认是5M的sstable，最上面一级为L0下面为L1，下面一层是上面一层的10倍大小。这种整理策略读取非常快，适合读敏感的情况，最坏只需要10%的空闲磁盘空间，它参考了LevelDB的实现，详见LevelDB的具体实现原理。

这里也有关于这两种方式的详细描述。



由于被删除的 column 并不会立即被从磁盘中删除，所以系统占用的磁盘空间会越来越大，这就需要有一种垃圾回收的机制，定期删除被标记了墓碑的 column。垃圾回收是在 compaction 的过程中完成的。



当数据发生修改(增删改)操作时，Cassandra将先将修改日志写到Commit Log，然后在把数据写到Memtable，再将Memtable中数据顺序Flush到磁盘保存在SSTable中，

当Memtable中数据超过配置阈值或Commitlog的空间超过commitlog_total_space_in_mb的值时，便会触发Flush操作。

Cassandra将数据刷新到SSTable，会将带有新时间戳的数据写入到新的SSTable中，不会覆盖会追加到旧的SSTable，因此不同的SSTable可能保存同一行数据的多个版本，为保证数据库的健康性，Cassandra周期性的合并SSTables,并将老数据废弃掉，该行为被称为合并压缩。

Cassandra提供以下压缩策略：

1、SizeTieredCompactionStrategy(STCS)

2、LeveledCompactionStrategy(LCS)

3、TimeWindowCompactionStrategy(TWCS)

4、DateTieredCompactionStrategy(DTCS)



SizeTieredCompactionStrategy(STCS)

当Cassandra 相同大小的SSTables数目达到一个固定的数目(默认是4),STCS开始将多个SSTable压缩成一个更大的SSTable。

优势：写占比高的情况压缩很好

劣势: 可能将过期的数据保存的很久，随着时间推移，需要的内存大小随之增加。

适用场景：写占比高的场景。

LeveledCompactionStrategy(LCS)

LCS会将数据合并压缩成多个层级，每一层是上一层的10倍。LCS压缩过程确保了从L1层开始的SSTables不会有重复的数据。

优势： 磁盘空间的要求容易预测。读操作的延迟更容易预测。过时的数据回收的更及时。

劣势: 更高的I/O使用影响操作延迟。

适用场景：读占比高的场景

TimeWindowCompactionStrategy(TWCS)

TWCS基于时间窗口将SSTable进行分组，

优势: 用作时间序列数据，为表中所有数据使用默认的TTL。比DTCS配置更简单。

劣势: 不适用于时间乱序的数据，因为SSTables不会继续做压缩，存储会没有边界的增长，所以也不适用于没有设置TTL的数据。相比较DTCS，需要更少的调优配置。

适用场景：时间序列且设置了TTL的场景



DateTieredCompactionStrategy(DTCS)

DTCS类似于TWCS，已弃用。



### 数据复制和分发

数据分发和复制通常是一起的，数据用表的形式来组织，用主键来识别应该存储到哪些节点上，行的copy称作replica。当一个集群被创建时，至少要指定如下几个配置：Virtual Nodes，Partitioner，Replication Strategy，Snitch。

数据复制策略有两种，一种是SimpleStrategy，适合一个数据中心的情况，第一份数据放在Partitioner确定的节点，后面的放在顺时针找到的节点上，它不考虑跨数据中心和机架的复制。另外一种是NetworkTopologyStargegy，第一份数据和前一种一样，第二份复制的数据放在不同的机架上，每个数据中心可以有不同数据的replicas。

Partitioner策略有三种，默认是Murmur3Partitioner，使用MurmurHash。RandomPartitioner，使用Md5 Hash。        ByteOrderedPartitioner使用数据的字节进行有顺分区。Cassandra默认使用MurmurHash，这种有更高的性能。

Snitch用来确定从哪个数据中心和哪个机架上写入或读取数据,有如下几种策略：

DynamicSnitch：监控各节点的执行情况，根据节点执行性能自动调节，大部分情况推荐使用这种配置

SimpleSnitch：不会考虑数据库和机架的情况，当使用SimpleStategy策略时考虑使用这种情况

RackInterringSnitch：考虑数据库中和机架

PropertyFileSnitch：用cassandra-topology.properties文件来自定义

GossipPropertyFileSnitch:定义一个本地的数据中心和机架，然后使用Gossip协议将这个信息传播到其它节点，对应的配置文件是cassandra-rockdc.properties



### 失败检测和修复（Failure detection and recovery）

Cassandra从Gossip信息中确认某个节点是否可用，避免客户端请求路由到一个不可用的节点，或者执行比较慢的节点，这个通过dynamic snitch可以判断出来。Cassandra不是设定一个固定值来标记失败的节点，而是通过连续的计算单个节点的网络性能、工作量、以及其它条件来确定一个节点是否失败。节点失败的原因可能是硬件故障或者网络中断等，节点的中断通常是短暂的但有时也会持续比较久的时间。节点中断并不意味着这个节点永久不可用了，因此不会永久地从网络环中去除，其它节点会定期通过Gossip协议探测该节点是否恢复正常。如果想永久的去除，可以使用nodetool手工删除。

当节点从中断中恢复过来后，它会缺少最近写入的数据，这部分数据由其它复制节点暂为保存，叫做Hinted Handoff，可以从这里进行自动恢复。但如果节点中断时间超过maxhintwindowinms（默认3小时）设定的值，这部分数据将会被丢弃，此时需要用nodetool repair在所有节点上手工执行数据修复，以保证数据的一致性。



### 动态扩展

Cassandra最初版本是通过一致性Hash来实现节点的动态扩展的，这样的好处是每次增加或减少节点只会影响相邻的节点，但这个会带来一个问题就是造成数据不均匀，比如新增时数据都会迁移到这台机的机器上，减少时这台机器上的数据又都会迁移到相邻的机器上，而其它机器都不能分担工作，势必会造成性能问题。从1.2版本开始，Cassandra引入了虚拟节点(Virtual Nodes)的概念，为每个真实节点分配多个虚拟节点（默认是256），这些节点并不是按Hash值顺序排列，而是随机的，这样在新增或减少一个节点时，会有很多真实的节点参与数据的迁移，从而实现了负载匀衡。







---

如果再同一时刻发生两次更新会发生什么？



更新顺序必须是可交换的，因为他们很有可能到达不同副本的顺序是不一样的。只要cassandra有一个确定的方法选出这个赢家（相同的时间戳），那么这在其它节点也是一样的，这是一个重要的实现细节。也就是说，对于相同时间戳的操作，Cassandra遵循以下两个原则：第一：删除要优先于更新和插入，第二：如果两个都是更新，那个在语法上比较大的更新会被选中



Cassandra 不支持连接或子查询



每个节点地位都是一样的，都可以扮演coordinator或副本存储节点，哪个节点接受了客户端请求就成了coordinator，客户端也允许每个不同的操作都换一个coordinator节点来接受，这也是高并发高可靠的原因。





RF（复制因子 replication factor）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806400.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190806017.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190806095.jpg)



多数据中心参考

https://zhuanlan.zhihu.com/p/30498475





