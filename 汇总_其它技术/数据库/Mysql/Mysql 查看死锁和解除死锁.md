https://www.cnblogs.com/jpfss/p/11491526.html



在分析innodb中锁阻塞时，几种方法的对比情况：

（1）使用show processlist查看不靠谱；

（2）直接使用show engine innodb status查看，无法判断到问题的根因；

（3）使用mysqladmin debug查看，能看到所有产生锁的线程，但无法判断哪个才是根因；

（4）开启innodb_lock_monitor后，再使用show engine innodb status查看，能够找到锁阻塞的根因。