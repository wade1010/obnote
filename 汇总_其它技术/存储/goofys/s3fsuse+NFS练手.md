Linux7

vim ${HOME}/.aws/credentials

```
[default]
aws_access_key_id = oeosadmin
aws_secret_access_key = oeosadmin
```

```
yum install s3fs-fuse
```

mkdir /mnt/s3fs

chmod 777 s3fs

s3fs -o allow_other -o nonempty -o no_check_certificate -o use_path_request_style -o umask=000  -o dbglevel=debug -f -o curldbg -o url=[http://172.16.1.232:9000 ](http://172.16.1.232:9000)s3fs-test /mnt/s3fs

设置共享

修改 /etc/exports，添加共享目录

```
echo "/mnt/s3fs 192.168.1.0/24(fsid=0,rw,no_all_squash,sync)" >> /etc/exports
任意IP
echo "/mnt/s3fs *(fsid=0,rw,no_all_squash,sync)" >> /etc/exports
```

刷新配置使得修改立刻生效

```
exportfs -r
```

## **客户端-192.168.1.231**

查看可挂载目录

showmount -e 192.168.1.232

创建空挂载点

mkdir -p /mnt/s3fs

chmod 777 -R /mnt/s3fs

挂载共享文件

mount -t nfs 192.168.1.232:/mnt/s3fs /mnt/s3fs