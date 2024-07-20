wget https://github.com/akopytov/sysbench/archive/refs/tags/1.0.14.tar.gz



tar zxvf 1.0.14.tar.gz&&cd sysbench-1.0.14



yum -y install make automake libtool pkgconfig libaio-devel

# For MySQL support, replace with mysql-devel on RHEL/CentOS 5

yum -y install mariadb-devel

# For PostgreSQL support

yum -y install postgresql-devel





./autogen.sh
# Add --with-pgsql to build with PostgreSQL support
./configure

make -j && make install



cd /opt/tidb-cluster

vim topo.yaml

```javascript
server_configs:
  tikv:
    log-level: "error"
    prepared-plan-cache.enabled: true
    rocksdb.defaultcf.block-cache-size: "24GB"
    rocksdb.writecf.block-cache-size: "6GB"
    storage.block-cache.capacity: "30GB"

```



tiup cluster reload tidb-cluster



mkdir -p /opt/tidb-sysbench-test && cd /opt/tidb-sysbench-test



vim config

```javascript
mysql-host=127.0.0.1
mysql-port=4000
mysql-user=root
mysql-password=
mysql-db=sbtest
time=600
threads=16
report-interval=10
db-driver=mysql
```





mysql --host 192.168.100.110 --port 4000 -u root -p



```javascript
set global tidb_disable_txn_auto_retry = off;
```



然后退出客户端。

重新启动 MySQL 客户端执行以下 SQL 语句，创建数据库 sbtest：



mysql --host 192.168.100.110 --port 4000 -u root -p

```javascript
create database sbtest;
```



rm -rf /usr/local/share/sysbench/oltp_common.lua



cd /usr/local/share/sysbench



wget https://raw.githubusercontent.com/pingcap/tidb-bench/master/sysbench/sysbench-patch/oltp_common.lua



cd /opt/tidb-sysbench-test



sysbench --config-file=config oltp_point_select --tables=16 --table-size=10000000 prepare



mysql --host 192.168.100.110 --port 4000 -u root -p



>use sbtest;

>SELECT COUNT(pad) FROM sbtest7 USE INDEX (k_7);

>ANALYZE TABLE sbtest7;



退出



cd /opt/tidb-sysbench-test



Point select 测试命令

sysbench --config-file=config oltp_point_select --tables=16 --table-size=10000000 run



```javascript
SQL statistics:
    queries performed:
        read:                            6885256
        write:                           0
        other:                           0
        total:                           6885256
    transactions:                        6885256 (11475.11 per sec.)
    queries:                             6885256 (11475.11 per sec.)
    ignored errors:                      0      (0.00 per sec.)
    reconnects:                          0      (0.00 per sec.)

General statistics:
    total time:                          600.0122s
    total number of events:              6885256

Latency (ms):
         min:                                    0.27
         avg:                                    1.39
         max:                                   66.22
         95th percentile:                        2.39
         sum:                              9584624.81

Threads fairness:
    events (avg/stddev):           430328.5000/206.31
    execution time (avg/stddev):   599.0391/0.00
```





Update index 测试命令

sysbench --config-file=config oltp_update_index --tables=16 --table-size=10000000 run

```javascript
SQL statistics:
    queries performed:
        read:                            0
        write:                           485
        other:                           3765517
        total:                           3766002
    transactions:                        3766002 (6276.50 per sec.)
    queries:                             3766002 (6276.50 per sec.)
    ignored errors:                      0      (0.00 per sec.)
    reconnects:                          0      (0.00 per sec.)

General statistics:
    total time:                          600.0122s
    total number of events:              3766002

Latency (ms):
         min:                                    0.53
         avg:                                    2.55
         max:                                  169.66
         95th percentile:                        3.82
         sum:                              9590963.16

Threads fairness:
    events (avg/stddev):           235375.1250/224.34
    execution time (avg/stddev):   599.4352/0.00
```

Read-only 测试命令

sysbench --config-file=config oltp_read_only --tables=16 --table-size=10000000 run

```javascript
SQL statistics:
    queries performed:
        read:                            4602836
        write:                           0
        other:                           657548
        total:                           5260384
    transactions:                        328774 (547.94 per sec.)
    queries:                             5260384 (8766.97 per sec.)
    ignored errors:                      0      (0.00 per sec.)
    reconnects:                          0      (0.00 per sec.)

General statistics:
    total time:                          600.0215s
    total number of events:              328774

Latency (ms):
         min:                                   11.01
         avg:                                   29.19
         max:                                  123.01
         95th percentile:                       41.85
         sum:                              9598292.96

Threads fairness:
    events (avg/stddev):           20548.3750/21.06
    execution time (avg/stddev):   599.8933/0.01
```



