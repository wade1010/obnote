本文实例讲述了python中MySQLdb模块用法。分享给大家供大家参考。具体用法分析如下：

MySQLdb其实有点像php或asp中连接数据库的一个模式了，只是MySQLdb是针对mysql连接了接口，我们可以在python中连接MySQLdb来实现数据的各种操作。

python连接mysql的方案有oursql、PyMySQL、 myconnpy、MySQL Connector 等，不过本篇要说的确是另外一个类库MySQLdb，MySQLdb 是用于Python链接Mysql数据库的接口，它实现了 Python 数据库 API 规范 V2.0，基于 MySQL C API 上建立的。可以从：https://pypi.python.org/pypi/MySQL-python 进行获取和安装，而且很多发行版的linux源里都有该模块，可以直接通过源安装。

一、数据库连接

MySQLdb提供了connect方法用来和数据库建立连接,接收数个参数,返回连接对象：

复制代码 代码如下:

conn=MySQLdb.connect(host="localhost",user="root",passwd="jb51",db="test",charset="utf8")



比较常用的参数包括:

host:数据库主机名.默认是用本地主机

user:数据库登陆名.默认是当前用户

passwd:数据库登陆的秘密.默认为空

db:要使用的数据库名.没有默认值

port:MySQL服务使用的TCP端口.默认是3306

charset:数据库编码

更多关于参数的信息可以查这里 http://mysql-python.sourceforge.net/MySQLdb.html

然后,这个连接对象也提供了对事务操作的支持,标准的方法:

commit() 提交

rollback() 回滚

看一个简单的查询示例如下：

复制代码 代码如下:

#!/usr/bin/python

# encoding: utf-8

import MySQLdb

# 打开数据库连接

db = MySQLdb.connect("localhost","root","361way","test" )

# 使用cursor()方法获取操作游标

cursor = db.cursor()

# 使用execute方法执行SQL语句

cursor.execute("SELECT VERSION()")

# 使用 fetchone() 方法获取一条数据库。

data = cursor.fetchone()

print "Database version : %s " % data

# 关闭数据库连接

db.close()



脚本执行结果如下：

Database version : 5.5.40

二、cursor方法执行与返回值

cursor方法提供两类操作：1.执行命令,2.接收返回值 。

cursor用来执行命令的方法

复制代码 代码如下:

//用来执行存储过程,接收的参数为存储过程名和参数列表,返回值为受影响的行数

callproc(self, procname, args)

//执行单条sql语句,接收的参数为sql语句本身和使用的参数列表,返回值为受影响的行数

execute(self, query, args)

//执行单挑sql语句,但是重复执行参数列表里的参数,返回值为受影响的行数

executemany(self, query, args)

//移动到下一个结果集

nextset(self)

cursor用来接收返回值的方法

//接收全部的返回结果行.

fetchall(self)

//接收size条返回结果行.如果size的值大于返回的结果行的数量,则会返回cursor.arraysize条数据

fetchmany(self, size=None)

//返回一条结果行

fetchone(self)

//移动指针到某一行.如果mode='relative',则表示从当前所在行移动value条,如果mode='absolute',则表示从结果集的第一行移动value条

scroll(self, value, mode='relative')

//这是一个只读属性，并返回执行execute()方法后影响的行数

rowcount



三、数据库操作

1、创建database tables

如果数据库连接存在我们可以使用execute()方法来为数据库创建表，如下所示创建表EMPLOYEE：

复制代码 代码如下:

#!/usr/bin/python

# encoding: utf-8

import MySQLdb

# 打开数据库连接

db = MySQLdb.connect("localhost","root","361way","test" )

# 使用cursor()方法获取操作游标

cursor = db.cursor()

# 如果数据表已经存在使用 execute() 方法删除表。

cursor.execute("DROP TABLE IF EXISTS EMPLOYEE")

# 创建数据表SQL语句

sql = """CREATE TABLE EMPLOYEE (

         FIRST_NAME  CHAR(20) NOT NULL,

         LAST_NAME  CHAR(20),

         AGE INT,

         SEX CHAR(1),

         INCOME FLOAT )"""

cursor.execute(sql)

# 关闭数据库连接

db.close()



2、数据库插入操作

复制代码 代码如下:

#!/usr/bin/python

# encoding: utf-8

import MySQLdb

# 打开数据库连接

db = MySQLdb.connect("localhost","root","361way","test" )

# 使用cursor()方法获取操作游标

cursor = db.cursor()

# SQL 插入语句

sql = """INSERT INTO EMPLOYEE(FIRST_NAME,

         LAST_NAME, AGE, SEX, INCOME)

         VALUES ('Mac', 'Mohan', 20, 'M', 2000)"""

try:

   # 执行sql语句

   cursor.execute(sql)

   # 提交到数据库执行

   db.commit()

except:

   # Rollback in case there is any error

   db.rollback()

# 关闭数据库连接

db.close()



这里是一个单sql 执行的示例，cursor.executemany的用法感兴趣的读者可以参看相关的aws主机资产管理系统示例。

上例也可以写成通过占位符传参的方式进行执行，如下：

复制代码 代码如下:

#!/usr/bin/python

# encoding: utf-8

import MySQLdb

# 打开数据库连接

db = MySQLdb.connect("localhost","testuser","test123","TESTDB" )

# 使用cursor()方法获取操作游标

cursor = db.cursor()

# SQL 插入语句

sql = "INSERT INTO EMPLOYEE(FIRST_NAME, \

       LAST_NAME, AGE, SEX, INCOME) \

       VALUES ('%s', '%s', '%d', '%c', '%d' )" % \

       ('Mac', 'Mohan', 20, 'M', 2000)

try:

   # 执行sql语句

   cursor.execute(sql)

   # 提交到数据库执行

   db.commit()

except:

   # 发生错误时回滚

   db.rollback()

# 关闭数据库连接

db.close()



也可以以变量的方式传递参数，如下：

复制代码 代码如下:

..................................

user_id = "test"

password = "password123"

con.execute('insert into Login values("%s", "%s")' % \

             (user_id, password))

..................................



3、数据库查询操作

以查询EMPLOYEE表中salary（工资）字段大于1000的所有数据为例：

复制代码 代码如下:

#!/usr/bin/python

# encoding: utf-8

import MySQLdb

# 打开数据库连接

db = MySQLdb.connect("localhost","root","361way","test" )

# 使用cursor()方法获取操作游标

cursor = db.cursor()

# SQL 查询语句

sql = "SELECT * FROM EMPLOYEE \

       WHERE INCOME > '%d'" % (1000)

try:

   # 执行SQL语句

   cursor.execute(sql)

   # 获取所有记录列表

   results = cursor.fetchall()

   for row in results:

      fname = row[0]

      lname = row[1]

      age = row[2]

      sex = row[3]

      income = row[4]

      # 打印结果

      print "fname=%s,lname=%s,age=%d,sex=%s,income=%d" % \

             (fname, lname, age, sex, income )

except:

   print "Error: unable to fecth data"

# 关闭数据库连接

db.close()



以上脚本执行结果如下：

fname=Mac, lname=Mohan, age=20, sex=M, income=2000

4、数据库更新操作

更新操作用于更新数据表的的数据，以下实例将 test表中的 SEX 字段全部修改为 'M'，AGE 字段递增1：

复制代码 代码如下:

# encoding: utf-8

#!/usr/bin/python

import MySQLdb

# 打开数据库连接

db = MySQLdb.connect("localhost","root","361way","test" )

# 使用cursor()方法获取操作游标

cursor = db.cursor()

# SQL 更新语句

sql = "UPDATE EMPLOYEE SET AGE = AGE + 1

                          WHERE SEX = '%c'" % ('M')

try:

   # 执行SQL语句

   cursor.execute(sql)

   # 提交到数据库执行

   db.commit()

except:

   # 发生错误时回滚

   db.rollback()

# 关闭数据库连接

db.close()



5、执行事务

事务机制可以确保数据一致性。

事务应该具有4个属性：原子性、一致性、隔离性、持久性。这四个属性通常称为ACID特性。

① 原子性（atomicity）。一个事务是一个不可分割的工作单位，事务中包括的诸操作要么都做，要么都不做。

② 一致性（consistency）。事务必须是使数据库从一个一致性状态变到另一个一致性状态。一致性与原子性是密切相关的。

③ 隔离性（isolation）。一个事务的执行不能被其他事务干扰。即一个事务内部的操作及使用的数据对并发的其他事务是隔离的，并发执行的各个事务之间不能互相干扰。

④ 持久性（durability）。持续性也称永久性（permanence），指一个事务一旦提交，它对数据库中数据的改变就应该是永久性的。接下来的其他操作或故障不应该对其有任何影响。

Python DB API 2.0 的事务提供了两个方法 commit 或 rollback。实例：

复制代码 代码如下:

# SQL删除记录语句

sql = "DELETE FROM EMPLOYEE WHERE AGE > '%d'" % (20)

try:

   # 执行SQL语句

   cursor.execute(sql)

   # 向数据库提交

   db.commit()

except:

   # 发生错误时回滚

   db.rollback()



对于支持事务的数据库， 在Python数据库编程中，当游标建立之时，就自动开始了一个隐形的数据库事务。commit()方法游标的所有更新操作，rollback（）方法回滚当前游标的所有操作。每一个方法都开始了一个新的事务。

希望本文所述对大家的Python程序设计有所帮助。