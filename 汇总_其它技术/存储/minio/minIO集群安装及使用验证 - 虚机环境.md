# 一、环境

## 1 - 节点配置

节点数量：4个

节点IP：192.168.88.121，192.168.88.122，192.168.88.123，192.168.88.124

完装完成后，快速启动用

```
#停止服务
systemctl stop minio



#启动服务


systemctl start minio

#查看服务状态


systemctl status minio.service -l

```

## 2 - 环境配置

```
#关闭防火墙
systemctl stop firewalld.service
systemctl disable firewalld.service
```

# 二、节点加盘（非必要步骤，如果盘已具备，跳过本节）

## 1 - 节点加盘（此处，每个节点加一块4GB的盘）

```
#查看现在的盘
fdisk -l




#添加新盘，输入fdisk /dev/sdb
[root@localhost ~]# fdisk /dev/sdb


Welcome to fdisk (util-linux 2.23.2).

Changes will remain in memory only, until you decide to write them.
Be careful before using the write command.

Device does not contain a recognized partition table
Building a new DOS disklabel with disk identifier 0x00c8898f.



#新建分区，输入n
Command (m for help): n
Partition type:
   p   primary (0 primary, 0 extended, 4 free)
   e   extended
   
#新建主分区,输入p
Select (default p): p


#分区编号,输入1
Partition number (1-4, default 1): 1


#回车
First sector (2048-8388607, default 2048): 
Using default value 2048
#回车
Last sector, +sectors or +size{K,M,G} (2048-8388607, default 8388607): 
Using default value 8388607
Partition 1 of type Linux and of size 4 GiB is set


#写入分区结果，输入w
Command (m for help): w
The partition table has been altered!

Calling ioctl() to re-read partition table.
Syncing disks.




```

## 2 - 格式化

```
#格式化
mkfs.ext4 /dev/sdb1




#在根目录创建/data作为此分区的挂载点
mkdir /miniodata
 

#将分区挂载到目录下
mount /dev/sdb1 /miniodata



#查看挂载结果有“/dev/sdb1       3.9G   16M  3.7G   1% /miniodata” 如下：
df -h
[root@localhost ~]# df -h
Filesystem      Size  Used Avail Use% Mounted on
devtmpfs        1.4G     0  1.4G   0% /dev
tmpfs           1.4G     0  1.4G   0% /dev/shm
tmpfs           1.4G   11M  1.4G   1% /run
tmpfs           1.4G     0  1.4G   0% /sys/fs/cgroup
/dev/sda3        27G   14G   13G  52% /
/dev/sda1       297M  163M  135M  55% /boot
tmpfs           276M  8.0K  276M   1% /run/user/42
tmpfs           276M     0  276M   0% /run/user/0
/dev/sdb1       3.9G   16M  3.7G   1% /miniodata
```

## 3 - 配置启动时自动挂载

```
#重启时自动挂载
vi /etc/fstab

#加入如下配置
/dev/sdb1	/miniodata	ext4	defaults	0	0


#重启
reboot
```

# 三、安装minIO**（下面的步骤，4个节点都执行）**

## 1 - 创建安装目录

```
#先删除
rm -Rf /miniodata/d1
rm -Rf /miniodata/d2
rm -Rf /miniodata/d3
rm -Rf /miniodata/d4
#再创建


mkdir -p /miniodata/d1
mkdir -p /miniodata/d2
mkdir -p /miniodata/d3
mkdir -p /miniodata/d4
```

## 2 - 上传minIO可执行文件

可从此下载

[minio](attachments/WEBRESOURCE80ddf22b8db21b75b3f3677223850d6fminio)

或使用wget下载

wget https://dl.minio.io/server/minio/release/linux-amd64/minio

```
#创建minIO可执行文件目录
mkdir -p /var/minio/bin
cd /var/minio/bin

wget https://dl.minio.io/server/minio/release/linux-amd64/minio



#上传miniio文件并授权可执行
chmod +x /var/minio/bin/minio

```

## 3 - 创建配置文件目录

```
rm -rf /etc/minio/*
mkdir -p /etc/minio
```

## 4 - 创建启动脚本

```
rm -rf /var/minio/bin/run.sh
vim /var/minio/bin/run.sh

```

粘贴下面的脚本

- export MINIO_ACCESS_KEY=admin	网页登录用户名，同时也是S3访问的AK。

- export MINIO_SECRET_KEY=P@ssw0rd	网页登录密码，同时也是S3访问的SK。

- export MINIO_STORAGE_CLASS_STANDARD=EC:2		纠删策略配置。EC:2 表示‘校验块’数量是2

- --console-address ":9001"	表示网页端登录端口

- miniodata/d1		表示每个节点磁盘的挂载点

```
#!/bin/bash
export MINIO_ACCESS_KEY=admin
export MINIO_SECRET_KEY=P@ssw0rd
export MINIO_STORAGE_CLASS_STANDARD=EC:2
/var/minio/bin/minio server --console-address ":9001" --config-dir /etc/minio \
http://192.168.88.121/miniodata/d1 http://192.168.88.121/miniodata/d2 http://192.168.88.121/miniodata/d3 http://192.168.88.121/miniodata/d4 \
http://192.168.88.122/miniodata/d1 http://192.168.88.122/miniodata/d2 http://192.168.88.122/miniodata/d3 http://192.168.88.122/miniodata/d4 \
http://192.168.88.123/miniodata/d1 http://192.168.88.123/miniodata/d2 http://192.168.88.123/miniodata/d3 http://192.168.88.123/miniodata/d4 \
http://192.168.88.124/miniodata/d1 http://192.168.88.124/miniodata/d2 http://192.168.88.124/miniodata/d3 http://192.168.88.124/miniodata/d4

```

```
#授权脚本可执行
chmod +x /var/minio/bin/run.sh
```

## 5 - 编写服务启动脚本

```
#先删除可能存在的脚本
rm -rf /usr/lib/systemd/system/minio.service

vim /usr/lib/systemd/system/minio.service

```

粘贴下面的脚本

- WorkingDirectory	工作目录

- ExecStar			启动脚本

```
[Unit]
Description=Minio service
Documentation=https://docs.minio.io/

[Service]
WorkingDirectory=/var/minio/bin/
ExecStart=/var/minio/bin/run.sh
Restart=on-failure
RestartSec=5

[Install]
WantedBy=multi-user.target
```

```
#赋可执行权
chmod +x /usr/lib/systemd/system/minio.service
```

## 四、启动服务

```
systemctl start minio

```

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004932.jpg)

更多服务相关命令

```
#停止服务
systemctl stop minio

#加载服务配置


systemctl daemon-reload

#随系统启动


systemctl enable minio



#启动服务


systemctl start minio

#查看服务状态


systemctl status minio.service -l

```

# 五、创建桶

登录地址 [http://192.168.88.124:9001/login](http://192.168.88.124:9001/login)

创建桶

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004213.jpg)

# 六、S3Broswer连接及对象上传

## 1 - 配置帐号

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004418.jpg)

## 2 - 上传对象

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004956.jpg)

## 3 - 查看文件在盘上作为对象的存储结构

1）上传一个文件

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004358.jpg)

2）在对象存储层面的结构如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004746.jpg)

桶，对象，元数据，对象数据块分布情况。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004710.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004166.jpg)

# 其它、清空集群数据，重配纠删（4个节点快速操作）

```
#停止服务
systemctl stop minio 



#清空数据盘数据
rm -Rf /miniodata/*
rm -Rf /miniodata/*
rm -Rf /miniodata/*
rm -Rf /miniodata/*
#创建数据目录


mkdir -p /miniodata/d1
mkdir -p /miniodata/d2
mkdir -p /miniodata/d3
mkdir -p /miniodata/d4


#删除脚本
rm -rf /etc/minio/*
rm -rf /var/minio/bin/run.sh
#新建脚本

vim /var/minio/bin/run.sh

#加入如下脚本  MINIO_STORAGE_CLASS_STANDARD=EC:2 用于指定纠删策略



#!/bin/bash
export MINIO_ACCESS_KEY=admin
export MINIO_SECRET_KEY=P@ssw0rd
export MINIO_STORAGE_CLASS_STANDARD=EC:2
/var/minio/bin/minio server --console-address ":9001" --config-dir /etc/minio \
http://192.168.88.121/miniodata/d1 http://192.168.88.121/miniodata/d2 http://192.168.88.121/miniodata/d3 http://192.168.88.121/miniodata/d4 \
http://192.168.88.122/miniodata/d1 http://192.168.88.122/miniodata/d2 http://192.168.88.122/miniodata/d3 http://192.168.88.122/miniodata/d4 \
http://192.168.88.123/miniodata/d1 http://192.168.88.123/miniodata/d2 http://192.168.88.123/miniodata/d3 http://192.168.88.123/miniodata/d4 \
http://192.168.88.124/miniodata/d1 http://192.168.88.124/miniodata/d2 http://192.168.88.124/miniodata/d3 http://192.168.88.124/miniodata/d4




#启动
chmod +x /var/minio/bin/run.sh
systemctl start minio

#查看状态

systemctl status minio.service -l

```

结束！