ceph config get osd mon_max_pg_per_osd

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
Section: admin
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
sudo apt-get update //如果update过就不需要了
sudo apt-get install ceph ceph-mds
```

root@monitor:~# ceph -v

ceph version 17.2.5 (98318ae89f1a893a6ded3a640405cdbb33e08757) quincy (stable)

# 启动monitor

sudo vim /etc/ceph/ceph.conf

```
[global]
fsid = a7f64266-0894-4f1e-a635-d0aeaca0e993
mon initial members = node1
mon host = 192.168.10.165
auth cluster required = cephx
auth service required = cephx
auth client required = cephx
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
monmaptool --create --add node1 192.168.10.165 --fsid a7f64266-0894-4f1e-a635-d0aeaca0e993 /tmp/monmap
```

```
sudo -u ceph mkdir /var/lib/ceph/mon/ceph-node1
```

```
sudo -u ceph ceph-mon --mkfs -i node1 --monmap /tmp/monmap --keyring /tmp/ceph.mon.keyring
```

```
sudo systemctl enable ceph-mon@node1
sudo systemctl start ceph-mon@node1
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

systemctl enable [ceph-mgr@bob](http://ceph-mgr@bob)

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

[Ceph Dashboard — Ceph Documentation](https://docs.ceph.com/en/latest/mgr/dashboard/)

```
ceph mgr module enable dashboard
```

```
ceph dashboard create-self-signed-cert
```

```
openssl req -new -nodes -x509 \
-subj "/O=IT/CN=ceph-mgr-dashboard" -days 3650 \
-keyout dashboard.key -out dashboard.crt -extensions v3_ca
```

```
ceph dashboard set-ssl-certificate -i dashboard.crt
ceph dashboard set-ssl-certificate-key -i dashboard.key
```

```
ceph mgr services

root@node1:~# ceph mgr services
{
    "dashboard": "https://192.168.10.165:8443/"
}

```

》如果没生成证书，ceph mgr services发现显示为空，服务并没有启动

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

rm -rf admin.txt

```
root@monitor:/var/log/ceph# ceph dashboard ac-user-create admin -i admin.txt administrator
{"username": "admin", "password": "$2b$12$pkIxXwllbhFONCJBuV.yEukbW9I2YpR1PpwSH1lZCuq0wkPH8pz3S", "roles": ["administrator"], "name": null, "email": null, "lastUpdate": 1679324360, "enabled": true, "pwdExpirationDate": null, "pwdUpdateRequired": false}
```

打开http://192.168.1.147:8080/就可以用 admin  和adminadmin 登录了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359959.jpg)

这里手动部署，后面可以出一版通过dashboard操作

root@node1:~# ceph osd lspools

1 .mgr

查看pool的信息

root@node1:~# ceph osd pool get .mgr all

size: 3

min_size: 2

pg_num: 1

pgp_num: 1

crush_rule: replicated_rule

hashpspool: true

nodelete: false

nopgchange: false

nosizechange: false

write_fadvise_dontneed: false

noscrub: false

nodeep-scrub: false

use_gmt_hitset: 1

fast_read: 0

pg_autoscale_mode: on

pg_num_min: 1

eio: false

bulk: false

pg_num_max: 32

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

```
scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node2:/var/lib/ceph/bootstrap-osd/ceph.keyring
由于node2没有上级目录，这里可以直接执行下面命令
scp -r /var/lib/ceph/bootstrap-osd root@node2:/var/lib/ceph/
```

scp /etc/ceph/ceph.conf root@node2:/etc/ceph/

sudo ceph-volume lvm create --data /dev/sdb

但是会有报错，导致不用cephx

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359367.jpg)

还需要拷贝到/etc/ceph目录下

scp /var/lib/ceph/bootstrap-osd/ceph.keyring root@node2:/etc/ceph/ceph.client.bootstrap-osd.keyring

##### 登录到node2

注意node2也要更新软件到相同版本

wget -q -O- '[https://download.ceph.com/keys/release.asc](https://download.ceph.com/keys/release.asc)' | sudo apt-key add -

lsb_release -sc

```
root@monitor:~# lsb_release -sc
focal
```

sudo apt-add-repository 'deb [https://download.ceph.com/debian-quincy/](https://download.ceph.com/debian-quincy/) focal main'

sudo apt-get install ceph

sudo ceph-volume lvm create --data /dev/sdb

sudo ceph-volume lvm create --data /dev/sdb

sudo ceph-volume lvm create --data /dev/sdd

# 创建文件系统

简单的创建

```
root@node1:~# ceph fs volume create cephfs
Volume created successfully (no MDS daemons created)
```

```
root@node1:~# ceph osd pool ls
.mgr
cephfs.cephfs.meta
cephfs.cephfs.data
```

也可以自己指定pool，如下：

[https://docs.ceph.com/en/quincy/cephfs/add-remove-mds/](https://docs.ceph.com/en/quincy/cephfs/add-remove-mds/)

```
ceph osd pool create cephfs_data 16 16
ceph osd pool create cephfs_metadata 16 16
ceph fs new cephfs cephfs_metadata cephfs_data
ceph fs ls
ceph -s
```

```
ceph mgr module ls
ceph mgr module enable mds --force
ceph mgr module ls

mkdir /var/lib/ceph/mds/ceph-mds1
ceph auth get-or-create mds.mds1 mon 'profile mds' mgr 'profile mds' mds 'allow *' osd 'allow *' > /var/lib/ceph/mds/ceph-mds1/keyring
```

systemctl start ceph-mds@mds1

使用fuse挂载cephfs

apt install ceph-fuse -y

ceph-fuse -n client.admin /mnt/mycephfs

```

root@node1:~# ceph-fuse -n client.admin /mnt/mycephfs
2023-03-22T15:28:17.455+0800 7f47b1cc4200 -1 init, newargv = 0x55c1aa925fc0 newargc=15
ceph-fuse[66675]: starting ceph client
ceph-fuse[66675]: starting fuse
```

ceph osd pool get xxx all

ceph osd pool application get

```
cd /mnt/mycephfs
echo 1111 >  1.txt

```

```
root@node1:/mnt/mycephfs# ceph df
--- RAW STORAGE ---
CLASS     SIZE    AVAIL     USED  RAW USED  %RAW USED
hdd    120 GiB  119 GiB  1.4 GiB   1.4 GiB       1.15
TOTAL  120 GiB  119 GiB  1.4 GiB   1.4 GiB       1.15

--- POOLS ---
POOL                ID  PGS   STORED  OBJECTS     USED  %USED  MAX AVAIL
.mgr                 1    1  577 KiB        2  577 KiB      0     37 GiB
cephfs.cephfs.meta   2   16   11 KiB       22   11 KiB      0     56 GiB
cephfs.cephfs.data   3   32      5 B        1      5 B      0     56 GiB
```

卸载cephfs

```
umount /mnt/mycephfs
```

持久挂载

vim /etc/fstab    这里用admin用户，所以id=admin

```
none    /mnt/mycephfs  fuse.ceph ceph.id=admin,_netdev,defaults  0 0
```

```
systemctl start ceph-fuse@/mnt/mycephfs.service
systemctl enable ceph-fuse.target
systemctl enable ceph-fuse@-mnt-mycephfs.service
```

# 开启	Object Gateway

[https://docs.ceph.com/en/latest/man/8/radosgw/#cmdoption-radosgw-i](https://docs.ceph.com/en/latest/man/8/radosgw/#cmdoption-radosgw-i)

apt install radosgw -y

ceph auth get-or-create client.radosgw mon 'allow rwx' osd 'allow rwx' -o /etc/ceph/ceph.client.radosgw.keyring

ceph mgr module enable rgw --force

vim /etc/ceph/ceph.conf

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
mon_allow_pool_delete = true
[client.radosgw]
keyring = /etc/ceph/ceph.client.radosgw.keyring
```

systemctl enable [ceph-radosgw@radosgw](http://ceph-radosgw@radosgw)

systemctl start [ceph-radosgw@radosgw](http://ceph-radosgw@radosgw)

radosgw-admin user create --display-name="admin user" --uid=admin

```
root@node1:~# radosgw-admin user create --display-name="admin user" --uid=admin
{
    "user_id": "admin",
    "display_name": "admin user",
    "email": "",
    "suspended": 0,
    "max_buckets": 1000,
    "subusers": [],
    "keys": [
        {
            "user": "admin",
            "access_key": "ISLJ3JS9R7162OSSM8WN",
            "secret_key": "tYvhfDM2ABZiqAC8Xhj4X3TsC5xKcNuD71ohuBnt"
        }
    ],
    "swift_keys": [],
    "caps": [],
    "op_mask": "read, write, delete",
    "default_placement": "",
    "default_storage_class": "",
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
    "type": "rgw",
    "mfa_ids": []
}
```

curl 127.0.0.1:7480

```
root@node1:~# curl 127.0.0.1:7480
<?xml version="1.0" encoding="UTF-8"?><ListAllMyBucketsResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/"><Owner><ID>anonymous</ID><DisplayName></DisplayName></Owner><Buckets></Buckets></ListAllMyBucketsResult>r
```

[https://www.bbsmax.com/A/MAzAm3aMz9/](https://www.bbsmax.com/A/MAzAm3aMz9/)

[https://docs.ceph.com/en/latest/man/8/rados/?highlight=rados](https://docs.ceph.com/en/latest/man/8/rados/?highlight=rados)

rados不知道怎么操作就卡住，回头研究，这里用s3cmd或者s3browser测试是可以的

> 自动启动有问题

root@node1:~# systemctl status ceph-radosgw@node1

- ceph-radosgw@node1.service - Ceph rados gateway

Loaded: loaded (/lib/systemd/system/ceph-radosgw@.service; enabled; vendor preset: enabled)

Active: failed (Result: exit-code) since Wed 2023-03-22 18:39:28 CST; 8s ago

Process: 4810 ExecStart=/usr/bin/radosgw -f --cluster ${CLUSTER} --name client.node1 --setuser ceph --setgroup ceph (code=exited, status=1/FAILURE)

Main PID: 4810 (code=exited, status=1/FAILURE)

3▒<9C><88> 22 18:39:28 node1 systemd[1]: ceph-radosgw@node1.service: Scheduled restart job, restart counter is at 5.

3▒<9C><88> 22 18:39:28 node1 systemd[1]: Stopped Ceph rados gateway.

3▒<9C><88> 22 18:39:28 node1 systemd[1]: ceph-radosgw@node1.service: Start request repeated too quickly.

3▒<9C><88> 22 18:39:28 node1 systemd[1]: ceph-radosgw@node1.service: Failed with result 'exit-code'.

3▒<9C><88> 22 18:39:28 node1 systemd[1]: Failed to start Ceph rados gateway.

root@node1:~# /usr/bin/radosgw -f --cluster ceph --name client.node1 --setuser ceph --setgroup ceph

2023-03-22T18:40:03.932+0800 7f30dbed0cc0 -1 auth: unable to find a keyring on /var/lib/ceph/radosgw/ceph-node1/keyring: (2) No such file or directory

2023-03-22T18:40:03.932+0800 7f30dbed0cc0 -1 AuthRegistry(0x55ff3edbb260) no keyring found at /var/lib/ceph/radosgw/ceph-node1/keyring, disabling cephx

2023-03-22T18:40:03.940+0800 7f30dbed0cc0 -1 auth: unable to find a keyring on /var/lib/ceph/radosgw/ceph-node1/keyring: (2) No such file or directory

2023-03-22T18:40:03.940+0800 7f30dbed0cc0 -1 AuthRegistry(0x7ffd91e07d90) no keyring found at /var/lib/ceph/radosgw/ceph-node1/keyring, disabling cephx

failed to fetch mon config (--no-mon-config to skip)

# RBD测试

[https://docs.ceph.com/en/latest/rbd/rados-rbd-cmds/](https://docs.ceph.com/en/latest/rbd/rados-rbd-cmds/)

ceph osd pool create rbdpool 8 8    //最好就创建pool的名字为rbd,因为默认就是操作rbd这个pool，要不然很多操作都需要指定pool参数

rbd pool init rbdpool

创建块设备映像

rbd create --size 1G rbdpool/test1   或者 rbd create --size 1G test1 --pool rbdpool

rbd ls -p rbdpool

rbd info --pool rbdpool test1

```

root@node1:/mnt/mycephfs# rbd info --pool rbdpool test1
rbd image 'test1':
        size 1 GiB in 256 objects
        order 22 (4 MiB objects)
        snapshot_count: 0
        id: fab1b8004cd6
        block_name_prefix: rbd_data.fab1b8004cd6
        format: 2
        features: layering, exclusive-lock, object-map, fast-diff, deep-flatten
        op_features:
        flags:
        create_timestamp: Thu Mar 23 10:46:50 2023
        access_timestamp: Thu Mar 23 10:46:50 2023
        modify_timestamp: Thu Mar 23 10:46:50 2023
```

[https://docs.ceph.com/en/latest/man/8/rbdmap/#examples](https://docs.ceph.com/en/latest/man/8/rbdmap/#examples)

rbd map --pool rbdpool test1    或者 rbd map rbdpool/test1

```
root@node1:/mnt/mycephfs# rbd map --pool rbdpool test1
/dev/rbd0
```

ceph osd pool application enable rbdpool rbd

mkfs.xfs /dev/rbd0

mkdir /mnt/rbdpooltest1

vim /etc/fstab

```
/dev/rbd/rbdpool/test1 /mnt/rbdpooltest1 xfs noauto 0 0
```

mount   /dev/rbd0   /mnt/rbdpooltest1

```
systemctl enable rbdmap.service
```

ceph df

dd if=/dev/zero of=/dev/rbd0  bs=1M count=10

ceph df