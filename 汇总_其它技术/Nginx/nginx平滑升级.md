：

已经安装好的nginx，需要添加一个未被编译安装的模块，需要怎么弄呢？

具体：

这里以安装第三方ngx_http_google_filter_module模块为例

nginx的模块是需要重新编译nginx，而不是像apache一样配置文件引用.so

1. 下载第三方扩展模块ngx_http_google_filter_module

# cd /data/software/
# git clone https://github.com/cuber/ngx_http_google_filter_module

 

2. 查看nginx编译安装时安装了哪些模块

# nginx -V
nginx version: nginx/1.8.0
built by gcc 4.4.7 20120313 (Red Hat 4.4.7-11) (GCC) 
built with OpenSSL 1.0.1e-fips 11 Feb 2013
TLS SNI support enabled
configure arguments: --prefix=/usr/local/nginx --with-http_ssl_module --with-http_sub_module --with-http_gzip_static_module --with-http_stub_status_module --add-module=/data/software/ngx_http_substitutions_filter_module

可以看出编译安装使用了--prefix=/usr/local/nginx --with-http_ssl_module --with-http_sub_module --with-http_gzip_static_module --with-http_stub_status_module --add-module=/data/software/ngx_http_substitutions_filter_module这些参数。--add-module=/data/software/ngx_http_substitutions_filter_module是之前编译添加ngx_http_substitutions_filter_module模块时添加

 

3. 加入需要安装的模块，重新编译，如这里添加–add-module=/data/software/ngx_http_google_filter_module

# ./configure --prefix=/usr/local/nginx --with-http_ssl_module --with-http_sub_module --with-http_gzip_static_module --with-http_stub_status_module --add-module=/data/software/ngx_http_substitutions_filter_module --add-module=/data/software/ngx_http_google_filter_module
# make    //千万不要make install，不然就真的覆盖了

 

4. 替换nginx二进制文件:

# cp /usr/local/nginx/sbin/nginx /usr/local/nginx/sbin/nginx.bak
# cp ./objs/nginx /usr/local/nginx/sbin/



















$ wget http://nginx.org/download/nginx-1.3.2.tar.gz



$ tar xvzf nginx-1.3.2.tar.gz



#查看ngixn版本极其编译参数 



$ /usr/local/nginx/sbin/nginx -V



nginx version: nginx/1.3.2

TLS SNI support disabled

configure arguments: --prefix=/usr/local/nginx --with-http_stub_status_module --with-http_ssl_module 



#进入nginx源码目录



$ cd nginx-1.3.2



#用下面这段编译



$ ./configure --prefix=/usr/local/nginx --with-http_stub_status_module --with-http_ssl_module --with-file-aio  --with-http_realip_module



$ make #千万别make install，否则就覆盖安装了



#make完之后在objs目录下就多了个nginx，这个就是新版本的程序了



#备份旧的nginx程序



$ cp /usr/local/nginx/sbin/nginx /usr/local/nginx/sbin/nginx.bak



#把新的nginx程序覆盖旧的



$ cp objs/nginx /usr/local/nginx/sbin/nginx



#测试新的nginx程序是否正确



$ /usr/local/nginx/sbin/nginx -t



nginx: the configuration file /usr/local/nginx/conf/nginx.conf syntax is ok

nginx: configuration file /usr/local/nginx/conf/nginx.conf test is successful



#平滑重启nginx



$ /usr/local/nginx/sbin/nginx -s reload



#查看ngixn版本极其编译参数



$ /usr/local/nginx/sbin/nginx -V



nginx version: nginx/1.3.2

TLS SNI support disabled

configure arguments: --prefix=/usr/local/nginx --with-http_stub_status_module --with-http_ssl_module --with-file-aio --with-http_realip_module