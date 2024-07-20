class Lock{

protected $redis;

protected $lockId;

public function __construct($redis){

$this->redis=$redis;

}

public function lock($k,$ttl=5,$retry=4,$usleep=10000){

$res=false;

while($retry-->0){

$v=session_create_id();

$res=$this->redis->set($k,$v,['NX','EX'=>$ttl]);

if($res){

$this->lockId[]=$v;

break;

}

usleep($usleep);

}



return $res;

}



public function unlock($k){

//能够删除自己的锁，不能删除别人的锁

if(isset($this->lockId[$k])){

$id=$this->lockId[$k];

$value=$this->redis->get($k]);

if($value==$id){

//sleep(5)//模拟网络延迟 ，这时候锁过期,另外一个请求加锁成功，下面代码就会删除掉另外一个请求的锁

return $this->redis->del($k);

}

上面极端情况下 可以出现误删别人锁的情况，因为Redis请求是有网络请求的

//改成

$script=<<<lua

if (redis.call('get',KEYS[1])==ARGV[1] ) then

return redis.call('del',KEYS[1])

end

return 0;

lua;

return $this->redis->eval($script,[$k,$id],1);

}

return false;

}

}





------------------------------------------------------------------------------------------------------------------------------









![](https://gitee.com/hxc8/images7/raw/master/img/202407190029865.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190029022.jpg)





key 没有判断是不是自己的 会出现删除别人锁的情况



![](https://gitee.com/hxc8/images7/raw/master/img/202407190029005.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190029419.jpg)

