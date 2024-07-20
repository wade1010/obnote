coreseek(sphinx)错误:WARNING: attribute 'id' not found - IGNORING原因及解决方法

coreseek(sphinx)建立索引时提示错误：

WARNING: attribute 'id' not found - IGNORING

原因：

sphinx不能使用主键来做属性字段，你的索引配置文件中一定用了类似

sql_attr_uint = id (id为表的主键)

解决方法：

去掉sql_attr_uint = id

或改为

sql_query = SELECT id,id as aid,body from table

sql_attr_uint = aid

即：在sql_query中给id用as 重新命个名子

