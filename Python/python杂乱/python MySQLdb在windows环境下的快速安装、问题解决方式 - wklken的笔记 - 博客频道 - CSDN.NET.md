使用python访问mysql，需要一系列安装



linux下MySQLdb安装见  

Python MySQLdb在Linux下的快速安装

http://blog.csdn.net/wklken/article/details/7271019



-------------------------------------------------------------

以下是windows环境下的：



1.      安装数据库mysql

下载地址：http://www.mysql.com/downloads/

可以顺带装个图形工具，我用的是MySQL-Front

2.      安装MySQLdb

好了，到了这一步，你有两个选择

A.     安装已编译好的版本（一分钟）

B.     从官网下，自己编译安装（介个…..半小时到半天不等，取决于你的系统环境以及RP）

若是系统32位的，有c++编译环境的，自认为RP不错的，可以选择自己编译安装，当然，遇到问题还是难免的，一步步搞还是能搞出来的

若是系统64位的，啥都木有的，建议下编译版本的，甭折腾

2.1安装已编译版本：

http://www.codegood.com/downloads

根据自己系统下载，双击安装，搞定

然后import MySQLdb，查看是否成功

我的，win7,64位，2.7版本

MySQL-python-1.2.3.win-amd64-py2.7.exe

2.2自己编译安装

话说搞现成的和自己编译差距不一一点半点的，特别是64位win7，搞死了

2.2.1安装setuptools

在安装MySQLdb之前必须安装setuptools，要不然会出现编译错误

http://pypi.python.org/pypi/setuptools

http://peak.telecommunity.com/dist/ez_setup.py 使用这个安装（64位系统必须用这个）

2.2.2安装MySQLdb

下载MySQLdb

http://sourceforge.net/projects/mysql-python/

解压后，cmd进入对应文件夹

如果32位系统且有gcc编译环境，直接

python setup.py build

2.2.3问题汇总

A. 64位系统，无法读取注册表的问题

异常信息如下：

F:\devtools\MySQL-python-1.2.3>pythonsetup.py build

Traceback (most recent call last):

 File "setup.py", line 15, in

   metadata, options = get_config()

 File "F:\devtools\MySQL-python-1.2.3\setup_windows.py", line7, in get_config

   serverKey = _winreg.OpenKey(_winreg.HKEY_LOCAL_MACHINE, options[' registry_ke

y'] )

WindowsError: [Error 2] The system cannotfind the file specified

解决方法：

其实分析代码，发现只是寻找mysql的安装地址而已  修改setup_windows.py如下

注解两行，加入一行，为第一步mysql的安装位置

   #serverKey = _winreg.OpenKey(_winreg.HKEY_LOCAL_MACHINE,options['registry_key'] )

   #mysql_root, dummy = _winreg.QueryValueEx(serverKey,'Location')

   mysql_root = r"F:\devtools\MySQL\MySQL Server 5.5"

B.没有gcc编译环境

unable to find vcvarsall.bat

解决方法：安装编译环境（一个老外的帖子）

1)  First ofall download MinGW. Youneed g++compiler and MingW make in setup.

2)  If youinstalled MinGW for example to “C:\MinGW” then add “C:\MinGW\bin”to your PATH in Windows.（安装路径加入环境变量）

3)  Now startyour Command Prompt and go the directory where you have your setup.py residing.

4)  Last andmost important step:

setup.py install build --compiler=mingw32

或者在setup.cfg中加入：

[build]

    compiler = mingw32

C.gcc: /Zl: No suchfile or directory错误

异常信息如下

F:\devtools\MinGW\bin\gcc.exe -mno-cygwin-mdll -O -Wall -Dversion_info=(1,2,3,'

final',0) -D__version__=1.2.3"-IF:\devtools\MySQL\MySQL Server 5.5\include" -IC

:\Python27\include -IC:\Python27\PC -c_mysql.c -o build\temp.win-amd64-2.7\Rele

ase\_mysql.o /Zl

gcc: error: /Zl: No such file or directory

error: command 'gcc' failed with exitstatus 1

参数是vc特有的编译参数，如果使用mingw的话因为是gcc所以不支持。可以在setup_windows.py中去掉

/Zl  

解决方法：

修改setup_windows.py  改为空的

#extra_compile_args = [ '/Zl' ]

    extra_compile_args = [ '' ]

 目前就遇到这几个问题，望补充

3.  增删改查代码示例及结果(just for test)

[sql] view plain copy print ?

1. CREATETABLE `user` (  

1.   `Id` int(11) NOTNULL AUTO_INCREMENT,  

1.   `name` varchar(255) DEFAULTNULL,  

1.   `age` varchar(255) DEFAULTNULL,  

1. PRIMARYKEY (`Id`)  

1. ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;  



[python] view plain copy print ?

1. #-*- coding:utf-8 -*-

1. #dbtest.py

1. #just used for a mysql test

1. '''''

1. Created on 2012-2-12

1. @author: ken

1. '''

1. #mysqldb    

1. import time, MySQLdb, sys    

1. #connect 

1. conn=MySQLdb.connect(host="localhost",user="root",passwd="test_pwd",db="school",charset="utf8")    

1. cursor = conn.cursor()      

1. #add

1. sql = "insert into user(name,age) values(%s,%s)"

1. param = ("tom",str(20))      

1. n = cursor.execute(sql,param)      

1. print n      

1. #更新    

1. sql = "update user set name=%s where Id=9001"

1. param = ("ken")      

1. n = cursor.execute(sql,param)      

1. print n      

1. #查询    

1. n = cursor.execute("select * from user")      

1. for row in cursor.fetchall():      

1. for r in row:      

1. print r,     

1. print ""  

1. #删除    

1. sql = "delete from user where name=%s"

1. param =("ted")      

1. n = cursor.execute(sql,param)      

1. print n      

1. cursor.close()      

1. #关闭    

1. conn.close()  



