1. docker run -d -p 5000:5000 --restart always --name registry registry:2  (官方提供了 registry 镜像来启动本地的私有仓库。好像默认是开机自启动的)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754175.jpg)

https://hub.docker.com/r/library/registry/tags/ 找到tag

默认时候仓库数据存在的位置是/var/lib/registry 可以通过参数v改变仓库数据的位置。

查看容器运行情况

[root@bogon docker]# docker ps
CONTAINER ID        IMAGE               COMMAND                CREATED             STATUS              PORTS                    NAMES
2119ae9bc14d        registry:2.5.0      "/entrypoint.sh /etc   17 minutes ago      Up 17 minutes       0.0.0.0:5000->5000/tcp   sharp_sinoussi

说明docker的私有仓库已经运行成功，打开浏览器访问显示如下内容说明运行正常。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754376.jpg)



验证

打开另一台机器

然后推送一个镜像到registry中

[root@bogon opt]# docker images
REPOSITORY          TAG                 IMAGE ID            CREATED             VIRTUAL SIZE
centos              6.8                 c51f770ba2ca        5 weeks ago         194.5 MB
[root@bogon opt]# docker tag c51f770ba2ca 192.168.1.106:5000/centos:6.8
[root@bogon opt]# docker images
REPOSITORY             TAG                 IMAGE ID            CREATED             VIRTUAL SIZE
centos                 6.8                 c51f770ba2ca        5 weeks ago         194.5 MB
192.168.1.106:5000/centos   6.8                 c51f770ba2ca        5 weeks ago         194.5 MB

[root@bogon opt]# docker push 192.168.1.39:5000/centos
The push refers to a repository [192.168.1.106:5000/centos] (len: 1)
c51f770ba2ca: Image already exists 
c51f770ba2ca: Buffering to Disk 
b92e3b877355: Image successfully pushed 
b92e3b877355: Buffering to Disk 
Digest: sha256:bb00aaaf4f7993e3d34b02c58573622c4c039712611f521313a7fd00ba687571



上面push命令可能会报错：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754436.jpg)

原因分析：

客户端http不能访问

解决方案，创建配置文件，修改配置。用到这个仓库的机器都要执行这个操作：	

centos7是如下命令解决，换成自己的IP

echo '{ "insecure-registries":["xxx.xxx.xxx.xxx:5000"] }' > /etc/docker/daemon.json

然后重启 

systemctl restart docker







ps:



上面拉下来的image名字比较长，如下图：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754940.jpg)



只能tag一个新的image

![](https://gitee.com/hxc8/images7/raw/master/img/202407190754023.jpg)



然后删除长名字的



![](https://gitee.com/hxc8/images7/raw/master/img/202407190754157.jpg)

