前言：

核心版本除了提供了客户端的基本实现的基类以外，还提供了 HTTP 客户端和 Socket 客户端。这两个客户端都可以创建为同步或异步客户端。这两个客户端既可以在命令行环境下使用，也可以在 php-fpm 或其他 PHP 环境下使用。

另外 swoole 版本 提供了纯异步的 HTTP 客户端，Socket 客户端和 WebSocket 客户端。Swoole 客户端只能在命令行环境下使用。

其中 HTTP 客户端支持跟 HTTP、HTTPS 绑定的 Hprose 服务器通讯。

Socket 客户端支持跟 TCP、Unix Socket 绑定的 Hprose 服务器通讯，并且支持全双工和半双工两种模式。

WebSocket 客户端支持跟 ws、wss 绑定的 Hprose 服务器通讯。



swoole 版本这里就不演示了，因为该版本使用的swoole版本比较低，应该是1.8左右吧，期待hprose3.0后再使用吧

---



publish.php



```javascript
<?php
require_once "vendor/autoload.php";

use Hprose\Socket\Server;

$server = new Server('tcp://0.0.0.0:9501');
$server->publish('time');
$server->tick(1000, function () use ($server) {
    $time = microtime(true);
    /** @var Hprose\Socket\Server $server */
    $server->push('time', $time);
    var_dump($time);
});
$server->start();
```



subscribe.php



```javascript
<?php
require_once "vendor/autoload.php";
$client = new \Hprose\Socket\Client('tcp://127.0.0.1:9501');
$client->subscribe('time', function ($date) {
    var_dump($date);
});
```





终端先执行 publish.php 再执行subscribe.php 就能看到结果了