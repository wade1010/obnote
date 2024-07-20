本节点只安装server和web里面的agent已经监控

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351905.jpg)

这里面复制来 上图两个文件

mkdir ~/.pip

vim ~/.pip/pip.conf

```
[global]
index-url = https://mirrors.aliyun.com/pypi/simple/
[install]
trusted-host=mirrors.aliyun.com
```

cd SDS22/build-sw-kylin/server

bash install        (这里要是报错如下，可以安装结束后再执行一遍install)

----     install snmp     -----

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351907.jpg)

cd 

cd SDS22/build-sw-kylin/web

bash install  （这里安装依赖和 agent   monitor两个项目）

安装ceph

yum install -y python-prettytable python-rados python-rbd python-rgw python-cherrypy python-pecan python-protobuf python-werkzeug python3-remoto librados-devel leveldb-devel libbabeltrace-devel libradosstriper-devel python2-prettytable

cd ceph_rpm

rpm -ivh ceph-mgr-14.2.8-3.ky10.sw_64.rpm  --nodeps

mv ceph-mgr-14.2.8-3.ky10.sw_64.rpm /

rpm -ivh *.rpm

mv /ceph-mgr-14.2.8-3.ky10.sw_64.rpm .

firewall-cmd --zone=public --add-port=8888/tcp --permanent

firewall-cmd --reload

后来发现还是关闭防火墙

systemctl stop firewalld.service

systemctl disable firewalld.service

后来发现diamond这个使用节点1的influxdb，

vim /etc/diamond/diamond.conf

```
[[InfluxdbHandler]]
hostname = 10.200.152.47,

```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351923.jpg)

systemctl stop influxd

systemctl restart diamond

然后就可以在web页面上选择node2看统计了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351120.jpg)