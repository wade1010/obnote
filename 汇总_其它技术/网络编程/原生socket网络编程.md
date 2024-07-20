server.php

```javascript
<?php
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_NOTICE);
$ip = '127.0.0.1';
$port = '8090';
if (!($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
    echo 'socket_create fail,reason:', socket_last_error($sock);
    return;
}
if (!($bind = socket_bind($sock, $ip, $port))) {
    echo 'socket_bind fail,reason:', socket_last_error($sock);
    return;
}
if (!($listen = socket_listen($sock, 4))) {
    echo 'socket_listen fail,reason:', socket_last_error($sock);
    return;
}
echo 'start time ', date('Y-m-d H:i:s'), PHP_EOL;
echo 'listening at ', $ip, ':', $port, PHP_EOL;

while (true) {
    if ($msgSock = socket_accept($sock)) {
        $readClient = socket_read($msgSock, 1024);
        echo 'welcome client', (int)$msgSock, '  ', $readClient, PHP_FLOAT_EPSILON;
        $msg = '连接成功';
        socket_write($msgSock, $msg, strlen($msg));
    } else {
        echo 'socket_accept fail,reason:', socket_last_error($sock);
        break;
    }
    socket_close($msgSock);
}
socket_close($sock);
```



client.php

```javascript
<?php
$ip = '127.0.0.1';
$port = '8090';
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$sock) {
    echo 'socket_create fail,reason:', socket_last_error($sock);
    return;
}
$connect = socket_connect($sock, $ip, $port);
if (!$connect) {
    echo 'socket_connect fail,reason:', socket_last_error($sock);
    return;
}
socket_write($sock, 'hello', 1024);
$result = socket_read($sock, 1024);
echo '服务器返回：', $result, PHP_EOL;
socket_close($sock);
```

