### 进入容器

docker exec -it xxxxxx /bin/bash

### 同步时间

mv /etc/localtime /etc/localtime_bak

cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime

date 验证就可以了

我是dbeaver连接这个docker的mysql，方向date在服务端是对的，但是dbeaver还是有问题，关闭重启下就好了。