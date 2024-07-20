令牌桶实现原理

对于令牌桶中令牌的产生一般有两种做法：

- 一种解法是，开启一个定时任务，由定时任务持续生成令牌。这样的问题在于会极大的消耗系统资源，如，某接口需要分别对每个用户做访问频率限制，假设系统中存在6W用户，则至多需要开启6W个定时任务来维持每个桶中的令牌数，这样的开销是巨大的。

- 第二种解法是延迟计算，定义一个 resync 函数。该函数会在每次获取令牌之前调用，其实现思路为，若当前时间晚于 nextFreeTicketMicros，则计算该段时间内可以生成多少令牌，将生成的令牌加入令牌桶中并更新数据。这样一来，只需要在获取令牌时计算一次即可。

Swoft 采用的是第二种，当每次获取令牌时，先执行 resync 来更新令牌桶中令牌的数量，从而达到异步产生令牌的目的。





```javascript
public function getTicket(array $config): bool
    {
        $name = $config['name'];
        $key = $config['key'];

        $now = time();
        $sKey = $this->getStorekey($name, $key);
        $nKey = $this->getNextTimeKey($name, $key);

        $rate = $config['rate'];
        $max = $config['max'];
        $default = $config['default'];

        $lua = <<<LUA
        local sKey = KEYS[1];
        local nKey = KEYS[2];
        local now = tonumber(ARGV[1]);
        local rate = tonumber(ARGV[2]);
        local max = tonumber(ARGV[3]);
        local default = tonumber(ARGV[4]);
        
        local sNum = redis.call('get', sKey);
        if((not sNum) or sNum == nil)
        then
            sNum = 0
        end
        
        sNum = tonumber(sNum);
        
        local nNum = redis.call('get', nKey);
        if((not nNum) or nNum == nil)
        then
            nNum = now
            sNum = default
        end
        
        nNum = tonumber(nNum);
        
        local newPermits = 0;
        if(now > nNum)
        then
              newPermits = (now-nNum)*rate+sNum;
              sNum = math.min(newPermits, max)
        end
        
        local isPermited = 0;
        if(sNum > 0)
        then
            sNum = sNum -1;
            isPermited = 1;
        end
        
        redis.call('set', sKey, sNum);
        redis.call('set', nKey, now);
        
        return isPermited;
LUA;

        $args = [
            $sKey,
            $nKey,
            $now,
            $rate,
            $max,
            $default,
        ];

        $result = Redis::connection($this->pool)->eval($lua, $args, 2);
        return (bool)$result;
    }
```

