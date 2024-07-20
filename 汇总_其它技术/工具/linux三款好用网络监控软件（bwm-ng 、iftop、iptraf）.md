**linux三款好用网络监控软件（bwm-ng 、iftop、iptraf）**

一：Bwm

Linux流量监控软件bwm （支持64位系统）

Bandwidth Monitor NG (简称为 Bwm-NG)是一个简单的网络和磁盘带宽监视程序，可在Linux、BSD、Solaris等平台上运行。它支持各种各样的检测元件，用于收集各种统计数据，包括/proc/net/dev、netstat、getifaddr、sysctl、kstat、 /proc/diskstats /proc/partitions、 IOKit、 devstat 、 libstatgrab等。接口或设备可以黑白方式列示，这样用户就可以只查看感兴趣的数据。Bwm-NG支持多种输出选项，如图形、纯文本、CVS及 HTML等。

查看流量命令：bwm-ng -d （按u键可切换流量单位）

软件下载地址

32位

wget [http://apt.sw.be/redhat/el5/en/i386/rpmforge/RPMS/bwm-ng-0.5-2.el5.rf.i386.rpm](http://apt.sw.be/redhat/el5/en/i386/rpmforge/RPMS/bwm-ng-0.5-2.el5.rf.i386.rpm)

centos 5

wget [http://dl.fedoraproject.org/pub/epel/5/x86_64/bwm-ng-0.5-9.el5.x86_64.rpm](http://dl.fedoraproject.org/pub/epel/5/x86_64/bwm-ng-0.5-9.el5.x86_64.rpm)

centos6

wget [http://dl.fedoraproject.org/pub/epel/6/x86_64/bwm-ng-0.6-6.el6.1.x86_64.rpm](http://dl.fedoraproject.org/pub/epel/6/x86_64/bwm-ng-0.6-6.el6.1.x86_64.rpm)

wget [http://jaist.dl.sourceforge.net/project/bwmng/bwmng/0.6/bwm-ng-0.6.tar.gz](http://jaist.dl.sourceforge.net/project/bwmng/bwmng/0.6/bwm-ng-0.6.tar.gz)

报错

error: Failed dependencies:

libstatgrab.so.6()(64bit) is needed by bwm-ng-0.5-9.el5.x86_64

centos 6x 补丁包！

wget [http://dl.fedoraproject.org/pub/epel/6/x86_64/libstatgrab-0.17-1.el6.x86_64.rpm](http://dl.fedoraproject.org/pub/epel/6/x86_64/libstatgrab-0.17-1.el6.x86_64.rpm)

centos 5x 补丁包！

wget [http://dl.fedoraproject.org/pub/epel/5/x86_64/libstatgrab-0.13-4.el5.x86_64.rpm](http://dl.fedoraproject.org/pub/epel/5/x86_64/libstatgrab-0.13-4.el5.x86_64.rpm)

安装步骤如下：

1.解压

tar -xvf bwm-ng-0.6.tar.gz

2.进入安装目录

cd bwm-ng-0.6

3.编译安装

./configure

make

make install

4.执行命令

bwm-ng

设你使用的是‘curses’输出方式，那么将会用到 ‘a’, ‘t’和‘u’这三个命令键：

‘a’-在全部传感器接口和选定接口间转换 (bwm-ng.conf)

‘t’-在 rate, max (峰值), sum (程序启动后的总吞吐量),以及30秒平均值之间切换。

‘u’-显示bytes/bits/packets/errors

二，iftop

iftop是类似于top的实时流量监控工具。

iftop可以用来监控网卡的实时流量（可以指定网段）、反向解析IP、显示端口信息等，详细的将会在后面的使用参数中说明。

CentOS系统：

yum install flex byacc  libpcap ncurses ncurses-devel

wget [ftp://fr2.rpmfind.net/linux/dag/redhat/el5/en/i386/dag/RPMS/iftop-0.17-1.el5.rf.i386.rpm](ftp://fr2.rpmfind.net/linux/dag/redhat/el5/en/i386/dag/RPMS/iftop-0.17-1.el5.rf.i386.rpm)

rpm -ivh iftop-0.17-1.el5.rf.i386.rpm

centos6x

wget [ftp://ftp.muug.mb.ca/mirror/fedora/epel/6/x86_64/iftop-1.0-0.1.pre2.el6.x86_64.rpm](ftp://ftp.muug.mb.ca/mirror/fedora/epel/6/x86_64/iftop-1.0-0.1.pre2.el6.x86_64.rpm)

centos5x

wget [http://pkgs.repoforge.org/iftop/_buildlogs/iftop-0.17-1.el5.rf.x86_64.ok.log.gz](http://pkgs.repoforge.org/iftop/_buildlogs/iftop-0.17-1.el5.rf.x86_64.ok.log.gz)

wget [http://apt.sw.be/redhat/el5/en/x86_64/rpmforge/RPMS/iftop-0.17-1.el5.rf.x86_64.rpm](http://apt.sw.be/redhat/el5/en/x86_64/rpmforge/RPMS/iftop-0.17-1.el5.rf.x86_64.rpm)

直接运行： iftop

iftop相关参数

常用的参数

-i设定监测的网卡，如：# iftop -i eth1

-B 以bytes为单位显示流量(默认是bits)，如：# iftop -B

-n使host信息默认直接都显示IP，如：# iftop -n

-N使端口信息默认直接都显示端口号，如: # iftop -N

-F显示特定网段的进出流量，如# iftop -F 10.10.1.0/24或# iftop -F 10.10.1.0/255.255.255.0

-h（display this message），帮助，显示参数信息

-p使用这个参数后，中间的列表显示的本地主机信息，出现了本机以外的IP信息;

-b使流量图形条默认就显示;

-f这个暂时还不太会用，过滤计算包用的;

-P使host信息及端口信息默认就都显示;

-m设置界面最上边的刻度的最大值，刻度分五个大段显示，例：# iftop -m 100M

进入iftop画面后的一些操作命令(注意大小写)

按h切换是否显示帮助;

按n切换显示本机的IP或主机名;

按s切换是否显示本机的host信息;

按d切换是否显示远端目标主机的host信息;

按t切换显示格式为2行/1行/只显示发送流量/只显示接收流量;

按N切换显示端口号或端口服务名称;

按S切换是否显示本机的端口信息;

按D切换是否显示远端目标主机的端口信息;

按p切换是否显示端口信息;

按P切换暂停/继续显示;

按b切换是否显示平均流量图形条;

按B切换计算2秒或10秒或40秒内的平均流量;

按T切换是否显示每个连接的总流量;

按l打开屏幕过滤功能，输入要过滤的字符，比如ip,按回车后，屏幕就只显示这个IP相关的流量信息;

按L切换显示画面上边的刻度;刻度不同，流量图形条会有变化;

按j或按k可以向上或向下滚动屏幕显示的连接记录;

按1或2或3可以根据右侧显示的三列流量数据进行排序;

按<根据左边的本机名或IP排序;

按>根据远端目标主机的主机名或IP排序;

按o切换是否固定只显示当前的连接;

按f可以编辑过滤代码，这是翻译过来的说法，我还没用过这个！

按!可以使用shell命令，这个没用过！没搞明白啥命令在这好用呢！

按q退出监控。

三、IPTRAF

IPTraf的是一个IP网络的网络监控工具。它截取网络上的数据包，并给出了当前的IP流量在它的各条信息。IPTraf的返回的信息包括：

总计，IP，TCP，UDP，ICMP和非IP字节数

TCP源地址和目的地址和端口

TCP包和字节计数

TCP标志状态

UDP源和目的地信息

ICMP类型信息

OSPF的源和目的地信息

TCP和UDP服务统计

接口数据包计数

接口IP校验和错误计数

界面活性指标

局域网站统计

IPTraf的可以用来监控一个IP网络上的负载，最常用的类型的网络服务，程序的TCP连接，以及其他。

IPTraf的是一个纯软件的分析仪。它利用内置的原始数据包捕获的Linux内核，允许它被用于广泛的以太网卡，支持FDDI适配器，支持ISDN适配器，令牌环网，异步SLIP / PPP接口和其他网络设备的接口。不需要特殊的硬件要求。

重要的TCP / IP协议（IP，TCP，UDP，ICMP等）的基本知识是必要的，你最了解的信息由程序生成。

1.软件下载

wget [ftp://iptraf.seul.org/pub/iptraf/iptraf-3.0.0.bin.i386.tar.gz](ftp://iptraf.seul.org/pub/iptraf/iptraf-3.0.0.bin.i386.tar.gz)

wget [ftp://iptraf.seul.org/pub/iptraf/iptraf-3.0.0.tar.gz](ftp://iptraf.seul.org/pub/iptraf/iptraf-3.0.0.tar.gz)

rpm 下载地址如下：

wget [http://szmov.net/centos55/CentOS/iptraf-3.0.0-5.el5.i386.rpm](http://szmov.net/centos55/CentOS/iptraf-3.0.0-5.el5.i386.rpm)

2.软件安装

2.1 使用系统yum 安装；

yum install iptraf -y

2.2安装下载的软件包；

tar zxvf iptraf-3.0.0.tar.gz

cd iptraf-3.0.0

./Setup

3.使用

直接运行 iptraf

后有一个如下的菜单提示,然后进入相关的选项查看

IP流量监视(IP traffic monitor)

网络接口的一般信息统计(General Interface Statistics)

网络接口的细节信息统计(Detailed Interface Statistics)

统计分析(Statistical Breakdowns)

局域网工作站统计(LAN Station Statistics)

过滤器(Filters...)

配置(Configure...)

退出(Exit)

4.命令行参数：

-i 网络接口：让IPTraf监视特定的网络接口，例如：eth0。-i all表示监视系统的所有网络接口。

-g：网络接口的一般统计信息。

-d 网络接口：显示特定网络接口的周详统计信息。

-s 网络接口：对特定网络接口的TCP/UDP数据流量进行监视。

-z 网络接口：监视局域网的特定网络接口。-l all表示全部。

-t timeout：使IPTraf在指定的时间后，自动退出。如果没有设置IPTraf就会一直运行，直到用户按下退出键(x)才退出。

-B：使IPTraf在后台运行。独立使用无效(被忽略直接进入菜单界面)，只能和-i、-g、-d、-s、-z、-l中的某个参数一块使用。

-L filename：如果使用-B参数，使用-L filename使IPTraf把日志信息写入其他的文件(filename)中。如果filename不包括文件的绝对路径，就把文件放在默认的日志目录(/var/log/iptraf)。

-f：使IPTraf强制清除所有的加锁文件，重置所有实例计数器。

-h：显示简短的帮助信息

也可以直接加参数或选项直接进入

可以查看还有哪些参数和选项

©著作权归作者所有：来自51CTO博客作者无锋剑客的原创作品，请联系作者获取转载授权，否则将追究法律责任

linux三款好用网络监控软件（bwm-ng 、iftop、iptraf）

[https://blog.51cto.com/michaelkang/5580845](https://blog.51cto.com/michaelkang/5580845)