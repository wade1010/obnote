分流 和 降级



IP访问+用户鞋带标识（限制次数的标识，记录到Redis当中）







1、判断下（IP）这个标识存在数据库



2、设置标识，让标识+1







$redis = new Redis();



$redis->connect('127.0.0.1',6379);



$key = $_SERVER['REMOTE_ADDR'];//案例以IP为例



$limit = 5;



if($redis->incr($key)>$limit){



}





上面会出现下面问题



一分钟之内只允许访问10次，如果说是0秒访问一次59秒的时候访问9次，1分00秒访问10次

，2秒之内访问近20次







$redis->lpush($key,time());



if($redis->exist($key)){

if($redis->llen($key)>30){//一分钟最多访问30次

exit();

}else{

$redis->lpush($key,time());

}

}else{

$redis->lpush($key,time());

$redis->expire($key,60);

}







![](https://gitee.com/hxc8/images8/raw/master/img/202407191102935.jpg)

