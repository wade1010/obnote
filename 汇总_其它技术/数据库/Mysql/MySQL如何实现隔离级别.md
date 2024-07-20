

![](https://gitee.com/hxc8/images7/raw/master/img/202407190812774.jpg)





ru隔离级别：写加X锁，事务提交或回滚释放X锁，读不加锁

rc：写加排它锁，读一条语句看到的是一个snapshoot version

rr:一个事务看到的一个snapshoot







MySQL是如何支持不同隔离级别的

知识准备(术语解释)

- 一致性读取 (consistent read)

- 事务在进行读操作时，使用的是事务开始时的行快照数据，这样就不用担心读到其他其他事务修改的数据。

- 在[可重复读]下，事务快照是基于第一次读操作的快照(通过undo log 回溯)

- 在[可提交读]下，每一次一致性读操作都会重置快照

- 优点：不上锁，允许其他事务进行修改

- 半一致性读取 (semi-consistent read)

- 即UPDATE语句中的读/匹配操作，当UPDATE语句执行的时候，InnoDB会取最后一次提交到MySQL的数据来进行 Where 子句中的匹配。

- 如果匹配上了（也就是要更新），那就重读该行并加锁（或等待加锁）

- todo 为什么要重读该行不是很理解，直接用那条数据不就好了

- 仅用于[可提交读]隔离级别

- 个人理解其实就是一致性读取在[可提交读]隔离级别下 UPDATE的表现

- 锁定读 (locking read)

- 即加锁的查询语句

- E.g SELECT ... FOR UPDATE | SELECT ... LOCK IN SHARE MODE

- 行锁 (record lock) : 即锁定索引记录的锁，即使没有索引也会找到对应行记录锁主键哦

- 间隙锁 (gap lock) : 锁在了两条索引记录之间的锁，或者(无穷小,某索引)/(某索引,无穷大)，他们锁住的是一个范围，且不同的间隙锁不互斥，他们排斥的只是在锁范围内的插入操作

- mark: R.C 隔离级别下是被禁用的

- next-key lock : 行锁和间隙锁的组合实现

---

读未提交 - Read Uncommited

- SELECT 语句采用的无锁策略，也就更容易产生脏读

- 写加写锁，事务提交或回滚释放写锁

---

读已提交 - Read Commited

- 该隔离级别下的一致性读取，读的都是最新版的快照，每次读快照都会被重置成最新

Each consistent read, even within the same transaction, sets and reads its own fresh snapshot.

- 对于Locking Reads、UPDATE、DELETE 语句，InnoDB的锁策略是只锁索引记录，并没有用间隙锁来锁范围，所以容易产生幻读问题

特别的对于 R.C 隔离级别有以下变动

- 对于UPDATE语句，如果行已经被加锁了，InnoDB会使用 “semi-consistent”read，来读取快照中最新的值(而不是最原始的快照)来进行 WHERE 匹配更新。（也就是说 UPDATE 也采用 R.C 特供版一致性读取）

- 对于UPDATE或 DELETE语句，InnoDB仅对其更新或删除的行持有锁。MySQL评估WHERE条件后，将释放不匹配行的记录锁。这大大降低了死锁的可能性。

- 比如扫表过程中，扫到这行不是要修改的，那么就释放锁，要改的就一直加锁直到事务结束

- R.R 级别下则不会释放

关于这个“semi-consistent” read

- 他读取的是最后一次提交到MySQL的版本，而不是快照中最早读的那个版本

- 优点：MySQL可以判断当前的更新操作是否符合Where条件，如果不匹配就不用更新了，也就节省了一次写操作，如果匹配就重新读取尝试加锁

- 缺点：如果都采用一致性读consistent read，读取都是最早事务的快照，就不会产生幻象问题了(Phantom Problem)；但是采用了半一致性，两次查询的结果可能会不一致。

---

可重复读 - Repeatable Read

- 采用一致性读取，同事务中的每次读取都取第一次读的快照。（R.R 版一致性读取）

Consistent reads within the same transaction read the snapshot established by the first read.

- 对于 locking reads，UPDATE，DELETE ，其加锁策略取决于是否是唯一索引唯一条件查询

- 唯一索引配合唯一查询条件，引擎只锁定那条索引记录，不锁间隙

- 其他场景，引擎使用Gap Lock 或 Next-Record Lock来锁定扫描的索引范围，以阻止其他事务插入新行到该间隙

---

串行化 - Serializable

- InnoDB 默默的把所有纯 SELECT 语句都转成了 SELECT ...LOCK IN SHARE MODE ，也就默认都加读锁