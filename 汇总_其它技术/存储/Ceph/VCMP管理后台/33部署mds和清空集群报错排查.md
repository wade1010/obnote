### 清空集群

touch /etc/ceph/osdc-dev.conf

mkdir /var/named

touch /etc/ctdb/nodes

touch /etc/ctdb/public_addresses

touch /etc/named.rfc1912.zones

vcmp-anget的报错日志如下

```
ERROR /opt/vcfs/vcmp-agent/src/agent/utils/common.py exec_cmd_as 68 72323985483600 [code: 5] [timeout 30 systemctl stop named] [out: b''] [err: b'Failed to stop named.service: Unit named.service not loaded.\n']
2023-09-10 08:50:24,951 ERROR /opt/vcfs/vcmp-agent/src/agent/handlers/cluster/deploy.py clean_cluster 37 72323985483600 clean_cluster(): err: _stop_external_services() takes 0 positional arguments but 1 was given
```

vim /opt/vcfs/vcmp-agent/src/agent/controlers/x86/cluster/deploy.py +335

注释掉#yield self.clean_DNS_file()

由于调试的时候，是一直清空集群，有些文件已经被清理了，这里ctdb相关的也先注释了

修改下面两个文件

vim /opt/vcfs/vcmp-agent/src/agent/controlers/debian9/cluster/deploy.py +24

vim /opt/vcfs/vcmp-agent/src/agent/controlers/ft/cluster/deploy.py +19

添加一个参数如下

    def _stop_external_services(agent_clean_services):

这时候再点清空应该是没问题了。

### 部署mds

是之前数据库没创建全