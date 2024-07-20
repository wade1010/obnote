

```javascript
<?php

class WorkerByPcntl
{
    protected $socket;
    protected $workerNum = 1;
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
        for ($i = 0; $i < $this->workerNum; $i++) {
            $pid = pcntl_fork();
            if ($pid < 0) {
                exit('创建失败');
            } elseif ($pid > 0) {
                //父进程空间
            } else {
                //子进程空间
                $this->accept();//子进程负责接收客户端请求
            }
        }
        $status = 0;
        $pid = pcntl_wait($status);
        echo "子进程回收了:$pid" . PHP_EOL;

    }

    private function accept()
    {
        while (true) {
            $client = stream_socket_accept($this->socket);//阻塞监听
            //触发事件的连接的回调
            if (!empty($client) && is_callable($this->onConnect)) {
                call_user_func($this->onConnect, $client);
            }
            //从连接当中读取客户端的内容
            $buffer = fread($client, 65535);
            //正常读取到数据,触发消息接收事件,响应内容
            if (!empty($buffer) && is_callable($this->onMessage)) {
                call_user_func($this->onMessage, $client, $buffer);
            }
            fclose($client);//必须关闭
        }
    }

}

$worker = new WorkerByPcntl('tcp://0.0.0.0:9800');
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

