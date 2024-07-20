udp_server.php

---

<?php

//创建Server对象，监听 127.0.0.1:9502端口，类型为SWOOLE_SOCK_UDP
$serv = new swoole_server("0.0.0.0", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP); 

//监听数据接收事件
$serv->on('Packet', function ($serv, $data, $clientInfo) {
    $serv->sendto($clientInfo['address'], $clientInfo['port'], "Server ".$data);
    var_dump($clientInfo);
});

//启动服务器
$serv->start(); 

---



UDP服务器可以使用netcat -u 来连接测试



netcat -u 127.0.0.1 9502

hello

Server: hello





telnet是用tcp协议的，所以这里使用netcat也就是nc

centos下安装nc应该是 yum -y install nc

MAC OS 的话brew install netcat











官网说明

UDP服务器与TCP服务器不同，UDP没有连接的概念。启动Server后，客户端无需Connect，直接可以向Server监听的9502端口发送数据包。对应的事件为onPacket。

- $clientInfo是客户端的相关信息，是一个数组，有客户端的IP和端口等内容

- 调用 $server->sendto 方法向客户端发送数据