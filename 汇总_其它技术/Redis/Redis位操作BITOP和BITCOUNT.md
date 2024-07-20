一 BITOP

1 介绍

对一个或多个保存二进制位的字符串key进行位元操作，并将结果保存到destkey上。
operation可以是AND、OR、NOT、XOR这四种操作中的任意一种。

BITOP AND destkey key [key ...]  ，对一个或多个key求逻辑并，并将结果保存到destkey
BITOP OR destkey key [key ...] ，对一个或多个key求逻辑或，并将结果保存到destkey
BITOP XOR destkey key [key ...] ，对一个或多个key求逻辑异或，并将结果保存到destkey
BITOP NOT destkey key ，对给定key求逻辑非，并将结果保存到destkey

除了NOT操作外，其他操作都可以接受一个或多个key作为输入
当BITOP处理不同长度的字符串时，较短的那个字符串所缺少的部分会被看做0
空的key也被看作是包含0的字符串序列

2 实战


```
127.0.0.1:6379> SET key1 "foobar"
OK
127.0.0.1:6379> SET key2 "abcdeff"
OK
127.0.0.1:6379> BITOP OR dest key1 key2
(integer) 7
127.0.0.1:6379> GET dest
"goofevf"
```

二 BITCOUNT

1 介绍

统计指定位区间上值为1的个数
BITCOUNT key [start end]

从左向右从0开始，从右向左从-1开始，注意start和end是字节

BITCOUNT testkey 0 0 表示从索引0个字节到索引0个字节，就是第一个字节的统计

BITCOUNT testkey 0 -1 等同于BITCOUNT testkey 

最常用的就是BITCOUNT testkey 

2 实战

```
127.0.0.1:6379> SET mykey "foobar"
OK
127.0.0.1:6379> BITCOUNT mykey
(integer) 26
127.0.0.1:6379> BITCOUNT mykey 0 0
(integer) 4
127.0.0.1:6379> BITCOUNT mykey 1 1
(integer) 6
```

##### 参考

https://blog.csdn.net/chengqiuming/article/details/79118093