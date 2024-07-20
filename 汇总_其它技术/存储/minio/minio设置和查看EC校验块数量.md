minio设置和查看EC校验块数量

mc admin config get minio32 storage_class

mc admin config set minio32/ storage_class standard="EC:2"

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004566.jpg)

上图版本是

minio version RELEASE.2021-11-05T09-16-26Z

下面是当前最新版本2022-11-08 18:16:54

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004099.jpg)