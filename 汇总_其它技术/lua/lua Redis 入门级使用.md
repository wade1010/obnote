

```javascript
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/app/config/define.php";
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
//$setName = <<<lua
//return redis.call('set','name','bob');
//lua;
//echo $redis->eval($setName);

//$getName = <<<lua
//return redis.call('get','name');
//lua;
//echo $redis->eval($getName);

$set = <<<lua
return redis.call('set',KEYS[1],ARGV[1]);
lua;
echo $redis->eval($set, ['name', '发大多数'], 1);
echo $redis->get('name');
```

