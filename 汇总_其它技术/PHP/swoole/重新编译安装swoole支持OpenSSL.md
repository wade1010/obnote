- 下载：wget https://pecl.php.net/get/swoole-4.5.6.tgz
- tar zxvf swoole-4.5.6.tgz
- cd swoole-4.5.6
- phpize
- whereis php-config 查看php-config路径 替换下面的--with-php-config路径
- ./configure --enable-openssl --with-php-config=/usr/bin/php-config
- make && make install 
-  php --ri swoole 检查是否成功
```
➜  swoole-4.5.6 php --ri swoole     

swoole

Swoole => enabled
Author => Swoole Team <team@swoole.com>
Version => 4.5.6
Built => Nov  1 2020 23:00:06
coroutine => enabled
epoll => enabled
eventfd => enabled
signalfd => enabled
cpu_affinity => enabled
spinlock => enabled
rwlock => enabled
openssl => OpenSSL 1.0.2k-fips  26 Jan 2017
pcre => enabled
zlib => 1.2.7
mutex_timedlock => enabled
pthread_barrier => enabled
futex => enabled
async_redis => enabled

Directive => Local Value => Master Value
swoole.enable_coroutine => On => On
swoole.enable_library => On => On
swoole.enable_preemptive_scheduler => Off => Off
swoole.display_errors => On => On
swoole.use_shortname => On => On
swoole.unixsock_buffer_size => 8388608 => 8388608
```

#### 参考 

https://www.cnblogs.com/hodge01/p/8658296.html