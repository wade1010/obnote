curl --proto '=https' --tlsv1.2 -sSf https://tiup-mirrors.pingcap.com/install.sh | sh



source /root/.bash_profile





tiup cluster



vim /etc/ssh/sshd_config



由于模拟多机部署，需要通过 root 用户调大 sshd 服务的连接数限制：

修改 /etc/ssh/sshd_config 将 MaxSessions 调至 20。

重启 sshd 服务：

service sshd restart





cd /opt && mkdir tidb-cluster && cd tidb-cluster





vim topo.yaml



```javascript
# # bal variables are applied to all deployments and used as the default value of
# # the deployments if a specific deployment value is missing.
global:
 user: "tidb"
 ssh_port: 22
 deploy_dir: "/tidb-deploy"
 data_dir: "/tidb-data"

# # Monitored variables are applied to all the machines.
monitored:
 node_exporter_port: 9100
 blackbox_exporter_port: 9115

server_configs:
 tidb:
   log.slow-threshold: 300
 tikv:
   readpool.storage.use-unified-pool: false
   readpool.coprocessor.use-unified-pool: true
 pd:
   replication.enable-placement-rules: true
   replication.location-labels: ["host"]
 tiflash:
   logger.level: "info"

pd_servers:
 - host: 192.168.199.11

tidb_servers:
 - host: 192.168.199.11

tikv_servers:
 - host: 192.168.199.11
   port: 20160
   status_port: 20180
   config:
     server.labels: { host: "logic-host-1" }

 - host: 192.168.199.11
   port: 20161
   status_port: 20181
   config:
     server.labels: { host: "logic-host-2" }

 - host: 192.168.199.11
   port: 20162
   status_port: 20182
   config:
     server.labels: { host: "logic-host-3" }

tiflash_servers:
 - host: 192.168.199.11

monitoring_servers:
 - host: 192.168.199.11

grafana_servers:
 - host: 192.168.199.11
```





如果vim粘贴时格式有问题就 :set paste 再粘贴





tiup cluster deploy <cluster-name> <tidb-version> ./topo.yaml --user root -p





tiup cluster deploy tidb-cluster v5.0.1 ./topo.yaml --user root -p





tiup cluster start tidb-cluster



![](https://gitee.com/hxc8/images7/raw/master/img/202407190809884.jpg)











