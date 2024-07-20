面对强大的clickHouse，试想一下这个产品是来自于伟大的战斗名族俄罗斯，这一切就不难想象这个软件的厉害之处了，介绍一下clickhouse里面的engine：

在engine家族里面讲engine分为四大部分：

（1）MergeTree

适合高负载将数据快速插入表的情形，支持数据复制，分区、分块等

- MergeTree

**适合场景**：数据插入过程中多次重复写入数据存储

**特点**：

大量的数据插入到表中，后台将会逐个part写入，并且按照后台的规则对写入的数据进行合并

使用主键存储数据，还会使用一些稀疏索引来帮助快速找到数据

按照分区字段进行分区操作

数据复制支持和数据抽样支持

```sql
# 创建表
CREATE TABLE [IF NOT EXISTS] [db.]table_name [ON CLUSTER cluster]
(
    name1 [type1] [DEFAULT|MATERIALIZED|ALIAS expr1] [TTL expr1],
    name2 [type2] [DEFAULT|MATERIALIZED|ALIAS expr2] [TTL expr2],
    ...
    INDEX index_name1 expr1 TYPE type1(...) GRANULARITY value1,
    INDEX index_name2 expr2 TYPE type2(...) GRANULARITY value2
) ENGINE = MergeTree()
ORDER BY expr
[PARTITION BY expr]
[PRIMARY KEY expr]
[SAMPLE BY expr]
[TTL expr [DELETE|TO DISK 'xxx'|TO VOLUME 'xxx'], ...]
[SETTINGS name=value, ...]
```

- ReplacingMergeTree

**适合场景**：清除重复数据，节省数据空间

**特点**：清除重复数据往往发生在数据合并时，所以在清除数据时，可以节省大量的空间，但是不能保证数据没有重复。

```sql
CREATE TABLE [IF NOT EXISTS] [db.]table_name [ON CLUSTER cluster]
(
    name1 [type1] [DEFAULT|MATERIALIZED|ALIAS expr1],
    name2 [type2] [DEFAULT|MATERIALIZED|ALIAS expr2],
    ...
) ENGINE = ReplacingMergeTree([ver])
[PARTITION BY expr]
[ORDER BY expr]
[PRIMARY KEY expr]
[SAMPLE BY expr]
[SETTINGS name=value, ...]

```

- SummingMergeTree

**适合场景**：适合汇总统计的场景

**特点**：与MergeTree一起使用，存储使用MergeTree，但是查询使用SummingMergeTree去聚合存储的数据，clickhouse会复制所有相同主键的列，并将包含有数字类型的列聚合起来形成新的列。

```sql
# 创建表
CREATE TABLE [IF NOT EXISTS] [db.]table_name [ON CLUSTER cluster]
(
    name1 [type1] [DEFAULT|MATERIALIZED|ALIAS expr1],
    name2 [type2] [DEFAULT|MATERIALIZED|ALIAS expr2],
    ...
) ENGINE = SummingMergeTree([columns])
[PARTITION BY expr]
[ORDER BY expr]
[SAMPLE BY expr]
[SETTINGS name=value, ...]

# 例子
CREATE TABLE summtt
(
    key UInt32,
    value UInt32
)
ENGINE = SummingMergeTree()
ORDER BY key

INSERT INTO summtt Values(1,1),(1,2),(2,1)

SELECT key, sum(value) FROM summtt GROUP BY key

┌─key─┬─sum(value)─┐
│   2 │          1 │
│   1 │          3 │
└─────┴──── ─┘

# 注意：当某个字段值为0时，此条数据将会被删除
[(1, 100)] + [(2, 150)] -> [(1, 100), (2, 150)]
[(1, 100)] + [(1, 150)] -> [(1, 250)]
[(1, 100)] + [(1, 150), (2, 150)] -> [(1, 250), (2, 150)]
[(1, 100), (2, 150)] + [(1, -100)] -> [(2, 150)]
```

- Aggregatingmergetree

**适合场景**：增量数据聚合

**特点**：

可以结合AggregateFunction使用：

```sql
CREATE TABLE t
(
    column1 AggregateFunction(uniq, UInt64),
    column2 AggregateFunction(anyIf, String, UInt8),
    column3 AggregateFunction(quantiles(0.5, 0.9), UInt64)
) ENGINE = ...
```

可以结合SimpleAggregateFunction使用：

```sql
CREATE TABLE t
(
    column1 SimpleAggregateFunction(sum, UInt64),
    column2 SimpleAggregateFunction(any, String)
) ENGINE = ...
```

- CollapsingMergeTree

适用场景： 适合记录银行流水的场景

特点： 每一行记录后都有一个sign标记，这个标记用来指示此条记录是否有效，是否属于当前状态。

```sql
# 创建表
CREATE TABLE [IF NOT EXISTS] [db.]table_name [ON CLUSTER cluster]
(
    name1 [type1] [DEFAULT|MATERIALIZED|ALIAS expr1],
    name2 [type2] [DEFAULT|MATERIALIZED|ALIAS expr2],
    ...
) ENGINE = CollapsingMergeTree(sign)
[PARTITION BY expr]
[ORDER BY expr]
[SAMPLE BY expr]
[SETTINGS name=value, ...]

# 实例
CREATE TABLE UAct
(
    UserID UInt64,
    PageViews UInt8,
    Duration UInt8,
    Sign Int8
)
ENGINE = CollapsingMergeTree(Sign)
ORDER BY UserID

INSERT INTO UAct VALUES (4324182021466249494, 5, 146, 1)
INSERT INTO UAct VALUES (4324182021466249494, 5, 146, -1),(4324182021466249494, 6, 185, 1)
┌──────────────UserID─┬─PageViews─┬─Duration─┬─Sign─┐
│ 4324182021466249494 │         5 │      146 │    1 │
│ 4324182021466249494 │         5 │      146 │   -1 │
│ 4324182021466249494 │         6 │      185 │    1 │
└──────────────────┴──────┴── ────┴────┘
┌──────────────UserID─┬─PageViews─┬─Duration─┐
│ 4324182021466249494 │         6 │      185 │
└──────────┴───────────┴──────────┘

```

- VersionedCollapsingMergeTree

功能跟CollapsingMergeTree 一样，只不过标记为增加了一个“version”，版本字段

- GraphiteMergeTree

用来对稀疏数据进行聚合和平均操作

（2）Log

具有最小功能的轻型引擎。 当您需要快速编写许多小表（最多约100万行）并在以后整体读取它们时，它们是最有效的。

- TinyLog

适用场景：快速读写大量的小表，每个表不能超过100万行

特点：轻量级，write-once method（一次写入多次读取），用于处理小批量中间数据，比log engine更简单

- Log

适用场景：临时数据表，用于测试或者演示等目的

特点：跟TinyLog相似，但也有区别：存储的列文件中存在某些“标记”数据，这些数据都是写到数据块上并携带些offset，这些offset可以记录从何处读取文件和跳转到何处去，在并发数据访问中适合多线程读取表数据，写操作是带锁的，log engine不支持索引操作，如果表写入失败，将会导致表不可用。

- Stripelog

适用场景：使用少量数据（不超过100万条）写多张表

特点：列式存储，将所有列存储在一个文件中

```sql
# 创建表
CREATE TABLE [IF NOT EXISTS] [db.]table_name [ON CLUSTER cluster]
(
    column1_name [type1] [DEFAULT|MATERIALIZED|ALIAS expr1],
    column2_name [type2] [DEFAULT|MATERIALIZED|ALIAS expr2],
    ...
) ENGINE = StripeLog

# 写数据
# 所有的数据都存在下面两个文件中
data.bin — Data file.
index.mrk — File with marks. Marks contain offsets for each column of each data block inserted.

# 读数据
# 并行读取数据，不保证读数顺序，杂乱无序的
```

（3）Integration Engines

- Kafka

- MySQL

- ODBC

- JDBC

- HDFS

- S3

（4）Special Engines

- Distributed

- MaterializedView

- Dictionary

- Merge

- File

- Null

- Set

- Join

- URL

- View

- Memory

- Buffer