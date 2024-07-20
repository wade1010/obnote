## 格式

FROM

RUN   写的是系统镜像中的命令
 
COPY  复制本地资源到指定目录

EXPOSE   端口开放


### 创建ngxin dockerfile 练手


```
FROM centos:centos7
# 创建nginx的用户组,并创建好相应的data和conf
RUN mkdir /data && mkdir /conf && groupadd -r nginx && useradd -r -g nginx nginx

# copy centos源 到docker  
COPY ./epel-7.repo /etc/yum.repos.d/epel.repo

# 安装Nginx所需要的依赖包
RUN yum update -y \
    && yum clean all  \
    && yum makecache  \
    && yum -y install  gcc gcc-c++ autoconf automake make zlib zlib-devel net-tools openssl* pcre* wget \
    && yum clean all  && rm -rf /var/cache/yum/*
    
# 复制 Nginx tar 包 ( http://nginx.org/download 去预先下载到本地 当前目录)
COPY ./nginx-1.17.10.tar.gz  /data/nginx-1.17.10.tar.gz

RUN cd /data \
   && tar -zxvf nginx-1.17.10.tar.gz \
   && cd nginx-1.17.10 \
   && ./configure --prefix=/usr/local/nginx --user=nginx --group=nginx \
   && make && make install && rm -rf /data/nginx-1.17.10.tar.gz  && rm -rf /data/nginx-1.17.10
   
  
COPY ./nginx.conf /conf

# 全局使用nginx,软链接

RUN ln -s /usr/local/nginx/sbin/* /usr/local/sbin

#声明端口
EXPOSE 80

#执行一条命令 nginx -c /conf/nginx.conf

ENTRYPOINT ["/usr/local/nginx/sbin/nginx","-c","/conf/nginx.conf","-g","daemon off;"]

```

nginx.conf


```
user  root;
worker_processes  1;
events {
    worker_connections  1024;
}
http {
    default_type  application/octet-stream;
    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';
    sendfile        on;
    keepalive_timeout  65;
    server {
            listen       80;
            access_log  /usr/local/nginx/logs/$host  main;
            location / {
                root  /www; #php容器的目录
                default_type text/html;
            }
            location  ~ \.php/?.*  {
                    default_type text/html;
                    #做php-fpm 配置，注意地址
                    root           /www;  #php-fpm容器当中的路径，不是nginx容器路径
                    fastcgi_index  index.php;

                    #fastcgi_pass   192.168.1.10:9002; #本地IP+本地php容器暴露出来的端口

                    fastcgi_pass   168.100.100.101:9000; #PHP容器IP+php端口

                    #为php-fpm指定的根目录
                    fastcgi_param  SCRIPT_FILENAME  $DOCUMENT_ROOT$fastcgi_script_name;
                    #注意是容器当中的位置

                    #定义变量 $path_info ，用于存放pathinfo信息
                    set $path_info "";
                    if ($fastcgi_script_name ~ "^(.+?\.php)(/.+)$") {
                            #将文件地址赋值给变量 $real_script_name
                            set $real_script_name $1;
                                #将文件地址后的参数赋值给变量 $path_info
                            set $path_info $2;
                        }
                         #配置fastcgi的一些参数
                        fastcgi_param SCRIPT_NAME $real_script_name;
                        fastcgi_param PATH_INFO $path_info;
                        include        /usr/local/nginx/conf/fastcgi_params;
                 }
            error_page   500 502 503 504  /50x.html;
            location = /50x.html {
                  root   html;
            }
        }
}


```


上面命令复制到 DockerFile 文件中 然后执行

### 目录

![image](https://gitee.com/hxc8/images0/raw/master/img/202407172037698.jpg)

> docker built -f nginx

最后出现

```
Successfully built 4ae7e42f38a9
Successfully tagged my-nginx:latest
```
就行了

### 测试

> docker run -itd --name my-nginx my-nginx

> docker exec -it my-nginx bash  进入容器

> docker stop my-nginx|xargs docker rm   停止并删除容器

#### 跟本地82端口绑定

> docker run -itd -p 82:80 --name my-nginx my-nginx


## 构建PHP镜像


```
FROM php:7.3-fpm-alpine

# Version
ENV PHPREDIS_VERSION 4.0.0

# Libs
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories \
    && apk  add  \
        curl \
        vim  \
        wget \
        git \
        openssl-dev\
        zip \
        unzip \
        g++  make autoconf


RUN mv "$PHP_INI_DIR/php.ini-production"  "$PHP_INI_DIR/php.ini" \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sysvmsg

# Redis extension
RUN wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
    && pecl install /tmp/redis.tar.tgz \
    && rm -rf /tmp/redis.tar.tgz \
    && docker-php-ext-enable redis
# 修改php.ini的文件 extension=redis.so

EXPOSE 9000
#设置工作目录
WORKDIR  /www

```

> docker build -t my-php .

### 启动PHP

> docker run -itd -p 9002:9000 --name my-php my-php

## 构建Redis


```
FROM centos:centos7
RUN groupadd -r redis && useradd -r -g redis redis

RUN mkdir data ;\
    yum update -y ; \
    yum -y install gcc automake autoconf libtool make wget epel-release gcc-c++;

COPY ./redis-5.0.7.tar.gz redis-5.0.7.tar.gz
RUN mkdir -p /usr/src/redis; \
    tar -zxvf redis-5.0.7.tar.gz -C /usr/src/redis; \
    rm -rf redis-5.0.7.tar.gz; \
    cd /usr/src/redis/redis-5.0.7 && make ; \
    cd /usr/src/redis/redis-5.0.7 && make install

COPY ./conf/redis.conf /usr/src/redis/redis-5.0.7/redis.conf

EXPOSE 6379

ENTRYPOINT ["redis-server", "/usr/src/redis/redis-5.0.7/redis.conf"]

```


![image](https://gitee.com/hxc8/images0/raw/master/img/202407172037365.jpg)

> docker build -t my-redis .

### 启动Redis

> docker run -itd -p 6380:6379 --name my-redis my-redis


### 删除所有容器命令

> docker stop $(docker ps -a -q)|xargs docker rm

### 本地目录与容器目录共享  nginx容器为例

> docker run -itd -v /Users/bob/workspace/服务/docker/nginx/conf:/conf -p 82:80 --name my-nginx my-nginx


之后在 对应的本地目录或者容器中对应的目录 新增删除文件 都会同步


## 进入 PHP容器

> docker exec -it my-php sh

www 目录下创建个index.php

> vi index.php

```
<?php
echo 'hello world';
```

## 本地访问 http://127.0.0.1:82/index.php

就能输出 hello world