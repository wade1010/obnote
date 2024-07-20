Docker安装clickhouse

1.创建相关配置目录

mkdir -P ~/workspace/service/clickhouse/data

mkdir -P ~/workspace/service/clickhouse/log

log

2.拉取镜像

下载最新版本clickhouse

docker pull yandex/clickhouse-server

下载指定版本clickhouse

docker pull yandex/clickhouse-server:20.3.5.21

3.查看[https://hub.](https://hub.)[docker](https://so.csdn.net/so/search?q=docker&spm=1001.2101.3001.7020).com/r/yandex/clickhouse-server/dockerfile 文件，EXPOSE 9000 8123 9009 了三个端口

4.创建临时容器，用以生成配置文件

docker run -d --name clickhouse-server --ulimit nofile=262144:262144 \

-p 8123:8123 -p 9000:9000 -p 9009:9009 \

-v /Users/bob/workspace/service/clickhouse/data/:/var/lib/clickhouse \

-v /Users/bob/workspace/service/clickhouse/log:/var/log/clickhouse-server \

yandex/clickhouse-server

8123端口是可以用于客户端连接的