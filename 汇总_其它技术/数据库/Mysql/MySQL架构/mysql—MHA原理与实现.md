官方介绍：https://code.google.com/p/mysql-master-ha/



---

MySQL复制集群中的master故障时，MHA按如下步骤进行故障转移：

                  

![](https://gitee.com/hxc8/images7/raw/master/img/202407190810953.jpg)

从上图可总结MHA工作步骤为：

-从宕机崩溃的master保存二进制日志事件(binlogevents)。

-识别含有最新更新的slave。

-应用差异的中继日志(relay log)到其它slave。

-应用从master保存的二进制日志事件(binlogevents)。

-提升一个slave为新master。

-使其它的slave连接新的master进行复制。



---



---

MHA工作原理总结为如下：

 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190810222.jpg)

                                 （ 图01 ）

（1）从宕机崩溃的master保存二进制日志事件（binlog events）;

（2）识别含有最新更新的slave；

（3）应用差异的中继日志（relay log）到其他的slave；

（4）应用从master保存的二进制日志事件（binlog events）；

（5）提升一个slave为新的master；

（6）使其他的slave连接新的master进行复制；