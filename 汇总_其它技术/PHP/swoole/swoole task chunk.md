<?php

//tcp协议

$server=new Swoole\Server("0.0.0.0",9801);   //创建server对象



//include '222xx'; 不能

$key=ftok(__DIR__,1);

$server->set([

    'worker_num'=>2, //设置进程

    //'heartbeat_idle_time'=>10,//连接最大的空闲时间

    //'heartbeat_check_interval'=>3 //服务器定时检查

    'task_worker_num'=>3,  //task进程数

    'task_ipc_mode'=>2,

    'message_queue_key'=>$key,

    'open_length_check'=>1,

    'package_length_type'=>'N',//设置包头的长度

    'package_length_offset'=>0, //包长度从哪里开始计算

    'package_body_offset'=>4,  //包体从第几个字节开始计算

]);



$server->on('start',function (){

});





//kill -15 主进程

$server->on('Shutdown',function (){

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



$server->on('PipeMessage',function (swoole_server $server, int $src_worker_id,$message){

    echo "来自于{$src_worker_id}的错误信息".PHP_EOL;

    var_dump($message);

    //接收到投递的错误信息，记录错误次数，错误次数到达一定次数之后，就保留日志

});







//消息发送过来

$server->on('receive',function (swoole_server $server, int $fd, int $reactor_id, string $data){

    for ($i=0;$i<100;$i++){

        $tasks[] =['id'=>$i,'msg'=>time()];

    }

    $count=count($tasks);

    $data=array_chunk($tasks,ceil($count/3));

    foreach ($data as $k=>$v){

        $server->task($v,$k);  //(0-task_woker_num-1)

    }



});



//ontask事件回调

$server->on('task',function ($server,$task_id,$form_id,$data){

    //echo "任务来自于:$form_id".",任务id为{$task_id}".PHP_EOL;

    try{

        foreach ($data as $k=>$v){

            if(mt_rand(1,5)==3){ //故意的去出现错误

                $server->sendMessage($v,1); //主动去通知worker进程，0 ~ (worker_num + task_worker_num - 1）

            }

        }

    }catch (\Exception $e){

           //$server->sendMessage();

    }

    $server->finish("执行完毕");



});



$server->on('finish',function ($server,$task_id,$data){

    //echo "任务{$task_id}执行完毕:{$data}".PHP_EOL;

    // var_dump(posix_getpid());



    //$server->send($data['fd'], "任务执行完毕");

});







//消息关闭

$server->on('close',function (){

    //echo "消息关闭".PHP_EOL;

});







//服务器开启

$server->start();



