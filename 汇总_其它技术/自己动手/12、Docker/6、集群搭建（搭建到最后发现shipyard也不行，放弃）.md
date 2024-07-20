1. 安装Swarm

1. docker pull swarm

1. Error response from daemon: Get https://registry-1.docker.io/v2/: net/http: request canceled while waiting for connection (Client.Timeout exceeded while awaiting headers)

1. 链接不上，解决办法如下

1. docker在中国已经有了仓库，修改配置

1. vim /etc/docker/daemon.json

1. { "registry-mirrors": ["https://registry.docker-cn.com"]}

1. 重启systemctl restart docker

1. 继续执行命令  docker pull swarm

1. docker deamon的监听端口修改为0.0.0.0:2375

1. 修改管理端口。vim /lib/systemd/system/docker.service 将其中第11行的 ExecStart=/usr/bin/dockerd 替换为： ExecStart=/usr/bin/dockerd -H tcp://0.0.0.0:2375 -H unix:///var/run/docker.sock -H tcp://0.0.0.0:7654（此处默认2375为主管理端口，unix:///var/run/docker.sock用于本地管理，7654是备用的端口）

1. 将管理地址写入 /etc/profile

1. echo 'export DOCKER_HOST=tcp://0.0.0.0:2375' >> /etc/profile

1. source /etc/profile

1. systemctl daemon-reload

1. systemctl restart docker

1. 检查是否监听2375(这里也有可能要设置下防火墙，虚拟机我都关闭了)

1. netstat -tnlp|grep 2375           有结果，表明成功

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754576.jpg)

  



1. 配Swarm集群

1. docker run --rm swarm create

1. 启动swarm manger

1. docker run -ti -d -p 2376:2375 --restart=always --name shipyard-swarm-manager swarm:latest manage --host tcp://0.0.0.0:2375 token://cff32907da8ba4e1f5258362399971f3

1. 启动swarm agent，将当前docker节点加入到集群中(这里是两台机器加入节点到集群，在每个docker节点上分开执行)

1. docker run -ti -d --restart=always --name shipyard-swarm-agent swarm:latest join --addr 192.168.1.39:2375 token://cff32907da8ba4e1f5258362399971f3

1. docker run -ti -d --restart=always --name shipyard-swarm-agent swarm:latest join --addr 192.168.1.40:2375 token://cff32907da8ba4e1f5258362399971f3

这个是没装swarm，这里会自动装，如果是timeout就按之前主服务器安装的办法来装

1. 可以使用命令查看docker节点情况（任意docker节点上都可以执行）：docker run --rm swarm list token://cff32907da8ba4e1f5258362399971f3

1. 可以使用命令查看docker集群的详情（可在任意docker节点上执行,IP地址是装了swarm master主机的IP）docker -H 192.168.1.39:2376 info

1. 安装Shipyard

1. docker pull rethinkdb	

1. docker pull microbox/etcd

1. docker pull shipyard/docker-proxy

1. docker pull shipyard/shipyard

1. shipyard安装脚本

1.  #下载官方脚本

wget https://shipyard-project.com/deploy

若下载失败请使用

wget https://raw.githubusercontent.com/shipyard/shipyard-project.com/master/site/themes/shipyard/static/deploy

1. 替换官方脚本(中文版) grep -n shipyard:latest deploy sed -i 's/shipyard\/shipyard:latest/dockerclub\/shipyard:latest/g' deploy 

1. 修改端口 sed  "s/-8080/-8083/g" deploy  | grep "PORT:-8083"

1. 安装 sh deploy

1. 删除  cat deploy | ACTION=remove bash

1. 启动rethinkdb。

1.  docker run -ti -d --restart=always --name shipyard-rethinkdb -p 8082:8080 -p 28015:28015 -p 29015:29015 -v /opt/rethinkdb:/data rethinkdb

1. 查看rethinkdb的使用情况:打开http://192.168.1.39:8082

1. 启动shipyard

1. docker run -ti -d --restart=always --name shipyard-controller --link shipyard-rethinkdb:rethinkdb --link shipyard-swarm-manager:swarm -p 8083:8080 shipyard/shipyard server -d tcp://swarm:2375

1. Shipyard将创建一个默认账号，用户名：admin，密码：shipyard  打开http://192.168.1.39:8083 既可以访问







































