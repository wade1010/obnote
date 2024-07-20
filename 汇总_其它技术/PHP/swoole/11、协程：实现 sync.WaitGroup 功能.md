

```javascript
<?php
Swoole\Runtime::enableCoroutine();
class WaitGroup
{
    private $count = 0;
    private $chan;
    /**
     * waitgroup constructor.
     * @desc 初始化一个channel
     */
    public function __construct()
    {
        $this->chan = new chan;
    }
    public function add()
    {
        $this->count++;
    }
    public function done()
    {
        $this->chan->push(true);
    }
    public function wait()
    {
        while($this->count--)
        {
            $this->chan->pop();
        }
    }
}
go(function () {
    $wg = new WaitGroup();
    $result = [];
    $wg->add();
    //启动第一个协程
    go(function () use ($wg,&$result) {
      	swoole_timer_after(3000,function()use($wg,&$result){
        	$result['taobao'] = 'fadsfadsfadsfadsfadsfadsfdas';
        	$wg->done();
      	});
    });
    $wg->add();
    //启动第二个协程
    go(function () use ($wg,&$result) {
       swoole_timer_after(3000,function()use($wg,&$result){
        	$result['baidu'] = 'ffffffffffffffffffffffffffffffffff';
        	$wg->done();
      	});
    });
    //挂起当前协程，等待所有任务完成后恢复		
    $wg->wait();
    //这里 $result 包含了 2 个任务执行结果
    var_dump($result);
});
```





