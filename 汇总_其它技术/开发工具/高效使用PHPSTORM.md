PHPSTORM

下面在windows系统中介绍PHPSTORM使用,MAC使用与windows只是键盘布局差异。所以就不重复介绍了。

风格

1. 安装插件 Material Theme UI ，安装后重起phpstorm

1. Tools -> Material Theme 中选择喜欢的样式就可以了

快捷键

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/8343E1A9303A411BA4B6F62F24D97BAD.png)

1. 全屏幕快捷键

Keymap>Main menu>View>Toggle Distraction Free mode 为 f11健Keymap>Main menu>View>Toggle Full Screen mode 为 alt f11健

1. Keymap>Tool Windows>Database 数据库管理 alt+shift+d

1. Terminal 快捷键就使用默认的 alt+shift+t

1. Remote Host 远程主机面板 alt+shift+h

1. Run Command 切换命令控制台 alt+shift+m

1. File Structure 查找文件定义的方法 alt+shift+j

1. Navigate>File 查找文件 alt+p

1. Recent Files 查找文件定义的方法 alt+e

1. Editor Tabs>Close 关闭文件 alt+w

1. File>Save All 保存全部 alt+s

1. Code>Generate 快捷创建 alt+n

使用 MAC的同学习惯于 Command 键，所以本套按键设置大量定义了 alt 键

bootstrap

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/8995BD8C972E4C4B8B274EB1C296645F.png)

关闭angular提示

以前使用angular.js比较多，现在主要使用vue.js，所在angular.js的提示暂时不需要。

settings>Editor>Live Templates

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/11D8FAA0B44D4A1485B5DDA8D31711F0.png)

Blade

PHPstorm 默认支持Laravel的blade 模板提示，但我们需要定义一下快捷键。

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/1A7FA8C9971F48968D7C2E7692D080A1.png)

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/49D8CF393CD84EE8B0CD79771CFB84A9.png)

修改代码风格

Editor>Code Style>PHP 点击 Set From... ,我使用的是Symfony2

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/053CAC99DFE74F0981BCD06978C461F1.png)

Shell

windows10 更改 Shell（用于全局使用ls，rm等Linux命令），Mac与Linux不需要设置。

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/3B2551AF065B4DDEB751118FAC0C5031.png)

C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe

字体大小设置

鼠标滚动改变大小

Preferences | Editor | Font >Change font Size(Zoom)...

改变编辑区大小

Preferences | Editor | Font

改变终端字体大小

Preferences | Editor | Color Scheme | Console Font

改变文件列表等dialog字体大小

Preferences | Appearance & Behavior | Appearance > Use custom font

一般我录制课程的设置是 编辑区 35，终端35，文件列表25

PHP命令

Phpstorm中大量使用composer或命令行指令，所以需要设置合适的php命令

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/239DAACC72784CBEBECB2BA339BEC8BB.png)

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/1C14F691E7E04E58AD716FB8C77425E4.png)

创建项目

软件启动时 Create New Project 或 选择菜单 File>new Project ，下面是演示安装 Laravel 项目

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/52DF897C722145D095E15786FD572EB4.png)

Laravel

Laravel Plugin

在phpstorm中安装 laravel plugin 插件.

Settings > Languages & Frameworks > PHP > Laravel 点击开启 Enable for this project

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/45506635E59F496AB814EF6A6CC69D74.png)

laravel-ide-helper

laravel-ide-helper 用于实现方便的代码提示功能，详细查看插件官网

使用composer安装插件

composer require --dev barryvdh/laravel-ide-helper

生成代码跟踪支持

phpartisanide-helper:generate

其他插件

在 IDE 中设置中搜索插件 Preferences | Plugins需要安装的插件列表如下：

- Laravel Plugin

- Laravel Snippets

命令提示

settings>Tools>Command Line Tool Support

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/6C837193E7DE4698A26A566A27178D87.png)

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/5795BA767C104F058DDA7FEA7D54ADB8.png)

Git

phpstorm很好的内置支持版本库管理。选择菜单 VCS>Enable Version Control Integration

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/C1F7B308FD924DFF9472D054C33CD1C8.png)

安装 .ignore 插件用于管理 Git的 .gitignore 文件

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/7718718EC0634B9A80CEE5B0E57F7FBB.png)

提交代码

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/05198B0E63E540979C26B87BBF266029.png)

editorconfig

editorConfig可以帮助开发人员在不同的编辑器和IDE中定义和维护一致的编码风格。下面是laravel 项目的配置，也是大叔使用的配置。官网 https://editorconfig.org/

主流开源项目的 editorconfig 配置 https://github.com/editorconfig/editorconfig/wiki/Projects-Using-EditorConfig

在 phpstorm 插件中安装 editorconfig 插件，然后在项目根目录创建 .editorconfig 文件内容如下：

root = true[*]charset = utf-8end_of_line = lfinsert_final_newline = trueindent_style = spaceindent_size = 4trim_trailing_whitespace = true[*.md]trim_trailing_whitespace = false[*.yml]indent_style = spaceindent_size = 2

说明

indent_style设置缩进风格(tab是硬缩进，space为软缩进)indent_size用一个整数定义的列数来设置缩进的宽度，如果indent_style为tab，则此属性默认为tab_widthtab_width用一个整数来设置tab缩进的列数。默认是indent_sizeend_of_line设置换行符，值为lf、cr和crlfcharset设置编码，值为latin1、utf-8、utf-8-bom、utf-16be和utf-16le，不建议使用utf-8-bomtrim_trailing_whitespace设为true表示会去除换行行首的任意空白字符。insert_final_newline设为true表示使文件以一个空白行结尾root　　　表示是最顶层的配置文件，发现设为true时，才会停止查找.editorconfig文件   

composer

composer.json 配置文件管理，需要安装插件

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/0C17C6B60ECF47B782CC51F6012EA512.png)

其他设置

取消格式化代码时 自动换行

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/1A54C8950B10424B9F4A7F58CF57525B.png)

快速加符号

为选中字符快速添加引号或其他包裹符号。

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/76D755A034004092B1BC350C63E0E1F6.png)

解决NPM变慢的问题

生成 node_modules 目录后，加载特别慢并会卡死，解决方法如下：

![aa](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/E0DD7F598F0745D6990905BAB2E8A229.png)

自动换行

Preferences | Editor | General | Use soft wraps in editor

保存时自动格式化

下载save actions插件



![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEc799b5530c1dd4efd6407ca7a517463a截图.png)



![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE2c2c6d6461609edbd67c7c528e524473截图.png)

