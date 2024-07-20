近期登录服务器的宝塔后台显示磁盘空间仅剩10%左右

但是我查找大文件，也没多少，不可能使用这么多空间的，我用的是 du -sh /



查看剩余空间的是用 df -h /



这两者差距特别大。



查看了一些文章才知道原因，我的解决办法就是重启服务器，这样df就不会统计已删除的文件了。就恢复了正常显示



参考文章如下







---

在Linux下查看磁盘空间使用情况，最常使用的就是du和df了。然而两者还是有很大区别的，有时候其输出结果甚至非常悬殊。

1. 如何记忆这两个命令

du （Disk Usage）

df  （Disk Free）

2. df 和du 的工作原理

2.1 du的工作原理

du命令会对待统计文件逐个调用fstat这个系统调用，获取文件大小。它的数据是基于文件获取的，所以有很大的灵活性，不一定非要针对一个分区，可以跨越多个分区操作。如果针对的目录中文件很多，du速度就会很慢了。

2.2 df的工作原理

df命令使用的事statfs这个系统调用，直接读取分区的超级块信息获取分区使用情况。它的数据是基于分区元数据的，所以只能针对整个分区。由于df直接读取超级块，所以运行速度不受文件多少影响。

3 du和df不一致情况模拟

常见的df和du不一致情况就是文件删除的问题。当一个文件被删除后，在文件系统 目录中已经不可见了，所以du就不会再统计它了。然而如果此时还有运行的进程持有这个已经被删除了的文件的句柄，那么这个文件就不会真正在磁盘中被删除， 分区超级块中的信息也就不会更改。这样df仍旧会统计这个被删除了的文件。

（1）当前分区sda1的使用情况

[plain] view plaincopy

1. [root@centos192 testdu]# df -h /dev/sda1  

1. 文件系统          容量  已用  可用 已用%% 挂载点  

1. /dev/sda1              49G  776M   45G   2% /var  

（2）新建一个1GB的大文件

[plain] view plaincopy

1. [root@centos192 var]# dd if=/dev/zero of=myfile.iso bs=1024k count=1000  

1. 记录了1000+0 的读入  

1. 记录了1000+0 的写出  

1. 1048576000字节(1.0 GB)已复制，24.0954 秒，43.5 MB/秒  

（3）此时的分区sda1使用情况

df结果：

[plain] view plaincopy

1. [root@centos192 var]# df -h /dev/sda1  

1. 文件系统<span style="white-space:pre">  </span>      容量  已用  可用 已用%% 挂载点  

1. /dev/sda1              49G  1.8G   44G   4% /var  

du结果：

[plain] view plaincopy

1. [root@centos192 var]# du -sh /var/  

1. 1.6G    /var/  

此时两者结果基本相同。



（4）模拟一个进程打开这个大文件，然后删除这个大文件

[plain] view plaincopy

1. [root@centos192 var]# tail -f myfile.iso &  

1. [1] 23277  

1. [root@centos192 var]# rm -f myfile.iso   



（5）此时，再对比du和df的结果

首先确认有进程持有myfile.iso句柄。

[plain] view plaincopy

1. [root@centos192 var]# lsof | grep myfile.iso  

1. tail      23955      root    3r      REG                8,1 1048576000       7999 /var/myfile.iso (deleted)  



[plain] view plaincopy

1. [root@centos192 var]# du -sh /var/  

1. 596M    /var/  

1. [root@centos192 var]# df -h /dev/sda1  

1. 文件系统          容量  已用  可用 已用%% 挂载点  

1. /dev/sda1              49G  1.8G   44G   4% /var  

可以看出，df结果没有变化，而du则不再统计被删除了的文件myfile.iso。



（6）停止模拟进程，再对比du和df结果

首先确认没有进程持有myfile.iso句柄。

[plain] view plaincopy

1. [root@centos192 var]# lsof | grep myfile.iso  

1. [root@centos192 var]#   



[plain] view plaincopy

1. [root@centos192 var]# du -sh /var/; df -h /dev/sda1  

1. 596M    /var/  

1. 文件系统          容量  已用  可用 已用%% 挂载点  

1. /dev/sda1              49G  776M   45G   2% /var  

此时，myfile.iso已经没有进程占有它了，也就从磁盘上删除了，分区的超级块信息已经更改，df也就显示正常了。



4 工作中需要注意的地方

(1)当出现du和df差距很大的情况时，考虑是否是有删除文件未完成造成的，方法是lsof命令，然后停止相关进程即可。

(2)可以使用清空文件的方式来代替删除文件，方式是:echo > myfile.iso。

(3)对于经常发生删除问题的日志文件，以改名、清空、删除的顺序操作。

(4)除了rm外，有些命令会间接的删除文件，如gzip命令完成后会删除原来的文件，为了避免删除问题，压缩前先确认没有进程打开该文件。

---

 

du和df命令都被用于获得文件系统大小的信息：df用于报告文件系统的总块数及剩余块数，du -s /<filesystem>用于报告文件系统使用的块数。但是，我们可以发现从df命令算出的文件系统使用块数的值与通过du命令得出的值是不一致的。如下例：

# du -s /tmp 返回如下值：

12920 /tmp

而 df /tmp返回如下值：

Filesystem 512-blocks Free %Used Iused %Iused Mounted on

/dev/hd3 57344 42208 26% 391 4% /tmp

 

从上面的值我们可以算出<total from df> - <Free from df> = <used block count>: 57344 - 42208 = 15136. 而15136大于12920。该值差异的存在是由于du与df命令实施上的不同: du -s命令通过将指定文件系统中所有的目录、符号链接和文件使用的块数累加得到该文件系统使用的总块数；而df命令通过查看文件系统磁盘块分配图得出总块数与剩余块数。文件系统分配其中的一些磁盘块用来记录它自身的一些数据，如i节点，磁盘分布图，间接块，超级块等。这些数据对大多数用

户级的程序来说是不可见的，通常称为Meta Data。 

du命令是用户级的程序，它不考虑Meta Data，而df命令则查看文件系统的磁盘分配图并考虑Meta Data。df命令获得真正的文件系统数据，而du命令只查看文件系统的部分情况。例如，一个frag=4096 并且 nbpi=4096的空的大小为4MB的日志文件系统

中Meta Data 的分配情况如下：

1 4k block for the LVM

2 4k super blocks

2 4k blocks for disk maps

2 4k blocks for inode maps

2 4k blocks for .indirect

32 4k blocks for inodes

-------------------------

41 4k blocks for meta data on an empty 4MB file system

 

对于AIX 4.X 版本：

执行 du /foo返回的结果如下：

8 /foo/lost+found

16 /foo

要使du命令输出的结果与df 命令输出的结果匹配，我们必须要加上Meta Data。首先，将41个4k 的块转换为以512字节为单

位的值：

41 * 8 = 328

328(meta data) + 16(from du) = 344

所以有344个以512字节为单位的块分配给了这个空的文件系统。

 

而使用 df /foo命令我们可以得到下面的结果：

Filesystem 512-blocks Free %Used Iused %Iused Mounted on

/dev/lv01 8192 7848 5% 16 2% /foo

从中我们可以得到该文件系统使用的块数：8192(total blocks) - 7848(free blocks) = 344。该值与上面得出的值一致。

 



上面的换算方法对于空的文件系统很容易实现，但是对于非空的文件系统，由于Meta Data中文件间接块的大小不定，因此较难实现。所以我们不需要查看du 与 df返回的值的匹配关系，而只需要了解du -s命令返回的值反映了分配给文件及目录的磁盘块数，而df命令则反映了文件系统的实际分配情况。df命令反映的实际情况包含了用户数据（文件及目录）和Meta Data。

 

另一个表现出du与df命令不同之处的例子如下：

如果用户删除了一个正在运行的应用所打开的某个目录下的文件，则du命令返回的值显示出减去了该文件后的目录的大小。但df命令并不显示减去该文件后的大小。直到该运行的应用关闭了这个打开的文件，df返回的值才显示出减去了该文件后的文件系统的使用情况。