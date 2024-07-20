![](https://gitee.com/hxc8/images6/raw/master/img/202407190003937.jpg)

上图的nvme0n1和nvme0n2什么意思呢？

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003313.jpg)

上图的/dev/nvme0是指NVMe的控制器

后面的nvme0n1和nvme0n2是指磁盘的索引

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003363.jpg)

编译，这里./configure --with-nvme-cuse   这个cuse后面需要用到

编译完spdk后，执行下面命令

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003568.jpg)

上面0000开头的就是PCI地址，什么是PCI呢？PCI就像一条高铁，这个地址是什么意思？作为PCI总线，是可以挂N多个设备的，每个高铁站都有自己的地址，那么这个0000开头的就是这个地址。

后面uio_pci_generic ，这个uio是Linux提供出来的一层通用IO，就是对外设而言，我们操作各种各样的磁盘各种各样的网络，我们提供一种叫做uio的方法，不当是操作磁盘，操作网卡，网络io，同样是uio。

很多人就说那不是socket吗？socket是应用层的概念，对于uio提供出来的，它是在内核里面提供出来的一层通用的对外设操作的方法

那么uio操作外设和PCI怎么理解呢？PCI作为总线，uio作为子系统，基于PCI封装了一层uio的框架，就好比你在操作数据库的时候，比如是MySQL，你就是基于mysql提供出来的开发驱动，在它的基础上封装一个连接池出来， 那么这个PCI就相当于MySQL提供出来的开发驱动，那么这个uio就相当于你做得一个连接池。那么这个连接池不单是操作MySQL，也可以操作Redis或者其它的数据库。

NVMe磁盘如何规划？

再次执行前面执行过的命令 ls /dev/nvme* -l发现没有结果显示

为什么会没结果呢？

因为NVMe已经在我们执行这条命令的时候（./scripts/setup.sh）就把nvme接管过来了，本来是由系统接管的，这个由系统接管的时候，可以通过mount把nvme0n1或者nvme0n2挂载到某个目录，这个挂在我完是由内核所接管使用的。现在我们通过setup.sh后就跟dpdk绑定完网卡一样，你会发现dpdk绑定完网卡，你通过ifconfig是看不到该网卡的，和这种情况一样。

spdk通过setup.sh绑定完之后,这两个nvme已经被spdk所接管，接下来就是spdk开始工作。

那么现在磁盘都看不到了，该如何工作呢？

接下来看下spdk提供的几种方式。

1、命令：创建文件，删除移动，cmd （通过字符设备去操作）

2、数据传输,read,write

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003583.jpg)

对于内核而言，我们内够去操作的东西，提供出来这么一组方法，就是nvme提供一套机制，这个命令是通过字符设备去操作的

加载cuse模块

modprobe cuse

启动一个网络框架

./build/bin/spdk_tgt    这个叫做spdk-host

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003503.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003152.jpg)

上图可以看出启动一个app_start和一个reactor_run

再启动一个终端，使用RPC操作spdk-host

执行命令如下：

先注册

./scripts/rpc.py bdev_nvme_cuse_register -n Nvme0

执行后server端会显示如下内容

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003709.jpg)

cuse就是这个字符设备，我们在内核里面去操作一个设备的时候，提供出来一组命令，就是用cmd操作方法，它跟fuse很像，fuse就是一个用户态文件系统,我们在读写文件的时候，在/dev下面生成一个fuse文件，我们通过一个开发库libfuse，这个库在启动的时候不断的去监听fuse里面有没有数据，内核里面有变化，这个fuse就知道。而这个cuse也跟fuse的思路一样，但是它是通过外界去设置进去的。

执行 ls /dev/spdk 会看到3个文件

nvme0   nvme0n1 nvme0n2

那么这3个和之前的/dev/nvme0、/dev/nvme0n1、/dev/nvme0n2有什么区别呢？

/dev/nvme0、/dev/nvme0n1、/dev/nvme0n2是原生系统给你自带的

 /dev/spdk下面的是通过cuse，cuse就是spdk提供出来一组对磁盘操作的一种方式，被spdk接管了

./scripts/rpc.py bdev_nvme_attach_controller -b Nvme0 -t PCIe -a 0000:03:00.0  

-b是块设备名称

-t是type类型

-a是地址 address

客户端执行完结果返回的是 Nvme0n1 和Nvme0n2

查看绑定的结果

./scripts/rpc.py bdev_nvme_get_controllers

下面介绍一种工具，nvme-cli

```
nvme --help
```

下面介绍spdk的网络

cd yourpath/spdk/examples/sample/spdk_server

make clean

make

cd yourpath/spdk/build/examples

运行server

./spdk_server -H 0.0.0.0 -P 8080

可以使用网络调试助手 NetAssist.exe来作为客户端连接下服务端进行测试

spdk

./configure --with-nvme-cuse

a.spd-->对于ssd/nvme的方式去接管，本来一块磁盘，在系统默认启动是，会在/dev/下面多一个设备，现在spdk一开始启动的时候，绑定完后，这个数据不从内核走了，直接从spdk走。

与dpdk的区别-->网卡的数据被dpdk接管，它是把本来经过内核的数据通过dpdk进行了旁路。

简单的说，dpdk是作为网卡的旁路，截获网卡的数据，spdk是截获磁盘的数据，不从内核走，从spdk走

那么中间的数据是哪个调用哪个呢？

spdk是把dpdk的数据都有包含进来的，但是并没有用里面网络的东西，用了里面uio、ringbuffer的东西