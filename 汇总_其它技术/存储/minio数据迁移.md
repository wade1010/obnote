https://www.csdn.net/tags/MtTaEg1sNDM5NjIyLWJsb2cO0O0O.html

Minio数据迁移

千次阅读

2022-01-23 14:15:54

文章目录

- 项目背景

- 迁移方案

- 什么是[Rclone](https://rclone.org/)

- rclone 能为您做什么？

- 安装[Rclone](https://rclone.org/downloads/)

- 配置

- 常用功能

- 常用功能选项

- 常用参数

- 日志

项目背景

最近公司要把阿里云产品迁移到腾讯云上，记录一下迁移使用的方案，以及迁移过程中遇到的问题。产品中使用到了minio

，用来存储文件，由于数据量较大，需要先写一个迁移方案，并验证是否可行，记录一下使用过的方案

迁移方案

1. 使用Minio自带的mc工具

未采用原因：传输效率太慢，不支持异步传输，每秒钟不到1mb，果断放弃

1. 使用腾讯云自带的COSCMD工具

未采用原因：流量走的是公网，运维不让使用这个方案，也放弃了

1. 使用Rclone实现minio数据的迁移

采用原因：传输效率快，支持异步传输，果断采用

什么是Rclone

Rclone 是一个命令行程序，用于管理云存储上的文件。它是云供应商 Web 存储接口的功能丰富的替代品。超过 40 种云存储产品支持 rclone，包括 S3 对象存储、商业和消费者文件存储服务以及标准传输协议。

Rclone 与 unix 命令 rsync、cp、mv、mount、ls、ncdu、tree、rm 和 cat 具有强大的云等效功能。Rclone 熟悉的语法包括 shell 管道支持和--dry-run保护。它用于命令行、脚本或通过其API。

Rclone 将任何本地、云或虚拟文件系统作为磁盘安装在 Windows、macOS、linux 和 FreeBSD 上，并通过 SFTP 、 HTTP 、 WebDAV 、 FTP 和DLNA 提供这些服务。

Rclone 是成熟的开源软件，最初受 rsync 启发并用Go编写。友好的支持社区熟悉各种用例。官方 Ubuntu、Debian、Fedora、Brew 和 Chocolatey 存储库。包括rclone。推荐从 rclone.org 下载最新版本。

Rclone 广泛用于 Linux、Windows 和 Mac。第三方开发人员使用 rclone 命令行或 API 创建创新的备份、恢复、GUI 和业务流程解决方案。

Rclone 完成了与云存储通信的繁重工作。

rclone 能为您做什么？

Rclone 可以帮助您：

- 将文件备份（和加密）到云存储

- 从云存储恢复（和解密）文件

- 将云数据镜像到其他云服务或本地

- 将数据迁移到云端，或在云存储供应商之间迁移

- 将多个、加密、缓存或多样化的云存储挂载为磁盘

- 使用lsf、ljson、size、ncdu分析和说明云存储中保存的数据

- 将文件系统联合在一起以将多个本地和/或云文件系统呈现为一个

安装Rclone

在 Linux/macOS/BSD 系统上安装 rclone

curl https://rclone.org/install.sh | sudo bash


如果网络不好建议先下载到本地，然后上传到服务器

# 下载文件
wget https://downloads.rclone.org/v1.57.0/rclone-v1.57.0-linux-amd64.zip
# 解压文件
unzip rclone-v1.57.0-linux-amd64.zip
# 给一下权限
chmod 0755 ./rclone-v1.57.0-linux-amd64/rclone
# 拷贝到 /usr/bin/ 可以直接使用 rclone命令
cp ./rclone-v1.57.0-linux-amd64p/rclone /usr/bin/
# 删除源文件
rm -rf ./rclone-v1.56.0-linux-amd64.zip


配置

rclone config - 进入交互式配置选项，进行添加、删除、管理网盘等操作。
rclone config file - 显示配置文件的路径，一般配置文件在 ~/.config/rclone/rclone.conf，更换服务器可直接copy该文件。
rclone config show - 显示配置文件信息


1. 生成配置文件

- 第一种方案，这里生成配置文件时会提示让你选，随便选或者全用默认的都行，因为后面还要再改一遍配置文件

一般配置文件在 ~/.config/rclone/rclone.conf

# 备注：可以在一个配置文件中配置多份，使用[name]来区分
[oldminio]
type = s3
provider = Minio
env_auth = false
access_key_id = minio
secret_access_key = 123
region = cn-east-1
endpoint = http://IP:PORT
location_constraint =
server_side_encryption =

[newminio]
type = s3
provider = Minio
env_auth = false
access_key_id = minio
secret_access_key = 123
region = cn-east-1
endpoint = http://IP:PORT
location_constraint =
server_side_encryption =


- 第二种方案，自己根据提示配置

常用功能

# 将阿里云minio的数据迁移到腾讯的cos上
aliyun 配置文件的名称
wechat-root-files 存储桶名称
files 要传输的文件
-P 显示实时传输进度
--transfers=N - 并行文件数，默认为 4。在比较小的内存的 VPS 上建议调小这个参数，比如 128M 的小鸡上使用建议设置为 1
./rclone sync aliyun:wechat-root-files/files tencent:sj-data-testwechat-1307267266/files -P --transfers=200

# 删除文件
./rclone delete tencent:sj-data-testwechat-1307267266/wechat-root-files -P --transfers=100


常用功能选项

rclone copy - 复制
rclone move - 移动，如果要在移动后删除空源目录，请加上 --delete-empty-src-dirs 参数
rclone sync - 同步：将源目录同步到目标目录，只更改目标目录。
rclone delete - 删除路径下的文件内容。
rclone purge - 删除路径及其所有文件内容。
rclone mkdir - 创建目录。
rclone rmdir - 删除目录。
rclone rmdirs - 删除指定灵境下的空目录。如果加上 --leave-root 参数，则不会删除根目录。
rclone check - 检查源和目的地址数据是否匹配。
rclone ls - 列出指定路径下的所有的文件以及文件大小和路径。
rclone lsl - 比上面多一个显示上传时间。
rclone lsd 列出指定路径下的目录
rclone lsf - 列出指定路径下的目录和文件

# 日志
# rclone 有 4 个级别的日志记录，ERROR，NOTICE，INFO 和 DEBUG。
# 默认情况下，rclone 将生成 ERROR 和 NOTICE 级别消息。




常用参数

-n = --dry-run - 测试运行，用来查看 rclone 在实际运行中会进行哪些操作。
-P = --progress - 显示实时传输进度。
--cache-chunk-size SizeSuffi - 块的大小，默认 5M，理论上是越大上传速度越快，同时占用内存也越多。如果设置得太大，可能会导致进程中断。
--cache-chunk-total-size SizeSuffix - 块可以在本地磁盘上占用的总大小，默认 10G。
--transfers=N - 并行文件数，默认为 4。在比较小的内存的 VPS 上建议调小这个参数，比如 128M 的小鸡上使用建议设置为 1。
--config string - 指定配置文件路径，string 为配置文件路径。比如在使用宝塔面板输入命令操作时可能会遇到找不到配置文件的问题，这时就需要指定配置文件路径。




日志

rclone 有 4 个级别的日志记录，ERROR，NOTICE，INFO 和 DEBUG。

默认情况下，rclone 将生成 ERROR 和 NOTICE 级别消息。

-q rclone 将仅生成 ERROR 消息。
-v rclone 将生成 ERROR，NOTICE 和 INFO 消息，推荐此项。
-vv rclone 将生成 ERROR，NOTICE，INFO 和 DEBUG 消息。
--log-level LEVEL 标志控制日志级别。


输出日志到文件

使用 --log-file=FILE 选项，rclone 会将 Error，Info 和 Debug 消息以及标准错误重定向到 FILE，这里的 FILE 是你指定的日志文件路径。

另一种方法是使用系统的指向命令，比如

rclone sync -v Onedrive:/DRIVEX Gdrive:/DRIVEX > "~/DRIVEX.log" 2>&1


文件过滤

--exclude 排除文件或目录。比如 --exclude *.bak，排除所有 bak 文件。
--include 包含文件或目录。比如 --include *.{png,jpg} ，包含所有 png 和 jpg 文件，排除其他文件。
--delete-excluded 删除排除的文件。需配合过滤参数使用，否则无效。


目录过滤

--exclude .git/ 排除所有目录下的 .git 目录。
--exclude /.git/ 只排除根目录下的 .git 目录。


参考链接：

https://p3terx.com/archives/rclone-advanced-user-manual-common-command-parameters.html