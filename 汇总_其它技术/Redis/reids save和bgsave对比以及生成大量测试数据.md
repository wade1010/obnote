
```
bob@cmbp ~ » redis-cli -h 127.0.0.1 -p 6379
127.0.0.1:6379> dbsize
(integer) 11418
127.0.0.1:6379> select 10
OK
127.0.0.1:6379[10]> dbsize
(integer) 0
127.0.0.1:6379[10]> debug populate 10000
OK
127.0.0.1:6379[10]> dbsize
(integer) 10000
```

可以用大量数据测试 save 和bgsave

#### save 

是阻塞当前Redis服务，直到保存RDB完成为止，对于内存比较大的实例会造成比较长的时间阻塞，线上环境不建议使用

#### bgsave

redis 进程执行fork操作创建子进程,保存RDB由子进程负责，完成后自动结束。阻塞只会发生在fork阶段，一般时间很短。显然bgsave是对save阻塞的优化，推荐此种方式


一个连接
```
127.0.0.1:6379[10]> save
OK
(0.54s)
127.0.0.1:6379[10]> debug populate 10000000
OK
(9.21s)
127.0.0.1:6379[10]> save
OK
(7.19s)
127.0.0.1:6379[10]> save
OK
(7.03s)
```

另一个链接


```
127.0.0.1:6379> set test 1
OK
(5.99s)
127.0.0.1:6379> get test
"1"
(6.15s)
```


所以 推荐使用 bgsave

