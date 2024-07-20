主键


```
  KEY `cate_id` (`cate_id`),
  KEY `price` (`price`)
```

数据

```
mysql> select cate_id,price from goods2;
+---------+-------+
| cate_id | price |
+---------+-------+
|      30 |     1 |
|      36 |     0 |
|      36 |     0 |
|      40 |     0 |
|      48 |     0 |
+---------+-------+
```


```
mysql> explain select cate_id,price from goods2 where cate_id=36 and price >1 \G;
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: goods2
         type: ref
possible_keys: cate_id,price
          key: cate_id
      key_len: 4
          ref: const
         rows: 2
        Extra: Using where
1 row in set (0.00 sec)
```

扫描行数是2 因为cate_id=36 表内总共是2 

# 改进

将 cate_id和price改成联合索引

```
  KEY `cate_id_price` (`cate_id`,`price`)
```


```
alter table goods2 drop index cate_id;
alter table goods2 drop index price;
alter table goods2 add index cate_id_price(cate_id,price);
```



```
mysql> explain select cate_id,price from goods2 where cate_id=36 and price >1 \G;
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: goods2
         type: ref
possible_keys: cate_id_price
          key: cate_id_price
      key_len: 4
          ref: const
         rows: 1
        Extra: Using where; Using index
1 row in set (0.00 sec)
```


发小扫描行数由 2变成了1