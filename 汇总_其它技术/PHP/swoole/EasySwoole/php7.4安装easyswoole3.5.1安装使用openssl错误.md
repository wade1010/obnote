mkdir cgs-new-easyswoole

cd cgs-new-easyswoole



浏览器打开https://github.com/easy-swoole/easyswoole/releases 查看最新版





composer require easyswoole/easyswoole=x.x.x

最新版替换上面的x.x.x



这里使用目前最新版3.5.1



composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/



composer require easyswoole/easyswoole=3.5.1



![](https://gitee.com/hxc8/images8/raw/master/img/202407191108707.jpg)



brew upgrade composer



.......

我艹

原来的php版本是php7.3。现在给我升级到8.1了



为了兼容现在的项目，我卸载了8.1.先安装7.4





这样要重新装了





首先进入 Swoole 的 Github 下载地址: https://github.com/swoole/swoole-src/releases



如果没有特殊需求，请选择最新稳定版本开始下载(我这里是稳定版v4.8.8):



sudo pecl install swoole-4.8.8

安装失败。我猜估计最新版需要php8支持，所以swoole降级。  

swoole-4.7.0也是失败



就再降级

sudo pecl install swoole-4.6.7     (pecl install swoole-4.6.0 这个版本没有让你选择是否启动openssl)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108838.jpg)

这里都选yes吧。免得回头再装。



晕，最后也没成功

```javascript
/private/tmp/pear/install/swoole/include/swoole_ssl.h:27:10: fatal error: 'openssl/ssl.h' file not found
```



试试编译安装吧



wget https://pecl.php.net/get/swoole-4.6.7.tgz



tar zxvf swoole-4.6.7.tgz



cd swoole-4.6.7 



phpize



./configure --with-openssl-dir=/usr/local/opt/openssl@1.1 --enable-openssl



make && sudo make install





检查是否成功



php --ri swoole

```javascript
swoole

Swoole => enabled
Author => Swoole Team <team@swoole.com>
Version => 4.6.7
Built => Apr  2 2022 13:42:51
coroutine => enabled with boost asm context
kqueue => enabled
rwlock => enabled
openssl => OpenSSL 1.1.1n  15 Mar 2022
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





进入正题



composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/



composer require easyswoole/easyswoole=3.5.1



php vendor/bin/easyswoole install   都选Y



```javascript
php vendor/bin/easyswoole install
do you want to release Index.php? [ Y / N (default) ] : y
do you want to release Router.php? [ Y / N (default) ] : y
install success,enjoy!!!
dont forget run composer dump-autoload !!!
```





安装 IDE 代码提示组件

```javascript
composer require easyswoole/swoole-ide-helper
```





# 启动框架

```javascript
php easyswoole server start
```





composer require easyswoole/orm=1.5.x



composer require easyswoole/redis



composer require easyswoole/redis-pool



composer require easyswoole/task



composer require easyswoole/jwt



composer require easyswoole/http-client



composer require php-curl-class/php-curl-class



将原来的code-generation拷贝到vendor/easyswoole下面





composer require easyswoole/queue 3.x



composer require easyswoole/smtp 2.x



composer require ritaswc/zx-ip-address







后来通过宝塔发现php7.4能装swoole4.8.5



```javascript
wget https://pecl.php.net/get/swoole-4.8.5.tgz
tar zxvf swoole-4.8.5.tgz
cd swoole-4.8.5
find / -name 'phpize' 2> /dev/null
/usr/local/Cellar/php@7.4/7.4.28_1/bin/phpize
./configure --enable-openssl --with-openssl-dir=/usr/local/opt/openssl@3 --with-php-config=/usr/local/Cellar/php@7.4/7.4.28_1/bin/php-config
make && make install
```



php --ri swoole 检查是否成功

```javascript
php --ri swoole                                                                                            ~

swoole

Swoole => enabled
Author => Swoole Team <team@swoole.com>
Version => 4.8.5
Built => Apr  3 2022 22:55:15
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





