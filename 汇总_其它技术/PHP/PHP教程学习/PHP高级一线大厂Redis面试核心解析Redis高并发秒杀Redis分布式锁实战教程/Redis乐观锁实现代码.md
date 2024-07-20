$redis=new Redis();

$redis->connect('xxx');

$k='sale';

$redis->watch($k);

$sales=$redis->get($k);

$store=3;//库存 

if($sales>=$store){

exit('秒杀结束');

}

//记录用户信息，更新库存



//保证这一组命令，要么成功，要么都不成功



$redis->mult();

$redis->incr($k);

sleep(1);//模拟业务

$result=$redis->exec();



//将下单信息用户信息等存到消息队列

。。。。。待补全

逻辑大概如下

![](https://gitee.com/hxc8/images8/raw/master/img/202407191101315.jpg)





