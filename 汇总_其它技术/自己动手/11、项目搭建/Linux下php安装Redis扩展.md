git  clone https://github.com/phpredis/phpredis.git

cd phpredis

phpize
./configure --with-php-config=/usr/local/php/bin/php-config

make && make install





然后

1、配置php支持

vi /usr/local/php/etc/php.ini  #编辑配置文件，在最后一行添加以下内容

添加

extension="redis.so"

:wq! #保存退出

 

 

2、 重启服务