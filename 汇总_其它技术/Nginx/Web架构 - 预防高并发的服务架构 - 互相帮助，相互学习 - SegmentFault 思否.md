CDN（静态资源） + Nginx（负载均衡&反向代理）+ Redis（主从配置&Sentinel监听集群）+ Mysql（主从配置）

介绍：业务从发展的初期到逐渐成熟，服务器架构也是从相对单一到集群，再到分布式，技术迭代的速度非常快，导致我们不断的学习。。。

一个可以支持高并发的服务少不了好的服务器架构，需要有负载均衡，主从集群的数据库，主从集群的缓存，静态文件上传cdn，比如 七牛云 等，这些都是让业务程序流畅运行的强大后盾。

闲话不多说，下面简单介绍搭建Windows服务器架构。

一、配置Nginx

介绍：Nginx是 Igor Sysoev 为俄罗斯访问量第二 Rambler.ru 站点开发的一款高性能HTTP和反向代理服务器。

那么有些人不明白反向代理与正向代理有什么不同？

正向代理就像是 因为GWF，国内需要使用代理访问Google，但是Google不知道真实的客户端是谁，代理隐藏了真实的客户端请求，客户端请求的服务都被代理服务器代替。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190801242.jpg)

www.baidu.com 是我们的反向代理服务器，反向代理服务器会帮我们把请求转发到真实的服务器那里去。

1、下载 Nginx-Windows & Tomcat7

1：重新加载配置 2：关闭 3：开启（或者nginx -c conf/nginx.conf）

附录：Nginx工作原理

2、修改配置文件

修改\Tomcat\conf\server.xml （三个端口）& \nginx-1.11.6\conf\nginx.conf ：

#user  nobody;#用户名worker_processes1;#工作进程（与CPU个数一比一）#error_log  logs/error.log;#error_log  logs/error.log  notice;#error_log  logs/error.log  info;#pid        logs/nginx.pid;events {    worker_connections1024;#单个进程最大连接数（worker_processes*worker_connections/4小于系统进程打开的文件总数）}http {      upstream tomcat  { #反向代理 server localhost:8082 weight=2;#weight权重（机器性能好weight就设大些）server localhost:8083 weight=3;          }     include       mime.types;    default_type  application/octet-stream;    #log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '#                  '$status $body_bytes_sent "$http_referer" '#                  '"$http_user_agent" "$http_x_forwarded_for"';#转发真实ip#access_log  logs/access.log  main;sendfileon;    #tcp_nopush     on;#keepalive_timeout  0;keepalive_timeout65;    #gzip  on;server {        listen80;#默认80端口server_name  localhost;        #charset koi8-r;#access_log  logs/host.access.log  main;location / {#配置静态文件等root   html;            index  index.html index.htm;            proxy_pass http://tomcat;#反向代理（上面upstream tomcat）        }        #error_page  404              /404.html;# redirect server error pages to the static page /50x.html#error_page500502503504  /50x.html;        location = /50x.html {            root   html;        }    }}

3、测试

如何知道集群服务器配置好了呢？

我们修改项目页面，将body内容改为aaa...和bbb...，打包分别放到上面的Tomcat中，重新启动Tomcat和Nginx。



根据上图，Nginx会将请求分发到不同的Tomcat中。

介绍：Nginx是采用master-worker多进程的方式，master负责请求转发，worker的数量为CPU数，所有worker进程的listenfd会在新连接到来时变得可读，为保证只有一个进程处理该连接，所有worker进程在注册listenfd读事件前抢accept_mutex，抢到互斥锁的那个进程注册listenfd读事件，在读事件里调用accept接受该连接。

4、防预CC攻击

使用 NGINX 流控和 fail2ban 防止 CC 攻击

在http模块中添加

limit_req_zone$binary_remote_addr zone=sym:10m rate=5r/s;   #限制发起的请求频率，每秒5次limit_conn_zone$binary_remote_addr zone=conn_sym:10m;       #限制发起的连接数

在location中添加

limit_req zone=sym burst=5;limit_conn conn_sym 10;

配置好后Nginx重启。模拟多线程并发请求，结果显示成功和异常：

查询Nginx/conf/error.log，显示如下：

二、配置Redis&Sentinel

1、下载 Redis3.0

解压redis（主）再复制三份，文件夹名称分别改为redis-slave（从）、redis-slave2（从）、redis-sentinel（哨兵）这些文件夹都能复制多次

（*.conf文件的名字可能不同！sentinel.conf需要新建！）

2、修改配置文件

修改redis（主）文件夹下的redis.windows.conf：

port 6380#端口（不能重复） logfile "E:/redis.log"#日志（防止宕机后可查）slave-read-only no                 #默认为yes，改为no用于主从复制requirepass "XXX"#密码（主从密码需相同）

修改redis-slave（从）下的redis.windows.conf：

port 6381logfile "E:/redis_slave1.log"        slaveof 127.0.0.16380              #master     slave-read-only nomasterauth "XXX"                    #主密码requirepass "XXX"

在redis-sentinel（哨兵）下创建sentinel.conf文件，内容为：

port 26379sentinel monitor mymaster 127.0.0.163801          #主配置，数字1代表有1个Sentinel监听有问题就进行主从复制并切换sentinel down-after-milliseconds mymaster 6000sentinel failover-timeout mymaster 900000sentinel auth-pass mymaster Alex                    #密码

下面为演示：

（1）运行主从Redis:





（2）运行Sentinel：

介绍：监听Redis的哨兵，具体看附录

附录：Sentinel



运行Sentinel后，.conf中配置内容就会刷新成：



为防止Sentinel宕掉。可复制多份sentinel并修改端口，分别启动。

（3）检测主从切换

当master宕机后，防止整个资源挂掉，将采用Sentinel实时监控Redis，情况发生后会立即主从复制并切换，这样系统崩溃的概率大大降低。





监听的主端口变为6381，非之前的6380，子监听的主也自动切换了。

三、配置Mysql集群

1、配置master主服务器

（1）在Master MySQL上创建用户，允许其他Slave服务器可以通过远程访问Master，通过该用户读取二进制日志，实现数据同步。



创建的用户必须具有REPLICATION SLAVE权限，除此之外没必要添加不必要的权限，密码为'XXX'。192.168.94.%是指明用户所在服务器，%是通配符，表示192.168.94.0/255的Server都可以登陆主服务器。

（2）修改my.Ini文件。启动二进制日志log-bin。

在[mysqld]下面增加：

server-id=1                       #给数据库服务的唯一标识，一般为大家设置服务器Ip的末尾号log-bin=master-binlog-bin-index=master-bin.index

（3）重启Mysql服务，查看日志

2、配置slaver从服务器

（1）修改my.ini文件，在[mysqld]下面增加

log_bin           = mysql-binserver_id         = 2relay_log         = mysql-relay-binlog_slave_updates = 1read_only         = 1

重启Mysql

（2）连接Master

changemasterto master_host='192.168.XXX.XXX', master_port=3306,master_user='alexnevsky',master_password='XXXX', master_log_file='master-bin.000001',master_log_pos=0;

（3）启动Slave

start slave;