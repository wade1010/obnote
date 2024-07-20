Consumer.php


```
<?php
function consumeCallBack(AMQPEnvelope $envelope, AMQPQueue $queue)
{
    try {
        usleep(10000);
        var_dump($envelope->getBody());
        //显式确认，队列收到消费者显式确认后，会删除该消息
//        $queue->ack($envelope->getDeliveryTag());
        $queue->nack($envelope->getDeliveryTag());
    } catch (AMQPChannelException $e) {
    } catch (AMQPConnectionException $e) {
    }
}

$config = [
    'host' => '127.0.0.1',
    'port' => '5672',
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest'
];
$conn = new AMQPConnection($config);
try {
    if (!$conn->connect()) {
        var_dump('连接失败');
        exit();
    }
} catch (AMQPConnectionException $e) {
    var_dump($e);
    exit();
}

try {
    //创建通道
    $chanel = new AMQPChannel($conn);
    //创建交换机
    $exchange = new AMQPExchange($chanel);
    //设置交换机名称
    $exchange->setName('test_exchange');
    //设置direct类型
    $exchange->setType(AMQP_EX_TYPE_DIRECT);
    //设置持久化 2是宕机后重启 数据还在
    $exchange->setFlags(AMQP_DURABLE);
    //声明创建一个交换机
    $exchange->declareExchange();


    //创建消息队列
    $queue = new AMQPQueue($chanel);

    //设置队列名称
    $queue->setName('test_queue');
    //持久化
    $queue->setFlags(AMQP_DURABLE);
    //声明创建一个队列
    $queue->declareQueue();

    $routing_key = 'routing_key';
    $queue->bind($exchange->getName(), $routing_key);
    //接收消息并回调consumeCallBack处理
    $queue->consume('consumeCallBack');

} catch (AMQPConnectionException $e) {
} catch (AMQPExchangeException $e) {
} catch (AMQPChannelException $e) {
} catch (AMQPQueueException $e) {
} catch (AMQPEnvelopeException $e) {
}
```




Publisher.php

```
<?php
$config = [
    'host' => '127.0.0.1',
    'port' => '5672',
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest'
];

$conn = new AMQPConnection($config);
try {
    if (!$conn->connect()) {
        var_dump('连接失败');
        exit();
    }
} catch (AMQPConnectionException $e) {
    var_dump($e);
    exit();
}
try {
    //创建通道
    $chanel = new AMQPChannel($conn);
    //创建交换机
    $exchange = new AMQPExchange($chanel);
    //设置交换机名称
    $exchange->setName('test_exchange');
    //设置direct类型
    $exchange->setType(AMQP_EX_TYPE_DIRECT);
    //设置持久化 2是宕机后重启 数据还在
    $exchange->setFlags(AMQP_DURABLE);
    //声明创建一个交换机
    $exchange->declareExchange();

    $routing_key = 'routing_key';

    for ($i = 0; $i < 10; $i++) {
        $message = [
            'to' => 'beauty' . $i,
            'content' => 'hello baby'
        ];
        //发送消息到交换机，并返回发送结果
        //delivery_mode:2声明消息持久，持久的队列+持久的消息在RabbitMQ重启后才不会丢失
        $exchange->publish(json_encode($message), $routing_key, AMQP_NOPARAM, ['delivery_mode' => 2]);
    }


} catch (AMQPConnectionException $e) {
} catch (AMQPExchangeException $e) {
} catch (AMQPChannelException $e) {
}



```


![image](https://gitee.com/hxc8/images7/raw/master/img/202407190740670.jpg)