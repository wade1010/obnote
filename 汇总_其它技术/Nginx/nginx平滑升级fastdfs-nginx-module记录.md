

1. git clone https://github.com/happyfish100/fastdfs-nginx-module.git 

1. 下载后完整路径是/root/fastdfs-nginx-module

1. 然后进入nginx安装目录，添加fastdfs-nginx-module

1. ./configure --prefix=/opt/nginx-new --with-http_ssl_module --with-http_stub_status_module --with-http_dav_module	#编译参数最好用旧版的参数(旧版的参数可用/opt/nginx/sbin/nginx -V 查看),nginx的位置指向之前的旧版本c

1. make   

1. 操作就就到这里了！不在需要make install 了！！  

1. 备份旧版的nginx二进制文件

1. cd /opt/nginx/sbin

1. mv nginx{,.old}

1. cp /usr/local/src/nginx-1.12.1/objs/nginx .

1. 检测新版的nginx语法

1. /opt/nginx/sbin/nginx -t        #确保无误之后在进行后面操作。

1. 关键操作

```javascript
kill -USR2 `cat /opt/nginx/logs/nginx.pid`                   #让nginx把nginx.pid改成nginx.pid.oldbin 跟着启动新的nginx
kill -QUIT `cat /opt/nginx/logs/nginx.pid.oldbin`   #退出旧版的nginx     
最后检查nginx的版本:/opt/nginx/sbin/nginx -V
```





