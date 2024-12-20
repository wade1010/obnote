上篇讲过有关2PC和3PC理论知识，博客：分布式事务(1)---2PC和3PC理论

我的理解：2PC、3PC还有TCC都蛮相似的。3PC大致是把2PC的第一阶段拆分成了两个阶段，而TCC我感觉是把2PC的第二阶段拆分成了两个阶段。

一、概念

1、概念

TCC又称补偿事务。其核心思想是："针对每个操作都要注册一个与其对应的确认和补偿（撤销操作）"。它分为三个操作：

1、Try阶段：主要是对业务系统做检测及资源预留。
2、Confirm阶段：确认执行业务操作。
3、Cancel阶段：取消执行业务操作。


TCC对应 Try、Confirm、Cancel 三种操作可以理解成关系型数据库事务的三种操作：DML、Commit、Rollback。

在一个跨应用的业务操作中

Try：Try操作是先把多个应用中的业务资源预留和锁定住，为后续的确认打下基础，类似的，DML操作要锁定数据库记录行，持有数据库资源。

Confirm：Confirm操作是在Try操作中涉及的所有应用均成功之后进行确认，使用预留的业务资源，和Commit类似；

Cancel：Cancel则是当Try操作中涉及的所有应用没有全部成功，需要将已成功的应用进行取消(即Rollback回滚)。其中Confirm和Cancel操作是一对反向业务操作。

TCC的具体原理图如（盗图）:

![](https://gitee.com/hxc8/images7/raw/master/img/202407190027956.jpg)

从图中我们可以明显看到Confirm和Cancel操作是一对反向业务操作 即要try返回成功执行Confirm,要么try返回失败执行Cancel操作。

分布式事务协调者：分布式事务协调者管理控制整个业务活动，包括记录维护TCC全局事务的事务状态和每个从业务服务的子事务状态，并在业务活动提交时确认所有的TCC型

操作的confirm操作，在业务活动取消时调用所有TCC型操作的cancel操作。

2、举例

例子：A服务转30块钱、B服务转50块钱，一起到C服务上。

Try：尝试执行业务。完成所有业务检查(一致性)：检查A、B、C的帐户状态是否正常，帐户A的余额是否不少于30元，帐户B的余额是否不少于50元。预留必须业务资源

(准隔离性)：帐户A的冻结金额增加30元，帐户B的冻结金额增加50元，这样就保证不会出现其他并发进程扣减了这两个帐户的余额而导致在后续的真正转帐操作过程中，

帐户A和B的可用余额不够的情况。

Confirm：确认执行业务。真正执行业务：如果Try阶段帐户A、B、C状态正常，且帐户A、B余额够用，则执行帐户A给账户C转账30元、帐户B给账户C转账50元的转帐

操作。 这时已经不需要做任何业务检查，Try阶段已经完成了业务检查。只使用Try阶段预留的业务资源：只需要使用Try阶段帐户A和帐户B冻结的金额即可。

Cancel：取消执行业务释放Try阶段预留的业务资源：如果Try阶段部分成功，比如帐户A的余额够用，且冻结相应金额成功，帐户B的余额不够而冻结失败，则需要

对帐户A做Cancel操作，将帐户A被冻结的金额解冻掉。

3、TCC和2PC比较

2PC是资源层面的分布式事务，强一致性，在两阶段提交的整个过程中，一直会持有资源的锁。

XA事务中的两阶段提交内部过程是对开发者屏蔽的，事务管理器在两阶段提交过程中，从prepare到commit/rollback过程中，资源实际上一直都是被加锁的。

如果有其他人需要更新这两条记录，那么就必须等待锁释放。

TCC是业务层面的分布式事务，最终一致性，不会一直持有资源的锁。

我的理解就是当执行try接口的时候，已经把所需的资源给预扣了，比如上面举例的A服务已经预扣30元，B服务已经预扣50元，它是由try接口实现，这样就保证不会

出现其他并发进程扣减了这两个帐户的余额而导致在后续的真正转帐操作过程中，帐户A和B的可用余额不够的情况，同时保证不会一直锁住整个资源。（核心点应该就在这）

TCC中的两阶段提交并没有对开发者完全屏蔽，也就是说从代码层面，开发者是可以感受到两阶段提交的存在。

1、try过程的本地事务，是保证资源预留的业务逻辑的正确性。

2、confirm/cancel执行的本地事务逻辑确认/取消预留资源，以保证最终一致性，也就是所谓的补偿型事务。

由于是多个独立的本地事务，因此不会对资源一直加锁。

总的来讲

TCC 实质上是应用层的2PC()，好比把 XA 两阶段提交那种在数据资源层做的事务管理工作提到了数据应用层。

2PC是资源层面的分布式事务，是强一致性，在两阶段提交的整个过程中，*一直会持有资源的锁*。

TCC是业务层面的分布式事务，最终一致性，*不会一直持有资源的锁*。

TCC相比较于2PC来讲性能会好很多，但是因为同时需要改造try、confirm、canel3个接口，开发成本高。


注意 还有一点需要注意的是Confirm和Cancel操作可能被重复调用，故要求Confirm和Cancel两个接口必须是幂等。



参考

分布式事务（一）原理概览

分布式事务之说说TCC事务

分布式事务：深入理解什么是2PC、3PC及TCC协议

柔性事务 ：TCC两阶段补偿型