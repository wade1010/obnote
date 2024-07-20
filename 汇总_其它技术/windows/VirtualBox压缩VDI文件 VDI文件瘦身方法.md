[https://blog.csdn.net/mr__bai/article/details/129147223](https://blog.csdn.net/mr__bai/article/details/129147223)

sudo dd if=/dev/zero of=/free bs=1M

df -h可以看到剩余空间，100%的时候可以停止dd，

sudo rm -rf /free

关闭虚拟机

入命令行，并进入virtualbox软件安装目录下

PS D:\Program Files\Oracle\VirtualBox> .\VBoxManage.exe modifyhd 'D:\VirtualBox VMs\xxx16\xxx16-disk001.vdi' --compact