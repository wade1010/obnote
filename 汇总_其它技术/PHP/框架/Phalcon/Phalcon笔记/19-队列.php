<?php

Beanstalkd，一个高性能、轻量级的分布式内存队列系统.
Beanstalkd设计里面的核心概念：
　　◆ job
　　一个需要异步处理的任务，是Beanstalkd中的基本单元，需要放在一个tube中。
　　◆ tube
　　一个有名的任务队列，用来存储统一类型的job，是producer和consumer操作的对象。
　　◆ producer
　　Job的生产者，通过put命令来将一个job放到一个tube中。
　　◆ consumer
　　Job的消费者，通过reserve/release/bury/delete命令来获取job或改变job的状态。

==========
= 写入队列
==========
//Connect to the queue
$queue = new Phalcon\Queue\Beanstalk(array(
    'host' => '192.168.0.21'
));
//Insert the job in the queue
$queue->put(array('processVideo' => 4871));
-----------

连接参数:
host	IP where the beanstalk server is located	127.0.0.1
port	Connection port	11300
----------

附加参数的写入队列:
//Insert the job in the queue with options
$queue->put(
    array('processVideo' => 4871),
    array('priority' => 250, 'delay' => 10, 'ttr' => 3600)
);
priority是优先级, delay是延迟时间(秒),  ttr是运行时间(秒)
--------

写入队列的JOB都会返回一个jobId:
$jobId = $queue->put(array('processVideo' => 4871));
------------

==========
= 获取数据
==========
while (($job = $queue->peekReady()) !== false) {
    $message = $job->getBody();
    var_dump($message);
    $job->delete();
}

 