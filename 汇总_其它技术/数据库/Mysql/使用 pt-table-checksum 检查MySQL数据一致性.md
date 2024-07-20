在主库上运行即可

![](https://gitee.com/hxc8/images7/raw/master/img/202407190812760.jpg)





Step 1. pt-table-chum 原理

pt-table-checksum用于在线检测MySQL主从一致性，其原理是在主库执行checksum查询，然后与从库进行结果的比对，从而得出是否一致性的报告.

pt-table-checksum checksum每张表，然后得出每个从库的一致性报告。pt-table-checksum 工具只关注数据的不一致，修复数据一致性需要用到 pt-table-sync 工具.

pt-table-checksum 连接指定的主库，然后查找数据库和表（如果指定了过滤条件，则按过滤条件查找）。它同一时间只checksum 一张表，所以不会消耗过多的内存和资源。对于大数据库的数据库来说，这是一个非常有用的设计，无论数据库有多大，比如一个server中有几百个数据库和表，数万亿（trillions）的行，我们都无需担心，pt-table-checksum都可以胜任。

可以胜任的原因是， pt-table-checksum 将每张表都拆分成行块（chunks of rows），默认是1000行。然后对每个行块使用单独的REPLACE…SELECT 进行checksum 查询。行块（chunks of rows）大小随着设定的–chunk-time 时间（默认0.5秒）动态调节。每次 checksum 查询执行完毕后，下次checksum 查询会根据上次执行的时间调整 行块（chunks of rows）大小，也就是通过学习系统的负载情况来调节行块大小。  如果不设定 –chunk-time ，行块大小则不会自动调整。这样就不会形成一个大的checkum 查询操作，确保了主从复制不会产生大的延迟或者负载



> pt-table-checksum 是 Percona-Toolkit 的组件之一，用于检测MySQL主、从库的数据是否一致。其原理是在主库执行基于statement的sql语句来生成主库数据块的checksum，把相同的sql语句传递到从库执行，并在从库上计算相同数据块的checksum，最后，比较主从库上相同数据块的checksum值，由此判断主从数据是否一致。检测过程根据唯一索引将表按row切分为块（chunk），以为单位计算，可以避免锁表。检测时会自动判断复制延迟、 master的负载， 超过阀值后会自动将检测暂停，减小对线上服务的影响。

> pt-table-checksum 默认情况下可以应对绝大部分场景，官方说，即使上千个库、上万亿的行，它依然可以很好的工作，这源自于设计很简单，一次检查一个表，不需要太多的内存和多余的操作；必要时，pt-table-checksum 会根据服务器负载动态改变 chunk 大小，减少从库的延迟。

> 为了减少对数据库的干预，pt-table-checksum还会自动侦测并连接到从库，当然如果失败，可以指定--recursion-method选项来告诉从库在哪里。它的易用性还体现在，复制若有延迟，在从库 checksum 会暂停直到赶上主库的计算时间点（也通过选项--设定一个可容忍的延迟最大值，超过这个值也认为不一致。

 

Step 2.安装 pt-table-checksum

```javascript
$ wget  https://www.percona.com/redir/downloads/percona-release/redhat/latest/percona-release-0.1-6.noarch.rpm

$ rpm -ivh percona-release-0.1-6.noarch.rpm

$ yum install percona-toolkit -y

```

至此pt-table-checksum 命令已安装完成！

 

Step 3.创建一个专门用于checksum的用户，它需要有连接到从库的权限

```javascript
mysql> GRANT SELECT, PROCESS, SUPER, REPLICATION SLAVE ON *.* TO 'checksum'@'%' IDENTIFIED BY 'checksum'; 
Query OK, 0 rows affected (0.00 sec)

mysql> GRANT ALL PRIVILEGES ON *.* TO 'checksum'@'%' IDENTIFIED BY 'checksum';
Query OK, 0 rows affected (0.01 sec)
```

 

Step 4. 只检查cnail一个库数据是否一致，使用–databases参数

- TS ：完成检查的时间戳。

- ERRORS ：检查时候发生错误和警告的数量。

- DIFFS ：不一致的chunk数量。当指定 --no-replicate-check 即检查完但不立即输出结果时，会一直为0；当指定 --replicate-check-only 即不检查只从checksums表中计算crc32，且只显示不一致的信息（毕竟输出的大部分应该是一致的，容易造成干扰）。

- ROWS ：比对的表行数。

- CHUNKS ：被划分到表中的块的数目。

- SKIPPED ：由于错误或警告或过大，则跳过块的数目。

- TIME ：执行的时间。

- TABLE ：被检查的表名

```javascript
$ pt-table-checksum  --user=checksum --password=checksum --host=192.168.1.120  --databases=anna --replicate=cnail.checksums --create-replicate-table  --no-check-binlog-format
Checking if all tables can be checksummed ...
Starting checksum ...
            TS ERRORS  DIFFS     ROWS  DIFF_ROWS  CHUNKS SKIPPED    TIME TABLE
01-29T12:10:17      0      0        0          0       1       0   0.026 anna.tb1
```



Step 5.检查所有库是否一致

```javascript
$ pt-table-checksum  --user=checksum --password=checksum --host=192.168.1.100   --replicate=cnail.checksums --create-replicate-table  --no-check-binlog-format

Checking if all tables can be checksummed ...
Starting checksum ...
Diffs cannot be detected because no slaves were found.  Please read the --recursion-method documentation for information.
            TS ERRORS  DIFFS     ROWS  DIFF_ROWS  CHUNKS SKIPPED    TIME TABLE
01-29T15:50:55      0      0        0          0       1       0   0.011 anna.tb1
01-29T15:50:55      0      0        0          0       1       0   0.011 dong.tb1
01-29T15:50:55      0      0        0          0       1       0   0.010 mysql.columns_priv
01-29T15:50:55      0      0        2          0       1       0   0.011 mysql.db
01-29T15:50:55      0      0        0          0       1       0   0.012 mysql.event
01-29T15:50:55      0      0        0          0       1       0   0.012 mysql.func
01-29T15:50:55      0      0       40          0       1       0   0.013 mysql.help_category
01-29T15:50:55      0      0      614          0       1       0   0.011 mysql.help_keyword
01-29T15:50:55      0      0     1225          0       1       0   0.009 mysql.help_relation
01-29T15:50:55      0      0      585          0       1       0   0.011 mysql.help_topic
01-29T15:50:55      0      0        0          0       1       0   0.013 mysql.ndb_binlog_index
01-29T15:50:55      0      0        0          0       1       0   0.012 mysql.plugin
01-29T15:50:55      0      0        0          0       1       0   0.012 mysql.proc
01-29T15:50:55      0      0        0          0       1       0   0.011 mysql.procs_priv
01-29T15:50:55      0      0        2          0       1       0   0.010 mysql.proxies_priv
01-29T15:50:55      0      0        0          0       1       0   0.011 mysql.servers
01-29T15:50:55      0      0        0          0       1       0   0.012 mysql.tables_priv
01-29T15:50:55      0      0        0          0       1       0   0.013 mysql.time_zone
01-29T15:50:55      0      0        0          0       1       0   0.014 mysql.time_zone_leap_second
01-29T15:50:55      0      0        0          0       1       0   0.013 mysql.time_zone_name
01-29T15:50:55      0      0        0          0       1       0   0.014 mysql.time_zone_transition
01-29T15:50:55      0      0        0          0       1       0   0.012 mysql.time_zone_transition_type
01-29T15:50:55      0      0        9          0       1       0   0.015 mysql.user
01-29T15:50:55      0      0        4          0       1       0   0.010 percona.checksums
01-29T15:50:55      0      0        2          0       1       0   0.015 percona.tb1
01-29T15:50:55      0      0        0          0       1       0   0.012 percona.tb2
01-29T15:50:55      0      0        0          0       1       0   0.014 percona.tb3
```

 

Step 6. –replicate-check-only 参数，只输出不一致的表的信息

```javascript
$ pt-table-checksum  --user=checksum --password=checksum --host=192.168.1.120  --replicate=cnail.checksums --create-replicate-table  --no-check-binlog-format --max-load='Threads_connected=120'  --replicate-check-only
```

