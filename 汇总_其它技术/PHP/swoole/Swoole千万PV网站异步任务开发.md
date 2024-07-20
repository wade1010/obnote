

1 异步任务的开发场景

秒杀 消息推送  类似crontab定时任务

PHP是同步阻塞的



群发消息：客户端发消息给服务端，之前是循环一个个发，swoole可以异步发送。大大提高效率



![](https://gitee.com/hxc8/images8/raw/master/img/202407191108754.jpg)





TCP服务端

```javascript
<?php
//TCP服务器  速度快  http等协议都是建立在TCP之上
$server=new Swoole\Server('0.0.0.0',8082);
//服务配置参数
$server->set([
    'worker_num'=>2,//工作线程 和CPU挂钩
    'task_worker_num'=>4//异步任务线程
]);

//接收消息

$server->on('receive',function($server,$fd,$id,$data){
    $task_id=$server->task($data);
    echo '开始处理task id：',$task_id,PHP_EOL;
    $server->send($fd,'投递成功');
});

$server->on('task',function($server,$task_id,$id,$data){
    $str="receive id [id=$task_id]";
    //处理数据
    $server->finish('task执行完毕');
});

$server->on('finish',function($server,$task_id,$data){
    echo 'success',$task_id,PHP_EOL;
});


$server->start();


```





TCP客户端

延展：如果用HTTP协议连接上面的服务端能不能访问上面的服务端？

 能访问，但是数据不能被解析。TCP是传输层是二进制传输的（流式）

发送数据过去，有个缓冲区，首先缓冲数据到缓冲区，然后缓冲区把数据合成起来做到传输使用。

所以TCP通信需要做粘包处理，类似水流，不知道什么时候关闭水流。

不像应用层，所有数据都是封装好的，能直接使用数据。

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108049.jpg)



可以使用固定包头识别自己发送的数据



```javascript
<?php
$client=new Swoole\Client(SWOOLE_SOCK_TCP);
$ret=$client->connect('127.0.0.1',8082);
if(empty($ret)){
    echo 'error';
}else{
    $client->send('hello');
    $data=$client->recv();
    var_dump($data);
    $client->close();
}
```



2 swoole工作原理

![](https://gitee.com/hxc8/images8/raw/master/img/202407191108138.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191108169.jpg)

3 数据传输之粘包

4 微服务





