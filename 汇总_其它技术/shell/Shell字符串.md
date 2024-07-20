单引号

1. str='this is a string'

单引号字符串的限制：

- 单引号里的任何字符都会原样输出，单引号字符串中的变量是无效的；

- 单引号字串中不能出现单引号（对单引号使用转义符后也不行）。

双引号

1. your_name='qinjx'

1. str="Hello, I know your are \"$your_name\"! \n"

双引号的优点：

- 双引号里可以有变量

- 双引号里可以出现转义字符

拼接字符串

1. your_name="qinjx"

1. greeting="hello, "$your_name" !"

1. greeting_1="hello, ${your_name} !"

1. echo $greeting $greeting_1

获取字符串长度

1. string="abcd"

1. echo ${#string} #输出 4

提取子字符串

1. string="alibaba is a great company"

1. echo ${string:1:4} #输出liba

查找子字符串

1. string="alibaba is a great company"

1. echo `expr index "$string" is`