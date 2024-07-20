### 安装

> docker pull mysql:5.6

> docker images


```
➜  ~ docker images                 
REPOSITORY                                      TAG                 IMAGE ID            CREATED             SIZE
docker.io/mysql                                 5.6                 c580203d8753        2 weeks ago         302 MB
```


### 启动

（第一次启动Docker-MySql主要是查看Docker里面MySQL的默认配置，数据位置，日志位置，配置文件位置）

> docker run -it --entrypoint /bin/bash --rm mysql:5.6

#### 命令解释
（创建并进入容器里，方便查看容器里面的默认设置，--rm参数表示退出容器会自动删除当前容器）


> cat /etc/mysql/mysql.cnf  (查看默认配置文件)


```
....... 主要有下面
!includedir /etc/mysql/conf.d/
!includedir /etc/mysql/mysql.conf.d/
```
#### 退出

#### 再启动


```
docker run -d --name mysql -p 13306:3306 --env MYSQL_ROOT_PASSWORD=123456 mysql:5.6 --character-set-server=utf8 --collation-server=utf8_unicode_ci
```

设置空密码 这样root就是空密码了


```
docker run -d --name mysql -p 13306:3306 --env MYSQL_ALLOW_EMPTY_PASSWORD=root mysql:5.6 --character-set-server=utf8 --collation-server=utf8_unicode_ci
```


### 在宿主机上链接

> mysql -u root -h 127.0.0.1 -P 13306 -p


```
➜   mysql -u root -h 127.0.0.1 -P 13306 -p
Enter password: 
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 2
Server version: 5.6.50 MySQL Community Server (GPL)

Copyright (c) 2000, 2020, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.


```