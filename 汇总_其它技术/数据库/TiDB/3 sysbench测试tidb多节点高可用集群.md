

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809004.jpg)





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
    numa_node: "0,1,2,3"
  - host: 192.168.100.112
    name: "pd-2"
    client_port: 2379
    peer_port: 2380
    deploy_dir: "/nvme0/tidb-deploy/pd-2379"
    data_dir: "/nvme0/tidb-data/pd-2379"
    log_dir: "/nvme0/tidb-deploy/pd-2379/log"
    numa_node: "0,1,2,3"
  - host: 192.168.100.113
    name: "pd-3"
    client_port: 2379
    peer_port: 2380
    deploy_dir: "/nvme0/tidb-deploy/pd-2379"
    data_dir: "/nvme0/tidb-data/pd-2379"
    log_dir: "/nvme0/tidb-deploy/pd-2379/log"
    numa_node: "0,1,2,3"

tidb_servers:
  - host: 192.168.100.110
    port: 4000
    status_port: 10080
    deploy_dir: "/tidb-deploy/tidb-4000"
    log_dir: "/tidb-deploy/tidb-4000/log"
    numa_node: "4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19"
  - host: 192.168.100.112
    port: 4000
    status_port: 10080
    deploy_dir: "/tidb-deploy/tidb-4000"
    log_dir: "/tidb-deploy/tidb-4000/log"
    numa_node: "4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19"
  - host: 192.168.100.113
    port: 4000
    status_port: 10080
    deploy_dir: "/tidb-deploy/tidb-4000"
    log_dir: "/tidb-deploy/tidb-4000/log"
    numa_node: "4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19"

tikv_servers:
  - host: 192.168.100.110
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme1/tidb-deploy/tikv-20160"
    data_dir: "/nvme1/tidb-data/tikv-20160"
    log_dir: "/nvme1/tidb-deploy/tikv-20160/log"
    numa_node: "20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39"
  - host: 192.168.100.112
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme1/tidb-deploy/tikv-20160"
    data_dir: "/nvme1/tidb-data/tikv-20160"
    log_dir: "/nvme1/tidb-deploy/tikv-20160/log"
    numa_node: "20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39"
  - host: 192.168.100.113
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme1/tidb-deploy/tikv-20160"
    data_dir: "/nvme1/tidb-data/tikv-20160"
    log_dir: "/nvme1/tidb-deploy/tikv-20160/log"
    numa_node: "20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39"

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



sysbench --config-file=config oltp_point_select --tables=16 --table-size=10000000 prepare



mysql --host 192.168.100.112 --port 4000 -u root -p



>use sbtest;

>SELECT COUNT(pad) FROM sbtest7 USE INDEX (k_7);

>ANALYZE TABLE sbtest7;



退出



cd /opt/tidb-sysbench-test





3个节点同时 持续2分钟压测



Point select 测试命令

sysbench --config-file=config oltp_point_select --tables=32 --table-size=10000000 run

```javascript
    transactions:                        7244391 (60241.20 per sec.)
    queries:                             7244391 (60241.20 per sec.)

    transactions:                        7043101 (58563.61 per sec.)
    queries:                             7043101 (58563.61 per sec.)

    transactions:                        6939812 (57760.47 per sec.)
    queries:                             6939812 (57760.47 per sec.)

```



Update index 测试命令

sysbench --config-file=config oltp_update_index --tables=32 --table-size=10000000 run

```javascript
    transactions:                        617371 (5134.68 per sec.)
    queries:                             617371 (5134.68 per sec.)

    transactions:                        594869 (4939.85 per sec.)
    queries:                             594869 (4939.85 per sec.)

    transactions:                        592830 (4922.11 per sec.)
    queries:                             592830 (4922.11 per sec.)

```

Read-only 测试命令

sysbench --config-file=config oltp_read_only --tables=32 --table-size=10000000 run

```javascript
    transactions:                        202735 (1682.26 per sec.)
    queries:                             3243760 (26916.11 per sec.)

    transactions:                        196393 (1620.58 per sec.)
    queries:                             3142288 (25929.29 per sec.)

    transactions:                        210106 (1740.99 per sec.)
    queries:                             3361696 (27855.81 per sec.)

```



