![](https://gitee.com/hxc8/images5/raw/master/img/202407172347603.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347584.jpg)

1、docker exec -it docker-qamarketcollector-1 /bin/sh

qarandom和QARC_Random功能介绍如下图：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347660.jpg)

2、qarandom --code J2001 --price 2500 --tradingday 20190918 --mu 0 --sigma 0.2 --theta 0.15 --dt 1e-2

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347780.jpg)

3、继续在此docker里面执行下面命令

QARC_Random --code rb1910 --date 20190619 --price 3800 --interval 1

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347254.jpg)

4、QARC_CTP --code rb1910

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347110.jpg)

上图 也就是假行情的主动推送

有了假行情，也就可以对假行情进行二次采样了

进入qaweb的docker，注意必须得加--host。因为默认是localhost。

docker exec -it qaweb /bin/sh 

假行情的分钟线如下：

qaps_sub --exchange realtime_min_rb1910 --model fanout --host 172.19.3.5

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347219.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347105.jpg)

然后可以继续resample数据。比如5分钟

![](images/WEBRESOURCEf47ce5f34c9bdc98e3747d59c104b86d截图.png)

上图如果不行，就用命令执行：

QARC_Resample --code rb1910 --freq 5min 

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347909.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347225.jpg)