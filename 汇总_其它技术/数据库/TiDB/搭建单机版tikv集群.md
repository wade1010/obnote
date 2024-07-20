参考[https://blog.csdn.net/u010033674/article/details/109347814](https://blog.csdn.net/u010033674/article/details/109347814)

1、装tiup简单

步骤可以参考

[https://tikv.org/docs/5.1/deploy/install/production/](https://tikv.org/docs/5.1/deploy/install/production/)

2、主要是配置，vi topology.yaml

```
global:
  user: "tikv"
  ssh_port: 22
  deploy_dir: "./tikv-deploy"
  data_dir: "./tikv-data"
server_configs:
 tikv:
   readpool.storage.use-unified-pool: false
   readpool.coprocessor.use-unified-pool: true
 pd:
   replication.enable-placement-rules: true
   replication.location-labels:
   - host
pd_servers:
  - host: 192.168.1.99
tikv_servers:
  - host: 192.168.1.99
    port: 20160
    status_port: 20180
    deploy_dir: "./tidb-deploy/tikv-20160"
    data_dir: "./tidb-data/tikv-20160"
    config:
       server.labels: { host: "192.168.1.99" }
  - host: 192.168.1.99
    port: 20161
    status_port: 20181
    deploy_dir: "./tidb-deploy/tikv-20161"
    data_dir: "./tidb-data/tikv-20161"
    config:
       server.labels: { host: "192.168.1.99" }
  - host: 192.168.1.99
    port: 20162
    status_port: 20182
    deploy_dir: "./tidb-deploy/tikv-20162"
    data_dir: "./tidb-data/tikv-20162"
    config:
       server.labels: { host: "192.168.1.99" }
```

```
tiup cluster check ./topology.yaml --user root -p
tiup cluster check ./topology.yaml --apply --user root -p
tiup cluster deploy tikv-test v5.3.0 ./topology.yaml --user root -p
tiup cluster start tikv-test
tiup display tikv-test
```