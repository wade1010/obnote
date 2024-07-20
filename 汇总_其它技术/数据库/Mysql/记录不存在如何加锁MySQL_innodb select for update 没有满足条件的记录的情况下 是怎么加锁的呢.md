在REPEATABLE-READ隔离级别下，如果两个线程同时对同一张表用SELECT…FOR UPDATE加锁，在没有符合该条件记录情况下，两个线程都会加锁成功。程序发现记录尚不存在，就试图插入一条新记录，如果两个线程都这么做，就会出现死锁。这是什么原因呢？

//session_1

mysql> select @@tx_isolation;

+-----------------+

| @@tx_isolation |

+-----------------+

| REPEATABLE-READ |

+-----------------+

1 row in set (0.00 sec)

mysql> set autocommit = 0;

Query OK, 0 rows affected (0.00 sec)

//当前session对不存在的记录加for update的锁：

mysql> select actor_id,first_name,last_name from actor where actor_id = 201 for update;

Empty set (0.00 sec)

//session_2

//其他session也可以对不存在的记录加for update的锁：

mysql> select actor_id,first_name,last_name from actor where actor_id = 202 for update;

Empty set (0.00 sec)

//session_1

//因为其他session也对该记录加了锁，所以当前的插入会等待：

mysql> insert into actor (actor_id , first_name , last_name) values(201,'Lisa','Tom');

//等待

//session_2

//因为其他session已经对记录进行了更新，这时候再插入记录就会提示死锁并退出：

mysql> insert into actor (actor_id, first_name , last_name) values(202,'Lucas','Jack');

ERROR 1213 (40001): Deadlock found when trying to get lock; try restarting transaction

//session_1

//由于其他session已经退出，当前session可以获得锁并成功插入记录：

mysql> insert into actor (actor_id , first_name , last_name) values(201,'Lisa','Tom');

Query OK, 1 row affected (13.58 sec)