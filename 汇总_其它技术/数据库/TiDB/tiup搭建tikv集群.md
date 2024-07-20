curl --proto '=https' --tlsv1.2 -sSf https://tiup-mirrors.pingcap.com/install.sh | sh



source .bash_profile



tiup  验证下是否安装成功



tiup cluster



vim topology.yaml



```javascript
global:
  user: "tikv"
  ssh_port: 22
  deploy_dir: "/tikv-deploy"
  data_dir: "/tikv-data"
server_configs: {}
pd_servers:
  - host: 192.168.1.88
  - host: 192.168.1.89
  - host: 192.168.1.90
tikv_servers:
  - host: 192.168.1.88
  - host: 192.168.1.89
  - host: 192.168.1.90
monitoring_servers:
  - host: 192.168.1.88
grafana_servers:
  - host: 192.168.1.89
```





tiup cluster check ./topology.yaml --user root -p

输入密码



tiup cluster check ./topology.yaml --apply --user root -p

输入密码



tiup cluster deploy tikv-test v5.0.1 ./topology.yaml --user root -p

输入密码







tiup cluster list





tiup cluster display tikv-test





tiup cluster start tikv-test



```javascript
[root@localhost ~]# tiup cluster start tikv-test
Starting component `cluster`: /root/.tiup/components/cluster/v1.5.6/tiup-cluster start tikv-test
Starting cluster tikv-test...
+ [ Serial ] - SSHKeySet: privateKey=/root/.tiup/storage/cluster/clusters/tikv-test/ssh/id_rsa, publicKey=/root/.tiup/storage/cluster/clusters/tikv-test/ssh/id_rsa.pub
+ [Parallel] - UserSSH: user=tikv, host=192.168.1.89
+ [Parallel] - UserSSH: user=tikv, host=192.168.1.88
+ [Parallel] - UserSSH: user=tikv, host=192.168.1.88
+ [Parallel] - UserSSH: user=tikv, host=192.168.1.90
+ [Parallel] - UserSSH: user=tikv, host=192.168.1.90
+ [Parallel] - UserSSH: user=tikv, host=192.168.1.89
+ [Parallel] - UserSSH: user=tikv, host=192.168.1.88
+ [Parallel] - UserSSH: user=tikv, host=192.168.1.89
+ [ Serial ] - StartCluster
Starting component pd
	Starting instance 192.168.1.90:2379
	Starting instance 192.168.1.88:2379
	Starting instance 192.168.1.89:2379
	Start instance 192.168.1.88:2379 success
	Start instance 192.168.1.90:2379 success
	Start instance 192.168.1.89:2379 success
Starting component tikv
	Starting instance 192.168.1.90:20160
	Starting instance 192.168.1.89:20160
	Starting instance 192.168.1.88:20160
	Start instance 192.168.1.90:20160 success
	Start instance 192.168.1.89:20160 success
	Start instance 192.168.1.88:20160 success
Starting component prometheus
	Starting instance 192.168.1.88:9090
	Start instance 192.168.1.88:9090 success
Starting component grafana
	Starting instance 192.168.1.89:3000
	Start instance 192.168.1.89:3000 success
Starting component node_exporter
	Starting instance 192.168.1.90
	Starting instance 192.168.1.88
	Starting instance 192.168.1.89
	Start 192.168.1.88 success
	Start 192.168.1.90 success
	Start 192.168.1.89 success
Starting component blackbox_exporter
	Starting instance 192.168.1.90
	Starting instance 192.168.1.88
	Starting instance 192.168.1.89
	Start 192.168.1.90 success
	Start 192.168.1.89 success
	Start 192.168.1.88 success
+ [ Serial ] - UpdateTopology: cluster=tikv-test
```

