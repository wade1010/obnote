



![](https://gitee.com/hxc8/images8/raw/master/img/202407191102767.jpg)

多进程  常驻进程里面比较合适

class Token{

private $max;

private $redis;

private $queque;//令牌桶名称

public function __construct(){

$this->redis=new \Redis();

$this->redis->connect('127.0.0.1',6379);

$this->queque='keyname';

$this->max=100;//

}



//每秒 每分钟投递 根据业务量

public function add($num){

// 不要从0开开始

for($i=1;$i<=$num;$i++)

$this->redis->lpush($this->queque,$i);

}



public function get(){

return $this->redis->rpop($this->queque)?true;false;

}



public function init(){

$this->redis->del($this->queque);

return $this->add($this->max);

}



}