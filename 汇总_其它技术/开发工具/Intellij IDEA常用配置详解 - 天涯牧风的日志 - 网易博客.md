1. IDEA内存优化

先看看你机器本身的配置而配置. 

\IntelliJ IDEA 8\bin\idea.exe.vmoptions 

----------------------------------------- 

-Xms64m 

-Xmx256m 

-XX:MaxPermSize=92m 

-ea 

-server 

-Dsun.awt.keepWorkingSetOnMinimize=true

查询快捷键

CTRL+N   查找类

CTRL+SHIFT+N  查找文件

CTRL+SHIFT+ALT+N 查 找类中的方法或变量

CIRL+B   找变量的来源

CTRL+ALT+B  找所有的子类

CTRL+SHIFT+B  找变量的 类

CTRL+G   定位行

CTRL+F   在当前窗口查找文本

CTRL+SHIFT+F  在指定窗口查找文本

CTRL+R   在 当前窗口替换文本

CTRL+SHIFT+R  在指定窗口替换文本

ALT+SHIFT+C  查找修改的文件

CTRL+E   最 近打开的文件

F3   向下查找关键字出现位置

SHIFT+F3  向上一个关键字出现位置

F4   查找变量来源

CTRL+ALT+F7  选 中的字符 查找工程出现的地方

CTRL+SHIFT+O  弹出显示查找内容

SVN 管理

把SVN库添加到IDEA中 SETTING -<  VERSION CONTROL -< VCS = SVBVERSION

自动代码

ALT+回车  导入包,自动修正

CTRL+ALT+L  格式化代码

CTRL+ALT+I  自 动缩进

CTRL+ALT+O  优化导入的类和包

ALT+INSERT  生成代码(如GET,SET方法,构造函数等)

CTRL+E 或者ALT+SHIFT+C 最近更改的代码

CTRL+SHIFT+SPACE 自动补全代码

CTRL+空格  代码提示

CTRL+ALT+SPACE  类 名或接口名提示

CTRL+P   方法参数提示

CTRL+J   自动代码

CTRL+ALT+T  把选中的代码放在 TRY{} IF{} ELSE{} 里

复制快捷方式

F5   拷贝文件快捷方式

CTRL+D   复制行

CTRL+X   剪 切,删除行

CTRL+SHIFT+V  可以复制多个文本 

高亮

CTRL+F   选中的文字,高亮显示 上下跳到下一个或者上一个

F2 或SHIFT+F2  高亮错误或警告快速定位

CTRL+SHIFT+F7  高亮显示多个关键字. 

其他快捷方式

CIRL+U   大小写切换

CTRL+Z   倒退

CTRL+SHIFT+Z  向 前

CTRL+ALT+F12  资源管理器打开文件夹

ALT+F1   查找文件所在目录位置

SHIFT+ALT+INSERT 竖 编辑模式

CTRL+/   注释//  

CTRL+SHIFT+/  注释/*...*/

CTRL+W   选中代码，连续按会 有其他效果

CTRL+B   快速打开光标处的类或方法

ALT+ ←/→  切换代码视图

CTRL+ALT ←/→  返回上次编辑的位置

ALT+ ↑/↓  在方法间快速移动定位

SHIFT+F6  重构-重命名

CTRL+H   显 示类结构图

CTRL+Q   显示注释文档

ALT+1   快速打开或隐藏工程面板

CTRL+SHIFT+UP/DOWN 代码 向上/下移动。

CTRL+UP/DOWN  光标跳转到第一行或最后一行下

ESC   光标返回编辑框

SHIFT+ESC  光 标返回编辑框,关闭无用的窗口

F1   帮助 千万别按,很卡!

CTRL+F4   非常重要 下班都用

重要的设置

不编译某个MODULES的方法，但在视图上还是有显示

SETTINGS -< COMPILER -< EXCLUDES -< 

不编译某个MODULES，并且不显示在视图上

MODULES SETTINGS -< (选择你的MODULE) -< SOURCES -< EXCLUDED -< 整个工程文件夹

IDEA编码设置3步曲

FILE -< SETTINGS -< FILE ENCODINGS -< IDE ENCODING

FILE -< SETTINGS -< FILE ENCODINGS -< DEFAULT ENCODING FOR PROPERTIES FILES

FILE -< SETTINGS -< COMPILER -< JAVA COMPILER -< ADDITIONAL COMMAND LINE PARAMETERS

加上参数 -ENCODING UTF-8 编译GROOVY文件的时候如果不加，STRING S = "中文"; 这样的GROOVY文件编译不过去.

编译中添加其他类型文件比如 *.TXT *.INI

FILE -< SETTINGS -< RESOURCE PATTERNS

改变编辑文本字体大小

FILE -< SETTINGS -< EDITOR COLORS & FONTS -< FONT -< SIZE 

修改智能提示快捷键 

FILE -< SETTINGS -< KEYMAP -< MAIN MENU -< CODE -< COMPLETE CODE -< BASIC 

显示文件过滤

FILE -< SETTINGS -< FILE TYPES -< IGNORE FILES...

下边是我过滤的类型,区分大小写的

CVS;SCCS;RCS;rcs;.DS_Store;.svn;.pyc;.pyo;*.pyc;*.pyo;.git;*.hprof;_svn;.sbas;.IJI.*;vssver.scc;vssver2.scc;.*;*.iml;*.ipr;*.iws;*.ids

在PROJECT窗口中快速定位,编辑窗口中的文件

在编辑的所选文件按ALT+F1, 然后选择PROJECT VIEW

------------------------------------------------------------------------------------------------------------ 

2.优化文件保存和工程加载

取消“Synchronize file on frame activation”（同步文件功能，酌情考虑可以不取消）

取消“Save files on framedeactivation”的选择

同时我们选择"Save files automatically", 并将其设置为30秒，这样IDEA依然可以自动保持文件,所以在每次切换时，你需要按下Ctrl+S保存文件



如何让IntelliJ IDEA动的时候不打开工程文件：Settings-<General去掉Reopen last project on startup

3.用*标识编辑过的文件

Editor –< Editor Tabs

—————————————–

在IDEA中，你需要做以下设置, 这样被修改的文件会以*号标识出来，你可以及时保存相关的文件。"Mark modifyied tabs with asterisk"





4.显示行号

如何显示行号：Settings-<Editor-<Appearance标签项，勾选Show line numbers





5.自定义键盘快捷方式

如果默认代码提示和补全快捷键跟输入法冲突，如何解决：Settings-<Keymap





6.如何让光标不随意定位

Settings-<Editor中去掉Allow placement of caret after end of line。





7.中文乱码问题

Settings-< File Encondings 选择 IDE Encoding为GBK。

在包含中文文件名或者文件夹的时候会出现??的乱码，解决方法如下：



File菜单-<Settings-<Colors & Fonts-<Editor Font=宋体, size=12, line spacing =1.0



Settings-<Appearance中勾选Override default fonts by (not recommended)，设置Name:NSimSun，Size:12

------------------------------------------------------------------------------------------------------------------------------------------------

General

----------------------------------------- 

取消“Synchronize file on frame activation”和“Save files onframedeactivation”的选择 

同时我们选择"Save files automatically", 并将其设置为30秒，这样IDEA依然可以自动保持文件,所以在每次切换时，你需要按下Ctrl+S保存文件。 



如何让IntelliJ IDEA动的时候不打开工程文件：Settings-<General去掉Reopen last project on startup 



Editor --< Editor Tabs

----------------------------------------- 

在IDEA中，你需要做以下设置, 这样被修改的文件会以*号标识出来，你可以及时保存相关的文件。 

"Mark modifyied tabs with asterisk" 



如何显示行号：Settings-<Editor-<Appearance标签项，勾选Show line numbers 

默认代码提示和补全快捷键跟输入法冲突，如何解决：Settings-<Keymap 

如何让光标不随意定位：Settings-<Editor中去掉Allow placement of caret after end of line 



----------------------------------------- 

IntelliJ IDEA不支持热发布，就因为改个页面我们去重启容器，是很浪费时间的，也很麻烦。我们通过改变目录来解决这个问题。在“AJAX”上点右键，选择“ Module settings” 

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/F3D7DB5F183A4F288AB728953409A1792012032009501313.gif)

到这里，你会发现有个Web Facet Exploede Directory，然后我们改变这个目录，指向当前工程的Web跟节点即可

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/85A08B491EC9434AA13DD1F70849C1262012032009503520.gif)

*:注意 Exclude from module content勾去掉，不然会有webroot不见了的现象

然后我们启动容器，随便的编辑页面，然后点击刷新，发现页面立即能显示出来了