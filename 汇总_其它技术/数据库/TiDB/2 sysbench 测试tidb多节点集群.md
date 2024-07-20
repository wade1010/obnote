40核256G内存( tikv 单独部署到3个节点)



wget https://github.com/akopytov/sysbench/archive/refs/tags/1.0.14.tar.gz



tar zxvf 1.0.14.tar.gz



cd sysbench-1.0.14



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
mysql-host=192.168.100.110
mysql-port=4000
mysql-user=root
mysql-password=
mysql-db=sbtest
time=600
threads=1024
report-interval=10
db-driver=mysql
```





mysql -h 127.0.0.1 -P4000 -u root



```javascript
set global tidb_disable_txn_auto_retry = off;
```



然后退出客户端。

重新启动 MySQL 客户端执行以下 SQL 语句，创建数据库 sbtest：



mysql -h 127.0.0.1 -P4000 -u root

```javascript
create database sbtest;
```



rm -rf /usr/local/share/sysbench/oltp_common.lua



cd /usr/local/share/sysbench



wget https://raw.githubusercontent.com/pingcap/tidb-bench/master/sysbench/sysbench-patch/oltp_common.lua



cd /opt/tidb-sysbench-test



sysbench --config-file=config oltp_point_select --tables=32 --table-size=10000000 prepare



大概花了3个小时插完数据



mysql --host 192.168.199.110 --port 4000 -u root -p



>use sbtest;

>SELECT COUNT(pad) FROM sbtest7 USE INDEX (k_7);

>ANALYZE TABLE sbtest7;



退出



cd /opt/tidb-sysbench-test



Point select 测试命令

sysbench --config-file=config oltp_point_select --tables=32 --table-size=10000000 run



```javascript
SQL statistics:
    queries performed:
        read:                            55678623
        write:                           0
        other:                           0
        total:                           55678623
    transactions:                        55678623 (92759.30 per sec.)
    queries:                             55678623 (92759.30 per sec.)
    ignored errors:                      0      (0.00 per sec.)
    reconnects:                          0      (0.00 per sec.)

General statistics:
    total time:                          600.2465s
    total number of events:              55678623

Latency (ms):
         min:                                    0.53
         avg:                                   11.03
         max:                                  306.63
         95th percentile:                       50.11
         sum:                            614321462.23

Threads fairness:
    events (avg/stddev):           54373.6553/369.25
    execution time (avg/stddev):   599.9233/0.02

```





Update index 测试命令

sysbench --config-file=config oltp_update_index --tables=32 --table-size=10000000 run

```javascript
SQL statistics:
    queries performed:
        read:                            0
        write:                           8257857
        other:                           168235
        total:                           8426092
    transactions:                        8426092 (14038.55 per sec.)
    queries:                             8426092 (14038.55 per sec.)
    ignored errors:                      0      (0.00 per sec.)
    reconnects:                          0      (0.00 per sec.)

General statistics:
    total time:                          600.2071s
    total number of events:              8426092

Latency (ms):
         min:                                    1.72
         avg:                                   72.92
         max:                                 3661.82
         95th percentile:                      125.52
         sum:                            614446210.68

Threads fairness:
    events (avg/stddev):           8228.6055/31.32
    execution time (avg/stddev):   600.0451/0.05


```

Read-only 测试命令

sysbench --config-file=config oltp_read_only --tables=32 --table-size=10000000 run

```javascript

```



大致跑了下

1、oltp_point_select

[ 10s ] thds: 1024 tps: 81542.37 qps: 81542.07 (r/w/o: 81542.07/0.00/0.00) lat (ms,95%): 54.83 err/s: 0.00 reconn/s: 0.00

[ 20s ] thds: 1024 tps: 96425.54 qps: 96425.84 (r/w/o: 96425.84/0.00/0.00) lat (ms,95%): 47.47 err/s: 0.00 reconn/s: 0.00



2、oltp_update_index

[ 10s ] thds: 1024 tps: 14136.95 qps: 14136.95 (r/w/o: 0.00/13858.40/278.54) lat (ms,95%): 123.28 err/s: 0.00 reconn/s: 0.00

[ 20s ] thds: 1024 tps: 14995.24 qps: 14995.24 (r/w/o: 0.00/14690.54/304.70) lat (ms,95%): 116.80 err/s: 0.00 reconn/s: 0.00



3、oltp_read_only

[ 10s ] thds: 1024 tps: 1488.00 qps: 24654.40 (r/w/o: 21582.52/0.00/3071.88) lat (ms,95%): 1191.92 err/s: 0.00 reconn/s: 0.00

[ 20s ] thds: 1024 tps: 1519.49 qps: 24292.57 (r/w/o: 21256.09/0.00/3036.48) lat (ms,95%): 1149.76 err/s: 0.00 reconn/s: 0.00





