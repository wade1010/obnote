1.查看当前会话隔离级别

 

select @@tx_isolation;

 

2.查看系统当前隔离级别

 

select @@global.tx_isolation;

 

3.设置当前会话隔离级别

 

set session transaction isolation level repeatable read;



 

4.设置系统当前隔离级别

 

set global transaction isolation level repeatable read;

 

5.命令行，开始事务时

 

set autocommit=off 或者 start transaction

 

关于隔离级别的理解

 

1.read uncommitted

 

可以看到未提交的数据（脏读），举个例子：别人说的话你都相信了，但是可能他只是说说，并不实际做。



解决的问题：没有使用事务隔离产生的数据不一致

产生的问题：脏读

 

2.read committed

 

读取提交的数据。但是，可能多次读取的数据结果不一致（不可重复读，幻读）。用读写的观点就是：读取的行数据，可以写。

 

解决的问题：脏读

产生的问题：可以读到已提交的事务->不可重复读



3.repeatable read(MySQL默认隔离级别)

 

可以重复读取，但有幻读。读写观点：读取的数据行不可写，但是可以往表中新增数据。在MySQL中，其他事务新增的数据，看不到，不会产生幻读。采用多版本并发控制（MVCC）机制解决幻读问题。



解决的问题：脏读   不可重复读

产生的问题：幻读

 

4.serializable

 

可读，不可写。像java中的锁，写数据必须等待另一个事务结束。







幻读产生的情景：

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056766.jpg)



除了MVVC解决幻读外 也可以如下  (查询的时候加个排它锁)



![](https://gitee.com/hxc8/images8/raw/master/img/202407191056401.jpg)





原因



![](https://gitee.com/hxc8/images8/raw/master/img/202407191056985.jpg)

