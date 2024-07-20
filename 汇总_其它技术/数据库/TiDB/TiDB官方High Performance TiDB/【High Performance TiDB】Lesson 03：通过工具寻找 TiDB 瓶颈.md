

![](https://gitee.com/hxc8/images7/raw/master/img/202407190808330.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190808514.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190808804.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190808086.jpg)













![](https://gitee.com/hxc8/images7/raw/master/img/202407190808373.jpg)





 





![](https://gitee.com/hxc8/images7/raw/master/img/202407190808834.jpg)



示例1  假如粒度低于100KB，可能造成我们吞吐不能达到100MB/S



示例2  有了这些数据之后，我们就能知道我们在设计程序的时候，目标的性能大概是多少，你的粒度是不是划分的合理。假设CPU是很多核，是能够忙的过来的，我的磁盘假设吞吐不是400MB/S ，它是个NVME，可能是1GB/s,那这时候4KB的粒度实际上是不够的，这时候你没有充分利用磁盘的IO带宽，其实它还有很大空间







![](https://gitee.com/hxc8/images7/raw/master/img/202407190808072.jpg)



读写放大举例说明：

假设你只需要去读取100个字节，结果你触发个很多个page的读写，也就是说，你写入100字节到数据库，然后数据库需要保证数据在磁盘上是持久化的，但是对于操作系统来说，它的IO单元最小可能是个page,比如说4KB，这个时候就产生了一个放大效果，这就叫读写放大。







![](https://gitee.com/hxc8/images7/raw/master/img/202407190808534.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190808110.jpg)



也可以直接curl

![](https://gitee.com/hxc8/images7/raw/master/img/202407190808628.jpg)









![](https://gitee.com/hxc8/images7/raw/master/img/202407190808076.jpg)







![](https://gitee.com/hxc8/images7/raw/master/img/202407190808789.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190808255.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190808609.jpg)









 iostat从盘的角度看IO   



安装iostat 

```javascript
yum install sysstat
```



![](https://gitee.com/hxc8/images7/raw/master/img/202407190808475.jpg)



Linux系统中查看IO命令iostat详解

```javascript
[root@rac01-node01 /]# iostat -xd 3
Linux 3.8.13-16.2.1.el6uek.x86_64 (rac01-node01)     05/27/2017     _x86_64_    (40 CPU)
Device:         rrqm/s   wrqm/s     r/s     w/s   rsec/s   wsec/s avgrq-sz avgqu-sz   await  svctm  %util
sda               0.05     0.75    2.50    0.50    76.59    69.83    48.96     0.00    1.17   0.47   0.14
scd0              0.00     0.00    0.02    0.00     0.11     0.00     5.25     0.00   21.37  20.94   0.05
dm-0              0.00     0.00    2.40    1.24    75.88    69.83    40.00     0.01    1.38   0.38   0.14
dm-1              0.00     0.00    0.02    0.00     0.14     0.00     8.00     0.00    0.65   0.39   0.00
sdc               0.00     0.00    0.01    0.00     0.11     0.00    10.20     0.00    0.28   0.28   0.00
sdb               0.00     0.00    0.01    0.00     0.11     0.00    10.20     0.00    0.15   0.15   0.00
sdd               0.00     0.00    0.01    0.00     0.11     0.00    10.20     0.00    0.25   0.25   0.00
sde               0.00     0.00    0.01    0.00     0.11     0.00    10.20     0.00    0.14   0.14   0.00
```



输出参数描述：

```javascript
rrqms：每秒这个设备相关的读取请求有多少被Merge了（当系统调用需要读取数据的时候，VFS将请求发到各个FS，如果FS发现不同的读取请求读取的是相同Block的数据，FS会将这个请求合并Merge）
wrqm/s：每秒这个设备相关的写入请求有多少被Merge了。
rsec/s：The number of sectors read from the device per second.
wsec/s：The number of sectors written to the device per second.
rKB/s：The number of kilobytes read from the device per second.
wKB/s：The number of kilobytes written to the device per second.
avgrq-sz：平均请求扇区的大小,The average size (in sectors) of the requests that were issued to the device.
avgqu-sz：是平均请求队列的长度。毫无疑问，队列长度越短越好,The average queue length of the requests that were issued to the device.   
await：每一个IO请求的处理的平均时间（单位是微秒毫秒）。这里可以理解为IO的响应时间，一般地系统IO响应时间应该低于5ms，如果大于10ms就比较大了。这个时间包括了队列时间和服务时间，也就是说，一般情况下，await大于svctm，它们的差值越小，则说明队列时间越短，反之差值越大，队列时间越长，说明系统出了问题。
svctm：表示平均每次设备I/O操作的服务时间（以毫秒为单位）。如果svctm的值与await很接近，表示几乎没有I/O等待，磁盘性能很好。如果await的值远高于svctm的值，则表示I/O队列等待太长，系统上运行的应用程序将变慢。
%util： 在统计时间内所有处理IO时间，除以总共统计时间。例如，如果统计间隔1秒，该设备有0.8秒在处理IO，而0.2秒闲置，那么该设备的%util = 0.8/1 = 80%，所以该参数暗示了设备的繁忙程度，一般地，如果该参数是100%表示磁盘设备已经接近满负荷运行了（当然如果是多磁盘，即使%util是100%，因为磁盘的并发能力，所以磁盘使用未必就到了瓶颈）。
```









iotop是从进程或线程的角度看IO

安装iotop

```javascript
yum install iotop
```



![](https://gitee.com/hxc8/images7/raw/master/img/202407190808745.jpg)











![](https://gitee.com/hxc8/images7/raw/master/img/202407190808151.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190808398.jpg)







![](https://gitee.com/hxc8/images7/raw/master/img/202407190808650.jpg)













![](https://gitee.com/hxc8/images7/raw/master/img/202407190808946.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190808258.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190808559.jpg)









