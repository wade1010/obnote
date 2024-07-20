1.数据库执行原理与索引查找原理

索引：

对数据库的一列或多列的数据进行排序的结构

![](https://gitee.com/hxc8/images8/raw/master/img/202407191102458.jpg)





hash值一样，后面用链表存储相同的值



![](https://gitee.com/hxc8/images8/raw/master/img/202407191102160.jpg)





索引的使用方案：

select * from test where sex='女'



索引当中，哪些情况不走索引：

计算步骤，函数不走，or  ,like



or 右边加入不是索引字段 就有可能不走索引



like  '李%' 走索引

like  '%李%' 不走





![](https://gitee.com/hxc8/images8/raw/master/img/202407191102584.jpg)





2.不同存储引擎查找方式 innodb引擎





3.大型网站的优化方案







