kafka脑裂问题研究

### 概述

脑裂是分布式系统高可用（High Avaliablity）场景下容易出现的问题。我们知道，在分布式系统中常常用多副本来解决容错性问题，多副本中会选举出一个Leader负责与客户端进行交互，但由于各种原因分布式集群中会出现脑裂的情况。

kafka controller相当于整个kafka集群的master，负责topic的创建、删除、以及partition的状态机转换，broker的上线、下线等。那么controller是如何选举出来的呢？它是通过抢占方式在zookeeper上注册临时节点来实现的，第一个注册成功的即为controller。关键之处来了，由于zookeeper临时节点的有效性是通过session来判断的，若在session timeout时间内，controller所在的broker断掉，则会触发新的controller选举。

但有时由于网络问题，可能同时有两个broker认为自己是controller，这时候其他的broker就会发生脑裂，不知道该听从谁的。

[https://www.jianshu.com/p/072380e12657](https://www.jianshu.com/p/072380e12657)

[http://www.pomit.cn/tr/5331301090265601](http://www.pomit.cn/tr/5331301090265601)

[https://blog.csdn.net/devcloud/article/details/125678163](https://blog.csdn.net/devcloud/article/details/125678163)