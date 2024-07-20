

```javascript
<?php declare(strict_types=1);


namespace App\Utility\Pool;


use EasySwoole\Redis\Redis;

/**
 * Class RedisUtil
 * @method static set($key, $data, $ttl = 0)
 * @method static get($key)
 * @method static hSet($key, $field, $value)
 * @method static hMSet($key, $data)
 * @method static expire($key, $ttl)
 * @method static hGetAll($key)
 * @method static hGet($key, $field, $default = null)
 * @method static incr($key)
 * @method static del(...$keys)
 * 想要添加啥办法 自己按上面格式复制一个改个名字和参数即可
 * @package App\Utility\Pool
 */
class RedisUtil
{
    public static function __callStatic($name, $arguments)
    {
        return \EasySwoole\RedisPool\Redis::invoke('redis', function (Redis $redis) use ($name, $arguments) {
            return $redis->$name(...$arguments);
        });
    }
}
```

