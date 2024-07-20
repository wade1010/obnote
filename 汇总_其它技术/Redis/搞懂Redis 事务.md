提到redis的事务，相信很多初学的朋友会对它的理解和使用有些模糊不清，料想它和我们常见的关系型数据库（mysql 、mssql等）中的事务相同，也支持回滚，但这样理解就进入了一个误区，首先：关系型数据中的事务都是原子性的，而redis 的事务是非原子性的。再多说一句，什么是程序原子性？简单的理解就是：整个程序中的所有操作，要么全部完成，要不全部不完成，不会停留在中间某个环节。那么非原子性就是不满足原子性的条件就是非原子性了。我们用例子来解释一下：

原子性：数据库中的某个事务A中要更新t1表、t2表的某条记录，当事务提交，t1、t2两个表都被更新，若其中一个表操作失败，事务将回滚。

非原子性：数据库中的某个事务A中要更新t1表、t2表的某条记录，当事务提交，t1、t2两个表都被更新，若其中一个表操作失败，另一个表操作继续，事务不会回滚。（当然对于关系型数据库不会出现非原子性）

Redis事务相关命令：

- MULTI ：开启事务，redis会将后续的命令逐个放入队列中，然后使用EXEC命令来原子化执行这个命令系列。

- EXEC：执行事务中的所有操作命令。

- DISCARD：取消事务，放弃执行事务块中的所有命令。

- WATCH：监视一个或多个key,如果事务在执行前，这个key(或多个key)被其他命令修改，则事务被中断，不会执行事务中的任何命令。

- UNWATCH：取消WATCH对所有key的监视。

下面具体看一下事务命令的使用：

1、MULTI开始一个事务：

（1） 给k1、k2分别赋值，在事务中修改k1、k2，执行事务后，查看k1、k2值都被修改。

```javascript
127.0.0.1:6379> set k1 v1
OK
127.0.0.1:6379> set k2 v2
OK
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set k1 11
QUEUED
127.0.0.1:6379> set k2 22
QUEUED
127.0.0.1:6379> EXEC
1) OK
2) OK
127.0.0.1:6379> get k1
"11"
127.0.0.1:6379> get k2
"22"
127.0.0.1:6379>
```

（2）事务失败处理：

-    语法错误（编译器错误），在开启事务后，修改k1值为11，k2值为22，但k2语法错误，最终导致事务提交失败，k1、k2保留原值。

```javascript
127.0.0.1:6379> set k1 v1
OK
127.0.0.1:6379> set k2 v2
OK
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set k1 11
QUEUED
127.0.0.1:6379> sets k2 22
(error) ERR unknown command `sets`, with args beginning with: `k2`, `22`, 
127.0.0.1:6379> exec
(error) EXECABORT Transaction discarded because of previous errors.
127.0.0.1:6379> get k1
"v1"
127.0.0.1:6379> get k2
"v2"
127.0.0.1:6379> 
```



-    Redis类型错误（运行时错误），在开启事务后，修改k1值为11，k2值为22，但将k2的类型作为List，在运行时检测类型错误，最终导致事务提交失败，此时事务并没有回滚，而是跳过错误命令继续执行， 结果k1值改变、k2保留原值。

```javascript
127.0.0.1:6379> set k1 v1
OK
127.0.0.1:6379> set k1 v2
OK
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set k1 11
QUEUED
127.0.0.1:6379> lpush k2 22
QUEUED
127.0.0.1:6379> EXEC
1) OK
2) (error) WRONGTYPE Operation against a key holding the wrong kind of value
127.0.0.1:6379> get k1
"11"
127.0.0.1:6379> get k2
"v2"
127.0.0.1:6379>
```



总结：为什么Redis不支持事务回滚？

以上两个例子总结出，多数事务失败是由语法错误或者数据结构类型错误导致的，语法错误说明在命令入队前就进行检测的，而类型错误是在执行时检测的，Redis为提升性能而采用这种简单的事务，这是不同于关系型数据库的，特别要注意区分。

2、EXEC执行事务中的所有命令：

必须与MULTI命令成对使用

```javascript
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set k1 23
QUEUED
127.0.0.1:6379> set k2 22
QUEUED
127.0.0.1:6379> EXEC
1) OK
2) OK
```

 

3、DISCARD取消事务：

```javascript
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set k1 33
QUEUED
127.0.0.1:6379> set k2 34
QUEUED
127.0.0.1:6379> DISCARD
OK
```

4、WATCH监视key：

严格的说Redis的命令是原子性的，而事务是非原子性的，我们要让Redis事务完全具有事务回滚的能力，需要借助于命令WATCH来实现。

Redis使用WATCH命令来决定事务是继续执行还是回滚，那就需要在MULTI之前使用WATCH来监控某些键值对，然后使用MULTI命令来开启事务，执行对数据结构操作的各种命令，此时这些命令入队列。

当使用EXEC执行事务时，首先会比对WATCH所监控的键值对，如果没发生改变，它会执行事务队列中的命令，提交事务；如果发生变化，将不会执行事务中的任何命令，同时事务回滚。当然无论是否回滚，Redis都会取消执行事务前的WATCH命令。

 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191112273.jpg)

 Redis执行事务过程

在事务开始前用WATCH监控k1，之后修改k1为11，说明事务开始前k1值被改变，MULTI开始事务，修改k1值为12，k2为22，执行EXEC，发回nil，说明事务回滚；查看下k1、k2的值都没有被事务中的命令所改变。

```javascript
127.0.0.1:6379> set k1 v1
OK
127.0.0.1:6379> set k2 v2
OK
127.0.0.1:6379> WATCH k1
OK
127.0.0.1:6379> set k1 11
OK
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set k1 12
QUEUED
127.0.0.1:6379> set k2 22
QUEUED
127.0.0.1:6379> EXEC
(nil)
127.0.0.1:6379> get k1
"11"
127.0.0.1:6379> get k2
"v2"
127.0.0.1:6379>
```

5、UNWATCH取消监视所有key：

```javascript
127.0.0.1:6379> set k1 v1
OK
127.0.0.1:6379> set k2 v2
OK
127.0.0.1:6379> WATCH k1
OK
127.0.0.1:6379> set k1 11
OK
127.0.0.1:6379> UNWATCH
OK
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set k1 12
QUEUED
127.0.0.1:6379> set k2 22
QUEUED
127.0.0.1:6379> exec
1) OK
2) OK
127.0.0.1:6379> get k1
"12"
127.0.0.1:6379> get k2
"22"
127.0.0.1:6379>
```

 

参考资料：

http://c.biancheng.net/view/4544.html









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