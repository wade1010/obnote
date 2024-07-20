============================================

自己写的

![](https://gitee.com/hxc8/images8/raw/master/img/202407191057826.jpg)

INSERT INTO `resume_import_html`(

    `resumenumber`, `resumeid`, `username`, 

    `userpwd`, `url`, `urlmd5`, `html`, 

    `origin`

) 

VALUES 

    (

        '1', '2', '3', '4', '5', 'd2857bfea54911bb27e65382bbcb3c9c', 

        '7', '8'

    ) ON DUPLICATE KEY 

UPDATE 

    userpwd = 'update2records'



数据库中 d2857bfea54911bb27e65382bbcb3c9c  是已经存在的，所以执行的是更新操作

============================================

MySQL 自4.1版以后开始支持INSERT … ON DUPLICATE KEY UPDATE语法，使得原本需要执行3条SQL语句（SELECT,INSERT,UPDATE），缩减为1条语句即可完成。

INSERT ... ON DUPLICATE KEY UPDATE，当插入的记录会引发主键冲突或者违反唯一约束时，则使用UPDATE更新旧的记录，否则插入新记录。

例如ipstats表结构如下：

CREATE TABLE ipstats (ip VARCHAR(15)NOT NULLUNIQUE,clicks SMALLINT(5)UNSIGNEDNOT NULLDEFAULT'0');

原本需要执行3条SQL语句，如下：

IF(SELECT * FROM ipstats WHERE ip='192.168.0.1'){UPDATE ipstats SET clicks=clicks+1WHERE ip='192.168.0.1';} else {INSERTINTO ipstats (ip, clicks)VALUES('192.168.0.1', 1);}

而现在只需下面1条SQL语句即可完成：

INSERTINTO ipstats VALUES('192.168.0.1', 1) ON DUPLICATE KEY UPDATE clicks=clicks+1;

注意，要使用这条语句，前提条件是这个表必须有一个唯一索引或主键。

再看一例子：

mysql> desc test;

+-------+-------------+------+-----+---------+-------+

| Field | Type        | Null | Key | Default | Extra |

+-------+-------------+------+-----+---------+-------+

| uid   | int(11)     | NO   | PRI |         |       |

| uname | varchar(20) | YES |     | NULL    |       |

+-------+-------------+------+-----+---------+-------+

2 rows in set (0.00 sec)



mysql> select * from test;

+-----+--------+

| uid | uname |

+-----+--------+

|   1 | uname1 |

|   2 | uname2 |

|   3 | me     |

+-----+--------+

3 rows in set (0.00 sec)



mysql> INSERT INTO test values ( 3,'insertName' )

    -> ON DUPLICATE KEY UPDATE uname='updateName';

Query OK, 2 rows affected (0.03 sec)



mysql> select * from test;

+-----+------------+

| uid | uname      |

+-----+------------+

|   1 | uname1     |

|   2 | uname2     |

|   3 | updateName |

+-----+------------+

3 rows in set (0.00 sec)



mysql> create index i_test_uname on test(uname);

Query OK, 3 rows affected (0.20 sec)

Records: 3 Duplicates: 0 Warnings: 0



mysql> INSERT INTO test VALUES ( 1 , 'uname2')   

-> ON DUPLICATE KEY UPDATE uname='update2records';

Query OK, 2 rows affected (0.00 sec)



mysql> select * from test;

+-----+----------------+

| uid | uname          |

+-----+----------------+

|   2 | uname2         |

|   1 | update2records |

|   3 | updateName     |

+-----+----------------+

3 rows in set (0.00 sec)



插入时会与两条记录发生冲突，分别由主键和唯一索引引起。但最终只UPDATE了其中一条。这在手册中也说明了，有多个唯一索引（或者有键也有唯一索引）的情况下，不建议使用该语句。

create table xx (sad,xasd,asda,primary key(a,x,a));就可以用了,注意一定要有由主键和唯一索引^_^