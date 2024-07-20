= 水平分区（根据列属性按行分）=

举个简单例子：一个包含十年发票记录的表可以被分区为十个不同的分区，每个分区包含的是其中一年的记录。

水平分区的模式：

1. Range（范围） – 这种模式允许DBA将数据划分不同范围。例如DBA可以将一个表通过年份划分成三个分区，80年代（1980's）的数据，90年代（1990's）的数据以及任何在2000年（包括2000年）后的数据。 

1. Hash（哈希）  – 这种模式允许DBA通过对表的一个或多个列的Hash Key进行计算，最后通过这个Hash码不同数值对应的数据区域进行分区。例如DBA可以建立一个对表主键进行分区的表。 

1. Key（键值）    – Hash模式的一种延伸，这里的Hash Key是MySQL系统产生的。 

1. List（预定义列表） – 这种模式允许系统通过DBA定义的列表的值所对应的行数据进行分割。例如：DBA建立了一个横跨三个分区的表，分别根据2004年2005年和2006年值所对应的数据。 

1. Composite（复合模式） - 很神秘吧，哈哈，其实是以上模式的组合使用而已，就不解释了。举例：在初始化已经进行了Range范围分区的表上，我们可以对其中一个分区再进行hash哈希分区。 

 

 垂直分区（按列分）：

       举个简单例子：一个包含了大text和BLOB列的表，这些text和BLOB列又不经常被访问，这时候就要把这些不经常使用的text和BLOB了划分到另一个分区，在保证它们数据相关性的同时还能提高访问速度。

 

分区表和未分区表试验过程

      *创建分区表,按日期的年份拆分

mysql> CREATE TABLE part_tab (

 c1 int default NULL, 

 c2 varchar(30) default NULL, 

 c3 date default NULL

) engine=myisam 
PARTITION BY RANGE (year(c3)) (PARTITION p0 VALUES LESS THAN (1995),
PARTITION p1 VALUES LESS THAN (1996) , PARTITION p2 VALUES LESS THAN (1997) ,
PARTITION p3 VALUES LESS THAN (1998) , PARTITION p4 VALUES LESS THAN (1999) ,
PARTITION p5 VALUES LESS THAN (2000) , PARTITION p6 VALUES LESS THAN (2001) ,
PARTITION p7 VALUES LESS THAN (2002) , PARTITION p8 VALUES LESS THAN (2003) ,
PARTITION p9 VALUES LESS THAN (2004) , PARTITION p10 VALUES LESS THAN (2010),
PARTITION p11 VALUES LESS THAN MAXVALUE );

    注意最后一行，考虑到可能的最大值

   *创建未分区表

mysql> create table no_part_tab (

        c1 int(11) default NULL,

        c2 varchar(30) default NULL,

        c3 date default NULL

       ) engine=myisam;

   *通过存储过程灌入800万条测试数据

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql> set sql_mode=''; /* 如果创建存储过程失败，则先需设置此变量, bug? */
mysql> delimiter //     /* 设定语句终结符为 //，因存储过程语句用;结束 */



  mysql> CREATE PROCEDURE load_part_tab()

  begin

   declare v int default 0;

   while v < 8000000

  do

   insert into part_tab

   values (v,'testing partitions',adddate('1995-01-01',(rand(v)*36520) mod 3652));

  set v = v + 1;

  end while;

  end

  //

  mysql> delimiter ;

  mysql> call load_part_tab();

  Query OK, 1 row affected (8 min 17.75 sec)

  mysql> insert into no_part_tab select * from part_tab;      //将800万数据复制到未分区的表no_part_tab 中

  Query OK, 8000000 rows affected (51.59 sec)

  Records: 8000000 Duplicates: 0 Warnings: 0

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

    * 测试SQL性能

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql> select count(*) from part_tab where c3 > date（'1995-01-01'） and c3 < date（'1995-12-31'）;

+----------+

| count(*) |

+----------+

|   795181 |

+----------+

  1 row in set (0.55 sec)

mysql> select count(*) from no_part_tab where c3 > date（'1995-01-01'） and c3 < date（'1995-12-31'）; 

+----------+

| count(*) |

+----------+

|   795181 |

+----------+

1 row in set (4.69 sec)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   结果表明分区表比未分区表的执行时间少90%。

  * 通过explain语句来分析执行情况

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql > explain select count(*) from no_part_tab where c3 > date('1995-01-01') and c3 < date ('1995-12-31') \G    #结尾的\G使得mysql的输出改为列模式 

  *************************** 1. row ***************************

           id: 1

  select_type: SIMPLE

        table: no_part_tab

         type: ALL

possible_keys: NULL

          key: NULL

      key_len: NULL

          ref: NULL

         rows: 8000000               #需要查询800万条记录

        Extra: Using where

  1 row in set (0.00 sec)

  mysql> explain select count(*) from part_tab where c3 > date ('1995-01-01') and c3 < date ('1995-12-31') \G

  *************************** 1. row ***************************

           id: 1

  select_type: SIMPLE

        table: part_tab

         type: ALL

possible_keys: NULL

          key: NULL

      key_len: NULL

          ref: NULL

         rows: 798458               #只需要查询798458条记录

        Extra: Using where

  1 row in set (0.00 sec)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

    * 试验创建索引后情况

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql> create index idx_of_c3 on no_part_tab (c3);

Query OK, 8000000 rows affected (1 min 18.08 sec)

Records: 8000000 Duplicates: 0 Warnings: 0



mysql> create index idx_of_c3 on part_tab (c3);

Query OK, 8000000 rows affected (1 min 19.19 sec)

Records: 8000000 Duplicates: 0 Warnings: 0

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   创建索引后的数据库文件大小列表：

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

2008-05-24 09:23             8,608 no_part_tab.frm
2008-05-24 09:24       255,999,996 no_part_tab.MYD
2008-05-24 09:24        81,611,776 no_part_tab.MYI
2008-05-24 09:25                 0 part_tab#P#p0.MYD
2008-05-24 09:26             1,024 part_tab#P#p0.MYI
2008-05-24 09:26        25,550,656 part_tab#P#p1.MYD
2008-05-24 09:26         8,148,992 part_tab#P#p1.MYI
2008-05-24 09:26        25,620,192 part_tab#P#p10.MYD
2008-05-24 09:26         8,170,496 part_tab#P#p10.MYI
2008-05-24 09:25                 0 part_tab#P#p11.MYD
2008-05-24 09:26             1,024 part_tab#P#p11.MYI
2008-05-24 09:26        25,656,512 part_tab#P#p2.MYD
2008-05-24 09:26         8,181,760 part_tab#P#p2.MYI
2008-05-24 09:26        25,586,880 part_tab#P#p3.MYD
2008-05-24 09:26         8,160,256 part_tab#P#p3.MYI
2008-05-24 09:26        25,585,696 part_tab#P#p4.MYD
2008-05-24 09:26         8,159,232 part_tab#P#p4.MYI
2008-05-24 09:26        25,585,216 part_tab#P#p5.MYD
2008-05-24 09:26         8,159,232 part_tab#P#p5.MYI
2008-05-24 09:26        25,655,740 part_tab#P#p6.MYD
2008-05-24 09:26         8,181,760 part_tab#P#p6.MYI
2008-05-24 09:26        25,586,528 part_tab#P#p7.MYD
2008-05-24 09:26         8,160,256 part_tab#P#p7.MYI
2008-05-24 09:26        25,586,752 part_tab#P#p8.MYD
2008-05-24 09:26         8,160,256 part_tab#P#p8.MYI
2008-05-24 09:26        25,585,824 part_tab#P#p9.MYD
2008-05-24 09:26         8,159,232 part_tab#P#p9.MYI
2008-05-24 09:25             8,608 part_tab.frm
2008-05-24 09:25                68 part_tab.par

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   * 再次测试SQL性能

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql> select count(*) from no_part_tab where c3 > date ('1995-01-01') and c3 < date ('1995-12-31');

+----------+

| count(*) |

+----------+

|   795181 |

+----------+

  1 row in set (2.42 sec)   # 为原来4.69 sec 的51%

  #重启mysql ( net stop mysql, net start mysql)后，查询时间降为0.89 sec,几乎与分区表相同。

 

  mysql> select count(*) from part_tab where c3 > date ('1995-01-01') and c3 < date ('1995-12-31');

  +----------+

  | count(*) |

  +----------+

  |   795181 |

  +----------+

  1 row in set (0.86 sec)

 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   * 更进一步的试验

    ** 增加日期范围

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql> select count(*) from no_part_tab where c3 > date ('1995-01-01') and c3 < date ('1997-12-31');

+----------+

| count(*) |

+----------+

| 2396524 |

+----------+

1 row in set (5.42 sec)



mysql> select count(*) from part_tab where c3 > date ('1995-01-01') and c3 < date ('1997-12-31');

+----------+

| count(*) |

+----------+

| 2396524 |

+----------+

  1 row in set (2.63 sec)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

    ** 增加未索引字段查询

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql> select count(*) from no_part_tab where c3 > date ('1995-01-01') and c3 < date ('1996-12-31') and c2='hello';

+----------+

| count(*) |

+----------+

|        0 |

+----------+

1 row in set (11.52 sec)



mysql> select count(*) from part_tab where c3 > date ('1995-01-01') and c3 < date ('1996-12-31') and c2='hello';

+----------+

| count(*) |

+----------+

|        0 |

+----------+

1 row in set (0.75 sec)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   = 初步结论 =

      * 分区和未分区占用文件空间大致相同 （数据和索引文件）

      * 如果查询语句中有未建立索引字段，分区时间远远优于未分区时间

      * 如果查询语句中字段建立了索引，分区和未分区的差别缩小，分区略优于未分区。

= 最终结论 =

  * 对于大数据量，建议使用分区功能。

  * 去除不必要的字段

  * 根据手册， 增加myisam_max_sort_file_size 会增加分区性能( mysql重建索引时允许使用的临时文件最大大小)

 

分区命令详解

   * RANGE 类型

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

CREATE TABLE users (
       uid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(30) NOT NULL DEFAULT '',
       email VARCHAR(30) NOT NULL DEFAULT ''
)
PARTITION BY RANGE (uid) (
       PARTITION p0 VALUES LESS THAN (3000000)
       DATA DIRECTORY = '/data0/data'
       INDEX DIRECTORY = '/data1/idx',
 
       PARTITION p1 VALUES LESS THAN (6000000)
       DATA DIRECTORY = '/data2/data'
       INDEX DIRECTORY = '/data3/idx',
 
       PARTITION p2 VALUES LESS THAN (9000000)
       DATA DIRECTORY = '/data4/data'
       INDEX DIRECTORY = '/data5/idx',
 
       PARTITION p3 VALUES LESS THAN MAXVALUE     DATA DIRECTORY = '/data6/data' 
       INDEX DIRECTORY = '/data7/idx'
);

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   在这里，将用户表分成4个分区，以每300万条记录为界限，每个分区都有自己独立的数据、索引文件的存放目录，与此同时，这些目录所在的物理磁盘分区可能也都是完全独立的，可以提高磁盘IO吞吐量。

   * LIST 类型

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

CREATE TABLE category (
     cid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(30) NOT NULL DEFAULT ''
)
PARTITION BY LIST (cid) (
     PARTITION p0 VALUES IN (0,4,8,12)
     DATA DIRECTORY = '/data0/data' 
     INDEX DIRECTORY = '/data1/idx',
     
     PARTITION p1 VALUES IN (1,5,9,13)
     DATA DIRECTORY = '/data2/data'
     INDEX DIRECTORY = '/data3/idx',
     
     PARTITION p2 VALUES IN (2,6,10,14)
     DATA DIRECTORY = '/data4/data'
     INDEX DIRECTORY = '/data5/idx',
     
     PARTITION p3 VALUES IN (3,7,11,15)
     DATA DIRECTORY = '/data6/data'
     INDEX DIRECTORY = '/data7/idx'
);

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   分成4个区，数据文件和索引文件单独存放。

   * HASH 类型

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

CREATE TABLE users (
     uid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(30) NOT NULL DEFAULT '',
     email VARCHAR(30) NOT NULL DEFAULT ''
)
PARTITION BY HASH (uid) PARTITIONS 4 (
     PARTITION p0
     DATA DIRECTORY = '/data0/data'
     INDEX DIRECTORY = '/data1/idx',
 
     PARTITION p1
     DATA DIRECTORY = '/data2/data'
     INDEX DIRECTORY = '/data3/idx',
 
     PARTITION p2
     DATA DIRECTORY = '/data4/data'
     INDEX DIRECTORY = '/data5/idx',
 
     PARTITION p3
     DATA DIRECTORY = '/data6/data'
     INDEX DIRECTORY = '/data7/idx'
);

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   * KEY 类型

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

CREATE TABLE users (
     uid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(30) NOT NULL DEFAULT '',
     email VARCHAR(30) NOT NULL DEFAULT ''
)
PARTITION BY KEY (uid) PARTITIONS 4 (
     PARTITION p0
     DATA DIRECTORY = '/data0/data'
     INDEX DIRECTORY = '/data1/idx',
     
     PARTITION p1
     DATA DIRECTORY = '/data2/data' 
     INDEX DIRECTORY = '/data3/idx',
     
     PARTITION p2 
     DATA DIRECTORY = '/data4/data'
     INDEX DIRECTORY = '/data5/idx',
     
     PARTITION p3 
     DATA DIRECTORY = '/data6/data'
     INDEX DIRECTORY = '/data7/idx'
);

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

   分成4个区，数据文件和索引文件单独存放。

   * 子分区

     子分区是针对 RANGE/LIST 类型的分区表中每个分区的再次分割。再次分割可以是 HASH/KEY 等类型。

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

CREATE TABLE users (
     uid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(30) NOT NULL DEFAULT '',
     email VARCHAR(30) NOT NULL DEFAULT ''
)
PARTITION BY RANGE (uid) SUBPARTITION BY HASH (uid % 4) SUBPARTITIONS 2(
     PARTITION p0 VALUES LESS THAN (3000000)
     DATA DIRECTORY = '/data0/data'
     INDEX DIRECTORY = '/data1/idx',
 
     PARTITION p1 VALUES LESS THAN (6000000)
     DATA DIRECTORY = '/data2/data'
     INDEX DIRECTORY = '/data3/idx'
);

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

    对 RANGE 分区再次进行子分区划分，子分区采用 HASH 类型。

    或者

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

CREATE TABLE users (
     uid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(30) NOT NULL DEFAULT '',
     email VARCHAR(30) NOT NULL DEFAULT ''
)
PARTITION BY RANGE (uid) SUBPARTITION BY KEY(uid) SUBPARTITIONS 2(
     PARTITION p0 VALUES LESS THAN (3000000)
     DATA DIRECTORY = '/data0/data'
     INDEX DIRECTORY = '/data1/idx',
 
     PARTITION p1 VALUES LESS THAN (6000000)
     DATA DIRECTORY = '/data2/data'
     INDEX DIRECTORY = '/data3/idx'
);

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

    对 RANGE 分区再次进行子分区划分，子分区采用 KEY 类型。

分区管理

   * 删除分区  

ALERT TABLE users DROP PARTITION p0; #删除分区 p0

   * 重建分区

       RANGE 分区重建

ALTER TABLE users REORGANIZE PARTITION p0,p1 INTO (PARTITION p0 VALUES LESS THAN (6000000));  #将原来的 p0,p1 分区合并起来，放到新的 p0 分区中。

        LIST 分区重建

ALTER TABLE users REORGANIZE PARTITION p0,p1 INTO (PARTITION p0 VALUES IN(0,1,4,5,8,9,12,13));#将原来的 p0,p1 分区合并起来，放到新的 p0 分区中。

        HASH/KEY 分区重建

ALTER TABLE users REORGANIZE PARTITION COALESCE PARTITION 2; #用 REORGANIZE 方式重建分区的数量变成2，在这里数量只能减少不能增加。想要增加可以用 ADD PARTITION 方法。

   * 新增分区

        新增 RANGE 分区   

#新增一个RANGE分区

ALTER TABLE category ADD PARTITION (PARTITION p4 VALUES IN (16,17,18,19)
            DATA DIRECTORY = '/data8/data'
            INDEX DIRECTORY = '/data9/idx');

       新增 HASH/KEY 分区

ALTER TABLE users ADD PARTITION PARTITIONS 8;   #将分区总数扩展到8个。

       给已有的表加上分区

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

alter table results partition by RANGE (month(ttime)) 
(

PARTITION p0 VALUES LESS THAN (1),
PARTITION p1 VALUES LESS THAN (2) , 

PARTITION p2 VALUES LESS THAN (3) ,
PARTITION p3 VALUES LESS THAN (4) , 

PARTITION p4 VALUES LESS THAN (5) ,
PARTITION p5 VALUES LESS THAN (6) , 

PARTITION p6 VALUES LESS THAN (7) ,
PARTITION p7 VALUES LESS THAN (8) , 

PARTITION p8 VALUES LESS THAN (9) ,
PARTITION p9 VALUES LESS THAN (10) , 

PARTITION p10 VALUES LESS THAN (11),
PARTITION p11 VALUES LESS THAN (12),
PARTITION P12 VALUES LESS THAN (13) 

);

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

默认分区限制分区字段必须是主键（PRIMARY KEY)的一部分，为了去除此限制:

  [方法1] 使用ID:

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql> ALTER TABLE np_pk
    ->     PARTITION BY HASH( TO_DAYS(added) )
    ->     PARTITIONS 4;

#ERROR 1503 (HY000): A PRIMARY KEY must include all columns in the table's partitioning function



mysql> ALTER TABLE np_pk
    ->     PARTITION BY HASH(id)
    ->     PARTITIONS 4;

Query OK, 0 rows affected (0.11 sec)

Records: 0 Duplicates: 0 Warnings: 0

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

  [方法2] 将原有PK去掉生成新PK

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

mysql> alter table results drop PRIMARY KEY;

Query OK, 5374850 rows affected (7 min 4.05 sec)

Records: 5374850 Duplicates: 0 Warnings: 0



mysql> alter table results add PRIMARY KEY(id, ttime);

Query OK, 5374850 rows affected (7 min 4.05 sec)

Records: 5374850 Duplicates: 0 Warnings: 0

![](https://gitee.com/hxc8/images8/raw/master/img/202407191056479.jpg)

