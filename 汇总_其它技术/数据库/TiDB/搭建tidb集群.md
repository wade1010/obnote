一、环境与系统配置检查 开始



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

ssh-copy-id -i ~/.ssh/id_rsa.pub 192.168.100.111

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

pd_servers:
  - host: 192.168.100.110
    name: "pd-1"
    client_port: 2379
    peer_port: 2380
    deploy_dir: "/nvme0/tidb-deploy/pd-2379"
    data_dir: "/nvme0/tidb-data/pd-2379"
    log_dir: "/nvme0/tidb-deploy/pd-2379/log"
  - host: 192.168.100.110
    name: "pd-2"
    client_port: 12379
    peer_port: 12380
    deploy_dir: "/nvme0/tidb-deploy/pd-12379"
    data_dir: "/nvme0/tidb-data/pd-12379"
    log_dir: "/nvme0/tidb-deploy/pd-12379/log"
  - host: 192.168.100.110
    name: "pd-3"
    client_port: 22379
    peer_port: 22380
    deploy_dir: "/nvme0/tidb-deploy/pd-22379"
    data_dir: "/nvme0/tidb-data/pd-22379"
    log_dir: "/nvme0/tidb-deploy/pd-22379/log"

tidb_servers:
  - host: 192.168.100.110
    port: 4000
    status_port: 10080
    deploy_dir: "/tidb-deploy/tidb-4000"
    log_dir: "/tidb-deploy/tidb-4000/log"
  - host: 192.168.100.110
    port: 4001
    status_port: 10081
    deploy_dir: "/tidb-deploy/tidb-4001"
    log_dir: "/tidb-deploy/tidb-4001/log"

tikv_servers:
  - host: 192.168.100.111
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme0/tidb-deploy/tikv-20160"
    data_dir: "/nvme0/tidb-data/tikv-20160"
    log_dir: "/nvme0/tidb-deploy/tikv-20160/log"
  - host: 192.168.100.112
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme0/tidb-deploy/tikv-20160"
    data_dir: "/nvme0/tidb-data/tikv-20160"
    log_dir: "/nvme0/tidb-deploy/tikv-20160/log"
  - host: 192.168.100.113
    port: 20160
    status_port: 20180
    deploy_dir: "/nvme0/tidb-deploy/tikv-20160"
    data_dir: "/nvme0/tidb-data/tikv-20160"
    log_dir: "/nvme0/tidb-deploy/tikv-20160/log"


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





pd            192.168.100.110  2379/2380    linux/x86_64  /nvme0/tidb-deploy/pd-2379,/nvme0/tidb-data/pd-2379

pd            192.168.100.110  12379/12380  linux/x86_64  /nvme0/tidb-deploy/pd-12379,/nvme0/tidb-data/pd-12379

pd            192.168.100.110  22379/22380  linux/x86_64  /nvme0/tidb-deploy/pd-22379,/nvme0/tidb-data/pd-22379

tikv          192.168.100.111  20160/20180  linux/x86_64  /nvme0/tidb-deploy/tikv-20160,/nvme0/tidb-data/tikv-20160

tikv          192.168.100.112  20160/20180  linux/x86_64  /nvme0/tidb-deploy/tikv-20160,/nvme0/tidb-data/tikv-20160

tikv          192.168.100.113  20160/20180  linux/x86_64  /nvme0/tidb-deploy/tikv-20160,/nvme0/tidb-data/tikv-20160

tidb          192.168.100.110  4000/10080   linux/x86_64  /tidb-deploy/tidb-4000

tidb          192.168.100.110  4001/10081   linux/x86_64  /tidb-deploy/tidb-4001

prometheus    192.168.100.110  9090         linux/x86_64  /tidb-deploy/prometheus-8249,/tidb-data/prometheus-8249

grafana       192.168.100.110  3000         linux/x86_64  /tidb-deploy/grafana-3000

alertmanager  192.168.100.110  9093/9094    linux/x86_64  /tidb-deploy/alertmanager-9093,/tidb-data/alertmanager-9093





启动tidb集群

tiup cluster start tidb-test