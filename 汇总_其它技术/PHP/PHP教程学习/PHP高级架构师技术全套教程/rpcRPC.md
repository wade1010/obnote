

![](https://gitee.com/hxc8/images8/raw/master/img/202407191104702.jpg)





![](https://gitee.com/hxc8/images8/raw/master/img/202407191104987.jpg)



server.php



<?php



$server = new Swoole\server('0.0.0.0',9502);

$server->set([

'worker_num'=>5

]);

$server->on('receive',function($server,$fd,$id,$data){

echo '客户端发送数据来了！';

$jsonData=json_decode($data,true);

$className=$jsonData['service'];

$actionName=$jsonData['action'];

$id=$jsonData['params']['id']

include_once __DIR__.'/server/'.$className.'.php';

$obj=new $className;

$data=$obj->$actionName($id);

$server->send($fd,json_encode($data));

});





$server->start();





client.php



class Client{

protected $ip;

protected $port;

protected $serviceName;

public funciton __construct($ip,$port){

$this->ip=$ip;

$this->port=$port;

}



public funciton __call($name,$params){

if($name=='serevice'){

$this->serviceName=$name;

return $this;

}

$client = new Swoole\client(SWOOLE_SOCK_TCP);



$client->connect('127.0.0.1',9502);



//rpc通讯  自定义的协议

$data=[

'service'=>'UserService',//服务名称

'action'=>'info',//方法

'token'=>'',

'params'=>['id'=>11111]

];



$client->send($data);

$recev = $client->recv();



var_dump($recv);

}

}



$client= new Client('127.0.0.2',9502);

$client->service('UserService')->info(5);





![](https://gitee.com/hxc8/images8/raw/master/img/202407191104394.jpg)























































