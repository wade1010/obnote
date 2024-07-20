原文地址：http://www.lifelinux.com/how-to-install-nginx-and-php-fpm-on-centos-6-via-yum/



开始安装Nginx和PHP-FPM之前,你必须卸载系统中以前安装的Apache和PHP。用root登录输入下面的命令：

[plain]view plaincopy

1. # yum remove httpd* php*  



增加额外资源库

    默认情况下，CentOS的官方资源是没有php-fpm的, 但我们可以从Remi的RPM资源中获得，它依赖于EPEL资源。我们可以这样增加两个资源库：

[plain]view plaincopy

1. # yum install yum-priorities -y  

1. # rpm -Uvh http://download.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm  

1. # rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm  

1. # yum --enablerepo=remi install php php-fpm



输出样例

[plain]view plaincopy

1. Retrieving http://download.fedora.redhat.com/pub/epel/6/x86_64/epel-release-6-7.noarch.rpm  

1. warning: /var/tmp/rpm-tmp.00kiDx: Header V3 RSA/SHA256 Signature, key ID 0608b895: NOKEY  

1. Preparing...########################################### [100%]  

1. 1:epel-release ########################################### [100%]  



安装Nginx

输入下列命令

[plain]view plaincopy

1. # yum install nginx  

输出样例

[plain]view plaincopy

1. Dependencies Resolved  

1. ================================================================================  

1.  Package                  Arch      Version                    Repository  Size  

1. ================================================================================  

1. Installing:  

1.  nginx                    x86_64    0.8.54-1.el6               epel       358 k  

1. Installing for dependencies:  

1.  GeoIP                    x86_64    1.4.8-1.el6                epel       620 k  

1.  fontconfig               x86_64    2.8.0-3.el6                base       186 k  

1.  freetype                 x86_64    2.3.11-6.el6_1.8           updates    358 k  

1.  gd                       x86_64    2.0.35-10.el6              base       142 k  

1.  libX11                   x86_64    1.3-2.el6                  base       582 k  

1.  libX11-common            noarch    1.3-2.el6                  base       188 k  

1.  libXau                   x86_64    1.0.5-1.el6                base        22 k  

1.  libXpm                   x86_64    3.5.8-2.el6                base        59 k  

1.  libjpeg                  x86_64    6b-46.el6                  base       134 k  

1.  libpng                   x86_64    2:1.2.46-1.el6_1           base       180 k  

1.  libxcb                   x86_64    1.5-1.el6                  base       100 k  

1.  libxslt                  x86_64    1.1.26-2.el6               base       450 k  

1.  perl                     x86_64    4:5.10.1-119.el6_1.1       base        10 M  

1.  perl-Module-Pluggable    x86_64    1:3.90-119.el6_1.1         base        37 k  

1.  perl-Pod-Escapes         x86_64    1:1.04-119.el6_1.1         base        30 k  

1.  perl-Pod-Simple          x86_64    1:3.13-119.el6_1.1         base       209 k  

1.  perl-libs                x86_64    4:5.10.1-119.el6_1.1       base       575 k  

1.  perl-version             x86_64    3:0.77-119.el6_1.1         base        49 k  

1. Transaction Summary  

1. ================================================================================  

1. Install      19 Package(s)  

1. Upgrade       0 Package(s)  

1. Total download size: 14 M  

1. Installed size: 47 M  

1. Is this ok [y/N]: y  



如果你想在系统启动时自动运行nginx，输入下列命令：

[plain]view plaincopy

1. # chkconfig --level 345 nginx on  



第一次启动nginx，输入下列命令：

[plain]view plaincopy

1. # /etc/init.d/nginx start  



输出样例

[plain]view plaincopy

1. Starting nginx:                                            [  OK  ]  



安装PHP-FPM

输入下列命令：

[plain]view plaincopy

1. # yum --enablerepo=remi install php php-fpm  



输出样例

[plain]view plaincopy

1. Dependencies Resolved  

1. ====================================================================================  

1.  Package            Arch        Version                          Repository    Size  

1. ====================================================================================  

1. Installing:  

1.  php                x86_64      5.3.10-2.el6.remi                remi         2.3 M  

1.  php-fpm            x86_64      5.3.10-2.el6.remi                remi         1.1 M  

1. Installing for dependencies:  

1.  apr                x86_64      1.3.9-3.el6_1.2                  base         123 k  

1.  apr-util           x86_64      1.3.9-3.el6_0.1                  base          87 k  

1.  apr-util-ldap      x86_64      1.3.9-3.el6_0.1                  base          15 k  

1.  httpd              x86_64      2.2.15-15.el6.centos.1           updates      813 k  

1.  httpd-tools        x86_64      2.2.15-15.el6.centos.1           updates       70 k  

1.  libedit            x86_64      2.11-4.20080712cvs.1.el6         base          74 k  

1.  mailcap            noarch      2.1.31-2.el6                     base          27 k  

1.  php-cli            x86_64      5.3.10-2.el6.remi                remi         2.2 M  

1. Transaction Summary  

1. ====================================================================================  

1. Install      10 Package(s)  

1. Upgrade       0 Package(s)  

1. Total download size: 6.8 M  

1. Installed size: 21 M  

1. Is this ok [y/N]: y  



如果你想在系统启动时自动运行php-fpm，输入下列命令：

[plain]view plaincopy

1. # chkconfig --level 345 php-fpm on  



PHP仅安装了核心模块，你很可能需要安装其他的模块，比如MySQL、 XML、 GD等等，你可以输入下列命令：

[plain]view plaincopy

1. # yum --enablerepo=remi install php-gd php-mysql php-mbstring php-xml php-mcrypt  



第一次启动php-fpm，输入下列命令：

[plain]view plaincopy

1. # /etc/init.d/php-fpm restart  



输出样例

[plain]view plaincopy

1. Starting php-fpm:                                          [ OK ]  

配置PHP-FPM和Nginx，让他们一起工作

nginx的配置文件在/etc/nginx/nginx.conf，输入下列命令编辑这个文件：

[plain]view plaincopy

1. # vi /etc/nginx/nginx.conf  



像下面这样编辑取消注释：

[plain]view plaincopy

1.        ...  

1. location / {  

1.            root   /usr/share/nginx/html;  

1.            index  index.html index.htm index.php;  

1.        }  

1.        ...  

1. location ~ \.php$ {  

1.            root           html;  

1.            fastcgi_pass   127.0.0.1:9000;  

1.            fastcgi_index  index.php;  

1.            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;  

1.            include        fastcgi_params;  

1.        }  

1.        ...  



重启Nginx会重新读取配置文件，输入

[plain]view plaincopy

1. # /etc/init.d/nginx reload  



现在在 document root目录下建立下列PHP文件



[plain]view plaincopy

1. # vi /usr/share/nginx/html/info.php  



文件内容如下：

[plain]view plaincopy

1. phpinfo();  

1. ?>  



访问 http://YOUR-SERVER-IP

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/A169B8E5161D46F38AE34A31171587DD1340184855_7583.jpg.jpeg)



Nginx虚拟主机设置

设置例子

IP: 192.168.1.113

Domain: domain.local

Hosted at: /home/www/domain.local

输入下列命令新建名叫“www”的用户

[plain]view plaincopy

1. # useradd www  

创建必要的目录

[plain]view plaincopy

1. # mkdir -p /home/www/domain.local/public_html  

1. # mkdir -p /home/www/domain.local/log  

1. # chown -R www.www /home/www/  

1. # chmod 755 /home/www/  



创建虚拟主机配置文件

[plain]view plaincopy

1. # cd /etc/nginx/conf.d/  

1. # cp virtual.conf www.conf  



输入下面命令打开www.conf文件

[plain]view plaincopy

1. # vi /etc/nginx/conf.d/www.conf  



增加以下配置

[plain]view plaincopy

1. server {  

1.         server_name  domain.local;  

1.         root /home/www/domain.local/public_html;  

1.         access_log /home/www/domain.local/log/domain.local-access.log;  

1.         error_log /home/www/domain.local/log/domain.local-error.log;  

1.         location / {  

1.                 index  index.html index.htm index.php;  

1.         }  

1.         location ~ \.php$ {  

1.                 include /etc/nginx/fastcgi_params;  

1.                 fastcgi_pass  127.0.0.1:9000;  

1.                 fastcgi_index index.php;  

1.                 fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;  

1.         }  

1. }  



你可以用下列方法检查配置文件是否有语法错误

[plain]view plaincopy

1. # /etc/init.d/nginx configtest  



输出样例

[plain]view plaincopy

1. the configuration file /etc/nginx/nginx.conf syntax is ok  

1. configuration file /etc/nginx/nginx.conf test is successful  



现在编辑 /etc/php-fpm.d/www.conf文件，将运行php-fpm进程的用户改为“www”，输入

[plain]view plaincopy

1. # vi /etc/php-fpm.d/www.conf  

找到“ group of processes”，编辑成下面的样子：

[plain]view plaincopy

1. ; Unix user/group of processes  

1. ; Note: The user is mandatory. If the group is not set, the default user's group  

1. ;       will be used.  

1. ; RPM: apache Choosed to be able to access some dir as httpd  

1. user = www  

1. ; RPM: Keep a group allowed to write in log dir.  

1. group = www  



最后重启nginx

[plain]view plaincopy

1. # /etc/init.d/nginx restart  

1. # /etc/init.d/php-fpm restart  









