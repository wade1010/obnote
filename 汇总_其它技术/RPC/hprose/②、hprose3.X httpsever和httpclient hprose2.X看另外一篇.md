其他的协议可能作者还没写完，以后再看吧。



新建 HttpServer.php



```javascript
<?php
require 'vendor/autoload.php';

use Hprose\RPC\Http\HttpServer;
use Hprose\RPC\Service;

function hello(string $name): string
{
    return "Hello " . $name . "!";
}

$service = new Service();
$service->addCallable("hello", "hello");
$server = new HttpServer();
$service->bind($server);
$server->listen();
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



Ra2{u~s5"hello"}z







新建 HttpClient.php



```javascript
<?php
require 'vendor/autoload.php';

use Hprose\RPC\Client;
use Hprose\RPC\Plugins\Log;

$client = new Client(['http://127.0.0.1:9502/HttpServer.php']);
$log = new Log();
//$client->use([$log, 'invokeHandler'], [$log, 'ioHandler']);
$proxy = $client->useService();
print($proxy->hello('world'));
```





打开终端输入



php HttpClient.php

```javascript
php HttpClient.php 
Hello world!
```





就实现简单的跨服务调用啦