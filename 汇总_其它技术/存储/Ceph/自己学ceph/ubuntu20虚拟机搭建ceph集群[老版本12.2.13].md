尴尬，后面才发现安装的是ceph version 12.2.13版本的。太老了,因为下面的用的阿里云源比较老。得重新搭建

切换到root

sudo apt install openssh-server -y

sudo systemctl enable ssh

sudo systemctl start ssh

snap install curl

sudo systemctl stop ufw

sudo systemctl disable ufw

sudo apt install lvm2 -y  (这个是后来执行sudo ceph-volume lvm create --data /dev/sdb 发现报错)

sudo apt install net-tools -y

sudo cp /etc/apt/sources.list /etc/apt/sources.list.bak

sudo vim /etc/apt/sources.list

```
这是导致问题的根源，记录下来，这个链接用的是老版本的，所以安装ceph过程中有的包不符合要求，但是却能安装成功，这是最致命的
deb http://mirrors.aliyun.com/ubuntu/ bionic main restricted universe multiverse
deb http://mirrors.aliyun.com/ubuntu/ bionic-updates main restricted universe multiverse
deb http://mirrors.aliyun.com/ubuntu/ bionic-backports main restricted universe multiverse
deb http://mirrors.aliyun.com/ubuntu/ bionic-security main restricted universe multiverse
deb http://mirrors.aliyun.com/ubuntu/ bionic-proposed main restricted universe multiverse

```

sudo apt update

```
sudo apt-get install ceph ceph-mds -y
```

sudo vim /etc/hosts

```
192.168.1.146 monitor
192.168.1.147 node1
```

sudo vim /etc/ceph/ceph.conf

```
[global]
fsid = a7f64266-0894-4f1e-a635-d0aeaca0e993
mon initial members = monitor
mon host = 192.168.1.146
auth cluster required = cephx
auth service required = cephx
auth client required = cephx
osd journal size = 1024
osd pool default size = 1
osd pool default min size = 1
osd pool default pg num = 128
osd pool default pgp num = 128
osd crush chooseleaf type = 1
mon_allow_pool_delete = true
```

1. Create a keyring for your cluster and generate a monitor secret key.

```
sudo ceph-authtool --create-keyring /tmp/ceph.mon.keyring --gen-key -n mon. --cap mon 'allow *'
```

1. Generate an administrator keyring, generate a

 client.admin user and add the user to the keyring.

```
sudo ceph-authtool --create-keyring /etc/ceph/ceph.client.admin.keyring --gen-key -n client.admin --cap mon 'allow *' --cap osd 'allow *' --cap mds 'allow *' --cap mgr 'allow *'
```

1. Generate a bootstrap-osd keyring, generate a

 client.bootstrap-osd user and add the user to the keyring.

```
sudo ceph-authtool --create-keyring /var/lib/ceph/bootstrap-osd/ceph.keyring --gen-key -n client.bootstrap-osd --cap mon 'profile bootstrap-osd' --cap mgr 'allow r'

cp /var/lib/ceph/bootstrap-osd/ceph.keyring /etc/ceph/ceph.client.bootstrap-osd.keyring
```

1. Add the generated keys to theceph.mon.keyring.

```
sudo ceph-authtool /tmp/ceph.mon.keyring --import-keyring /etc/ceph/ceph.client.admin.keyring
sudo ceph-authtool /tmp/ceph.mon.keyring --import-keyring /var/lib/ceph/bootstrap-osd/ceph.keyring
```

1. Change the owner for ceph.mon.keyring.

```
sudo chown ceph:ceph /tmp/ceph.mon.keyring
```

```
monmaptool --create --add monitor 192.168.1.146 --fsid a7f64266-0894-4f1e-a635-d0aeaca0e993 /tmp/monmap
```

sudo mkdir /var/lib/ceph/mon/ceph-monitor

sudo chown ceph:ceph /var/lib/ceph/mon/ceph-monitor

```
sudo -u ceph ceph-mon --mkfs -i monitor --monmap /tmp/monmap --keyring /tmp/ceph.mon.keyring
```

```
sudo systemctl enable ceph-mon@monitor
sudo systemctl start ceph-mon@monitor
```

```
ceph -s
```

```
root@monitor:/home/vboxuser# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_OK

  services:
    mon: 1 daemons, quorum monitor
    mgr: no daemons active
    osd: 0 osds: 0 up, 0 in

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0B
    usage:   0B used, 0B / 0B avail
    pgs:

```

ceph config set mon auth_allow_insecure_global_id_reclaim false

sudo ceph mon enable-msgr2

# mgr

sudo -u ceph mkdir /var/lib/ceph/mgr/ceph-bob

```
ceph auth get-or-create mgr.bob mon 'allow profile mgr' osd 'allow *' mds 'allow *' -o /var/lib/ceph/mgr/ceph-bob/keyring
```

启动ceph-mgr daemon

```
ceph-mgr -i bob
```

> systemctl enable 
> systemctl restart ceph-mgr@bob


Created symlink /etc/systemd/system/ceph-mgr.target.wants/ceph-mgr@bob.service → /usr/lib/systemd/system/ceph-mgr@.service.

ceph status

```

root@monitor:/etc/ceph# ceph status
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_OK

  services:
    mon: 1 daemons, quorum monitor (age 5m)
    mgr: bob(active, since 20s)
    osd: 0 osds: 0 up, 0 in

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0 B
    usage:   0 B used, 0 B / 0 B avail
    pgs:

```

开启dashboard

```
ceph mgr module ls
```

```
ceph mgr module enable dashboard
```

```
ceph mgr services
```

```
root@monitor:/home/vboxuser# ceph mgr services
{
    "dashboard": "http://monitor.monitor:7000/"
}
```

curl [http://monitor.monitor:7000](http://monitor.monitor:7000)

或者浏览器访问 http:/192.168.1.146:7000

添加OSD

先看下没添加之前的情况

```
root@monitor:~# lsblk
NAME   MAJ:MIN RM   SIZE RO TYPE MOUNTPOINT
loop0    7:0    0     4K  1 loop /snap/bare/5
loop1    7:1    0    62M  1 loop /snap/core20/1611
loop2    7:2    0   6.4M  1 loop /snap/curl/1435
loop3    7:3    0 346.3M  1 loop /snap/gnome-3-38-2004/115
loop4    7:4    0  91.7M  1 loop /snap/gtk-common-themes/1535
loop5    7:5    0  54.2M  1 loop /snap/snap-store/558
loop7    7:7    0  49.9M  1 loop /snap/snapd/18357
loop8    7:8    0  63.3M  1 loop /snap/core20/1828
loop9    7:9    0    46M  1 loop /snap/snap-store/638
loop10   7:10   0 346.3M  1 loop /snap/gnome-3-38-2004/119
sda      8:0    0   100G  0 disk
|-sda1   8:1    0   512M  0 part /boot/efi
|-sda2   8:2    0     1K  0 part
`-sda5   8:5    0  99.5G  0 part /
sr0     11:0    1  1024M  0 rom

```

关闭虚拟机添加3块硬盘，再执行lsblk看看

```
vboxuser@monitor:~$ lsblk
NAME   MAJ:MIN RM   SIZE RO TYPE MOUNTPOINT
loop0    7:0    0     4K  1 loop /snap/bare/5
loop1    7:1    0    62M  1 loop /snap/core20/1611
loop2    7:2    0  63.3M  1 loop /snap/core20/1828
loop3    7:3    0   6.4M  1 loop /snap/curl/1435
loop4    7:4    0  54.2M  1 loop /snap/snap-store/558
loop5    7:5    0 346.3M  1 loop /snap/gnome-3-38-2004/115
loop6    7:6    0 346.3M  1 loop /snap/gnome-3-38-2004/119
loop7    7:7    0  91.7M  1 loop /snap/gtk-common-themes/1535
loop8    7:8    0    46M  1 loop /snap/snap-store/638
loop9    7:9    0  49.9M  1 loop /snap/snapd/18357
sda      8:0    0   100G  0 disk
├─sda1   8:1    0   512M  0 part /boot/efi
├─sda2   8:2    0     1K  0 part
└─sda5   8:5    0  99.5G  0 part /
sdb      8:16   0    25G  0 disk
sdc      8:32   0    25G  0 disk
sdd      8:48   0    25G  0 disk
sr0     11:0    1  1024M  0 rom

```

sudo ceph-volume lvm create --data /dev/sdb

sudo ceph-volume lvm create --data /dev/sdc

sudo ceph-volume lvm create --data /dev/sdd

ceph -s 

```
root@monitor:~# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_OK

  services:
    mon: 1 daemons, quorum monitor
    mgr: bob(active)
    osd: 3 osds: 3 up, 3 in

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0B
    usage:   3.01GiB used, 72.0GiB / 75.0GiB avail
    pgs:

```

ceph osd tree

```
root@monitor:~# ceph osd tree
ID CLASS WEIGHT  TYPE NAME        STATUS REWEIGHT PRI-AFF
-1       0.07320 root default
-3       0.07320     host monitor
 0   hdd 0.02440         osd.0        up  1.00000 1.00000
 1   hdd 0.02440         osd.1        up  1.00000 1.00000
 2   hdd 0.02440         osd.2        up  1.00000 1.00000
```

# 新增OSD节点

scp /etc/ceph/ceph.conf root@node1:/etc/ceph/

scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node1:/var/lib/ceph/bootstrap-osd/ceph.keyring

scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node1:/etc/ceph/

sudo ceph-volume lvm create --data /dev/sdb

sudo ceph-volume lvm create --data /dev/sdc

sudo ceph-volume lvm create --data /dev/sdd

在monitor节点执行ceph -s

```
root@monitor:~# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_OK

  services:
    mon: 1 daemons, quorum monitor
    mgr: bob(active)
    osd: 6 osds: 6 up, 6 in

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0B
    usage:   6.06GiB used, 144GiB / 150GiB avail
    pgs:

```

# 测试RBD

ceph osd pool create testpool 128 128

```
Ceph集群中的PG总数：
PG总数 = (OSD总数 * 100) / 最大副本数
** 结果必须舍入到最接近的2的N次方幂的值。
Ceph集群中每个pool中的PG总数：
存储池PG总数 = (OSD总数 * 100 / 最大副本数) / 池数
```

```
root@monitor:~# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_OK

  services:
    mon: 1 daemons, quorum monitor
    mgr: bob(active)
    osd: 3 osds: 3 up, 3 in

  data:
    pools:   1 pools, 128 pgs
    objects: 0 objects, 0B
    usage:   3.01GiB used, 72.0GiB / 75.0GiB avail
    pgs:     128 active+clean
```

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

ceph osd pool application  enable   testpool   rbd

mkfs.ext4 /dev/rbd0

mount   /dev/rbd0   /mnt

ceph df

dd if=/dev/zero of=/mnt/test  bs=1M count=10

ceph df

rbd create --size 1G disk02 --pool testpool

rbd feature disable testpool/disk02 object-map fast-diff deep-flatten

rbd map --pool testpool disk02

lsblk

df  -hT

# 配置文件存储

[https://docs.ceph.com/en/quincy/cephfs/add-remove-mds/](https://docs.ceph.com/en/quincy/cephfs/add-remove-mds/)

```
ceph osd pool create cephfs_data 256 256
ceph osd pool create cephfs_metadata 256 256
ceph fs new cephfs cephfs_metadata cephfs_data
ceph fs ls
ceph -s
ceph mgr module ls
ceph mgr module enable mds --force
ceph mgr module ls

mkdir /var/lib/ceph/mds/ceph-monitor-mds
ceph auth get-or-create mds.monitor-mds mon 'profile mds' mgr 'profile mds' mds 'allow *' osd 'allow *' > /var/lib/ceph/mds/ceph-monitor-mds/keyring
```

systemctl start [ceph-mds@monitor-mds](http://ceph-mds@monitor-mds)

```
root@monitor:~# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_OK

  services:
    mon: 1 daemons, quorum monitor
    mgr: bob(active)
    mds: cephfs-1/1/1 up  {0=monitor-mds=up:active}
    osd: 3 osds: 3 up, 3 in

  data:
    pools:   2 pools, 512 pgs
    objects: 21 objects, 2.19KiB
    usage:   3.01GiB used, 72.0GiB / 75.0GiB avail
    pgs:     512 active+clean

```

这里登录到另外一台服务器node1

vim /etc/ceph/ceph.conf   这个从monitor节点复制下面3行过来即可

```
[global]
fsid = a7f64266-0894-4f1e-a635-d0aeaca0e993
mon host = 192.168.1.146

```

chmod 644 /etc/ceph/ceph.conf

在monitor节点执行 ceph fs authorize cephfs client.ttuser / rw

把上一行命令输出结果复制到node1的 /etc/ceph/ceph.client.ttuser.keyring文件中

vim /etc/ceph/ceph.client.ttuser.keyring

```
[client.ttuser]
        key = AQCq2RZkDaHREBAAZg57WRad2Od1E+sUQQ+gjA==
```

chmod 600 /etc/ceph/ceph.client.ttuser.keyring

mkdir /mnt/mycephfs

apt install ceph-fuse -y

挂载

ceph-fuse -n client.ttuser /mnt/mycephfs

```

root@node1:/etc/ceph# ceph-fuse -n client.ttuser /mnt/mycephfs
ceph-fuse[1751]: starting ceph client
2023-03-19 19:14:05.998452 7f4047fb9200 -1 init, newargv = 0x556d782d2400 newargc=9
ceph-fuse[1751]: starting fuse

```

df -h

```
root@node1:/etc/ceph# df -h
Filesystem      Size  Used Avail Use% Mounted on
.........
ceph-fuse        69G     0   69G   0% /mnt/mycephfs

```

测试

先在monitor上看下容量

```
root@monitor:~# ceph df
GLOBAL:
    SIZE        AVAIL       RAW USED     %RAW USED
    75.0GiB     72.0GiB      3.01GiB          4.01
POOLS:
    NAME                ID     USED        %USED     MAX AVAIL     OBJECTS
    cephfs_data         2           0B         0       68.2GiB           0
    cephfs_metadata     3      2.56KiB         0       68.2GiB          21

```

在node1上 执行  echo  11111 > 111.txt

再到monitor上看下容量

```
root@monitor:~# ceph df
GLOBAL:
    SIZE        AVAIL       RAW USED     %RAW USED
    75.0GiB     72.0GiB      3.01GiB          4.01
POOLS:
    NAME                ID     USED        %USED     MAX AVAIL     OBJECTS
    cephfs_data         2           6B         0       68.2GiB           1
    cephfs_metadata     3      4.16KiB         0       68.2GiB          21

```

容量有变化OK的

# 配置对象存储

[https://docs.ceph.com/en/latest/radosgw/admin/](https://docs.ceph.com/en/latest/radosgw/admin/)

[https://docs.ceph.com/en/latest/man/8/radosgw-admin/#examples](https://docs.ceph.com/en/latest/man/8/radosgw-admin/#examples)

1、预备知识

realm：一个realm包含1个或多个zonegroup。如果realm包含多个zonegroup，必须指定一个zonegroup为master

zonegroup， 用来处理系统操作。一个系统中可以包含多个realm，多个realm之间资源完全隔离。例如cephpmsc。

zonegroup：一个zonegroup如果包含1个或多个zone。如果一个zonegroup包含多个zone，必须指定一个zone作为master

zone，用来处理bucket和用户的创建。一个集群可以创建多个zonegroup，一个zonegroup也可以跨多个集群。例如huafengfloor7。

zone：包含多个RGW实例的一个逻辑概念。zone不能跨集群，同一个zone的数据保存在同一组pool中。

user：对象存储的使用者，默认情况下，一个用户只能创建1000个存储桶。 bucket：存储桶，用来管理对象的容器。

object：对象，泛指一个文档、图片或视频文件等，尽管用户可以直接上传一个目录，但是ceph并不按目录层级结构保存对象，

ceph所有的对象扁平化的保存在bucket中。

————————————————

版权声明：本文为CSDN博主「qq_852388750」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/qq_27979109/article/details/120345676](https://blog.csdn.net/qq_27979109/article/details/120345676)

2、创建用户

下面代码框是折腾的过程，看看就行

```
ceph-authtool --create-keyring /etc/ceph/ceph.client.radosgw.keyring

chmod +r /etc/ceph/ceph.client.radosgw.keyring

ceph-authtool /etc/ceph/ceph.client.radosgw.keyring -n client.radosgw.gateway --gen-key

ceph-authtool -n client.radosgw.gateway --cap osd 'allow rwx' --cap mon 'allow rwx' /etc/ceph/ceph.client.radosgw.keyring

apt install radosgw -y

sudo -u ceph mkdir -p /var/lib/ceph/radosgw/ceph-radosgw.monitor

chown -R ceph:ceph /etc/ceph/

sudo -u ceph cp /etc/ceph/ceph.client.admin.keyring /var/lib/ceph/radosgw/ceph-radosgw.monitor/ceph.keyring

radosgw -k /var/lib/ceph/radosgw/ceph-radosgw.monitor/ceph.keyring
```

radosgw -k /etc/ceph/ceph.client.admin.keyring   （如果在别的节点，没有这个文件，可以从mon节点拷贝过去）

》2023-3-22 22:31:55 后来发现这样启动不能开机自启动，在《公司电脑Ubuntu集群》里面有步骤

curl 127.0.0.1:7480

```
radosgw-admin user create --display-name="admin user" --uid=admin
```

```
root@monitor:~# radosgw-admin user create --display-name="admin user" --uid=admin
{
    "user_id": "admin",
    "display_name": "admin user",
    "email": "",
    "suspended": 0,
    "max_buckets": 1000,
    "auid": 0,
    "subusers": [],
    "keys": [
        {
            "user": "admin",
            "access_key": "5W7X83AT84RMT8QOD3TM",
            "secret_key": "OiPjlUicgkC72GEOkFmFshdU5EUXZ05bSI0p1A0C"
        }
    ],
    "swift_keys": [],
    "caps": [],
    "op_mask": "read, write, delete",
    "default_placement": "",
    "placement_tags": [],
    "bucket_quota": {
        "enabled": false,
        "check_on_raw": false,
        "max_size": -1,
        "max_size_kb": 0,
        "max_objects": -1
    },
    "user_quota": {
        "enabled": false,
        "check_on_raw": false,
        "max_size": -1,
        "max_size_kb": 0,
        "max_objects": -1
    },
    "temp_url_keys": [],
    "type": "rgw"
}

```

3、查询用户信息

radosgw-admin user list

radosgw-admin user info --uid admin

4、删除用户

radosgw-admin user rm --uid=admin

5、查看pool

```

root@monitor:~# ceph osd lspools
5 testpool,6 .rgw.root,7 default.rgw.control,8 default.rgw.meta,9 default.rgw.log,

```

除了第一个testpool是我建立的，其他几个是系统自己建立的

sudo ceph-authtool --create-keyring /etc/ceph/ceph.client.radosgw.johnny.keyring --name=client.radosgw.johnny --gen-key

sudo radosgw -c /etc/ceph/ceph.conf -n client.radosgw.johnny -k /etc/ceph/ceph.client.radosgw.johnny.keyring --rgw-zone=default --rgw-zonegroup=default --rgw-realm=default

[http://www.taodudu.cc/news/show-4721669.html](http://www.taodudu.cc/news/show-4721669.html)

[https://www.cnblogs.com/tanghao01/articles/15208512.html](https://www.cnblogs.com/tanghao01/articles/15208512.html)

6、测试

- 安装   

apt install s3cmd -y

- 配置s3cmd

s3cmd --configure	

```
root@monitor:~# s3cmd --configure
/usr/bin/s3cmd:303: SyntaxWarning: "is" with a literal. Did you mean "=="?
  if response["status"] is 200:
/usr/bin/s3cmd:305: SyntaxWarning: "is" with a literal. Did you mean "=="?
  elif response["status"] is 204:

Enter new values or accept defaults in brackets with Enter.
Refer to user manual for detailed description of all options.

Access key and Secret key are your identifiers for Amazon S3. Leave them empty for using the env variables.
Access Key: CC0EP9KG7QTYQ4TYC1UV
Secret Key: 8Gt82PN9QhgMMd4g6Ogd1HoaL1LRn6SZ4S50zDoV
Default Region [US]:

Use "s3.amazonaws.com" for S3 Endpoint and not modify it to the target Amazon S3.
S3 Endpoint [s3.amazonaws.com]: 192.168.1.146:7480

Use "%(bucket)s.s3.amazonaws.com" to the target Amazon S3. "%(bucket)s" and "%(location)s" vars can be used
if the target S3 system supports dns based buckets.
DNS-style bucket+hostname:port template for accessing a bucket [%(bucket)s.s3.amazonaws.com]: 192.168.1.146:7480

Encryption password is used to protect your files from reading
by unauthorized persons while in transfer to S3
Encryption password:
Path to GPG program [/usr/bin/gpg]:

When using secure HTTPS protocol all communication with Amazon S3
servers is protected from 3rd party eavesdropping. This method is
slower than plain HTTP, and can only be proxied with Python 2.7 or newer
Use HTTPS protocol [Yes]: no

On some networks all internet access must go through a HTTP proxy.
Try setting it here if you can't connect to S3 directly
HTTP Proxy server name:

New settings:
  Access Key: CC0EP9KG7QTYQ4TYC1UV
  Secret Key: 8Gt82PN9QhgMMd4g6Ogd1HoaL1LRn6SZ4S50zDoV
  Default Region: US
  S3 Endpoint: 192.168.1.146:7480
  DNS-style bucket+hostname:port template for accessing a bucket: 192.168.1.146:7480
  Encryption password:
  Path to GPG program: /usr/bin/gpg
  Use HTTPS protocol: False
  HTTP Proxy server name:
  HTTP Proxy server port: 0

Test access with supplied credentials? [Y/n] y
Please wait, attempting to list all buckets...
Success. Your access key and secret key worked fine :-)

Now verifying that encryption works...
Not configured. Never mind.

Save settings? [y/N] y
Configuration saved to '/root/.s3cfg'
```

创建桶

s3cmd mb s3://testbucket

```
root@monitor:~# s3cmd mb s3://testbucket
/usr/bin/s3cmd:303: SyntaxWarning: "is" with a literal. Did you mean "=="?
  if response["status"] is 200:
/usr/bin/s3cmd:305: SyntaxWarning: "is" with a literal. Did you mean "=="?
  elif response["status"] is 204:
Bucket 's3://testbucket/' created
```

s3cmd mb s3://testbucket2

s3cmd ls

```

root@monitor:~# s3cmd ls
/usr/bin/s3cmd:303: SyntaxWarning: "is" with a literal. Did you mean "=="?
  if response["status"] is 200:
/usr/bin/s3cmd:305: SyntaxWarning: "is" with a literal. Did you mean "=="?
  elif response["status"] is 204:
2023-03-19 13:27  s3://testbucket
2023-03-19 13:37  s3://testbucket2
```

也可以下载免费的s3brower测试

但是s3cmd和s3brower都发现不能put数据，报错大概就是range invalid