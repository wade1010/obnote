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



![](//note.youdao.com/src/6B3A536E952F4637A42037A5A0D00CF2)



使用performance_schema

