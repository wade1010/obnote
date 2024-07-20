![](https://gitee.com/hxc8/images0/raw/master/img/202407172059133.jpg)

一台主机之间会有一个CPU，进行一个主体的驱动，那一款CPU下面为了提升我们的计算性能，就会在下面挂很多块GPU，这些GPU都是以插槽的方式，也就是pcie Switch的pcie插槽，插到主板上面，那一块主板最多可以插八块CPU，这个时候呢叫做多个CPU之间是通过pcie Switch 进行一个互联的，但是在nvlink之前出现的时候，pcie3.0X16,整体的双向带宽并不高，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059398.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059618.jpg)

CPU左边有GPU0，右边有GPU1，GPU跟GPU之前，以前没有nvlink，需要通过pcie，也就是这里面会单独提供一个pcie的IO然后进行互联，而既然pcie跟pcie进行互联，中间就需要经过CPU进行一个数据的分发调度，pcie带宽的速率就会制约我们GPU跟GPU互联的速率了。

于是英伟达就提出了nvlink，这里面第一代保留了pcie的io，另外还提供了下面绿色的几个框框。GPC之间（也就是计算核心）可以访问卡间的HBM带宽，通过多条的nvlink(那nvlink它有多条链路嘛)对GPU跟GPU之间的HBM进行互联，

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059033.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059312.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059897.jpg)

nvlink是的CPU可以挂在更多的GPU，单个G PU的驱动进程就可以控制非常多的gpu，进行同时的计算。

另外一点就是HBM显存不受其它进行的干扰，可以随便的去访问我的LT（LD/ST）

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059850.jpg)

### 2、nvlink结构

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059047.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059328.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059568.jpg)

很难的，需要提供很多物理特性。

PHY就是物理层

DL就是data link数据的链路层

TL就是交易层

NCCL实际上封装了这里面nvlink底层的这些能力。

![](https://gitee.com/hxc8/images0/raw/master/img/202407172059672.jpg)

 

![](D:/download/youdaonote-pull-master/data/Technology/人工智能/ZOMI/20【AI芯片】GPU详解/images/WEBRESOURCE2880c081dcb897032aff363f98b9c574image.png)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100466.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100744.jpg)

### 3 nvlink 拓扑

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100173.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100372.jpg)

 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100926.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100323.jpg)

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100831.jpg)

 

![](https://gitee.com/hxc8/images0/raw/master/img/202407172100216.jpg)