用nginx做反向代理和负载均衡非常简单,

支持两个用法 1个proxy, 1个upstream,分别用来做反向代理,和负载均衡

以反向代理为例, nginx不自己处理php的相关请求,而是把php的相关请求转发给apache来处理.



![](https://gitee.com/hxc8/images7/raw/master/img/202407190801400.jpg)



----这不就是传说的”动静分离”,动静分离不是一个严谨的说法,叫反向代理比较规范.



反向代理后端如果有多台服务器,自然可形成负载均衡,

但proxy_pass如何指向多台服务器?

把多台服务器用 upstream指定绑定在一起并起个组名,

然后proxy_pass指向该组



默认的均衡的算法很简单,就是针对后端服务器的顺序,逐个请求.

也有其他负载均衡算法,如一致性哈希,需要安装第3方模块.

(自行预习nginx第3方模块的安装,以安装ngx_http_upstream_consistent_hash为例)

反向代理导致了后端服务器的IP,为前端服务器的IP,而不是客户真正的IP,怎么办?



下面是图片的负载均衡 练手配置



```javascript
#user  nobody;
worker_processes  1;
events {
    worker_connections  1024;
}


http {
    include       mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';
    sendfile        on;

    #keepalive_timeout  0;
    keepalive_timeout  65;

    server {
     listen 81;
     server_name localhost;    
     root html/images;
     access_log logs/81-access.log main;
    }    
    server {
     listen 82;
     server_name localhost;    
     root html/images;
     access_log logs/82-access.log main;
    }    
 
    upstream imgserver {
    	server localhost:81 weight=1 max_fails=2 fail_timeout=2;
    	server localhost:82 weight=1 max_fails=2 fail_timeout=2;
    }

    server {
        listen       8080;
        server_name  localhost;
        location ~* \.(jpg|png|gif)$ {
            proxy_pass http://imgserver;
        }
}
```

