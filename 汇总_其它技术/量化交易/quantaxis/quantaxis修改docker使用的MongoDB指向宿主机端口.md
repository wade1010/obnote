[http://localhost:8888/lab?](http://localhost:8888/lab?)  登录

打开terminal

输入 /bin/bash

#先查看原配置

cat .quantaxis/setting/config.ini

#修改配置

echo "[MONGODB]

uri = host.docker.internal:27017" > .quantaxis/setting/config.ini

#再次查看

cat .quantaxis/setting/config.ini