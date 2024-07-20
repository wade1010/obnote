本文通过实例介绍了MySQL中的group_concat函数的使用方法，比如select group_concat(name) 。

MySQL中group_concat函数

完整的语法如下：

group_concat([DISTINCT] 要连接的字段 [Order BY ASC/DESC 排序字段] [Separator '分隔符'])

基本查询

mysql> select * from aa;

+------+------+

| id| name |

+------+------+

|1 | 10|

|1 | 20|

|1 | 20|

|2 | 20|

|3 | 200  |

|3 | 500  |

+------+------+

6 rows in set (0.00 sec)

以id分组，把name字段的值打印在一行，逗号分隔(默认)

mysql> select id,group_concat(name) from aa group by id;

+------+--------------------+

| id| group_concat(name) |

+------+--------------------+

|1 | 10,20,20|

|2 | 20 |

|3 | 200,500|

+------+--------------------+

3 rows in set (0.00 sec)

以id分组，把name字段的值打印在一行，分号分隔

mysql> select id,group_concat(name separator ';') from aa group by id;

+------+----------------------------------+

| id| group_concat(name separator ';') |

+------+----------------------------------+

|1 | 10;20;20 |

|2 | 20|

|3 | 200;500  |

+------+----------------------------------+

3 rows in set (0.00 sec)

以id分组，把去冗余的name字段的值打印在一行，

逗号分隔

mysql> select id,group_concat(distinct name) from aa group by id;

+------+-----------------------------+

| id| group_concat(distinct name) |

+------+-----------------------------+

|1 | 10,20|

|2 | 20  |

|3 | 200,500 |

+------+-----------------------------+

3 rows in set (0.00 sec)

以id分组，把name字段的值打印在一行，逗号分隔，以name排倒序

mysql> select id,group_concat(name order by name desc) from aa group by id;

+------+---------------------------------------+

| id| group_concat(name order by name desc) |

+------+---------------------------------------+

|1 | 20,20,10  |

|2 | 20|

|3 | 500,200|

+------+---------------------------------------+

3 rows in set (0.00 sec)