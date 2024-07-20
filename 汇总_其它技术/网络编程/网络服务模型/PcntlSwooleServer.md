

```javascript
<?php

class WorkerByPcntlSwoole
{
    protected $socket;
    protected $socketList;
    protected $workerNum = 4;
    public $onConnect;
    public $onMessage;
    public $addr;
    public $reusePort = true;


    public function __construct($address)
    {
        //监听地址+端口
        $this->addr = $address;
    }

    public function start()
    {
        $this->fork();//子进程负责接收客户端请求
    }

    private function fork()
    {
        $status = 0;
        for ($i = 0; $i < $this->workerNum; $i++) {
            $pid = pcntl_fork();
            if ($pid < 0) {
                exit('创建失败');
            } elseif ($pid > 0) {

            } else {
                $this->accept();
                exit();
            }
            pcntl_wait($status);
            echo $i;
        }


    }

    private function accept()
    {
        $opts = [
            'socket' => ['backlog' => 10240]//成功建立socket连接的等待个数
        ];
        $context = stream_context_create($opts);
        //开启多端口监听,并且实现负载均衡
        stream_context_set_option($context, 'socket', 'so_reuseport', 1);
        stream_context_set_option($context, 'socket', 'so_reuseaddr', 1);
        $this->socket = stream_socket_server($this->addr, $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $context);
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
                    if (!is_resource($fd) || feof($fd)) {
                        //触发关闭事件
                        fclose($fd);
                    }
                }
                //正常读取到数据,触发消息接收事件,响应内容
                if (!empty($buffer) && is_callable($this->onMessage)) {
                    call_user_func($this->onMessage, $fd, $buffer);
                }
            });
        });
    }
}

$worker = new WorkerByPcntlSwoole('tcp://0.0.0.0:9800');
//开启多进程的端口监听
$worker->reusePort = true;

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

