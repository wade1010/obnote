【背景】

VMWare下安装了个13.04的Ubuntu，使用期间，有些经验，整理如下：

Ubuntu使用心得

系统使用

重启Ubuntu

右上角->Shut Down->Restart

快速运行某个程序

Alt+F2

相当于Windows中的Win+R，调出运行命令行，可以输入对应程序的名字，快速打开某程序，比如

【已解决】Ubuntu更改截图默认保存位置

中的，快速找到dconf-editor并打开。

右击地址栏中的文件夹然后拷贝，可以得到文件夹的绝对路径

举例：

![right click some folder then copy](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/28A4B66FD25C4134B34BFE635EFA5866right-click-some-folder-then-copy_thumb.png)

即可得到对应路径的地址，然后到别的地方粘贴出来看看：

![then paste the path](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/C4A903AB62FA493BA27ED2E412EBB20Dthen-paste-the-path_thumb.png)

![can got the file or folder path](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/1E746AED29EF4071AFF28573002AF5DFcan-got-the-file-or-folder-path_thumb.png)

显示当前所有的环境变量

用env或printenv

详见：

【已解决】Ubuntu中显示当前所有的环境变量

Alt+Tab切换到打开多个同类型程序时，停顿一下，可以显示出对应多个程序

之前只知道Alt+Tab去在多个程序间切换

但是发现一个问题，当我对于某个程序，打开了多个，

比如多个终端terminal

多个文件夹

时，无法准确的切换到对应的某个特定的终端上

后来发现，其实在Alt+Tab时，停顿一下，如果当前程序，有多个子进程，则可以自动显示出来，供你切换选择的：

![now tab to current terminal](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/64D64D6E06094A538DE4A27E1A713C08now-tab-to-current-terminal_thumb.png)

稍等一下，如果当前的程序（终端）有多个子窗口，则会自动显示出来，供你切换的：

![for terminal can choose between two sub window](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/3117CD679B144DD5814C392DE98C6FD5for-terminal-can-choose-between-two-sub-window_thumb.png)

更换源(sources.list)相关

更换源之后记得要去update

再更换源之后，无论是手动修改sources.list还是通过图形界面配置，记得最后都要去update一下：

sudo apt-get update

更换源时最好去用那个Select Best Server

当然，如果手动选择，也是可以的。

但是未必可以找到，对于你自己来说，速度最快的那个服务器。

所以，可以去用那个Select Best Server，去让系统自己找速度最快的服务器。

详见：

【记录】给Ubuntu更换源（sources.list）：从原始的us.archive.ubuntu.com换个支持13.04的速度更快的源

终端Terminal

快捷键打开终端

Ctrl+Alt+T，可直接调出终端（Terminal），不过打开终端，所处的当前路径都是HOME所在路径

![ctrl alt t call terminal but desktop location](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/8007DA6B74FF4FE4A9F75020762463B7ctrl-alt-t-call-terminal-but-desktop-location_thumb.png)

如果想要实现类似于Windows中，右击某文件夹，打开cmd且定位到该文件夹的效果，即：

Ubuntu中打开终端且定位到当前文件夹的话，可以使用第三方工具：nautilus-open-terminal

sudo apt-get install nautilus-open-terminal

详见：

【已解决】Ubuntu中右键（桌面和文件夹）打开终端（定位到当前路径）

让终端的提示信息变彩色

去.bashrc中，把force_color_prompt=yes之前的那个#注释去掉

->强制使用彩色prompt

详见：

【已解决】Ubuntu中终端中的提示信息（prompt）彩色显示

让终端显示的信息保留足够长

终端中任意地方右键->Profiles->Profile Preferences->Scrolling->Scrollback->设置为：Unlimited

详见：

【已解决】Ubuntu中让终端对于历史输出的内容保持足够长

让终端只显示当前路径而不是很长的那个绝对路径

修改.bashrc，把PS1的值（共三处）的小写的w改为大写的W

详见：

【已解决】Ubuntu中让终端只显示当前路径，而不显示绝对路径

apt相关(apt-get apt-cache等等)

安装特定版本的软件包

sudo apt-get install xxx=x.y.z

详见：

【已解决】Ubuntu中用apt-get install安装特定版本的软件包

安装svn不是apt-get install svn而是apt-get install subversion

详见：

【已解决】Ubuntu下安装svn

查看某个软件包的（依赖内容等）详细信息

apt-cache showpkg xxx

详见：

【已解决】Ubuntu中查看某个软件包所包含的内容

Ubuntu常见问题及解答

问题：双击Eclipse提示找不到Java

简答：先去安装jre（或jdk），再设置对应的JAVA_HOME，即可。

详见：

【已解决】Ubuntu中双击Eclipse结果出错：A Java Runtime Environment (JRE) or Java Development Kit (JDK) must be available in order to run Eclipse

问题：安装jdk（或jre）时出错

简答：

更换源，确保你所用的源，是支持Ubuntu 13.04（而不是最高只支持到12.04）

详见：

【已解决】Ubuntu中安装jre结果出错：openjdk-7-jre-headless : Depends: ca-certificates-java but it is not going to be installed

问题：网络断了也会导致apt-get install出现各种问题

普通情况下，apt-get install出现各种问题，是可以通过

更换源

clean

等动作而解决。

但是要注意，确保当前网络是正常的，否则，当然，也会导致apt-get出问题的。

详见：

【已解决】Ubuntu中执行sudo apt-get install出错：E: Unable to fetch some archives, maybe run apt-get update or try with –fix-missing?

0

您可能也喜欢：

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/95C1617F5B754C3D8CB103420C23B156PBMwemUT.png)

【整理】Cygwin使用心得和使用技巧

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/DB4C6505672641DABFE8B9D286C330FCemTXx7qR.jpg.jpeg)

ubuntu 10.10 安装SSH + 【已解决】secureCRT中：中文乱码显示问题 + Home/End移动到行首/行尾 + vi/vim中显示彩色代码

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/D24E59E4659946B5A002C26D54DFF61DOygbXoJC.png)

【整理】Thunderbird使用心得和一些使用技巧

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/A895757847694B1C96359125F3AF19F6HM5D0uM9.png)

【整理】ADT（Eclipse IDE）使用心得

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/6961450044E34B5EAB3F11957651DCBCbDkKZmhH.png)

【记录】Ubuntu中下载和安装Eclipse

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/5D0DD5C14C4A43BC882DB8BF812DE86C18nY604wH.png)

【已解决】Ubuntu更改截图默认保存位置

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/AAED3B4729DA45839711C4789E82FFE3tfVv6tyB.png)

【教程】在VirtualBox中创建Ubuntu的虚拟机

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/0DACBFC5046449E7A9844FF386A624F01Kxla131.png)

【已解决】Ubuntu下编译xmlrpc出错：gcc: error trying to exec 'cc1': execvp: No such file or directory

无觅关联推荐[?]