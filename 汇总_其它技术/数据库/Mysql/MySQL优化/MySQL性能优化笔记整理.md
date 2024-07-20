版本  mysql-5.5.35





一.测试篇

1.测试目的,就是量化找出短板(基础参数配置)


2.测试三大指标
IOPS:每秒处理的IO请求数，即IO响应速度（注意和IO吞吐量的区别）
QPS:每秒请求（查询）次数
TPS:每秒事务数

3.测试工具
mysqlslap(略)
sysbench
tpcc-mysql 

二.mysql性能调优的思路

1.确定问题范围
a.是周期性的变化还是偶尔问题?
b.是服务器整体性能的问题, 还是某单条语句的问题?
c.具体到单条语句, 这条语句是在等待上花的时间,还是查询上花的时间.

2.定位问题手段
a.通过 mysqladmin 判断服务器整体系统负载
b.通过 show processlist/或慢查询日志 观察长时间运行语句状态
c.通过 profile + explan 定位单个语句慢的位置


3.值得注意的mysql线程状态:

[mysql@mysql01 ~]$ mysql -uroot -p123456 -e 'show processlist\G'|grep State:|sort|uniq


converting HEAP to MyISAM      查询结果太大时,把结果放在磁盘
create tmp table               创建临时表(如group时储存中间结果)
Copying to tmp table on disk   把内存临时表复制到磁盘
locked                         被其他查询锁住
注：通过mysqladmin+debug确定locked的详细信息 

三.临时表的使用规则

    在处理请求的某些场景中,服务器创建内部临时表. 即表以MEMORY引擎在内存中处理,或以MyISAM引擎储存在磁盘上处理.如果表过大,服务器可能会把内存中的临时表转存在磁盘上.用户不能直接控制服务器内部用内存还是磁盘存储临时表

--什么情况下产生内存临时表?
1: group by 的列和order by 的列不同时
2: 表联查时,取A表的内容,group/order by另外表的列
3: distinct 和 order by 一起使用时
4: 开启了 SQL_SMALL_RESULT 选项
5：union合并查询时会用到临时表

--什么情况下临时表写到磁盘上?
1:取出的列含有text/blob类型时 ---内存表储存不了text/blob类型
2:在group by 或distinct的列中存在>512字节的string列
3:select 中含有>512字节的string列,同时又使用了union或union all语句

四.表的优化与列类型选择

表的优化
1:定长与变长分离
    核心且常用字段,宜建成定长,放在一张表.如 id int, 占4个字节, char(4) 占4个字符长度,time 
  而varchar, text,blob,这种变长字段,适合单放一张表, 用主键与核心表关联起来.
2:常用字段和不常用字段要分离.
  需要结合网站具体的业务来分析,分析字段的查询场景,查询频度低的字段,单拆出来.
3:合理添加冗余字段

列选择原则
1:字段类型优先级 整型 > date,time > enum,char>varchar > blob
    整型: 定长,没有国家/地区之分,没有字符集的差异
    time：定长,运算快,节省空间. （All date and time columns shall be INT UNSIGNED NOT NULL, and shall store a Unix timestamp in UTC）
    enum: 能起来约束值的目的, 内部用整型来存储,但与char联查时,内部要经历串与值的转化
    Char：定长, 考虑字符集和(排序)校对集
    varchar, 不定长 要考虑字符集的转换与排序时的校对集,速度慢.
    text/Blob 无法使用内存临时表
2:够用就行,不要慷慨 
  大的字段浪费内存,影响速度
3:尽量避免用NULL()
  NULL不利于索引,要用特殊的字节来标注.
  索引文件在磁盘上占据的空间其实更大.（mysql5.5里,关于null已经做了优化,大小区别已不明显）

Enum列的说明
1: enum列在内部是用整型来储存的
2: enum列与enum列相关联速度最快
3: enum列比(var)char 的弱势---在碰到与char关联时,要转化. 要花时间.
4: 优势在于,当char非常长时,enum依然是整型固定长度.当查询的数据量越大时,enum的优势越明显.
5: enum与char/varchar关联 ,因为要转化,速度要比enum->enum,char->char要慢,

五.索引优化策略

多列索引上,索引发挥作用,需要满足左前缀要求.
以 index(a,b,c) 为例,
-----------------------------------------------------------------
语句                                   | 索引是否发挥作用
-----------------------------------------------------------------
Where a=3                               | 是,只使用了a列
Where a=3 and b=5                      | 是,使用了a,b列
Where a=3 and b=5 and c=4               | 是,使用了abc
Where b=3 / where c=4                   | 否
Where a=3 and c=4                       | a列能发挥索引,c不能
Where a=3 and b>10 and c=7             | A能利用,b能利用, C不能利用
where a=3 and b like ‘xxxx%’ and c=7 | A能用,B能用,C不能用
-----------------------------------------------------------------


假设某个表有一个联合索引（c1,c2,c3,c4）一下——只能使用该联合索引的c1,c2,c3部分
A where c1=x and c2=x and c4>x and c3=x 
B where c1=x and c2=x and c4=x order by c3
C where c1=x and c4= x group by c3,c2
D where c1=x and c5=x order by c2,c3
E where c1=x and c2=x and c5=? order by c2,c3

create table t4 (
c1 tinyint(1) not null default 0,
c2 tinyint(1) not null default 0,
c3 tinyint(1) not null default 0,
c4 tinyint(1) not null default 0,
c5 tinyint(1) not null default 0,
index c1234(c1,c2,c3,c4)
);
insert into t4 values (1,3,5,6,7),(2,3,9,8,3),(4,3,2,7,5);

对于A:
c1=x and c2=x and c4>x and c3=x  <==等价==> c1=x and c2=x and c3=x and c4>x
因此 c1,c2,c3,c4都能用上. 如下:
mysql> explain select * from t4 where c1=1 and c2=2 and c4>3 and c3=3 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t4
         type: range
possible_keys: c1234
          key: c1234
      key_len: 4 #可以看出c1,c2,c3,c4索引都用上
          ref: NULL
         rows: 1
        Extra: Using where 

对于B: select * from t4 where c1=1 and c2=2 and c4=3 order by c3
c1 ,c2索引用上了,在c2用到索引的基础上,c3是排好序的,因此不用额外排序.
而c4没发挥作用.
mysql> explain select * from t4 where c1=1 and c2=2 and c4=3 order by c3 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t4
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 2
          ref: const,const
         rows: 1
        Extra: Using where
1 row in set (0.00 sec)

mysql> explain select * from t4 where c1=1 and c2=2 and c4=3 order by c5 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t4
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 2
          ref: const,const
         rows: 1
        Extra: Using where; Using filesort
1 row in set (0.00 sec)

对于 C: 只用到c1索引,因为group by c3,c2的顺序无法利用c2,c3索引
mysql> explain select * from t4 where c1=1 and c4=2 group by c3,c2 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t4
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 1 #只用到c1,因为先用c3后用c2分组,导致c2,c3索引没发挥作用
          ref: const
         rows: 1
        Extra: Using where; Using temporary; Using filesort
1 row in set (0.00 sec)

mysql> explain select * from t4 where c1=1 and c4=2 group by c2,c3 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t4
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 1
          ref: const
         rows: 1
        Extra: Using where
1 row in set (0.00 sec)

D语句: C1确定的基础上,c2是有序的,C2之下C3是有序的,因此c2,c3发挥的排序的作用.
因此,没用到filesort
mysql> explain select * from t4 where c1=1 and c5=2 order by c2,c3 \G  
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t4
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 1
          ref: const
         rows: 1
        Extra: Using where
1 row in set (0.00 sec)

E: 这一句等价与 elect * from t4 where c1=1 and c2=3 and c5=2 order by c3; 
因为c2的值既是固定的,参与排序时并不考虑

mysql> explain select * from t4 where c1=1 and c2=3 and c5=2 order by c2,c3 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t4
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 2
          ref: const,const
         rows: 1
        Extra: Using where
1 row in set (0.00 sec)

五.聚簇索引与非聚簇索引

innodb的主索引文件上 直接存放该行数据,称为聚簇索引,次索引指向对主键的引用
myisam的主索引和次索引,都指向物理行(磁盘位置).

注意: innodb来说, 
1: 主键索引 既存储索引值,又在叶子中存储行的数据
2: 如果没有主键, 则会Unique key做主键 
3: 如果没有unique,则系统生成一个内部的rowid做主键.
 
聚簇索引 
优势: 根据主键查询条目比较少时,不用回行(数据就在主键节点下)
劣势: 如果碰到不规则数据插入时,造成频繁的页分裂与页移动.

----《整理自燕十八mysql高性能》