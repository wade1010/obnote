http_server.php

---

<?php

$http = new swoole_http_server("0.0.0.0", 9501);

$http->on('request', function ($request, $response) {
    var_dump($request->get, $request->post);
    $response->header("Content-Type", "text/html; charset=utf-8");
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});

$http->start();

---















官网说明





Http服务器只需要关注请求响应即可，所以只需要监听一个onRequest事件。当有新的Http请求进入就会触发此事件。事件回调函数有2个参数，一个是$request对象，包含了请求的相关信息，如GET/POST请求的数据。

另外一个是response对象，对request的响应可以通过操作response对象来完成。$response->end()方法表示输出一段HTML内容，并结束此请求。

- 0.0.0.0 表示监听所有IP地址，一台服务器可能同时有多个IP，如127.0.0.1本地回环IP、192.168.1.100局域网IP、210.127.20.2 外网IP，这里也可以单独指定监听一个IP

- 9501 监听的端口，如果被占用程序会抛出致命错误，中断执行。

启动服务

php http_server.php


- 可以打开浏览器，访问http://127.0.0.1:9501查看程序的结果。

- 也可以使用apache ab工具对服务器进行压力测试

Chrome 请求两次问题

使用Chrome浏览器访问服务器，会产生额外的一次请求，/favicon.ico，可以在代码中响应404错误。

$http->on('request', function ($request, $response) {
    if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
        return $response->end();
    }
    var_dump($request->get, $request->post);
    $response->header("Content-Type", "text/html; charset=utf-8");
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});


URL 路由

应用程序可以根据$request->server['request_uri']实现路由。如：http://127.0.0.1:9501/test/index/?a=1，代码中可以这样实现URL路由。

$http->on('request', function ($request, $response) {
    list($controller, $action) = explode('/', trim($request->server['request_uri'], '/'));
    //根据 $controller, $action 映射到不同的控制器类和方法
    (new $contoller)->$action($request, $response);
});


