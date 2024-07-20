4台 Linux x86 96核  188G内存



1PD 3KV



vim /etc/security/limits.conf

```javascript
# End of file
* soft nofile 1024000
* hard nofile 1024000
root soft nofile 1024000
root hard nofile 1024000
```

重启机器



vim /etc/sysctl.conf

```javascript
# For more information, see sysctl.conf(5) and sysctl.d(5).
net.ipv4.tcp_max_tw_buckets = 20000
net.core.somaxconn = 65535
net.ipv4.tcp_max_syn_backlog = 262144
net.core.netdev_max_backlog = 30000
net.ipv4.tcp_tw_recycle = 0
fs.nr_open = 2500000
fs.file-max = 40000000
```

sysctl -p 即可



cd /opt/oeos/service/tidb-server/

tiup cluster deploy oeos-cluster v5.3.0 ./topology.yaml --user root

tiup cluster start oeos-cluster



https://tikv.org/docs/5.1/deploy/performance/instructions/



git clone https://github.com/pingcap/go-ycsb.git



cd go-ycsb



linux: CGO_ENABLED=0 GOOS=linux GOARCH=amd64 go build -o ycsb cmd/go-ycsb/*



mac: go build -o ycsb cmd/go-ycsb/* 



/root/go-ycsb/ycsb load tikv -P workloads/workloada -p tikv.pd="192.168.1.231:2379" -p tikv.type="txn" -p recordcount=1000000 -p operationcount=1000000 -p threadcount=96

/root/go-ycsb/ycsb run tikv -P workloads/workloada -p tikv.pd="192.168.1.231:2379" -p tikv.type="txn" -p recordcount=1000000 -p operationcount=1000000 -p threadcount=96

```javascript
READ   - Takes(s): 253.7, Count: 1499101, OPS: 5908.3, Avg(us): 1920, Min(us): 526, Max(us): 159131, 99th(us): 8000, 99.9th(us): 14000, 99.99th(us): 24000
UPDATE - Takes(s): 253.7, Count: 1500802, OPS: 5915.6, Avg(us): 14119, Min(us): 583, Max(us): 268381, 99th(us): 27000, 99.9th(us): 61000, 99.99th(us): 164000
UPDATE_ERROR - Takes(s): 251.9, Count: 96, OPS: 0.4, Avg(us): 50151, Min(us): 6837, Max(us): 116954, 99th(us): 117000, 99.9th(us): 117000, 99.99th(us): 117000
```



/root/go-ycsb/ycsb load tikv -P workloads/workloada -p tikv.pd="192.168.1.231:2379" -p tikv.type="raw" -p recordcount=1000000 -p operationcount=1000000 -p threadcount=96

/root/go-ycsb/ycsb run tikv -P workloads/workloada -p tikv.pd="192.168.1.231:2379" -p tikv.type="raw" -p recordcount=1000000 -p operationcount=1000000 -p threadcount=96

```javascript
INSERT - Takes(s): 10.0, Count: 174174, OPS: 17491.2, Avg(us): 5438, Min(us): 2182, Max(us): 258110, 99th(us): 12000, 99.9th(us): 46000, 99.99th(us): 258000
INSERT - Takes(s): 20.0, Count: 349675, OPS: 17520.0, Avg(us): 5412, Min(us): 2095, Max(us): 258110, 99th(us): 12000, 99.9th(us): 45000, 99.99th(us): 258000
INSERT - Takes(s): 30.0, Count: 521674, OPS: 17413.2, Avg(us): 5445, Min(us): 2095, Max(us): 258110, 99th(us): 13000, 99.9th(us): 45000, 99.99th(us): 163000
INSERT - Takes(s): 40.0, Count: 691972, OPS: 17317.6, Avg(us): 5473, Min(us): 2076, Max(us): 258110, 99th(us): 13000, 99.9th(us): 48000, 99.99th(us): 163000
INSERT - Takes(s): 50.0, Count: 862610, OPS: 17266.3, Avg(us): 5489, Min(us): 2076, Max(us): 258110, 99th(us): 13000, 99.9th(us): 49000, 99.99th(us): 140000
Run finished, takes 58.044330226s
INSERT - Takes(s): 58.0, Count: 999936, OPS: 17240.6, Avg(us): 5483, Min(us): 2076, Max(us): 258110, 99th(us): 13000, 99.9th(us): 49000, 99.99th(us): 140000
```



tiup cluster destroy oeos-cluster

tiup cluster deploy oeos-cluster v5.3.0 /opt/oeos/service/tidb-server/topology.yaml --user root

tiup cluster start oeos-cluster



/root/go-ycsb/ycsb load tikv -P workloads/workloadc -p tikv.pd="192.168.1.231:2379" -p tikv.type="raw" -p recordcount=1000000 -p operationcount=1000000 -p threadcount=96

/root/go-ycsb/ycsb run tikv -P workloads/workloadc -p tikv.pd="192.168.1.231:2379" -p tikv.type="raw" -p recordcount=1000000 -p operationcount=1000000 -p threadcount=96

```javascript
READ   - Takes(s): 10.0, Count: 455426, OPS: 45752.5, Avg(us): 2094, Min(us): 241, Max(us): 53417, 99th(us): 8000, 99.9th(us): 11000, 99.99th(us): 48000
READ   - Takes(s): 20.0, Count: 839257, OPS: 42059.2, Avg(us): 2275, Min(us): 241, Max(us): 65420, 99th(us): 8000, 99.9th(us): 13000, 99.99th(us): 36000
Run finished, takes 22.719535696s
READ   - Takes(s): 22.7, Count: 999907, OPS: 44102.0, Avg(us): 2156, Min(us): 235, Max(us): 94232, 99th(us): 8000, 99.9th(us): 14000, 99.99th(us): 63000
```





/root/go-ycsb/ycsb load tikv -P workloads/workloadc -p tikv.pd="192.168.1.231:2379" -p tikv.type="txn" -p recordcount=1000000 -p operationcount=1000000 -p threadcount=96

/root/go-ycsb/ycsb run tikv -P workloads/workloadc -p tikv.pd="192.168.1.231:2379" -p tikv.type="txn" -p recordcount=1000000 -p operationcount=1000000 -p threadcount=96

```javascript
READ   - Takes(s): 10.0, Count: 454914, OPS: 45708.0, Avg(us): 2100, Min(us): 525, Max(us): 115247, 99th(us): 9000, 99.9th(us): 18000, 99.99th(us): 52000
READ   - Takes(s): 20.0, Count: 885201, OPS: 44364.7, Avg(us): 2159, Min(us): 497, Max(us): 115247, 99th(us): 9000, 99.9th(us): 18000, 99.99th(us): 42000
Run finished, takes 22.455394118s
READ   - Takes(s): 22.4, Count: 999932, OPS: 44624.6, Avg(us): 2134, Min(us): 497, Max(us): 115247, 99th(us): 9000, 99.9th(us): 19000, 99.99th(us): 54000
[2022/04/07 18:26:07.696 +08:00] [INFO] [client.go:754] ["[pd] stop fetching the pending tso requests due to context canceled"] [dc-location=global]
[2022/04/07 18:26:07.696 +08:00] [INFO] [client.go:692] ["[pd] exit tso dispatcher"] [dc-location=global]
```





初始化OEOS

java  -jar /opt/oeos/service/import-oeos-configs.jar   /opt/oeos/service/oeos-config/    （现在2022年06月02日10:28:26  改成 import-oeos-configs  加到环境变量了）

/tmp/test/oeos server /usr/local/oeos/persistent/gatewayS3/sysmeta --node-conf /usr/local/oeos/conf/system.config --address :9000 --tenant-id 01



cosbench

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://172.16.1.231:9000;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

    <workstage name="prepare">
      <work type="prepare" workers="100" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,200000);sizes=c(4)KB" />
    </workstage>

    <workstage name="main">
      <work name="main" workers="16384" runtime="60">
        <operation type="read" ratio="100" config="cprefix=s3testqwer;containers=u(1,2);objects=u(1,200000)" />
      </work>
    </workstage>

    <workstage name="cleanup">
      <work type="cleanup" workers="100" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,200000)" />
    </workstage>

    <workstage name="dispose">
      <work type="dispose" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

  </workflow>

</workload>
```

OEOS如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008946.jpg)



4节点分布式minio的如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008488.jpg)



```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://172.16.1.231:9000;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

    <workstage name="prepare">
      <work type="prepare" workers="1000" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,200000);sizes=c(128)KB" />
    </workstage>

    <workstage name="main">
      <work name="main" workers="16384" runtime="60">
        <operation type="read" ratio="100" config="cprefix=s3testqwer;containers=u(1,2);objects=u(1,200000)" />
      </work>
    </workstage>

    <workstage name="cleanup">
      <work type="cleanup" workers="1000" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,200000)" />
    </workstage>

    <workstage name="dispose">
      <work type="dispose" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

  </workflow>

</workload>
```

oeos如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008913.jpg)







vi startminio.sh

```javascript
#!/bin/bash
export MINIO_ACCESS_KEY=minioadmin
export MINIO_SECRET_KEY=minioadmin
/tmp/minio server --config-dir /etc/minio \
--address "0.0.0.0:9029" \
http://172.16.1.231/tmp/minio-data/data1 http://172.16.1.231/opt/minio/data2 \
http://172.16.1.232/tmp/minio-data/data1 http://172.16.1.232/opt/minio/data2 \
http://172.16.1.233/tmp/minio-data/data1 http://172.16.1.233/opt/minio/data2

```





4节点minio 128K

![](D:/download/youdaonote-pull-master/data/Technology/存储/images/67D09F65AC414ABA830C9113F72406CBimage.png)







读写测试

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://172.16.1.231:9000;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

    <workstage name="prepare">
      <work type="prepare" workers="16384" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,100000);sizes=c(4)KB" />
    </workstage>

    <workstage name="main">
      <work name="main" workers="16384" runtime="60">
        <operation type="read" ratio="80" config="cprefix=s3testqwer;containers=u(1,2);objects=u(1,100000)" />
        <operation type="write" ratio="20" config="cprefix=s3testqwer;containers=u(1,2);objects=u(100001,200000);sizes=c(4)KB" />
      </work>
    </workstage>

    <workstage name="cleanup">
      <work type="cleanup" workers="16384" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,100000)" />
    </workstage>

    <workstage name="dispose">
      <work type="dispose" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

  </workflow>

</workload>
```



4K 20%写  80%读  

OEOS  读 1.9W OP/S  写4.7K OP/S

分布式minio  读 9.8K OP/S  写2.4K OP/S



oeos

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008483.jpg)

4节点分布式minio

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008918.jpg)







纯写

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://172.16.1.231:9000;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

    <workstage name="main">
      <work name="main" workers="16384" runtime="60">
        <operation type="write" ratio="100" config="cprefix=s3testqwer;containers=u(1,2);objects=u(1,200000);sizes=c(4)KB" />
      </work>
    </workstage>

    <workstage name="cleanup">
      <work type="cleanup" workers="16384" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,200000)" />
    </workstage>

    <workstage name="dispose">
      <work type="dispose" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

  </workflow>

</workload>
```

4K 100%写   

OEOS  1.1W OP/S

分布式minio  2.8K OP/S



oeos

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008417.jpg)



minio

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008846.jpg)





4K 100%写   

OEOS  1.1W OP/S

分布式minio  2.8K OP/S







---



---

4台 Linux x86 96核  188G内存



3PD 3KV 同在一个服务器



纯写

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://172.16.1.231:9000;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

    <workstage name="main">
      <work name="main" workers="16384" runtime="30">
        <operation type="write" ratio="100" config="cprefix=s3testqwer;containers=u(1,2);objects=u(1,200000);sizes=c(4)KB" />
      </work>
    </workstage>

    <workstage name="cleanup">
      <work type="cleanup" workers="16384" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,200000)" />
    </workstage>

    <workstage name="dispose">
      <work type="dispose" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190009132.jpg)





4k纯读  看来tikv跟PD对在同一个节点，确实对性能有影响

![](https://gitee.com/hxc8/images6/raw/master/img/202407190009432.jpg)



---



---



---

启动OEOS所有服务

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://172.16.1.231:9000;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

    <workstage name="prepare">
      <work type="prepare" workers="16384" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,200000);sizes=c(4)KB" />
    </workstage>

    <workstage name="main">
      <work name="main" workers="16384" runtime="30">
        <operation type="read" ratio="100" config="cprefix=s3testqwer;containers=u(1,2);objects=u(1,200000)" />
      </work>
    </workstage>

    <workstage name="cleanup">
      <work type="cleanup" workers="16384" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,200000)" />
    </workstage>

    <workstage name="dispose">
      <work type="dispose" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

  </workflow>

</workload>
```



![](https://gitee.com/hxc8/images6/raw/master/img/202407190009894.jpg)







---



---



---

3个节点OEOS 



./oeos server /usr/local/oeos/persistent/gatewayS3/sysmeta --node-conf /usr/local/oeos/conf/system.config --address :19003 --tenant-id 01





3driver +1 controller



cd /tmp/0.4.2.c4&&sh start-driver.sh



Ak1010fbi!



3TIKV  3PD 3Drive



4k小文件  30000workers  

23762.24 op/s

19787.47 op/s

15002.77 op/s

16139.51 op/s





16k小文件  30000workers  

15050.04 op/s	



128k小文件  30000workers  

11236.49 op/s



256k小文件  30000workers  

6277.01 op/s	



512k小文件  30000workers  

4811.21 op/s



1023k小文件 30000workers  

2761.11 op/s





1024k小文件 30000workers  

2619.72 op/s







tiup cluster destroy oeos-cluster

tiup cluster deploy oeos-cluster v5.3.0 /opt/oeos/service/tidb-server/topology.yaml --user root

tiup cluster start oeos-cluster

java  -jar /opt/oeos/service/import-oeos-configs.jar   /opt/oeos/service/oeos-config/

/oeos server --node-conf /usr/local/oeos/conf/system.config --address :19003 --tenant-id 01





```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=oeosadmin;secretkey=oeosadmin;endpoint=http://oeos.com:19003;path_style_access=true" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=4s3test2qwer;containers=r(1,3)" />
    </workstage>
    <workstage name="main">
      <work name="write1" workers="10000" driver="driver1" runtime="30">
        <operation type="write" ratio="100" config="cprefix=4s3test2qwer;containers=u(1,1);objects=s(1,200000);sizes=c(4)KB" />
      </work>
       <work name="write2" workers="10000" driver="driver2" runtime="30">
        <operation type="write" ratio="100" config="cprefix=4s3test2qwer;containers=u(2,2);objects=s(1,200000);sizes=c(4)KB" />
      </work>
       <work name="write3" workers="10000" driver="driver3" runtime="30">
        <operation type="write" ratio="100" config="cprefix=4s3test2qwer;containers=u(3,3);objects=s(1,200000);sizes=c(4)KB" />
      </work>
    </workstage>

  </workflow>

</workload>
```



