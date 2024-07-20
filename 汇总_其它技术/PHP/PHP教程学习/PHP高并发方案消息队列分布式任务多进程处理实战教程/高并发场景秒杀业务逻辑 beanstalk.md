

![](https://gitee.com/hxc8/images8/raw/master/img/202407191100228.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191100563.jpg)



<?php

require 'vendor/autoload.php';



$p=new \Pheanstalk\Pheanstalk('127.0.0.1',11300);



//生产者

$data=[

'tid'=>session_create_id();

];



//模拟10ms就生成一条数据



swoole_timer_tick(10,function()use($p,$data){

var_dump($p->useTube('trade')->put(json_encode($data)));

});





consumer

<?php

require 'vendor/autoload.php';

$p=new \Pheanstalk\Pheanstalk('127.0.0.1',11300);

while(true){

$job=$p->watch('trade')->ignore('default')->reserve();

$job->getData();

$p->delete($job);

usleep(10000);

}







//封装多进程模块进行消费

//pcntl_fork() //创建一个进程  但是可能会出现僵尸进程，所以这里用swoole

<?php



require 'vendor/autoload.php';



$pools=new Swool\Process\Pool(2);



$pool->on('workStart',function($pool,$workerId){

$p=new \Pheanstalk\Pheanstalk('127.0.0.1',11300);

while(true){

$job=$p->watch('trade')->ignore('default')->reserve();

$job->getData();

$p->delete($job);

usleep(10000);

}

});



$pool->on('workStop',function($pool,$workerId){



});



$pool->start();







------------------------------------------------------------------------------------------











