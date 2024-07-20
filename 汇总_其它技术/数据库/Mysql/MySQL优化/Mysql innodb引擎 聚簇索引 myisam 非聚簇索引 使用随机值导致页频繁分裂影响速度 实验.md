### 数据准备

- 建立innodb 和myisam 表

- 利用php连接mysql,规则插入10000条数据,

- 利用php连接mysql,不规则插入10000条数据

- 观察时间的差异,体会聚簇索引,页分裂的影响.  

#### 建表MySQL

```
create table t3(
id int primary key,
c1 varchar(3000),
c2 varchar(3000),
c3 varchar(3000),
c4 varchar(3000),
c5 varchar(3000),
c6 varchar(3000)
) engine myisam charset utf8;

create table t4(
id int primary key,
c1 varchar(3000),
c2 varchar(3000),
c3 varchar(3000),
c4 varchar(3000),
c5 varchar(3000),
c6 varchar(3000)
) engine myisam charset utf8;

create table t5(
id int primary key,
c1 varchar(3000),
c2 varchar(3000),
c3 varchar(3000),
c4 varchar(3000),
c5 varchar(3000),
c6 varchar(3000)
) engine innodb charset utf8;

create table t6(
id int primary key,
c1 varchar(3000),
c2 varchar(3000),
c3 varchar(3000),
c4 varchar(3000),
c5 varchar(3000),
c6 varchar(3000)
) engine innodb charset utf8;
```

##### innodb插入数据脚本

innodb顺序插入的PHP代码


```
<?php
$conn = mysqli_connect("localhost","root","","test");
$time_start = microtime_float();

$str = str_repeat('m',3000);
for($i=1;$i<=10000;$i++) {
   $sql = "insert into t5 values ($i,'$str' , '$str' , '$str' , '$str' , '$str' , '$str'
)";
   $conn->query($sql);
}

$time_end = microtime_float();
echo 'seq insert cost:' , ($time_end - $time_start) , " seconds\n";
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
```


innodb乱序插入的PHP代码

```
<?php
$base = range(1,10000);
shuffle($base);

$conn = mysqli_connect("localhost","root","","test");
$time_start = microtime_float();

$str = str_repeat('m',3000);
foreach($base as $i) {
    $sql = "insert into t6 values ($i,'$str' , '$str' , '$str' , '$str' , '$str' , '$str'
)";
   $conn->query($sql);
}

$time_end = microtime_float();
echo 'rand insert cost:' , ($time_end - $time_start) , " seconds\n";
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
```


### innodb时间

顺序


```
╰─$ php sequence.php
seq insert cost:12.436960935593 seconds
```

乱序


```
php shuffle.php 
rand insert cost:18.066556930542 seconds
```

结果差别挺大 因为无需插入 页分裂


##### myisam插入数据脚本

myisam顺序插入的PHP代码


```
<?php
$conn = mysqli_connect("localhost","root","","test");
$time_start = microtime_float();

$str = str_repeat('m',3000);
for($i=1;$i<=10000;$i++) {
   $sql = "insert into t3 values ($i,'$str' , '$str' , '$str' , '$str' , '$str' , '$str'
)";
   $conn->query($sql);
}

$time_end = microtime_float();
echo 'seq insert cost:' , ($time_end - $time_start) , " seconds\n";
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
```


myisam乱序插入的PHP代码

```
<?php
$base = range(1,10000);
shuffle($base);

$conn = mysqli_connect("localhost","root","","test");
$time_start = microtime_float();

$str = str_repeat('m',3000);
foreach($base as $i) {
    $sql = "insert into t4 values ($i,'$str' , '$str' , '$str' , '$str' , '$str' , '$str'
)";
   $conn->query($sql);
}

$time_end = microtime_float();
echo 'rand insert cost:' , ($time_end - $time_start) , " seconds\n";
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
```


### myisam时间

顺序


```
╰─$ php sequence.php
seq insert cost:3.2187960147858 seconds
```

乱序


```
php shuffle.php 
seq insert cost:3.4112558364868 seconds
```

结果没太大查别