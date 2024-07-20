fdisk -l

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/2D74FB14557847A6B74350265C58AC09image.png)



红色部分为新加的硬盘





fdisk /dev/sdb



```javascript
n
回车
回车
回车
w
```



fdisk -l

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/C329166051974A8394BCD145967FC811image.png)



mkfs.ext4 /dev/sdb1



mkdir /data1&&mount /dev/sdb1 /data1





echo "/dev/sdb1            /data1                   ext4    defaults        0 0">>/etc/fstab



vim /etc/fstab