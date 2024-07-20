1、linux下登录日志记录在：/var/log目录里的 secure文件。 

查看ssh用户的登录日志命令：cd /var/log && more secure

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/0709522E7F824EEAAE2F9556813D5C13clipboard.png)

上图中可以看到，用户在11:05:57和12:24:33进行了两次登录。

2、使用last命令，可以列出目前与过去登录系统的用户相关信息。这是一个功能很强大的命令。

语法：last [-R] [-num] [ -n num ] [-adiowx] [ -f file ] [ -t YYYYMMDDHHMMSS ] [name...]  [tty...]

例子：last -x ：显示系统关闭、用户登录和退出的历史

          last -i：显示特定ip登录的情况

          last -t  20150303120101： 显示20150303120101之前的登录信息