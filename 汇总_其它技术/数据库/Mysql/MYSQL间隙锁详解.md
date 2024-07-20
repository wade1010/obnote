https://www.jianshu.com/p/32904ee07e56





间隙锁（Gap Lock）是Innodb在可重复读提交下为了解决幻读问题时引入的锁机制，（下面的所有案例没有特意强调都使用可重复读隔离级别）幻读的问题存在是因为新增或者更新操作，这时如果进行范围查询的时候（加锁查询），会出现不一致的问题，这时使用不同的行锁已经没有办法满足要求，需要对一定范围内的数据进行加锁，间隙锁就是解决这类问题的。在可重复读隔离级别下，数据库是通过行锁和间隙锁共同组成的（next-key lock），来实现的





![](https://gitee.com/hxc8/images7/raw/master/img/202407190812377.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190812603.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190812177.jpg)

