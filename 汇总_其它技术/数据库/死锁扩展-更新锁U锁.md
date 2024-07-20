

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058825.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191058286.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191058884.jpg)





![](https://gitee.com/hxc8/images8/raw/master/img/202407191058642.jpg)







![](https://gitee.com/hxc8/images8/raw/master/img/202407191058350.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191058207.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191058822.jpg)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191058740.jpg)



这种情况，事务1写锁需要等待事务2的读锁释放以后才能进入到临界区，但是事务2的读锁需要在事务2的写锁进去完成操作以后才能释放。



所以事务1的写等待事务2的读，而事务2的写等待事务1的读，在这种情况下，就会产生死锁







![](https://gitee.com/hxc8/images8/raw/master/img/202407191058785.jpg)



那么数据库为什么不会出现死锁呢。因为他有个更新锁(U锁)







事务1，查询出id=100 再减少A  系统开始就知道读完之后就要写，所以开始不是申请读锁而是直接申请写锁



事务2 同理也是申请写锁



![](https://gitee.com/hxc8/images8/raw/master/img/202407191058430.jpg)

























