MVCC的两种读形式:

- 快照读

读取的只是当前事务的可见版本，不用加锁。而你只要记住 简单的 select操作就是快照读(select * from table where id = xxx)。

- 当前读

读取的是当前版本，比如 特殊的读操作，更新/插入/删除操作.

比如：

 select * from table where xxx lock in share mode，
 select * from table where xxx for update，
 update table set....
 insert into table (xxx,xxx) values (xxx,xxx)
 delete from table where id = xxx



![](https://gitee.com/hxc8/images8/raw/master/img/202407191058180.jpg)

