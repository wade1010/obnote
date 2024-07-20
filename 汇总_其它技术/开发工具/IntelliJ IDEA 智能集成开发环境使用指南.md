编写一个像 HelloWorld 这样的简单程序, 用记事本基本就可以完成了, 但如果是开发一个工程项目, 就需要一个强大的集成开发环境 (IDE). 当然开发 Java 项目的 IDE 有很多, 比较有名的当数 Eclipse, 以及它的一个重要扩展 MyEclipse, 然而, 今天我们要介绍的是一个更强大、更智能的 IDE — IntelliJ IDEA.

IntelliJ IDEA (下面简称 IDEA) 是捷克软件公司 JetBrains 旗下的核心产品之一, 主要用于开发 Java 应用, 它被誉为业界最好的 Java 开发工具之一, 尤其在代码智能补全、代码自动提示等方面, 可以说是”神器”. 关于产品的特性, 不在这里赘述, 详情参见官网产品介绍页面.

IDEA 支持 Windows、Mac OS X、Linux 三种平台, 可以说掌握了它, 无论在那个平台上, 进行开发都没有问题. 此外, JetBrains 公司还有推出 PhpStorm (开发 PHP), PyCharm (开发 Python), RubyMine (开发 Ruby, Rails) 等 IDE, 这些 IDE 都是在同一个基础之上, 整合相关插件完成的, 它们的界面、菜单、快捷键非常相似, 近日由谷歌推出的 Android Studio, 也建立在相同的基础之上, 换句话说, 掌握了 IDEA, 就掌握了多门语言的开发工具.

本文仅讲解在 Ubuntu 下, IDEA 12 的安装和配置方法, 并创建一个简单的 Java 学习项目.

安装

在官网的下载页面, 下载对应平台的最新版本, 截止笔者编写本章时, 最新版为 2013-06-10 发布的 12.1.4.

下载完以后, 解压缩即可. 为了以后访问方便, 将文件夹重命名为 idea.

tar -xkzvf ideaIU-12.1.4.tar.gzmv idea-IU-129.713 idea

运行 idea/bin/idea.sh 文件, 启动 IDEA, 可能返回如下错误提示

Install $ ./idea/bin/idea.shUnrecognized VM option '+UseCodeCacheFlushing'Could not create the Java virtual machine.

提示说明, Java 虚拟机的选项 +UseCodeCacheFlushing 不认识, 那么直接删除这个选项即可. 关键是这个选项在哪里? 我们首先想到, 可能在 idea/bin 下面, 执行查找命令

bin $ grep 'UseCodeCacheFlushing' *.*idea64.vmoptions:-XX:+UseCodeCacheFlushingidea.vmoptions:-XX:+UseCodeCacheFlushing

从上面的输出结果看到, 有两个文件 idea64.vmoptions 和 idea.vmoptions 都包含了这个选项, 从 64 可以猜想是指 64 位计算机.

于是我们有了解决方法, 如果你的系统是 32 位, 就注释掉 idea.vmoptions 文件中对应的行; 如果你的系统是 64 位, 就注释掉 idea64.vmoptions 文件中对应的行. 查看系统位数的方法

$ getconf LONG_BIT32

修改完以后, 应该就能正常启动了, 为了方便以后启动, 建议将启动命令定义成一个别名.

alias idea='sh /home/richard/Install/idea/bin/idea.sh'

创建项目

第一次启动 IDEA, 会弹出用户设置向导, 根据提示完成注册和简单的设置. 设置完成以后, 将弹出欢迎页面, 如下图所示.

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/A0E9C47968CC46A4A67E9C6290AC4DC720150323101458270.png)

IntelliJ IDEA 欢迎页面

接下来创建一个 Java 模块, 用于学习 Java 基础知识, 并熟悉这套 IDE. 在欢迎页面, 选择Create New Project, 弹出 New Project 对话框, 如下图所示. 在左边选择 Java Module, 在右边的Project location 选择项目存放的路径, 这里选择 /home/henry/workspace_java.

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/1B4A75E90BA0406D81B031A7A1B7A24E20150323101458823.png)

新建 Mahout 模块

展开右下方的 More Settings, IDEA 默认将创建一个与项目同名的模块, 这里改为 study, 创建一个学习模块.

在这里可以看出 IDEA 与 Eclipse 之间的一个不同之处, Eclipse 的一个工作目录 (workspace) 下可以有多个项目, 而 IDEA 却只有一个项目, 但一个项目下可以有多个模块, 所以, 有人将 IDEA 下的模块与 Eclipse 下的项目对等起来, 也有人建议, 在 IDEA 中, 一个项目下尽量只有一个模块, 不同的模块分属于不同的项目.

单击 Next, 进入支持技术选择页面, 如下图所示, 这里我们创建一个空项目, 单击 Finish 完成创建.

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/E7443885388A462FB610D1AE730A178A323101459750.png)

完成 Mahout 模块

由此, 我们创建了一个空的 Java 项目, 名为 workspace_java, 其中有一个 study 模块. 进入模块以后可以创建一个简单 HelloWorld 类来试试看.

配置

选择主题, 设置字体

默认的界面有点灰色, 有一款黑色界面, 非常酷, 在 Settings 的搜索框输入关键字 theme, 选择设置模块 IDE Settings,Appearance, 如下图所示.

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/ED88B63B5F9A4F9FB76051B3DEF1CA5520150323101459587.png)

选择主题, 设置字体

- 选择主题. 在 Theme 栏选择 Darcula 主题.

- 设置字体. 为了正确显示中文, 勾选 Override default fonts by, 然后在 Name 栏选择 SimSun主题.

光标位置

默认情况下, 光标是可以随意放置的, 可能会有些不习惯, 可通过如下方式取消随意放置 在Settings 的搜索框输入关键字 caret, 选择设置模块 IDE Settings,Editor, 如下图所示,

取消勾选右边的 Allow placement of caret after end of line 即可.

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/C4CA321FDFA64CBA9B04F4715C7CF44620150323101500184.png)

修改光标位置

显示行号

在 Settings 的搜索框输入关键字 numbers, 选择设置模块 IDE Settings,Editor,Appearance, 如下图所示, 勾选右边的 Show line numbers 即可.

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/23377EA4CD39412E8CC6377FAABB08A620150323101500741.png)

显示行号

快捷键

- Ctrl+Shift+F12 关闭工具窗口, 最大化编辑界面.

- Shift+F12 调出默认布局. 每次启动 IDEA 以后, 调整好窗口布局, 尤其是工具窗口布局, 然后使用菜单 Window,Store Current Layout as Default, 设为默认布局.

- Ctrl+E 调出最近使用的文件和工具窗口列表.

- Ctrl+N 按类名查找文件, 为了让打开的文件与资源窗口同步, 勾选 Autoscroll from Source.

- Ctrl+W 语法词选择, 利用这种方法可以快速选择对象, 重点是进行接下来的操作.

- Ctrl+Alt+V 引入新变量

- Ctrl+Shift+J 连接行

- Ctrl+X 剪切行

- Ctrl+D 复制行

- Ctrl+Q 调出 API 帮助信息

- Ctrl+B 调出定义

- Ctrl+U 调出使用 (自定义 Find Usage)

- Alt+Insert 自动生成代码

- Ctrl+Shift+B 包围 (自定义 surround)

- Ctrl+Shift+Enter 补全当前语句

- Ctrl+/ 行注释

- Ctrl+Shift+/ 块注释

- Ctrl+F12 调出类的结构, 方便快速跳转

- Alt+Shift+Insert 列选择

- Ctrl+Shift+F9 编译当前文件

- Ctrl+K 检查文件版本更新, 前提是配置了 SVN 或 Git 版本控制软件