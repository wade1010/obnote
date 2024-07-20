br

dumpling

mydumper

pd-tso-bench

sync_diff_inspector

tidb-lightning

tidb-lightning-ctl

tikv-importer



https://download.pingcap.org/dm-{version}-linux-amd64.tar.gz



下载链接中的 {version} 为 Dumpling 的版本号。例如，v4.0.11 版本的下载链接为 https://download.pingcap.org/tidb-toolkit-v4.0.11-linux-amd64.tar.gz。可以通过 Dumpling Release 查看当前已发布版本



这里最新版为 v5.0.1



wget https://download.pingcap.org/tidb-toolkit-v5.0.1-linux-amd64.tar.gz



tar zxvf tidb-toolkit-v5.0.1-linux-amd64.tar.gz



cd /opt/tidb-dumpling/tidb-toolkit-v5.0.1-linux-amd64/bin



mkdir /data1/tidb-backup



./dumpling \

  -u root \

  -P 4000 \

  -h 192.168.100.110 \

  --filetype sql \

  --threads 64 \

  -o /data1/tidb-backup \

  -r 200000 \

  -B sbtest \

  -t 64 \

  -F 256MiB



cd /opt/tidb-toolkit   (官网下载的工具包)

mkdir config && cd config

配置 tidb-lightning.toml

vim tidb-lightning.toml

```javascript
[lightning]
# 日志
level = "info"
file = "tidb-lightning.log"

[tikv-importer]
# 选择使用的 local 后端
backend = "local"
# 设置排序的键值对的临时存放地址，目标路径需要是一个空目录
sorted-kv-dir = "/nvme0/tidb-sorted-kv-dir"

[mydumper]
# 源数据目录。
data-source-dir = "/nvme0/tidb-backup/"

# 配置通配符规则，默认规则会过滤 mysql、sys、INFORMATION_SCHEMA、PERFORMANCE_SCHEMA、METRICS_SCHEMA、INSPECTION_SCHEMA 系统数据库下的所有表
# 若不配置该项，导入系统表时会出现“找不到 schema”的异常
filter = ['*.*', '!mysql.*', '!sys.*', '!INFORMATION_SCHEMA.*', '!PERFORMANCE_SCHEMA.*', '!METRICS_SCHEMA.*', '!INSPECTION_SCHEMA.*']
[tidb]
# 目标集群的信息
host = "192.168.100.112"
port = 4000
user = "root"
password = ""
# 表架构信息在从 TiDB 的“状态端口”获取。
status-port = 10080
# 集群 pd 的地址
pd-addr = "192.168.100.113:2379"
```





cd ..



vim start.sh

```javascript
#!/bin/bash
nohup ./bin/tidb-lightning -config ./config/tidb-lightning.toml > nohup.out &
```



cd /opt/tidb-toolkit

sudo sh start.sh

tailf tidb-lightning.log

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809492.jpg)



不一会 grafana就有动静了



大概需要20分钟导完之前我创建的32个表的测试数据



---



TiDB Lightning Web 界面

./tidb-lightning --server-mode --status-addr :8289

 http://127.0.0.1:8289 就可以访问了