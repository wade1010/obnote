https://www.cnblogs.com/yousheng/p/12944218.html





摘要

本文旨在了解MySQL InnoDB引擎如何支持事务的隔离级别。

文章主要内容分两个部分。

第一部分阐述数据库的并发问题以及为之产生的ANSI SQL 标准隔离级别。

第二部分根据 MySQL 官方文档解释 InnoDB 是如何支持这些隔离级别的。

数据库事务的并发问题

ANSI SQL 隔离级别的定义是来源于三个异象问题，ANSI SQL在权衡系统的可靠性和性能之间定义了不同的级别。所以这里先介绍主流的三个并发问题是什么。

读异象 (read phenomena)

- 脏读 (dirty read)

- 一个事务读取到了另一个事务更新但未提交的数据。当另一个事务回滚或者再次修改，就会读取到脏数据

- 不可重复读 (non-repeatable read / Fuzzy Read)

- 现象为一个事务多次同样读取数据的操作其结果不一致。例如读取时有其他事务提交了修改

- 幻读 (phantom)

- 在多次同样的查询操作下，后面的查询出现了新行的数据。

- 例如在执行多次查询的时候，其他事务插入了一个新行或者修改了某行数据使得能匹配上Where条件，那么后一次查询必然将查询到这个新数据（也就感觉出现了幻觉，莫名其妙多出了一行）

- 不可重复读和幻读其实类似，但是幻读偏向于查询新增数据（所以专门弄了间隙锁来防止幻读），不可重复读则是修改数据。

Def: The so-called phantom problem occurs within a transaction when the same query produces different sets of rows at different times. For example, if a SELECT is executed twice, but returns a row the second time that was not returned the first time, the row is a “phantom” row.

- 注：幻象 (Phantom) ，幻读 (Phantom Read) ，幻象问题 (Phantom Problem) 都是一个表述。

- 总结：上面介绍的三个读并发问题，其本质都是一个事务在读其他事务修改过的数据。

---

标准事务隔离级别

- 因为事务在并发执行的过程中会存在相互干扰，需要有隔离性的保障，故引入事务隔离级别规范，以平衡操作的性能、可靠和一致

ANSI SQL下规定的隔离级别（1992 - 很老的标准了）

1. 未提交读 - Read Uncommited

- 风险挺高，但是如果只是存粹的读操作可以推荐使用（MyISAM也挺香呀）

1. 已提交读 - Read Commited(互联网主流默认使用的隔离级别)

- 事务无法看见其他未提交事务的修改

1. 可重复读 - Repeatable Read (MySQL 默认)

- 只读事务开始时的快照数据

1. 可序列化 - Serializable

- ANSI SQL规定的隔离级别其实还是根据主流存在的数据库并发问题来定义的，每个隔离级别来解决不同的问题。

如果细分继续细分不同的并发问题，隔离级别还能更加细化。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190812126.jpg)

---

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

- E.g SELECT ... FOR UPDATE | SELECT ... FOR SHARE

- 行锁 (record lock) : 即锁定索引记录的锁，即使没有索引也会找到对应行记录锁主键哦

- 间隙锁 (gap lock) : 锁在了两条索引记录之间的锁，或者(无穷小,某索引)/(某索引,无穷大)，他们锁住的是一个范围，且不同的间隙锁不互斥，他们排斥的只是在锁范围内的插入操作

- mark: R.C 隔离级别下是被禁用的

- next-key lock : 行锁和间隙锁的组合实现

---

未提交读 - Read Uncommited

- SELECT 语句采用的无锁策略，也就更容易产生脏读

---

已提交读 - Read Commited

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

- InnoDB 默默的把所有纯 SELECT 语句都转成了 SELECT ... FOR SHARE ，也就默认都加读锁

---

小结

- InnoDB 是依赖于不同的锁策略实现了不同隔离级别的要求

- R.C 隔离级别使用了当前读 (R.C 下的一致性读取) 且 禁用了间隙锁

- 理论上 R.R 隔离级别下因为使用了一致性读和间隙锁是不会产生的幻读问题的，所以标准可能有点老了

- todo MySQL在介绍幻读的时候说 R.R 下面会有幻读，但又没有实际的例子，很难让人信服。（除非这个也算：两次查询前一次是普通读，后一次是锁定读）

---

扩展

- 在介绍 ANSI 隔离级别标准的时候，提到了ANSI标准是根据主流的三个并发问题来对症下药的，但其实仍然有一些并发问题被 ANSI 标准给默认忽略或者说就不关心了，比如典型的脏读和丢失修改。更详细的可以参考下图(1995)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190812656.jpg)

