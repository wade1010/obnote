前言：

核心版本除了提供了客户端的基本实现的基类以外，还提供了 HTTP 客户端和 Socket 客户端。这两个客户端都可以创建为同步或异步客户端。这两个客户端既可以在命令行环境下使用，也可以在 php-fpm 或其他 PHP 环境下使用。

另外 swoole 版本 提供了纯异步的 HTTP 客户端，Socket 客户端和 WebSocket 客户端。Swoole 客户端只能在命令行环境下使用。

其中 HTTP 客户端支持跟 HTTP、HTTPS 绑定的 Hprose 服务器通讯。

Socket 客户端支持跟 TCP、Unix Socket 绑定的 Hprose 服务器通讯，并且支持全双工和半双工两种模式。

WebSocket 客户端支持跟 ws、wss 绑定的 Hprose 服务器通讯。



swoole 版本这里就不演示了，因为该版本使用的swoole版本比较低，应该是1.8左右吧，期待hprose3.0后再使用吧

---





新建composer.json



```javascript
{
  "require": {
    "hprose/hprose": ">=2.0.0"
  }
}
```



就2020-11-27日来看。3.0是依赖PHP扩展来的，应该还在开发。文档没有。还是用2.X来



进入同级目录 



composer install 





![](D:/download/youdaonote-pull-master/data/Technology/RPC/hprose/images/FE891502ED6845428D449A2F3CD3F489image.png)





新建 HttpServer.php



```javascript
<?php
require_once "vendor/autoload.php";

use Hprose\Http\Server;

function hello($name)
{
    return "Hello $name!";
}

$server = new Server();
$server->add("hello");
$server->debug = false;
$server->crossDomain = true;
$server->start();
```





这个要配合web服务器使用



这里就简单使用PHP命令了



同级目录 执行下面命令



```javascript
php -S 127.0.0.1:9501
```





然后浏览器打开 



http://127.0.0.1:9501/HttpServer.php



就能看到 



Fa2{u#s5"hello"}z







