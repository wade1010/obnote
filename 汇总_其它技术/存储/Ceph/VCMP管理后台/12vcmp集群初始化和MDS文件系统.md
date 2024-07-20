先添加下图中红色框的内容

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354748.jpg)

添加后，再切换回 “集群操作”，会出现一个初始化按钮

初始化（大概耗时30秒左右）完成后，OSD或者MDS等状态可能是异常的，但是到服务器上执行ceph -s是正常的，这个时候可以点击“同步集群数据”按钮

创建后，MSD的的 "主/备" 一直都是备，如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354595.jpg)

这里需要创建存储池和文件系统

创建两个存储池，如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354510.jpg)

创建文件系统，如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354737.jpg)

然后也可以到服务器看下

df -h

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354439.jpg)