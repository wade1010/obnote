我是在虚拟机192.168.1.40上部署server服务

ws_server.php

---

<?php

//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server("0.0.0.0", 9502);

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    var_dump($request->fd, $request->get, $request->server);
    $ws->push($request->fd, "hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    echo "Message: {$frame->data}\n";
    $ws->push($frame->fd, "server: {$frame->data}");
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();

---



我是在虚拟机192.168.1.39上部署了web服务访问这个html页面



html 页面内容如下



```javascript
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>webSocket</title>
</head>
<body>
<p id="status"></p>
<input type="text" id="content" />
<button onclick="clientSend()">发送</button>
<button onclick="clientClose()">关闭连接</button>
<script type="text/javascript">
    var wsServer = "ws://192.168.1.40:9502";
    var webSocket = new WebSocket(wsServer);
    //on方法表示监听服务端动作
    webSocket.onopen = function (event) {
        document.getElementById('status').innerText += "onopen：连接成功\n";
    }
    webSocket.onmessage = function (event) {
        document.getElementById('status').innerText += "onmessage：" + event.data + "\n";
    }
    webSocket.onclose = function (event) {
        document.getElementById('status').innerText += "onclose：连接关闭\n";
    }
    webSocket.onerror = function (event, error) {
        document.getElementById('status').innerText += "onerror：" + error + "\n";
    }
    //客户端发送的操作
    function clientSend() {
        var content = document.getElementById('content').value;
        webSocket.send(content);
        document.getElementById('status').innerText += "send：" + content + "\n";
    }
    function clientClose() {
        webSocket.close();
        document.getElementById('status').innerText += "close：客户端关闭连接\n";
    }
</script>
 
</body>
</html>
```





这里需要你自己配置下Apache

然后浏览器访问http://192.168.1.39/index.html

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108335.jpg)



或者在电脑上创建一个xx.html文件，负责代码进去，然后用浏览器打开html文件





官方说明

WebSocket服务器是建立在Http服务器之上的长连接服务器，客户端首先会发送一个Http的请求与服务器进行握手。握手成功后会触发onOpen事件，表示连接已就绪，onOpen函数中可以得到$request对象，包含了Http握手的相关信息，如GET参数、Cookie、Http头信息等。

建立连接后客户端与服务器端就可以双向通信了。

- 客户端向服务器端发送信息时，服务器端触发onMessage事件回调

- 服务器端可以调用$server->push()向某个客户端（使用$fd标识符）发送消息

- 服务器端可以设置onHandShake事件回调来手工处理WebSocket握手

- swoole_http_server是swoole_server的子类，内置了Http的支持

- swoole_websocket_server是swoole_http_server的子类， 内置了WebSocket的支持

运行程序

php ws_server.php


可以使用Chrome浏览器进行测试，JS代码为：

var wsServer = 'ws://127.0.0.1:9502';
var websocket = new WebSocket(wsServer);
websocket.onopen = function (evt) {
    console.log("Connected to WebSocket server.");
};

websocket.onclose = function (evt) {
    console.log("Disconnected");
};

websocket.onmessage = function (evt) {
    console.log('Retrieved data from server: ' + evt.data);
};

websocket.onerror = function (evt, e) {
    console.log('Error occured: ' + evt.data);
};


- 不能直接使用swoole_client与websocket服务器通信，swoole_client是TCP客户端

- 必须实现WebSocket协议才能和WebSocket服务器通信，可以使用swoole/framework提供的PHP WebSocket客户端

Comet

WebSocket服务器除了提供WebSocket功能之外，实际上也可以处理Http长连接。只需要增加onRequest事件监听即可实现Comet方案Http长轮询。

---

