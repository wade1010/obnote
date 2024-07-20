oeos-chaos使用

## 启动server

先到需要注入故障的节点开启chaosblade server

这里以10.0.11.101-104为例，目录都是一致的

ssh  10.0.11.101

cd /opt/chaosblade

![](https://gitee.com/hxc8/images5/raw/master/img/202407172356772.jpg)

启动

./blade server start

关闭

./blade server stop

目标节点全部启动

设置开机自启动

vi /etc/init.d/oeos-chaos

```
#!/bin/bash
# chkconfig: 3 88 88
/opt/chaosblade/blade server start
```

chmod +x /etc/init.d/oeos-chaos

chkconfig --add oeos-chaos

chkconfig --list oeos-chaos

## 配置OEOS-CHAOS

ssh 10.0.11.183

cd /opt/oeos-chaos

![](https://gitee.com/hxc8/images5/raw/master/img/202407172356471.jpg)

编辑Setting.toml文件

vi Setting.toml

![](https://gitee.com/hxc8/images5/raw/master/img/202407172356744.jpg)

exec_minute ，每轮测试执行的时间，持续执行指定的时间后，停止本轮测试，然后进入下一轮测试。

sleep_minute，每轮测试执行后休眠多久，这个时间内目标节点就是正常状态。

endpoint，就是目标节点上的blade server ,默认是9526端口，多个的时候","分隔。每轮测试会随机从endpoint中选一个

我们这里也将测试分为3个level,每轮测试会从里面随机选一个level,多个时用","分隔，这里的命令怎么配置，可以到目标机器查看

如：

ssh  10.0.11.101

cd /opt/chaosblade

./blade create  会输出所有能创建的命令

![](https://gitee.com/hxc8/images5/raw/master/img/202407172356553.jpg)

这里以cpu为例，查看cpu相关演练的具体参数

./blade create cpu -h  会输出如下

![](https://gitee.com/hxc8/images5/raw/master/img/202407172356831.jpg)

./blade create cpu fullload-h  会输出如下

![](https://gitee.com/hxc8/images5/raw/master/img/202407172356156.jpg)

然后 将命令的前半部分"blade crate "去掉后，就是配置里面需要的内容。

如 blade create cpu load --cpu-percent 60  我们需要的就是“cpu load --cpu-percent 60”

## 启动OEOS-CHAOS

vi start.sh

```
nohup /opt/oeos-chaos/oeos-chaos >> log.txt 2>&1 &
```

chmod +x start.sh

sh start.sh

日志会在同级目录下log.txt

## 关闭OEOS-chaos

vi stop.sh

最好查看日志，最后一行是休眠的状态，否则，其他时间停止，目标节点上的故障注入不会被取消

```
pkill oeos-chaos
```

chmod +x stop.sh