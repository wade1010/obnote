使用profile



set profiling=1;

启动profile

这是一个session级的配置



执行查询



show profiles;



查询每一个查询所消耗的总时间信息



show profile for query N;

查询的每个阶段所耗的时间





show profile cpu for query 1;



但是每次使用都会有一个warning



![](https://gitee.com/hxc8/images7/raw/master/img/202407190812541.jpg)



使用performance_schema



![](https://gitee.com/hxc8/images7/raw/master/img/202407190813034.jpg)



全局有效







![](https://gitee.com/hxc8/images7/raw/master/img/202407190813675.jpg)



上面SQL返回结果部分

![](https://gitee.com/hxc8/images7/raw/master/img/202407190813267.jpg)





mysql -u root -h 127.0.0.1 -p



use performance_schema;



