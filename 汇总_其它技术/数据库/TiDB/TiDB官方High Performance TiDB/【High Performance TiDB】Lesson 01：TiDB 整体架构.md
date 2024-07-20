

![](https://gitee.com/hxc8/images7/raw/master/img/202407190808466.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190808972.jpg)



使用RAFT维持数据的多个副本之间的强一致性

在两阶段提交的帮助下，TIKV可以提供完整的分布式事务语义



PD的主要功能包括：为TIDB的事务分配单调递增的时间戳和管理整个集群的数据分布并作出合理的调度策略



  

![](https://gitee.com/hxc8/images7/raw/master/img/202407190808297.jpg)



逻辑

![](https://gitee.com/hxc8/images7/raw/master/img/202407190808619.jpg)



物理

![](https://gitee.com/hxc8/images7/raw/master/img/202407190808185.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190808419.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190808642.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190808069.jpg)



除此以外TIKV还会基于负载触发region的分裂，以应对热点问题







分布式事务

![](https://gitee.com/hxc8/images7/raw/master/img/202407190808409.jpg)



 



![](https://gitee.com/hxc8/images7/raw/master/img/202407190809064.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190809691.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190809160.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190809751.jpg)

以上图SQL为例，分析TiDB server 是如何同TIKV交互完成SQL语句的执行的。

首先解析后的SQL语句形成初始的执行计划，随后TiDB会基于大量规则对执行计划进行必要的改写，获得一个逻辑上等价，但执行效率更优的执行计划。接下来TiDB会利用数据的物理属性，以执行代价为优化目标，对执行计划进行进一步的改写，形成最终的物理执行计划。途中所示的Query plan 就是经过所有优化后所得到的最终物理执行计划，在第一次SQL执行引擎执行左图所示的执行计划时，执行引擎根据数据所在region对scan节点的任务进行切分，同时将filter位置下推，连同scan一同发送到tikv执行，从而过滤不满足查询条件的数据，降低不必要的数据传输开销。从tikv读取的数据在经过HashJoin和Projection两个算子后就完成了整个query的处理流程。



