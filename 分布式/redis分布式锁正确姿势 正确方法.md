来自于redis作者antireZ的总结归纳



加锁



通过setnx向特定的key写入一个随机值,并同时设置失效时间，写值成功即加锁成功：

注意点： 

 必须给锁设置一个失效时间    -》避免死锁

加锁时，每个节点产生一个随机字符串-》避免锁误删

写入随机值与设置失效时间必须是同时的-》保证加锁是原子的



解锁



匹配随机值，删除Redis上的特定的key数据，要保证获取数据，判断一致以及删除数据三个操作是原子的；

执行如下lua脚本：

```javascript
if redis.call("get",KEYS[1]) == ARGV[1] then return redis.call("del",KEYS[1])
esle
return 0
end
```



![](https://gitee.com/hxc8/images7/raw/master/img/202407190029450.jpg)

