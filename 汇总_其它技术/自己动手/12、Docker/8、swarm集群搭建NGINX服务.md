

1. 在manager节点部署nginx服务，服务数量为10个，公开指定端口是8080映射容器80,使用nginx镜像

1. docker service create --replicas 10 --name nginx --publish 8080:80  nginx

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754802.jpg)

1. 执行上述命令时manager节点分发任务到各个worker节点，包括Manager节点本身(管理节点也可以是worker节点),可以通过 docker service ps nginx 命令查看

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754674.jpg)

1. http://192.168.1.39:8080/   访问即可