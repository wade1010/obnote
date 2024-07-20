1、查看当前服务端口

         一般ssh服务的默认端口为22端口，查看监听的端口用netstat，如下：



[root@centos-linux-7 parallels]# netstat -tnlp |grep ssh

tcp        0      0 0.0.0.0:22              0.0.0.0:*               LISTEN      3766/sshd         

tcp6       0      0 :::22                   :::*                    LISTEN      3766/sshdx



2、修改默认端口,开始我们开放两个端口，一个是默认的22端口，一个是需要修改的端口，防止修改端口失败，需要进机房进行操作

[root@centos-linux-7 parallels]# sudo su

[root@centos-linux-7 parallels]# vim /etc/ssh/sshd_config



![](D:/download/youdaonote-pull-master/data/Technology/自己动手/images/454186963E5D4D7C8AA79AF4C593FBFDimage.png)



3、重启ssh服务 service sshd restart 或者/etc/init.d/sshd restart  第一个不行就换第二个

[root@centos-linux-7 parallels]# service sshd restart 

Redirecting to /bin/systemctl restart sshd.service



4、查看监听端口

[root@centos-linux-7 parallels]# netstat -tnlp |grep ssh

tcp        0      0 0.0.0.0:22              0.0.0.0:*               LISTEN      4437/sshd           

tcp        0      0 0.0.0.0:2018            0.0.0.0:*               LISTEN      4437/sshd           

tcp6       0      0 :::22                   :::*                    LISTEN      4437/sshd           

tcp6       0      0 :::2018                 :::*                    LISTEN      4437/sshd



此时2018端口就生效了



5、编辑防火墙配置：vi /etc/sysconfig/iptables  添加2018端口  -A INPUT -m state --state NEW -m tcp -p tcp --dport 2018 -j ACCEPT



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756753.jpg)



在虚拟机上装的centos7是最小安装方式，所以许多东西都没装，需要自己手动安装。



因此/etc/sysconfig/iptables不存在，没有安装iptables防火墙



可以通过以下命令安装iptables防火墙



systemctl  stop firewalld



systemctl mask firewalld

service iptables status 找到不到



yum install iptables-services   安装



service iptables status   找得到了



//设置开机启动



systemctl enable iptables



6、重启iptables

[root@centos-linux-7 parallels]# service iptables restart

Redirecting to /bin/systemctl restart iptables.service



7、修改完，测试

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756126.jpg)

看着提示输入密码  已经OK了



8、关闭22端口和防火墙  并重启相关服务service sshd restart 和 service iptables restart

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756373.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756487.jpg)



9、确认端口  netstat -tnpl |grep ssh

tcp        0      0 0.0.0.0:2018            0.0.0.0:*               LISTEN      4843/sshd           

tcp6       0      0 :::2018                 :::*                    LISTEN      4843/sshd  





OK大功告成



---

补充，如果是云服务器，可能有安装组，需要再设置里面打开相应的端口

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756691.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190756953.jpg)



9741就是我华为云ssh登录端口