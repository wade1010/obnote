查看之前Nginx 编译配置,再次编译的时候带上这些配置



nginx -V



再次编译安装Nginx 编译的同时添加module



```javascript
./configure --prefix=/usr/local/nginx --add-module=/usr/local/nginx/modules/ngx_http_consistent_hash
make
mv /usr/local/nginx/sbin/nginx /usr/local/nginx/sbin/nginx.bak
cp objs/nginx /usr/local/nginx/sbin
```



5、发送USR2信号



向主进程（master）发送USR2信号，Nginx会启动一个新版本的master进程和对应工作进程，和旧版一起处理请求



```javascript
➜  nginx ps aux | grep nginx
root      24562  0.0  0.0  24848   720 ?        Ss   10:30   0:00 nginx: master process ./sbin/nginx
nobody    24564  0.0  0.1  27384  1508 ?        S    10:30   0:00 nginx: worker process
nobody    24565  0.0  0.1  27384  1748 ?        S    10:30   0:00 nginx: worker process
➜  nginx kill -USR2 24562
➜  nginx ps aux | grep nginx
root      24562  0.0  0.0  24848   880 ?        Ss   10:30   0:00 nginx: master process ./sbin/nginx
nobody    24564  0.0  0.1  27384  1508 ?        S    10:30   0:00 nginx: worker process
nobody    24565  0.0  0.1  27384  1748 ?        S    10:30   0:00 nginx: worker process
root      27309  0.0  0.1  24852  1948 ?        S    10:35   0:00 nginx: master process ./sbin/nginx
nobody    27315  0.0  0.1  27388  1508 ?        S    10:35   0:00 nginx: worker process
nobody    27316  0.0  0.1  27388  1508 ?        S    10:35   0:00 nginx: worker process
```



6、发送WINCH信号

向旧的Nginx主进程（master）发送WINCH信号，它会逐步关闭自己的工作进程（主进程不退出），这时所有请求都会由新版Nginx处理

```javascript
➜  nginx kill -WINCH 24562
➜  nginx ps aux | grep nginx
root      24562  0.0  0.0  24848   880 ?        Ss   10:30   0:00 nginx: master process ./sbin/nginx
root      27309  0.0  0.1  24852  1948 ?        S    10:35   0:00 nginx: master process ./sbin/nginx
nobody    27315  0.0  0.1  27388  1508 ?        S    10:35   0:00 nginx: worker process
nobody    27316  0.0  0.1  27388  1508 ?        S    10:35   0:00 nginx: worker process
```



注意：回滚步骤，发送HUP信号



如果这时需要回退继续使用旧版本，可向旧的Nginx主进程发送HUP信号，它会重新启动工作进程， 仍使用旧版配置文件。然后可以将新版Nginx进程杀死（使用QUIT、TERM、或者KILL）

kill -HUP 24562





7、发送QUIT信号

升级完毕，可向旧的Nginx主进程（master）发送（QUIT、TERM、或者KILL）信号，使旧的主进程退出

```javascript
➜  nginx kill -QUIT 24562 
➜  nginx ps aux | grep nginx
root      27309  0.0  0.1  24852  1948 ?        S    10:35   0:00 nginx: master process ./sbin/nginx
nobody    27315  0.0  0.1  27388  1508 ?        S    10:35   0:00 nginx: worker process
nobody    27316  0.0  0.1  27388  1508 ?        S    10:35   0:00 nginx: worker process
```





8、验证nginx版本号，并访问测试





---

安装

下载并上传nginx-1.8.5.tar.gz

解压到root下

```javascript
tar -xvf nginx-1.8.5.tar.gz -C /root/
```

切换到nginx目录

```javascript
cd nginx-1.8.5/
```

编译nginx

```javascript
./configure --xxxxxxxxxxx（参数自行添加，此处省略.......）
make
make install
```

安装完成

-----------------------------------------------------------

升级

注：原nginx安装路径为/usr/local/nginx/，版本为1.8.5

下载新版本安装包并解压：

```javascript
tar -xvf nginx-1.8.7.tar.gz -C /root/
```

进入nginx目录

```javascript
cd nginx-1.8.7/
```

查看nginx编译参数

```javascript
nginx -V
```

将configure arguments:后的参数复制后放在./configure 后执行重新编译

```javascript
./configure --xxxxxxxxxxx
```

编译完成后make

```javascript
make
```

注：不执行make install，否则覆盖原文件数据

备份并替换nginx可执行文件

```javascript
mv /usr/local/nginx/sbin/nginx /usr/local/nginx/sbin/nginx.bak
cp /root/nginx-1.8.7/objs/nginx /usr/local/nginx/sbin
```

查看是否升级成功

```javascript
nginx -V
```

发现本版更换为1.8.7，升级成功

---------------------------------------------------------------------------------

添加模块

进入nginx目录

```javascript
cd nginx-1.8.7/
```

查看nginx编译参数

```javascript
nginx -V
```

将configure arguments:后的参数复制后放在./configure 后，加入要添加的模块--with-http_ssl_module，执行重新编译

```javascript
./configure --xxxxxxxxxxx --with-http_ssl_module
```

编译完成后make

```javascript
make
```

注：不执行make install，否则覆盖原文件数据

备份并替换nginx可执行文件

```javascript
mv /usr/local/nginx/sbin/nginx /usr/local/nginx/sbin/nginx.bak_v2
cp /root/nginx-1.8.7/objs/nginx /usr/local/nginx/sbin
```

查看是否添加模块成功

```javascript
nginx -V
```

发现编译参数添加进来了，添加模块成功

使用此方法，亲测无坑！