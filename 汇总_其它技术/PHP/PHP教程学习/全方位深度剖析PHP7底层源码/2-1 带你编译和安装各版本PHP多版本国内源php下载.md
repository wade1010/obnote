下载地址

https://www.php.net/releases/



wget https://www.php.net/distributions/php-7.1.0.tar.gz 



mkdir php-7.1.0-debug



tar -zxvf php-7.1.0.tar.gz -C php-7.1.0-debug





cd  php-7.1.0-debug/php-7.1.0





./configure -h >conf_help.txt





vim conf_help.txt  大致了解下





./configure --prefix=/home/cheng/Desktop/data/php/php-7.1.0-debug/php-7.1.0/output --enable-fpm --enable-debug





make&&make install





cd output



ll



```javascript
➜  output ll
总用量 0
drwxrwxr-x 2 root root 146 11月  8 11:27 bin
drwxrwxr-x 3 root root  68 11月  8 11:27 etc
drwxrwxr-x 3 root root  17 11月  8 11:27 include
drwxrwxr-x 3 root root  17 11月  8 11:27 lib
drwxrwxr-x 4 root root  28 11月  8 11:27 php
drwxrwxr-x 2 root root  21 11月  8 11:27 sbin
drwxrwxr-x 4 root root  28 11月  8 11:27 var
```





回到根目录



wget https://www.php.net/distributions/php-5.6.40.tar.gz



wget https://www.php.net/distributions/php-7.0.29.tar.gz



wget https://www.php.net/distributions/php-7.2.33.tar.gz





wget https://www.php.net/distributions/php-7.3.21.tar.gz





wget https://www.php.net/distributions/php-7.4.8.tar.gz



国内源    http://php.p2hp.com/downloads.php



wget  http://mirrors.sohu.com/php/php-7.4.12.tar.gz











