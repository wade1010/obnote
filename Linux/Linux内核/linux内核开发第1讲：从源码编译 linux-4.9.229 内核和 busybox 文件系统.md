linux内核开发第1讲：从源码编译 linux-4.9.229 内核和 busybox 文件系统

开发环境：ubuntu 14.04

linux源码版本：linux-4.9.229

busybox源码版本：busybox-1.30.0

qemu-system-x86_64版本：2.0.0

1 下载linux并编译linux内核源码    下载地址：[https://mirrors.edge.kernel.org/pub/linux/kernel/](https://mirrors.edge.kernel.org/pub/linux/kernel/)

2 编译busybox

不同体系的汇编代码是不一样的。

下载到本地，解压，然后进入linux-4.9.229目录：

1.指定硬件体系架构。

```
# export ARCH=x86
```

2.配置board config,此处配置为 x86_64_defconfig。好了，我们点好菜了，菜单就是x86_64_defconfig

```
make  x86_64_defconfig
```

make menuconfig  