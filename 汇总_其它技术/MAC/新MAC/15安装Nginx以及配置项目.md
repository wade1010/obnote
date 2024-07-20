### 安装
> brew install nginx

```
➜  ~ brew install nginx
Updating Homebrew...
==> Downloading https://mirrors.ustc.edu.cn/homebrew-bottles/bottles/nginx-1.17.
######################################################################## 100.0%
==> Pouring nginx-1.17.10.catalina.bottle.tar.gz
==> Caveats
Docroot is: /usr/local/var/www

The default port has been set in /usr/local/etc/nginx/nginx.conf to 8080 so that
nginx can run without sudo.

nginx will load all files in /usr/local/etc/nginx/servers/.

To have launchd start nginx now and restart at login:
  brew services start nginx
Or, if you don't want/need a background service you can just run:
  nginx
==> Summary
🍺  /usr/local/Cellar/nginx/1.17.10: 25 files, 2.1MB
```

### 配置Nginx

### brew services start php  会启动对应版本的php-fpm
```
server
{
    listen 8080;
    server_name local.ijisq-api.com;

    root /Users/bob/workspace/jisqworkspace/ijisq-api/public;
    
    location / {
        rewrite ^/(.*)$ /index.php/$1 last;
        break;
    }
    location /paysdk/ {
        alias /Users/bob/workspace/jisqworkspace/ijisq-api/thirdparty/paysdk/;
        index index.php;
    }
    location ~ ^/paysdk/.+\.php$ {
        root /Users/bob/workspace/jisqworkspace/ijisq-api/thirdparty/;
        rewrite /paysdk/(.*\.php?) /$1 break;
        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi.conf;
        fastcgi_param SCRIPT_FILENAME /Users/bob/workspace/jisqworkspace/ijisq-api/thirdparty/paysdk$fastcgi_script_name;
    }
    
    #PHP-INFO-START  PHP引用配置，可以注释或修改
    include conf/enable-php-73.conf;
    #PHP-INFO-END
    
    
    #禁止访问的文件或目录
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.svn|\.project|LICENSE|README.md)
    {
        return 404;
    }
    
    access_log  /usr/local/etc/nginx/logs/local.ijisq-api.com.log;
    error_log  /usr/local/etc/nginx/logs/local.ijisq-api.com.error.log;
}
```

#### 上面配置用到的enable-php-73.conf
```enable-php-73.conf
location ~ [^/]\.php(/|$)
{
	try_files  $uri = 404;
    fastcgi_pass  127.0.0.1:9000;
    fastcgi_index index.php;
    fastcgi_intercept_errors on;
    include fastcgi.conf;
    include conf/pathinfo.conf;
}
```

#### enable-php-73.conf配置用到的pathinfo.conf
```pathinfo.conf
set $real_script_name $fastcgi_script_name;
if ($fastcgi_script_name ~ "^(.+?\.php)(/.+)$") {
		set $real_script_name $1;
		set $path_info $2;
 }
fastcgi_param SCRIPT_FILENAME $document_root$real_script_name;
fastcgi_param SCRIPT_NAME $real_script_name;
fastcgi_param PATH_INFO $path_info;
```


### 前端配置
```
server
{
    listen 8080;
    server_name local.prod.ijisq.com;
    index index.html;
    root /Users/bob/workspace/jisqworkspace/ijisq-front/dist;
    location / {
        try_files $uri $uri/ /index.html;
    }
    location /api {
        rewrite ^/api/(.*)$ /$1 break;
        proxy_pass http://local.ijisq-api.com:8080;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
    location /pay {
        rewrite ^/pay/(.*)$ /paysdk/$1 break;
        proxy_pass http://local.ijisq-api.com:8080;
        proxy_set_header X-Forwarded-For $remote_addr;
    }

    #禁止访问的文件或目录
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.svn|\.project|LICENSE|README.md)
    {
        return 404;
    }
    
    access_log  /usr/local/etc/nginx/logs/local.prod.ijisq.com.log;
    error_log  /usr/local/etc/nginx/logs/local.prod.ijisq.com.error.log;
}
```