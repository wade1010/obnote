# Docker容器添加新端口映射的方法与详细步骤

[https://www.jb51.net/server/296638y3u.htm](https://www.jb51.net/server/296638y3u.htm)

在Docker容器已经创建后，需要添加新的端口映射，即对已经存在的Docker容器添加新的端口映射，可以通过以下步骤来添加，即通过修改配置文件的方法。

## Windows 10 下 Dockers容器的配置文件修改步骤：

### 1、先找到要修改的容器hash值：

docker inspect 容器名称

![](https://gitee.com/hxc8/images0/raw/master/img/202407172037578.jpg)

### 2、然后退出docker Desktop服务

（因为在线状态配置文件修改保存不了）

### 3、资源管理器中打开最新安装的Docker的配置文件的路径：

\\wsl$\docker-desktop-data\data\docker\containers\[容器hash值] win11一般为： \\wsl.localhost\docker-desktop-data\data\docker\containers\[容器hash值]

### 4、修改配置文件

打开后修改其中的 config.v2.json 和 hostconfig.json

![](https://gitee.com/hxc8/images0/raw/master/img/202407172037502.jpg)

**config.v2.json有两处需要添加，只修改一处，是不行的：**

位置1：

> "ExposedPorts":{"20/tcp":{},"21/tcp":{},"22/tcp":{},"3306/tcp":{},"443/tcp":{},"6379/tcp":{},"80/tcp":{},"8081/tcp":{},"8082/tcp":{},"8083/tcp":{},"8084/tcp":{},"888/tcp":{},"8888/tcp":{},"9501/tcp":{},"9502/tcp":{}} 


位置2：

> "Ports":{"20/tcp":[{"HostIp":"0.0.0.0","HostPort":"1020"}],"21/tcp":[{"HostIp":"0.0.0.0","HostPort":"1021"}],"22/tcp":[{"HostIp":"0.0.0.0","HostPort":"1022"}],"3306/tcp":[{"HostIp":"0.0.0.0","HostPort":"13306"}],"443/tcp":[{"HostIp":"0.0.0.0","HostPort":"10443"}],"6379/tcp":[{"HostIp":"0.0.0.0","HostPort":"16379"}],"80/tcp":null,"8081/tcp":[{"HostIp":"0.0.0.0","HostPort":"8081"}],"8082/tcp":[{"HostIp":"0.0.0.0","HostPort":"8082"}],"8083/tcp":[{"HostIp":"0.0.0.0","HostPort":"8083"}],"8084/tcp":[{"HostIp":"0.0.0.0","HostPort":"8084"}],"888/tcp":[{"HostIp":"0.0.0.0","HostPort":"888"}],"8888/tcp":[{"HostIp":"0.0.0.0","HostPort":"8888"}],"9501/tcp":[{"HostIp":"0.0.0.0","HostPort":"9501"}],"9502/tcp":[{"HostIp":"0.0.0.0","HostPort":"9502"}]} 


**hostconfig.json 有一处：**

> "PortBindings":{"20/tcp":[{"HostIp":"","HostPort":"1020"}],"21/tcp":[{"HostIp":"","HostPort":"1021"}],"22/tcp":[{"HostIp":"","HostPort":"1022"}],"3306/tcp":[{"HostIp":"","HostPort":"13306"}],"443/tcp":[{"HostIp":"","HostPort":"10443"}],"6379/tcp":[{"HostIp":"","HostPort":"16379"}],"8081/tcp":[{"HostIp":"","HostPort":"8081"}],"8082/tcp":[{"HostIp":"","HostPort":"8082"}],"8083/tcp":[{"HostIp":"","HostPort":"8083"}],"8084/tcp":[{"HostIp":"","HostPort":"8084"}],"888/tcp":[{"HostIp":"","HostPort":"888"}],"8888/tcp":[{"HostIp":"","HostPort":"8888"}],"9501/tcp":[{"HostIp":"","HostPort":"9501"}],"9502/tcp":[{"HostIp":"","HostPort":"9502"}]} 


### 5、启动Docker Desktop服务：

点击容器名称 - 点击 Imspect 即可查看到映射的端口列表

![](https://gitee.com/hxc8/images0/raw/master/img/202407172037349.jpg)

## 补充知识：如何给运行中的docker容器增加映射端口

命令行操作

| 1 | #1、查看容器的信息 | 


最后改完之后，重启docker服务就行了

| 1 | systemctl restart docker | 


## 总结 

到此这篇关于Docker容器添加新端口映射的方法与详细步骤的文章就介绍到这了,更多相关Docker容器添加新端口映射内容请搜索脚本之家以前的文章或继续浏览下面的相关文章希望大家以后多多支持脚本之家！

[https://blog.csdn.net/pillar04/article/details/131838636](https://blog.csdn.net/pillar04/article/details/131838636)