![](https://gitee.com/hxc8/images5/raw/master/img/202407172340108.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340781.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340116.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340947.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340354.jpg)

QARUN

简单的mapreduce实现。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340140.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340835.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340146.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340159.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340485.jpg)

进入上图的docker

qarandom --code J2001 --price 2500 --tradingday 20190918 --mu 0 --sigma 0.2 --theta 0.15 --dt 1e-2

然后进入 8888端口的jupyter[http://localhost:8888/lab?](http://localhost:8888/lab?)

打开终端

pip install quantaxis-randomprice

然后就可以在notebook里面测试了

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340463.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340193.jpg)

可以不断修改参数的值，模拟各种情况。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340469.jpg)

再改

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340386.jpg)

再改

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340800.jpg)

再改

![](https://gitee.com/hxc8/images5/raw/master/img/202407172340512.jpg)

再改

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341372.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341895.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341153.jpg)

假行情的作用就是测试策略鲁棒性

[https://baike.baidu.com/item/%E9%B2%81%E6%A3%92%E6%80%A7/832302?fr=aladdin](https://baike.baidu.com/item/%E9%B2%81%E6%A3%92%E6%80%A7/832302?fr=aladdin)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341120.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341392.jpg)

QARC_Random --code rb1910 --date 20190619 --price 3800 --interval 1

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341751.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341645.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341321.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341910.jpg)

random测试

QARC_Random --code rb1910 --date 20190619 --price 3800 --interval 1

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341366.jpg)

QARC_CTP --code rb1910

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341677.jpg)

上图 也就是假行情的主动推送

有了假行情，也就可以对假行情进行二次采样了

qaps_sub --exchange realtime_min_rb1910 --model fanout --host 172.19.3.5

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341258.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341861.jpg)

之前QA是给过一个破解版的QATTS，但是已经不行了。

其他非正规的：

1、 tradex，一直说要凉也没有凉

2、只听到发音，不知道具体名字，也没收到。读音是  瑞通 的发音

正规的大概有3个PB系统

1、中泰XTP

2、恒生UFX

3、迅投QMT

PS:

PB的全称是证券公司PB业务，是指证券公司为专业机构投资者和集中托管等[高净值客户](http://www.yjcf360.com/licaijj/712096.htm)提供的一站式综合金融服务清算、后台运营、研究支持、杠杆融资、证券借贷、集资服务。**PB系统**的定位是托管-清算-交易。目前的PB系统主要放在交易环节。对于一般散户的交易系统，是比较先进的交易系统。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341993.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341212.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172341010.jpg)