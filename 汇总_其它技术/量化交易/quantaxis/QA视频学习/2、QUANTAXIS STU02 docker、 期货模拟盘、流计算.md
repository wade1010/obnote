![](https://gitee.com/hxc8/images5/raw/master/img/202407172341571.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341185.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341564.jpg)

cd ~/.quantaxis/setting

cat config.ini

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341286.jpg)

改变这个地址

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341634.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341073.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341865.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341257.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341997.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341801.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341939.jpg)

[http://127.0.0.1:8020/order?action=sendorder&acc=1010101&price=3800&code=rb1910&&direction=BUY&offset=OPEN&volume=1&exchange=SHFE&type=sim](http://127.0.0.1:8020/order?action=sendorder&acc=1010101&price=3800&code=rb1910&&direction=BUY&offset=OPEN&volume=1&exchange=SHFE&type=sim)

返回内容

```
{
    "status": 200,
    "result": {
        "topic": "sendorder",
        "account_cookie": "1010101",
        "strategy_id": "test",
        "code": "rb1910",
        "price": 3800,
        "order_direction": "BUY",
        "order_offset": "OPEN",
        "volume": 1,
        "exchange_id": "SHFE"
    }
}
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342604.jpg)

CPTBEE打开后就会在RabbitMQ有一个exchange为CPTX,也就是打开了一个实时行情接口，

打开实时行情接口主要目的就是二次分发

qaps_sub --exchange CTPX --model direct --routing_key rb2001

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342536.jpg)

然后可以用 QUANTAXIS_RealtimeCollector实时采集分发

向RealtimeCollector订阅

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342247.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342340.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342218.jpg)

订阅了3个

也可以查看订阅的全部内容

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342354.jpg)

也可以从RabbitMQ看

![](images/WEBRESOURCE0fdfab68aa0e243ff10b4a26c6156a5a截图.png)

订阅 它干了什么事情呢？它会把CTP的tick变成一个realtime的bar  （如下图）

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342004.jpg)

这个bar也可以被别的订阅 （如下两张图）

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342807.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342414.jpg)

这上图是实时的1分钟数据，这时候想要5分钟或者10分钟，就可以用resample来进行采集

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342376.jpg)

查看  如下图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342451.jpg)

从MQ看 如下图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342055.jpg)

别的就可以订阅这个5分钟数据了

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342548.jpg)

上图是22:00:00的数据

下一个时间就是22:05:00 如下图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342770.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342739.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342971.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342521.jpg)

那怎么从paper trading切到sim呢？

只要把发单的部分(下1图)

改成向MQ发送消息(下2图)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342303.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342138.jpg)

上实盘就是把account改成实盘账户就行

如下图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342485.jpg)

看下群主的实盘截图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342297.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342622.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342716.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172342601.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343858.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343058.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343834.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343886.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343926.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343667.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343199.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343234.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343155.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343594.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343749.jpg)

![](images/WEBRESOURCE3be7385fd092b022ce4cf1a269b68cca截图.png)

订阅股票

![](images/WEBRESOURCE37f49a3be5a5c037d4ff60b615068dc3截图.png)

发完POST之后，可以看下MQ

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343458.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172343662.jpg)

然后别的需要的就可以订阅这个exchange了

这里用qapubsub

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344846.jpg)

上图由于已经收盘，所以每一条的信息都是一样的

如果再订阅一个000002

发送POST

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344325.jpg)

这个时候订阅者那边就能看到000001和000002，如下图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344277.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344912.jpg)

群主对于上面问题的回答

自动合成得自己写个worker，这部分没有开源，所以只能自己写

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344174.jpg)

后来群主给看了未开源的代码截图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344326.jpg)

另外也有别人在群里说的思路

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344430.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344124.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344818.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344418.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344550.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172344321.jpg)