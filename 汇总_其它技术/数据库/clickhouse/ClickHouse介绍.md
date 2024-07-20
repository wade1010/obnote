# ClickHouse介绍

## 一、clickhouse简介

ClickHouse 是俄罗斯的 Yandex 于 2016 年开源的用于**在线分析处理查询**（OLAP :Online Analytical Processing）**MPP架构**的**列式存储数据库**（DBMS：Database Management System），能够使用 SQL 查询实时生成分析数据报告。ClickHouse的全称是Click Stream，Data WareHouse。

clickhouse可以做用户行为分析，流批一体

线性扩展和可靠性保障能够原生支持 shard + replication

clickhouse没有走hadoop生态，采用 Local attached storage 作为存储

## 二、clickhouse特点

1、列式存储：

**行式存储的好处：**

想查找某个人所有的属性时，可以**通过一次磁盘查找加顺序读取**就可以；但是当想查所有人的年龄时，需要不停的查找，或者全表扫描才行，遍历的很多数据都是不需要的。

**列式存储的好处**

- 对于列的聚合、计数、求和等统计操作优于**行式存储**

- 由于**某一列的数据类型都是相同的**，针对于**数据存储更容易进行数据压缩**，每一列选择更优的数据压缩算法，大大提高了数据的压缩比重

- 数据压缩比更好，一方面**节省了磁盘空间**，另一方面对**于cache也有了更大的发挥空间**

- 列式存储不支持事务

2、DBMS功能：几乎覆盖了标准 SQL 的大部分语法，包括 DDL 和 DML、，以及配套的各种函数；用户管理及权限管理、数据的备份与恢复

3、多样化引擎：目前包括合并树、日志、接口和其他四大类20多种引擎。

4、高吞吐写入能力：

ClickHouse采用类LSM Tree的结构，**数据写入后定期在后台Compaction**。通过类 LSM tree的结构， ClickHouse**在数据导入时全部是顺序append写**，写入后数据段不可更改，在后台compaction时也是多个段merge sort后顺序写回磁盘。顺序写的特性，充分利用了磁盘的吞吐能力。

5、数据分区与线程及并行：

ClickHouse将数据划分为多个partition，每个partition再进一步划分为多个index granularity(索引粒度)，然后通过多个CPU核心分别处理其中的一部分来实现并行数据处理。在这种设计下， **单条 Query 就能利用整机所有 CPU**。 极致的并行处理能力，极大的降低了查询延时。

所以， ClickHouse 即使对于大量数据的查询也能够化整为零平行处理。但是有一个弊端就是**对于单条查询使用多cpu，就不利于同时并发多条查询。所以对于高 qps 的查询业务并不是强项。**

6、ClickHouse 像很多 OLAP 数据库一样，单表查询速度优于关联查询，而且 ClickHouse的两者差距更为明显。

关联查询：clickhouse会将右表加载到内存。

## 三、clickhouse为什么快？

C++可以利用硬件优势

摒弃了hadoop生态

数据底层以列式存储

利用单节点的多核并行处理

为数据建立索引一级、二级、稀疏索引

使用大量的算法处理数据

支持向量化处理

预先设计运算模型-预先计算

分布式处理数据

## 四、引擎作用：

表引擎是 ClickHouse 的一大特色。可以说， 表引擎决定了如何存储表的数据。包括：

- 数据的存储方式和位置

- 支持哪些查询以及如何支持

- 并发数据访问

- 索引的使用

- 是否可以执行多线性请求

- 数据复制参数

## 五、ClickHouse引擎：

引擎决定了数据的存储位置、存储结构、表的特征（是否修改操作DDL、DDL、是否支持并发操作）

1、数据库引擎：[数据库引擎 | ClickHouse文档](https://link.zhihu.com/?target=https%3A//clickhouse.tech/docs/zh/engines/database-engines/)

目前支持的数据库引擎有5种：

- Ordinary：默认引擎，在绝大多数情况下我们都会使用默认引擎，使用时无须刻意声明。在此数据库下可以使用任意类型的表引擎。

- Dictionary：字典引擎，此类数据库会自动为所有数据字典创建它们的数据表

- Memory：内存引擎，用于存放临时数据。此类数据库下的数据表只会停留在内存中，不会涉及任何磁盘操作，当服务重启后数据会被清除

- Lazy：日志引擎，此类数据库下只能使用Log系列的表引擎

- MySQL：MySQL引擎，将远程的MySQL服务器中的表映射到ClickHouse中,常用语数据的合并。

- MaterializeMySQL：MySQL数据同步；将MySQL数据全量或增量方式同步到clickhouse中，解决mysql服务并发访问压力过大的问题

2、表引擎：[表引擎 | ClickHouse文档](https://link.zhihu.com/?target=https%3A//clickhouse.tech/docs/zh/engines/table-engines/)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806747.jpg)