<?php

//tcp协议

$server=new Swoole\Server("0.0.0.0",9801);   //创建server对象



//include '222xx'; 不能

$key=ftok(__DIR__,1);



echo $key;



$server->set([

    'worker_num'=>1, //设置进程

    //'heartbeat_idle_time'=>10,//连接最大的空闲时间

    //'heartbeat_check_interval'=>3 //服务器定时检查

    'task_worker_num'=>1,  //task进程数

    'task_ipc_mode'=>2,

    'message_queue_key'=>$key,

    'open_length_check'=>1,

    'package_length_type'=>'N',//设置包头的长度

    'package_length_offset'=>0, //包长度从哪里开始计算

    'package_body_offset'=>4,  //包体从第几个字节开始计算

]);



$server->on('start',function (){

    // include 'index.php'; 不能

});





$server->on('Shutdown',function (){



    // include 'index.php'; 不能

    echo "正常关闭";

});



$server->on('workerStart',function ($server,$fd){

    //include 'index.php';

    if($server->taskworker){

        echo 'task_worker:'.$server->worker_id.PHP_EOL;

    }else{

        echo 'worker:'.$server->worker_id.PHP_EOL;

    }



});





//监听事件,连接事件

$server->on('connect',function ($server,$fd){



    //echo "新的连接进入xxx:{$fd}".PHP_EOL;

});





//消息发送过来

$server->on('receive',function (swoole_server $server, int $fd, int $reactor_id, string $data){

    //var_dump("消息发送过来:".$data);

    //不需要立刻马上得到结果的适合task

    $data=['tid'=>time()];

    //sleep(10);

    $data=str_repeat("a",10*1024*1024);

    $server->task($data); //投递到taskWorker进程组

    echo '异步非阻塞'.PHP_EOL;

    //服务端

});



//ontask事件回调

$server->on('task',function ($server,$task_id,$form_id,$data){

    //var_dump(posix_getpid());  //进程确实是发生了变化

    var_dump($server->worker_id);

    echo "任务来自于:$form_id".",任务id为{$task_id}".PHP_EOL;



    try{



    }catch (\Exception $e){

           //$server->sendMessage();

    }



    sleep(10);

    $server->finish("执行完毕");

});



$server->on('finish',function ($server,$task_id,$data){

    //echo "任务{$task_id}执行完毕:{$data}".PHP_EOL;

    // var_dump(posix_getpid());



    //$server->send($data['fd'], "任务执行完毕"); 是一个长连接



});







//消息关闭

$server->on('close',function (){

    //echo "消息关闭".PHP_EOL;

});







//服务器开启

$server->start();



