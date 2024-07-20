前言：

核心版本除了提供了客户端的基本实现的基类以外，还提供了 HTTP 客户端和 Socket 客户端。这两个客户端都可以创建为同步或异步客户端。这两个客户端既可以在命令行环境下使用，也可以在 php-fpm 或其他 PHP 环境下使用。

另外 swoole 版本 提供了纯异步的 HTTP 客户端，Socket 客户端和 WebSocket 客户端。Swoole 客户端只能在命令行环境下使用。

其中 HTTP 客户端支持跟 HTTP、HTTPS 绑定的 Hprose 服务器通讯。

Socket 客户端支持跟 TCP、Unix Socket 绑定的 Hprose 服务器通讯，并且支持全双工和半双工两种模式。

WebSocket 客户端支持跟 ws、wss 绑定的 Hprose 服务器通讯。



swoole 版本这里就不演示了，因为该版本使用的swoole版本比较低，应该是1.8左右吧，期待hprose3.0后再使用吧

---



1、同步CS 

server.php



```javascript
<?php
require_once "vendor/autoload.php";

use Hprose\Socket\Server;

function hello($name)
{
    return "Hello $name!";
}

$server = new Server("unix:/tmp/my.sock");
$server->addFunction('hello');
$server->start();
```



client.php



```javascript
<?php

require_once "vendor/autoload.php";

use Hprose\Client;

$client = Client::create("unix:/tmp/my2.sock");
var_dump($client->hello("world"));//同步的时候
```





2、异步CS 

server.php   #不变



```javascript
<?php
require_once "vendor/autoload.php";

use Hprose\Socket\Server;

function hello($name)
{
    return "Hello $name!";
}

$server = new Server("unix:/tmp/my.sock");
$server->addFunction('hello');
$server->start();
```



client.php



```javascript
<?php

require_once "vendor/autoload.php";

use Hprose\Client;

$client = Client::create("unix:/tmp/my.sock");
$client->hello('world')->then(function ($result) {//异步的时候
    var_dump($result);
});
```





开启服务

在终端输入下面命令

php server.php  #开启服务端



调用：

1、终端命令 php client

2、web访问  http://127.0.0.1:9502/client.php   （自己搭好服务）







3、客户端或者服务端addInvokeHandler

以客户端为例

```javascript
require_once "vendor/autoload.php";

use Hprose\Client;
use Hprose\Future;

$logHandler = function ($name, array &$args, stdClass $context, Closure $next) {
    error_log("before invoke:");
    error_log($name);
    error_log(var_export($args, true));
    $result = $next($name, $args, $context);
    error_log("after invoke:");
    if (Future\isFuture($result)) {
        $result->then(function ($result) {
            error_log(var_export($result, true));
        });
    } else {
        error_log(var_export($result, true));
    }
    return $result;
};

$client = Client::create("tcp://127.0.0.1:9501",false);
$client->addInvokeHandler($logHandler);
var_dump($client->hello("world"));
```



服务端一样加入相关代码

