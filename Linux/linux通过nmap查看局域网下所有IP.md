Nmap是Linux下的网络扫描工具，我们可以扫描远端主机上那些端口在开放状态。

1、安装Nmap

[root@localhost ~]# yum -y install nmap

2、扫描指定网段中那些IP是被使用的

[root@localhost ~]# nmap -sP 192.168.1-254

3、扫描指定远端主机开放了那些端口

[root@localhost ~]# nmap -sS 192.168.1.130

4、扫描指定远端主机是什么操作系统

[root@localhost ~]# nmap -O 192.168.1.130

5、扫描指定远端主机服务版本号

[root@localhost ~]# nmap -sV 192.168.1.130





mac 电脑



brew install nmap





只扫描某个端口 22端口

nmap -p22 192.168.3.1-254



