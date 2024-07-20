https://docs.datastax.com/en/cassandra-oss/3.x/

https://docs.datastax.com/en/archived/cql/3.0/index.html

https://docs.datastax.com/en/cql-oss/3.x/index.html

https://community.datastax.com/index.html

区分大小写,双引号括起来

```javascript
cqlsh:store> CREATE TABLE "TEST"(Foo int PRIMARY KEY ,"Bar" int);
cqlsh:store> DESC TABLES 

"TEST"  shopping_cart

cqlsh:store> CREATE TABLE TEST(Foo int PRIMARY KEY ,"Bar" int);
cqlsh:store> DESC TABLES 

"TEST"  test  shopping_cart

cqlsh:store> 
```





Uppercase and lowercase

Keyspace, column, and table names created using CQL 3 are case-insensitive unless enclosed in double quotation marks. If you enter names for these objects using any uppercase letters, Cassandra stores the names in lowercase. You can force the case by using double quotation marks. For example:

CREATE TABLE test (
  Foo int PRIMARY KEY,
  "Bar" int
);

The following table shows soem examples of partial queries that work and do not work to return results from the test table:

| Queries that Work | Queries that Don't Work |
| - | - |
| SELECT foo FROM . . . | SELECT "Foo" FROM . . . |
| SELECT Foo FROM . . . | SELECT "BAR" FROM . . . |
| SELECT FOO FROM . . . | SELECT bar FROM . . . |
| SELECT "foo" FROM . . . | SELECT Bar FROM . . . |
| SELECT "Bar" FROM . . . |   |


SELECT "foo" FROM ... works because internally, Cassandra stores foo in lowercase. The double-quotation mark character can be used as an escape character for the double quotation mark.

Case sensitivity rules in earlier versions of CQL apply when handling legacy tables.

CQL keywords are case-insensitive. For example, the keywords SELECT and select are equivalent. This document shows keywords in uppercase.









