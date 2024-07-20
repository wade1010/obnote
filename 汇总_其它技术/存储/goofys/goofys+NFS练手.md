[https://blog.csdn.net/weixin_43582081/article/details/123932105](https://blog.csdn.net/weixin_43582081/article/details/123932105)

[https://blog.csdn.net/liaomingwu/article/details/124313289](https://blog.csdn.net/liaomingwu/article/details/124313289)

安装goofys可以参考<<linux安装goofys>>

192.168.1.232做NFSserver

192.168.1.231做NFS client

yum install nfs-utils rpcbind -y    客户端服务端都需要安装

后来看

只安装 nfs-utils 即可，rpcbind 属于它的依赖，也会安装上。

启动NFS

systemctl start nfs.service

systemctl start rpcbind.service

开机自启

systemctl enable nfs.service

systemctl enable rpcbind.service

服务端-192.168.1.232

创建共享目录

mkdir -p /home/nfs

chmod 777 -R /home/nfs

设置共享

修改 /etc/exports，添加共享目录

```
echo "/home/nfs 192.168.1.0/24(rw,no_all_squash,sync)" >> /etc/exports
```

上面配置会有个错误，目前不会报出来，exportfs -r requires fsid for nfs export 需要改成

> echo "/home/nfs 192.168.1.0/24(fsid=0,rw,no_all_squash,sync)" >> /etc/exports


刷新配置使得修改立刻生效

```
exportfs -r
```

## NFS配置参数权限

详情 命令行 查看linux手册 man exports

[man-exports.txt](https://www.yuque.com/attachments/yuque/0/2022/txt/149866/1648923517688-823b0576-d13b-4cca-89fe-7b9e8bb788ae.txt?_lake_card=%7B%22src%22%3A%22https%3A%2F%2Fwww.yuque.com%2Fattachments%2Fyuque%2F0%2F2022%2Ftxt%2F149866%2F1648923517688-823b0576-d13b-4cca-89fe-7b9e8bb788ae.txt%22%2C%22name%22%3A%22man-exports.txt%22%2C%22size%22%3A22428%2C%22type%22%3A%22text%2Fplain%22%2C%22ext%22%3A%22txt%22%2C%22status%22%3A%22done%22%2C%22taskId%22%3A%22u68a3d856-e61d-40c5-a660-d4b7c2aaa64%22%2C%22taskType%22%3A%22upload%22%2C%22id%22%3A%22u52cba238%22%2C%22card%22%3A%22file%22%7D)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003612.jpg)

# 客户端-192.168.1.231

查看可挂载目录

showmount -e 192.168.1.232

创建空挂载点

mkdir -p /mnt/nfs

chmod 777 -R /mnt/nfs

挂载共享文件

mount -t nfs 192.168.1.232:/home/nfs /mnt/nfs

/goofys -f -debug_fuse -debug_s3 --endpoint [http://172.16.1.232:9000/](http://172.16.1.232:9000/) s3fs-test /opt/goofys

fuses是没有完全兼容POSIX，然后NFS共享goofys挂载的对象目录到另外一个服务器后，在另外一个服务器，写文件会报错，读可以。

写的时候报错如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003158.jpg)

测试大概如下，A服务装了对象存储和goofys,然后将对象的桶A挂载的目录/opt/goofys下

在A服务器的/opt/goofys目录下，所有操作（读写啊）都是可以的。

然后NFS共享到B服务，在B服务器上读是可以的，写就报上面的错。

有两个办法

1、从源头做起，找个完全兼容POSIX的对象共享挂载服务，juicefs官方写的是完全兼容。

2、从共享方式那里看看，NFS创建文件调用的是POSIX的MkNode，那有没有共享服务创建文件调的不是这个接口，掉的接口Goofys支持

![](D:/download/youdaonote-pull-master/data/Technology/存储/goofys/images/WEBRESOURCEf670b8dac6940b0f9bc999a24b8d8c84截图.png)

[https://github.com/kahing/goofys/issues/175](https://github.com/kahing/goofys/issues/175)

[https://github.com/s3fs-fuse/s3fs-fuse/pull/1957](https://github.com/s3fs-fuse/s3fs-fuse/pull/1957)

[https://github.com/s3fs-fuse/s3fs-fuse/issues/1934](https://github.com/s3fs-fuse/s3fs-fuse/issues/1934)