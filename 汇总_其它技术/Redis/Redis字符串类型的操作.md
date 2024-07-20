set key value [ex 秒数] / [px 毫秒数]  [nx] /[xx]

 

如: set a 1 ex 10 , 10秒有效

Set a 1 px 9000  , 9秒有效

注: 如果ex,px同时写,以后面的有效期为准

如 set a 1 ex 100 px 9000, 实际有效期是9000毫秒

 

nx: 表示key不存在时,执行操作

xx: 表示key存在时,执行操作

 

 

mset  multi set , 一次性设置多个键值

例: mset key1 v1 key2 v2 ....

 

get key

作用:获取key的值

 

mget key1 key2 ..keyn

作用:获取多个key的值

 

 

setrange key offset value

作用:把字符串的offset偏移字节,改成value

redis 127.0.0.1:6379> set greet hello

OK

redis 127.0.0.1:6379> setrange greet 2 x

(integer) 5

redis 127.0.0.1:6379> get greet

"hexlo"

 

注意: 如果偏移量>字符长度, 该字符自动补0x00

 

redis 127.0.0.1:6379> setrange greet 6 !

(integer) 7

redis 127.0.0.1:6379> get greet

"heyyo\x00!"

 

 

 

append key value

作用: 把value追加到key的原值上

 

getrange key start stop

作用: 是获取字符串中 [start, stop]范围的值

注意: 对于字符串的下标,左数从0开始,右数从-1开始

redis 127.0.0.1:6379> set title 'chinese'

OK

redis 127.0.0.1:6379> getrange title 0 3

"chin"

redis 127.0.0.1:6379> getrange title 1 -2

"hines"

 

注意:

1: start>=length, 则返回空字符串

2: stop>=length,则截取至字符结尾

3: 如果start 所处位置在stop右边, 返回空字符串

 

 

 

getset key newvalue

作用: 获取并返回旧值,设置新值

redis 127.0.0.1:6379> set cnt 0

OK

redis 127.0.0.1:6379> getset cnt 1

"0"

redis 127.0.0.1:6379> getset cnt 2

"1"

 

incr key

作用: 指定的key的值加1,并返回加1后的值

 

注意:

1:不存在的key当成0,再incr操作

2: 范围为64有符号

incrby key number

redis 127.0.0.1:6379> incrby age  90

(integer) 92

 

incrbyfloat key floatnumber

redis 127.0.0.1:6379> incrbyfloat age 3.5

"95.5"

 

decr key

redis 127.0.0.1:6379> set age 20

OK

redis 127.0.0.1:6379> decr age

(integer) 19

 

decrby key number

redis 127.0.0.1:6379> decrby age 3

(integer) 16

 

getbit key offset

作用:获取值的二进制表示,对应位上的值(从左,从0编号)

redis 127.0.0.1:6379> set char A

OK

redis 127.0.0.1:6379> getbit char 1

(integer) 1

redis 127.0.0.1:6379> getbit char 2

(integer) 0

redis 127.0.0.1:6379> getbit char 7

(integer) 1

 

 

setbit  key offset value

设置offset对应二进制位上的值

返回: 该位上的旧值

 

注意:

1:如果offset过大,则会在中间填充0,

2: offset最大大到多少

3:offset最大2^32-1,可推出最大的的字符串为512M

 

 

bitop operation destkey key1 [key2 ...]

 

对key1,key2..keyN作operation,并将结果保存到 destkey 上。

operation 可以是 AND 、 OR 、 NOT 、 XOR

 

redis 127.0.0.1:6379> setbit lower 7 0

(integer) 0

redis 127.0.0.1:6379> setbit lower 2 1

(integer) 0

redis 127.0.0.1:6379> get lower

" "

redis 127.0.0.1:6379> set char Q

OK

redis 127.0.0.1:6379> get char

"Q"

redis 127.0.0.1:6379> bitop or char char lower

(integer) 1

redis 127.0.0.1:6379> get char

"q"

 

注意: 对于NOT操作, key不能多个



link 链表结构

 

lpush key value

作用: 把值插入到链接头部

 

rpop key

作用: 返回并删除链表尾元素

 

rpush,lpop: 不解释

 

lrange key start  stop

作用: 返回链表中[start ,stop]中的元素

规律: 左数从0开始,右数从-1开始

 

 

lrem key count value

作用: 从key链表中删除 value值

注: 删除count的绝对值个value后结束

Count>0 从表头删除

Count<0 从表尾删除

 

ltrim key start stop

作用: 剪切key对应的链接,切[start,stop]一段,并把该段重新赋给key

 

lindex key index

作用: 返回index索引上的值,

如  lindex key 2

 

llen key

作用:计算链接表的元素个数

redis 127.0.0.1:6379> llen task

(integer) 3

redis 127.0.0.1:6379>

 

linsert  key after|before search value

作用: 在key链表中寻找’search’,并在search值之前|之后,.插入value

注: 一旦找到一个search后,命令就结束了,因此不会插入多个value

 

 

rpoplpush source dest

作用: 把source的尾部拿出,放在dest的头部,

并返回 该单元值

 

场景: task + bak 双链表完成安全队列

Task列表                             bak列表

|   |   |   |
| - | - | - |
|   |   |   |


|   |   |   |
| - | - | - |
|   |   |   |


 

 

业务逻辑:

1:Rpoplpush task bak

2:接收返回值,并做业务处理

3:如果成功,rpop bak 清除任务. 如不成功,下次从bak表里取任务

 

 

brpop ,blpop  key timeout

作用:等待弹出key的尾/头元素,

Timeout为等待超时时间

如果timeout为0,则一直等待

 

场景: 长轮询Ajax,在线聊天时,能够用到



Setbit 的实际应用

 

场景: 1亿个用户, 每个用户 登陆/做任意操作  ,记为 今天活跃,否则记为不活跃

 

每周评出: 有奖活跃用户: 连续7天活动

每月评,等等...

 

思路:

 

Userid   dt  active

1        2013-07-27  1

1       2013-0726   1

 

如果是放在表中, 1:表急剧增大,2:要用group ,sum运算,计算较慢

 

 

用: 位图法 bit-map

Log0721:  ‘011001...............0’

 

......

log0726 :   ‘011001...............0’

Log0727 :  ‘0110000.............1’

 

 

1: 记录用户登陆:

每天按日期生成一个位图, 用户登陆后,把user_id位上的bit值置为1

 

2: 把1周的位图  and 计算,

位上为1的,即是连续登陆的用户

 

 

redis 127.0.0.1:6379> setbit mon 100000000 0

(integer) 0

redis 127.0.0.1:6379> setbit mon 3 1

(integer) 0

redis 127.0.0.1:6379> setbit mon 5 1

(integer) 0

redis 127.0.0.1:6379> setbit mon 7 1

(integer) 0

redis 127.0.0.1:6379> setbit thur 100000000 0

(integer) 0

redis 127.0.0.1:6379> setbit thur 3 1

(integer) 0

redis 127.0.0.1:6379> setbit thur 5 1

(integer) 0

redis 127.0.0.1:6379> setbit thur 8 1

(integer) 0

redis 127.0.0.1:6379> setbit wen 100000000 0

(integer) 0

redis 127.0.0.1:6379> setbit wen 3 1

(integer) 0

redis 127.0.0.1:6379> setbit wen 4 1

(integer) 0

redis 127.0.0.1:6379> setbit wen 6 1

(integer) 0

redis 127.0.0.1:6379> bitop and  res mon feb wen

(integer) 12500001

 

 

如上例,优点:

1: 节约空间, 1亿人每天的登陆情况,用1亿bit,约1200WByte,约10M 的字符就能表示

2: 计算方便

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

集合 set 相关命令

 

集合的性质: 唯一性,无序性,确定性

 

注: 在string和link的命令中,可以通过range 来访问string中的某几个字符或某几个元素

但,因为集合的无序性,无法通过下标或范围来访问部分元素.

 

因此想看元素,要么随机先一个,要么全选

 

sadd key  value1 value2

作用: 往集合key中增加元素

 

srem value1 value2

作用: 删除集合中集为 value1 value2的元素

返回值: 忽略不存在的元素后,真正删除掉的元素的个数

 

spop key

作用: 返回并删除集合中key中1个随机元素

 

随机--体现了无序性

 

srandmember key

作用: 返回集合key中,随机的1个元素.

 

sismember key  value

作用: 判断value是否在key集合中

是返回1,否返回0

 

smembers key

作用: 返回集中中所有的元素

 

scard key

作用: 返回集合中元素的个数

 

smove source dest value

作用:把source中的value删除,并添加到dest集合中

 

 

 

sinter  key1 key2 key3

作用: 求出key1 key2 key3 三个集合中的交集,并返回

redis 127.0.0.1:6379> sadd s1 0 2 4 6

(integer) 4

redis 127.0.0.1:6379> sadd s2 1 2 3 4

(integer) 4

redis 127.0.0.1:6379> sadd s3 4 8 9 12

(integer) 4

redis 127.0.0.1:6379> sinter s1 s2 s3

1) "4"

redis 127.0.0.1:6379> sinter s3 s1 s2

1) "4"

 

sinterstore dest key1 key2 key3

作用: 求出key1 key2 key3 三个集合中的交集,并赋给dest

 

 

suion key1 key2.. Keyn

作用: 求出key1 key2 keyn的并集,并返回

 

sdiff key1 key2 key3

作用: 求出key1与key2 key3的差集

即key1-key2-key3

 



order set 有序集合

zadd key score1 value1 score2 value2 ..

添加元素

redis 127.0.0.1:6379> zadd stu 18 lily 19 hmm 20 lilei 21 lilei

(integer) 3

 

zrem key value1 value2 ..

作用: 删除集合中的元素

 

zremrangebyscore key min max

作用: 按照socre来删除元素,删除score在[min,max]之间的

redis 127.0.0.1:6379> zremrangebyscore stu 4 10

(integer) 2

redis 127.0.0.1:6379> zrange stu 0 -1

1) "f"

 

zremrangebyrank key start end

作用: 按排名删除元素,删除名次在[start,end]之间的

redis 127.0.0.1:6379> zremrangebyrank stu 0 1

(integer) 2

redis 127.0.0.1:6379> zrange stu 0 -1

1) "c"

2) "e"

3) "f"

4) "g"

 

zrank key member

查询member的排名(升续 0名开始)

 

zrevrank key memeber

查询 member的排名(降续 0名开始)

 

ZRANGE key start stop [WITHSCORES]

把集合排序后,返回名次[start,stop]的元素

默认是升续排列

Withscores 是把score也打印出来

 

zrevrange key start stop

作用:把集合降序排列,取名字[start,stop]之间的元素

 

zrangebyscore  key min max [withscores] limit offset N

作用: 集合(升续)排序后,取score在[min,max]内的元素,

并跳过 offset个, 取出N个

redis 127.0.0.1:6379> zadd stu 1 a 3 b 4 c 9 e 12 f 15 g

(integer) 6

redis 127.0.0.1:6379> zrangebyscore stu 3 12 limit 1 2 withscores

1) "c"

2) "4"

3) "e"

4) "9"

 

 

zcard key

返回元素个数

 

zcount key min max

返回[min,max] 区间内元素的数量

 

 

zinterstore destination numkeys key1 [key2 ...]

[WEIGHTS weight [weight ...]]

[AGGREGATE SUM|MIN|MAX]

求key1,key2的交集,key1,key2的权重分别是 weight1,weight2

聚合方法用: sum |min|max

聚合的结果,保存在dest集合内

 

注意: weights ,aggregate如何理解?

答: 如果有交集, 交集元素又有socre,score怎么处理?

 Aggregate sum->score相加   , min 求最小score, max 最大score

 

另: 可以通过weigth设置不同key的权重, 交集时,socre * weights

 

详见下例

redis 127.0.0.1:6379> zadd z1 2 a 3 b 4 c

(integer) 3

redis 127.0.0.1:6379> zadd z2 2.5 a 1 b 8 d

(integer) 3

redis 127.0.0.1:6379> zinterstore tmp 2 z1 z2

(integer) 2

redis 127.0.0.1:6379> zrange tmp 0 -1

1) "b"

2) "a"

redis 127.0.0.1:6379> zrange tmp 0 -1 withscores

1) "b"

2) "4"

3) "a"

4) "4.5"

redis 127.0.0.1:6379> zinterstore tmp 2 z1 z2 aggregate sum

(integer) 2

redis 127.0.0.1:6379> zrange tmp 0 -1 withscores

1) "b"

2) "4"

3) "a"

4) "4.5"

redis 127.0.0.1:6379> zinterstore tmp 2 z1 z2 aggregate min

(integer) 2

redis 127.0.0.1:6379> zrange tmp 0 -1 withscores

1) "b"

2) "1"

3) "a"

4) "2"

redis 127.0.0.1:6379> zinterstore tmp 2 z1 z2 weights 1 2

(integer) 2

redis 127.0.0.1:6379> zrange tmp 0 -1 withscores

1) "b"

2) "5"

3) "a"

4) "7"

 

 

 

 

 

 



Hash 哈希数据类型相关命令

 

hset key field value

作用: 把key中 filed域的值设为value

注:如果没有field域,直接添加,如果有,则覆盖原field域的值

 

hmset key field1 value1 [field2 value2 field3 value3 ......fieldn valuen]

作用: 设置field1->N 个域, 对应的值是value1->N

(对应PHP理解为  $key = array(file1=>value1, field2=>value2 ....fieldN=>valueN))

 

 

hget key field

作用: 返回key中field域的值

 

hmget key field1 field2 fieldN

作用: 返回key中field1 field2 fieldN域的值

 

hgetall key

作用:返回key中,所有域与其值

 

hdel key field

作用: 删除key中 field域

 

hlen key

作用: 返回key中元素的数量

 

hexists key field

作用: 判断key中有没有field域

 

hinrby key field value

作用: 是把key中的field域的值增长整型值value

 

hinrby float  key field value

作用: 是把key中的field域的值增长浮点值value

 

hkeys key

作用: 返回key中所有的field

 

kvals key

作用: 返回key中所有的value

 



Redis 中的事务

 

Redis支持简单的事务

 

Redis与 mysql事务的对比

 

|   | Mysql | Redis |
| - | - | - |
| 开启 | start transaction | muitl |
| 语句 | 普通sql | 普通命令 |
| 失败 | rollback 回滚 | discard 取消 |
| 成功 | commit | exec |


 

注: rollback与discard 的区别

如果已经成功执行了2条语句, 第3条语句出错.

Rollback后,前2条的语句影响消失.

Discard只是结束本次事务,前2条语句造成的影响仍然还在

 

注:

在mutil后面的语句中, 语句出错可能有2种情况

1: 语法就有问题,

这种,exec时,报错, 所有语句得不到执行

 

2: 语法本身没错,但适用对象有问题. 比如 zadd 操作list对象

Exec之后,会执行正确的语句,并跳过有不适当的语句.

 

(如果zadd操作list这种事怎么避免? 这一点,由程序员负责)

 

 

思考:

我正在买票

Ticket -1 , money -100

而票只有1张, 如果在我multi之后,和exec之前, 票被别人买了---即ticket变成0了.

我该如何观察这种情景,并不再提交

 

悲观的想法:

世界充满危险,肯定有人和我抢, 给 ticket上锁, 只有我能操作. [悲观锁]

 

乐观的想法:

没有那么人和我抢,因此,我只需要注意,

--有没有人更改ticket的值就可以了 [乐观锁]

 

Redis的事务中,启用的是乐观锁,只负责监测key没有被改动.

 

 

具体的命令----  watch命令

例:

redis 127.0.0.1:6379> watch ticket

OK

redis 127.0.0.1:6379> multi

OK

redis 127.0.0.1:6379> decr ticket

QUEUED

redis 127.0.0.1:6379> decrby money 100

QUEUED

redis 127.0.0.1:6379> exec

(nil)   // 返回nil,说明监视的ticket已经改变了,事务就取消了.

redis 127.0.0.1:6379> get ticket

"0"

redis 127.0.0.1:6379> get money

"200"

 

 

watch key1 key2  ... keyN

作用:监听key1 key2..keyN有没有变化,如果有变, 则事务取消

 

unwatch

作用: 取消所有watch监听

 

 

 

 

 

 



消息订阅

 

使用办法:

订阅端: Subscribe 频道名称

发布端: publish 频道名称 发布内容

 

客户端例子:

redis 127.0.0.1:6379> subscribe news

Reading messages... (press Ctrl-C to quit)

1) "subscribe"

2) "news"

3) (integer) 1

1) "message"

2) "news"

3) "good good study"

1) "message"

2) "news"

3) "day day up"

 

服务端例子:

redis 127.0.0.1:6379> publish news 'good good study'

(integer) 1

redis 127.0.0.1:6379> publish news 'day day up'

(integer) 1