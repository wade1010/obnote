在使用zookeeper 3.6之后的版本，开启服务器，zk会自动占用8080端口，而后端服务器大部分都需要使用8080端口，因此需要zk的配置文件即可。

在zk conf目录里面，修改zoo.cfg，在其中加上：

```
//admin.serverPort 默认占8080端口
admin.serverPort=2180
```

重启zk服务器，即可。

zoo.cfg 不知道在哪里，就用find查找下

```
find / -name 'zoo.cfg' 2> /dev/null
```

![](https://gitee.com/hxc8/images7/raw/master/img/202407190030595.jpg)