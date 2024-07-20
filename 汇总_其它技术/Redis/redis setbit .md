#### 格式

> SETBIT key offset value

 对 key 所储存的字符串值，设置或清除指定偏移量上的位(bit)。
 
 
 #### 举例
 
设置一个　key-value ，键的名字叫'test' 值为字符'a'

```
127.0.0.1:6379> set test a
OK
127.0.0.1:6379> get test
"a"
```

我们知道 'a' 的ASCII码是 97。转换为二进制是：01100001。offset的学名叫做“偏移” 。二进制中的每一位就是offset值啦，比如在这里 offset 0 等于 ‘0’ ，offset 1等于 '1' ,offset 2 等于 '1',offset 6 等于 '0' ，没错，offset是从左往右计数的，也就是从高位往低位。

我们通过SETBIT 命令将 'test'中的 'a' 变成 'b' 应该怎么变呢？

也就是将 01100001 变成 01100010 （b的ASCII码是98），这个很简单啦，也就是将'a'中的offset 6从0变成1，将offset 7 从1变成0 。

```
127.0.0.1:6379> setbit test 6 1
(integer) 0
127.0.0.1:6379> setbit test 7 0
(integer) 1
127.0.0.1:6379> get test
"b"
```

#### BITCOUNT

就是统计字符串的二进制码中，有多少个'1'。 所以在这里

```
127.0.0.1:6379> bitcount test
(integer) 3
```

