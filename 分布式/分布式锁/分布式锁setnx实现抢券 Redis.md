//未封装，下面有封装的

```javascript
<?php
//高性能HTTP服务器
$http = new Swoole\Http\Server("127.0.0.1", 9501);

$http->on("start", function ($server) {
    $redis = new Redis();
    $redis->connect('127.0.0.1');
    $redis->set('count', 1);
    $redis->del('coupon_user');
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$http->on("request", function (Swoole\Http\Request $request, $response) {
    if ($request->server['request_uri'] == '/favicon.ico') {
        $response->status(404);
        $response->end();
    }
    $redis = new Redis();
    $redis->connect('127.0.0.1');
    if (($request->get['show'] ?? '') == 'show') {
        $msg = json_encode($redis->hGetAll('coupon_user')) . $redis->get('count');
    } else {
        $msg = buy($redis);
    }
    $response->header("Content-Type", "text/plain;charset=utf8");
    $response->end("$msg\n");
});

function buy(Redis $redis)
{
    $userId = rand(0, 1000) % 3;
    try {
        $retry = 3;
        do {
            $key = 'my_lock';
            $value = session_create_id();
            $isLock = $redis->set($key, $value, ['NX', 'EX' => 5]);
            var_dump($retry);
            if ($isLock) {
                $lua = <<<lua
            local key = KEYS[1]
            local value = ARGV[1]
            if redis.call('get',key)==value
            then
                return redis.call('del',key)
            else
                return 0
            end
lua;
                //执行业务
                //判断用户是否已经抢过了
                $result = $redis->hGet('coupon_user', $userId);
                if ($result) {
                    $redis->eval($lua, [$key, $value], 1);
                    return '你已经抢过了';
                }
                //判定券是否已经抢完
                $count = $redis->get('count');
                if ($count < 1) {
                    $redis->eval($lua, [$key, $value], 1);
                    return '已经被抢完';
                }
                /*开启事务*/
                $redis->multi();
                //模拟业务
                sleep(3);//这里加入执行业务时间比锁的有效期长，其实需要加个看门狗的东西给锁续期
                /*给该用户绑定一张优惠券*/
                $redis->hSet('coupon_user', $userId, 1);
                $redis->decr('count');
                $redis->exec();
                $a = $redis->eval($lua, [$key, $value], 1);
                if ($a) {
                    return '抢票成功了';
                }

            } else {
                usleep(10000);
            }

        } while (!$isLock && $retry-- > 0);
        return '抢券失败';
    } catch (\Exception $e) {
        $redis->discard();
        return $e->getMessage();
    }
}

$http->start();
```





封装了下

1、Lock类

```javascript
<?php declare(strict_types=1);


class Lock
{
    /**
     * @var Redis $redis
     */
    protected $redis;
    protected $lockId;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function lock($key, $ex = 10, $retry = 3, $usleep = 10000)
    {
        $res = false;
        $this->lockId = session_create_id();
        while ($retry-- > 0) {
            if ($res = $this->redis->set($key, $this->lockId, ['NX', 'EX' => $ex])) {
                break;
            }
            usleep($usleep);
        }
        return $res;
    }

    public function unlock($key)
    {
        $script = <<<lua
local key = KEYS[1]
local val = ARGV[1]
if redis.call('get',key)==val
then
    return redis.call('del',key)
else
    return 0
end
lua;
        return $this->redis->eval($script, [$key, $this->lockId], 1);
    }
}
```



2、server.php

```javascript
<?php
//高性能HTTP服务器
$http = new Swoole\Http\Server("127.0.0.1", 9501);

$http->on("start", function ($server) {
    $redis = new Redis();
    $redis->connect('127.0.0.1');
    $redis->set('count', 1);
    $redis->del('coupon_user');
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$http->on("request", function (Swoole\Http\Request $request, $response) {
    if ($request->server['request_uri'] == '/favicon.ico') {
        $response->status(404);
        $response->end();
    }
    require_once __DIR__ . '/lock.php';
    $redis = new Redis();
    $redis->connect('127.0.0.1');
    if (($request->get['show'] ?? '') == 'show') {
        $msg = json_encode($redis->hGetAll('coupon_user')) . $redis->get('count');
    } else {
        $userId = rand(0, 1000) % 3;
        $msg = buy($redis, $userId);
    }
    $response->header("Content-Type", "text/plain;charset=utf8");
    $response->end("$msg\n");
});

function buy(Redis $redis, $userId)
{
    $lock = new Lock($redis);
    $key = 'my_lock';
    if ($lock->lock($key)) {
        //执行业务
        //判断用户是否已经抢过了
        $result = $redis->hGet('coupon_user', $userId);
        if ($result) {
            $lock->unlock($key);
            return '你已经抢过了';
        }
        //判定券是否已经抢完
        $count = $redis->get('count');
        if ($count < 1) {
            $lock->unlock($key);
            return '已经被抢完';
        }
        //模拟业务
        sleep(3);//这里加入执行业务时间比锁的有效期长，其实需要加个看门狗的东西给锁续期,或者开个子线程续期
        /*给该用户绑定一张优惠券*/
        $redis->hSet('coupon_user', $userId, 1);
        $redis->decr('count');

        $res = $lock->unlock($key);
        return '抢票成功' . ($res ? '且解锁成功' : '但解锁失败');
    } else {
        return '获取锁失败';
    }
}

$http->start();
```

