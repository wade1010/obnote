一、前言

> LVS是Linux Virtual Server的简写，意即Linux虚拟服务器，是一个虚拟的服务器集群系统。本项目在1998年5月由章文嵩博士成立，是中国国内最早出现的自由软件项目之一。目前有三种IP负载均衡技术（VS/NAT、VS/TUN和VS/DR），十种调度算法（rrr|wrr|lc|wlc|lblc|lblcr|dh|sh|sed|nq）

> 下面，搭建基于LVS+Keepalived的Nginx高可用负载均衡集群，其中，LVS实现Nginx的负载均衡，但是，简单的LVS不能监控后端节点是否健康，它只是基于具体的调度算法对后端服务节点进行访问。同时，单一的LVS又存在单点故障的风险。在这里，引进了Keepalived，可以实现以下几个功能：

> 检测后端节点是否健康及故障自动切换功能

> 实现LVS本身的高可用

二、准备工作

- 服务器列表

- 所用系统：CentOS7

- 真实web服务器（local-master）：172.16.122.193

- 真实web服务器（local-slave2）：172.16.122.195

- Master负载均衡服务器：172.16.122.188

- Backup负载均衡服务器：172.16.122.189

- 安装基础软件

- Nginx

使用docker 部署，省略

- LVS

CentOS7已经集成了LVS的核心，所以默认只需要安装LVS的管理工具ipvsadm就可以了

```javascript
yum -y install ipvsadm
```

- Keepalived

```javascript
yum -y install keepalived
```

三、LVS负载均衡服务器配置

```javascript
[root@s2 /]# ipvsadm -A -t 172.16.122.188:80 -s rr
[root@s2 /]# ipvsadm -a -t 172.16.122.188:80 -r 172.16.122.193 -m
[root@s2 /]# ipvsadm -a -t 172.16.122.188:80 -r 172.16.122.195 -m
[root@s2 /]# ipvsadm -S
-A -t s2:http -s rr
-a -t s2:http -r 172.16.122.193:http -m -w 1
-a -t s2:http -r 172.16.122.195:http -m -w 1

-A 添加虚拟服务
-a 添加一个真是的主机到虚拟服务
-S 保存
-s 选择调度方法
rr 轮训调度
-m 网络地址转换NAT
```

四、测试验证基于LVS的负载均衡

此步骤暂不涉及Keepalived，因此是无法故障自动切换的

```javascript
[root@s2 /]# curl 172.16.122.188:80
hello local-slave2
[root@s2 /]# curl 172.16.122.188:80
hello local-master
[root@s2 /]# curl 172.16.122.188:80
hello local-slave2
[root@s2 /]# curl 172.16.122.188:80
hello local-master
五、LVS+Keepalived
```



> 此步骤使用LVS+Keepalived即可做到故障的自动检测及切换



5.1、配置Keepalived



/etc/keepalived/keepalived.conf

```javascript

! Configuration File for keepalived

global_defs {
    notification_email {
        acassen@firewall.loc        #设置报警邮件地址，可以设置多个，每行一个。
        failover@firewall.loc       #需开启本机的sendmail服务
        sysadmin@firewall.loc
    }
    notification_email_from Alexandre.Cassen@firewall.loc  #设置邮件的发送地址
    smtp_server 127.0.0.1           #设置smtp server地址
    smtp_connect_timeout 30         #设置连接smtp server的超时时间
    router_id LVS_DEVEL             #表示运行keepalived服务器的一个标识。发邮件时显示在邮件主题的信息
}

vrrp_instance VI_1 {
    state MASTER              #指定keepalived的角色，MASTER表示此主机是主服务器，BACKUP表示此主机是备用服务器
    interface eno16777736     #指定HA监测网络的接口
    virtual_router_id 51      #虚拟路由标识，这个标识是一个数字，同一个vrrp实例使用唯一的标识。即同一vrrp_instance下，MASTER和BACKUP必须是一致的
    priority 100              #定义优先级，数字越大，优先级越高，在同一个vrrp_instance下，MASTER的优先级必须大于BACKUP的优先级
    advert_int 1              #设定MASTER与BACKUP负载均衡器之间同步检查的时间间隔，单位是秒
    authentication {          #设置验证类型和密码
        auth_type PASS        #设置验证类型，主要有PASS和AH两种
        auth_pass 1111        #设置验证密码，在同一个vrrp_instance下，MASTER与BACKUP必须使用相同的密码才能正常通信
    }
    virtual_ipaddress {       #设置虚拟IP地址，可以设置多个虚拟IP地址，每行一个
        172.16.122.100
    }
}

virtual_server 172.16.122.100 80 {     #设置虚拟服务器，需要指定虚拟IP地址和服务端口，IP与端口之间用空格隔开
    delay_loop 6                        #设置运行情况检查时间，单位是秒
    lb_algo rr                          #设置负载调度算法，这里设置为rr，即轮询算法
    lb_kind DR                          #设置LVS实现负载均衡的机制，有NAT、TUN、DR三个模式可选
    nat_mask 255.255.255.0
    persistence_timeout 0               #会话保持时间，单位是秒。这个选项对动态网页是非常有用的，为集群系统中的session共享提供了一个很好的解决方案。
                                        #有了这个会话保持功能，用户的请求会被一直分发到某个服务节点，直到超过这个会话的保持时间。
                                        #需要注意的是，这个会话保持时间是最大无响应超时时间，也就是说，用户在操作动态页面时，如果50秒内没有执行任何操作
                                        #那么接下来的操作会被分发到另外的节点，但是如果用户一直在操作动态页面，则不受50秒的时间限制
    protocol TCP                        #指定转发协议类型，有TCP和UDP两种

    real_server 172.16.122.193 80 {     #配置服务节点1，需要指定real server的真实IP地址和端口，IP与端口之间用空格隔开
        weight 1                        #配置服务节点的权值，权值大小用数字表示，数字越大，权值越高，设置权值大小可以为不同性能的服务器
                                        #分配不同的负载，可以为性能高的服务器设置较高的权值，而为性能较低的服务器设置相对较低的权值，这样才能合理地利用和分配系统资源
        TCP_CHECK {                     #realserver的状态检测设置部分，单位是秒
            connect_timeout 3           #表示3秒无响应超时
            nb_get_retry 3              #表示重试次数
            delay_before_retry 3        #表示重试间隔
            connect_port 80
        }
    }
    real_server 172.16.122.195 80 {
        weight 1

        TCP_CHECK {
            connect_timeout 3
            nb_get_retry 3
            delay_before_retry 3
            connect_port 80
        }
    }
}
```



另外一个台备用服务器上Keepavlied的配置类似，只是把MASTER改为BACKUP，把priority设置为比MASTER低



5.2、两台真实web服务器上为lo:0绑定VIP地址、ARP广播



在两台真实web服务器上编写以下脚本文件rs.sh

```javascript
#!/bin/bash
#description: Config realserver

VIP=172.16.122.100

/etc/rc.d/init.d/functions

case "$1" in
start)
       /sbin/ifconfig lo:0 $VIP netmask 255.255.255.255 broadcast $VIP
       /sbin/route add -host $VIP dev lo:0
       echo "1" >/proc/sys/net/ipv4/conf/lo/arp_ignore
       echo "2" >/proc/sys/net/ipv4/conf/lo/arp_announce
       echo "1" >/proc/sys/net/ipv4/conf/all/arp_ignore
       echo "2" >/proc/sys/net/ipv4/conf/all/arp_announce
       sysctl -p >/dev/null 2>&1
       echo "RealServer Start OK"
       ;;
stop)
       /sbin/ifconfig lo:0 down
       /sbin/route del $VIP >/dev/null 2>&1
       echo "0" >/proc/sys/net/ipv4/conf/lo/arp_ignore
       echo "0" >/proc/sys/net/ipv4/conf/lo/arp_announce
       echo "0" >/proc/sys/net/ipv4/conf/all/arp_ignore
       echo "0" >/proc/sys/net/ipv4/conf/all/arp_announce
       echo "RealServer Stoped"
       ;;
*)
       echo "Usage: $0 {start|stop}"
       exit 1
esac

exit 0
```



在两台真实web服务器上分别执行脚本

```javascript
./rs.sh start
```

5.3、启动Keepalived

```javascript
#Keepalived 相关操作命令

#启动Keepalived
systemctl start keepalived

#关闭Keepalived
systemctl start keepalived

#重启Keepalived
systemctl restart keepalived

#查看状态Keepalived
systemctl status keepalived

```

- 通过 ipvsadm -L 命令可以查看VIP是否已经成功映射到两台real服务器，如果发现有问题，可以通过tail -f /var/log/message查看错误原因



```javascript
[root@s2 keepalived]# ipvsadm -L
IP Virtual Server version 1.2.1 (size=4096)
Prot LocalAddress:Port Scheduler Flags
  -> RemoteAddress:Port           Forward Weight ActiveConn InActConn
TCP  172.16.122.100:http rr
  -> 172.16.122.193:http          Route   1      0          0
  -> 172.16.122.195:http          Route   1      0          0
```




5.4、测试负载均衡功能

```javascript
[root@s2 keepalived]# curl 172.16.122.100
hello local-master
[root@s2 keepalived]# curl 172.16.122.100
hello local-slave2
[root@s2 keepalived]# curl 172.16.122.100
hello local-master
[root@s2 keepalived]# curl 172.16.122.100
hello local-slave2
[root@s2 keepalived]# curl 172.16.122.100
hello local-master
[root@s2 keepalived]# curl 172.16.122.100
hello local-slave2
```

5.5、测试Keepalived的健康检测自动切换（异常剔除，恢复则重新加入）功能

1、停掉local-slave2的Nginx，然后在MASTER负载均衡服务器上可以到看VIP映射关系中自动剔除172.16.122.195

```javascript
[root@local-slave2 home]# docker stop nginx
nginx
```



```javascript
[root@s2 keepalived]# ipvsadm -L
IP Virtual Server version 1.2.1 (size=4096)
Prot LocalAddress:Port Scheduler Flags
  -> RemoteAddress:Port           Forward Weight ActiveConn InActConn
TCP  172.16.122.100:http rr
  -> 172.16.122.193:http          Route   1      0          0
#可看到VIP映射关系中已经剔除了ip为172.16.122.195的机器
```



2、恢复local-slave2的Nginx，然后在MASTER负载均衡服务器上可以到看VIP映射关系中自动加入172.16.122.195



```javascript
[root@local-slave2 home]# docker start nginx
nginx
```



```javascript
[root@s2 keepalived]# ipvsadm -L
IP Virtual Server version 1.2.1 (size=4096)
Prot LocalAddress:Port Scheduler Flags
  -> RemoteAddress:Port           Forward Weight ActiveConn InActConn
TCP  172.16.122.100:http rr
  -> 172.16.122.193:http          Route   1      0          0
  -> 172.16.122.195:http          Route   1      0          0
```

六、问题总结

1、内核开启IP转发和允许非本地IP绑定功能，如果是使用LVS的DR模式还需设置两个arp相关的参数

```javascript
#开启IP转发功能
sysctl -w net.ipv4.ip_forward=1
#开启允许绑定非本机的IP
sysctl -w net.ipv4.ip_nonlocal_bind = 1

```

2、Keepalived的配置文件中需要注意几点

- interface eno16777736：这里的eno16777736是我本地网卡名称，想要查看自己网卡名称的话，在/etc/sysconfig/network-scripts/ifcfg-e（敲下TAB即可查看）

- persistence_timeout 0：指的是在一定的时间内来自同一IP的连接将会被转发到同一realserver中。而不是严格意义上的轮询。默认为50s，因此在测试负载均衡是否可以正常轮询时，最好先把值设置为0，方便查看

