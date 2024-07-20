你的shell脚本有什么良好的规范



如何将标准输出和错误输出同时重定向到同一位置?

答：这里有两个方法来实现：

方法一：

2>&1 (如# ls /usr/share/doc > out.txt 2>&1 )

方法二：

&> (如# ls /usr/share/doc &> out.txt )



如何调试 Shell脚本？

- 使用 -x' 数（sh -x myscript.sh）可以调试 Shell脚本。

- 另一个种方法是使用 -nv 参数(sh -nv myscript.sh)。



在 Shell 脚本中，如何测试文件？



test 命令可以用来测试文件。基础用法如下表格：



Test         用法

-d 文件名    如果文件存在并且是目录，返回true

-e 文件名    如果文件存在，返回true

-f 文件名    如果文件存在并且是普通文件，返回true

-r 文件名    如果文件存在并可读，返回true

-s 文件名    如果文件存在并且不为空，返回true

-w 文件名    如果文件存在并可写，返回true

-x 文件名    如果文件存在并可执行，返回true



find /opt -type f -size +1G | xargs rm





shell命令运行符号&、;、&&区别

command1&command2       [2个命令同时执行 ]

command1;command2     [不管前面命令执行成功没有，后面的命令继续执行 ]

command1&&command2              [只有前面命令执行成功，后面命令才继续执行]



shell中的比较？

[ = ] 比较两个字符串是否相当

-eq 整数的比较，a>b

== >< 字符串的比较



中有哪些特殊的变量



内建变量    解释 
$0    命令行中的脚本名字
$1    第一个命令行参数
$2    第二个命令行参数
…..    …….
$9    第九个命令行参数
$#    命令行参数的数量
$*    所有命令行参数，以空格隔开



$?



答：在写一个 shell 脚本时，如果你想要检查前一命令是否执行成功，在 if 条件中使用 “$?” 可以来检查前一命令的结束状态。

简单的例子如下：

root@localhost:~# ls /usr/bin/shar
/usr/bin/shar
root@localhost:~# echo $?
0

如果结束状态是 0，说明前一个命令执行成功。

root@localhost:~# ls /usr/bin/share
ls: cannot access /usr/bin/share: No such file or directory
root@localhost:~# echo $?
2

如果结束状态不是 0，说明命令执行失败。



如何定义一个函数？

方法一：



func_name() {

    func body

    ...

}

方法二：



function func_name(){

    func body

    ...

}



如何获取一个文件每一行的第三个元素 ?

awk'{print $3}'

如何使用 awk 列出 UID 小于 100 的用户 ?

awk -F: '$3<100' /etc/passwd









& 和 && 有什么区别



& - 希望脚本在后台运行的时候使用它

&& - 当前一个脚本成功完成才执行后面的命令/脚本的时候使用它





' 和 " 引号有什么区别 ?

- ' - 当我们不希望把变量转换为值的时候使用它。

- " - 会计算所有变量的值并用值代替。







