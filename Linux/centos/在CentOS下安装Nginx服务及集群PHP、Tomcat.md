在CentOS下安装Ngix服务及集群PHP、Tomcat

资源下载地址：http://download.csdn.net/detail/attagain/7570597

一、 Ngix依赖模块安装

Ngix依赖模块有：pcre、zlib、openssl、md5 /sha1（如果系统中没有安装相应模块，需要按照下列方式安装）

1、 安装pcre模块（8.35）

官方网站：http://www.pcre.org/

安装命令：

# unzip pcre-8.35.zip

# cd pcre-8.35

# ./configure

# make && make install

     在64位linux系统中，nginx搜索的库位置为lib64；所以，需要建立软连接：

# ln -s /usr/local/lib/libpcre.so.1 /lib64/

# ln -s /usr/local/lib/libpcre.so.1 /lib/

# ln -s /usr/local/lib/libpcre.so.1 /usr/local/lib64/

2、 安装zlib模块（1.2.8）

官方网站：http://www.zlib.net/

安装命令：

# tar zxvf zlib-1.2.8.tar.gz

# cd zlib-1.2.8

# ./configure

# make && make install

3、 安装openssl模块（1.0.1h）

官方网站：http://www.openssl.org/

安装命令：

# tar zxvf openssl-1.0.1h.tar.gz

# cd openssl-1.0.1h

# ./config

# make && install

二、 Nginx安装

1、 安装Nginx（1.6.0）

官方网站：http://nginx.org/

安装命令：

# tar zxvf nginx-1.6.0.tar.gz

# cd nginx-1.6.0

# ./configure --prefix=/usr/local/nginx /

            --with-openssl=/usr/local/openssl /

            --with-http_stub_status_module

# make && make install

安装完成后的Nginx的目录结构：

[root@AP nginx-1.6.0]# ll /usr/local/nginx/

total 16

drwxr-xr-x 2 root root 4096 Jun 24 14:42 conf

drwxr-xr-x 2 root root 4096 Jun 24 14:42 html

drwxr-xr-x 2 root root 4096 Jun 24 14:42 logs

drwxr-xr-x 2 root root 4096 Jun 24 14:42 sbin

2、  修改配置文件中的监听端口，确保不被其他程序占用

修改配置文件：/usr/local/nginx/conf/nginx.conf

修改端口：80-<9090

2.1、设置Linux防火墙，打开端口9090

执行命令：

# /sbin/iptables -I INPUT -p tcp --dport 9090 -j ACCEPT

保存设置命令：

# /etc/rc.d/init.d/iptables save

查看端口打开情况命令：

#/etc/init.d/iptables status

重启防火墙服务

# /etc/rc.d/init.d/iptables restart

3、 启动、停止Nginx

A、 启动命令

#/usr/local/nginx/sbin/nginx

B、 停止命令

  # /usr/local/nginx/sbin/nginx -s stop

C、 检查配置文件

  # /usr/local/nginx/sbin/nginx -t -c /usr/local/nginx/conf/nginx.conf

D、 查看nginx版本及完整版本

# /usr/local/nginx/sbin/nginx –V

nginx version: nginx/1.6.0

# /usr/local/nginx/sbin/nginx –V

nginx version: nginx/1.6.0

built by gcc 4.4.6 20110731 (Red Hat 4.4.6-3) (GCC)

configure arguments: --prefix=/usr/local/nginx --with-openssl=/usr/local/openssl --with-http_stub_status_module

E、 查看帮助

# /usr/local/nginx/sbin/nginx –h

nginx version: nginx/1.6.0

Usage: nginx [-?hvVtq] [-s signal] [-c filename] [-p prefix] [-g directives]

Options:

  -?,-h         : this help

  -v            : show version and exit

  -V            : show version and configure options then exit

  -t            : test configuration and exit

  -q            : suppress non-error messages during configuration testing

  -s signal     : send signal to a master process: stop, quit, reopen, reload

  -p prefix     : set prefix path (default: /usr/local/nginx/)

  -c filename   : set configuration file (default: conf/nginx.conf)

  -g directives : set global directives out of configuration file

4、 Nginx默认页面显示

三、 Nginx反向代理Tomcat服务Nginx和Tomcat的整合非常的简单，只需要修改nginx.conf配置文件，添加如下信息：

location /TspWebManager/ {

            index index.html;

            proxy_passhttp://192.168.30.202:8001/TspWebManager/;

        }

location /CNP_MServiceProcess/ {

            index index.html;

            proxy_passhttp://192.168.30.202:8001/CNP_MServiceProcess/;

        }

说明：

1、 URL正则表达式

/TspWebManager/、/CNP_MServiceProcess/，当请求Nginx服务器的URL地址，和正则表达式匹配，则按照当前location中的规则进行反向代理。

2、 Index

默认页面

3、 proxy_pass

反向代理地址：这里是指向另外tomcat服务URL

 URL的定义，需要有一定的规则，方便Nginx的正则表达式定义、解析。本例中的2个location定义，实现的是Nginx反向代理另外一台服务器上的两种不同类型的业务服务。

四、 安装PHP及Oracle客户端驱动扩展

1、 安装oracle客户端

A、 Oracle客户端安装

官网：http://www.oracle.com/technetwork/database/features/instant-client/index-097480.html

下载如下包：

 oracle-instantclient-basic-10.2.0.5-1.x86_64.rpm

oracle-instantclient-devel-10.2.0.5-1.x86_64.rpm

安装客户端：

# rpm -ivh oracle-instantclient-basic-10.2.0.5-1.x86_64.rpm

# rpm -ivh oracle-instantclient-devel-10.2.0.5-1.x86_64.rpm

B、 建立软连接，使得pdo_oci能够识别64位客户端

#ln -s /usr/include/oracle/10.2.0.5/client64/ /usr/include/oracle/10.2.0.5/client

# ln -s /usr/lib/oracle/10.2.0.5/client64/ /usr/lib/oracle/10.2.0.5/client

# ln -s /usr/include/oracle/10.2.0.5/ /usr/include/oracle/10.2.0.3

# ln -s /usr/lib/oracle/10.2.0.5/ /usr/lib/oracle/10.2.0.3

C、  配置oracle客户端库

#echo "/usr/lib/oracle/10.2.0.5/client/lib/" </etc/ld.so.conf.d/oracle_client.conf

# /sbin/ldconfig

D、  设置客户端环境参数

# vi /etc/profile

配置文件尾部，添加如下配置信息：

export ORACLE_HOME=/usr/lib/oracle/10.2.0.5/client

export LD_LIBRARY_PATH=/usr/lib/oracle/10.2.0.5/client:$LD_LIBRARY_PATH

export NLS_LANG="AMERICAN_AMERICA.AL32UTF8"

执行命令，更新配置

# source /etc/profile

2、 安装re2c-0.13.6.tar.gz

官网地址：http://www.re2c.org/

# tar zxvf re2c-0.13.6.tar.gz

# cd re2c-0.13.6

# ./configure

# make && make install

3、 安装PHP服务

A、 安装PHP依赖包

 libxml2 libxml2-devel autoconf libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel  zlib zlib-devel glibc glibc-devel glib2 glib2-devel  需要自行安装。

B、 安装mcrypt工具

官网地址：http://sourceforge.net/projects/mcrypt/

    http://sourceforge.net/projects/mhash/

下载下列3个包：

libmcrypt-2.5.8.tar.gz

mhash-0.9.9.9.tar.gz

mcrypt-2.6.8.tar.gz

安装命令：

安装libmcrypt

#tar -zxvf libmcrypt-2.5.8.tar.gz

#cd libmcrypt-2.5.8

#./configure

#make && make install

安装mhash

#tar -zxvf mhash-0.9.9.9.tar.gz

#cd mhash-0.9.9.9

#./configure

#make && make install

安装mcrypt

#tar -zxvf mcrypt-2.6.8.tar.gz

#cd mcrypt-2.6.8

#LD_LIBRARY_PATH=/usr/local/lib ./configure

#make && make install

C、 安装libiconv（非必选项）

官网地址：http://www.gnu.org/software/libiconv/

# tar zxvf libiconv-1.14.tar.gz

# cd libiconv-1.14

# ./configure --prefix=/usr/local

# make && make install

D、 安装PHP服务

官网地址：http://php.net/

# tar zxvf php-5.5.13.tar.gz

# cd php-5.5.13

#./configure --prefix=/usr/local/php /

            --with-libdir=lib64 /

            --with-config-file-path=/usr/local/php/etc /

            --with-iconv-dir=/usr/local /

            --with-jpeg-dir=/usr/local/jpeg /

            --with-png-dir=/usr/local/libpng /

            --with-freetype-dir=/usr/local/freetype /

            --with-pcre-regex /

            --with-zlib /

            --with-bz2 /

            --with-curl /

            --with-curlwrappers /

            --with-libxml-dir /

            --with-gd /

            --with-zlib-dir /

            --with-ttf /

            --with-snmp /

            --enable-calendar /

            --enable-dba /

            --enable-ftp /

            --enable-gd-native-ttf /

            --enable-mbstring /

            --enable-pcntl /

            --enable-xml /

            --enable-sockets /

            --enable-zip /

            --enable-bcmath /

            --enable-fpm /

            --enable-fastcgi /

            --enable-force-cgi-redirect /

            --enable-safe-mode /

            --enable-discard-path /

            --disable-gd-jis-conv /

            --disable-sqlite /

            --disable-debug

其中，--enable-fpm为php和Nginx整合所必须的组件

# make

#make install

注：前期编译PHP时不要安装过多扩展选项，免得日后升级麻烦。如果make出错，使用命令make  ZEND_EXTRA_LIBS='-liconv'进行编译。如果出现某些库无法找到错误，实际库文件已经存在，不是系统搜索路径/lib，/usr/lib时，可以通过下列方式实现：

# vi /etc/ld.so.conf

在尾部添加要搜索库路径，如：/usr/local/lib

更新库加载信息：

# ldconfig

4、 安装pdo扩展

    如果php编译中有—disable-pdo选项，本步骤需要编译，同时在php.ini配置文件中，需要添加上extension=pdo_oci.so信息；相反，本步骤无需进行。

# cd /home/lhj/php/php-5.5.13/ext/pdo

# /usr/local/php/bin/phpize

#./configure --with-php-config=/usr/local/php/bin/php-config /

            --enable-pdo=shared

# make && make install

5、 安装oci8扩展

官网地址：http://pecl.php.net/package/oci8

# tar zxvf oci8-2.0.8.tgz

# cd oci8-2.0.8

# /usr/local/php/bin/phpize

# ./configure --with-php-config=/usr/local/php/bin/php-config /

            --with-oci8=instantclient,/usr/lib/oracle/10.2.0.3/client64/lib

# make && make install

6、 安装pdo_oci扩展

官网地址：http://pecl.php.net/package/PDO_OCI

# tar zxvf PDO_OCI-1.0.tgz

# cd PDO_OCI-1.0

# /usr/local/php/bin/phpize

#./configure --with-php-config=/usr/local/php/bin/php-config /

            --with-pdo-oci=instantclient,/usr,10.2.0.3

# make && make install

7、 配置扩展

A、 修改php.ini配置文件

添加如下内容：

extension_dir = "/usr/local/php/lib/php/extensions/"

; extension=pdo.so

extension=pdo_oci.so

extension=oci8.so

 修改文件上传最大上限为100M

upload_max_filesize = 100M

B、 创建并修改php-fpm.conf配置文件

# cp php-fpm.conf.default php-fpm.conf

 打开epoll、log等开关，在env[HOSTNAME] = $HOSTNAME添加如下环境信息：

env[ORACLE_HOME] = $ORACLE_HOME

env[NLS_LANG] = $NLS_LANG

8、 启动php

启动

#/usr/local/php/sbin/php-fpm -c /usr/local/php/etc/php.ini -y /usr/local/php/etc/php-fpm.conf

关闭

#kill -INT `cat /usr/local/php/var/run/php-fpm.pid`

重启

#kill -USR2 `cat /usr/local/php/var/run/php-fpm.pid`

测试配置文件

#/usr/local/php/sbin/php-fpm -c /usr/local/php/etc/php.ini -y /usr/local/php/etc/php-fpm.conf -t

五、 配置本地Nginx服务、整合php-fpm

按照前边Nginx安装的步骤，完成本地Nginx的部署。在nginx.conf配置文件中，添加如下信息：

        listen       9005;

location ~ /.php$ {

            root           html;

            fastcgi_pass   127.0.0.1:9000;

            fastcgi_index  index.php;

            fastcgi_param  SCRIPT_FILENAME  $document_root/$fastcgi_script_name;

            include        fastcgi_params;

        }

借助于php-fpm扩展，通过fastcgi_pass协议，代理php服务，实现php的完整发布。Nginx取代Apache实现普通的Web代理服务。

六、 Nginx反向代理PHP服务

PHP服务的反向代理，和Tomcat的反向代理类似，在前文安装负载均衡Nginx服务器配置文件中，添加如下信息：

location ~ /.php$ {

      proxy_pass  http://192.168.30.202:9005;

}

 实现PHP负载均衡的处理，仅在正则表达式进行php类型请求识别，剩下的工作反向代理给具体PHP业务服务器处理。

总结，Nginx既可以替代apache，提供本地Web代理服务，性能远优于apache；也可以独立部署，实现多种服务的反向代理及负载均衡处理。