之前讲过有关分布式事务2PC、3PC、TCC的理论知识,博客地址：

1、分布式事务(1)---2PC和3PC原理

2、分布式事务(2）---TCC原理

这篇讲有关RocketMQ实现分布式事务的理论知识，下篇也会示例 通过SpringCloud来实例RocketMQ实现分布式事务的项目。

一、举个分布式事务场景

列子：假设 A 给 B 转 100块钱，同时它们不是同一个服务上。

目标：就是 A 减100块钱，B 加100块钱。

实际情况可能有四种：

1）就是A账户减100 （成功），B账户加100 （成功）

2）就是A账户减100（失败），B账户加100 （失败）

3）就是A账户减100（成功），B账户加100 （失败）

4）就是A账户减100 （失败），B账户加100 （成功）


这里 第1和第2 种情况是能够保证事务的一致性的，但是 第3和第4 是无法保证事务的一致性的。

那我们来看下RocketMQ是如何来保证事务的一致性的。



二、RocketMQ实现分布式事务原理

RocketMQ虽然之前也支持分布式事务，但并没有开源，等到RocketMQ 4.3才正式开源。

1、基础概念

最终一致性

RocketMQ是一种最终一致性的分布式事务，就是说它保证的是消息最终一致性，而不是像2PC、3PC、TCC那样强一致分布式事务，至于为什么说它是最终一致性事务下面会详细说明。

Half Message(半消息)

是指暂不能被Consumer消费的消息。Producer 已经把消息成功发送到了 Broker 端，但此消息被标记为暂不能投递状态，处于该种状态下的消息称为半消息。需要 Producer

对消息的二次确认后，Consumer才能去消费它。

消息回查

由于网络闪段，生产者应用重启等原因。导致 Producer 端一直没有对 Half Message(半消息) 进行 二次确认。这是Brock服务器会定时扫描长期处于半消息的消息，会

主动询问 Producer端 该消息的最终状态(Commit或者Rollback),该消息即为 消息回查。

2、分布式事务交互流程

理解这张阿里官方的图，就能理解RocketMQ分布式事务的原理了。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190027426.jpg)

我们来说明下上面这张图

1、A服务先发送个Half Message给Brock端，消息中携带 B服务 即将要+100元的信息。

2、当A服务知道Half Message发送成功后，那么开始第3步执行本地事务。

3、执行本地事务(会有三种情况1、执行成功。2、执行失败。3、网络等原因导致没有响应)

4.1)、如果本地事务成功，那么Product像Brock服务器发送Commit,这样B服务就可以消费该message。

4.2)、如果本地事务失败，那么Product像Brock服务器发送Rollback,那么就会直接删除上面这条半消息。

4.3)、如果因为网络等原因迟迟没有返回失败还是成功，那么会执行RocketMQ的回调接口,来进行事务的回查。


从上面流程可以得知 只有A服务本地事务执行成功 ，B服务才能消费该message。

然后我们再来思考几个问题？

为什么要先发送Half Message(半消息)

我觉得主要有两点

1）可以先确认 Brock服务器是否正常 ，如果半消息都发送失败了 那说明Brock挂了。

2）可以通过半消息来回查事务，如果半消息发送成功后一直没有被二次确认，那么就会回查事务状态。


什么情况会回查

也会有两种情况

1）执行本地事务的时候，由于突然网络等原因一直没有返回执行事务的结果(commit或者rollback)导致最终返回UNKNOW，那么就会回查。

2) 本地事务执行成功后，返回Commit进行消息二次确认的时候的服务挂了，在重启服务那么这个时候在brock端
   它还是个Half Message(半消息)，这也会回查。


特别注意: 如果回查，那么一定要先查看当前事务的执行情况，再看是否需要重新执行本地事务。

想象下如果出现第二种情况而引起的回查，如果不先查看当前事务的执行情况，而是直接执行事务，那么就相当于成功执行了两个本地事务。

为什么说MQ是最终一致性事务

通过上面这幅图，我们可以看出，在上面举例事务不一致的两种情况中，永远不会发生

A账户减100 （失败），B账户加100 （成功）


因为：如果A服务本地事务都失败了，那B服务永远不会执行任何操作，因为消息压根就不会传到B服务。

那么 A账户减100 （成功），B账户加100 （失败） 会不会可能存在的。

答案是会的

因为A服务只负责当我消息执行成功了，保证消息能够送达到B,至于B服务接到消息后最终执行结果A并不管。

那B服务失败怎么办？

如果B最终执行失败，几乎可以断定就是代码有问题所以才引起的异常，因为消费端RocketMQ有重试机制，如果不是代码问题一般重试几次就能成功。

如果是代码的原因引起多次重试失败后，也没有关系，将该异常记录下来，由人工处理，人工兜底处理后，就可以让事务达到最终的一致性。