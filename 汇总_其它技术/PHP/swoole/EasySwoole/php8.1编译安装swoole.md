brew search php



brew install php



```javascript
php -v                     
PHP 8.1.4 (cli) (built: Mar 18 2022 09:44:47) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.1.4, Copyright (c) Zend Technologies
    with Zend OPcache v8.1.4, Copyright (c), by Zend Technologies
```



https://pecl.php.net/package/swoole  查看最新版当前是4.8.8



```javascript
wget https://pecl.php.net/get/swoole-4.8.8.tgz

tar zxvf swoole-4.8.8.tgz

cd swoole-4.8.8
```



```javascript
find / -name 'phpize' 2> /dev/null
```

找到php8.1的phpize

```javascript
/usr/local/Cellar/php/8.1.4/bin/phpize

find / -name 'php-config' 2> /dev/null 

./configure --enable-openssl --with-php-config=/usr/local/Cellar/php/8.1.4/bin/php-config

make && make install

发现报错swoole-4.8.8/include/swoole_ssl.h:27:10: fatal error: 'openssl/ssl.h' file not found

添加参数

./configure --enable-openssl --with-openssl-dir=/usr/local/opt/openssl@3 --with-php-config=/usr/local/Cellar/php/8.1.4/bin/php-config

make && make install

配置php.ini
修改php.ini 末行加入extension=swoole.so

php --ri swoole 检查是否成功
```



```javascript
 php --ri swoole

swoole

Swoole => enabled
Author => Swoole Team <team@swoole.com>
Version => 4.8.8
Built => Apr  2 2022 21:09:17
coroutine => enabled with boost asm context
kqueue => enabled
rwlock => enabled
openssl => OpenSSL 3.0.2 15 Mar 2022
dtls => enabled
pcre => enabled
zlib => 1.2.11
brotli => E16777225/D16777225
async_redis => enabled

Directive => Local Value => Master Value
swoole.enable_coroutine => On => On
swoole.enable_library => On => On
swoole.enable_preemptive_scheduler => Off => Off
swoole.display_errors => On => On
swoole.use_shortname => On => On
swoole.unixsock_buffer_size => 262144 => 262144
```

