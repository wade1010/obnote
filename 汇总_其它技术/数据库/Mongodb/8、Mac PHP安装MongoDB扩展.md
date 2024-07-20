下载地址 https://pecl.php.net/package/mongodb



1. wget https://pecl.php.net/get/mongodb-1.8.2.tgz

1. tar zxvf mongodb-1.8.2.tgz

1. cd mongodb-1.8.2

1. phpize

1. which php-config  获得php-config的路径

1.     ./configure --with-php-config=/usr/local/bin/php-config

1.  make && make install





找到自己的php.ini 位置(可以通过命令  php -ini|grep 'Configuration File')



vim php.ini



加入

```javascript
extension=mongodb.so
```





查看PHP的mongodb扩展模块是否已经成功安装



```javascript
php -m | grep mongodb
```



看不到就重启下php试试