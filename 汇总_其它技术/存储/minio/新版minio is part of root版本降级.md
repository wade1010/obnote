

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004175.jpg)







minio升级到最新后。以前的EC启动命令失败了。最新版好像不能用目录做纠删组了。只能用不同的盘。所以本地启动。最好暂时别升级了。





https://dl.min.io/server/minio/release/windows-amd64/archive/minio.RELEASE.2021-11-05T09-16-26Z



我知道的这个版本是不需要磁盘的。



ocfs.exe format tikv://192.168.1.95:2379/test --storage s3 --bucket http://192.168.1.98:19005/test01 --access-key oeosadmin --secret-key oeosadmin myocfs

ocfs.exe format tikv://192.168.1.95:2379/test --storage s3 --bucket http://192.168.1.98:19005/test01 --access-key oeosadmin --secret-key oeosadmin myocfs



直接点击链接下载，然后把下载的文件改名成minio.exe ，本地装过，就替换原来那个文件就行了



启动命令 minio.exe server http://127.0.0.1/D:/minio/disk{1...4}  --address :19000 --console-address :19001





所有版本  下载其他也可以到下面链接看下

https://dl.min.io/server/minio/release/