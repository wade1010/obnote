### 事务的4个特性:ACID
原子性(Atomicity）、一致性（Consistency）、隔离性（Isolation）、持久性（Durability）

原子性: 是指某几句sql的影响,要么都发生,要么都不发生.
      即:张三减300, 李四+300 , insert银行流水, 这3个操作,必须都完成,或都不产生效果.

一致性: 事务前后的数据,保持业务上的合理一致.
     (汇款前)张三的余额+李四的余额  ====== (汇款后) 张三的余额+李四余额
     比如: 张三只有280元, 280-300=-20,储蓄卡不是信用卡,不能为负,因此张三余0元.
     将导致, 汇款后,2者余额,汇款前,差了20元.

隔离性: 在事务进行过程中, 其他事务,看不到此事务的任何效果.
持久性: 事务一旦发生,不能取消. 只能通过补偿性事务,来抵消效果.



### 事务的使用流程:
比较简单:  

开启事务   start transaction

执行查询   xxxx

提交事务/回滚事务. commit / rollback

> set session transaction isolation level [read uncommitted |  read committed | repeatable read |serializable]

read uncommitted:  读未提交的事务内容,显然不符原子性, 称为”脏读”. 在业务中,没人这么用.

read commited:   在一个事务进行过程中, 读不到另一个进行事务的操作,但是,可以读到另一个结束事务的操作影响.

repeatable read: 可重复读,即在一个事务过程中,所有信息都来自事务开始那一瞬间的信息,不受其他已提交事务的影响. (大多数的系统,用此隔离级别)

serializeable 串行化  , 所有的事务,必须编号,按顺序一个一个来执行,也就取消了冲突的可能.这样隔离级别最高,但事务相互等待的等待长. 在实用,也不是很多.


#### 设置事务的级别:

set session transaction isolation level read uncommitted;