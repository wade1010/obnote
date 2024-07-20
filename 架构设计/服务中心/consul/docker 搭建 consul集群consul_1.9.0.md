###建议

服务端一般3-5台 ，客户端不限制

###安装
> docker pull consul

### 启动

##### 第一个 name consul-server1

> docker run -d --name consul-server1 --restart=always -p 8501:8500 -v /Users/bob/workspace/服务/docker/consul/consul/server1-data:/consul/data -v /Users/bob/workspace/服务/docker/consul/consul/server1-conf:/consul/config consul agent -server -bootstrap-expect=2 -node=server-node1 -ui -bind=0.0.0.0 -enable-script-checks=true -client=0.0.0.0

- -bootstrap-expect 2: 集群至少2台服务器，才能选举集群leader //比如开了5个服务，挂了1个还能选举，挂了4个就不行了
- -ui：运行 web 控制台
- -bind： 监听网口，0.0.0.0 表示所有网口，如果不指定默认未127.0.0.1，则无法和容器通信
- -client ： 限制某些网口可以访问

##### 获取consul-server1IP

> JOIN_IP="$(docker inspect -f '{{.NetworkSettings.IPAddress}}' consul-server1)"


##### 启动第二个consul服务 consul-server2， 使用join命令加入consul-server1

> docker run -d --name consul-server2 --restart=always -p 8502:8500 -v /Users/bob/workspace/服务/docker/consul/consul/server2-data:/consul/data -v /Users/bob/workspace/服务/docker/consul/consul/server2-conf:/consul/config consul agent -server -bootstrap-expect=2 -node=server-node2 -ui -bind=0.0.0.0 -enable-script-checks=true -client=0.0.0.0 -join=$JOIN_IP

##### 启动第三个consul服务 consul-server3， 使用join命令加入consul-server1

> docker run -d --name consul-server3 --restart=always -p 8503:8500 -v /Users/bob/workspace/服务/docker/consul/consul/server3-data:/consul/data -v /Users/bob/workspace/服务/docker/consul/consul/server3-conf:/consul/config consul agent -server -bootstrap-expect=2 -node=server-node3 -ui -bind=0.0.0.0 -enable-script-checks=true -client=0.0.0.0 -join=$JOIN_IP

##### 启动client1

> docker run -d --name consul-client1 --restart=always -p 8511:8500 -v /Users/bob/workspace/服务/docker/consul/consul/client1-data:/consul/data -v /Users/bob/workspace/服务/docker/consul/consul/client1-conf:/consul/config consul agent -node=client-node1 -bind=0.0.0.0 -enable-script-checks=true -join=$JOIN_IP -ui -client=0.0.0.0


##### 启动client2

> docker run -d --name consul-client2 --restart=always -p 8512:8500 -v /Users/bob/workspace/服务/docker/consul/consul/client2-data:/consul/data -v /Users/bob/workspace/服务/docker/consul/consul/client2-conf:/consul/config consul agent -node=client-node2 -bind=0.0.0.0 -enable-script-checks=true -join=$JOIN_IP -ui -client=0.0.0.0



##### 测试


```
docker ps                 
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                                                                      NAMES
29d9c31d783f        consul              "docker-entrypoint.s…"   8 minutes ago       Up 8 minutes        8300-8302/tcp, 8301-8302/udp, 8600/tcp, 8600/udp, 0.0.0.0:8512->8500/tcp   consul-client2
3b76afaec4dd        consul              "docker-entrypoint.s…"   9 minutes ago       Up 9 minutes        8300-8302/tcp, 8301-8302/udp, 8600/tcp, 8600/udp, 0.0.0.0:8511->8500/tcp   consul-client1
96b6217a3d9c        consul              "docker-entrypoint.s…"   12 minutes ago      Up 12 minutes       8300-8302/tcp, 8301-8302/udp, 8600/tcp, 8600/udp, 0.0.0.0:8503->8500/tcp   consul-server3
f81fdd4e30b3        consul              "docker-entrypoint.s…"   13 minutes ago      Up 13 minutes       8300-8302/tcp, 8301-8302/udp, 8600/tcp, 8600/udp, 0.0.0.0:8502->8500/tcp   consul-server2
53bb45dffabb        consul              "docker-entrypoint.s…"   13 minutes ago      Up 13 minutes       8300-8302/tcp, 8301-8302/udp, 8600/tcp, 8600/udp, 0.0.0.0:8501->8500/tcp   consul-server1
```

停掉consul-server1 （是leader）

> docker stop consul-server1


> docker logs consul-server2


```
New leader elected: payload=server-node3
```

选举出了新的leader 




```
–net=host docker参数, 使得docker容器越过了net namespace的隔离，免去手动指定端口映射的步骤
-server consul支持以server或client的模式运行, server是服务发现模块的核心, client主要用于转发请求
-advertise 将本机私有IP传递到consul
-retry-join 指定要加入的consul节点地址，失败后会重试, 可多次指定不同的地址
-client 指定consul绑定在哪个client地址上，这个地址可提供HTTP、DNS、RPC等服务，默认是>127.0.0.1
-bind 绑定服务器的ip地址；该地址用来在集群内部的通讯，集群内的所有节点到地址必须是可达的，>默认是0.0.0.0
allow_stale 设置为true则表明可从consul集群的任一server节点获取dns信息, false则表明每次请求都会>经过consul的server leader
-bootstrap-expect 数据中心中预期的服务器数。指定后，Consul将等待指定数量的服务器可用，然后>启动群集。允许自动选举leader，但不能与传统-bootstrap标志一起使用, 需要在server模式下运行。
-data-dir 数据存放的位置，用于持久化保存集群状态
-node 群集中此节点的名称，这在群集中必须是唯一的，默认情况下是节点的主机名。
-config-dir 指定配置文件，当这个目录下有 .json 结尾的文件就会被加载，详细可参考https://www.consul.io/docs/agent/options.html#configuration_files
-enable-script-checks 检查服务是否处于活动状态，类似开启心跳
-datacenter 数据中心名称
-ui 开启ui界面
-join 指定ip, 加入到已有的集群中
```



```
--net=host：指定 docker网络模式为host模式共享宿主机的网络，若采用默认的bridge模式，则会存在容器跨主机间通信失败的问题
-v /data/consul_data/data:/consul/data：主机的数据目录挂载到容器的/consul/data下，因为该容器默认的数据写入位置即是/consul/data
-v /data/consul_data/conf:/consul/config：主机的配置目录挂载到容器的/consul/conf下，因为该容器默认的数据写入位置即是/consul/conf
consul agent -server：consul的server启动模式
consul agent -client：consul的client启动模式
consul agent -bind=192.168.43.234：consul绑定到主机的ip上
consul agent -bootstrap-expect=3：server要想启动，需要至少3个server
consul agent -data-dir /consul/data：consul的数据目录
consul agent -config-dir /consul/config：consul的配置目录
consul agent -join：加入的consul-server1节点IP地址建立consul集群，启动之后，集群就开始了Vote（投票选Leader）的过程
–net=host docker参数, 使得docker容器越过了netnamespace的隔离，免去手动指定端口映射的步骤
-e或--env 使用-e设置的环境变量，容器内部的进程可以直接拿到
-server consul支持以server或client的模式运行, server是服务发现模块的核心, client主要用于转发请求
-client consul绑定在哪个client地址上，这个地址提供HTTP、DNS、RPC等服务，默认是127.0.0.1，0.0.0.0 表示任何地址可以访问
-node - 群集中此节点的名称。这在群集中必须是唯一的。默认情况下，这是计算机的主机名
-bootstrap-expect 指定consul集群中有多少代理
-retry-join 指定要加入的consul节点地址，失败会重试, 可多次指定不同的地址
-bind 绑定IP地址用来在集群内部的通讯，集群内的所有节点到地址都必须是可达的，默认是0.0.0.0，但当宿主机重启后所有docker容器IP地址会发生变化，-bind 绑定IP作用就会失效，集群无法找到leader报错，如果将集群单独部署在一个宿主机内可以使用
-allow_stale 设置为true, 表明可以从consul集群的任一server节点获取dns信息, false则表明每次请求都会经过consul server leader
--name DOCKER容器的名称
-ui 提供图形化的界面
其他命令请查看consul官方文档：https://www.consul.io/docs/agent/options.html#ports
```
