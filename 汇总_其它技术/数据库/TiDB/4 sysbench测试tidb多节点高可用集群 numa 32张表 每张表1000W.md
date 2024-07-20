

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809089.jpg)





| 实例 | 个数 | 物理机配置 | IP | 配置 |
| - | - | - | - | - |
| TiDB | 3 | 16 VCore 32GB \* 1 | 192.168.100.110<br>192.168.100.112<br>192.168.100.113 | 默认端口<br>全局目录配置 |
| PD | 3 | 4 VCore 8GB \* 1 | 192.168.100.110<br>192.168.100.112<br>192.168.100.113 | 默认端口<br>全局目录配置 |
| TiKV | 3 | 16 VCore 32GB 2TB (nvme ssd) \* 1 | 192.168.100.110<br>192.168.100.112<br>192.168.100.113 | 默认端口<br>全局目录配置 |
| Monitoring &amp; Grafana | 1 | 4 VCore 8GB \* 1 500GB (ssd) | 192.168.100.110 | 默认端口<br>全局目录配置 |


一、环境与系统配置检查 开始

fdisk /dev/nvme0n1

parted -s -a optimal /dev/nvme0n1 mklabel gpt -- mkpart primary ext4 1 -1



mkfs.ext4 /dev/nvme0n1p1



lsblk -f  记录下UUID



vim /etc/fstab



```javascript
UUID=xxxxx /nvme0 ext4 defaults,nodelalloc,noatime 0 2
```





mkdir /nvme0 && \
mount -a



mount -t ext4



fdisk /dev/nvme1n1

```javascript
d
w
```

parted -s -a optimal /dev/nvme1n1 mklabel gpt -- mkpart primary ext4 1 -1



mkfs.ext4 /dev/nvme1n1p1



lsblk -f  记录下UUID



vim /etc/fstab



```javascript
UUID=xxxxx /nvme1 ext4 defaults,nodelalloc,noatime 0 2
```





mkdir /nvme1 && mount -a



mount -t ext4





echo "vm.swappiness = 0">> /etc/sysctl.conf
swapoff -a && swapon -a
sysctl -p





firewall-cmd --state





sudo systemctl stop ntpd.service && \
sudo ntpdate 192.168.100.220 && \
sudo systemctl start ntpd.service





cat /sys/kernel/mm/transparent_hugepage/enabled



cat /sys/block/sd[bc]/queue/scheduler



udevadm info --name=/dev/sdb | grep ID_SERIAL

记录下ID_SERIAL



cpupower frequency-info --policy





tuned-adm list





mkdir /etc/tuned/balanced-tidb-optimal/
vim /etc/tuned/balanced-tidb-optimal/tuned.conf

```javascript
[main]
include=balanced

[cpu]
governor=performance

[vm]
transparent_hugepages=never

[disk]
devices_udev_regex=(ID_SERIAL=xxx)|(ID_SERIAL=xxxx)
elevator=noop
```





tuned-adm profile balanced-tidb-optimal





cat /sys/kernel/mm/transparent_hugepage/enabled





cat /sys/block/sd[bc]/queue/scheduler





cpupower frequency-info --policy





echo "fs.file-max = 1000000">> /etc/sysctl.conf
echo "net.core.somaxconn = 32768">> /etc/sysctl.conf
echo "net.ipv4.tcp_tw_recycle = 0">> /etc/sysctl.conf
echo "net.ipv4.tcp_syncookies = 0">> /etc/sysctl.conf
echo "vm.overcommit_memory = 1">> /etc/sysctl.conf
echo "vm.swappiness = 0">> /etc/sysctl.conf
sysctl -p







cat << EOF >>/etc/security/limits.conf
tidb           soft    nofile          1000000
tidb           hard    nofile          1000000
tidb           soft    stack          32768
tidb           hard    stack          32768
EOF







useradd tidb && \
passwd tidb





visudo

```javascript
tidb ALL=(ALL) NOPASSWD: ALL
```



环境与系统配置检查 结束

---

二、配置免密登录



ssh-keygen -t rsa

ssh-copy-id -i ~/.ssh/id_rsa.pub 192.168.100.110

ssh-copy-id -i ~/.ssh/id_rsa.pub 192.168.100.112

ssh-copy-id -i ~/.ssh/id_rsa.pub 192.168.100.113





---

三、在中控机上安装 TiUP 组件



su tidb



curl --proto '=https' --tlsv1.2 -sSf https://tiup-mirrors.pingcap.com/install.sh | sh



source .bash_profile



which tiup



tiup cluster

---

四、初始化集群拓扑文件

mkdir ~/tidb-cluster



cd ~/tidb-cluster



tiup cluster template > topology.yaml



或者 topology.yaml 粘贴如下内容进去保存

```javascript
global:
  user: "tidb"
  group: "tidb"
  ssh_port: 22
  deploy_dir: "/tidb-deploy"
  data_dir: "/tidb-data"
  arch: "amd64"

monitored:
  node_exporter_port: 9100
  blackbox_exporter_port: 9115
  deploy_dir: "/tidb-deploy/monitored-9100"
  data_dir: "/tidb-data/monitored-9100"
  log_dir: "/tidb-deploy/monitored-9100/log"
server_configs:
  tidb:
    log.level: "error"
    prepared-plan-cache.enabled: true
  tikv:
    log-level: "error"
    rocksdb.defaultcf.block-cache-size: "72GB"
    rocksdb.writecf.block-cache-size: "18GB"
    storage.block-cache.capacity: "90GB"
pd_servers:
  - host: 192.168.100.110
    name: "pd-1"
    client_port: 2379
    peer_port: 2380
    deploy_dir: "/nvme0/tidb-deploy/pd-2379"
    data_dir: "/nvme0/tidb-data/pd-2379"
    log_dir: "/nvme0/tidb-deploy/pd-2379/log"
    numa_node: "0,1"
  - host: 192.168.100.112
    name: "pd-2"
    client_port: 2379
    peer_port: 2380
    deploy_dir: "/nvme0/tidb-deploy/pd-2379"
    data_dir: "/nvme0/tidb-data/pd-2379"
    log_dir: "/nvme0/tidb-deploy/pd-2379/log"
    numa_node: "0,1"
  - host: 192.168.100.113
    name: "pd-3"
    client_port: 2379
    peer_port: 2380
    deploy_dir: "/nvme0/tidb-deploy/pd-2379"
    data_dir: "/nvme0/tidb-data/pd-2379"
    log_dir: "/nvme0/tidb-deploy/pd-2379/log"
    numa_node: "0,1"

tidb_servers:
  - host: 192.168.100.110
    port: 4000
    status_port: 10080
    deploy_dir: "/tidb-deploy/tidb-4000"
    log_dir: "/tidb-deploy/tidb-4000/log"
    numa_node: "0,1"
  - host: 192.168.100.112
    port: 4000
    status_port: 10080
    deploy_dir: "/tidb-deploy/tidb-4000"
    log_dir: "/tidb-deploy/tidb-4000/log"
    numa_node: "0,1"
  - host: 192.168.100.113
    port: 4000
    status_port: 10080
    deploy_dir: "/tidb-deploy/tidb-4000"
    log_dir: "/tidb-deploy/tidb-4000/log"
    numa_node: "0,1"

tikv_servers:
  - host: 192.168.100.110
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme1/tidb-deploy/tikv-20160"
    data_dir: "/nvme1/tidb-data/tikv-20160"
    log_dir: "/nvme1/tidb-deploy/tikv-20160/log"
    numa_node: "0,1"
  - host: 192.168.100.112
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme1/tidb-deploy/tikv-20160"
    data_dir: "/nvme1/tidb-data/tikv-20160"
    log_dir: "/nvme1/tidb-deploy/tikv-20160/log"
    numa_node: "0,1"
  - host: 192.168.100.113
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme1/tidb-deploy/tikv-20160"
    data_dir: "/nvme1/tidb-data/tikv-20160"
    log_dir: "/nvme1/tidb-deploy/tikv-20160/log"
    numa_node: "0,1"

monitoring_servers:
  - host: 192.168.100.110
    port: 9090
    deploy_dir: "/tidb-deploy/prometheus-8249"
    data_dir: "/tidb-data/prometheus-8249"
    log_dir: "/tidb-deploy/prometheus-8249/log"

grafana_servers:
  - host: 192.168.100.110
    port: 3000
    deploy_dir: /tidb-deploy/grafana-3000

alertmanager_servers:
  - host: 192.168.100.110
    web_port: 9093
    cluster_port: 9094
    deploy_dir: "/tidb-deploy/alertmanager-9093"
    data_dir: "/tidb-data/alertmanager-9093"
    log_dir: "/tidb-deploy/alertmanager-9093/log"

```



上面的numa_node是根据下面命令查看空闲内存配置的

```javascript
[tidb@node113 ~]$ numactl --hardware
available: 2 nodes (0-1)
node 0 cpus: 0 2 4 6 8 10 12 14 16 18 20 22 24 26 28 30 32 34 36 38
node 0 size: 131026 MB
node 0 free: 121892 MB
node 1 cpus: 1 3 5 7 9 11 13 15 17 19 21 23 25 27 29 31 33 35 37 39
node 1 size: 131072 MB
node 1 free: 80428 MB
node distances:
node   0   1 
  0:  10  20 
  1:  20  10 
```



如果free多 可以都绑定



tiup cluster check ./topology.yaml


tiup cluster check ./topology.yaml --apply

tiup cluster deploy tidb-test v5.0.0 ./topology.yaml







启动tidb集群



tiup cluster start tidb-test



验证集群状态

tiup cluster display tidb-test









mkdir -p /opt/tidb-sysbench-test && cd /opt/tidb-sysbench-test



vim config



每10秒显示一次测试结果，一共持续60秒

mysql-host 为互相测试，比如100.112上可以写100.113  ， 100.113上面可以写100.110  ，100.110上面可以写100.112

```javascript
mysql-host=192.168.100.112
mysql-port=4000
mysql-user=root
mysql-password=
mysql-db=sbtest
time=60
threads=1024
report-interval=10
db-driver=mysql
```





mysql --host 192.168.100.112 --port 4000 -u root -p



```javascript
set global tidb_disable_txn_auto_retry = off;
```



然后退出客户端。

重新启动 MySQL 客户端执行以下 SQL 语句，创建数据库 sbtest：



mysql --host 192.168.100.112 --port 4000 -u root -p

```javascript
create database sbtest;
```



rm -rf /usr/local/share/sysbench/oltp_common.lua



cd /usr/local/share/sysbench



wget https://raw.githubusercontent.com/pingcap/tidb-bench/master/sysbench/sysbench-patch/oltp_common.lua



cd /opt/tidb-sysbench-test



sysbench --config-file=config oltp_point_select --tables=32 --table-size=10000000 prepare



mysql --host 192.168.100.112 --port 4000 -u root -p



```javascript
use sbtest;
SELECT COUNT(pad) FROM sbtest1 USE INDEX (k_1);
ANALYZE TABLE sbtest1;
SELECT COUNT(pad) FROM sbtest2 USE INDEX (k_2);
ANALYZE TABLE sbtest2;
SELECT COUNT(pad) FROM sbtest3 USE INDEX (k_3);
ANALYZE TABLE sbtest3;
SELECT COUNT(pad) FROM sbtest4 USE INDEX (k_4);
ANALYZE TABLE sbtest4;
SELECT COUNT(pad) FROM sbtest5 USE INDEX (k_5);
ANALYZE TABLE sbtest5;
SELECT COUNT(pad) FROM sbtest6 USE INDEX (k_6);
ANALYZE TABLE sbtest6;
SELECT COUNT(pad) FROM sbtest7 USE INDEX (k_7);
ANALYZE TABLE sbtest7;
SELECT COUNT(pad) FROM sbtest8 USE INDEX (k_8);
ANALYZE TABLE sbtest8;
SELECT COUNT(pad) FROM sbtest9 USE INDEX (k_9);
ANALYZE TABLE sbtest9;
SELECT COUNT(pad) FROM sbtest10 USE INDEX (k_10);
ANALYZE TABLE sbtest10;
SELECT COUNT(pad) FROM sbtest11 USE INDEX (k_11);
ANALYZE TABLE sbtest11;
SELECT COUNT(pad) FROM sbtest12 USE INDEX (k_12);
ANALYZE TABLE sbtest12;
SELECT COUNT(pad) FROM sbtest13 USE INDEX (k_13);
ANALYZE TABLE sbtest13;
SELECT COUNT(pad) FROM sbtest14 USE INDEX (k_14);
ANALYZE TABLE sbtest14;
SELECT COUNT(pad) FROM sbtest15 USE INDEX (k_15);
ANALYZE TABLE sbtest15;
SELECT COUNT(pad) FROM sbtest16 USE INDEX (k_16);
ANALYZE TABLE sbtest16;
SELECT COUNT(pad) FROM sbtest17 USE INDEX (k_17);
ANALYZE TABLE sbtest17;
SELECT COUNT(pad) FROM sbtest18 USE INDEX (k_18);
ANALYZE TABLE sbtest18;
SELECT COUNT(pad) FROM sbtest19 USE INDEX (k_19);
ANALYZE TABLE sbtest19;
SELECT COUNT(pad) FROM sbtest20 USE INDEX (k_20);
ANALYZE TABLE sbtest20;
SELECT COUNT(pad) FROM sbtest21 USE INDEX (k_21);
ANALYZE TABLE sbtest21;
SELECT COUNT(pad) FROM sbtest22 USE INDEX (k_22);
ANALYZE TABLE sbtest22;
SELECT COUNT(pad) FROM sbtest23 USE INDEX (k_23);
ANALYZE TABLE sbtest23;
SELECT COUNT(pad) FROM sbtest24 USE INDEX (k_24);
ANALYZE TABLE sbtest24;
SELECT COUNT(pad) FROM sbtest25 USE INDEX (k_25);
ANALYZE TABLE sbtest25;
SELECT COUNT(pad) FROM sbtest26 USE INDEX (k_26);
ANALYZE TABLE sbtest26;
SELECT COUNT(pad) FROM sbtest27 USE INDEX (k_27);
ANALYZE TABLE sbtest27;
SELECT COUNT(pad) FROM sbtest28 USE INDEX (k_28);
ANALYZE TABLE sbtest28;
SELECT COUNT(pad) FROM sbtest29 USE INDEX (k_29);
ANALYZE TABLE sbtest29;
SELECT COUNT(pad) FROM sbtest30 USE INDEX (k_30);
ANALYZE TABLE sbtest30;
SELECT COUNT(pad) FROM sbtest31 USE INDEX (k_31);
ANALYZE TABLE sbtest31;
SELECT COUNT(pad) FROM sbtest32 USE INDEX (k_32);
ANALYZE TABLE sbtest32;

```



退出



cd /opt/tidb-sysbench-test





3个节点同时 持续10分钟压测



Point select 测试命令

sysbench --config-file=config oltp_point_select --tables=32 --table-size=10000000 run

```javascript
    transactions:                        12263739 (68073.63 per sec.)
    queries:                             12263739 (68073.63 per sec.)

    transactions:                        12322681 (68404.98 per sec.)
    queries:                             12322681 (68404.98 per sec.)

    transactions:                        12016162 (66702.94 per sec.)
    queries:                             12016162 (66702.94 per sec.)
```



numa绑在node 1上

```javascript
[ 10s ] thds: 1024 tps: 31957.05 qps: 31957.05 (r/w/o: 31957.05/0.00/0.00) lat (ms,95%): 73.13 err/s: 0.00 reconn/s: 0.00
[ 20s ] thds: 1024 tps: 37010.09 qps: 37010.09 (r/w/o: 37010.09/0.00/0.00) lat (ms,95%): 61.08 err/s: 0.00 reconn/s: 0.00
```

Update index 测试命令

sysbench --config-file=config oltp_update_index --tables=32 --table-size=10000000 run

```javascript
    transactions:                        881947 (4885.92 per sec.)
    queries:                             881947 (4885.92 per sec.)

    transactions:                        856822 (4712.01 per sec.)
    queries:                             856822 (4712.01 per sec.)

    transactions:                        864000 (4752.78 per sec.)
    queries:                             864000 (4752.78 per sec.)
```

Read-only 测试命令

sysbench --config-file=config oltp_read_only --tables=32 --table-size=10000000 run

```javascript
    transactions:                        351327 (1942.14 per sec.)
    queries:                             5621232 (31074.31 per sec.)

    transactions:                        351327 (1942.14 per sec.)
    queries:                             5621232 (31074.31 per sec.)

    transactions:                        355641 (1968.00 per sec.)
    queries:                             5690256 (31488.05 per sec.)
```



oltp_update_non_index测试命令

sysbench --config-file=config oltp_update_non_index --tables=32 --table-size=10000000 run

```javascript
    transactions:                        4496931 (24938.60 per sec.)
    queries:                             4496931 (24938.60 per sec.)

    transactions:                        4541404 (25209.52 per sec.)
    queries:                             4541404 (25209.52 per sec.)

    transactions:                        4542572 (25212.88 per sec.)
    queries:                             4542572 (25212.88 per sec.)
```





oltp_read_write测试命令

sysbench --config-file=config oltp_read_write --tables=32 --table-size=10000000 run

```javascript
    transactions:                        185804 (1025.11 per sec.)
    queries:                             3716080 (20502.27 per sec.)

    transactions:                        193450 (1067.82 per sec.)
    queries:                             3869000 (21356.32 per sec.)

    transactions:                        194460 (1074.21 per sec.)
    queries:                             3889200 (21484.30 per sec.)
```





oltp_write_only测试命令

sysbench --config-file=config oltp_write_only --tables=32 --table-size=10000000 run

```javascript
    transactions:                        316120 (1748.53 per sec.)
    queries:                             1896720 (10491.17 per sec.)

    transactions:                        309519 (1713.74 per sec.)
    queries:                             1857114 (10282.46 per sec.)

    transactions:                        313470 (1735.29 per sec.)
    queries:                             1880820 (10411.73 per sec.)
```





select_random_points测试命令

sysbench --config-file=config select_random_points --tables=32 --table-size=10000000 run

```javascript
    transactions:                        1147468 (6368.39 per sec.)
    queries:                             1147468 (6368.39 per sec.)

    transactions:                        1134828 (6297.33 per sec.)
    queries:                             1134828 (6297.33 per sec.)

    transactions:                        1132011 (6280.86 per sec.)
    queries:                             1132011 (6280.86 per sec.)
```





select_random_ranges测试命令

sysbench --config-file=config select_random_ranges --tables=32 --table-size=10000000 run

```javascript
    transactions:                        1673457 (9290.30 per sec.)
    queries:                             1673457 (9290.30 per sec.)

    transactions:                        1645887 (9127.01 per sec.)
    queries:                             1645887 (9127.01 per sec.)

    transactions:                        1641039 (9098.03 per sec.)
    queries:                             1641039 (9098.03 per sec.)
```





vim /nvme1/tidb-deploy/tikv-20160/scripts/run_tikv.sh

vim /tidb-deploy/tidb-4000/scripts/run_tidb.sh





exec numactl  --cpunodebind=1 --membind=1 env GODEBUG=madvdontneed=1 bin/tidb-server \

    -P 4000 \

    --status="10080" \

    --host="0.0.0.0" \

    --advertise-address="192.168.100.113" \

    --store="tikv" \

    --path="192.168.100.110:2379,192.168.100.112:2379,192.168.100.113:2379" \

    --log-slow-query="log/tidb_slow_query.log" \

    --config=conf/tidb.toml \

    --log-file="/tidb-deploy/tidb-4000/log/tidb.log" 2>> "/tidb-deploy/tidb-4000/log/tidb_stderr.log"





exec numactl --cpunodebind=0,1 --membind=0,1 bin/tikv-server \

    --addr "0.0.0.0:20160" \

    --advertise-addr "192.168.100.110:20160" \

    --status-addr "0.0.0.0:20180" \

    --advertise-status-addr "192.168.100.110:20180" \

    --pd "192.168.100.110:2379,192.168.100.112:2379,192.168.100.113:2379" \

    --data-dir "/nvme1/tidb-data/tikv-20160" \

    --config conf/tikv.toml \

    --log-file "/nvme1/tidb-deploy/tikv-20160/log/tikv.log" 2>> "/nvme1/tidb-deploy/tikv-20160/log/tikv_stderr.log"

