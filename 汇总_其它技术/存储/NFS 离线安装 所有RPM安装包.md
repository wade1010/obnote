NFS 离线安装 所有RPM安装包

# **一、下载rpm安装包**

```javascript
链接: https://pan.baidu.com/s/1OIXVwGcsjODgDUOD633zUA 提取码: pwsb 
```

# **二、执行下面命令安装**

```javascript
#关闭防火墙
systemctl stop firewalld.service
systemctl disable firewalld.service

#关闭selinux
setenforce 0

#创建目录/home/nfs，上传安装文件到此目录
mkdir -p /home/nfs

#执行安装
rpm -ivh keyutils-1.5.8-3.el7.x86_64.rpm
rpm -ivh libevent-2.0.21-4.el7.x86_64.rpm
rpm -ivh libnfsidmap-0.25-19.el7.x86_64.rpm
rpm -ivh libtirpc-0.2.4-0.16.el7.x86_64.rpm
rpm -ivh nfs-utils-1.3.0-0.68.el7.2.x86_64.rpm

```

# **三、配置NFS服务端共享目录**

[https://blog.51cto.com/loong576/2343248](https://blog.51cto.com/loong576/2343248)

```javascript
#编辑NFS服务端的配置
vi /etc/exports

#加入如下信息。
#表示允许172.27.34.0和172.27.9.0两个网段的服务器访问，若对所有ip地址都可以访问则可设置为*：
/backup 172.27.34.0/24(rw,sync,no_root_squash)
/backup 172.27.9.0/24(rw,sync,no_root_squash)
/backup  *(rw,sync,no_root_squash)

#加载配置
exportfs -r

#查看配置
exportfs 

#
systemctl enable rpcbind.service
systemctl enable nfs-server.service
systemctl start rpcbind.service
systemctl start nfs-server.service 

#
systemctl restart nfs-server.service 

```

### **典型参数说明**

```javascript

ro：共享目录只读
rw：共享目录可读可写
all_squash：所有访问用户都映射为匿名用户或用户组
no_all_squash（默认）：访问用户先与本机用户匹配，匹配失败后再映射为匿名用户或用户组
root_squash（默认）：将来访的root用户映射为匿名用户或用户组
no_root_squash：来访的root用户保持root帐号权限
secure（默认）：限制客户端只能从小于1024的tcp/ip端口连接服务器
insecure：允许客户端从大于1024的tcp/ip端口连接服务器
sync：将数据同步写入内存缓冲区与磁盘中，效率低，但可以保证数据的一致性
async：将数据先保存在内存缓冲区中，必要时才写入磁盘
wdelay（默认）：检查是否有相关的写操作，如果有则将这些写操作一起执行，这样可以提高效率
no_wdelay：若有写操作则立即执行，应与sync配合使用
subtree_check ：若输出目录是一个子目录，则nfs服务器将检查其父目录的权限
no_subtree_check（默认） ：即使输出目录是一个子目录，nfs服务器也不检查其父目录的权限，这样可以提高效率
```

# **四、NFS客户端安装，挂载**

```javascript
#关闭防火墙
systemctl stop firewalld.service
systemctl disable firewalld.service

#关闭selinux
setenforce 0

#
mkdir -p /home/nfs

#
rpm -ivh keyutils-1.5.8-3.el7.x86_64.rpm
rpm -ivh libevent-2.0.21-4.el7.x86_64.rpm
rpm -ivh libnfsidmap-0.25-19.el7.x86_64.rpm
rpm -ivh libtirpc-0.2.4-0.16.el7.x86_64.rpm
rpm -ivh nfs-utils-1.3.0-0.68.el7.2.x86_64.rpm

#创建挂载点
mkdir -p /home/hci/nfs

#显示服务端，可挂载目录
showmount -e 10.129.237.131

#进行挂载。
# - 172.27.9.181:/backup 。服务端IP和文共享目录。
# - /home/hci/nfs 。 本地挂载点。
mount -t nfs 10.129.237.131:/home /home/hci/nfs

```

# **五、相关依赖包下载**

这个地址，显示NFS相关依赖包。如果安装过程缺少包，从此下载。

[http://rpmfind.net/linux/RPM/centos/7.9.2009/x86_64/Packages/nfs-utils-1.3.0-0.68.el7.x86_64.html](http://rpmfind.net/linux/RPM/centos/7.9.2009/x86_64/Packages/nfs-utils-1.3.0-0.68.el7.x86_64.html)

结束！