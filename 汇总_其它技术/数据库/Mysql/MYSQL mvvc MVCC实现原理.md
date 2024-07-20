MVCC(Multi Version Concurrency Control的简称)，代表多版本并发控制。与MVCC相对的，是基于锁的并发控制，Lock-Based Concurrency Control)。

MVCC最大的优势：读不加锁，读写不冲突。在读多写少的OLTP应用中，读写不冲突是非常重要的，极大的增加了系统的并发性能



了解MVCC前，我们先学习下Mysql架构和数据库事务隔离级别



MYSQL 架构

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056648.jpg)

MySQL从概念上可以分为四层，顶层是接入层，不同语言的客户端通过mysql的协议与mysql服务器进行连接通信，接入层进行权限验证、连接池管理、线程管理等。下面是mysql服务层，包括sql解析器、sql优化器、数据缓冲、缓存等。再下面是mysql中的存储引擎层，mysql中存储引擎是基于表的。最后是系统文件层，保存数据、索引、日志等。



事务隔离级别

大家都知道数据库事务具备ACID特性，即Atomicity(原子性) Consistency(一致性), Isolation(隔离性), Durability(持久性)



原子性：要执行的事务是一个独立的操作单元，要么全部执行，要么全部不执行



一致性：事务的一致性是指事务的执行不能破坏数据库的一致性，一致性也称为完整性。一个事务在执行后，数据库必须从一个一致性状态转变为另一个一致性状态。



隔离性：多个事务并发执行时，一个事务的执行不应影响其他事务的执行，SQL92规范中对隔离性定义了不同的隔离级别：



读未提交(READ UNCOMMITED)->读已提交(READ COMMITTED)->可重复读(REPEATABLE READ)->序列化(SERIALIZABLE)。隔离级别依次增强，但是导致的问题是并发能力的减弱。





![](https://gitee.com/hxc8/images8/raw/master/img/202407191056191.jpg)



大多数数据库系统的默认隔离级别都是READ COMMITTED（但MySQL不是)，InnoDB存储引擎默认隔离级别REPEATABLE READ，通过多版本并发控制（MVCC，Multiversion Concurrency Control）解决了幻读的问题。



MYSQL 事务日志

事务日志可以帮助提高事务的效率。使用事务日志，存储引擎在修改表的数据时只需要修改其内存拷贝，再把该修改行为记录到持久在硬盘上的事务日志中，而不用每次都将修改的数据本身持久到磁盘。事务日志采用的是追加的方式，因此写日志的操作是磁盘上一小块区域内的顺序I/O，而不像随机I/O需要在磁盘的多个地方移动磁头，所以采用事务日志的方式相对来说要快得多。事务日志持久以后，内存中被修改的数据在后台可以慢慢地刷回到磁盘。目前大多数存储引擎都是这样实现的，我们通常称之为预写式日志（Write-Ahead Logging），修改数据需要写两次磁盘。

如果数据的修改已经记录到事务日志并持久化，但数据本身还没有写回磁盘，此时系统崩溃，存储引擎在重启时能够自动恢复这部分修改的数据。



MySQL Innodb中跟数据持久性、一致性有关的日志，有以下几种：



Bin Log:是mysql服务层产生的日志，常用来进行数据恢复、数据库复制，常见的mysql主从架构，就是采用slave同步master的binlog实现的

Redo Log:记录了数据操作在物理层面的修改，mysql中使用了大量缓存，修改操作时会直接修改内存，而不是立刻修改磁盘，事务进行中时会不断的产生redo log，在事务提交时进行一次flush操作，保存到磁盘中。当数据库或主机失效重启时，会根据redo log进行数据的恢复，如果redo log中有事务提交，则进行事务提交修改数据。

Undo Log: 除了记录redo log外，当进行数据修改时还会记录undo log，undo log用于数据的撤回操作，它记录了修改的反向操作，比如，插入对应删除，修改对应修改为原来的数据，通过undo log可以实现事务回滚，并且可以根据undo log回溯到某个特定的版本的数据，实现MVCC

MVCC实现

MVCC是通过在每行记录后面保存两个隐藏的列来实现的。这两个列，一个保存了行的创建时间，一个保存行的过期时间（或删除时间）。当然存储的并不是实际的时间值，而是系统版本号（system version number)。每开始一个新的事务，系统版本号都会自动递增。事务开始时刻的系统版本号会作为事务的版本号，用来和查询到的每行记录的版本号进行比较。

下面看一下在REPEATABLE READ隔离级别下，MVCC具体是如何操作的。



SELECT



InnoDB会根据以下两个条件检查每行记录：



InnoDB只查找版本早于当前事务版本的数据行（也就是，行的系统版本号小于或等于事务的系统版本号），这样可以确保事务读取的行，要么是在事务开始前已经存在的，要么是事务自身插入或者修改过的。

行的删除版本要么未定义，要么大于当前事务版本号。这可以确保事务读取到的行，在事务开始之前未被删除。

只有符合上述两个条件的记录，才能返回作为查询结果



INSERT



InnoDB为新插入的每一行保存当前系统版本号作为行版本号。



DELETE



InnoDB为删除的每一行保存当前系统版本号作为行删除标识。



UPDATE



InnoDB为插入一行新记录，保存当前系统版本号作为行版本号，同时保存当前系统版本号到原来的行作为行删除标识。

保存这两个额外系统版本号，使大多数读操作都可以不用加锁。这样设计使得读数据操作很简单，性能很好，并且也能保证只会读取到符合标准的行，不足之处是每行记录都需要额外的存储空间，需要做更多的行检查工作，以及一些额外的维护工作



举例说明

```javascript
 create table mvcctest( 
id int primary key auto_increment, 
name varchar(20));
transaction 1:

start transaction;
insert into mvcctest values(NULL,'mi');
insert into mvcctest values(NULL,'kong');
commit;
```

假设系统初始事务ID为1；





![](https://gitee.com/hxc8/images8/raw/master/img/202407191056677.jpg)

transaction 2:



```javascript
start transaction;
select * from mvcctest;  //(1)
select * from mvcctest;  //(2)
commit
```

SELECT

假设当执行事务2的过程中，准备执行语句(2)时，开始执行事务3：



transaction 3:

```javascript

start transaction;
insert into mvcctest values(NULL,'qu');
commit;
```



![](https://gitee.com/hxc8/images8/raw/master/img/202407191056829.jpg)



事务3执行完毕，开始执行事务2 语句2，由于事务2只能查询创建时间小于等于2的，所以事务3新增的记录在事务2中是查不出来的，这就通过乐观锁的方式避免了幻读的产生



UPDATE

假设当执行事务2的过程中，准备执行语句(2)时，开始执行事务4：



transaction session 4:



```javascript
start transaction;
update mvcctest set name = 'fan' where id = 2;
commit;
```

InnoDB执行UPDATE，实际上是新插入了一行记录，并保存其创建时间为当前事务的ID，同时保存当前事务ID到要UPDATE的行的删除时间





![](https://gitee.com/hxc8/images8/raw/master/img/202407191056807.jpg)



事务4执行完毕，开始执行事务2 语句2，由于事务2只能查询创建时间小于等于2的，所以事务修改的记录在事务2中是查不出来的，这样就保证了事务在两次读取时读取到的数据的状态是一致的



DELETE

假设当执行事务2的过程中，准备执行语句(2)时，开始执行事务5：



transaction session 5:



```javascript
start transaction;
delete from mvcctest where id = 2;
commit;
```



![](https://gitee.com/hxc8/images8/raw/master/img/202407191056846.jpg)



事务5执行完毕，开始执行事务2 语句2，由于事务2只能查询创建时间小于等于2、并且过期时间大于等于2，所以id=2的记录在事务2 语句2中，也是可以查出来的,这样就保证了事务在两次读取时读取到的数据的状态是一致的



参考：

《高性能MySQL》

InnoDB存储引擎MVCC实现原理

我理解的MVCC内部实现原理

【mysql】关于innodb中MVCC的一些理解

轻松理解MYSQL MVCC 实现机制



来自 https://www.jianshu.com/p/f692d4f8a53e