直接yum 安装的redis 不是最新版本

yum install redis

如果要安装最新的redis，需要安装Remi的软件源，官网地址：http://rpms.famillecollet.com/

yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm

然后可以使用下面的命令安装最新版本的redis：

yum --enablerepo=remi install redis

安装完毕后，即可使用下面的命令启动redis服务

service redis start

或者

systemctl start redis

 redis安装完毕后，我们来查看下redis安装时创建的相关文件，如下：

rpm -qa |grep redis

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755997.jpg)

rpm -ql redis

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755228.jpg)

查看redis版本：

redis-cli --version

 

设置为开机自动启动：

chkconfig redis on

或者

systemctl enable redis.service

Redis开启远程登录连接，redis默认只能localhost访问，所以需要开启远程登录。解决方法如下：

在redis的配置文件/etc/redis.conf中

将bind 127.0.0.1 改成了 bind 0.0.0.0

然后要配置防火墙 开放端口6379

连接redis

redis-cli

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755722.jpg)

 更新：2018-01-22

在azure vm centos7.4 安装了最新的redis 4.0.6 bind 0.0.0.0 发现外网连接不上，发现azure vm 打开端口的地方已经变了，需要注意：要将源端口设置为 * ，目标端口为我们要打开的redis 端口，打开后可以使用telnet 命令测试一下：telnet 101.200.189.125 6379

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755063.jpg)

 

另外： redis 3.2后新增protected-mode配置，默认是yes，即开启。解决方法分为两种：1、设置 protected-mode 为 no  2、配置bind或者设置密码

测试的时候我使用了配置bind 方式，没有加密码，正式生产环境可以使用加密码方式



vim /lib/systemd/system/redis.service

可以修改redis启动的配置confi



编译安装



1、安装

$ wget http://download.redis.io/releases/redis-4.0.8.tar.gz

$ tar xzf redis-4.0.8.tar.gz

$ cd redis-4.0.8

$ make



 2、编译完成后，在Src目录下，有四个可执行文件redis-server、redis-benchmark、redis-cli和redis.conf。然后拷贝到一个目录下。

mkdir /opt/redis

cp redis-server /opt/redis

cp redis-benchmark /opt/redis

cp redis-cli /opt/redis

cp redis.conf /opt/redis

cd /opt/redis

3、启动Redis服务。

$ redis-server redis.conf

或

$nohup ./redis-server ./redis.conf &    （后台运行）



 4、然后用客户端测试一下是否启动成功。

$ redis-cli

redis> set foo bar

OK

redis> get foo

"bar"



详见   https://redis.io/download