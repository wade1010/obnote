## 使用场景一：用户周活跃

一周用户登录情况，假设用户ID 1000 1001 1002

```
127.0.0.1:6379> setbit Monday 1000 0
(integer) 0
127.0.0.1:6379> setbit Monday 1001 1
(integer) 0
127.0.0.1:6379> setbit Monday 1002 1
(integer) 0
127.0.0.1:6379> setbit Tuesday 1000 0
(integer) 0
127.0.0.1:6379> setbit Tuesday 1001 0
(integer) 0
127.0.0.1:6379> setbit Tuesday 1002 1
(integer) 0
127.0.0.1:6379> setbit Wednesday 1000 0
(integer) 0
127.0.0.1:6379> setbit Wednesday 1001 1
(integer) 0
127.0.0.1:6379> setbit Wednesday 1002 1
(integer) 0
127.0.0.1:6379> setbit Thursday 1000 0
(integer) 0
127.0.0.1:6379> setbit Thursday 1001 0
(integer) 0
127.0.0.1:6379> setbit Thursday 1002 0
(integer) 0
127.0.0.1:6379> setbit Friday 1000 0
(integer) 0
127.0.0.1:6379> setbit Friday 1001 1
(integer) 0
127.0.0.1:6379> setbit Friday 1002 1
(integer) 0
127.0.0.1:6379> setbit Saturday 1000 0
(integer) 0
127.0.0.1:6379> setbit Saturday 1001 1
(integer) 0
127.0.0.1:6379> setbit Saturday 1002 0
(integer) 0
127.0.0.1:6379> setbit Sunday 1000 0
(integer) 0
127.0.0.1:6379> setbit Sunday 1001 1
(integer) 0
127.0.0.1:6379> setbit Sunday 1002 0
(integer) 0
 
```

接下来要计算7天内有登录行为的用户，只需要将周一到周五的值做位或运算就可以了
补充下位与运算符：

```
按位与运算符（&）
参加运算的两个数据，按二进制位进行“与”运算。
运算规则：0&0=0;  0&1=0;   1&0=0;    1&1=1;
    即：两位同时为“1”，结果才为“1”，否则为0
      
按位或运算符（|）
参加运算的两个对象，按二进制位进行“或”运算。
运算规则：0|0=0；  0|1=1；  1|0=1；   1|1=1；
    即 ：参加运算的两个对象只要有一个为1，其值为1。
     
异或运算符（^）
参加运算的两个数据，按二进制位进行“异或”运算。
运算规则：0^0=0；  0^1=1；  1^0=1；   1^1=0；
即：参加运算的两个对象，如果两个相应位为“异”（值不同），则该位结果为1，否则为0。
```

 ##### 命令格式
 
 > bitop operation destkey key1 [key2 ...]

##### 解释

对key1 key2做opecation并将结果保存在destkey上
opecation可以是AND（与） OR（或） NOT（非） XOR（异或）

最后计算7天内登录过的活跃用户：

```
127.0.0.1:6379> bitop OR result Monday Tuesday Wednesday Thursday Friday Saturday Sunday
```

```
1000 0|0|0|0|0|0|0 = 0
1001 1|0|1|0|1|1|1 = 1
1002 1|1|1|0|1|0|0 = 1

```

这里计算的结果假设3个用户id都是连续的话就是 110，其实真实的存储位置是
…1…1…0…
```
127.0.0.1:6379> bitcount result
(integer) 2
```
也就是本周有2个活跃用户登录过。


## 使用场景二：统计活跃用户

使用时间作为cacheKey，然后用户ID为offset，如果当日活跃过就设置为1
那么我该如果计算某几天/月/年的活跃用户呢(暂且约定，统计时间内只有有一天在线就称为活跃)，有请下一个redis的命令

命令 BITOP operation destkey key [key …]

说明：对一个或多个保存二进制位的字符串 key

进行位元操作，并将结果保存到 destkey 上。

说明：BITOP 命令支持 AND 、 OR 、 NOT 、 XOR 这四种操作中的任意一种参数


```
//日期对应的活跃用户
 
$data = array(
 
'2017-01-10' => array(1,2,3,4,5,6,7,8,9,10),
 
'2017-01-11' => array(1,2,3,4,5,6,7,8),
 
'2017-01-12' => array(1,2,3,4,5,6),
 
'2017-01-13' => array(1,2,3,4),
 
'2017-01-14' => array(1,2)
 
);
 
 
 
//批量设置活跃状态
 
foreach($data as $date=>$uids) {
 
$cacheKey = sprintf("stat_%s", $date);
 
foreach($uids as $uid) {
 
$redis->setBit($cacheKey, $uid, 1);
 
}
 
}
 
 
 
$redis->bitOp('AND', 'stat', 'stat_2017-01-10', 'stat_2017-01-11', 'stat_2017-01-12') . PHP_EOL;
 
//总活跃用户：6
 
echo "总活跃用户：" . $redis->bitCount('stat') . PHP_EOL;
 
 
 
$redis->bitOp('AND', 'stat1', 'stat_2017-01-10', 'stat_2017-01-11', 'stat_2017-01-14') . PHP_EOL;
 
//总活跃用户：2
 
echo "总活跃用户：" . $redis->bitCount('stat1') . PHP_EOL;
 
 
 
$redis->bitOp('AND', 'stat2', 'stat_2017-01-10', 'stat_2017-01-11') . PHP_EOL;
 
//总活跃用户：8
 
echo "总活跃用户：" . $redis->bitCount('stat2') . PHP_EOL;
```

假设当前站点有5000W用户，那么一天的数据大约为50000000/8/1024/1024=6MB

## 使用场景三：用户在线状态

使用bitmap是一个节约空间效率又高的一种方法，只需要一个key，然后用户ID为offset，如果在线就设置为1，不在线就设置为0，和上面的场景一样，5000W用户只需要6MB的空间。


```
//批量设置在线状态
$uids = range(1, 500000);
 
foreach($uids as $uid) {
 
$redis->setBit('online', $uid, $uid % 2);
 
}
 
//一个一个获取状态
 
$uids = range(1, 500000);
 
$startTime = microtime(true);
 
foreach($uids as $uid) {
 
echo $redis->getBit('online', $uid) . PHP_EOL;
 
}
 
$endTime = microtime(true);
 
//在我的电脑上，获取50W个用户的状态需要25秒
 
echo "total:" . ($endTime - $startTime) . "s";
 
 
 
 
/**
* 对于批量的获取，上面是一种效率低的办法，实际可以通过get获取到value，然后自己计算
* 具体计算方法改天再写吧，之前写的代码找不见了。。。
*/

```


## 使用场景四：用户签到

很多网站都提供了签到功能(这里不考虑数据落地事宜)，并且需要展示最近一个月的签到情况，如果使用bitmap我们怎么做？一言不合亮代码！


```
<?php
$redis = new Redis();
$redis->connect('127.0.0.1');
 
 
//用户uid
$uid = 1;
 
//记录有uid的key
$cacheKey = sprintf("sign_%d", $uid);
 
//开始有签到功能的日期
$startDate = '2017-01-01';
 
//今天的日期
$todayDate = '2017-01-21';
 
//计算offset
$startTime = strtotime($startDate);
$todayTime = strtotime($todayDate);
$offset = floor(($todayTime - $startTime) / 86400);
 
echo "今天是第{$offset}天" . PHP_EOL;
 
//签到
//一年一个用户会占用多少空间呢？大约365/8=45.625个字节，好小，有木有被惊呆？
$redis->setBit($cacheKey, $offset, 1);
 
//查询签到情况
$bitStatus = $redis->getBit($cacheKey, $offset);
echo 1 == $bitStatus ? '今天已经签到啦' : '还没有签到呢';
echo PHP_EOL;
 
//计算总签到次数
echo $redis->bitCount($cacheKey) . PHP_EOL;
 
/**
* 计算某段时间内的签到次数
* 很不幸啊,bitCount虽然提供了start和end参数，但是这个说的是字符串的位置，而不是对应"位"的位置
* 幸运的是我们可以通过get命令将value取出来，自己解析。并且这个value不会太大，上面计算过一年一个用户只需要45个字节
* 给我们的网站定一个小目标，运行30年，那么一共需要1.31KB(就问你屌不屌？)
*/
//这是个错误的计算方式 redis的setbit设置或清除的是bit位置，而bitcount计算的是byte位置。
echo $redis->bitCount($cacheKey, 0, 20) . PHP_EOL;
```
