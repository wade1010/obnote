尽量减少事务边界（事务边界，单个sql语句在后端数据库上同时执行的数量）



如果每一条SQL语句都能带有分库分表键，通过分布式服务层在对于SQL的解析后都能精确地将这条SQL语句推送到该数据所在的数据库上执行；但是如果数据库访问不带有分库分表键，则会出现全表扫描，导致事务边界的数量增大。会出现的问题：系统中锁冲突的概率增加，系统难以扩展（整个平台的数据库连接能力是取决于后端单个数据库的连接能力），整体性能降低（大数据量的聚合、排序、分组计算，会占用较大的内存和CPU计算资源）。



> 买家在查看自己订单的过程中出现全表扫描，有违“尽量减少事务边界”这一原则

> 如何解决这类问题？异构索引表，拿空间换时间。

> 采用异步机制将原表内的每一次创建或者更新，都换另一个维度保存一份完整的数据表或者索引表，通过两次带分库分表键的访问代替了全表扫描。