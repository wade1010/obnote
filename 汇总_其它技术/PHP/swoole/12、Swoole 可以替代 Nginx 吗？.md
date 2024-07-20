暂时不能，随着 Swoole 越来越强大，以后说不准。

官方建议 Swoole 与 Nginx 结合使用。

> Http\Server 对 Http 协议的支持并不完整，建议仅作为应用服务器。并且在前端增加Nginx作为代理。

根据自己的 Nginx 配置文件，可以自行调整。

比如：新增一个配置文件

enable-swoole-php.conf

```
location ~ [^/]\.php(/|$)
{
    proxy_http_version 1.1;
    proxy_set_header Connection "keep-alive";
    proxy_set_header X-Real-IP $remote_addr;
    proxy_pass http://127.0.0.1:9501;
}
```

我们都习惯会将虚拟域名的配置文件放在 vhost 文件夹中。

比如，虚拟域名的配置文件为：local.swoole.com.conf，可以选择加载 enable-php.conf ，也可以选择加载 enable-swoole-php.conf。

配置文件供参考：

```
server
    {
        listen 80;
        #listen [::]:80;
        server_name local.swoole.com ;
        index index.html index.htm index.php default.html default.htm default.php;
        root  /home/wwwroot/project/swoole;

        #include rewrite/none.conf;
        #error_page   404   /404.html;

        #include enable-php.conf;
        include enable-swoole-php.conf;

        location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
        {
            expires      30d;
        }

        location ~ .*\.(js|css)?$
        {
            expires      12h;
        }

        location ~ /.well-known {
            allow all;
        }

        location ~ /\.
        {
            deny all;
        }

        access_log  /home/wwwlogs/local.swoole.com.log;
    }
```