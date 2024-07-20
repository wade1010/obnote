在mysql中可以把已经存在的表直接通过命令复制为另一个表

方法1：create table mmm select * from bbb; 注意：这条命令要求mmm这个表在数据库中不存在

这条命令可以创建新表mmm并且bbb中的表结构以及数据和mmm完全一样mysql insert，也可以导出部分字段 create table mmm select 字段1，字段2 from bbb;

方法2：insert into mmm select * bbb; 这条语句和上一条语句实现同样的功能，只是要求mmm表必须在数据库中存在