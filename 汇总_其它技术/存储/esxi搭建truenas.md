[https://www.itsvse.com/thread-10223-1-1.html](https://www.itsvse.com/thread-10223-1-1.html)

NAS（Network Attached Storage：网络附属存储）按字面简单说就是连接在网络上，具备资料存储功能的装置，因此也称为“网络存储器”。它是一种专用数据存储服务器。

现市面上的比较热门的 NAS 产品有：

群晖、威联通、铁威马等，购买其硬件后，自带各厂家自己开发的 NAS 系统。如果不想购买其产品，也

可以用闲置的电脑安装 TrueNAS 搭建一台 NAS 服务。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007919.jpg)

> TrueNAS CORE（以前称为 FreeNAS）是世界上最受欢迎的存储操作系统，因为它使您能够构建自己的专业级存储系统，以用于各种数据密集型应用程序，而无需任何软件成本。只需将其安装到硬件或 VM 上，即可体验开源存储的真正存储自由。TrueNAS CORE 可用于从家庭到办公室再到数据中心的各种数据密集型用例。IT 专业人员、摄影师、设计师、音频/视频制作人和编辑、开发人员以及任何认真对待存储和保护大量数据的用户都可以利用 TrueNAS CORE。将它与您最喜欢的备份软件配对，将大量不常用的数据从本地设备存档，甚至将数据同步并推送到云端。TrueNAS 的核心是自我修复的OpenZFS文件系统。以前只能在最高端的企业存储系统上使用，TrueNAS 让您可以直接、用户友好地访问 ZFS。凭借其内置的 RAID、强大的数据管理工具以及自动检测和修复静默数据损坏（和位腐烂）的能力，TrueNAS 和 OpenZFS 可确保自始至终的数据完整性。TrueNAS 不仅仅是传统的“网络附加存储”，它还是一种统一存储，可无缝集成到具有各种文件、块或对象访问协议的任何环境中。您还可以使用各种免费插件扩展其功能，例如 Plex Media Server、NextCloud、Zoneminder 监控等。


TrueNA 文档：[https://www.truenas.com/docs/](https://www.truenas.com/docs/)

开源地址：[https://github.com/truenas](https://github.com/truenas)

安装教程：[https://www.truenas.com/docs/core/gettingstarted/install/](https://www.truenas.com/docs/core/gettingstarted/install/)

**下载 TrueNAS**

下载链接：

[https://www.truenas.com/download-truenas-core/](https://www.truenas.com/download-truenas-core/)

[https://download.freenas.org/12.0/STABLE/U7/x64/TrueNAS-12.0-U7.iso](https://download.freenas.org/12.0/STABLE/U7/x64/TrueNAS-12.0-U7.iso)

我使用

 DELL T340 和 ESXI 7.0 安装 TrueNAS 系统，关于如何在 DELL T340 上面安装 ESXI 可以参考如下：

> 【实战】DELL PowerEdge T340 安装 ESXI 7.0 虚拟化


我机器插入了 

**2块 2TB 的红盘**，并且通过 iDRAC 组成了 RAID1 阵列，供 TrueNAS 使用（TrueNAS 自带软 raid 功能），可以参考如下：

> DELL T340 服务器通过 iDRAC9 创建 RAID


将下载好的“

TrueNAS-12.0-U7.iso”镜像文件上传到 ESXI 存储中，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007284.jpg)

**开始安装 TrueNAS**

TrueNAS 最低要求

- 64 位 CPU

- 16 GB RAM 内存

- 16 GB 引导驱动器（支持 SSD）

- 至少一个附加磁盘

注意事项：

TrueNAS 应安装在 **BIOS 模式**下安装。

某些产品需要识别 VM 上安装的操作系统。理想的选择是**FreeBSD 12 64 位**。如果这不可用，请尝试FreeBSD 12、FreeBSD 64 位、64 位 OS或其他等选项。不要选择与 Windows 或 Linux 相关的操作系统类型。

使用 ESXI 新建虚拟机，客户机操作系统版本选择：FreeBSD 12（64 位），如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007608.jpg)

选择存储（

2块2TB组成的 RAID1 存储）

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007567.jpg)

根据自己情况设置 CPU、内存、磁盘大小等信息，我这里选择”厚置备，延迟置零“比较快！

**必须添加两块硬盘**，一块作为系统盘，一块作为文件存储盘！

> 一：精简置备（thin）：创建磁盘时，占用磁盘的空间大小根据实际使用量计算，即用多少分多少，提前不分配空间，对磁盘保留数据不置零，且最大不超过划分磁盘的大小。所以当有I/O操作时，需要先分配空间，在将空间置零，才能执行I/O操作。当有频繁I/O操作时，磁盘性能会有所下降。二：厚置备，延迟置零：默认的创建格式，创建磁盘时，直接从磁盘分配空间，但对磁盘保留数据不置零。所以当有I/O操作时，只需要做置零的操作。磁盘性能较好，时间短，适合于做池模式的虚拟桌面三：厚置备置零（thick）：创建群集功能的磁盘。创建磁盘时，直接从磁盘分配空间，并对磁盘保留数据置零。所以当有I/O操作时，不需要等待直接执行。磁盘性能最好，时间长，适合于做跑运行繁重应用业务的虚拟机


![](https://gitee.com/hxc8/images6/raw/master/img/202407190007320.jpg)

CD/DVD驱动器选择刚才上传的 ISO 文件，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007260.jpg)

引导选项选择”BIOS“，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007450.jpg)

下一步：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007703.jpg)

点击完成后，打开虚拟机电源，直接按回车键安装 TrueNAS。（或则等待10秒将自动进入安装界面）

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007849.jpg)

选择 OK 按回车键，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007086.jpg)

使用**空格键**选择一个驱动器，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007127.jpg)

此操作将擦除硬盘所有数据，选择 YES 回车继续，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007062.jpg)

设置一个超级管理员密码，在 web 登录界面会使用到。如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007204.jpg)

选择 Boot 模式为 BIOS，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007236.jpg)

开始安装，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007209.jpg)

安装完成，重启系统，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007336.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007409.jpg)

系统启动成功后，输出如下：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007537.jpg)

根据控制台输出的地址，输入到浏览器，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007745.jpg)

输入刚才设置的管理员密码，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190007819.jpg)

**TrueNAS 设置**

安装完成 TrueNAS 之后，需要进行简单的设置，设置时区为：中国上海，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008099.jpg)

找到服务，启动 SMB 服务，并设置开机自动启动，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008502.jpg)

**Windows 远程访问 TrueNAS 存储**

新建存储池，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008611.jpg)

我们是单块硬盘，硬盘已经做了 RAID1，TrueNAS 认为我们使用一块磁盘存储，可能会导致数据丢失，如下图：

A stripe data vdev is highly discouraged and will result in data loss if it fails（非常不鼓励使用条带数据 vdev，如果失败将导致数据丢失）

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008703.jpg)

在存储池中新建一个 DataSet（可以理解为文件夹，DataSet 下面还可以新建 DataSet），如下图：

 

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008910.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008326.jpg)

在 TrueNAS 新建组和用户，新建一个“

itsvse_home”的组，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008448.jpg)

新建一个 test 用户，密码：a123456，添加到 itsvse_home 组里面，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008482.jpg)

在存储池找到刚才新建的 DataSet（test）设置 ACL 权限，如下图：

 

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008687.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008903.jpg)

在共享菜单，找到 Windows shares（SMB），新建，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008079.jpg)

尝试打开我的电脑，在导航栏中输入 

**\\192.168.50.177\test_private**，账号：test，密码：a123456，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008180.jpg)

测试可以新建文件夹和正常上传下载数据了。也可以直接将该共享映射为网络驱动器，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190008160.jpg)

（完）