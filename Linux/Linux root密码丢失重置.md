https://blog.csdn.net/niufenger/article/details/80314386



【init方式修改root密码！】



重启CentOS7.X系统，并在GRUB2启动界面时，光标停留在第一行按e键进入编辑模式。



当按e后出现如下界面，找到linux16开头的那行，在行末输入init=/bin/sh，并按ctrl+x进入命令行模式；



3.重新挂载根/使其有写权限，mount -o remount,rw  /



4.此时我们有了写权限，可以使用passwd更改root密码



5密码更改完成后，如果之前系统启用了selinux，必须运行touch /.autorelabel 命令，否则将无法正常启动系统；使用命令exec /sbin/init或者exec /sbin/reboot重启即可。

