



Linux 的目录结构是怎样的 



![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/E04F3F9D82FB41D1A7595BA599D1857Bimage.png)

 



· 常见目录说明：

| 目录 | 介绍 |
| - | - |
| /bin | 存放二进制可执行文件(ls,cat,mkdir等)，常用命令一般都在这里； |
| /etc | 存放系统管理和配置文件； |
| /home | 存放所有用户文件的根目录，是用户主目录的基点，比如用户user的主目录就是/home/user，可以用~user表示； |
| /usr | 用于存放系统应用程序； |
| /opt | 额外安装的可选应用程序包所放置的位置。一般情况下，我们可以把tomcat等都安装到这里； |
| /proc | 虚拟文件系统目录，是系统内存的映射。可直接访问这个目录来获取系统信息； |
| /root | 超级用户（系统管理员）的主目录（特权阶级）； |
| /sbin | 存放二进制可执行文件，只有root才能访问。这里存放的是系统管理员使用的系统级别的管理命令和程序。如ifconfig等； |
| /dev | 用于存放设备文件； |
| /mnt | 系统管理员安装临时文件系统的安装点，系统提供这个目录是让用户临时挂载其他的文件系统； |
| /boot | 存放用于系统引导时使用的各种文件； |
| /lib | 存放着和系统运行相关的库文件 ； |
| /tmp | 用于存放各种临时文件，是公用的临时文件存储点； |
| /var | 用于存放运行时需要改变数据的文件，也是某些大文件的溢出区，比方说各种服务的日志文件（系统启动日志等。）等； |
| /lost+found | 这个目录平时是空的，系统非正常关机而留下&quot;无家可归”的文件（windows下叫什么.chk）就在这里 |


 

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/5B6443C85E494536A9CFA40B2A8E3192image.png)



有个shell脚本 当前权限是444  我只想让所有者能执行怎么办

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/8CB4CB8DB149483987078E65089BCB89image.png)



![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/C1634B997FD64EAF977B4D1211E02BB0image.png)



 

一台 Linux 系统初始化环境后需要做一些什么安全工作？

·  1、添加普通用户登陆，禁止 root 用户登陆，更改 SSH 端口号。

·  修改 SSH 端口不一定绝对哈。当然，如果要暴露在外网，建议改下。l

·  ·  2、服务器使用密钥登陆，禁止密码登陆。

·  ·  3、开启防火墙，关闭 SElinux ，根据业务需求设置相应的防火墙规则。

·  ·  4、装 fail2ban 这种防止 SSH 暴力破击的软件。

·  ·  5、设置只允许公司办公网出口 IP 能登陆服务器(看公司实际需要)

·  也可以安装 VPN 等软件，只允许连接 VPN 到服务器上。

·  ·  6、修改历史命令记录的条数为 10 条。

·  ·  7、只允许有需要的服务器可以访问外网，其它全部禁止。

·  ·  8、做好软件层面的防护。

· ·  8.1 设置 nginx_waf 模块防止 SQL 注入。

· 8.2 把 Web 服务使用 www 用户启动，更改网站目录的所有者和所属组为 www 。

 

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/5EA37251EE844E4A9D9E795B980CDB1Cimage.png)



![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/605E8B25077B4E749F1F8AC092D3E28Dimage.png)



![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/6C5478E3AB51401CB40ED56E8F8F7B6Aimage.png)

 部署过哪些集群





1.nohup

用途：不挂断地运行命令。

语法：nohup Command [ Arg … ] [　& ]

　　无论是否将 nohup 命令的输出重定向到终端，输出都将附加到当前目录的 nohup.out 文件中。

　　如果当前目录的 nohup.out 文件不可写，输出重定向到 $HOME/nohup.out 文件中。

　　如果没有文件能创建或打开以用于追加，那么 Command 参数指定的命令不可调用。

退出状态：该命令返回下列出口值： 　　

　　126 可以查找但不能调用 Command 参数指定的命令。 　　

　　127 nohup 命令发生错误或不能查找由 Command 参数指定的命令。 　　

　　否则，nohup 命令的退出状态是 Command 参数指定命令的退出状态。

2.&

用途：在后台运行

一般两个一起用

nohup command &





df 命令的全称是Disk Free ，显而易见它是统计磁盘中空闲的空间，也即空闲的磁盘块数。它是通过文件系统磁盘块分配图进行计算出的。

du 命令的全称是 Disk Used ，统计磁盘有已经使用的空间。它是直接统计各文件各目录的大小，而不是从硬盘获得信息的。

两者区别     



       du，disk usage,是通过搜索文件来计算每个文件的大小然后累加，du能看到的文件只是一些当前存在的，没有被删除的。他计算的大小就是当前他认为存在的所有文件大小的累加和。

       df，disk free，通过文件系统来快速获取空间大小的信息，当我们删除一个文件的时候，这个文件不是马上就在文件系统当中消失了，而是暂时消失了，当所有程序都不用时，才会根据OS的规则释放掉已经删除的文件， df记录的是通过文件系统获取到的文件的大小，他比du强的地方就是能够看到已经删除的文件，而且计算大小的时候，把这一部分的空间也加上了，更精确了。

        当文件系统也确定删除了该文件后，这时候du与df就一致了。

