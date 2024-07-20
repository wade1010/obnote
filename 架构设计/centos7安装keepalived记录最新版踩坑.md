#### 环境
VMware虚拟机1： 192.168.1.39

VMware虚拟机2： 192.168.1.40

mysql版本都是5.7.25, for linux
#### 下载
- 官网下载：http://www.keepalived.org/download.html  找到想要下载的版本地址
```shell
wget http://www.keepalived.org/software/keepalived-2.0.14.tar.gz
tar zxf keepalived* &&cd keepalived*
```

#### 安装
##### 两个服务器上面都得安装，安装过程中会有几个包得安装
```
The following build packages are needed:
        make
        autoconf automake (to build from git source tree rather than tarball)
The following libraries need to be installed:
        openssl-devel libnl3-devel ipset-devel iptables-devel
For magic file identification support:
        file-devel
For SNMP support:
        net-snmp-devel
For DBUS support:
        glib2-devel
For JSON support:
        json-c-devel
For PCRE support
        pcre2-devel
For nftables support
        libnftnl-devel libmnl-devel

For building the documentation, the following packages need to be installed:
        Fedora: python-sphinx (will pull in: python2-sphinx_rtd_theme)
        CentOS-and-friends: python-sphinx epel-release python-sphinx_rtd_theme
   For latex or pdf files, the following are also needed:
        Fedora: latexmk python-sphinx-latex
        CentOS-and-friends: latexmk texlive texlive-titlesec texlive-framed texlive-threeparttable texlive-wrapfig texlive-multirow

```
```
yum install -y make autoconf automake
yum install -y iptables-devel
yum install -y file-devel
yum install -y net-snmp-devel
yum install -y glib2-devel
yum install -y json-c-devel pcre2-devel libnftnl-devel libmnl-devel
yum install -y python-sphinx epel-release python-sphinx_rtd_theme
yum install -y latexmk texlive texlive-titlesec texlive-framed texlive-threeparttable texlive-wrapfig texlive-multirow
```
### ==折腾了好久，最新版的不行。准备降级安装试试==

```
wget http://www.keepalived.org/software/keepalived-1.2.19.tar.gz
tar zxf keepalived* &&cd keepalived*
./configure --prefix=/usr/local/keepalived  --sbindir=/usr/sbin/ --sysconfdir=/etc/ --mandir=/usr/local/share/man/
make
make install
```
```shell
[root@vmware39 keepalived-1.2.19]# make
....
....
Make complete
[root@vmware39 keepalived-1.2.19]# make install
make -C keepalived install
make[1]: Entering directory `/root/keepalived-1.2.19/keepalived'
install -d /usr/sbin
install -m 700 ../bin/keepalived /usr/sbin/
install -d /etc/rc.d/init.d
install -m 755 etc/init.d/keepalived.init /etc/rc.d/init.d/keepalived
install -d /etc/sysconfig
install -m 644 etc/init.d/keepalived.sysconfig /etc/sysconfig/keepalived
install -d /etc/keepalived/samples
install -m 644 etc/keepalived/keepalived.conf /etc/keepalived/
install -m 644 ../doc/samples/* /etc/keepalived/samples/
install -d /usr/local/share/man/man5
install -d /usr/local/share/man/man8
install -m 644 ../doc/man/man5/keepalived.conf.5 /usr/local/share/man/man5
install -m 644 ../doc/man/man8/keepalived.8 /usr/local/share/man/man8
make[1]: Leaving directory `/root/keepalived-1.2.19/keepalived'
make -C genhash install
make[1]: Entering directory `/root/keepalived-1.2.19/genhash'
install -d /usr/local/keepalived/bin
install -m 755 ../bin/genhash /usr/local/keepalived/bin/
install -d /usr/local/share/man/man1
install -m 644 ../doc/man/man1/genhash.1 /usr/local/share/man/man1
make[1]: Leaving directory `/root/keepalived-1.2.19/genhash'

```
>另外一台机器安装了。发现不需要装上面那么多包。可能我那台机器已经装了必须的几个包
```
wget http://www.keepalived.org/software/keepalived-1.2.19.tar.gz
tar zxf keepalived* &&cd keepalived*
./configure --prefix=/usr/local/keepalived  --sbindir=/usr/sbin/ --sysconfdir=/etc/ --mandir=/usr/local/share/man/
make
make install
#如果需要可以添加开机自启
chkconfig --add keepalived
chkconfig keepalived on
```
#### 创建配置文件（两台都需要）
```shell
cd /etc/keepalived
cp keepalived.conf keepalived.conf.bak
echo ''>keepalived.conf
vim keepalived.conf
```
粘贴下面的配置
```config
! Configuration File for keepalived
#简单的头部，这里主要可以做邮件通知报警等的设置，此处就暂不配置了；
global_defs {
        notificationd LVS_DEVEL
}
#预先定义一个脚本，方便后面调用，也可以定义多个，方便选择；
vrrp_script chk_haproxy {
    script "/etc/keepalived/chk.sh"  #具体脚本路径
    interval 2  #脚本循环运行间隔
}
#VRRP虚拟路由冗余协议配置
vrrp_instance VI_1 {   #VI_1 是自定义的名称；
    state BACKUP    #MASTER表示是一台主设备，BACKUP表示为备用设备【我们这里因为设置为开启不抢占，所以都设置为备用】
    nopreempt      #开启不抢占
    interface eth0   #指定VIP需要绑定的物理网卡，这里默认是eht0但是我的是ens33，如果报错了就改成自己物理网卡名字
    virtual_router_id 11   #VRID虚拟路由标识，也叫做分组名称，该组内的设备需要相同
    priority 130   #定义这台设备的优先级 1-254；开启了不抢占，所以此处优先级必须高于另一台

    advert_int 1   #生存检测时的组播信息发送间隔，组内一致
    authentication {    #设置验证信息，组内一致
        auth_type PASS   #有PASS 和 AH 两种，常用 PASS
        auth_pass 123456    #密码
    }
    virtual_ipaddress {
        192.168.1.222    #指定VIP地址，组内一致，可以设置多个IP,但是不能存在
        192.168.1.223
    }
    track_script {    #使用在这个域中使用预先定义的脚本，上面定义的
        chk_haproxy   
    }

    notify_backup "xxxxx你的脚本"   #表示当切换到backup状态时,要执行的脚本
    notify_fault "xxxx你的脚本"     #故障时执行的脚本
}
```
最好去掉注释，下面提供无注释版，方便复制
```config
! Configuration File for keepalived

global_defs {
        notificationd LVS_DEVEL
}

vrrp_script chk_haproxy {
    script "/etc/keepalived/chk.sh"
    interval 2
}

vrrp_instance VI_1 {
    state BACKUP
    nopreempt
    interface eth0
    virtual_router_id 11
    priority 130

    advert_int 1 
    authentication {
        auth_type PASS
        auth_pass 123456
    }
    virtual_ipaddress {
        192.168.1.222
        192.168.1.223
    }
    track_script {
        chk_haproxy   
    }
}
```
另外一台机器配置文件与上面的几乎一样，仅仅改变priority 100【只需要比上面的小即可】

>配置说明详解
```
全局定义块

1、email通知（notification_email、smtp_server、smtp_connect_timeout）：用于服务有故障时发送邮件报警，可选项，不建议用。需要系统开启sendmail服务，建议用第三独立监控服务，如用nagios全面监控代替。 
2、lvs_id：lvs负载均衡器标识，在一个网络内，它的值应该是唯一的。 
3、router_id：用户标识本节点的名称，通常为hostname 
4、花括号｛｝：用来分隔定义块，必须成对出现。如果写漏了，keepalived运行时不会得到预期的结果。由于定义块存在嵌套关系，因此很容易遗漏结尾处的花括号，这点需要特别注意。

VRRP实例定义块

vrrp_sync_group：同步vrrp级，用于确定失败切换（FailOver）包含的路由实例个数。即在有2个负载均衡器的场景，一旦某个负载均衡器失效，需要自动切换到另外一个负载均衡器的实例是哪
group：至少要包含一个vrrp实例，vrrp实例名称必须和vrrp_instance定义的一致
vrrp_instance：vrrp实例名 
1> state：实例状态，只有MASTER 和 BACKUP两种状态，并且需要全部大写。抢占模式下，其中MASTER为工作状态，BACKUP为备用状态。当MASTER所在的服务器失效时，BACKUP所在的服务会自动把它的状态由BACKUP切换到MASTER状态。当失效的MASTER所在的服务恢复时，BACKUP从MASTER恢复到BACKUP状态。 
2> interface：对外提供服务的网卡接口，即VIP绑定的网卡接口。如：eth0，eth1。当前主流的服务器都有2个或2个以上的接口（分别对应外网和内网），在选择网卡接口时，一定要核实清楚。 
3>mcast_src_ip：本机IP地址 
4>virtual_router_id：虚拟路由的ID号，每个节点设置必须一样，可选择IP最后一段使用，相同的 VRID 为一个组，他将决定多播的 MAC 地址。 
5> priority：节点优先级，取值范围0～254，MASTER要比BACKUP高 
6>advert_int：MASTER与BACKUP节点间同步检查的时间间隔，单位为秒 
7>lvs_sync_daemon_inteface：负载均衡器之间的监控接口,类似于 HA HeartBeat的心跳线。但它的机制优于 Heartbeat，因为它没有“裂脑”这个问题,它是以优先级这个机制来规避这个麻烦的。在 DR 模式中，lvs_sync_daemon_inteface与服务接口interface使用同一个网络接口 
8> authentication：验证类型和验证密码。类型主要有 PASS、AH 两种，通常使用PASS类型，据说AH使用时有问题。验证密码为明文，同一vrrp实例MASTER与BACKUP使用相同的密码才能正常通信。 
9>smtp_alert：有故障时是否激活邮件通知 
10>nopreempt：禁止抢占服务。默认情况，当MASTER服务挂掉之后，BACKUP自动升级为MASTER并接替它的任务，当MASTER服务恢复后，升级为MASTER的BACKUP服务又自动降为BACKUP，把工作权交给原MASTER。当配置了nopreempt，MASTER从挂掉到恢复，不再将服务抢占过来。 
11>virtual_ipaddress：虚拟IP地址池，可以有多个IP，每个IP占一行，不需要指定子网掩码。注意：这个IP必须与我们的设定的vip保持一致。
```
>主备切换时执行的脚本
```
1.notify_master“想要执行的脚本路径” #表示当切换到master状态时，要执行的脚本

2.notify_backup “想要执行的脚本路径”#表示当切换到backup状态时，要执行的脚本

3.notify_fault“想要执行的脚本路径”#表示切换出现故障时要执行的脚本
```

#### 创建脚本文件（两台都需要）
检测haproxy有没有发生故障，发生故障则将keepalived停掉，让出vip

vim /etc/keepalived/chk.sh   粘贴下面代码进去
```
#!/bin/bash
#
if [ $(ps -C haproxy --no-header | wc -l) -eq 0 ]; then
       service keepalived stop
fi
```
添加执行权限
```
chmod +x /etc/keepalived/chk.sh
```
#### 启动keepalived
```
service keepalived start
```
```
[root@vmware39 keepalived]# ps -ef|grep keep
root      77236      1  0 21:26 ?        00:00:00 keepalived -D
root      77238  77236  0 21:26 ?        00:00:00 keepalived -D
root      77239  77236  0 21:26 ?        00:00:00 keepalived -D
root      77261   8674  0 21:28 pts/0    00:00:00 grep --color=auto keep
```
到此安装成功啦！

#### 查看网卡信息

```
ip a|grep 111
```

居然没发现，继续找原因


```
journalctl -u keepalived
```
```
ar 27 21:38:39 vmware39 systemd[1]: Started SYSV: Start and stop Keepalived.
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP 192.168.1.39 added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP 172.18.0.1 added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP 172.17.0.1 added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP 2409:8a00:602b:35f0:20c:29ff:fe5d:57e5 added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP fe80::20c:29ff:fe5d:57e5 added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP fe80::42:a6ff:fe17:6313 added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP fe80::42:17ff:fe62:84e6 added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP fe80::a8c7:a7ff:fe3a:370b added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP fe80::683d:93ff:fee4:c1f0 added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP fe80::683f:3fff:fe3e:cc6b added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP fe80::5c90:e7ff:fef6:4fd added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Netlink reflector reports IP fe80::6c0c:cbff:fe01:ee3e added
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Registering Kernel netlink reflector
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Registering Kernel netlink command channel
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Registering gratuitous ARP shared channel
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Opening file '/etc/keepalived/keepalived.conf'.
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Cant find interface eth0 for vrrp_instance VI_1 !!!
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Configuration error: VRRP definition must belong to an interface
Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Default interface eth0 does not exist and no interface specified. Skip VRRP address.
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Initializing ipvs 2.6
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Netlink reflector reports IP 192.168.1.39 added
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Netlink reflector reports IP 172.18.0.1 added
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Netlink reflector reports IP 172.17.0.1 added
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Netlink reflector reports IP 2409:8a00:602b:35f0:20c:29ff:fe5d:57e5 added
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Netlink reflector reports IP fe80::20c:29ff:fe5d:57e5 added
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Netlink reflector reports IP fe80::42:a6ff:fe17:6313 added
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Netlink reflector reports IP fe80::42:17ff:fe62:84e6 added
Mar 27 21:38:39 vmware39 Keepalived_healthcheckers[77444]: Netlink reflector reports IP fe80::a8c7:a7ff:fe3a:370b added

```
上面日志主要信息如下：

>==Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Opening file '/etc/keepalived/keepalived.conf'.==

>==Mar 27 21:38:39 vmware39 Keepalived_vrrp[77445]: Cant find interface eth0 for vrrp_instance VI_1 !!!==
eth0网卡接口没有。
```
[root@vmware39 keepalived]# ifconfig
ens33: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 192.168.1.39  netmask 255.255.255.0  broadcast 192.168.1.255
        inet6 fe80::20c:29ff:fe5d:57e5  prefixlen 64  scopeid 0x20<link>
        inet6 2409:8a00:602b:35f0:20c:29ff:fe5d:57e5  prefixlen 64  scopeid 0x0<global>
        ether 00:0c:29:5d:57:e5  txqueuelen 1000  (Ethernet)
        RX packets 186914  bytes 181906336 (173.4 MiB)
        RX errors 0  dropped 0  overruns 0  frame 0
        TX packets 185830  bytes 20976844 (20.0 MiB)
        TX errors 0  dropped 0 overruns 0  carrier 0  collisions 0

```
其中没有eth0,所以我把keepalived.conf中的'interface'改成了ens33之后重启
```
service keepalived restart
```
显示成功后等10秒左右或者查看启动日志
```
Mar 27 21:54:25 vmware39 Keepalived_vrrp[78236]: VRRP_Instance(VI_1) Sending gratuitous ARPs on ens33 for 192.168.1.222
Mar 27 21:54:25 vmware39 Keepalived_healthcheckers[78235]: Netlink reflector reports IP 192.168.1.222 added
```
看到添加之后就可以了，再看一次
```
[root@vmware39 keepalived]# ip a|grep 111
    inet 192.168.1.222/32 scope global ens33
    inet 192.168.1.223/32 scope global ens33

```
那备机启动后，再执行==journalctl -ex==   可以看到如下日志

>VRRP_Instance(VI_1) Entering BACKUP STATE

#### 测试
关闭优先级别较高的(本文配置priority 130的机器)

service keepalived stop

在到另外一台机器上查看日志，你会看到如下结果
```
Mar 27 23:10:53 vmware40 Keepalived_healthcheckers[14763]: Using LinkWatch kernel netlink reflector...
Mar 27 23:10:53 vmware40 Keepalived_vrrp[14764]: VRRP_Instance(VI_1) Entering BACKUP STATE
Mar 27 23:10:53 vmware40 Keepalived_vrrp[14764]: VRRP sockpool: [ifindex(2), proto(112), unicast(0), fd(10,11)]
Mar 27 23:12:19 vmware40 Keepalived_vrrp[14764]: VRRP_Instance(VI_1) Transition to MASTER STATE
Mar 27 23:12:20 vmware40 Keepalived_vrrp[14764]: VRRP_Instance(VI_1) Entering MASTER STATE
Mar 27 23:12:20 vmware40 Keepalived_vrrp[14764]: VRRP_Instance(VI_1) setting protocol VIPs.
Mar 27 23:12:20 vmware40 Keepalived_vrrp[14764]: VRRP_Instance(VI_1) Sending gratuitous ARPs on ens33 for 192.168.111.
Mar 27 23:12:20 vmware40 Keepalived_vrrp[14764]: VRRP_Instance(VI_1) Sending gratuitous ARPs on ens33 for 192.168.111.
Mar 27 23:12:20 vmware40 Keepalived_healthcheckers[14763]: Netlink reflector reports IP 192.168.1.222 added
Mar 27 23:12:20 vmware40 Keepalived_healthcheckers[14763]: Netlink reflector reports IP 192.168.1.223 added
```
自动添加了ip

在查看下网卡信息
```
[root@vmware40 keepalived]# ip a|grep 111
    inet 192.168.1.222/32 scope global ens33
    inet 192.168.1.223/32 scope global ens33
```

#### 错误
我闲着没事把 virtual_ipaddress里面的改成了电脑的ip,重启keepalived后，ssh远程链接到虚拟机自动断开，并且重新ssh连接不上，只能从VMware上修改配置到其他不存在的ip，重启才恢复ssh连接



>tcpdump -i ens33 vrrp -n