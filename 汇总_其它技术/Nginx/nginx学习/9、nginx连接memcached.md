

![](https://gitee.com/hxc8/images7/raw/master/img/202407190801641.jpg)



用法: nginx响应请求时,直接请求memcached,

如果没有相应的内容,再回调PHP页面,去查询database,并写入memcached.

 

分析: memcached是k/v存储, key-->value,

nginx请求memecached时,用什么做key?

一般用 uri arg 做key,  如 /abc.php?id=3







http://nginx.org/en/docs/http/ngx_http_memcached_module.html





Example Configuration



```javascript

server {
    location / {
        set            $memcached_key "$uri?$args";
        memcached_pass host:11211;
        error_page     404 502 504 = @fallback;
    }

    location @fallback {
        proxy_pass     http://backend;
    }
}
```





附加



```javascript
docker pull memcached
docker run -p 11211:11211 --name memcache memcached
```



https://www.cnblogs.com/cnsec/p/13406990.html

https://www.cnblogs.com/hgj123/p/4270431.html

