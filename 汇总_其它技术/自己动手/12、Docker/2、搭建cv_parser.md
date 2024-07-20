cn



1、使用源码里面的Jenkins脚本将源代码打包

2、scp到服务器并解压

3、然后执行setup.sh（注意脚本里面的tika路径是不对的，需要自己scp  tika-server.jar到某个目录，再将路径修改成这个目录）

4、mv  整个项目到 到/opt/cv_parser/cn

5、修改config.py的配置

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755112.jpg)



6、cd /opt/cv_parser/cn

7、vim  start_docker_server.sh  (主要是修改端口和add  host对应的域名 ip)

8、执行sh start_docker_server.sh    就可以了



---

en

1. 源代码打包

1. scp到服务器并解压到en目录，tar -zxvf ./en.tar.gz -C  /opt/cv_parser/en  (这些目录在cn里面已经创建过了)

1. cd /opt/cv_parser/en/config

1. vim config.py      注意事项请看cn，不过en有很多配置不需要

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755796.jpg)

1. cd /opt/cv_parser/en

1. vim  start_docker_server.sh  (主要是修改端口和add  host对应的域名 ip)

1. 执行sh start_docker_server.sh    就可以了