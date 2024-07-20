select模型

```javascript
<?php

class WorkerBySelect
{
    protected $socket;
    protected $socketList;
    public $onConnect;
    public $onMessage;


    public function __construct($address)
    {
        //创建一个socket服务
        $this->socket = stream_socket_server($address);
        stream_set_blocking($this->socket, 0); //设置非阻塞
        $this->socketList[(int)$this->socket] = $this->socket;
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
        while (true) {
            $write = $except = [];
            $read = $this->socketList;
            //反馈状态，是可以读取的客户端,在系统的底层会动态修改(传址)
            stream_select($read, $write, $except, 60);//select 模型
            //遍历
            foreach ($read as $index => $s) {
                //读取到客户端的socket（描述符）,阻塞，等待结果
                //IO阻塞(休眠状态)
                if ($s == $this->socket) {
                    $client = stream_socket_accept($s);//阻塞监听
                    //触发连接建立成功
                    if (!empty($client) && is_callable($this->onConnect)) {
                        call_user_func($this->onConnect, $client);
                    }
                    $this->socketList[(int)$client] = $client;
                } else {
                    //从连接当中读取客户端的内容
                    $buffer = fread($s, 1024);
                    //如果数据为空，或者为false,不是资源类型
                    if ($buffer === '') {
                        if (feof($s) || !is_resource($s)) {
                            fclose($s);
                            unset($this->socketList[(int)$s]);
                            continue;
                        }
                    } else {
                        //正常读取到数据,触发消息接收事件,响应内容
                        if (is_callable($this->onMessage)) {
                            call_user_func($this->onMessage, $s, $buffer);
                        }
                    }
                }
            }
        }
    }
}

$worker = new WorkerBySelect('tcp://0.0.0.0:9800');
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

