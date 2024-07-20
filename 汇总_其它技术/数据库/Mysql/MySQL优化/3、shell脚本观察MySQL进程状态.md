### 脚本

linux

```
# /bin/bash
while true
do
mysql -u root -e 'show processlist\G'|grep State|uniq|sort -rn >> tate.txt

usleep 100000
done
```


mac 

```
# /bin/bash
while true
do
mysql -u root -e 'show processlist\G'|grep State|uniq|sort -rn >> state.txt

sleep 0.001

done
```


常见状态

```
5   State: Sending data
2   State: statistics
2   State: NULL
1   State: Updating
1   State: update
```


以下几种状态要注意:

```
converting HEAP to MyISAM 
create tmp table  
Copying to tmp table on disk
locked
```


```
converting HEAP to MyISAM 查询结果太大,内存放不下时,把结果放在磁盘
create tmp table                       创建临时表(如group时储存中间结果)
Copying to tmp table on disk   把内存临时表复制到磁盘
locked         被其他查询锁住  
logging slow query 记录慢查询

```

出现上面的状态 sql 必须优化


## 测试

把上面的脚本保存到 mysql_state.sh中

启动 脚本
> sh mysql_state.sh

 sysbench 插入数据
 
 
> sysbench oltp_read_write.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --table_size=200000 --tables=10 --threads=10 prepare


> sysbench oltp_read_write.lua --mysql-host=127.0.0.1 --mysql-port=3306 --mysql-db=test --mysql-user=root --mysql-password='' --table_size=200000 --tables=10 --threads=10 run


sysbench运行完毕 关闭 mysql_state.sh

> more state.txt|sort|uniq -c|sort -rn
```
2570   State: 
2301   State: init
 268   State: statistics
 191   State: Writing to net
 130   State: update
  63   State: System lock
  61   State: closing tables
  55   State: updating
  50   State: Opening tables
  30   State: optimizing
  28   State: executing
  24   State: Sending data
  21   State: Creating sort index
  19   State: query end
   9   State: NULL
   8   State: preparing
   8   State: checking permissions
   5   State: end
   5   State: cleaning up
   3   State: removing tmp table
   3   State: Creating tmp table
   2   State: freeing items
   1   State: Sorting result
   ```
   
   
   
   ## 异常状态 演示
   
   > show variables like '%size%';
  或
  > show variables like '%tmp_table%';
   
   
```
max_tmp_tables	32
tmp_table_size	16777216
```


   
里面有个 tmp_table_size
   
 
临时修改 size
   
  > set session tmp_table_size=100;
  
  > set profiling=1;
  
  > show profiles;
  
  > select * from sbtest1 limit 10000;
  
  > show profiles;
  
 
```
mysql> show profiles;
+----------+------------+-----------------------------------+
| Query_ID | Duration   | Query                             |
+----------+------------+-----------------------------------+
|        1 | 0.01253200 | select * from sbtest1 limit 10000 |
+----------+------------+-----------------------------------+
1 row in set, 1 warning (0.00 sec)
```

> show profile for query 1;


```
mysql> show profile for query 1;
+----------------------+----------+
| Status               | Duration |
+----------------------+----------+
| starting             | 0.000033 |
| checking permissions | 0.000005 |
| Opening tables       | 0.000012 |
| init                 | 0.000010 |
| System lock          | 0.000005 |
| optimizing           | 0.000003 |
| statistics           | 0.000007 |
| preparing            | 0.000005 |
| executing            | 0.000002 |
| Sending data         | 0.012391 |
| end                  | 0.000012 |
| query end            | 0.000005 |
| closing tables       | 0.000011 |
| freeing items        | 0.000008 |
| cleaning up          | 0.000023 |
+----------------------+----------+
15 rows in set, 1 warning (0.01 sec)
```
 
可见大部分时间花在"Sending data"上

再修改下MySQL 语句 （无实际意义 测试使用 字段都是sysbench生成的表内字段）

> select * from sbtest1 group by c,k order by pad;

> show profiles;

```
mysql> show profiles;
+----------+------------+---------------------------------------------------+
| Query_ID | Duration   | Query                                             |
+----------+------------+---------------------------------------------------+
|        1 | 0.01253200 | select * from sbtest1 limit 10000                 |
|        2 | 3.43123100 | select * from sbtest1 group by c,k order by pad   |
+----------+------------+---------------------------------------------------+
2 rows in set, 1 warning (0.00 sec)
```

> show profile for query 1;

```
+---------------------------+----------+
| Status                    | Duration |
+---------------------------+----------+
| starting                  | 0.000039 |
| checking permissions      | 0.000005 |
| Opening tables            | 0.000012 |
| init                      | 0.000015 |
| System lock               | 0.000006 |
| optimizing                | 0.000003 |
| statistics                | 0.000008 |
| preparing                 | 0.000008 |
| Creating tmp table        | 0.000018 |
| Sorting result            | 0.000003 |
| executing                 | 0.000002 |
| Sending data              | 0.409088 |
| converting HEAP to MyISAM | 0.116379 |
| Sending data              | 1.818236 |
| Creating sort index       | 0.896426 |
| end                       | 0.000010 |
| removing tmp table        | 0.070322 |
| end                       | 0.000013 |
| query end                 | 0.000008 |
| closing tables            | 0.000043 |
| freeing items             | 0.000087 |
| cleaning up               | 0.000019 |
+---------------------------+----------+
22 rows in set, 1 warning (0.00 sec)
```

可以看到"converting HEAP to MyISAM" 占比也比较高

> 