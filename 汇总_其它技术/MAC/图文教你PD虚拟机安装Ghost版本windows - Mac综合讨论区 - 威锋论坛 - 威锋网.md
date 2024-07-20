本帖最后由 army0210 于 2016-8-26 00:13 编辑



相信很多封釉遇到过parallels desktop安装不了ghost版本的win，其实遇到这样的问题无非是各种原因没有原版的ISO或者不想要原版的win。那么问题来了，此类安装教程这么多，但是小白还是看不懂或者还是装不上

那么问题来了，就发个处女帖吧！

准备工具：parallels desktop 10.1.1

                  Mac or iMac－－－－ whatever。。。

                  ghost win.iso－－－－无论什么版本





1：启动PD, 新建虚拟机

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749857.jpg)





2:选“手动查找”

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749401.jpg)





3:把ghostXXX.iso的win映像拖入



![](https://gitee.com/hxc8/images7/raw/master/img/202407190749727.jpg)





4:提示“提示无法检测。。。”不管它点“继续”



![](https://gitee.com/hxc8/images7/raw/master/img/202407190749987.jpg)





5:在弹出的窗口中选你的win版本，“确定”



![](https://gitee.com/hxc8/images7/raw/master/img/202407190749349.jpg)





6:选择你要应用的系统类型，“继续”



![](https://gitee.com/hxc8/images7/raw/master/img/202407190749597.jpg)



7:在“安装前设定”打勾，“继续”

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749795.jpg)





8:点“配置”

![](D:/download/youdaonote-pull-master/data/Technology/MAC/images/8A175A6FAE4B4CA2A030276658224544235858vgtk7h52hs7jfk7u.jpg.jpeg)





9:选“硬件”－－－“启动顺序”－－把“硬盘”前的勾去掉。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749278.jpg)





10:选左侧的“硬盘”－－－“编辑”

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749521.jpg)







11:移动滑块，调整分区大小，自己随意，我的16G,然后点“应用”



![](https://gitee.com/hxc8/images7/raw/master/img/202407190749645.jpg)







12:此时弹出警告，应该没问题，果断”继续“



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750147.jpg)







13:等它进度条完成就可以关闭它和配置窗口了



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750655.jpg)







14:回到虚拟机安装界面，”继续“



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750787.jpg)







15：是不是很眼熟？选“启动PE"



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750798.jpg)







16:进入到PE,使用分区工具

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750220.jpg)





17:分区工具不会用？



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750668.jpg)





18：分区格式ntfs／fat32都可以

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750779.jpg)





19:回到桌面开始安装／还原win



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750230.jpg)





20:ghost出来了

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750411.jpg)





21:win安装中



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750816.jpg)





22:等win安装完了就关机，关闭虚拟系统，注意不是中止，也不是关闭PD

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750284.jpg)





不然进配置是这样的：



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750623.jpg)





23:完全关闭虚拟系统后进“配置”把“启动顺序”的“硬盘1”打勾，关闭“配置”

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750917.jpg)







24:开机吧



![](https://gitee.com/hxc8/images7/raw/master/img/202407190750229.jpg)







附安装parallels tool

Resolution

1. Take a snapshot of the virtual machine to be able to revert the changes by going to virtual machine menu bar > Actions > Take Snapshot.

1. Download the attached registry fix in the virtual machine by opening this article in the Windows Internet browser.

1. Unzip the archive and run the fix.

1. Reboot virtual machine and install Parallels Tools.

1. Once Parallels Tools are successfully installed, delete the snapshot by going to virtual machine menu bar > Actions > Manage Snapshot.



















