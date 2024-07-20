根据这个install脚本安装启动mq

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354613.jpg)

添加web管理插件

```
rabbitmq-plugins enable rabbitmq_management
```

重启 RabbitMQ 服务器以使管理插件生效

```
systemctl restart rabbitmq-server
```

Web 管理页面运行在本地的 15672 端口。要访问该页面，打开一个支持 Web 浏览器的客户端，输入 [http://localhost:15672/](http://localhost:15672/)，然后输入 RabbitMQ 的用户名和密码来登录管理页面。默认情况下，RabbitMQ 的管理员帐户的用户名和密码都是 "guest"。

这里由于install脚本里面对用户进行了处理

使用

用户名：vcfs

密码：Vcfs.123456

但是install里面的命令没有让vcfs是管理员，这里再加上管理员的权限

rabbitmqctl set_user_tags vcfs administrator

然后就可以登录了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354636.jpg)