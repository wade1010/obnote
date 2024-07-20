分布式文件系统

| dfs：对文件做索引   | 
| -- |
| VFS | 
| 内核里面提供出来的各种各样的文件系统，比如 | 


大概是由上面三层组成

对外界访问的时候，第一层dfs只是个应用程序，对外界提供服务。

第二层VFS是操作系统Linux提供出来的这么一层，比如去操作EXT4、EXT3和TEMPFS的时候，它们每个文件系统的文件组织格式都是不一样的，那VFS是对外提供这么一层标准的接口，比如说调用open去打开文件，调用write去写文件，调用read去读文件，调用close去关闭文件，都是VFS提供这么关键的一层。

这些文件系统存储下面，不管是ext3、ext4等再往下层走，就是磁盘。磁盘和内存之间是如何互相访问的？这里有一个东西叫做PCI总线

NVMe,以前普通磁盘是一个磁头，NVMe提供的是多个磁头，大大的提升了访问速度，也就是硬件的速度大大升级，那么硬件速度大大升级后就会出现一个现象，软件速度跟不上，限制在软件上面。那么英特尔提供SPDK的方案，用来解决软件上缺失的问题。

 

1、SPDK环境搭建

vmware15.x

ubuntu 20.04

Linux内核版本5.4以上，为什么对内核也有要求，因为vfio的支持

vfio叫virtual functional IO，这是一个虚拟功能的IO，之前学习DPDK的时候学过uio。

NVMe是访问磁盘介质的一种方式

vfio和uio是纯软件的，

uio也是对于IO的操作，也就是对PCI总线地址的操作，PCI总线相当于高铁，比如从广州到北京，中间由N个站，每个站都有自己的地址，那么这个地址，那么这个地址对应的设备，uio就是对应这些设备，比如鼠标、磁盘、网卡都挂在PCIe上面，那么uio是提供一种在/proc/sys文件夹下面提供两个东西，一个是地址address，第二个就是size，访问这个设备对应的空间的时候，第一， 把这个地址read出来，然后对应的size有多大，这是uio给我们提供的交互方式。

vfio不一样，提供出来的是在/dev/vfio/vfio 

安装好spdk后

ls /dev/vfio/vfio -l

会看到一个文件

spdk提供出来的也是一组，但是它的操作方式不一样，相当于open("/dev/vfio/vfio",)

分三级来看

第一是在内核中有个driver(或者叫module)叫做vfio，

第二就是设备文件，就是Linux启动后就有那么一个文件，这里就是/dev/vfio/vfio

第三就是在用户空间操作vfio的时候，提供一个库叫libvfio-user来操作第二层的设备文件

当你看到任何一个/dev下面有一个对应设备文件，都可以分3级来看，

第一级系统内核里面有一个模块，

第二级就是这个文件

第三级就是用户空间操作这个文件的lib库

2、SPDK的编译

参照 [https://github.com/spdk/spdk](https://github.com/spdk/spdk)

下面中划线是视频测试部分，不需要看

然后为NVMe设备创建文件系统，（mkfs.ext4 /dev/nvme0n1）

然后挂载就可以用了(mount /dev/nvme0n1 /xxx/xxx/xxx)

接下来讲讲SPDK该如何操作

先终端命令行执行 lspci 看下

其中有一个 03:00.0 Non-Volatile memory controller:VMware Device 07f0

其中初始化SPDK环境 sudo scripts/setup.sh，主要是把SPDK里面对应的模块 内核文件加上。执行后会出现一个特别有意思的东西

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003536.jpg)

也就是系统里面默认的是uio，可以在启动的时候修改为支持vfio

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003014.jpg)

上图：在uio的基础上采用PCI的地址去访问的，现在默认支持的还是uio

 3、启动hello example

所有新的框架，先跑起来，

10%代码跟底层框架有关系，90%跟业务有关系

./scripts/gen_nvme.sh --json-with-subsystems > ./build/examples/hello_bdev.json

bdev就是 block device ,就是块设备，这个块设备跟Linux内核里面的块设备不是一个概念，

请注意这里的块设备就是所说的我们定义的就是用于一块存储空间,它跟Linux内核里面那个块设备不是一个概念，Linux内核里面的块设备就是说，我们创建一个driver，比如说对于磁盘或者对于U盘

创建一个驱动，而这里不是这个意思，  这里是提供出来就是SPDK里面对于我们存储的一块空间，然后规划了这么一个设备

可以看下hello_bdev.json的内容

cat ./build/examples/hello_bdev.json

```
{
    "subsystems":[
        {
            "subsystem":"bdev",
            "config":[
                "method":"bdev_nvme_attach_controller",
                "params":{
                    "trtype":"PCIe",
                    "name":"Nvme0",
                    "traddr":"000:03:00.0"                
                }            
            ]        
        }    
    ]
}
```

上面配置就是告诉SPDK，现在有一个设备,分配的设备是bdev，它是NVMe的，它的地址是多少，它的名字是什么

./build/examples/hello_bdev --json ./build/examples/hello_bdev.json -b Nvme0n1

在Linux系统里面提供异步写的方案有哪些？

1、比较老的方案aio(spdk提供的是aio)

2、现在可能用的不那么多的io_uring，但是它还是比较常用的

vhost

平时使用的Nginx配置多个server，反向代理，也叫vhost（virtual host是个逻辑关系）

启动一个vhost 

./build/bin/vhost -c ./build/examples/hello_bdev.json 

cat ./build/examples/hello_bdev.json

内容如下

```
"config":[
    {
        "method":"bdev_nvme_attach_controller",
        "params":{
            "trtype":"PCIe",
            "name":"Nvme0",
            "traddr":"0000:03:00.0"        
        }    
    }
]
```

上面配置，vhost启动的时候，一个NVMe就对应一个vhost。

vhost可以做几个呢？比如我们再加一个NVMe,可以是多个。

当然还有个客户端，去连接操作vhost，从而操作NVMe,

SPDK就是提供了vhost方案,对后面的NVMe提供一个存储的方案，对它里面的数据进行规划。

新开一个终端，在终端执行下面命令启动客户端

./scripts/spdkcli.py

![](D:/download/youdaonote-pull-master/data/Technology/存储/spdk/images/WEBRESOURCE858b0179c4944038a9f6f0d94b55db22截图.png)

vhost对应的就是 下图红色框框部分，可以理解为一个vhost后面带一块固态硬盘，SSD速度比较快，SDPK就是提供了这么一套方案。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003332.jpg)

分布式文件系统也是前面一个网络后面带一个存储，那么spdk的vhost和分布式文件系统有什么区别？

spdk最大的特点就是对于文件的存储以及文件的规划，它已经不再是分布式文件系统，分布式文件系统是这样的：只对文件本身进行规划，进行索引，至于文件具体存储的方式，比如数据源、数据的格式这些事管不了的，但是spdk不一样，它把对应的文件存储都做到了用户空间来了，就是把本身内核操作的东西，现在把它做到了用户空间，有我们自己去做、去控制，这样的一种方案，包括前面的网络也是(vhost，可以用DPDK)，这是跟以往的分布式文件系统不一样的地方。

加入一个节点怎么做？

先ls看下 

./scripts/spdkcli.py ls

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003076.jpg)

从上图可以看出bdev对io提供了很多方法，aio malloc nvme rbd split_disk等

创建命令

./scripts/rpc.py bdev_malloc_create 64 512 -b Malloc0

执行后再ls看下

./scripts/spdkcli.py ls

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003144.jpg)

创建后就可以对数据进行存储和修改

如何添加一个vhost?

./scripts/rpc.py vhost_create_scsi_controller --cpumask 0x1 vhost.0

执行后可以从另外一个终端（vhost进程）看到如下字样

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003116.jpg)

可以用netstat anop|grep vhost来看下

上图的vhost-user server不是用户态协议栈，它是内核提供出来的

![](https://gitee.com/hxc8/images6/raw/master/img/202407190003608.jpg)

接下来为vhost.0分配位置

./scripts/rpc.py vhost_scsi_controller_add_target vhost.0 Malloc0

 

![](https://gitee.com/hxc8/images6/raw/master/img/202407190004223.jpg)

spdk-client提供一系列的命令，比如ls/cd等命令去操作vhost

rpc对vhost进行修改，如前面的./scripts/rpc.py vhost_scsi_controller_add_target vhost.0 Malloc0 这里面的vhost_scsi_controller_add_target就是SPDK源码里面的一个函数名