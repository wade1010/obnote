[https://mp.weixin.qq.com/s/HvbMxNiVudjNPRgYC8nXyg](https://mp.weixin.qq.com/s/HvbMxNiVudjNPRgYC8nXyg)

1. FUSE 框架就是内核开发者为了日益多样的用户需求开发出来的，使得用户态程序参与到 IO 路径的处理成为可能；

1. FUSE 框架的 3 大组件分别是：内核 fuse 模块，用户态 

libfuse 库，

fusermount 挂载工具；

1. 内核 fuse 模块用于承接 vfs 的请求，并且通过 

/dev/fuse 建立的管道，把封装后的请求发往用户态；

1. libfuse

 则是用户态封装用来解析 FUSE 数据包协议的库代码，服务于所有的用户态文件系统；

1. fusermount

 则是用户态文件系统用来挂载的工具而已；

1. /dev/fuse

 就是连接内核 fuse 和用户态文件系统的纽带；

1. 上面动图演示了 FUSE 文件系统的完整 IO 路径，你学fei了吗？

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002109.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003728.jpg)

下面总结一下上面的基础以上的知识：

1. mount 用来列举查看当前所有文件系统实例，也能支持挂载命令（但 

mount  挂载不会持久化，重启就没了），

umount 用来卸载；

1. /etc/fstab

 是用来配置文件系统挂载规则的，是持久化的配置，重启不丢；

1. df -aTh

 用来查看每个文件系统挂载目录的详情，包括空间使用量，总量，挂载点等信息；

1. 内核模块的功能以 

ko 文件的形式体现，在 

/lib/modules/${kernel_version}/kernel/fs/ 目录可以看到支持的内核文件系统模块，

lsmod 命令可以看到已经加载的内核模块；

1. 文件系统开发之所以难？是因为之前在内核中开发，内核开发最难的在于调试和排障手段不方便。那文件系统还有出路吗？有，奇伢带你

**自制一个极简的文件系统**，基于 Linux 系统使用纯 Go 语言来做哦，敬请期待后续，自己动手，理解更深；

**第一步：解析 FUSE 协议**

在 02 FUSE 框架篇我们介绍了 FUSE 协议，说到了 FUSE 框架的 3 组件：内核 fuse 文件系统，用户态 libfuse 库，fusermount 工具。

内核的 fuse 文件系统只有一份，用于承接 vfs 请求，封装成 FUSE 协议包，走 /dev/fuse 建立起来的通道转发用户态。用户态的任务就是把 FUSE 协议包解析出来并且处理，然后把请求响应按照 FUSE 协议封装起来，走 /dev/fuse 通道传回内核，由 vfs 传回用户。

推荐一个 Go 的 FUSE 库：bazil/fuse，这是一个纯 Go 写的 FUSE 协议解析库，作用和 libfuse 这个纯 c 语言写的库作用完全一样。

[https://github.com/bazil/fuse](https://github.com/bazil/fuse)

1. FUSE 框架三大组件：内核 fuse 模块，用户态 FUSE 协议解析库，fusermount 工具；

1. FUSE 协议解析本身跟具体语言无关，c 可以实现，Go 可以实现，甚至 Python 都可以实现；

1. libfuse

 是纯 c 实现的 FUSE 协议解析库，如果你想用 c 语言实现一个用户文件系统，那么选它就对了；

1. bazil.org/fuse

 是纯 Go 实现的 FUSE 协议库，我们用 Go 语言实现用户文件系统，那么选它就对了；

1. 实现一个用户文件系统有多简单？只需要定义 FS，Dir，File 这三大结构的处理逻辑，以上我们实现了一个名叫 

hello，world 的文件系统；