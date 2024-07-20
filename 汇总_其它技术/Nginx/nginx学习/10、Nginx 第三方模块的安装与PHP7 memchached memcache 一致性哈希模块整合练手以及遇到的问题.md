第三方modules

https://www.nginx.com/nginx-wiki/build/dirhtml/modules/





一致性哈希模块

https://www.nginx.com/resources/wiki/modules/consistent_hash/







```javascript
git clone https://github.com/replay/ngx_http_consistent_hash.git
```





查看之前Nginx 编译配置,再次编译的时候带上这些配置



nginx -V





再次编译安装Nginx 编译的同时添加module



/usr/local/nginx/modules/ngx_http_consistent_hash这是我的下载目录 



```javascript
make clean
```



```javascript
./configure --prefix=/usr/local/nginx --add-module=/usr/local/nginx/modules/ngx_http_consistent_hash
```



添加模块具体可以参考  这篇文章 "10-1、源码安装的nginx平滑升级及重新编译添加模块"





搭建memcached集群 这里用docker



```javascript
docker run -itd -p 11211:11211 --name memcache1 memcached
docker run -itd -p 11212:11211 --name memcache2 memcached
docker run -itd -p 11213:11211 --name memcache3 memcached
```





upstream配置

```javascript
upstream memcacheserver {
        server 127.0.0.1:11211 weight=1 max_fails=2 fail_timeout=2;
        server 127.0.0.1:11212 weight=1 max_fails=2 fail_timeout=2;
        server 127.0.0.1:11213 weight=1 max_fails=2 fail_timeout=2;
    }
```



memcached 访问路径配置  注意这里memcached_pass后面不要加http://



```javascript
        location ~ memcached {
           set $memcached_key "$uri";
           memcached_pass memcacheserver;
           error_page 404 502 504 /call_back.php;
        }
```



在nginx根目录下的html目录下创建call_back.php



```javascript
<?php
print_r($_SERVER);
```



telnet登录到 127.0.0.1:11211 添加 一个key '/memcached/news.html'

```javascript
➜  nginx telnet 127.0.0.1 11211       
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
add /memcached/news.html 0 0 3
123
STORED
```





重启Nginx



浏览器访问 http://192.168.1.52:8080/memcached/news.html



发现 第一次有 第二次404 第三次404  第四次有 如此循环  这肯定不是我们想要的结果



这时候就可以用到我们的consistent_hash





```javascript
    upstream memcacheserver {
        consistent_hash $request_uri;
        server 127.0.0.1:11211 weight=1 max_fails=2 fail_timeout=2;
        server 127.0.0.1:11212 weight=1 max_fails=2 fail_timeout=2;
        server 127.0.0.1:11213 weight=1 max_fails=2 fail_timeout=2;
    }
```



重启 发现报错



```javascript
➜  nginx ./sbin/nginx -s reload             
nginx: [emerg] balancing method does not support parameter "max_fails=2" in /usr/local/nginx/conf/nginx.conf:60
```



有了一致性hash算法 那些多余的就不用了  修改下

```php
    upstream memcacheserver {
        consistent_hash $request_uri;
        server 127.0.0.1:11211;
        server 127.0.0.1:11212;
        server 127.0.0.1:11213;
    }
```





重启



这时候发现  浏览器访问 http://192.168.1.52:8080/memcached/news.html  一直都是请求了call_back.php



因为根据一致性hash算法 是路由到 11212上 



附上 nginx.conf主要配置

```php
 http {
 upstream memcacheserver {
        consistent_hash $request_uri;
        server 127.0.0.1:11211;
        server 127.0.0.1:11212;
        server 127.0.0.1:11213;
    }
    server {
        listen       8080;
        server_name  localhost;
         location / {
            root   html;
            index  index.html index.htm;
        }

        location ~ memcached {
           set $memcached_key "$uri";
           memcached_pass memcacheserver;
           error_page 404 502 504 = /call_back.php;
        }
        location ~ \.php$ {
            root           html;
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }
}
```



进阶：将call_back.php 修改成 如下

```javascript
<?php
$users=[
'a',
'b',
'c',
'd'
];
  header("Content-type: text/html; charset=utf-8");

  $uri=$_SERVER['REQUEST_URI'];
  #/user1.html
  #字符串截取获取数字

  preg_match('/user(\d+)\.html/',$uri,$match);
  $uid = $match[1]??'';

  if(!isset($users[$uid])){
     echo "用户不存在";
   }else{
     echo "用户存在";
    #写入memcached
$memcache = new Memcached;
$memcache->addServer('127.0.0.1', 11211);
$memcache->addServer('127.0.0.1', 11212);
$memcache->addServer('127.0.0.1', 11213);
$memcache->set($uri,$users[$uid]);
$memcache->close();
}
```





这时候发现 Nginx取得值和PHPset的值不一定在一台服务器上，这里PHP也要使用一致性hash算法



进入 https://www.php.net/manual/zh/ini.list.php 搜索下 hash

![](https://gitee.com/hxc8/images7/raw/master/img/202407190801578.jpg)





https://www.php.net/manual/zh/memcache.ini.php#ini.memcache.hash-strategy



memcache.hash_strategy string

控制key到服务器的映射（分布式）策略。值 consistent允许服务器增减而不会（大量）导致健的重新映射 （译注：参见http://tech.idv2.com/2008/07/24/memcached-004/），设置为 standard则使用余数方式进行key的映射。



在PHP.ini中,如下配置 （后来发现这是memcache.so扩展的写法）

 

```javascript
memcache.hash_strategy = consistent
```



这样: nginx与PHP即可完成对memcached的集群与负载均衡算法.



测试发现

//memecached的一致性hash算法要在PHP中指定配置



```javascript
$m = new Memcached();
var_dump($m->getOption(Memcached::OPT_HASH));
$m->setOption(Memcached::OPT_HASH,Memcached::HASH_DEFAULT);
$m->setOption(Memcached::OPT_HASH,Memcached::HASH_MD5);
$m->setOption(Memcached::OPT_HASH,Memcached::HASH_CRC);
#$m->setOption(Memcached::OPT_HASH,Memcached::HASH_FNV1_64);
#$m->setOption(Memcached::OPT_HASH,Memcached::HASH_FNV1A_64);
#$m->setOption(Memcached::OPT_HASH,Memcached::HASH_FNV1_32);
#$m->setOption(Memcached::OPT_HASH,Memcached::HASH_FNV1A_32);
#$m->setOption(Memcached::OPT_HASH,Memcached::HASH_HSIEH);
#$m->setOption(Memcached::OPT_HASH,Memcached::HASH_MURMUR);
var_dump($m->getOption(Memcached::OPT_HASH));
$m->setOption(Memcached::OPT_DISTRIBUTION,Memcached::DISTRIBUTION_CONSISTENT);
$m->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE,true);
$m->addServers(array(
        array('192.168.1.52', 11211),
        array('192.168.1.52', 11212),
        array('192.168.1.52', 11213),
));
```





测试好久发现跟Nginx那个怎么都对不上，后来看了下，官网那个是为memcache写的。



接下来安装memcache试试

https://pecl.php.net/package/memcache



```javascript

wget https://pecl.php.net/get/memcache-4.0.5.2.tgz

tar -zxvf memcache-4.0.5.2.tgz

cd memcache-4.0.5.2

phpize

./configure --enable-memcache --with-php-config=/usr/local/php/bin/php-config

make && make install

vim php.ini 自己找到文件地址

添加下面两行
extension=memcache.so
memcache.hash_strategy = consistent
```



后来又发现有问题PHP7的memcache.so扩展 添加数据的时候key会增加个 0.1



test.php

```javascript
<?php
$memcache = new Memcache;

$memcache->connect('127.0.0.1',11211);

$memcache->add('var_key','test variable',false,30);
~                                                   
```



curl 127.0.0.1/test.php  



执行上面curl ，memcached的服务端日志输出如下



```javascript
slab class  39: chunk size    524288 perslab       2
<26 server listening (auto-negotiate)
<27 server listening (auto-negotiate)
<28 new auto-negotiating client connection
28: Client using the ascii protocol
<28 add 0.1var_key 0 30 13
>28 STORED
<28 connection closed.
```





key 会多个0.1  用memcached不会出这个问题，猜测是php7操作memcache的问题。未验证。



查询得知 PHP7还真有点问题



https://www.lnmp.cn/install-memcache-and-memcached-extends-under-php7.html

https://ihuan.me/3532.html





这里比较晚了 懒得去搞环境了



直接改nginx.conf



```javascript
upstream memcacheserver {
        consistent_hash "0.1$request_uri";
        server 192.168.1.52:11211;
        server 192.168.1.52:11212;
        server 192.168.1.52:11213;
    }
    
    
    location ~ memcached {
           set $memcached_key "0.1$request_uri";
           memcached_pass memcacheserver;
           error_page 404 502 504 = @fallback;
        }
```



这两部分改下，你多个0.1 我就加个 0.1







测试后 没问题



总结下



Nginx 的一致性hash模块 是给memcache写的，不适用于memcached  





有不对的欢迎指正



附上最终 call_back.php

```javascript
<?php
$users = [
    'aaaa',
    'bbbbb',
    'ccccc',
    'dddddd',
    'fadsfdsafa'
];
header("Content-type: text/html; charset=utf-8");
$uri = $_SERVER['REQUEST_URI'];
preg_match('/user(\d+)\.html/', $uri, $match);
$uid = $match[1] ?? '';
echo 'from php';
if (!isset($users[$uid])) {
    echo "用户不存在";
} else {
    echo "用户存在";
    #写入memcache
    $mem = new memcache;
    $mem->addServer('192.168.1.52', 11211);
    $mem->addServer('192.168.1.52', 11212);
    $mem->addServer('192.168.1.52', 11213);

    echo $uri, '  ', $users[$uid];

    $mem->set($uri, $users[$uid]);
}
```

