### 数据准备

- 建立innodb 和myisam 表

- 利用php连接mysql,规则插入10000条数据,

- 利用php连接mysql,不规则插入10000条数据

#### 建表MySQL

```
create table t1(
id char(64) not null,
ver int(11) not null default 0,
c1 varchar(3000),
c2 varchar(3000),
c3 varchar(3000),
primary key  (id),
key idver (id,ver)
) engine myisam charset utf8;

create table t2(
id char(64) not null,
ver int(11) not null default 0,
c1 varchar(3000),
c2 varchar(3000),
c3 varchar(3000),
primary key  (id),
key idver (id,ver)
) engine innodb charset utf8;
```

##### 插入数据脚本

t1表插入的PHP代码


```
<?php
$conn = mysqli_connect("localhost","root","","test");
$time_start = microtime_float();

$str = str_repeat('m',3000);
for($i=1;$i<=10000;$i++) {
   $id = md5($i).md5(time());
   $sql = "insert into t1 values ('$id',$i,'$str' , '$str' , '$str')";
   $conn->query($sql);
}

$time_end = microtime_float();
echo 'insert cost:' , ($time_end - $time_start) , " seconds\n";
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
```

t2表将上面脚本里面的t1改成t2即可

```
<?php
$conn = mysqli_connect("localhost","root","","test");
$time_start = microtime_float();

$str = str_repeat('m',3000);
for($i=1;$i<=10000;$i++) {
   $id = md5($i).md5(time());
   $sql = "insert into t2 values ('$id',$i,'$str' , '$str' , '$str')";
   $conn->query($sql);
}

$time_end = microtime_float();
echo 'insert cost:' , ($time_end - $time_start) , " seconds\n";
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

```

### 时间

myisam 


```
╰─$ php test.php
insert cost:2.1865890026093 seconds
```

innodb


```
╰─$ php test.php
insert cost:8.7707788944244 seconds
```


## 测试查询

#### myisam

> set profiling=1;

> select id from t1 order by id;

> select id from t1 order by id,ver;

> show profiles;


```
mysql> show profiles;
+----------+------------+-----------------------------------+
| Query_ID | Duration   | Query                             |
+----------+------------+-----------------------------------+
|        1 | 0.00806300 | select id from t1 order by id     |
|        2 | 0.00877300 | select id from t1 order by id,ver |
+----------+------------+-----------------------------------+
2 rows in set, 1 warning (0.00 sec)
```

可以看出 myisam 没多大区别


#### innodb


> select id from t2 order by id;

> select id from t2 order by id,ver;

> show profiles;


```
mysql> show profiles;
+----------+------------+-----------------------------------+
| Query_ID | Duration   | Query                             |
+----------+------------+-----------------------------------+
|        1 | 0.00806300 | select id from t1 order by id     |
|        2 | 0.00877300 | select id from t1 order by id,ver |
|        3 | 0.07648800 | select id from t2 order by id     |
|        4 | 0.00538300 | select id from t2 order by id,ver |
+----------+------------+-----------------------------------+
4 rows in set, 1 warning (0.00 sec)
```

可以看出 innodb (Query_ID 3和4) 差别还是挺大的


## 删除 innodb中过长字段 c1 c2 c3

> alter table t2 drop column c1;

> alter table t2 drop column c2;

> alter table t2 drop column c3;

> select id from t2 order by id;

> select id from t2 order by id,ver;

> show profiles;


```
mysql> show profiles;
+----------+------------+-----------------------------------+
| Query_ID | Duration   | Query                             |
+----------+------------+-----------------------------------+
|        1 | 0.00806300 | select id from t1 order by id     |
|        2 | 0.00877300 | select id from t1 order by id,ver |
|        3 | 0.07648800 | select id from t2 order by id     |
|        4 | 0.00538300 | select id from t2 order by id,ver |
|        5 | 0.00003900 | alter table drop column c1        |
|        6 | 4.43217200 | alter table t2 drop column c1     |
|        7 | 1.61069100 | alter table t2 drop column c2     |
|        8 | 0.92016800 | alter table t2 drop column c3     |
|        9 | 0.00013600 | show create table t2              |
|       10 | 0.00475000 | select id from t2 order by id     |
|       11 | 0.00464300 | select id from t2 order by id,ver |
+----------+------------+-----------------------------------+
11 rows in set, 1 warning (0.00 sec)
```


可以看出 innodb (Query_ID 10和11) 几乎没差别





