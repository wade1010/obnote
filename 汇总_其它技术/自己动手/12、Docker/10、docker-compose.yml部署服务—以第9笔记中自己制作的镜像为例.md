version: "3"
services:
  web:

# 使用你自己的username/repo:tag
    image:192.168.1.39:5000/friendlyhello  
    deploy:
      replicas: 5
      resources:
        limits:
          cpus: "0.1"
          memory: 50M
      restart_policy:
        condition: on-failure
    ports:
      - "8000:80"
    networks:
      - webnet
networks:
  webnet:




1. 复制上面内容到  docker-compose.yml 中，内容解释如下

1. 镜像地址

1. replicas:5，是指运行5个实力作为一个服务web,并且限制每个实例的CPU使用率最多只能到10%，内存最多使用50M。

1. restart_policy   condition: on-failure  一旦一个容器失败就重启。

1. 把宿主机的8000端口映射到web这个服务的80端口。

1. networks ,指示web服务里的所有容器通过一个负载均衡的网络webnet来共享80端口（在内部，这些容器自己会使用一个临时的端口来映射到web服务的80端口）。

1. 外层的networks  ,把webnet网络作为默认网络设置。

1. 运行负载均衡应用

1. 在swarm manager节点上执行 docker stack deploy -c docker-compose.yml getstartedlab

1. 如果执行失败，根据提示改下yml文件，我遇到的就是端口被占用，就改个端口

1. docker stack ps getstartedlab   查看容器

1. 启动后，大概30秒左右，访问http://192.168.1.39:8000/  你刷新页面，你还可以看到容器ID在不断变化，这也证明了是负载均衡的

1. 卸载应用

1. docker stack rm getstartedlab