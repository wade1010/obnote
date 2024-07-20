

1. https://hub.docker.com/_/elasticsearch?tab=tags&page=1  查看下版本或者直接用最新版本

1. docker pull elasticsearch:2.4.4

1. 我启动的是单节点模式，然后把配置文件，以及数据文件 挂载到了外面，所以我的启动脚本是如下这样，这里要注意一点，配置文件目录下面得要有配置文件，不然es是起不来的，比较方便的办法是，先常规启动es，然后用docker cp命令，把配置文件复制到宿主机挂载目录，然后再进行修改就ok了，具体命令如下

1. docker run -d -p 9200:9200 -p 9300:9300 -e "discovery.type=single-node" --name es elasticsearch:2.4.4

1. 报错,jvm内存不够:

There is insufficient memory for the Java Runtime Environment to continue. 

Native memory allocation (mmap) failed to map 2060255232 bytes for committing reserved memory. 

An error report file with more information is saved as: 

/tmp/hs_err_pid1.log



使用 docker -e 设置变量 

//ES_JAVA_OPTS=内存大小 NETWORK_HOST=kibana远程访问 

docker run -it -p 9200:9200 -p 9300:9300 --name myes -e ES_JAVA_OPTS="-Xms512m -Xmx512m" -e NETWORK_HOST="0.0.0.0" 		elasticsearch:2.4.4 

1. docker ps 查看下container id

1. docker cp 容器id:/usr/share/elasticsearch/config /opt/docker/config/es    （后面是主机目录，没有的话先创建）

1. docker cp 容器id:/usr/share/elasticsearch/data  /opt/docker/data/es 

1. docker stop es

1. docker rm es

1. 如果需要更改配置，可以直接修改config目录下的  elasticsearch.yml 文件，然后启动es

1. docker run -d -p 9200:9200 -p 9300:9300 -e "discovery.type=single-node" --restart always --name es -v /opt/docker/config/es:/usr/share/elasticsearch/config -v /opt/docker/data/es:/usr/share/elasticsearch/data elasticsearch:2.4.4

1. 检查是否成功http://192.168.1.39:9200/

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754477.jpg)

1. 进入容器安装hly需要的插件

1. docker ps 查看 es的容器id

1. docker exec -it 容器ID bash

```javascript
root@bb889797b52f:/usr/share/elasticsearch/bin# ./plugin install delete-by-query
-> Installing delete-by-query...
Trying https://download.elastic.co/elasticsearch/release/org/elasticsearch/plugin/delete-by-query/2.4.4/delete-by-query-2.4.4.zip ...
Downloading ...........DONE
Verifying https://download.elastic.co/elasticsearch/release/org/elasticsearch/plugin/delete-by-query/2.4.4/delete-by-query-2.4.4.zip checksums if available ...
Downloading .DONE
Installed delete-by-query into /usr/share/elasticsearch/plugins/delete-by-query
```

1. 退出容器

1. 重启容器  docker restart es

