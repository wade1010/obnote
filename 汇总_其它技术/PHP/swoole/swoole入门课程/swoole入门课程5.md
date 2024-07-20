swoole提供的几种协议



EOF协议

在每个发送数据的结尾加一个标记  标记一定不能在正常数据内出现

![](https://gitee.com/hxc8/images8/raw/master/img/202407191107520.jpg)



固定包头协议

![](https://gitee.com/hxc8/images8/raw/master/img/202407191107526.jpg)

前面头的长度一定是固定长度的。假如是20个字节





服务器先只读20个字节，然后从里面找长度字段，然后根据长度再receive这个长度指明的数据，这样就能保证收到的是一个完整的数据包





addListener()



Swoole\Server->listen(string $host, int $port, int $type = SWOOLE_SOCK_TCP): bool|Swoole\Server\Port



```javascript
$this->server = new Swoole\Server('0.0.0.0', 9501);
$this->server->set([
    'open_length_check' => true,
    'package_length_type' => 'N',
    'package_length_offset' => 0,
    'package_body_offset' => 4,
]);
$this->server->on('start', [$this, 'onStart']);
$this->server->on('connect', [$this, 'onConnect']);
$this->server->on('receive', [$this, 'onReceive']);
$this->server->on('close', [$this, 'onClose']);
$this->server->on('workerStart', [$this, 'onWorkerStart']);
$tcpServer = $this->server->addListener('0.0.0.0', 9502, SWOOLE_SOCK_TCP);
$tcpServer->set([
//            'open_eof_check' => true,//开启这个有粘包问题
    'open_eof_split' => true,   //打开EOF_SPLIT检测 性能差 无粘包问题
    'package_eof' => "\r\n",
]);
$tcpServer->on('receive', [$this, 'onTcpReceive']);
$this->server->start();
```



注意 addListener之后是返回个对象要对其对应设置方法