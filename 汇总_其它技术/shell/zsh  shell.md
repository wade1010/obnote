昨天在 twitter 上问为什么 terminal 里显示的名称那么长。推友说是电脑名太长，把电脑名改短就 OK 了。才想起当时设置 iMac 的时候似乎也是把电脑名改了。当时同学推荐了 zsh，可以去掉那个名字，并且多色高亮似乎很不错。就决定了从 bash 转到 zsh。如果已经装了 Git，也就几个命令行的事：

1. 下载一个 .oh-my-zsh 配置（推荐有）

git clone git://github.com/robbyrussell/oh-my-zsh.git ~/.oh-my-zsh

1. 创建新配置

NOTE: 如果你已经有一个 .zshrc 文件，那么备份一下吧

cp ~/.zshrc ~/.zshrc.orig

cp ~/.oh-my-zsh/templates/zshrc.zsh-template ~/.zshrc

1. 把 zsh 设置成默认的 shell:

chsh -s /bin/zsh

1. 重启 zsh (打开一个新的 terminal 窗口)

话说，推荐一下。对于常使用 terminal 的人来说，还是很不错的。相应的 alias 在里面的定义也跟原来在 ~/.bash_profile 里面写的一样。copy 过来就可以了。更新还是用 source ~/.zshrc 这样的方法

配置别名 

|   |
| - |
| vi .zshrc |


 

|   |
| - |
| alias cls='clear'<br>alias ll='ls -l'<br>alias la='ls -a'<br>alias vi='vim'<br>alias javac="javac -J-Dfile.encoding=utf8"<br>alias grep="grep --color=auto"<br>alias -s html=mate   \# 在命令行直接输入后缀为 html 的文件名，会在 TextMate 中打开<br>alias -s rb=mate     \# 在命令行直接输入 ruby 文件，会在 TextMate 中打开<br>alias -s py=vi       \# 在命令行直接输入 python 文件，会用 vim 中打开，以下类似<br>alias -s js=vi<br>alias -s c=vi<br>alias -s java=vi<br>alias -s txt=vi<br>alias -s gz='tar -xzvf'<br>alias -s tgz='tar -xzvf'<br>alias -s zip='unzip'<br>alias -s bz2='tar -xjvf' |




autojump 插件安装：

先用brew install autojump;

autojump

autojump插件使你能够快速切换路径，再也不需要逐个敲入目录，只需敲入目标目录，就可以迅速切换目录。

- 安装

如果你是mac用户，可以使用brew安装：

[plain] view plain copy

 

![在CODE上查看代码片](https://gitee.com/hxc8/images7/raw/master/img/202407190748158.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. brew install autojump  

- 关于 zsh: command not found: j 报错

请在.zshrc文件相应的位置，找到 plugins=() 这行,添加 autojump 记得各插件名之间用英文空格隔开  然后重启item即可

[plain] view plain copy

 

![在CODE上查看代码片](https://gitee.com/hxc8/images7/raw/master/img/202407190748158.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. plugins=(git autojump)  



如果是linux用户，首先下载autojump最近版本，比如：

- [plain] view plain copy

 

![在CODE上查看代码片](https://gitee.com/hxc8/images7/raw/master/img/202407190748158.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. git clone git://github.com/joelthelion/autojump.git  

然后进入目录，执行

[plain] view plain copy

 

![在CODE上查看代码片](https://gitee.com/hxc8/images7/raw/master/img/202407190748158.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. ./install.py  

最后将以下代码加入~/.zshrc配置文件：

[plain] view plain copy

 

![在CODE上查看代码片](https://gitee.com/hxc8/images7/raw/master/img/202407190748158.jpg)

![派生到我的代码片](https://code.csdn.net/assets/ico_fork.svg)

1. [[ -s ~/.autojump/etc/profile.d/autojump.sh ]] && . ~/.autojump/etc/profile.d/autojump.sh  

- 使用

如果你之前打开过~/.oh-my-zsh/themes目录，现在只需敲入j themes就可以快速切换到~/.oh-my-zsh/themes目录。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190748538.jpg)



autojump.png



一些常用的插件

![11020.png](https://gitee.com/hxc8/images7/raw/master/img/202407190748770.jpg)





其中zsh-autosuggestions 和 zsh-syntax-highlighting 、autojump  在zsh自带的plugings下是没有的，要自己装。可以git clone到该目录，也可以别的方法安装



对于Oh-my-zsh用户

git clone https://github.com/zsh-users/zsh-syntax-highlighting.git /Users/xhcheng/.oh-my-zsh/plugins/zsh-syntax-highlighting


- 1

- 2

然后激活这个插件，通过在 

~/.zshrc 

中加入插件的名字 

plugins=( [plugins...] zsh-syntax-highlighting) 

最后当然是source一下，让改变生效

source ~/.zshrc

- 1

Note:插件可以装很多，但是装多了之后会在每次进入命令提示符的时候明显变慢。慎用。



git clone git://github.com/zsh-users/zsh-autosuggestions /Users/xhcheng/.oh-my-zsh/plugins/zsh-autosuggestions 



plugins=(git zsh-autosuggestions)



source ~/.zshrc