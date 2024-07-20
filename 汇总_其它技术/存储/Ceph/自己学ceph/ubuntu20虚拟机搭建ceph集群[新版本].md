curl --silent --remote-name --location [https://github.com/ceph/ceph/raw/quincy/src/cephadm/cephadm](https://github.com/ceph/ceph/raw/quincy/src/cephadm/cephadm)

chmod +x cephadm

./cephadm add-repo --release quincy

wget -q -O- '[https://download.ceph.com/keys/release.asc](https://download.ceph.com/keys/release.asc)' | sudo apt-key add -

lsb_release -sc

```
root@monitor:~# lsb_release -sc
focal
```

sudo apt-add-repository 'deb [https://download.ceph.com/debian-quincy/](https://download.ceph.com/debian-quincy/) focal main'

```
root@monitor:~# apt info ceph
Package: ceph
Version: 17.2.5-1focal
Priority: optional
Section: admin apt info ceph
Maintainer: Ceph Maintainers <ceph-maintainers@lists.ceph.com>
Installed-Size: 9,216 B
Depends: ceph-mgr (= 17.2.5-1focal), ceph-mon (= 17.2.5-1focal), ceph-osd (= 17.2.5-1focal)
Recommends: ceph-mds (= 17.2.5-1focal)
Homepage: http://ceph.com/
Download-Size: 3,876 B
APT-Sources: https://download.ceph.com/debian-quincy focal/main amd64 Packages
Description: distributed storage and file system
 Ceph is a massively scalable, open-source, distributed
 storage system that runs on commodity hardware and delivers object,
 block and file system storage.

N: There are 4 additional records. Please use the '-a' switch to see them.
```

这时候就是最新版了

安装

```
sudo apt-get update && sudo apt-get install ceph ceph-mds
```

root@monitor:~# ceph -v

ceph version 17.2.5 (98318ae89f1a893a6ded3a640405cdbb33e08757) quincy (stable)

# 启动monitor

sudo vim /etc/ceph/ceph.conf

```
[global]
fsid = a7f64266-0894-4f1e-a635-d0aeaca0e993
mon initial members = monitor
mon host = 192.168.1.147
auth cluster required = cephx
auth service required = cephx
auth client required = cephx
osd journal size = 1024
osd pool default size = 3
osd pool default min size = 2
osd pool default pg num = 333
osd pool default pgp num = 333
osd crush chooseleaf type = 1
mon_allow_pool_delete = true

```

```
sudo ceph-authtool --create-keyring /tmp/ceph.mon.keyring --gen-key -n mon. --cap mon 'allow *'
```

```
sudo ceph-authtool --create-keyring /etc/ceph/ceph.client.admin.keyring --gen-key -n client.admin --cap mon 'allow *' --cap osd 'allow *' --cap mds 'allow *' --cap mgr 'allow *'
```

```
sudo ceph-authtool --create-keyring /var/lib/ceph/bootstrap-osd/ceph.keyring --gen-key -n client.bootstrap-osd --cap mon 'profile bootstrap-osd' --cap mgr 'allow r'
```

```
sudo ceph-authtool /tmp/ceph.mon.keyring --import-keyring /etc/ceph/ceph.client.admin.keyring
```

```
sudo ceph-authtool /tmp/ceph.mon.keyring --import-keyring /var/lib/ceph/bootstrap-osd/ceph.keyring
```

```
sudo chown ceph:ceph /tmp/ceph.mon.keyring
```

```
monmaptool --create --add monitor 192.168.1.147 --fsid a7f64266-0894-4f1e-a635-d0aeaca0e993 /tmp/monmap
```

```
sudo mkdir -p /var/lib/ceph/mon/ceph-monitor
```

```
sudo -u ceph ceph-mon --mkfs -i monitor --monmap /tmp/monmap --keyring /tmp/ceph.mon.keyring
```

```
sudo chown ceph:ceph -R /var/lib/ceph
```

```
sudo systemctl start ceph-mon@monitor
```

```
root@monitor:~# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_OK

  services:
    mon: 1 daemons, quorum monitor (age 3s)
    mgr: no daemons active
    osd: 0 osds: 0 up, 0 in

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0 B
    usage:   0 B used, 0 B / 0 B avail
    pgs:

```

# 启动MGR

sudo -u ceph mkdir /var/lib/ceph/mgr/ceph-bob

```
ceph auth get-or-create mgr.bob mon 'allow profile mgr' osd 'allow *' mds 'allow *' -o /var/lib/ceph/mgr/ceph-bob/keyring
```

```
ceph-mgr -i bob
```

ceph -s

```
root@monitor:~# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_WARN
            mon is allowing insecure global_id reclaim
            1 monitors have not enabled msgr2

  services:
    mon: 1 daemons, quorum monitor (age 5m)
    mgr: bob(active, since 17s)
    osd: 0 osds: 0 up, 0 in

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0 B
    usage:   0 B used, 0 B / 0 B avail
    pgs:
```

解决上面两个警告

sudo ceph mon enable-msgr2

ceph config set mon auth_allow_insecure_global_id_reclaim false

```
root@monitor:~# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_WARN
            OSD count 0 < osd_pool_default_size 3

  services:
    mon: 1 daemons, quorum monitor (age 7s)
    mgr: bob(active, since 102s)
    osd: 0 osds: 0 up, 0 in

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0 B
    usage:   0 B used, 0 B / 0 B avail
    pgs:

```

```
ceph mgr module enable dashboard
```

```
ceph mgr module ls
```

```
ceph mgr services
```

发现显示为空，服务并没有启动

看了下mgr的日志less ceph-mgr.bob.log

```
Config not ready to serve, waiting: no certificate configured
```

简单解决 如下，也可以生成证书，参考[https://docs.ceph.com/en/latest/mgr/dashboard/](https://docs.ceph.com/en/latest/mgr/dashboard/)

```
ceph config set mgr mgr/dashboard/ssl false
```

再试试，就OK了  

```
root@monitor:/var/log/ceph# ceph mgr services
{
    "dashboard": "http://192.168.1.147:8080/"
}

```

添加用户和密码

echo "adminadmin" > admin.txt

ceph dashboard ac-user-create admin -i admin.txt administrator

```
root@monitor:/var/log/ceph# ceph dashboard ac-user-create admin -i admin.txt administrator
{"username": "admin", "password": "$2b$12$pkIxXwllbhFONCJBuV.yEukbW9I2YpR1PpwSH1lZCuq0wkPH8pz3S", "roles": ["administrator"], "name": null, "email": null, "lastUpdate": 1679324360, "enabled": true, "pwdExpirationDate": null, "pwdUpdateRequired": false}
```

打开http://192.168.1.147:8080/就可以用 admin  和adminadmin 登录了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358346.jpg)

sudo systemctl stop ufw

sudo systemctl disable ufw

# 添加OSD

停止虚拟机，添加3块25G硬盘，开机

```
root@monitor:~# lsblk
NAME   MAJ:MIN RM   SIZE RO TYPE MOUNTPOINT

sda      8:0    0   100G  0 disk
|-sda1   8:1    0   512M  0 part /boot/efi
|-sda2   8:2    0     1K  0 part
`-sda5   8:5    0  99.5G  0 part /
sdb      8:16   0    25G  0 disk
sdc      8:32   0    25G  0 disk
sdd      8:48   0    25G  0 disk
sr0     11:0    1  1024M  0 rom

```

> 重启后，可能由于没设置开机服务自启动，手动启动下
> sudo systemctl start 
> sudo systemctl start 


chown ceph:ceph /var/lib/ceph/bootstrap-osd/ceph.keyring

chown -R ceph:ceph /etc/ceph/

sudo -u ceph cp /var/lib/ceph/bootstrap-osd/ceph.keyring /etc/ceph/ceph.client.bootstrap-osd.keyring

```
sudo ceph-volume lvm create --data /dev/sdb
```

```
root@monitor:~# sudo ceph-volume lvm create --data /dev/sdb
Running command: /usr/bin/ceph-authtool --gen-print-key
Running command: /usr/bin/ceph --cluster ceph --name client.bootstrap-osd --keyring /var/lib/ceph/bootstrap-osd/ceph.keyring -i - osd new 4577fe5d-0077-4432-92fc-b4bace88895e
Running command: vgcreate --force --yes ceph-fc5fe4ae-c8bc-48b1-b0cd-2022e1c7a806 /dev/sdb
 stdout: Physical volume "/dev/sdb" successfully created.
 stdout: Volume group "ceph-fc5fe4ae-c8bc-48b1-b0cd-2022e1c7a806" successfully created
Running command: lvcreate --yes -l 6399 -n osd-block-4577fe5d-0077-4432-92fc-b4bace88895e ceph-fc5fe4ae-c8bc-48b1-b0cd-2022e1c7a806
 stdout: Logical volume "osd-block-4577fe5d-0077-4432-92fc-b4bace88895e" created.
Running command: /usr/bin/ceph-authtool --gen-print-key
Running command: /usr/bin/mount -t tmpfs tmpfs /var/lib/ceph/osd/ceph-0
--> Executable selinuxenabled not in PATH: /usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin
Running command: /usr/bin/chown -h ceph:ceph /dev/ceph-fc5fe4ae-c8bc-48b1-b0cd-2022e1c7a806/osd-block-4577fe5d-0077-4432-92fc-b4bace88895e
Running command: /usr/bin/chown -R ceph:ceph /dev/dm-0
Running command: /usr/bin/ln -s /dev/ceph-fc5fe4ae-c8bc-48b1-b0cd-2022e1c7a806/osd-block-4577fe5d-0077-4432-92fc-b4bace88895e /var/lib/ceph/osd/ceph-0/block
Running command: /usr/bin/ceph --cluster ceph --name client.bootstrap-osd --keyring /var/lib/ceph/bootstrap-osd/ceph.keyring mon getmap -o /var/lib/ceph/osd/ceph-0/activate.monmap
 stderr: got monmap epoch 2
--> Creating keyring file for osd.0
Running command: /usr/bin/chown -R ceph:ceph /var/lib/ceph/osd/ceph-0/keyring
Running command: /usr/bin/chown -R ceph:ceph /var/lib/ceph/osd/ceph-0/
Running command: /usr/bin/ceph-osd --cluster ceph --osd-objectstore bluestore --mkfs -i 0 --monmap /var/lib/ceph/osd/ceph-0/activate.monmap --keyfile - --osd-data /var/lib/ceph/osd/ceph-0/ --osd-uuid 4577fe5d-0077-4432-92fc-b4bace88895e --setuser ceph --setgroup ceph
 stderr: 2023-03-20T23:49:47.758+0800 7f23daf91240 -1 bluestore(/var/lib/ceph/osd/ceph-0/) _read_fsid unparsable uuid
--> ceph-volume lvm prepare successful for: /dev/sdb
Running command: /usr/bin/chown -R ceph:ceph /var/lib/ceph/osd/ceph-0
Running command: /usr/bin/ceph-bluestore-tool --cluster=ceph prime-osd-dir --dev /dev/ceph-fc5fe4ae-c8bc-48b1-b0cd-2022e1c7a806/osd-block-4577fe5d-0077-4432-92fc-b4bace88895e --path /var/lib/ceph/osd/ceph-0 --no-mon-config
Running command: /usr/bin/ln -snf /dev/ceph-fc5fe4ae-c8bc-48b1-b0cd-2022e1c7a806/osd-block-4577fe5d-0077-4432-92fc-b4bace88895e /var/lib/ceph/osd/ceph-0/block
Running command: /usr/bin/chown -h ceph:ceph /var/lib/ceph/osd/ceph-0/block
Running command: /usr/bin/chown -R ceph:ceph /dev/dm-0
Running command: /usr/bin/chown -R ceph:ceph /var/lib/ceph/osd/ceph-0
Running command: /usr/bin/systemctl enable ceph-volume@lvm-0-4577fe5d-0077-4432-92fc-b4bace88895e
 stderr: Created symlink /etc/systemd/system/multi-user.target.wants/ceph-volume@lvm-0-4577fe5d-0077-4432-92fc-b4bace88895e.service -> /lib/systemd/system/ceph-volume@.service.
Running command: /usr/bin/systemctl enable --runtime ceph-osd@0
 stderr: Created symlink /run/systemd/system/ceph-osd.target.wants/ceph-osd@0.service -> /lib/systemd/system/ceph-osd@.service.
Running command: /usr/bin/systemctl start ceph-osd@0
--> ceph-volume lvm activate successful for osd ID: 0
--> ceph-volume lvm create successful for: /dev/sdb

```

sudo ceph-volume lvm create --data /dev/sdc

sudo ceph-volume lvm create --data /dev/sdd

ceph -s

```
root@monitor:~# ceph -s
  cluster:
    id:     a7f64266-0894-4f1e-a635-d0aeaca0e993
    health: HEALTH_OK

  services:
    mon: 1 daemons, quorum monitor (age 2m)
    mgr: bob(active, since 2m)
    osd: 3 osds: 2 up (since 4s), 3 in (since 9s)

  data:
    pools:   0 pools, 0 pgs
    objects: 0 objects, 0 B
    usage:   439 MiB used, 50 GiB / 50 GiB avail
    pgs:
```

systemctl status [ceph-osd@0](http://ceph-osd@0)

systemctl status [ceph-osd@1](http://ceph-osd@1)

.....

# 添加MDS

```

mkdir -p /var/lib/ceph/mds/ceph-mds1
ceph-authtool --create-keyring /var/lib/ceph/mds/ceph-mds1/keyring --gen-key -n mds.mds1
ceph auth add mds.mds1 osd "allow rwx" mds "allow *" mon "allow profile mds" -i /var/lib/ceph/mds/ceph-mds1/keyring
vim /etc/ceph/ceph.conf
```

```
[global]
fsid = a7f64266-0894-4f1e-a635-d0aeaca0e993
mon initial members = monitor
mon host = 192.168.1.147
auth cluster required = cephx
auth service required = cephx
auth client required = cephx
osd journal size = 1024
osd pool default size = 3
osd pool default min size = 2
osd pool default pg num = 333
osd pool default pgp num = 333
osd crush chooseleaf type = 1
mon_allow_pool_delete = true
[mds.mds1]
host = mds1

```

ceph-mds -i mds1 -m 192.168.1.147:6677 --no-mon-config

上面不行，还是按下面的

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

ceph-mds -i monitor-mds

systemctl start [ceph-mds@monitor-mds](http://ceph-mds@monitor-mds)

RGW：

root@monitor:~# radosgw -d --keyring /etc/ceph/ceph.client.admin.keyring

2023-03-20T23:53:38.035+0800 7fd8b5397cc0  0 ceph version 17.2.5 (98318ae89f1a893a6ded3a640405cdbb33e08757) quincy (stable), process radosgw, pid 8004

2023-03-20T23:53:38.035+0800 7fd8b5397cc0  0 framework: beast

2023-03-20T23:53:38.035+0800 7fd8b5397cc0  0 framework conf key: port, val: 7480

2023-03-20T23:53:38.035+0800 7fd8b5397cc0  1 radosgw_Main not setting numa affinity

2023-03-20T23:53:38.043+0800 7fd8b5397cc0  1 rgw_d3n: rgw_d3n_l1_local_datacache_enabled=0

2023-03-20T23:53:38.043+0800 7fd8b5397cc0  1 D3N datacache enabled: 0

2023-03-20T23:53:39.047+0800 7fd8b5397cc0  0 rgw main: rgw_init_ioctx ERROR: librados::Rados::pool_create returned (34) Numerical result out of range (this can be due to a pool or placement group misconfiguration, e.g. pg_num < pgp_num or mon_max_pg_per_osd exceeded)

2023-03-20T23:53:39.047+0800 7fd8b5397cc0  0 rgw main: failed reading realm info: ret -34 (34) Numerical result out of range

2023-03-20T23:53:39.047+0800 7fd8b5397cc0  0 rgw main: ERROR: failed to start notify service ((34) Numerical result out of range

2023-03-20T23:53:39.047+0800 7fd8b5397cc0  0 rgw main: ERROR: failed to init services (ret=(34) Numerical result out of range)

2023-03-20T23:53:39.051+0800 7fd8b5397cc0 -1 Couldn't init storage provider (RADOS)

改了ceph.conf

[global]

fsid = a7f64266-0894-4f1e-a635-d0aeaca0e993

mon initial members = monitor

mon host = 192.168.1.147

auth cluster required = cephx

auth service required = cephx

auth client required = cephx

osd journal size = 1024

osd pool default size = 1

osd pool default min size = 1

mon_allow_pool_delete = true

root@monitor:~# ceph df

--- RAW STORAGE ---

CLASS    SIZE   AVAIL    USED  RAW USED  %RAW USED

hdd    75 GiB  75 GiB  26 MiB    26 MiB       0.03

TOTAL  75 GiB  75 GiB  26 MiB    26 MiB       0.03

--- POOLS ---

POOL                       ID  PGS   STORED  OBJECTS     USED  %USED  MAX AVAIL

.mgr                        1    1      0 B        0      0 B      0     24 GiB

.rgw.root                   2   32  1.3 KiB        4   16 KiB      0     71 GiB

default.rgw.log             3   32  3.6 KiB      205  136 KiB      0     71 GiB

default.rgw.control         4   32      0 B        7      0 B      0     71 GiB

default.rgw.meta            5   32    921 B        5   16 KiB      0     71 GiB

default.rgw.buckets.index   6    1      0 B       11      0 B      0     71 GiB

default.rgw.buckets.data    7    1  2.1 MiB        1  2.1 MiB      0     71 GiB