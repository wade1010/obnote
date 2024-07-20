epoll模型

```javascript
<?php

class WorkerByEpoll
{
    protected $socket;
    public $onConnect;
    public $onMessage;


    public function __construct($address)
    {
        //创建一个socket服务
        $this->socket = stream_socket_server($address);
    }

    public function start()
    {
        $this->fork();
    }

    private function fork()
    {
        $this->accept();//子进程负责接收客户端请求
    }

    private function accept()
    {
        //第一个需要监听的事件(服务端socket的事件),一旦监听到可读事件之后会触发
        swoole_event_add($this->socket, function ($fd) {
            $clientSocket = stream_socket_accept($fd);
            //触发事件的连接的回调
            if (!empty($clientSocket) && is_callable($this->onConnect)) {
                call_user_func($this->onConnect, $clientSocket);
            }
            //监听客户端可读
            swoole_event_add($clientSocket, function ($fd) {
                //从连接当中读取客户端的内容
                $buffer = fread($fd, 1024);
                //如果数据为空，或者为false,不是资源类型
                if (empty($buffer)) {
                    if (feof($fd) || !is_resource($fd)) {
                        //触发关闭事件
                        fclose($fd);
                    }
                } else {
                    //正常读取到数据,触发消息接收事件,响应内容
                    if (is_callable($this->onMessage)) {
                        call_user_func($this->onMessage, $fd, $buffer);
                    }
                }
            });
        });
    }
}

$worker = new WorkerByEpoll('tcp://0.0.0.0:9800');
$worker->onConnect = function ($conn) {
    echo '新的连接来了', PHP_EOL;
};
$worker->onMessage = function ($conn, $msg) {
    $data = '返回的内容：' . $msg;
    $http_response = "HTTP/1.1 200 OK\r\n";
    $http_response .= "Content-Type: text/html; charset=UTF-8\r\n";
    $http_response .= "Connection: keep-alive\r\n";
    $http_response .= "Server: crab-server\r\n";
    $http_response .= "Content-length: " . strlen($data) . "\r\n\r\n";
    $http_response .= $data;
    fwrite($conn, $http_response);
};
$worker->start();
```

