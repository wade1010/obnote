[https://github.com/juicedata/go-fuse](https://github.com/juicedata/go-fuse)

FUSE概述
FUSE（用户态文件系统）是一个实现在用户空间的文件系统框架，通过FUSE内核模块的支持，使用者只需要根据fuse提供的接口实现具体的文件操作就可以实现一个文件系统。
在fuse出现以前，Linux中的文件系统都是完全实现在内核态，编写一个特定功能的文件系统，不管是代码编写还是调试都不太方便，就算是仅仅在现有传统文件系统上添加一个小小的功能，因为是在内核中实现仍需要做很大的工作量。在用户态文件系统FUSE出现后(2.6内核以后都支持fuse)，就会大大的减少工作量，也会很方便的进行调试。编写FUSE文件系统时，只需要内核加载了fuse内核模块即可，不需要重新编译内核。
FUSE 特点
    用户空间文件系统——类Unix OS的框架
    允许非超户在用户空间开发文件系统
    内核的API接口，使用fs-type操作
    支持多种编程语言（ c、c++、perl、java 等）
    普通用户也可以挂载FUSE
    不用重新编译内核
FUSE组成
fuse主要由三部分组成：FUSE内核模块、用户空间库libfuse以及挂载工具fusermount。
    fuse内核模块：实现了和VFS的对接，实现了一个能被用户空间进程打开的设备，当VFS发来文件操作请求之后，将请求转化为特定格式，并通过设备传递给用户空间进程，用户空间进程在处理完请求后，将结果返回给fuse内核模块，内核模块再将其还原为Linux kernel需要的格式，并返回给VFS；
    fuse库libfuse：负责和内核空间通信，接收来自/dev/fuse的请求，并将其转化为一系列的函数调用，将结果写回到/dev/fuse；提供的函数可以对fuse文件系统进行挂载卸载、从linux内核读取请求以及发送响应到内核。libfuse提供了两个APIs：一个“high-level”同步API 和一个“low-level” 异步API 。这两种API 都从内核接收请求传递到主程序（fuse_main函数），主程序使用相应的回调函数进行处理。当使用high-level API时，回调函数使用文件名（file names）和路径（paths）工作，而不是索引节点inodes，回调函数返回时也就是一个请求处理的完成。使用low-level API 时，回调函数必须使用索引节点inode工作，响应发送必须显示的使用一套单独的API函数。
    挂载工具：实现对用户态文件系统的挂载