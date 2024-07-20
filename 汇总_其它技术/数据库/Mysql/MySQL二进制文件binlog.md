

![](https://gitee.com/hxc8/images7/raw/master/img/202407190814094.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190814196.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190814730.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190814363.jpg)







show variables like 'binlog_format';



5.7版本后默认是row之前的版本默认是statement



set session binlog_format=statement;



show binary logs;



flush logs;



show binary logs;





然后进行一些数据库操作，如创建数据库 进入数据库 创建表 插入数据 等





然后进入binlog目录



mysqlbinlog mysql-bin.000002   来查看statement日志内容

![](https://gitee.com/hxc8/images7/raw/master/img/202407190814719.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190814105.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190814259.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190814593.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190814948.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190814287.jpg)

