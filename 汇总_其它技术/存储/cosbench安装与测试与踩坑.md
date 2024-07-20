环境：centos7

节点:

node1  controller、driver

node2 driver

node3 driver





1、yum install java curl  wget unzip nmap-ncat -y        全部节点执行

 



2、装cosbench         全部节点执行

https://github.com/intel-cloud/cosbench/releases

可以下载最新



2021-09-28日最新的是下面的链接   (!!!!!这里就是坑！！继续往下看)

wget https://github.com/intel-cloud/cosbench/releases/download/v0.4.2/0.4.2.zip

unzip 0.4.2.zip&&mv 0.4.2 cosbench&&cd cosbench&&chmod +x *.sh

systemctl stop firewalld

systemctl disable firewalld



centosq7上运行当前版本会报错，需要修改cosbench-start.sh

vim cosbench-start.sh

第35行

 35 TOOL_PARAMS=""  (源代码是TOOL_PARAMS="-i 0")



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





根据这些步骤发现启动不了driver.......哎。后来发现官方issue https://github.com/intel-cloud/cosbench/issues/380



推荐不要使用正式版的0.4.2，推荐试试0.4.2.c4, 于是下载了更早的一个版本0.4.2.c4。



重复步骤二，只是wget把地址改成  https://github.com/intel-cloud/cosbench/releases/download/v0.4.2.c4/0.4.2.c4.zip

wget https://github.com/intel-cloud/cosbench/releases/download/v0.4.2.c4/0.4.2.c4.zip

unzip 0.4.2.c4.zip&&mv 0.4.2.c4 cosbench&&cd cosbench&&chmod +x *.sh











