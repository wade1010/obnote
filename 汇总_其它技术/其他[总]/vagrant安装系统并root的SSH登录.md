mkdir **vagrant**-ubuntu

cd **vagrant**-ubuntu

**vagrant** init ubuntu/trusty64

**vagrant** up

**vagrant** ssh

 

ps:

这个时候其实在宿主机是可以用vagrant这个用户登录的，密码也是vagrant      ssh -p2222 vagrant@127.0.0.1

添加root密码  默认是没有的

sudo passwd root

然后

sudo vim 

![](https://gitee.com/hxc8/images5/raw/master/img/202407172330586.jpg)

cd **vagrant**-ubuntu

vagrant reload

ssh -p2222 [root@127.0.0.1](http://root@127.0.0.1)

就可以登录了

![](https://gitee.com/hxc8/images5/raw/master/img/202407172330842.jpg)

配置免密登录

ssh-copy-id -i ~/.ssh/id_rsa.pub -p2222 [root@127.0.0.1](http://root@127.0.0.1)