常用限流

Nginx

Nginx+lua

kong

dinggo

redis令牌桶





![](https://gitee.com/hxc8/images8/raw/master/img/202407191112743.jpg)





```javascript
<?php
class TrafficShaper{
    private $_config;
    private $_queque;
    private $_max;
    private $redis;    
    
    public function __construct($config,$queque,$max){
        $this->_config=$config;
        $this->_queque=$queque;
        $this->_max=$max;
        $this->_redis=$this->connect();
    }
    
    public function add($num=0){
        $curnum=intval($this->_redis->lSize($this->_queque));
        $max=intval($this->_max);
        $num=$max>=$curnum+$num?$num:$max-$curnum;
        if($num>0){
            $tokens=array_fill(0,$num,1);
            foreach($tokens as $token){
                $this->_redis->lpush($this->_queque,$token);
            }
            return $num;
        }
        return 0;
    }
    
    public function get(){
        return $this->_redis->rpop($this->_queque);
    }
    
    public function reset(){
       return $this->_redis->del($this->_queque);
    }
    
}


```

