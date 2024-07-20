[https://github.com/tphillips/s3fs-samba](https://github.com/tphillips/s3fs-samba)

[https://github.com/land007/docker_s3fs-samba](https://github.com/land007/docker_s3fs-samba)

[https://github.com/utils-docker/s3fs-samba](https://github.com/utils-docker/s3fs-samba)

[https://github.com/hayond/s3fs-nfs](https://github.com/hayond/s3fs-nfs)

[https://github.com/flaccid/docker-s3-backed-ftp-server](https://github.com/flaccid/docker-s3-backed-ftp-server)

[https://cloudacademy.com/blog/s3-ftp-server/](https://cloudacademy.com/blog/s3-ftp-server/)  

[https://blog.csdn.net/u012993894/article/details/123730412](https://blog.csdn.net/u012993894/article/details/123730412)

[https://www.serverlab.ca/tutorials/linux/storage-file-systems-linux/how-to-scale-wordpress-sites-on-ubuntu-using-aws-s3-storage/](https://www.serverlab.ca/tutorials/linux/storage-file-systems-linux/how-to-scale-wordpress-sites-on-ubuntu-using-aws-s3-storage/)

[https://github.com/hauau/logged-s3fs](https://github.com/hauau/logged-s3fs)

SMB(全称是Server Message Block)是一个网络协议名，它能被用于Web连接和客户端与服务器之间的信息沟通。SMB最初是IBM的贝瑞·费根鲍姆（Barry Feigenbaum）研制的，其目的是将DOS操作系统中的本地文件接口“中断13”改造为网络文件系。

SMB（Server Messages Block，信息服务块）是一种在局域网上共享文件和打印机的一种通信协议，它为局域网内的不同计算机之间提供文件及打印机等资源的共享服务。SMB协议是客户机/服务器型协议，客户机通过该协议可以访问服务器上的共享文件系统、打印机及其他资源。

比如说s3fs把桶bk1挂载到目录/mnts3fs/bk1

nfs配置 共享目录 /mnts3fs 

客户端连接后，只能看到bk1这个目录 ，看不到bk1里面的内容

s3fs把桶bk1挂载到目录/mnts3fs

nfs配置 共享目录 /mnts3fs 

客户端连接后，就正常

解决办法：

目前想到比较好的就是客户端mount多个bk到同一个根目录下

[https://blog.csdn.net/kali_yao/article/details/120903104](https://blog.csdn.net/kali_yao/article/details/120903104)

[https://access.redhat.com/documentation/en-us/red_hat_enterprise_linux/6/html/security-enhanced_linux/sect-security-enhanced_linux-mounting_file_systems-multiple_nfs_mounts](https://access.redhat.com/documentation/en-us/red_hat_enterprise_linux/6/html/security-enhanced_linux/sect-security-enhanced_linux-mounting_file_systems-multiple_nfs_mounts)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002355.jpg)

vim /etc/exports

```
/mnts3fs/nfs/tbk1 *(rw,sync,insecure)
/mnts3fs/nfs/tbk2 *(rw,sync,insecure)
```

是没问题的。

但是

```
/mnts3fs/nfs/bk1 *(rw,sync,insecure)
/mnts3fs/nfs/bk2 *(rw,sync,insecure)
```

就报错

```
exportfs -r
exportfs: /mnts3fs/nfs/bk2 requires fsid= for NFS export
exportfs: /mnts3fs/nfs/bk1 requires fsid= for NFS export
```