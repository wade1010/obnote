

1. 环境准备工作

1. 关闭三台机器上的防火墙。如果开启防火墙，则需要在所有节点的防火墙上依次放行2377/tcp（管理端口）、7946/udp（节点间通信端口）、4789/udp（overlay 网络端口）端口。

1. 方法一，关闭防火墙

1. systemctl disable firewalld.service

1. systemctl stop firewalld.service

1. 方法二，放行端口

1. firewall-cmd --zone=pulic --add-port=2377/tcp --permanent

1. firewall-cmd --reload

1. 关闭selinux(这一步一定要做，否则使用web管理工具shipyard、portainerd的时候会报错，获取不到节点、Containers、images等信息，有可能这里浪费很多时间找原因)所有机器的selinux都要关闭

1. 临时关闭selinux setenforce 0:重启机器失效

1. vi /etc/selinux/config    设置SELINUX=disable

1. 配置docker

1. 在manager节点和node节点上分别安装docker

1. yum -y install docker

1. 配置docker(这里非常重要)

1. Ubuntu系统

1. vi /etc/default/docker

1. 添加一行：DOCKER_OPTS="-H tcp://0.0.0.0:2375 -H unix://var/run/docker.sock"

1. centos7

1. vi /usr/lib/systemd/system/docker.service

1. 在ExecStart=后面添加-H tcp://0.0.0.0:2375 -H unix://var/run/docker.sock

1. systemctl daemon-reload

1. systemctl restart docker

1. 所有机器都安装swarm

1. 下载swarm镜像

1. docker pull swarm

1. Error response from daemon: Get https://registry-1.docker.io/v2/: net/http: request canceled while waiting for connection (Client.Timeout exceeded while awaiting headers)

1. 解决方法一

1. 链接不上，解决办法如下

1. docker在中国已经有了仓库，修改配置

1. vim /etc/docker/daemon.json

1. { "registry-mirrors": ["https://registry.docker-cn.com"]}

1. 重启systemctl restart docker

1. 继续执行命令  docker pull swarm

1. 多试几遍docker pull swarm  会好的

1. docker images  查看是否已经安装

1. 在manager节点上初始化swarm,初始化之后保存提示的command

1. docker swarm init --advertise-addr 192.168.1.39

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754276.jpg)

1. 在想要加入的node节点上执行上面的提示command

1. docker node ls 查看加入的node节点 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754202.jpg)

注意上面node ID旁边那个*号表示现在连接到这个节点上

1. docker info 查看集群的相关信息

1. 在manager节点上安装可视化管理工具portainer

1. docker run -d -p 9000:9000 --restart=always --name portainer portainer/portainer

1. 上面命令会自动下载最新版本portainer镜像来安装

1.  在浏览器打开 http://192.168.1.39:9000

1. 在portainer上配置docker环境

1. 登录后，会自动进入配置endpoint页面

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754183.jpg)

1. 如果点击connect之后页面右上角提示失败，大部分原因是因为2375端口没连上

1. 解决办法，在对应节点上执行下面命令即可

1. systemctl daemon-reload

1. systemctl restart docker

1. 再点击connect，就自动进入portainer的home页面，点击刷新endpoints按钮，没出现的话就重复刷新或者手动刷新页面

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754335.jpg)

1. 点击"Endpoints"->点击"+Add endpoint"

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754311.jpg)

1. 按图填完信息后，点击“+Add endpoint”即可

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754456.jpg)

1. 点击home进入endpoints,点击任意一个进入对应node的页面

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754800.jpg)





































