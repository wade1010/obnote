## 建表

```
create table t2(
c1 char(1) not null default 0,
c2 char(1) not null default 0,
c3 char(1) not null default 0,
c4 char(1) not null default 0,
c5 char(1) not null default 0,
index c1234(c1,c2,c3,c4)
)engine innodb charset utf8;
```

## 插入数据


```
insert into t2 values (1,3,5,6,7),(2,3,9,8,3),(4,3,2,7,5);
```


## 验证下面几种情况


```
假设某个表有一个联合索引（c1,c2,c3,c4
A where c1=1 and c2=2 and c4>3 and c3=4 
B where c1=1 and c2=2 and c4=3 order by c3
C where c1=1 and c4=2 group by c3,c2
D where c1=1 and c5=2 order by c2,c3
E where c1=1 and c2=2 and c5=4 order by c2,c3
```

### 开始验证

#### 验证A

> explain select * from t2 where c1='a' and c2='b' and c4>'a' and c3='b'\G;



```
mysql> explain select * from t2 where c1='a' and c2='b' and c4>'a' and c3='b'\G; 
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t2
         type: range
possible_keys: c1234
          key: c1234
      key_len: 12
          ref: NULL
         rows: 1
        Extra: Using index condition
1 row in set (0.00 sec)

ERROR: 
No query specified
```

结果 4个都用了


#### 验证B

> explain select * from t2 where c1='a' and c2='b' and c4='a' order by c3\G;


```
mysql> explain select * from t2 where c1='a' and c2='b' and c4='a' order by c3\G;
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t2
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 6
          ref: const,const
         rows: 2
        Extra: Using index condition; Using where
1 row in set (0.00 sec)

ERROR: 
No query specified
```

结果 c1 c2 用了  c3用在排序上

#### 验证C

> explain select * from t2 where c1='a' and c4='b' group by c3,c2\G;


```
mysql> explain select * from t2 where c1='a' and c4='b' group by c3,c2\G;
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t2
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 3
          ref: const
         rows: 2
        Extra: Using index condition; Using where; Using temporary; Using filesort
1 row in set (0.00 sec)

ERROR: 
No query specified
```

结果 用了1个c1



#### 验证D

> explain select * from t2 where c1='a' and c5='b' order by c2,c3\G;

```
mysql> explain select * from t2 where c1='a' and c5='b' order by c2,c3\G;
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t2
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 3
          ref: const
         rows: 2
        Extra: Using index condition; Using where
1 row in set (0.00 sec)

ERROR: 
No query specified
```

结果 用了1个c1，  排序用到了c2和c3

##### D变种

> explain select * from t2 where c1='a' and c5='b' order by c3,c2\G;


```
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t2
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 3
          ref: const
         rows: 2
        Extra: Using index condition; Using where; Using filesort
1 row in set (0.00 sec)

ERROR: 
No query specified
```

结果 用了1个c1  c3和c2顺序使用错误 导致索引失效 产生filesort

#### 验证E

> explain select * from t2 where c1='a' and c2='c' and c5='b' order by c2,c3\G;


```
mysql> explain select * from t2 where c1='a' and c2='c' and c5='b' order by c2,c3\G;
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t2
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 6
          ref: const,const
         rows: 1
        Extra: Using index condition; Using where
1 row in set (0.00 sec)

ERROR: 
No query specified
```

结果 用了2个 c1 c2      排序用到c2和c3

##### E变种

> explain select * from t2 where c1='a' and c2='c' and c5='b' order by c3,c2\G;


```
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: t2
         type: ref
possible_keys: c1234
          key: c1234
      key_len: 6
          ref: const,const
         rows: 1
        Extra: Using index condition; Using where
1 row in set (0.00 sec)

ERROR: 
No query specified
```

结果 用了2个  此时c2是确定值 相当于常量