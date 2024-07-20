S3FS挂载点通过NFS共享

 

# 1 - 环境

| IP | 用途 | 备注 | 
| -- | -- | -- |
| 192.168.88.121 | NFS服务端 | NFS(5) | 
| 192.168.88.122 | NFS客户端 | NFS(5) | 
| 192.168.88.130 | 对象存储 | HCP | 


# 2 - NFS服务端安装、配置

> 参考《NFS 离线安装 所有RPM安装包》
> 


## 2.1 - 编辑nfs配置文件

```
vi /etc/exports

```

## 2.2 - 加入如下配置信息

```
# no_root_squash | 使用非root用户挂载访问时，赋于root用户权限
# fsid=0 | 使用root用户的ID
# rw | 赋于读、写权限
/mnt/s3fs	*(rw,fsid=0,no_root_squash)

```

## 2.3 - 刷新配置、重启nfs

```
exportfs -rv

systemctl reload nfs.service
systemctl restart nfs-server.service 
```

# 3 - S3FS挂载对象存储

```
# 挂载时，把权限放开 | -o allow_other -o umask=0 -o mp_umask=0
s3fs n5 /mnt/s3fs -o url=http://t1.hcp.demo.com -o allow_other -o umask=0 -o mp_umask=0 -o use_wtf8

```

调试模式，便于跟踪错误。

```
# - 调试模式的挂载参数 （-f -o curldbg -o dbglevel=critical） - 
s3fs n5 /mnt/s3fs -o url=http://t1.hcp.demo.com -f -o curldbg -o dbglevel=critical -o allow_other -o umask=0 -o mp_umask=0 -o use_wtf8

```

挂载后，可看到详细的对象桶中的文件。

shell命令行查看。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002939.jpg)

对象桶界面查看。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002970.jpg)

```

**-o use_wtf8** |  support arbitrary file system encoding. 支持任意文件系统编码
S3 requires all object names to be valid UTF-8. 
But some clients, notably Windows NFS clients, use their own encoding. 
This option re-encodes invalid UTF-8 object names into valid UTF-8 by mapping offending codes into a 'private' codepage of the Unicode set. 
Useful on clients not using UTF-8 as their file system encoding.
```

# 4 - NFS客户端挂载访问

```
mount -t nfs 192.168.88.121:/mnt/s3fs /mnt/s3fs

```

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002282.jpg)

# 5 - NFS文件访问功能测试（在NFS客户端执行）

## 5.1 - 复制文件、目录 | 失败

```
# - 客户端执行文件复制 - 失败 - 
[root@localhost 110]# cp ./DefaultStatisticsService.java ./DefaultStatisticsService.java.bak
cp: cannot create regular file ‘./DefaultStatisticsService.java.bak’: No such file or directory

# - S3FS报错信息 - 报404 - 错把文件当目录处理了 - | 同样的命令，在S3FS挂载点，可成功 | 
2022-07-12T08:01:44.148Z [CURL DBG] > HEAD /110/DefaultStatisticsService.java.bak/ HTTP/1.1
2022-07-12T08:01:44.148Z [CURL DBG] > User-Agent: s3fs/1.91 (commit hash unknown; OpenSSL)
2022-07-12T08:01:44.148Z [CURL DBG] > Accept: */*
2022-07-12T08:01:44.148Z [CURL DBG] > Authorization: AWS4-HMAC-SHA256 Credential=YWRtaW4=/20220712/us-east-1/s3/aws4_request, SignedHeaders=host;x-amz-content-sha256;x-amz-date, Signature=927042af1b699dee8e3d21c6425792d33b6ec4f0de2858a19e66af6071278a5b
2022-07-12T08:01:44.148Z [CURL DBG] > host: n5.t1.hcp.demo.com
2022-07-12T08:01:44.148Z [CURL DBG] > x-amz-content-sha256: e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855
2022-07-12T08:01:44.148Z [CURL DBG] > x-amz-date: 20220712T080144Z
2022-07-12T08:01:44.148Z [CURL DBG] > 
2022-07-12T08:01:44.152Z [CURL DBG] < HTTP/1.1 404 Not Found
2022-07-12T08:01:44.152Z [CURL DBG] < Date: Tue, 12 Jul 2022 08:01:43 GMT
```

## 5.2 - 创建多级目录（mkdir -p ） | 成功

```
mkdir -p a/b/c
```

## 5.3 - 删除文件（rm） | 成功

```
rm -f DefaultStatisticsService.java
```

## 5.4 - 删除目录（rm -r） | 成功

```
rm -rf ./a
```

## 5.5 - 文件改名（mv）| 成功

```
mv DefaultStatisticsService.java.bak abc.txt
```

## 5.6 - 目录改名（mv **）** | 成功

注：不建议此类操作。如果目录中文件数量巨大，将导致对象存储端大量复制行为。结果不可预知。

```
mv es esbak
```

## 5.7 - 编辑文件 （vi） | 成功

```
vi test.txt
```

会弹出如下提示。但是，结果是成功的。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002266.jpg)

结束！