#### 安装Haproxy

```
docker pull haproxy
```
#### 创建Haproxy配置文件
```
mkdir /opt/docker/haproxy/conifg     #路径可以自己决定，docker run的时候映射
cd /opt/docker/haproxy/conifg
vim haproxy.cfg
```
插入下面的配置
```config
global
	#工作目录
	chroot /usr/local/etc/haproxy
	#日志文件，使用rsyslog服务中local5日志设备（/var/log/local5），等级info
	log 127.0.0.1 local5 info
	#守护进程运行
	daemon

defaults
	log	global
	mode	http
	#日志格式
	option	httplog
	#日志中不记录负载均衡的心跳检测记录
	option	dontlognull
    #连接超时（毫秒）
	timeout connect 5000
    #客户端超时（毫秒）
	timeout client  50000
	#服务器超时（毫秒）
    timeout server  50000

#监控界面	
listen  admin_stats
	#监控界面的访问的IP和端口
	bind  0.0.0.0:8888
	#访问协议
    mode        http
	#URI相对地址
    stats uri   /admin
	#统计报告格式
    stats realm     Global\ statistics
	#登陆帐户信息
    stats auth  admin:123456
#数据库负载均衡
listen  proxy-mysql
	#访问的IP和端口
	bind  0.0.0.0:3306  
    #网络协议
	mode  tcp
	#负载均衡算法（轮询算法）
	#轮询算法：roundrobin
	#权重算法：static-rr
	#最少连接算法：leastconn
	#请求源IP算法：source 
    balance  roundrobin
	#日志格式
    option  tcplog
	#在MySQL中创建一个没有权限的haproxy用户，密码为空。Haproxy使用这个账户对MySQL数据库心跳检测
    option  mysql-check user haproxy
    server  MySQL_10 192.168.1.10:3306 check weight 1 maxconn 2000  
    server  MySQL_39 192.168.1.39:3306 check weight 1 maxconn 2000  
	server  MySQL_40 192.168.1.40:3306 check weight 1 maxconn 2000
	#使用keepalive检测死链
    option  tcpka  

```

上面配置主要有：
- chroot /usr/local/etc/haproxy   目录
- stats auth  admin:123456   登陆帐户信息
- server  MySQL_1 xxx.xxx.xxx.xxx:3306 check weight 1 maxconn 2000
#### 创建mysql用户
每个server上的mysql 创建一个用户，供haproxy访问
```
create user 'haproxy'@'%'  identified by '';
```
#### 启动haproxy容器
```
docker run -it -d -p 4001:8888 -p 4002:3306 
-v /opt/docker/haproxy/conifg:/usr/local/etc/haproxy --name haproxy1 --privileged haproxy
```
##### 如果报错IPv4 forwarding is disabled解决方法 
可以参考 [IPv4 forwarding is disabled解决方法](attachments/noteshare)

#### 进入容器启动haproxy服务
```
docker exec -it haproxy1 bash
haproxy -f /usr/local/etc/haproxy/haproxy.cfg
```
#### 其他机器登陆haproxy

>mysql -uhaproxy -h 192.168.1.39 -P 4002 -p

密码这里是空(自己授权的时候配置的密码)，直接回车就行

登录成功即可

>或者用root登录mysql -uroot -h 192.168.1.39 -P 4002 -p

输入root的密码。都可以proxy到可用的服务器

#### 访问Haproxy监控界面
在浏览器访问 http://宿主机ip:4001/admin ， 输入haproxy.cfg中配置的账号密码。
#### 验证是否监控
- 关闭其中一台服务器的mysql，再次刷新haproxy刷新界面，即可查看到结果，发现对应的MySQL变红
- 重启mysql,再次刷新haproxy刷新界面，即可查看到结果，发现对应的MySQL恢复正常


