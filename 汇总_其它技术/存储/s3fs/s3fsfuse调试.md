这里由于是调试fuse操作，所以使用attach process的方式调试，方式参照《vscode使用attach process调试进程》这篇笔记

libfuse.so里面代码暂时不知道怎么跳进去

所以我这里挨个点进定义的首行代码打上断点。

![](https://gitee.com/hxc8/images6/raw/master/img/202407190001189.jpg)

#### 一、测试ll /mnt/s3

s3fs_getattr

s3fs_access

s3fs_getattr

s3fs_opendir

s3fs_getattr

s3fs_readdir

s3fs_getattr

s3fs_getattr

s3fs_getattr

s3fs_getattr

s3fs_getattr

s3fs_getattr

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002823.jpg)

readdir后面有5次 s3fs_getattr

刚好对应5个对象

#### 二、测试 echo

echo "11111" > /mnt/s3/1234567.txt

s3fs_getattr

s3fs_getattr

s3fs_getattr

s3fs_getattr

s3fs_create（跟goofys一样创建一个0大小的空文件）

s3fs_getattr

s3fs_flush     

s3fs_write     （写入文件内容）

s3fs_flush

s3fs_release