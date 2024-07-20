最近一直在折腾ubuntu下的svn，命令行下的svn还真是没用过。在创建版本库时遇到了点问题，特此写出来备忘一下：





创建版本库：

终端输入：svnadmin create [路径]

该路径可以是相对路径，例如，当前在home/user/svn/目录下，输入svnadmin create myproject，则该版本库会被创建在home/user/svn/目录下，新建一个文件夹名为myproject，其中便是版本库的文件了。





将文件导入版本库：

终端输入：svn import [源路径] [目标版本库路径] -m [日志信息]

源路径可以是相对路径，导入时会递归导入源路径下的所有文件和文件夹，目标版本库路径需要绝对目录（反正我试验是这样的），例如版本库的目录是：home/user/svn/myproject/。则应该这样写：file:///home/user/svn/myproject/

例如将当前目录导入版本库myproject：

svn import . file:///home/user/svn/myproject -m "导入文件"





从版本库导出：

导入后原文件并未被纳入版本管理，若想获得受版本控制的文件，就需要从版本库导出

终端输入：svn co [版本库路径] [导出目标路径]

例如将myproject库中的文件导出到当前目录：svn co file:///home/user/svn/myproject .