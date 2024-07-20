简介

在使用QtCreator开发过程中，保持组内一致的代码风格，非常重要。但是很多人编写程序时不注意程序的版式结构，往往很难保持一致。代码自动格式化，把代码风格生成配置文件大家一起使用，不仅使代码整洁易读，更加清晰易懂，还能保持组内代码风格一致。

一、Qt Creator安装插件Beautiful

菜单栏打开 帮助->关于插件，找到C++下面的Beautiful，勾选上，然后重启Qt。

二、下载AStyle

astyle 官网下载： [https://sourceforge.net/projects/astyle/](https://sourceforge.net/projects/astyle/)

其他风格： Google 开源项目 c/c++风格

1、菜单栏打开 工具->选项，选择Beautiful，General页签，我们选择Artistic Style

2、点击Artistic Style页签，选择我们下载的AStyle.exe程序，

然后点击Edit，添加我们的样式，然后保存。

我这里设置样式如下所示，仅供参考。更多的设置请参考Artistic

```
style=linux # 设置 Linux 风格
indent-switches # 设置 switch 整体缩进
indent-namespaces # 设置 namespace 整体缩进
indent-preproc-block # 设置预处理模块缩进
pad-oper # 操作符前后填充空格
delete-empty-lines # 删除多余空行
add-braces # 单行语句加上大括号
indent=spaces=4 # 4空格缩进
indent-cases # 设置cases整体缩进
unpad-paren # 移除括号两端多余空格
indent-labels
pad-header
keep-one-line-statements
convert-tabs
indent-preprocessor
align-pointer=name
align-reference=name
keep-one-line-blocks
attach-namespaces
max-instatement-indent=120
```

————————————————

版权声明：本文为CSDN博主「吴小白白白」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/zuoweijie_/article/details/128049815](https://blog.csdn.net/zuoweijie_/article/details/128049815)