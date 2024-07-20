

centos7:

一. gcc 安装

安装 nginx 需要先将官网下载的源码进行编译，编译依赖 gcc 环境，如果没有 gcc 环境，则需要安装：



yum install gcc-c++

二. PCRE pcre-devel 安装

PCRE(Perl Compatible Regular Expressions) 是一个Perl库，包括 perl 兼容的正则表达式库。nginx 的 http 模块使用 pcre 来解析正则表达式，所以需要在 linux 上安装 pcre 库，pcre-devel 是使用 pcre 开发的一个二次开发库。nginx也需要此库。命令：



yum install -y pcre pcre-devel

三. zlib 安装

zlib 库提供了很多种压缩和解压缩的方式， nginx 使用 zlib 对 http 包的内容进行 gzip ，所以需要在 Centos 上安装 zlib 库。



yum install -y zlib zlib-devel

四. OpenSSL 安装

OpenSSL 是一个强大的安全套接字层密码库，囊括主要的密码算法、常用的密钥和证书封装管理功能及 SSL 协议，并提供丰富的应用程序供测试或其它目的使用。

nginx 不仅支持 http 协议，还支持 https（即在ssl协议上传输http），所以需要在 Centos 安装 OpenSSL 库。



yum install -y openssl openssl-devel



五.下载nginx

wget http://nginx.org/download/nginx-1.9.9.tar.gz

tar -zxvf nginx-1.9.9.tar.gz&&cd nginx-1.9.9

./configure

make

make install

/usr/local/nginx/sbin/nginx -t     查看配置文件路径

vim /usr/local/nginx/conf/nginx.conf  将80改成8080   你可以根据自己需要改

访问 http://192.168.1.39:8080/    看见Welcome to nginx!  就OK了



六.配置自启动

 cd /lib/systemd/system/

vim nginx.service

```javascript
[Unit]
Description=nginx service
After=network.target 
   
[Service] 
Type=forking 
ExecStart=/usr/local/nginx/sbin/nginx
ExecReload=/usr/local/nginx/sbin/nginx -s reload
ExecStop=/usr/local/nginx/sbin/nginx -s quit
PrivateTmp=true 
   
[Install] 
WantedBy=multi-user.target
```



[Unit]:服务的说明

Description:描述服务

After:描述服务类别

[Service]服务运行参数的设置

Type=forking是后台运行的形式

ExecStart为服务的具体运行命令

ExecReload为重启命令

ExecStop为停止命令

PrivateTmp=True表示给服务分配独立的临时空间

注意：[Service]的启动、重启、停止命令全部要求使用绝对路径

[Install]运行级别下服务安装的相关设置，可设置为多用户，即系统运行级别为3



第三步：加入开机自启动

# systemctl enable nginx

如果不想开机自启动了，可以使用下面的命令取消开机自启动

# systemctl disable nginx





```javascript
# systemctl start nginx.service　         启动nginx服务

# systemctl stop nginx.service　          停止服务

# systemctl restart nginx.service　       重新启动服务

# systemctl list-units --type=service     查看所有已启动的服务

# systemctl status nginx.service          查看服务当前状态

# systemctl enable nginx.service          设置开机自启动

# systemctl disable nginx.service         停止开机自启动
```



一个常见的错误

Warning: nginx.service changed on disk. Run 'systemctl daemon-reload' to reload units.

 直接按照提示执行命令systemctl daemon-reload 即可。



# systemctl daemon-reload



---





mac:

brew install nginx 





Docroot is: /usr/local/var/www



The default port has been set in /usr/local/etc/nginx/nginx.conf to 8080 so that

nginx can run without sudo.



nginx will load all files in /usr/local/etc/nginx/servers/.



To have launchd start nginx now and restart at login:

  brew services start nginx

Or, if you don't want/need a background service you can just run:

  nginx

