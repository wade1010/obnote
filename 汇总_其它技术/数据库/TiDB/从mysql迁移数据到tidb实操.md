我这个是单机部署

1、部署：

参考 [https://docs.pingcap.com/zh/tidb/stable/quick-start-with-tidb#%E9%83%A8%E7%BD%B2%E6%9C%AC%E5%9C%B0%E6%B5%8B%E8%AF%95%E9%9B%86%E7%BE%A4](https://docs.pingcap.com/zh/tidb/stable/quick-start-with-tidb#%E9%83%A8%E7%BD%B2%E6%9C%AC%E5%9C%B0%E6%B5%8B%E8%AF%95%E9%9B%86%E7%BE%A4)

2、使用tiup安装DM集群

参考 [https://docs.pingcap.com/zh/tidb/stable/deploy-a-dm-cluster-using-tiup](https://docs.pingcap.com/zh/tidb/stable/deploy-a-dm-cluster-using-tiup)

大致如下：

tiup install dm dmctl

vim dm.yaml

```

global:
  user: "tidb"
  ssh_port: 22
  deploy_dir: "/home/tidb/dm/deploy"
  data_dir: "/home/tidb/dm/data"

master_servers:
  - host: 192.168.1.118

worker_servers:
  - host: 192.168.1.118

monitoring_servers:
  - host: 192.168.1.118

grafana_servers:
  - host: 192.168.1.118

alertmanager_servers:
  - host: 192.168.1.118

```

tiup list dm-master 选一个最新版本，当前最新的是v6.5.0

tiup dm deploy dm-test v6.5.0 ./dm.yaml --user root -p

tiup dm start dm-test

tiup dm display dm-test

3、小数据量MySQL迁移数据到TiDB(小数据量就是TiB级别以下)

参考：[https://docs.pingcap.com/zh/tidb/stable/migrate-small-mysql-to-tidb](https://docs.pingcap.com/zh/tidb/stable/migrate-small-mysql-to-tidb)

大致如下

vim source1.yaml

```
source-id: "mysql-01"
enable-gtid: false
from:
  host: "127.0.0.1"
  user: "root"
  password: "xxxxxxxxx"
  port: 3306
```

上面配置我直接用了root，如果不是root需要配置DM 所需上下游数据库权限，参考链接里面有

tiup dmctl --master-addr 192.168.1.118:8261 operate-source create source1.yaml

vim task1.yaml

```
name: "test"
# 任务模式，可设为
# full：只进行全量数据迁移
# incremental： binlog 实时同步
# all： 全量 + binlog 迁移
task-mode: "full"
# 下游 TiDB 配置信息。
target-database:
  host: "127.0.0.1"                   # 例如：172.16.10.83
  port: 4000
  user: "root"
  password: "i21b^Z@wp$rv0!7j98"           # 支持但不推荐使用明文密码，建议使用 dmctl encrypt 对明文密码进行加密后使用

# 当前数据迁移任务需要的全部上游 MySQL 实例配置。
mysql-instances:
-
  # 上游实例或者复制组 ID。
  source-id: "mysql-01"
  # 需要迁移的库名或表名的黑白名单的配置项名称，用于引用全局的黑白名单配置，全局配置见下面的 `block-allow-list` 的配置。
  block-allow-list: "listA"


# 黑白名单全局配置，各实例通过配置项名引用。
block-allow-list:
  listA:                              # 名称
    do-dbs: ["yourdbname"]
```

[](https://docs.pingcap.com/zh/tidb/stable/dm-worker-intro)

tiup dmctl --master-addr 192.168.1.118:8261 check-task task1.yaml

全部符合条件后就可以启动迁移任务了，如下

tiup dmctl --master-addr 192.168.1.118:8261 start-task task1.yaml

检查进度

tiup dmctl --master-addr 192.168.1.118:8261 query-status task1.yaml