#### 环境
VMware虚拟机1： 192.168.1.39

VMware虚拟机2： 192.168.1.40

keepalived-1.2.19
#### keepalived安装
可以查看上一篇文章[centos7安装keepalived记录最新版踩坑](https://blog.csdn.net/wade1010/article/details/88858050) 
==这个文章记录里面,之前的配置是有问题的，后来我编辑了下，改对了。具体改了哪里，在下面"问题"中说明==
# 简单版本，试试keepalived的漂移
#### docker安装nginx（两台机器都要装）
> docker pull nginx

> docker run -p 8080:80 -d --name mynginx nginx

修改nginx 默认访问页面index.html  加上当前机器的ip地址，这样便于在浏览器访问时定位到是哪台机器在提供服务。
很多docker镜像里面是没有vi的。这里采用下面办法，先从容器cp出来->修改->cp回容器

- docker cp mynginx:/usr/share/nginx/html/index.html .
- vim index.html
- 加上本机ip
- docker cp index.html mynginx:/usr/share/nginx/html
- docker restart mynginx
- 单独打开服务器192.168.1.39:8080和192.168.1.40:8080都是能访问的。
#### 问题
排查问题可以看下这篇文章 [keepalived的vip无法ping通排查过程](https://blog.csdn.net/wade1010/article/details/88863780)
#### 测试
- 打开http://192.168.1.222:8080/
- 看到上面显示的ip是"IP:192.168.1.39"
- 关闭1.39上的keepalived服务
- 再打开打开http://192.168.1.222:8080/
- 看到上面显示的ip是"IP:192.168.1.40"
- 再重启1.39上的keepalived服务
- 再打开打开http://192.168.1.222:8080/
- 如果你配置的时候开启了抢占VIP，那就会切换回master,如果没开启抢占，就不会改变
# 高可用版本(在简单版本基础上)
#### 拷贝配置
>mkdir -p /opt/docker/nginx/config

>mkdir -p /opt/docker/nginx/logs

>mkdir -p /opt/docker/nginx/html

>可以拷贝之前mynginx容器中的index.html或者自己创建一个index.html到/opt/docker/nginx/html

>docker cp mynginx:/etc/nginx/nginx.conf /opt/docker/nginx/config

>docker cp mynginx:/etc/nginx/conf.d /opt/docker/nginx/config

>docker rm -f mynginx

#### 创建运行nginx镜像的脚本
>mkdir -p /opt/docker/nginx/script&& cd /opt/docker/nginx/script

>vim docker_nginx.sh

粘贴如下内容：
```
#!/bin/bash
docker run --name mynginx --restart=always -p 8080:80 \
    -v /opt/docker/nginx/config/nginx.conf:/etc/nginx/nginx.conf:ro \
    -v /opt/docker/nginx/config/conf.d:/etc/nginx/conf.d \
    -v /opt/docker/nginx/html:/usr/share/nginx/html \
    -v /opt/docker/nginx/logs:/var/log/nginx \
    -d nginx
```

>sh docker_nginx.sh

### 修改keepalived配置文件

>vim keepalived.conf

加入下面检查ngxin的脚本配置


```
! Configuration File for keepalived

global_defs {
   router_id LVS_DEVEL
}
#添加部分1
vrrp_script chk_nginx {
    script "/etc/keepalived/nginx_pid.sh"   # 检查nginx状态的脚本
    interval 2
    weight 3
}

vrrp_instance VI_1 {
    state BACKUP
    nopreempt
    interface ens33
    virtual_router_id 51
    priority 120
    advert_int 1
    authentication {
        auth_type PASS
        auth_pass 1111
    }
    virtual_ipaddress {
        192.168.1.222 
    }
    #添加部分2
    track_script {
        chk_nginx
    }
}

```
### 添加检查nginx状态的脚本

>vim /etc/keepalived/nginx_pid.sh

```
#!/bin/bash
#version 0.0.1
#
A=`ps -C nginx --no-header |wc -l`
if [ $A -eq 0 ];then
      docker restart mynginx
      sleep 3
            if [ `ps -C nginx --no-header |wc -l` -eq 0 ];then
                  systemctl stop keepalived
fi 
fi
```
>chmod +x /etc/keepalived/nginx_pid.sh

##### 脚本说明：

当nginx进程不存在时，会自动重启nginx容器；再次检查nginx进程，如果不存在，就停止keepalived服务，然后BACKUP会自动接替MASTER的工作。

### 配置firewalld防火墙允许vrrp协议,关闭防火墙了，跳过此步
VRRP（Virtual Router Redundancy Protocol，虚拟路由器冗余协议）

```
firewall-cmd --permanent --add-rich-rule="rule family="ipv4" source address="192.168.1.39" protocol value="vrrp" accept"
firewall-cmd --reload
```
如果是backup服务器，source address改成master服务器的IP

### 重启keepalived
> service keepalived restart 

### 测试
关闭MASTER的nginx容器  docker stop mynginx,关闭后立马重启
```
[root@vmware39 keepalived]# docker stop mynginx
mynginx
[root@vmware39 keepalived]# docker ps
CONTAINER ID        IMAGE                 COMMAND                  CREATED             STATUS                  PORTS                                            NAMES
ba8b4299bcbe        nginx                 "nginx -g 'daemon ..."   59 minutes ago      Up Less than a second   0.0.0.0:8080->80/tcp      
```
关闭MASTER的keepalived服务service keepalived stop
这时候VIP漂移到BACKUP上，通过浏览器访问IP已经变成192.168.1.40。

至此笔记完成啦

---
其实还可以给ngxin加上负载均衡，转到另外的几台服务器上访问web内容，这样的话其中一台web服务器挂了也可以正常使用，真正实现高可用

大体架构引用下面的图来看看

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190747151.jpg)