一、安装MySQL

        目前web服务器已经很少有跑静态页面的，如果要跑动态网站那当然就离不开数据库，虽然在以前文章中有写MySQL是怎么安装的，但是感觉好久没装MySQL，现在只把步骤贴出来，就不做过多的讲解了

#useradd mysql

#tar zxvf mysql-5.0.40.tar.gz

#cd mysql-5.0.40

#./configure --prefix=/usr/local/mysql

#make && make install

#/usr/local/mysql/bin/mysql_install_db --user=mysql          //初始化MySQL数据库

#chown -R mysql /usr/local/mysql/var

#/usr/local/mysql/bin/mysqld_safe &          //启动MySQL

#/usr/local/mysql/bin/mysqladmin -u root password 123456    //设置MySQL密码

#cp support-files/my-medium.cnf /etc/my.cnf

#echo "/usr/local/mysql/bin/mysqld_safe &" >>/etc/rc.local

二、安装PCRE

         PCRE是perl所用到的正则表达式，目的是让所装的软件支持正则表达式。默认情况下，Nginx只处理静态的网页请求，也就是html.如果是来自动态的网页请求，比如*.php，那么Nginx就要根据正则表达式查询路径，然后把*.PHP交给PHP去处理

#rpm -qa | grep pcre              //查询系统中有没有安装PCRE，一般装系统是默认装有，所以我们要删掉系统自带的

#cp /lib/libpcre.so.0 /           //在删除系统自带的PCRE之前，要先备份一下libpcre.so.0这个文件，因为RPM包的关联性太强，在删除后没libpcre.so.0这个文件时我们装PCRE是装不上的

#rpm -e --nodeps pcre-6.6-1.1          //删除系统自带的PCRE

# tar zxvf pcre-8.00.tar.gz

#cd pcre-8.00

#cp /libpcre.so.0 /lib/              //把我们删除系统自带的PCRE之前备份的libpcre.so.0拷贝到/lib 目录下

#./configure         //配置PCRE，因为PCRE是一个库，而不是像pache、php、postfix等这样的程序，所以我们安装时选择默认路径即可，这样会在后面安装其它东西时避免一些不必要的麻烦，执行完这部后会显示出下图，上面显示了我们对PCRE的配置

#make && make install

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/F513D31064D54868B028865FBA08CC5720110322213430555.jpg.png)

三、安装Nginx

       在网上，看到不少人装Nginx 时非常麻烦，配置时用了一大堆选项，请问你们真实现那么多功能么？害的我越看越郁闷。此次安装Nginx如果是按着上面笔者的步骤一步步走下来，安装Nginx时只需指定Nginx的安装路径即可

#tar zxvf nginx-0.8.24.tar.gz

#cd nginx-0.8.24

#./configure --prefix=/usr/local/nginx     //此处在本环节只需指定一个路径

#make && make install

#/usr/local/nginx/sbin/nginx       //启Nginx

#echo "/usr/local/nginx/sbin/nginx" >>/etc/rc.local

Nginx启动后有两个进程，master为主进程，worker为工作进程，如下图

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/E8638730492F43B18023C58E5A915E4620110322213430592.jpg.jpeg)

在启动完NGINX后，我们可以在浏览器中输入http://localhost查看，如下图

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/E574E9AC6A97466B925E78301D39FD1A20110322213430170.jpg.jpeg)

四、安装PHP

        既然安装PHP，那GD便是不可少的，在此GD的安装不再进行描述

1、安装libpng

#tar xvf libpng-1.2.10.tar.tar

#cd libpng-1.2.10

#./configure --prefix=/usr/local/png

#make;make install

#ln -s /usr/local/png/lib/* /usr/lib/

2、安装jpeg

#mkdir /usr/local/jpeg

#mkdir /usr/local/jpeg/bin

#mkdir /usr/local/jpeg/lib

#mkdir /usr/local/jpeg/include

#mkdir /usr/local/jpeg/man

#mkdir /usr/local/jpeg/man/man1

#tar xvf jpegsrc.v7.tar.tar

#cd jpeg-7

#./configure --prefix=/usr/local/jpeg --enable-shared --enable-static

#make;make install

#ln -s /usr/local/jpeg/lib/* /usr/lib/

3、安装 freetype

#tar xvf freetype-2.3.9.tar.tar

#cd freetype-2.3.9

#./configure --prefix=/usr/local/freetype

#make;make install

4、安装fontconfig

#tar zxvf fontconfig-2.4.2.tar.gz

#cd fontconfig-2.4.2

#./configure --prefix=/usr/local/fontconfig --with-freetype-config=/usr/local/freetype/bin/freetype-config

#make;make install

5、安装GD

#tar zxvf gd-2.0.32.tar.gz

#cd gd-2.0.32

#./configure --prefix=/usr/local/gd --with-png=/usr/local/png --with-jpeg=/usr/local/jpeg --with- freetype=/usr/local/freetype --with-fontconfig=/usr/local/fontconfig

#cp /usr/local/png/include/png.h ./

#cp /usr/local/png/include/pngconf.h ./

#make;make install

6、安装PHP

这个地方是最重要的地方，因为默认情况下Nginx和PHP他俩之间是一点感觉没有的。在之前，很多朋友都搭建过Apache+PHP，Apache+PHP编译后生成的是模块文件，而Nginx+PHP需要PHP生成可执行文件才可以，所以要利用fastcgi技术来实现N ginx与PHP的整合，这个只要我们安装是启用FastCGI即可。此次我们安装PHP不仅使用了FastCGI，而且还使用了PHP-FPM这么一个东东，PHP-FPM说白了是一个管理FastCGI的一个管理器，它作为PHP的插件纯在，在安装PHP要想使用PHP-FPM时就需要把PHP-FPM以补丁的形式安装到PHP中，而且PHP要与PHP-FPM版本一致，这是必须的，切记！

      首先我们把PHP和PHP-FPM下载到同一目录下，此次用的为php-5.3.0.tar.bz2和php-5.3.0-fpm-0.5.12.diff.gz，下载到了同一目录下

#tar xvf php-5.3.0.tar.bz2

#gzip -cd php-5.3.0-fpm-0.5.12.diff.gz | patch -d php-5.3.0 -p1         //将php-5.3.0-fpm-0.5.12.diff.gz以补丁形式加到php-5.3.0里面

#cd php-5.3.0

#./configure --prefix=/usr/local/php --with-gd=/usr/local/gd --with-jpeg-dir=/usr/local/jpeg --with-png-dir=/usr/local/png --with-freetype-dir=/usr/local/freetype --with-mysql=/usr/local/mysql --enable-fastcgi --enable-fpm

注：Nginx+PHP整合，在安装时必须启用--enable-fastcgi和 --enable-fpm，这两个选项是做什么的上面已经描述。执行完后系统会提示--enable-fastcgi是一个未知选项，我们不必理会

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/7FB3F514D91C49B1BFEA74A5F61D126920110322213430298.jpg.png)

#make

#make install

#cp php.ini-dist /usr/local/php/etc/php.ini

下面我们就要启动PHP-FPM

#/usr/local/php/sbin/php-fpm start

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/9A7A61CDA10E454D9386C49DA4AACAB420110322213430639.jpg.jpeg)

在启动PHP-FPM时会报上面这个错误，原因是PHP-FPM自己不知道以那个用户和组运行PHP，所以我们要修改一个文件，把文件中的注释去掉即可（打开文件把红色部分删除），然后PHP-FPM会以nobody用户和组去运行PHP。

#vi /usr/local/php/etc/php-fpm.conf

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/C8C4E4E28FBF47B9B3161AE89A3DE07020110322213430252.jpg.jpeg)

#/usr/local/php/sbin/php-fpm start

#ps -aux | grep php

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/F7C47062EA374E7C87E6B7667F2CF12120110322213430268.jpg.jpeg)

#echo "/usr/local/php/sbin/php-fpm start" >>/etc/rc.local

五、整合Nginx与PHP

上面已经讲过，Nginx自己并不处理动态网页的请求，而且Nginx将得到的动态请求转交给PHP，下面我们打开Nginx的配置文件看一下

#vi /usr/local/nginx/conf/nginx.conf        //标的部分是我们后面要修改的

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/7757E6748ADB4265987F6706F03B911220110322213431231.jpg.png)

看上图，Nginx已经知道怎么把得到的请求传达给PHP，Nginx在得到*.php请求时，会把请求通过9000端口传给PHP。下面我们把这些注释给去掉即可，如下图

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/8142E6D02D0745D9A49D97E66B3A8D1F20110322213434426.jpg.jpeg)

注：上面的/usr/local/nginx/html 是我们PHP网站放置的路径

      那么只有Nginx自己知道咋找PHP了还不行，还需要PHP知道咋找Nginx，PS：你见过大街上的JJMM约会时有不是相互认识对方，或者是不知道用啥方法和对方接头的？这点我们不需要担心，PHP-FPM已经在配置文件中定义了从哪接受PHP请求，我们可以打开配置文件看一下

#vi /usr/local/php/etc/php-fpm.conf

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/E23C22A59BE9499F97D9C310E783238220110322213437548.jpg.jpeg)

如上图所示，我们在前面已经看到过Nginx是通过本机的9000端口将PHP请求转发给PHP的，而上图我们可以看到PHP自己是从本机的9000端口侦听数据 ，Nginx与PHP通过本机的9000端口完成了数据请求。

六、测试

我们在nginx的配置文件里面已经定义了PHP网站的存放路径，路径问/usr/local/nginx/html

下面我们在这个目录下新建一个PHP页面测试网页，文件名为test.php，内容如下

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/41D7C6501BD94EAD8A8B22A0CF4FC50020110322213440260.jpg.png)

重启PHP与nginx后(可以用杀死进程的方式关闭，然后在启动)我们在浏览器中输入http://localhost/test.php，出现如下界面算成功

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/images/1A8A8239535742779BA720C19258027C20110322213443966.jpg.jpeg)

