## QAPUBSUB的作用

QAPUBSUB是一种数据分发的方式，SUB订阅数据，PUB推送数据，EventMQ作为中间媒介。下面对比原始的For循环，和QAPUBSUB订阅模式的区别。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348625.jpg)

原始模式: 通过for循环遍历数据，一条条处理。

订阅模式: 订阅数据后，名为on_bar的回调函数就等数据过来，过来一条处理一条。

PYTHON

|   | 


优点：

1. 逻辑清晰

1. 单个策略执行速度快

缺点在多个策略同时运行时暴露：

1. API访问次数限制

1. 同时访问数据库压力大

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348327.jpg)

以中间的eventmq为媒介，生产者发布数据，消费者订阅接收数据。生产者发布一次，多个消费者接收数据，执行各自的逻辑。消费者加工数据后可以发布数据，供其他消费者使用，例如：图中消费者tick2Bar把tick数据变成bar数据后发布，策略订阅了bar数据，bar数据一来策略就运行起来了。

优点在多个策略同时运行时尤为明显：

1. 多策略，数据持久化，发单等等写起来都很方便，只要订阅就好

缺点在多个策略同时运行时暴露：

1. 逻辑上需要理解下

1. 单策略执行速度不如for循环，毕竟有eventmq的开销

总的来说优点远大于缺点，用过的都说好。

PYTHON

|   | 


![](images/WEBRESOURCE1cb2d54892815d3b0263935d5f4efbe9截图.png)

PYTHON

|   | 


PYTHON

|   | 


PYTHON

|   | 


[https://github.com/yutiansut/QAPUBSUB](https://github.com/yutiansut/QAPUBSUB)