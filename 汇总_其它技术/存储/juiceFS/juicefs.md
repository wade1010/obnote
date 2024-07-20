社区版文档：

https://juicefs.com/docs/zh/community/introduction/





macOS 系统​

由于 macOS 默认不支持 FUSE 接口，需要先安装 macFUSE 实现对 FUSE 的支持。

提示

macFUSE 是一个开源的文件系统增强工具，它让 macOS 可以挂载第三方的文件系统，使得 JuiceFS 客户端可以将文件系统挂载到 macOS 系统中使用。





编译安装：（交叉编译看下方）



go build -ldflags="-s -w" -o ocfs ./cmd



详细文档如下



```javascript
类 Unix 客户端​
编译面向 Linux、macOS、BSD 等类 Unix 系统的客户端需要满足以下依赖：
Go 1.17+
GCC 5.4+
克隆源码
git clone https://github.com/juicedata/juicefs.git
进入源代码目录
cd juicefs
切换分支
源代码默认使用 main 分支，你可以切换到任何正式发布的版本，比如切换到最新发布的v1.0.0-beta2(2022年三月发布)：
git checkout v1.0.0-beta2
注意
开发分支经常涉及较大的变化，请不要将「开发分支」编译的客户端用于生产环境。
执行编译
make
编译好的 juicefs 二进制程序位于当前目录。
```





使用：



```javascript
./ocfs format \
    --storage s3 \
    --bucket http://127.0.0.1:19000/oeosstorage1  \
    --access-key minioadmin \
    --secret-key minioadmin \
    redis://localhost:6379/0 \
    myocfs
```

执行后输出结果如下

```javascript
2022/05/09 14:28:24.703951 ocfs[13817] <INFO>: Meta address: redis://localhost [interface.go:385]
2022/05/09 14:28:24.720217 ocfs[13817] <WARNING>: AOF is not enabled, you may lose data if Redis is not shutdown properly. [info.go:83]
2022/05/09 14:28:24.720769 ocfs[13817] <INFO>: Ping redis: 433.891µs [redis.go:2779]
2022/05/09 14:2挂载文件系统8:24.723073 ocfs[13817] <INFO>: Data use s3://oeosstorage1/myocfs/ [format.go:410]
2022/05/09 14:28:24.757467 ocfs[13817] <INFO>: Volume is formatted as {Name:myocfs UUID:5871b0ae-e58c-4f00-bd14-621a57937754 
Storage:s3 Bucket:http://127.0.0.1:19000/oeosstorage1 AccessKey:minioadmin SecretKey:removed BlockSize:4096 
Compression:none Shards:0 HashPrefix:false Capacity:0 Inodes:0 EncryptKey: KeyEncrypted:true TrashDays:1 
MetaVersion:1 MinClientVersion: MaxClientVersion:} [format.go:448]
```





表示正常启动





挂载文件系统

```javascript
./ocfs mount redis://localhost:6379/0 mnt
```





mnt 是要挂载的本地目录，比如/opt/mnt   上面mnt就是挂载到当前目录下的mnt目录上









卸载文件系统



```javascript
./ocfs umount mnt
```





如何销毁文件系统



先查找查找文件系统的 UUID



./ocfs status redis://127.0.0.1:6379/0

```javascript
2022/05/09 14:39:42.183451 ocfs[14491] <INFO>: Meta address: redis://127.0.0.1:6379/0 [interface.go:385]
2022/05/09 14:39:42.196870 ocfs[14491] <WARNING>: AOF is not enabled, you may lose data if Redis is not shutdown properly. [info.go:83]
2022/05/09 14:39:42.200636 ocfs[14491] <INFO>: Ping redis: 2.489984ms [redis.go:2779]
{
  "Setting": {
    "Name": "myocfs",
    "UUID": "5871b0ae-e58c-4f00-bd14-621a57937754",
    "Storage": "s3",
    "Bucket": "http://127.0.0.1:19000/oeosstorage1",
    "AccessKey": "minioadmin",
    "SecretKey": "removed",
    "BlockSize": 4096,
    "Compression": "none",
    "Shards": 0,
    "HashPrefix": false,
    "Capacity": 0,
    "Inodes": 0,
    "KeyEncrypted": true,
    "TrashDays": 1,
    "MetaVersion": 1,
    "MinClientVersion": "",
    "MaxClientVersion": ""
  },
  "Sessions": []
}

```



./ocfs destroy redis://127.0.0.1:6379/0 5871b0ae-e58c-4f00-bd14-621a57937754



```javascript
2022/05/09 14:40:20.505001 ocfs[14515] <INFO>: Meta address: redis://127.0.0.1:6379/0 [interface.go:385]
2022/05/09 14:40:20.506602 ocfs[14515] <WARNING>: AOF is not enabled, you may lose data if Redis is not shutdown properly. [info.go:83]
2022/05/09 14:40:20.506735 ocfs[14515] <INFO>: Ping redis: 65.505µs [redis.go:2779]
 volume name: myocfs
 volume UUID: 5871b0ae-e58c-4f00-bd14-621a57937754
data storage: s3://oeosstorage1/myocfs/
  used bytes: 83730432
 used inodes: 4
WARNING: The target volume will be destoried permanently, including:
WARNING: 1. ALL objects in the data storage: s3://oeosstorage1/myocfs/
WARNING: 2. ALL entries in the metadata engine: redis://127.0.0.1:6379/0
Proceed anyway? [y/N]: y
Deleted objects count: 24   
2022/05/09 14:40:25.066583 ocfs[14515] <INFO>: The volume has been destroyed! You may need to delete cache directory manually. [destroy.go:209]
```





# mac交叉编译到windows



```javascript
brew install FiloSottile/musl-cross/musl-cross
```



make juicefs.exe



报错

```javascript
 make juicefs.exe                                                                                                                                                           ✹ ✭
GOOS=windows CGO_ENABLED=1 CC=x86_64-w64-mingw32-gcc \
             go build -ldflags="-s -w -X github.com/juicedata/juicefs/pkg/version.revision=cb51d130 -X github.com/juicedata/juicefs/pkg/version.revisionDate=2022-05-08" -buildmode exe -o juicefs.exe ./cmd
# runtime/cgo
cgo: C compiler "x86_64-w64-mingw32-gcc" not found: exec: "x86_64-w64-mingw32-gcc": executable file not found in $PATH

```



GCC 没有原生 Windows 客户端

但是   cgo  不支持交叉编译 ，go 支持 但是 c  不支持

因为用了cgo 库 交叉编译 



解决

https://www.macports.org/install.php 装不成功。。。。



查了下 brew也行



brew install mingw-w64



make juicefs.exe



有如下报错

但是编译出来东西了。。。。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003007.jpg)















# mac交叉编译到linux

```javascript
make juicefs.linux
```



最终命令就是	

```javascript
CGO_ENABLED=1 GOOS=linux GOARCH=amd64 CC=x86_64-linux-musl-gcc CGO_LDFLAGS="-static" go build -ldflags="-s -w -X github.com/juicedata/juicefs/pkg/version.revision=cb51d130 -X github.com/juicedata/juicefs/pkg/version.revisionDate=2022-05-08"  -o juicefs ./cmd
```



