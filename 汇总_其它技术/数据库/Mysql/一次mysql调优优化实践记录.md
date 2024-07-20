1.  准备工作

1. 3张表sql

```javascript
CREATE TABLE `course` (
  `cid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `score` (
  `sc_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_id` int(11) DEFAULT NULL,
  `c_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`sc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```

1. 插入数据

1. course表使用存储过程插入100条数据

```javascript
delimiter $$
create procedure add_course(a int) 
begin
while a > 0 do
	insert into course(name)VALUES(substring(MD5(RAND()),floor(RAND()*26)+1,6) );
set a = a-1;
end while;
end $$
```

1. mysql命令上执行上面命令后，在执行   call add_course(100)    这里的100是次数

1. student表使用存储过程插入70000条数据

```javascript
delimiter $$
create procedure add_student(a int) 
begin
while a > 0 do
	insert into sudent(name)VALUES(substring(MD5(RAND()),floor(RAND()*26)+1,7) );
set a = a-1;
end while;
end $$
```

1. mysql命令上执行上面命令后，在执行   call add_course(70000)    这里的70000是次数

1. score表使用php插入700万条数据

```javascript
public function testAction()
    {
        $values = [];
        $sql = "insert into score1(c_id,s_id,score)VALUES";
        for ($i = 0; $i < 100; $i++) {
            for ($j = 1; $j < 70001; $j++) {
                $values[] = "($i,$j," . rand(70, 100) . ")";
                if ($j % 10000 == 0) {
                    $newSql = $sql . implode(',', $values) . ";";
                    $m = memory_get_usage(); //获取当前占用内存,700万数据可能会内存溢出,自己看看怎么优化
                    ORMBaseModel::hydrateRaw($newSql);
                    print_r($m);
                    print_r("\n");
                    $values = [];
                }
            }
        }
    }
```

1. 大体思路是生成批量插入，但是数据太多，就得分批，我这里是每10000条插入一次，最终执行SQL是用了框架，执行SQL可以改成自己的，我这里就不做详细说明了(另外我这里取巧，没有查student和course的id，因为首次创建表，按上面的步骤插入student和course是有序的^_^)

1. 查询目的:查找英语(course表cid=1)考100分的学生（由于随机的100分可能比较多，我这里手动改了cid=1的3个分数，改成101分）

1. 开始试验

1. 开始的SQL

```javascript
mysql> SELECT sql_no_cache * FROM student WHERE id IN (SELECT s_id FROM score WHERE c_id = 1 AND score = 101);
+----+-----------+
| id | name      |
+----+-----------+
|  1 | cfad8952e |
|  2 | f242f8091 |
|  9 | ea670b462 |
+----+-----------+
3 rows in set (1.53 sec)
```

1. 查看执行计划 (id越大越先执行，id相同认为是一组，从上到下执行)

```javascript
mysql> explain SELECT sql_no_cache * FROM student WHERE id IN (SELECT s_id FROM score WHERE c_id = 1 AND score = 101);
+----+--------------+-------------+--------+---------------+------------+---------+-----------------+---------+-------------+
| id | select_type  | table       | type   | possible_keys | key        | key_len | ref             | rows    | Extra       |
+----+--------------+-------------+--------+---------------+------------+---------+-----------------+---------+-------------+
|  1 | SIMPLE       | student     | ALL    | PRIMARY       | NULL       | NULL    | NULL            |   70470 | Using where |
|  1 | SIMPLE       | <subquery2> | eq_ref | <auto_key>    | <auto_key> | 5       | test.student.id |       1 | NULL        |
|  2 | MATERIALIZED | score       | ALL    | NULL          | NULL       | NULL    | NULL            | 6984012 | Using where |
+----+--------------+-------------+--------+---------------+------------+---------+-----------------+---------+-------------+
3 rows in set (0.00 sec)
```

1. 发现type都是ALL,先想到要给where中的字段添加索引

```javascript
mysql> create index sc_c_id_index on score(c_id);
Query OK, 0 rows affected (10.13 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> create index sc_score_index on score(score);
Query OK, 0 rows affected (12.27 sec)
Records: 0  Duplicates: 0  Warnings: 0
```

1. drop index sc_c_id_index on score;

1. drop sc_score_index on score;

1. 再执行上面的查询语句，从下面的时间可以看出时间从1.53sec->0.08sec

```javascript
mysql> SELECT sql_no_cache * FROM student WHERE id IN (SELECT s_id FROM score WHERE c_id = 1 AND score = 101);
+----+-----------+
| id | name      |
+----+-----------+
|  1 | cfad8952e |
|  2 | f242f8091 |
|  9 | ea670b462 |
+----+-----------+
3 rows in set (1.01 sec)
```

1. 再次执行查询计划

```javascript
mysql> explain SELECT * FROM student WHERE id IN (SELECT s_id FROM score WHERE c_id = 1 AND score = 101);
+----+-------------+---------+--------+-------------------------------+-----------------+---------+-----------------+------+------------------------------+
| id | select_type | table   | type   | possible_keys                 | key             | key_len | ref             | rows | Extra                        |
+----+-------------+---------+--------+-------------------------------+-----------------+---------+-----------------+------+------------------------------+
|  1 | SIMPLE      | score   | ref    | sc_c_id_index,sc_score__index | sc_score__index | 5       | const           |    3 | Using where; Start temporary |
|  1 | SIMPLE      | student | eq_ref | PRIMARY                       | PRIMARY         | 4       | test.score.s_id |    1 | End temporary                |
+----+-------------+---------+--------+-------------------------------+-----------------+---------+-----------------+------+------------------------------+
2 rows in set (0.00 sec)
```

1. ps:查看实际执行顺序

```javascript
mysql> explain extended SELECT * FROM student WHERE id IN (SELECT s_id FROM score WHERE c_id = 1 AND score = 101);
+----+-------------+---------+--------+------------------------------+----------------+---------+-----------------+------+----------+------------------------------+
| id | select_type | table   | type   | possible_keys                | key            | key_len | ref             | rows | filtered | Extra                        |
+----+-------------+---------+--------+------------------------------+----------------+---------+-----------------+------+----------+------------------------------+
|  1 | SIMPLE      | score   | ref    | sc_c_id_index,sc_score_index | sc_score_index | 5       | const           |   19 |   100.00 | Using where; Start temporary |
|  1 | SIMPLE      | student | eq_ref | PRIMARY                      | PRIMARY        | 4       | test.score.s_id |    1 |   100.00 | End temporary                |
+----+-------------+---------+--------+------------------------------+----------------+---------+-----------------+------+----------+------------------------------+
2 rows in set, 1 warning (0.01 sec)

mysql> show warnings;
+-------+------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Level | Code | Message                                                                                                                                                                                                                                                           |
+-------+------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Note  | 1003 | /* select#1 */ select `test`.`student`.`id` AS `id`,`test`.`student`.`name` AS `name` from `test`.`student` semi join (`test`.`score`) where ((`test`.`student`.`id` = `test`.`score`.`s_id`) and (`test`.`score`.`score` = 101) and (`test`.`score`.`c_id` = 1)) |
+-------+------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.10 sec)

```































