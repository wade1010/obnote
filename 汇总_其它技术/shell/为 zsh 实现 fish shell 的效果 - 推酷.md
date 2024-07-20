fish shell

很久以前就见过 fish shell ，很多人见到这两张图就会想去试一下：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190748720.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190748857.jpg)

fish shell 炫酷在哪？

主要就是这两张图中的两个功能：智能提示 和 语法高亮。

为此我也试用过多次 fish shell ，但是每次都败了，因为还是有很多地方不习惯：

- 无插件系统，功能上还是比 oh-my-zsh 少了很多

- 不兼容 bash 语法，导致我之前的很多脚本无法运行

oh-my-zsh 才是我的真爱！

那么问题来了， oh-my-zsh 中有没有插件可以实现类似的功能？

答案是肯定的！

zsh-users

我先是在 oh-my-zsh 官方插件库里找了一下，但是没找到，后来发现了这样一个项目：

zsh-users

上面的介绍说是：Zsh community projects，感觉是非官方的项目。

里面有两个插件：

- zsh-autosuggestions

- zsh-syntax-highlighting

安装起来非常简单， clone 到 $ZSH_CUSTOM/plugins 目录，然后在 .zshrc 文件正配置一下即可。

最终效果图如下：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190748751.jpg)

途中可以看到 git 是绿色的，代表存在这个命令，如果打错了，它就是红色的：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190748702.jpg)

一目了然，不用等出错了再去修正错误了。