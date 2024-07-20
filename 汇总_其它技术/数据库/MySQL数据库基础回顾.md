PHP移动互联网开发笔记（6）——MySQL数据库基础回顾[1] 来源：CSDN   时间：2014-03-26 13:47:36   阅读数：91690

分享到： 0

[导读] 一、数据类型1、整型数据类型存储空间说明取值范围TINYINT1字节非常小的整数带符号值：-128~127无符号值：0~255SMALLINT2字节较小的整数带符号值：-32768~32767无符号值：0~65535MEDIUMNT3字节中等大小的整数带符

一、数据类型

1、整型

| 数据类型 | 存储空间 | 说明 | 取值范围 |
| - | - | - | - |
| TINYINT | 1字节 | 非常小的整数 | 带符号值：-128~127<br>无符号值：0~255 |
| SMALLINT | 2字节 | 较小的整数 | 带符号值：-32768~32767<br>无符号值：0~65535 |
| MEDIUMNT | 3字节 | 中等大小的整数 | 带符号值：-8388608~8388607<br>无符号值：0~16777215 |
| INT | 4字节 | 标准整数 | 带符号值：-2147483648~2147483647<br>无符号值：0~4294967295 |
| BIGINT | 8字节 | 大整数 |  |




2、浮点型

| 数据类型 | 存储空间 | 说明 | 取值范围 |
| - | - | - | - |
| FLOAT | 4字节 | 单精度浮点数 |  |
| DOUBLE | 8字节 | 双精度浮点数 |  |
| DECIMAL(M,D) | 自定义 | 以字符串形式表示 |  |




3、字符串类型

| 类型 | 存储空间 | 说明 | 最大长度 |
| - | - | - | - |
| Char[(M)] | M字节 | 定长字符串 | M字节 |
| Varchar[(M)] | L+1字节 | 可变长字符串 | M字节 |
| tinyblog,tingtext | L+1字节 | 非常小的blob和文本串 | 2^8字符 |
| blog,text | L+2字节 | 小BLOB和文本串 | 2^16-1字节 |
| mediumblob,mediumtext | L+3字节 | 中等的BLOB和文本串 | 2^24字节 |
| longblob,longtext | L+4字节 | 大BLOB和文本串 | 2^32-1字节 |
| enum('value','value') | 1或2字节 | 枚举：可赋予某个枚举成员 | 65535个成员 |
| set('value', 'value') | 1,2,3,4或8字节 | 集合：可赋予多个集合成员 | 64个成员 |




4、日期和时间型数据

| 类型 | 存储空间 | 说明 | 最大长度 |
| - | - | - | - |
| Date | 3字节 | YYYY-MM-DD格式表示 | 1000-01-01~9999-12-31 |
| TIME | 3字节 | hh:mm:ss格式表示时间值 | -838:59:59~838:59:59 |
| DATETIME | 8字节 | YYYY-MM-DD  hh:mm:ss格式 |  |
| TIMESTAMP | 4字节 | YYYYMMDDhhmmss格式表示时间戳 |  |
| YEAR | 1字节 | YYYY格式的年份值 | 1901~2155 |




二、MySQL数据库的操作

1、登录数据库

mysql 参数

-D,--database=name 打开指定数据库

--delimiter=name 指定分隔符

-E,--vertical 垂直显示结果

-h,--host=name 服务器名称

-H,--html 提供HTML输出

-X,--xml 提供XML输出

-p,--password[=name]密码

-P,--port=# 端口号

--prompt=name 设置提示符

-u,--user=name 用户名

-V，--version 输出版本信息并退出

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058393.jpg)

mysql -h 服务器主机地址 -u 用户名 -p 用户密码

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058375.jpg)

2、退出登录

exit

quit

\q

3、修改密码

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058447.jpg)

4、创建选择及查看数据库

创建数据库

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058486.jpg)

选择数据库

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058525.jpg)

删除数据库中的内容

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058626.jpg)

