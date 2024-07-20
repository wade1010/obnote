一、背景说明

1.1 墙外的吐槽

云是个好东西但我一直不觉是个有那么好的东西，因为就较多次的体验来看，用得很难受；如果要我来选我宁愿自建机房。要说难受的具体原因原来倒是没想得很清楚，现在想来网速慢不是最主要的主要的是，主要的是我们站在墙外操作墙内的主机，然后被操作主机要从墙内向墙外反馈结果。

如果是物理机，那么人与设备相对于网络同处于一侧，人可以直接操作机器甚至是物理操作机器；而如果是云，那么人与设备分处于网络的两侧，人只能通过网络操作机器，首先要确保网络是没问题的然后操作程度只能限于云提供的接口。

 

1.2 阿里云ssh断开描述

在阿里云建了几台虚拟机开放端口后ssh连上去，短则几秒钟长则十来分钟就自动断开，正在操作也会断开。

因为会话维持的长短时间不一所以不是定时断开，由于正在操作也断开所以也不是会话超时断开（查看TMOUT确实也未设置），从未见过这种情况不懂什么原因。而且观察到似乎网络流量越大断开越快。

 

二、处理办法

2.1 无效的网上处理办法

网上看到最多的处理办法是编缉/etc/ssh/sshd_config在最后追加以下两项，然后重启sshd

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331592.jpg)

cat >> /etc/ssh/sshd_config << EOF
# ClientAliveInterval设定服务端向客户端发送存活确认的时间间隔，单位为秒
ClientAliveInterval 60
# ClientAliveCountMax设定服务端向客户端发送存活确认客户端无响应即主动关闭会话的次数
ClientAliveCountMax 8888
EOF

# systemd也无所谓，会自动重定向到systemctl restart sshd
service sshd restart

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331592.jpg)

从道理上来说这种方法应该是可行的才对，但在多台Centos6和7都未见有效依然断开。

 

2.2 启用TCPKeepAlive

经常在yum、make、mvn等命令中途断开，虽然nohup有些作用，但还是感觉饱受折磨。

无意间注意到本地机器的配置文件中启用了TCPKeepAlive，而阿里云中未启用，试了一下似乎真有用至少就现在观察来看到了第二天都不会自动断开。所以最终处理办法如下：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331592.jpg)

cat >> /etc/ssh/sshd_config << EOF
# ClientAliveInterval设定服务端向客户端发送存活确认的时间间隔，单位为秒
ClientAliveInterval 60
# ClientAliveCountMax设定服务端向客户端发送存活确认客户端无响应即主动关闭会话的次数
ClientAliveCountMax 8888
# 保持会话
TCPKeepAlive yes
EOF

# systemd也无所谓，会自动重定向到systemctl restart sshd
service sshd restart

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331592.jpg)

（其实也不太确定是不是TCPKeepAlive起了作用，因为我现在注释掉ssh也没见断开，未生效只是阿里云系统故障？）



https://www.cnblogs.com/lsdb/p/9988796.html

