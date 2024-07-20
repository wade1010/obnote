#### 环境
VMware虚拟机1： 192.168.1.39

VMware虚拟机2： 192.168.1.40

keepalived-1.2.19

MySQL5.7.25

docker1.13.1

#### 安装keepalived（两台机器都要安装）
[安装keepalived记录](https://blog.csdn.net/wade1010/article/details/88858050)
#### docker安装haproxy（两台机器都要安装）
[docker安装haproxy](https://blog.csdn.net/wade1010/article/details/88848483)

[配置主主复制](https://blog.csdn.net/wade1010/article/details/88842641)

keepalived和haproxy和mysql就可以进行下面的步骤了

### 修改keepalived配置
##### master

>vim /etc/keepalived/keepalived.conf

加入check和notify脚本,之前的配置也有少许改动，主要是设置了个master开启抢占

``` 
! Configuration File for keepalived

global_defs {
   router_id LVS_DEVEL
}
vrrp_script chk_haproxy {
    script "killall -0 haproxy"
    weight -2 
    interval 2 
}
vrrp_script chk_mantaince_down {#用于人工让出vip
   script "[[ -f /etc/keepalived/down ]] && exit 1 || exit 0"
   interval 2
   weight -2
}
vrrp_instance VI_1 {
    state MASTER
    #state BACKUP
    #nopreempt
    interface ens33
    virtual_router_id 51
    priority 101 #backup 设置为100
    advert_int 1
    authentication {
        auth_type PASS
        auth_pass 1111
    }
    virtual_ipaddress {
        192.168.1.222 
    }
    track_script {
        chk_haproxy
	chk_mantaince_down
    }
    notify_master "/opt/docker/haproxy/script/notify.sh master"
    notify_backup "/opt/docker/haproxy/script/notify.sh backup" 
    notify_fault "/opt/docker/haproxy/script/notify.sh fault"
}

```
##### backup

>vim /etc/keepalived/keepalived.conf

```
! Configuration File for keepalived

global_defs {
   router_id LVS_DEVEL
}
vrrp_script chk_haproxy {
    script "killall -0 haproxy"#这里也可改成检查失败就关闭本机keepalived让出vip
    weight -2 #失败就将priority-2 小于backup的priority，让出vip
    interval 2 
}
vrrp_script chk_mantaince_down {
   script "[[ -f /etc/keepalived/down ]] && exit 1 || exit 0"
   interval 2
   weight -2 #exit 1 就将priority-2 小于backup的priority，让出vip
}
vrrp_instance VI_1 {
    state BACKUP
    interface ens33
    virtual_router_id 51
    priority 100
    advert_int 1
    authentication {
        auth_type PASS
        auth_pass 1111
    }
    virtual_ipaddress {
        192.168.1.222 
    }
    track_script {
        chk_haproxy
	chk_mantaince_down
    }
    notify_master "/opt/docker/haproxy/script/notify.sh master"
    notify_backup "/opt/docker/haproxy/script/notify.sh backup" 
    notify_fault "/opt/docker/haproxy/script/notify.sh fault"
}
```

##### 各服务器创建 notify .sh

>vim /opt/docker/haproxy/script/notify.sh

==脚本一定要加上执行权限 chmod +x /opt/docker/haproxy/script/notify.sh==

```
#!/bin/bash
# 
case "$1" in
    master)
        docker start haproxy1 #这里也可以增加判断，存在就不用执行了
        exit 0
    ;;
    backup)
        docker restart haproxy1 
        exit 0
    ;;
    fault)
        docker stop haproxy1 
        exit 0
    ;;
    *)
        echo 'Usage: `basename $0` {master|backup|fault}'
        exit 1
    ;;
esac
```
其中haproxy1是我用docker启动haproxy容器时起的名字

##### 测试
- 关闭master的haproxy容器docker stop haproxy1

master日志变化,先让出了VIP，然后通过notify脚本重启了haproxy，check通过又抢占会VIP
```
Mar 28 19:05:31 vmware39 Keepalived_vrrp[73257]: VRRP_Script(chk_haproxy) failed
Mar 28 19:05:32 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) Received higher prio advert
Mar 28 19:05:32 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) Entering BACKUP STATE
Mar 28 19:05:32 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) removing protocol VIPs.
Mar 28 19:05:32 vmware39 Keepalived_vrrp[73257]: Opening script file /opt/docker/haproxy/script/notify.sh
Mar 28 19:05:32 vmware39 Keepalived_healthcheckers[73256]: Netlink reflector reports IP 192.168.1.222 removed
.....
.....
Mar 28 19:05:33 vmware39 Keepalived_vrrp[73257]: VRRP_Script(chk_haproxy) succeeded
Mar 28 19:05:34 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) forcing a new MASTER election
Mar 28 19:05:34 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) forcing a new MASTER election
Mar 28 19:05:35 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) Transition to MASTER STATE
Mar 28 19:05:36 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) Entering MASTER STATE
Mar 28 19:05:36 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) setting protocol VIPs.
Mar 28 19:05:36 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) Sending gratuitous ARPs on ens33 for 192.168.1.222
Mar 28 19:05:36 vmware39 Keepalived_vrrp[73257]: Opening script file /opt/docker/haproxy/script/notify.sh
Mar 28 19:05:36 vmware39 Keepalived_healthcheckers[73256]: Netlink reflector reports IP 192.168.1.222 added
Mar 28 19:05:41 vmware39 Keepalived_vrrp[73257]: VRRP_Instance(VI_1) Sending gratuitous ARPs on ens33 for 192.168.1.222

```
backup日志变化，先获得VIP，再让出VIP
```
Mar 28 19:05:33 vmware40 Keepalived_vrrp[61414]: VRRP_Instance(VI_1) Transition to MASTER STATE
Mar 28 19:05:34 vmware40 Keepalived_vrrp[61414]: VRRP_Instance(VI_1) Entering MASTER STATE
Mar 28 19:05:34 vmware40 Keepalived_vrrp[61414]: VRRP_Instance(VI_1) setting protocol VIPs.
Mar 28 19:05:34 vmware40 Keepalived_vrrp[61414]: VRRP_Instance(VI_1) Sending gratuitous ARPs on ens33 for 192.168.1.222
Mar 28 19:05:34 vmware40 Keepalived_vrrp[61414]: Opening script file /opt/docker/haproxy/script/notify.sh
Mar 28 19:05:34 vmware40 Keepalived_healthcheckers[61413]: Netlink reflector reports IP 192.168.1.222 added
Mar 28 19:05:34 vmware40 Keepalived_vrrp[61414]: VRRP_Instance(VI_1) Received higher prio advert
Mar 28 19:05:34 vmware40 Keepalived_vrrp[61414]: VRRP_Instance(VI_1) Entering BACKUP STATE
Mar 28 19:05:34 vmware40 Keepalived_vrrp[61414]: VRRP_Instance(VI_1) removing protocol VIPs.
Mar 28 19:05:34 vmware40 Keepalived_vrrp[61414]: Opening script file /opt/docker/haproxy/script/notify.sh
Mar 28 19:05:34 vmware40 Keepalived_healthcheckers[61413]: Netlink reflector reports IP 192.168.1.222 removed

```

---
后来又试验了下双backup
发现下面的配置，既能在haproxy容器关闭后让出VIP，又能在keepalived关闭后让出vip。感觉比上面的还要好。

```
cat vim keepalived.conf
cat: vim: No such file or directory
! Configuration File for keepalived

global_defs {
   router_id LVS_DEVEL
}
vrrp_script chk_haproxy {
    script "killall -0 haproxy"
    weight -2 
    interval 2 
}
vrrp_script chk_mantaince_down {
   script "[[ -f /etc/keepalived/down ]] && exit 1 || exit 0"
   interval 2
   weight -2
}
vrrp_instance VI_1 {
    state BACKUP
    interface ens33
    virtual_router_id 51
    priority 100
    advert_int 1
    authentication {
        auth_type PASS
        auth_pass 1111
    }
    virtual_ipaddress {
        192.168.1.222 
    }
    track_script {
        chk_haproxy
	chk_mantaince_down
    }
    notify_master "/opt/docker/haproxy/script/notify.sh master"
    notify_backup "/opt/docker/haproxy/script/notify.sh backup" 
    notify_fault "/opt/docker/haproxy/script/notify.sh fault"
}
```
而且两台电脑的配置一模一样

    state BACKUP #都为backup
    priority 100 #优先级都一样
    
priority我改成不一样过，关闭keepalived肯定是可以让出VIP的。但是开始的master在haproxy挂了后能让出VIP，另外一台服务器获得VIP后，再关闭haproxy，这台机器却让不出VIP。我的chk_haproxy里面检查失败，weight都会减2，理论另外一台服务器获得VIP后，haproxy挂了，应该weight会比老的master的weight低啊。这样就达到让出VIP。但是事实没这样，也不知道是不是哪里弄错了。