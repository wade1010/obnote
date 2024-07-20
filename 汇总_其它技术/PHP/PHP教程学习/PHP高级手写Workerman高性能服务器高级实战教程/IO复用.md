select  



poll



epoll



![](https://gitee.com/hxc8/images8/raw/master/img/202407191059749.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191059225.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191059772.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191059150.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191059175.jpg)





![](https://gitee.com/hxc8/images8/raw/master/img/202407191059985.jpg)





epoll模式

1、不需要用户空间拷贝到内核空间

2、事件通知的形式



select 多路复用实现服务端

```javascript
<?php

class Worker
{
    protected $socket;
    protected $socketList;
    public $onConnect;
    public $onMessage;


    public function __construct($address)
    {
        //创建一个socket服务
        $this->socket = stream_socket_server($address);
        $this->socketList[(int)$this->socket] = $this->socket;
    }

    public function runAll()
    {
        while (1) {
            $write = $except = [];
            $read = $this->socketList;
            //反馈状态，是可以读取的客户端,在系统的底层会动态修改(传址)
            stream_select($read, $write, $except, 60);
            //遍历
            foreach ($read as $index => $s) {
                //读取到客户端的socket（描述符）,阻塞，等待结果
                //IO阻塞(休眠状态)
                if ($s == $this->socket) {
                    $client = stream_socket_accept($s);
                    //触发连接建立成功
                    if ($this->onConnect) {
                        call_user_func($this->onConnect, $client);
                    }
                    //把客户端放入到监听列表
                    $this->socketList[(int)$client] = $client;
                } else {
                    $msg = fread($s, 65535);
                    if ($msg === '') {
                        if (feof($s) || !is_resource($s)) {
                            fclose($s);
                            return null;
                        }
                    } else {
                        //读取客户端消息
                        if ($this->onMessage) {
                            call_user_func($this->onMessage, $s, 'hello');
                        }
                    }
                }
            }
        }
    }
}

$worker = new Worker('tcp://0.0.0.0:9800');
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
$worker->runAll();
```

