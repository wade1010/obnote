[root@node02 ~]# cat /proc/version

Linux version 4.19.90-25.0.v2111.ky10.sw_64 (KYLINSOFT@localhost.localdomain) (gcc version 8.3.0 20190222 (Kylin 8.3.0-4) (GCC)) #1 SMP Mon Feb 28 14:55:25 CST 2022

[root@node02 ~]# uname -a

Linux node02 4.19.90-25.0.v2111.ky10.sw_64 #1 SMP Mon Feb 28 14:55:25 CST 2022 sw_64 sw_64 sw_64 GNU/Linux

[root@node02 ~]# uname -r

4.19.90-25.0.v2111.ky10.sw_64

[root@node02 ~]# uname -v

#1 SMP Mon Feb 28 14:55:25 CST 2022

[root@node02 ~]# uname -i

sw_64

[root@node02 ~]# lsb_release -a

LSB Version:    :core-5.0-noarch:core-5.0-sw_64

Distributor ID: KylinAdvancedServer

Description:    Kylin Linux Advanced Server release V10 (Tercel)

Release:        V10

Codename:       Tercel

先在某个服务器上，能使用代理的服务器，开启服务器后。

git clone -b v14.2.8 --depth=1 [https://github.com/ceph/ceph.git](https://github.com/ceph/ceph.git)

vim make-dist 去掉一些不需要的下载，比如boost和前端代码

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350912.jpg)

再执行 ./make-dist 14.2.8会生成 ceph-14.2.8.tar.bz2

下面是ceph.spec内容

[ceph.spec](attachments/WEBRESOURCE17d531cba3e7cce72125df6c3fb327e1ceph.spec)

把上面的 ceph-14.2.8.tar.bz2放到/rpmbuild/SOURCES目录下

把ceph.spec放到/rpmbuild/SPECS目录下

time sudo rpmbuild -bb --define '_topdir /rpmbuild' --noclean --noprep --without ceph_test_package /rpmbuild/SPECS/ceph.spec

报错 Could NOT find Java (missing: Java_JAVAH_EXECUTABLE)

这里我看java安装了当时jre没装，我就yum install -y java-1.8.0-swjdk java-1.8.0-swjdk-devel安装

设置JAVA_HOME  不知道有没有用，反正设置了

vim .bashrc

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350213.jpg)

再执行

time sudo rpmbuild -bb --define '_topdir /rpmbuild' --noclean --noprep --without ceph_test_package /rpmbuild/SPECS/ceph.spec

注意

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350304.jpg)

中间不能有空行

### 监控部分

编译安装iftop

[http://www.ex-parrot.com/~pdw/iftop/](http://www.ex-parrot.com/~pdw/iftop/)  下载iftop-1.0pre4.tar.gz

或者直接使用链接 [http://www.ex-parrot.com/~pdw/iftop/download/iftop-1.0pre4.tar.gz](http://www.ex-parrot.com/~pdw/iftop/download/iftop-1.0pre4.tar.gz)

tar zxf iftop-1.0pre4.tar.gz

cd iftop-1.0pre4

vim config/config.sub

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350478.jpg)

./configure --build=sw_64-linux-gnu

make && make install

yum install ipmitool mdadm hddtemp

后来到申威官网下载麒麟的ceph的rpm包

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350566.jpg)

rpm -ivh *.rpm

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350580.jpg)

yum install -y python3-remoto python-werkzeug python-cherrypy

yum install -y libradosstriper1-14.2.8-3.ky10.sw_64

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350813.jpg)

rpm -ivh *.rpm --nodeps  直接强制安装

yum install -y iscsi-initiator-utils iscsi-initiator-utils-devel iscsi-initiator-utils-iscsiuio isns-utils-libs libibverbs librdmacm lsof scsi-target-utils target-restore targetcli   ctdb

记得在安装脚本里面正例haproxy

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350908.jpg)

处理 

```
def clean_cache(self):
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350639.jpg)

去掉下面try 

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350979.jpg)

昨晚改到这，有问题，得还原，后面ceph相关的都没清理掉

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350416.jpg)

第二天把上面的return 0改回return 1，

然后操作如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350664.jpg)

vim /opt/vcfs/vcmp-agent/src/agent/controlers/x86/cluster/deploy.py +329

把上图红色框内的部分注释，然后加上pass关键字

重启 vcmp-agent

再清空集群就行了。

注释还原后，清空还是报错

vim /opt/vcfs/vcmp-agent/src/agent/controlers/x86/cluster/deploy.py +147

注释掉yield [service_ctl(srv_name=srv_name, op='stop') for srv_name in srv_list]

2023-8-15 11:07:20后来发现还原之前操作（去掉下面try ）就可以了，所以这些操作就用删除线标注了

监控添加是被，可能是influxdb没启动，

我这里是想用命令启动，然后他添加成功后，再关闭命令启动，然后用systemctl start influxd来qido

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350877.jpg)

2023-8-15 11:37:49

把python改回指向python2

然后安装了 yum install -y python-rbd python2-urllib3

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350049.jpg)

因为在Python 2中，urllib.parse模块被称为urlparse。

vim /usr/lib/python2.7/site-packages/pecan/compat/**init**.py

将

```
import urllib.parse as urlparse  # noqa
from urllib.parse import quote, unquote_plus  # noqa
from urllib.request import urlopen, URLError  # noqa
from html import escape  # noqa
```

替换为

```
from urllib2 import urlopen, URLError
import urlparse
from urllib import quote, unquote_plus
from cgi import escape
```

后来发现这么替换不是个事，

pip2 uninstall pecan

yum install python2-pecan

pip2 install pyOpenSSL

感觉也挺多的，就想着能不能看所有依赖

yum deplist ceph-mgr|grep  py

最后整理得出下面yum install -y python-cherrypy python-pecan python-protobuf python-six python-werkzeug

执行后发现所有的都安装

然后重启ceph-mgr，发现不报错了。

期间主要命令如下：

```
systemctl reset-failed ceph-mgr@node1.service
systemctl restart ceph-mgr@node1.service
tailf /var/log/ceph/ceph-mgr.node1.log
```

终于发现ceph -s是健康的了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350324.jpg)

发现添加文件系统报错

![](https://gitee.com/hxc8/images6/raw/master/img/202407182350542.jpg)

ceph fs rm cephfs --yes-i-really-mean-it

vim /opt/vcfs/vcmp-agent/src/agent/handlers/node/configure.py

加上

```
, check_mount_kill
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351994.jpg)

发现可以清空文件系统了

2023年8月17日13:26:283

vim /opt/vcfs/vcmp-agent/src/agent/handlers/node/shareservice.py +16

添加, Http

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351599.jpg)

iscis，添加访问路径之后，systemctl status tgtd显示如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351845.jpg)

failed to find bstype,rbd

安装下面（估计是需要scsi-target-utils-rbd.sw_64，但是没具体验证）

yum install -y libiscsi.sw_64 lsscsi.sw_64  libiscsi-devel.sw_64 libvirt-daemon-driver-storage-iscsi.sw_64 libvirt-daemon-driver-storage-iscsi-direct.sw_64 libvirt-daemon-driver-storage-scsi.sw_64 open-iscsi.sw_64 open-iscsi-devel.sw_64 scsi-target-utils.sw_64 scsi-target-utils-rbd.sw_64

安装后，systemctl status tgtd

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351858.jpg)

iscsiadm -m discovery -t sendtargets -p 10.200.152.47  

发现OK了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351964.jpg)

好像这里有相关说法，[https://github.com/Statemood/documents/blob/master/ceph/use-iscsi-to-windows.md](https://github.com/Statemood/documents/blob/master/ceph/use-iscsi-to-windows.md)

验证下，在另外一台节点，就是上图执行discovery的节点

执行以下命令登录目标器  iscsiadm -m node -p 10.200.152.47 -l

lsblk  就可以看到多了一个设备

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351911.jpg)

查看session命令 iscsiadm -m node  

执行以下命令，登出应用服务器 iscsiadm -m node -U all

登出后，再lsblk就看不到上面的设备了。