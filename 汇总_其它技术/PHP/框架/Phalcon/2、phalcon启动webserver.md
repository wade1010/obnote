在根目录快速启动 



```javascript
$(which php) -S localhost:8000 -t public .htrouter.php
```



命令说明

- $(which php) - will insert the absolute path to your PHP binary

- -S localhost:8000 - invokes server mode with the provided host:port

- -t public - defines the servers root directory, necessary for php to route requests to assets like JS, CSS, and images in your public directory

- .htrouter.php - the entry point that will be evaluated for each request





```javascript
-> % $(which php) -S localhost:8000 -t public .htrouter.php
PHP 7.3.22 Development Server started at Sun Nov 15 11:57:02 2020
Listening on http://localhost:8000
Document root is /Users/bob/workspace/templateworkspace/phalcon_demo/public
Press Ctrl-C to quit.

```



访问 http://localhost:8000



![](https://gitee.com/hxc8/images8/raw/master/img/202407191106986.jpg)









apache vhost配置



```javascript
<VirtualHost *:80>

    ServerAdmin    admin@example.host
    DocumentRoot   "/var/vhosts/tutorial/public"
    DirectoryIndex index.php
    ServerName     example.host
    ServerAlias    www.example.host

    <Directory "/var/vhosts/tutorial/public">
        Options       All
        AllowOverride All
        Require       all granted
    </Directory>

</VirtualHost>
```





NOTE: Note that using .htaccess files requires your apache installation to have the AllowOverride All option set.



在httpd.conf添加





```javascript
<Directory />
    AllowOverride ALL
</Directory>
```





完成