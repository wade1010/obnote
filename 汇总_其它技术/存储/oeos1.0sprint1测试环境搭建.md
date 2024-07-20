一、安装cosbench

https://github.com/intel-cloud/cosbench/blob/master/COSBenchUserGuide.pdf

环境：centos7

节点:

node1  controller、driver

node2 driver

node3 driver



1、yum install java curl  wget unzip nmap-ncat -y        全部节点执行

 

2、装cosbench         全部节点执行

wget https://github.com/intel-cloud/cosbench/releases/download/v0.4.2.c4/0.4.2.c4.zip    这是最新版本的前一个版本，目前最新版centos7上测试是有bug的。

unzip 0.4.2.c4.zip&&mv 0.4.2.c4 cosbench&&cd cosbench&&chmod +x *.sh

systemctl stop firewalld

systemctl disable firewalld



需要修改一个脚本，要不然测试read的时候会failed

vim cosbench-start.sh

找到下方java启动命令行出，加入 -Dcom.amazonaws.services.s3.disableGetObjectMD5Validation=true

如

/usr/bin/nohup java -Dcom.amazonaws.services.s3.disableGetObjectMD5Validation=true -Dcosbenchxxxxxxx........



3、配置cosbench

cosbench有两个重要的组成部分，一个是controller，另一个是driver

- Controller node drives the benchmarks and aggregate results.

- Driver nodes which runs test driver processes. Each driver node can host multiple driver processes as configured.



3.1、controller配置              controller节点执行

vim conf/controller.conf

```javascript
[controller]
drivers = 3
log_level = INFO
log_file = log/system.log
archive_dir = archive

[driver1]
name = driver1
url = http://127.0.0.1:18088/driver


[driver2]
name = driver2
url = http://192.168.1.89:18088/driver

[driver3]
name = driver3
url = http://192.168.1.90:18088/driver
```



web 控制台地址 http://192.168.1.88:19088/controller/

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008085.jpg)

二、安装tikv集群



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



三、每个节点上以分布式运行minio作为s3服务



wget https://dl.min.io/server/minio/release/linux-amd64/minio

chmod +x minio

mkdir /miniodata

nohup ./minio server http://127.0.0.1/miniodata/data\{1...4\} --console-address :19001 --address :19000 &> minio.log &



这里默认用户名密码都是 minioadmin



四、上传oeos可执行文件  (编译oeos项目生成可执行文件  这里文件名为oeos)

scp oeos root@192.168.1.88:/root

scp oeos root@192.168.1.89:/root

scp oeos root@192.168.1.90:/root



五、配置node.ini文件

```javascript
[default]
node_id = N01
launch_params_str = /root/oeosdata,--console-address,:9001
tikv = 127.0.0.1:2379
s3_endpoint = 127.0.0.1:19000
s3_access_key_id = minioadmin
s3_secret_access_key = minioadmin
s3_default_bucket = default
cache_file_size = 1024
cache_cycle = 0
```



需要修改的就是 node_id

192.168.1.88  -> N01

192.168.1.89  -> N02

192.168.1.90  -> N03



六、启动oeos

./oeos server --node-conf /root/node.ini &> oeos.log &



七、关闭oeos

pkill oeos



八、测试，这里只对S3配置作说明

进入cosbench根目录

cat conf/s3-config-sample.xml

```javascript
<?xml version="1.0" encoding="UTF-8" ?>
<workload name="s3-sample" description="sample benchmark for s3">

  <storage type="s3" config="accesskey=<accesskey>;secretkey=<scretkey>;proxyhost=<proxyhost>;proxyport=<proxyport>;endpoint=<endpoint>" />

  <workflow>

    <workstage name="init">
      <work type="init" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

    <workstage name="prepare">
      <work type="prepare" workers="1" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,10);sizes=c(64)KB" />
    </workstage>

    <workstage name="main">
      <work name="main" workers="8" runtime="30">
        <operation type="read" ratio="80" config="cprefix=s3testqwer;containers=u(1,2);objects=u(1,10)" />
        <operation type="write" ratio="20" config="cprefix=s3testqwer;containers=u(1,2);objects=u(11,20);sizes=c(64)KB" />
      </work>
    </workstage>

    <workstage name="cleanup">
      <work type="cleanup" workers="1" config="cprefix=s3testqwer;containers=r(1,2);objects=r(1,20)" />
    </workstage>

    <workstage name="dispose">
      <work type="dispose" workers="1" config="cprefix=s3testqwer;containers=r(1,2)" />
    </workstage>

  </workflow>

</workload>
```



下面对配置文件的参数进行说明:

- workload name : 测试时显示的任务名称，这里可以自行定义

- description : 描述信息，这里可以自己定义

- storage type: 存储类型，这里配置为s3即可

- config : 对该类型的配置，

- workstage name : cosbench是分阶段按顺序执行，此处为init初始化阶段，主要是进行bucket的创建，workers表示执行该阶段的时候开启多少个工作线程，创建bucket通过不会计算为性能，所以单线程也可以;config处配置的是存储桶bucket的名称前缀;containers表示轮询数，上例中将会创建以s3testqwer为前缀，后缀分别为1和2的bucket

- prepare阶段 : 配置为bucket写入的数据，workers和config以及containers与init阶段相同，除此之外还需要配置objects，表示一轮写入多少个对象，以及object的大小。

- main阶段 : 这里是进行测试的阶段，runtime表示运行的时间，时间默认为秒

- operation type : 操作类型，可以是read、write、delete等。ratio表示该操作所占有操作的比例，例如上面的例子中测试读写，read的比例为80%,write的比例为20%; config中配置bucket的前缀后缀信息。注意write的sizes可以根据实际测试进行修改

- cleanup阶段 : 这个阶段是进行环境的清理，主要是删除bucket中的数据，保证测试后的数据不会保留在集群中

- dispose阶段 : 这个阶段是删除bucket





修改完配置就可以开始测试了。有两种办法启动测试



1、通过命令行启动   



```javascript
./cli.sh submit conf/s3-config-sample.xml
```



2、通过web console 启动



![](https://gitee.com/hxc8/images6/raw/master/img/202407190008585.jpg)



![](https://gitee.com/hxc8/images6/raw/master/img/202407190008929.jpg)



选择你的xml配置 submit就行了