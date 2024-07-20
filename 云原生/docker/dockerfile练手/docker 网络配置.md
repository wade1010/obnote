
本文接 dockerfile构建LNRP环境  这篇文章

### 查看网络

> docker network ls


```
docker network ls
NETWORK ID          NAME                          DRIVER              SCOPE
d0799d308383        bridge                        bridge              local
a3a2495de683        chaogushe-end-swoft_default   bridge              local
a98bb03ea293        host                          host                local
6bb35f34aa6e        none                          null                local
cadfcdf89dc6        redis-cluster_default         bridge              local
698ea01ab25f        redis_cluster_default         bridge              local
f0565b4ecdbd        swoft-im_my-bridge            bridge              local
```

尽量使用桥接模式

## 创建网络

> docker network create --subnet=168.100.100.0/24 my-network

#### 尽量不要跟本机IP段相同 比如本地是192.xxx 就不要用192开头

> docker network ls


```
docker network ls                                         
NETWORK ID          NAME                          DRIVER              SCOPE
d0799d308383        bridge                        bridge              local
a3a2495de683        chaogushe-end-swoft_default   bridge              local
a98bb03ea293        host                          host                local
7c3e954318cb        my-network                    bridge              local
6bb35f34aa6e        none                          null                local
cadfcdf89dc6        redis-cluster_default         bridge              local
698ea01ab25f        redis_cluster_default         bridge              local
f0565b4ecdbd        swoft-im_my-bridge            bridge              local
```

## 使用


关闭 之前测试的docker容器

> docker stop $(docker ps -a -q)|xargs docker rm   


##### 启动nginx

> docker run -itd -v /Users/bob/workspace/服务/docker/nginx/conf:/conf --network=my-network --ip=168.100.100.100 -p 82:80 --name my-nginx my-nginx

##### 启动PHP
> docker run -itd -v /Users/bob/workspace/服务/docker/php/www:/www --network=my-network --ip=168.100.100.101 -p 9002:9000 --name my-php my-php

#### 查看php容器的ip

> docker exec -it my-php sh

> ipaddr
```
/www # ipaddr
1: lo: <LOOPBACK,UP,LOWER_UP> mtu 65536 qdisc noqueue state UNKNOWN qlen 1000
    link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
    inet 127.0.0.1/8 scope host lo
       valid_lft forever preferred_lft forever
2: tunl0@NONE: <NOARP> mtu 1480 qdisc noop state DOWN qlen 1000
    link/ipip 0.0.0.0 brd 0.0.0.0
3: ip6tnl0@NONE: <NOARP> mtu 1452 qdisc noop state DOWN qlen 1000
    link/tunnel6 00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00 brd 00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00
69: eth0@if70: <BROADCAST,MULTICAST,UP,LOWER_UP,M-DOWN> mtu 1500 qdisc noqueue state UP 
    link/ether 02:42:a8:64:64:65 brd ff:ff:ff:ff:ff:ff
    inet 168.100.100.101/24 brd 168.100.100.255 scope global eth0
       valid_lft forever preferred_lft forever
```

#### 修改 nginx.conf

将

```
fastcgi_pass   192.168.1.10:9002; #本地IP+本地php容器暴露出来的端口
```
改成 

```
fastcgi_pass   168.100.100.101:9000; #PHP容器IP+php端口
```

### 重启nginx容器

> docker restart my-nginx

本机浏览器访问  http://127.0.0.1:82/index.php

就会出现 PHP容器中www目录下的index.php文件中的输出内容