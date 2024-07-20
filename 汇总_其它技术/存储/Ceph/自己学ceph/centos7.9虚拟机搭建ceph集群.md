# 一、基础搭建

网络规划

1个mon，3个osd，在扩展到4个osd

| 序号 | 节点 | public-network | cluster-network | 角色 | 
| -- | -- | -- | -- | -- |
| 1 | monitor | 192.168.1.200 | 192.168.56.10 | mon | 
| 2 | node1 | 192.168.1.201 | 192.168.56.11 | osd-1 | 
| 3 | node2 | 192.168.1.202 | 192.168.56.12 | osd-2 | 
| 4 | node3（扩展） | 192.168.1.203 | 192.168.56.13 | osd-3 | 


先配置一个monitor虚拟机，添加两个网卡，然后可以通过nmtui配置两个ip段，配置好之后，关机。然后复制3台虚拟机，配置一台开机一台，开机后，修改ip到对应的规划ip，然后启动下一台即可，最后全部启动。

每个node节点添加一个磁盘，我这设置是20G

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359529.jpg)

> 所有节点都执行

systemctl disable firewalld

systemctl stop firewalld

关闭 selinux

setenforce 0

sed -i 's/^SELINUX=.*/SELINUX=disabled/' /etc/selinux/config

vim /etc/hosts

```
192.168.1.200 monitor
192.168.1.201 node1
192.168.1.202 node2
192.168.1.203 node3
```

确保每个节点都有 CentOS 7 系统，并且已经更新到最新的软件包。

yum update -y

注意centos7最新只能安装15.2.9版

yum install -y [https://download.ceph.com/rpm-15.2.9/el7/noarch/ceph-release-1-1.el7.noarch.rpm](https://download.ceph.com/rpm-15.2.9/el7/noarch/ceph-release-1-1.el7.noarch.rpm)

sudo yum install -y wget

sudo yum install -y epel-release

sudo yum install -y python-pip

sudo pip install --upgrade pip

sudo yum install -y python-devel

sudo yum install -y gcc

sudo yum install -y glibc-headers

sudo yum install -y kernel-devel

sudo yum install -y libffi-devel

sudo yum install -y openssl-devel

yum install -y ceph ceph-fuse ceph-mds ceph-mon ceph-osd ceph-radosgw

monitor节点执行下面命令，免密登录

ssh-keygen

ssh-copy-id node1

ssh-copy-id node2

ssh-copy-id node3

开始部署集群

# 二、部署mon

1、登录到monitor节点

vim /etc/ceph/ceph.conf

```
[global]
fsid = e8020c3a-1181-4ff9-8574-d3004cf518f7
mon_initial_members = monitor
mon_host = 192.168.1.200
ms_type = async+msgr2
auth cluster required = cephx
auth service required = cephx
auth client required = cephx
osd journal size = 1024
osd pool default size = 3
osd pool default min size = 2
osd pool default pg num = 1024
osd pool default pgp num = 1024
osd crush chooseleaf type = 1
[mon]
ms bind msgr2 = true

```

2、创建集群keyring并生成监视器秘钥

```
sudo ceph-authtool --create-keyring /tmp/ceph.mon.keyring --gen-key -n mon. --cap mon 'allow *'
```

3、生成管理员秘钥，生成client.admin用户，并将该用户添加到密钥

```
sudo ceph-authtool --create-keyring /etc/ceph/ceph.client.admin.keyring --gen-key -n client.admin --cap mon 'allow *' --cap osd 'allow *' --cap mds 'allow *' --cap mgr 'allow *'
```

4.0、生成bootstrap-osd keyring

```
sudo ceph-authtool --create-keyring /var/lib/ceph/bootstrap-osd/ceph.keyring --gen-key -n client.bootstrap-osd --cap mon 'profile bootstrap-osd' --cap mgr 'allow r'
```

4.1、把管理员秘钥，导入到监视器秘钥文件中

```
sudo ceph-authtool /tmp/ceph.mon.keyring --import-keyring /etc/ceph/ceph.client.admin.keyring
```

```
sudo ceph-authtool /tmp/ceph.mon.keyring --import-keyring /var/lib/ceph/bootstrap-osd/ceph.keyring
```

```
sudo chown ceph:ceph /tmp/ceph.mon.keyring
```

5、生成监视器映射，保存到/tmp/monmap

```
sudo monmaptool --create --add monitor 192.168.1.200 --fsid e8020c3a-1181-4ff9-8574-d3004cf518f7 /tmp/monmap
```

6、创建mon默认数据目录

```
sudo -u ceph mkdir /var/lib/ceph/mon/ceph-monitor
```

7、Populate the monitor daemon(s) with the monitor map and keyring

```
sudo -u ceph ceph-mon --mkfs -i monitor --monmap /tmp/monmap --keyring /tmp/ceph.mon.keyring
```

9、启动monitor(s)

```
systemctl enable ceph-mon@monitor

systemctl start ceph-mon@monitor

//停止 systemctl stop ceph-mon@monitor
```

//@后面内容就是mon_initial_members的value，这里就是monitor

ceph-authtool --create-keyring /etc/ceph/ceph.mgr.keyring --gen-key -n mgr. --cap mgr 'allow *'

sudo -u ceph mkdir -p /var/lib/ceph/mgr/ceph-bob

```
ceph auth get-or-create mgr.bob mon 'allow profile mgr' osd 'allow *' mds 'allow *' -o /var/lib/ceph/mgr/ceph-bob/keyring
```

ceph-mgr -i bob

> 重启 systemctl restart ceph-mgr@bob


10、验证集群在运行

ceph -s

```
[root@monitor ceph]# ceph -s
  cluster:
    id:     e8020c3a-1181-4ff9-8574-d3004cf518f7
    health: HEALTH_WARN
            mon is allowing insecure global_id reclaim
            Module 'restful' has failed dependency: No module named 'pecan'
            1 monitors have not enabled msgr2
            OSD count 0 < osd_pool_default_size 1

  services:
    mon: 1 daemons, quorum monitor (age 8m)
    mgr: bob(active, since 72s)
    osd: 0 osds: 0 up, 0 in

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0 B
    usage:   0 B used, 0 B / 0 B avail
    pgs:

```

解决上面pecan报错，Module 'restful' has failed dependency: No module named 'pecan' (这个在centos7上安装13版本不会出现)

yum install python36-devel -y

pip3 install pecan werkzeug

systemctl restart ceph-mon.target

systemctl restart ceph-mgr.target

#没生效记得重启服务器

ceph config set mon auth_allow_insecure_global_id_reclaim false

11、scp ceph.client.admin.keyring到node节点

```
scp /etc/ceph/ceph.conf root@node1:/etc/ceph/
scp /etc/ceph/ceph.conf root@node2:/etc/ceph/
scp /etc/ceph/ceph.conf root@node3:/etc/ceph/
```

12、scp osd启动keyring

```
scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node1:/var/lib/ceph/bootstrap-osd/ceph.keyring
scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node1:/etc/ceph/ceph.client.bootstrap-osd.keyring
scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node2:/var/lib/ceph/bootstrap-osd/ceph.keyring
scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node2:/etc/ceph/ceph.client.bootstrap-osd.keyring
scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node3:/var/lib/ceph/bootstrap-osd/ceph.keyring
scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node3:/etc/ceph/ceph.client.bootstrap-osd.keyring
```

# 三、部署OSD

1、登录到node1（osd节点）

查看该节点挂载盘

```
[root@node1 ~]# lsblk
NAME            MAJ:MIN RM  SIZE RO TYPE MOUNTPOINT
sda               8:0    0  100G  0 disk
├─sda1            8:1    0    1G  0 part /boot
└─sda2            8:2    0   99G  0 part
  ├─centos-root 253:0    0   50G  0 lvm  /
  ├─centos-swap 253:1    0  3.9G  0 lvm  [SWAP]
  └─centos-home 253:2    0 45.1G  0 lvm  /home
sdb               8:16   0   20G  0 disk
sr0              11:0    1 1024M  0 rom

```

```
sudo ceph-volume lvm create --data /dev/sdb
```

> 还原/dev/sdb

```
[root@node1 ceph]# lsblk
NAME                                                                                                  MAJ:MIN RM  SIZE RO TYPE MOUNTPOINT
sda                                                                                                     8:0    0  100G  0 disk
├─sda1                                                                                                  8:1    0    1G  0 part /boot
└─sda2                                                                                                  8:2    0   99G  0 part
  ├─centos-root                                                                                       253:0    0   50G  0 lvm  /
  ├─centos-swap                                                                                       253:1    0  3.9G  0 lvm  [SWAP]
  └─centos-home                                                                                       253:2    0 45.1G  0 lvm  /home
sdb                                                                                                     8:16   0   20G  0 disk
└─ceph--3c3010fb--fe32--4f34--8f74--ad86829a772d-osd--block--2f84bb9a--67e9--4f36--badb--22e8eea08901 253:3    0   20G  0 lvm
sr0  
```

```

[root@node1 ~]# lsof /var/lib/ceph/osd/ceph-0
COMMAND    PID USER   FD   TYPE DEVICE SIZE/OFF  NODE NAME
ceph-osd 44221 ceph   29r   DIR   0,38      320 82345 /var/lib/ceph/osd/ceph-0
ceph-osd 44221 ceph   30uW  REG   0,38       37 81456 /var/lib/ceph/osd/ceph-0/fsid

```

```
kill 44221
```

```
ceph-volume lvm zap /dev/sdb --destroy
```

```
[root@node1 ~]# lsblk
NAME            MAJ:MIN RM  SIZE RO TYPE MOUNTPOINT
sda               8:0    0  100G  0 disk
├─sda1            8:1    0    1G  0 part /boot
└─sda2            8:2    0   99G  0 part
  ├─centos-root 253:0    0   50G  0 lvm  /
  ├─centos-swap 253:1    0  3.9G  0 lvm  [SWAP]
  └─centos-home 253:2    0 45.1G  0 lvm  /home
sdb               8:16   0   20G  0 disk
sr0              11:0    1 1024M  0 rom

```

260  rm -rf /etc/ceph/*

261  rm -rf /var/lib/ceph/*

262  systemctl reset-failed

263  rm -rf /var/log/ceph

```
ll /var/lib/ceph/osd/ceph-*
```

2、登录到node2（osd节点）

sudo ceph-volume lvm create --data /dev/sdb

3、登录到node3（osd节点）

sudo ceph-volume lvm create --data /dev/sdb

至此简单集群算是完成了

# 四、测试RBD

ceph mgr module ls

ceph mgr module enable dashboard --force

ceph config set mgr mgr/dashboard/ssl false

ceph osd pool create testpool 64

ceph osd lspools

rbd create --size 1G disk01 --pool testpool

```
[root@monitor ~]# lsblk
NAME            MAJ:MIN RM  SIZE RO TYPE MOUNTPOINT
sda               8:0    0  100G  0 disk 
├─sda1            8:1    0    1G  0 part /boot
└─sda2            8:2    0   99G  0 part 
  ├─centos-root 253:0    0   50G  0 lvm  /
  ├─centos-swap 253:1    0  3.9G  0 lvm  [SWAP]
  └─centos-home 253:2    0 45.1G  0 lvm  /home
sdb               8:16   0   20G  0 disk
```

rbd info --pool testpool disk01

```
[root@monitor ~]# rbd info --pool testpool disk01
rbd image 'disk01':
	size 1 GiB in 256 objects
	order 22 (4 MiB objects)
	snapshot_count: 0
	id: 857e4597e6bd
	block_name_prefix: rbd_data.857e4597e6bd
	format: 2
	features: layering, exclusive-lock, object-map, fast-diff, deep-flatten
	op_features: 
	flags: 
	create_timestamp: Sat Mar 18 08:16:07 2023
	access_timestamp: Sat Mar 18 08:16:07 2023
	modify_timestamp: Sat Mar 18 08:16:07 2023
```

rbd --pool testpool feature disable disk01 exclusive-lock, object-map, fast-diff, deep-flatten

rbd map --pool testpool disk01

mkfs.ext4 /dev/rbd0

mount   /dev/rbd0   /mnt

ceph osd pool application  enable   testpool   rbd

rbd create --size 1G disk02 --pool testpool

rbd feature disable testpool/disk02 object-map fast-diff deep-flatten

rbd map --pool testpool disk02

dd if=/dev/zero of=/mnt/test  bs=1M count=10

lsblk

df  -hT

ceph -s 报警告 3 monitors have not enabled msgr2 执行如下命令开启

```
sudo ceph mon enable-msgr2
```