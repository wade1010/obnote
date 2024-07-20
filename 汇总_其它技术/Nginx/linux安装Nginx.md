#### 下载
> wget https://nginx.org/download/nginx-1.16.1.tar.gz

> tar -zxvf nginx-1.16.1.tar.gz

> cd nginx-1.16.1

> ./configure --prefix=/usr/local/nginx

> make&&make install

##### 测试是否安装成功

>  cd /usr/local/nginx

> ./sbin/nginx -t
```
➜  nginx ./sbin/nginx -t
nginx: the configuration file /usr/local/nginx/conf/nginx.conf syntax is ok
nginx: configuration file /usr/local/nginx/conf/nginx.conf test is successful
```
表明成功

> ./nginx -s reload
> ./nginx -s stop

#### 参考
https://www.cnblogs.com/xxoome/p/5866475.html

