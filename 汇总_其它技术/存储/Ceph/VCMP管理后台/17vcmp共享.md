#### CIFS

安装依赖（可能有报错，报错的话就是命令复制多了）

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353170.jpg)

新建

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353328.jpg)

添加后

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353659.jpg)

登录到服务端，

vim /etc/samba/smb.conf

```
[global]
load printers = no
store dos attributes = yes
security = user
printcap name = /dev/null
log level = 0
map to guest = Bad User
max log size = 5
strict locking = no
log file = /var/log/samba/log.%%m
cups options = raw
max xmit = 4194304
case sensitive = yes
printing = bsd
[share1]
public = yes
guest ok = yes
writeable = yes
browseable = yes
path = /vcluster/cephfs/test1
```

可以看到share1

windows 验证这个共享

win+R

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353255.jpg)

会让你输入密码，输入 “认证用户”页面添加的用户

这里是testuser1

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353330.jpg)

验证这个文件是否存到ceph集群

```
# ceph osd pool ls
fs_meta
fs_data
.rgw.root
rc.rgw.control
rc.rgw.meta
rc.rgw.log
rc.rgw.buckets.index
rc.rgw.buckets.non-ec
rc.rgw.buckets.data
```

这里用的是fs_data来保存数据的，

```
# rados -p fs_data ls
10000000005.00000000
```

查看文件内容（下载到当前目录，取名为test.txt）：

rados -p <pool_name> get <object_name> <output_file>

```
rados -p fs_data get 10000000005.00000000 test.txt
```

然后使用less查看test.txt内容

内容确实是我上传的。

#### nfs

安装依赖

apt install nfs-common nfs-ganesha nfs-ganesha-gluster nfs-kernel-server nfs-ganesha-gpfs nfs-ganesha-mem nfs-ganesha-mount-9p nfs-ganesha-nullfs nfs-ganesha-proxy nfs-ganesha-vfs nfs-ganesha-xfs

systemctl stop nfs-ganesha   （这里得stop下，要不后台创建nfs会报错）

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353857.jpg)

2023-8-17 11:06:43

yum -y install libcephfs-devel librgw-devel libuuid-devel userspace-rcu-devel

发现麒麟上面没有nfs-ganesha，只能编译安装

git clone -b V2.8-stable --depth=1 [https://github.com/nfs-ganesha/nfs-ganesha.git](https://github.com/nfs-ganesha/nfs-ganesha.git)

cd nfs-ganesha

git submodule update --init --recursive

mkdir build && cd build

cmake -DUSE_FSAL_RGW=ON -DUSE_FSAL_CEPH=ON ../src/

make -j64

make install

发现 make 编译会失败 后面再研究吧

验证：

NFS服务器是在node1（ip是 10.200.152.47）上，登录到node2

showmount -e 10.200.152.47

```
# showmount -e 10.200.152.47
Export list for 10.200.152.47:
/cephnfs/cephfs/nfs *

```

mkdir -p /mnt/nfs

chmod 777 -R /mnt/nfs

mount -t nfs 10.200.152.47:/cephnfs/cephfs/nfs /mnt/nfs

df -h

```
# df -h
文件系统                           容量  已用  可用 已用% 挂载点
。。。。。。。。。。。。。。
ceph-fuse                          6.9T     0  6.9T    0% /vcluster/cephfs
10.200.152.47:/cephnfs/cephfs/nfs  6.9T     0  6.9T    0% /mnt/nfs
```

验证数据过程同上面cifs

这里用的是fs_data来保存数据的，

```
# rados -p fs_data ls
10000000005.00000000
10000000006.00000000
```

查看文件内容（下载到当前目录，取名为test.txt）：

rados -p <pool_name> get <object_name> <output_file>

```
rados -p fs_data get 10000000006.00000000 test.txt
```

然后使用less查看test.txt内容

内容确实是我上传的。

#### FTP

依赖

apt install vsftpd

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353121.jpg)

验证：

netstat -tnlp|grep vsftp  确定进程启动了

使用mobaxterm

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353125.jpg)

测试上传，如下：

![](D:/download/youdaonote-pull-master/data/Technology/存储/Ceph/VCMP管理后台/images/WEBRESOURCEdc33b8571b88e91facd92d029274ef54截图.png)

#### HTTP

这里设计apache的httpd ，ubuntu上只能直接安装apache2

页面添加，报错如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353187.jpg)

2023-8-17 13:39:15

在申威64麒麟启动上

有httpd，

验证：

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353473.jpg)

这时候没有文件，

cd /vcluster/cephfs/http/

echo 11111111111 > http.txt

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353818.jpg)