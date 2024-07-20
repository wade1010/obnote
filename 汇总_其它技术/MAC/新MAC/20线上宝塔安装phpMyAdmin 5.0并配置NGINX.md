# 最终版
1. bt中安装phpMyAdmin最新版
2. bt网站添加站点，如下图
![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749185.jpg)

#### 这里说明下：
域名跟bt同名,端口先用777，因为888不是合法端口。应该是888留给PMA的。添加之后再改配置文件里面改成888就行了。


3. 修改配置如下
```
server
{
    listen 888;
    server_name bt.wode.life;
    index index.php index.html;
    root /www/server/phpmyadmin;
 
    #PHP-INFO-START  PHP引用配置，可以注释或修改
    include enable-php-73.conf;
    #PHP-INFO-END
    
    #可以不配置-------开始
    
    #禁止访问的文件或目录
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.svn|\.project|LICENSE|README.md)
    {
        return 404;
    }
    
    #一键申请SSL证书验证目录相关设置
    location ~ \.well-known{
        allow all;
    }
    
    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
        expires      30d;
        error_log off;
        access_log /dev/null;
    }
    
    location ~ .*\.(js|css)?$
    {
        expires      12h;
        error_log off;
        access_log /dev/null; 
    }
    #可以不配置-------结束
    access_log  /www/wwwlogs/phpmyadmin.log;
    error_log  /www/wwwlogs/phpmyadmin.error.log;
}
```

这样就能在宝塔"数据库"里面直接试用了

等等 页面打开会报错 ^_^


![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749612.jpg)


##### 解决
修改下目录权限 改成 755就行


另外我发现PHPmyadmin5.0 从宝塔数据库中点击，不传

```
pma_username: root
pma_password: 30bc9cae99dfb7ed
server: 1
target: index.php
```

---

## 下面是爬坑过程


1. bt中安装phpMyAdmin，我用的是5.0 应该没多大区别
2. 通过安装日志可以看到是用Apache自动配置的(不确实是不是我安装了Apache才默认使用Apache的，这里不管了)
```
 10200K .......... .......... .......... .......... .......... 98%  101K 1s
 10250K .......... .......... .......... .......... .......... 98%  287K 1s
 10300K .......... .......... .......... .......... .......... 99%  149K 0s
 10350K .......... .......... .......... .......... .......... 99%  429K 0s
 10400K .......... .......... .......... .......... ..        100%  133K=53s

2020-05-19 13:55:39 (199 KB/s) - ‘phpMyAdmin.zip’ saved [10693016/10693016]

cat: write error: Broken pipe
cat: write error: Broken pipe
reload apache...  done
Warning: ALREADY_ENABLED: 888:tcp
success
```
3. 我们需要用nginx配置
##### 路径/www/server/phpmyadmin/phpmyadmin_7a463330f2910749

phpmyadmin_7a463330f2910749  _后面的字符是每次安装随即生成的，直接复制就行

4. Nginx配置
```
server
{
    listen 888;
    server_name phpmyadmin.xxx.xxx;
    index index.php index.html;
    root /www/server/phpmyadmin/phpmyadmin_7a463330f2910749;

    #PHP-INFO-START  PHP引用配置，可以注释或修改
    include enable-php-73.conf;
    #PHP-INFO-END
    
    #可以不配置-------开始
    #禁止访问的文件或目录
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.svn|\.project|LICENSE|README.md)
    {
        return 404;
    }
    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
        expires      30d;
        error_log off;
        access_log /dev/null;
    }
    
    location ~ .*\.(js|css)?$
    {
        expires      12h;
        error_log off;
        access_log /dev/null; 
    }
    #可以不配置-------结束
    access_log  /www/wwwlogs/phpmyadmin.wode.life.log;
    error_log  /www/wwwlogs/phpmyadmin.wode.life.error.log;
}
```

后来发现自己配置的NGINX配置不能再宝塔数据库中直接打开，需要自己手动输入账号密码。所以我尝试把Apache删掉(我不需要用这个)

然后重新装phpMyAdmin 5.0,发现自动配置了Nginx^_^

```
13650K .......... .......... .......... .......... .......... 99% 3.85M 0s
 13700K .......... .......... .......... .........            100% 15.7M=3.4s

2020-05-19 14:51:13 (4.00 MB/s) - ‘phpMyAdmin.zip’ saved [14069197/14069197]

rm: cannot remove ‘/www/server/phpmyadmin/phpmyadmin_7a463330f2910749/.user.ini’: Operation not permitted
cat: write error: Broken pipe
cat: write error: Broken pipe
Reload service nginx...  done
Warning: ALREADY_ENABLED: 888:tcp
```

接下来就可以直接用了


后来看了下bt安装PHPmyadmin的源码，有下面一段，


```
	if [ -d "${Root_Path}/server/apache"  ];then
		webserver='apache'
	elif [ -d "${Root_Path}/server/nginx"  ];then
		webserver='nginx'
	elif [ -f "/usr/local/lsws/bin/lswsctrl" ];then
		webserver='openlitespeed'
	fi

```

后来看了源码 发现根本没给我弄啥Nginx配置，只是给我改了个对应PHP的应用conf

还是得手动添加Nginx配置
