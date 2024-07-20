### 一、安装PHP5.6扩展
#### brew-php-switcher 5.6切换到php5.6

##### 1、安装yaf-2.3.5 不要太高，高版本需要PHP7

##### 我在桌面建一个文件夹yaf

> mkdir ~/Desktop/yaf&&cd ~/Desktop/yaf

> wget https://pecl.php.net/get/yaf-2.3.5.tgz  (慢的话直接复制链接到浏览器下载)

> tar -zxvf yaf-2.3.5.tgz && cd yaf-2.3.5

> find / -name 'phpize'  2> /dev/null  

> find / -name 'php-config'  2> /dev/null

利用上面find命令找到 对应版本的phpize 和 php-config 在哪里

> /usr/local/Cellar/php@5.6/5.6.40/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php@5.6/5.6.40/bin/php-config && make && make test

> mkdir -p /usr/local/lib/php/pecl/20131226

##### 我根据php.ini里面默认的extension_dir创建了目录/usr/local/lib/php/pecl/20131226

> cp modules/yaf.so /usr/local/lib/php/pecl/20131226

> find / -name 'php.ini'  2> /dev/null

> sudo vim /usr/local/etc/php/5.6/php.ini


再在最下方添加如下代码

```
[yaf]
yaf.environ = local
yaf.library = NULL
yaf.cache_config = 0
yaf.name_suffix = 1
yaf.name_separator = ""
yaf.forward_limit = 5
yaf.use_namespace = 0
yaf.use_spl_autoload = 0
extension=yaf.so
```

> 上面 yaf.environ 本地就用local 线上就用product (我的用法)

> sudo apachectl restart

> php -i|grep yaf  

查得到就OK了    

但是可能会有个warning


PHP Warning:  Unknown: It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. in Unknown on line 0


> sudo vim /usr/local/etc/php/5.6/php.ini

找到 date.timezone  去掉注释  值等于 PRC

date.timezone = PRC


##### 2、安装redis

##### 我在桌面建一个文件夹redis

> mkdir ~/Desktop/redis&&cd ~/Desktop/redis

> wget https://pecl.php.net/get/redis-4.3.0.tgz   （这个是支持php5的最后一个版本）

> tar -zxvf redis-4.3.0.tgz && cd redis-4.3.0

> /usr/local/Cellar/php@5.6/5.6.40/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php@5.6/5.6.40/bin/php-config && make && make test

> cp modules/redis.so /usr/local/lib/php/pecl/20131226

> sudo vim /usr/local/etc/php/5.6/php.ini

在最下方加入如下内容
```
[redis]
extension = redis.so
```

##### 3、安装xdebug

##### 我在桌面建一个文件夹xdebug

> mkdir ~/Desktop/xdebug&&cd ~/Desktop/xdebug

> wget https://pecl.php.net/get/xdebug-2.5.0.tgz  

> tar -zxvf xdebug-2.5.0.tgz && cd xdebug-2.5.0

> /usr/local/Cellar/php@5.6/5.6.40/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php@5.6/5.6.40/bin/php-config && make && make test

> cp modules/xdebug.so /usr/local/lib/php/pecl/20131226

> sudo vim /usr/local/etc/php/5.6/php.ini

在最下方加入如下内容
```
[xdebug]
zend_extension = xdebug.so
xdebug.auto_trace = on
xdebug.auto_profile = on
xdebug.collect_params = on
xdebug.collect_return = on
xdebug.profiler_enable = on
xdebug.trace_output_dir = "/tmp"
xdebug.profiler_output_dir = "/tmp"
xdebug.dump.GET = *
xdebug.dump.POST = *
xdebug.dump.COOKIE = *
xdebug.dump.SESSION = *
xdebug.remote_enable = on
xdebug.remote_handler = dbgp
xdebug.remote_host = 127.0.0.1
xdebug.remote_port = 9010
xdebug.remote_autostart = 1
xdebug.idekey = PHPSTORM
```


---

---

---

### 二、安装PHP7.3扩展 统一都用pecl上下载编译安装
#### brew-php-switcher 7.3 切换到php7.3

##### 1、安装yaf最新版

##### 我在桌面建一个文件夹yaf7

> mkdir ~/Desktop/yaf7&&cd ~/Desktop/yaf7

> wget https://pecl.php.net/get/yaf-3.2.3.tgz  (目前最新stable版本就是3.2.3,慢的话直接复制链接到浏览器下载)

> tar -zxvf yaf-3.2.3.tgz && cd yaf-3.2.3

> find / -name 'phpize'  2> /dev/null  

> find / -name 'php-config'  2> /dev/null

利用上面find命令找到 对应版本的phpize 和 php-config 在哪里

> /usr/local/Cellar/php@7.3/7.3.18/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php@7.3/7.3.18/bin/php-config && make && make test

> mkdir -p /usr/local/lib/php/pecl/20180731

##### 我根据php.ini里面默认的extension_dir创建了目录/usr/local/lib/php/pecl/20180731

> cp modules/yaf.so /usr/local/lib/php/pecl/20180731

> find / -name 'php.ini'  2> /dev/null

> sudo vim /usr/local/etc/php/7.3/php.ini

打开编辑后先配置 extension_dir

再在最下方添加如下代码

```
[yaf]
yaf.environ = local
yaf.library = NULL
yaf.cache_config = 0
yaf.name_suffix = 1
yaf.name_separator = ""
yaf.forward_limit = 5
yaf.use_namespace = 0
yaf.use_spl_autoload = 0
extension=yaf.so
```

> 上面 yaf.environ 本地就用local 线上就用product (我的用法)

> sudo apachectl restart

> php -i|grep yaf  

查得到就OK了

##### 2、安装redis

##### 我在桌面建一个文件夹redis

> mkdir ~/Desktop/redis7&&cd ~/Desktop/redis7

> wget https://pecl.php.net/get/redis-5.2.2.tgz

> tar -zxvf redis-5.2.2.tgz && cd redis-5.2.2

> /usr/local/Cellar/php@7.3/7.3.18/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php@7.3/7.3.18/bin/php-config && make && make test

> cp modules/redis.so /usr/local/lib/php/pecl/20180731

> sudo vim /usr/local/etc/php/7.3/php.ini

在最下方加入如下内容
```
[redis]
extension = redis.so
```

##### 3、安装xdebug

##### 我在桌面建一个文件夹xdebug

> mkdir ~/Desktop/xdebug7&&cd ~/Desktop/xdebug7

> wget https://pecl.php.net/get/xdebug-2.9.5.tgz 

> tar -zxvf xdebug-2.9.5.tgz && cd xdebug-2.9.5

> /usr/local/Cellar/php@7.3/7.3.18/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php@7.3/7.3.18/bin/php-config && make && make test

> cp modules/xdebug.so /usr/local/lib/php/pecl/20180731

> sudo vim /usr/local/etc/php/7.3/php.ini


在最下方加入如下内容
```
[xdebug]
zend_extension = xdebug.so
xdebug.auto_trace = on
xdebug.auto_profile = on
xdebug.collect_params = on
xdebug.collect_return = on
xdebug.profiler_enable = on
xdebug.trace_output_dir = "/tmp"
xdebug.profiler_output_dir = "/tmp"
xdebug.dump.GET = *
xdebug.dump.POST = *
xdebug.dump.COOKIE = *
xdebug.dump.SESSION = *
xdebug.remote_enable = on
xdebug.remote_handler = dbgp
xdebug.remote_host = 127.0.0.1
xdebug.remote_port = 9010
xdebug.remote_autostart = 1
xdebug.idekey = PHPSTORM
```


如果PHP7.4使用7.3的.so文件会报下面的错,yaf为例

```
Xdebug requires Zend Engine API version 320180731.
The Zend Engine API version 320190902 which is installed, is newer.
Contact Derick Rethans at https://xdebug.org/docs/faq#api for a later version of Xdebug.

PHP Warning:  PHP Startup: yaf: Unable to initialize module
Module compiled with module API=20180731
PHP    compiled with module API=20190902
These options need to match
 in Unknown on line 0
PHP Warning:  PHP Startup: redis: Unable to initialize module
Module compiled with module API=20180731
PHP    compiled with module API=20190902
These options need to match
 in Unknown on line 0
Warning: PHP Startup: yaf: Unable to initialize module
```

---

---

---

### 三、安装PHP7.4扩展 统一都用pecl上下载编译安装
#### brew-php-switcher 7.4 切换到php7.4

##### 1、安装yaf最新版

##### cd ~/Desktop/yaf7 然后删掉除tgz包之外的文件

> tar -zxvf yaf-3.2.3.tgz && cd yaf-3.2.3

> find / -name 'phpize'  2> /dev/null  

> find / -name 'php-config'  2> /dev/null

利用上面find命令找到 对应版本的phpize 和 php-config 在哪里

> /usr/local/Cellar/php/7.4.6/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php/7.4.6/bin/php-config && make && make test

> mkdir -p /usr/local/lib/php/pecl/20190902

##### 我根据php.ini里面默认的extension_dir创建了目录/usr/local/lib/php/pecl/20190902

> cp modules/yaf.so /usr/local/lib/php/pecl/20190902

> sudo vim /usr/local/etc/php/7.4/php.ini

打开编辑后先配置 extension_dir

再在最下方添加如下代码

```
[yaf]
yaf.environ = local
yaf.library = NULL
yaf.cache_config = 0
yaf.name_suffix = 1
yaf.name_separator = ""
yaf.forward_limit = 5
yaf.use_namespace = 0
yaf.use_spl_autoload = 0
extension=yaf.so
```

> 上面 yaf.environ 本地就用local 线上就用product (我的用法)

> sudo apachectl restart

> php -i|grep yaf  

查得到就OK了

##### 2、安装redis

##### cd ~/Desktop/redis7 然后删掉除tgz包之外的文件

> tar -zxvf redis-5.2.2.tgz && cd redis-5.2.2

> /usr/local/Cellar/php/7.4.6/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php/7.4.6/bin/php-config && make && make test

> cp modules/redis.so /usr/local/lib/php/pecl/20190902

> sudo vim /usr/local/etc/php/7.4/php.ini

在最下方加入如下内容
```
[redis]
extension = redis.so
```

##### 3、安装xdebug

##### cd ~/Desktop/xdebug7 然后删掉除tgz包之外的文件

> tar -zxvf xdebug-2.9.5.tgz && cd xdebug-2.9.5

> /usr/local/Cellar/php/7.4.6/bin/phpize

> ./configure --with-php-config=/usr/local/Cellar/php/7.4.6/bin/php-config && make && make test

> cp modules/xdebug.so /usr/local/lib/php/pecl/20190902

> sudo vim /usr/local/etc/php/7.4/php.ini


在最下方加入如下内容
```
[xdebug]
zend_extension = xdebug.so
xdebug.auto_trace = on
xdebug.auto_profile = on
xdebug.collect_params = on
xdebug.collect_return = on
xdebug.profiler_enable = on
xdebug.trace_output_dir = "/tmp"
xdebug.profiler_output_dir = "/tmp"
xdebug.dump.GET = *
xdebug.dump.POST = *
xdebug.dump.COOKIE = *
xdebug.dump.SESSION = *
xdebug.remote_enable = on
xdebug.remote_handler = dbgp
xdebug.remote_host = 127.0.0.1
xdebug.remote_port = 9010
xdebug.remote_autostart = 1
xdebug.idekey = PHPSTORM
```