phpStudy Linux版&Win版同步上线 支持Apache/Nginx/Tengine/Lighttpd/IIS7/8/6

phpStudy for Linux 支持Apache/Nginx/Tengine/Lighttpd，

支持php5.2/5.3/5.4/5.5切换

已经在centos-6.5,debian-7.4.,ubuntu-13.10测试成功。

下载版：http://lamp.phpstudy.net/phpstudy.bin

[phpstudy.bin](attachments/08B164BFAB6A47ACBF552C286C1BA3CEphpstudy.bin)

完整版：http://lamp.phpstudy.net/phpstudy-all.bin

[phpstudy-all.bin](attachments/35074A2005654E379ED31280842A4498phpstudy-all.bin)



安装：

wget -c http://lamp.phpstudy.net/phpstudy.bin

chmod +x phpstudy.bin    #权限设置

./phpstudy.bin 　　　　#运行安装



![](D:/download/youdaonote-pull-master/data/Technology/Linux/phpStudy/images/7E70C10C6F5E483188804FDA1BF8AA7920140318191157_35392.jpg.jpeg)

用时十到几十分钟不等，安装时间取决于电脑的下载速度和配置。

也可以事先下载好完整，安装时无需下载。

安装完成

![](D:/download/youdaonote-pull-master/data/Technology/Linux/phpStudy/images/F9C42031CF3F474FAB3EA3D2FD89CFA120140318191248_38899.jpg.jpeg)



如何切换php版：

假如你先安装的apache+php5.3

想切换成nginx+php5.4

你就再走一次./phpstudy.bin

但是你会发现有一行是否安装mysql提示选不安装

这样只需要编译nginx+php5.4

从而节省时间，这样只需要几分钟即可。

使用说明：

服务进程管理：phpstudy (start|stop|restart|uninstall)

站点主机管理：phpstudy (add|del|list)

ftpd用户管理：phpstudy ftp (add|del|list)



