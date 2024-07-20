client.php

---

<?php

$client = new swoole_client(SWOOLE_SOCK_TCP);



//连接到服务器

if (!$client->connect('192.168.1.40', 9501, 0.5))

{

    die("connect failed.");

}

  fwrite(STDOUT,"请输入消息:");

  $msg = trim(fgets(STDIN));

  //发送teco server 服务器

  $client->send($msg);

  //接收服务端发送数据

  $result= $client->recv();

  echo $result;

//关闭连接

$client->close();

---

1、启动1中的tcp服务端

2、启动上面的client





官网说明



创建一个TCP的同步客户端，此客户端可以用于连接到我们第一个示例的TCP服务器。向服务器端发送一个hello world字符串，服务器会返回一个 Server: hello world字符串。

这个客户端是同步阻塞的，connect/send/recv 会等待IO完成后再返回。同步阻塞操作并不消耗CPU资源，IO操作未完成当前进程会自动转入sleep模式，当IO完成后操作系统会唤醒当前进程，继续向下执行代码。

- TCP需要进行3次握手，所以connect至少需要3次网络传输过程

- 在发送少量数据时$client->send都是可以立即返回的。发送大量数据时，socket缓存区可能会塞满，send操作会阻塞。

- recv操作会阻塞等待服务器返回数据，recv耗时等于服务器处理时间+网络传输耗时之和。

TCP通信过程

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108684.jpg)

