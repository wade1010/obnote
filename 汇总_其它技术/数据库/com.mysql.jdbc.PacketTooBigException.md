org.springframework.dao.TransientDataAccessResourceException: PreparedStatementCallback; SQL [insert into cerse_videokeyword (keyword,text,filename,json)values(?,?,?,?)]; Packet for query is too large (1983421 > 1048576). You can change this value on the server by setting the max_allowed_packet' variable.; nested exception is com.mysql.jdbc.PacketTooBigException: Packet for query is too large (1983421 > 1048576). You can change this value on the server by setting the max_allowed_packet' variable.

 at org.springframework.jdbc.support.SQLStateSQLExceptionTranslator.doTranslate(SQLStateSQLExceptionTranslator.java:106)

 at org.springframework.jdbc.support.AbstractFallbackSQLExceptionTranslator.translate(AbstractFallbackSQLExceptionTranslator.java:73)

 at org.springframework.jdbc.support.AbstractFallbackSQLExceptionTranslator.translate(AbstractFallbackSQLExceptionTranslator.java:81)

 at org.springframework.jdbc.support.AbstractFallbackSQLExceptionTranslator.translate(AbstractFallbackSQLExceptionTranslator.java:81)

 at org.springframework.jdbc.core.JdbcTemplate.execute(JdbcTemplate.java:660)

 at org.springframework.jdbc.core.JdbcTemplate.update(JdbcTemplate.java:909)

 at org.springframework.jdbc.core.JdbcTemplate.update(JdbcTemplate.java:970)

 at org.springframework.jdbc.core.JdbcTemplate.update(JdbcTemplate.java:980)

 at com.iflytek.edu.cerse.dao.impl.VideoWordDaoImpl.saveKeyWord(VideoWordDaoImpl.java:40)

 at com.iflytek.edu.cerse.processor.HandlerTask.saveKeyWord(HandlerTask.java:158)

 at com.iflytek.edu.cerse.processor.HandlerTask.handler(HandlerTask.java:126)

 at com.iflytek.edu.cerse.processor.HandlerTask.run(HandlerTask.java:64)

 at java.util.concurrent.ThreadPoolExecutor.runWorker(Unknown Source)

 at java.util.concurrent.ThreadPoolExecutor$Worker.run(Unknown Source)

 at java.lang.Thread.run(Unknown Source)

Caused by: com.mysql.jdbc.PacketTooBigException: Packet for query is too large (1983421 > 1048576). You can change this value on the server by setting the max_allowed_packet' variable.

 at com.mysql.jdbc.MysqlIO.send(MysqlIO.java:3910)

 at com.mysql.jdbc.MysqlIO.sendCommand(MysqlIO.java:2596)

 at com.mysql.jdbc.MysqlIO.sqlQueryDirect(MysqlIO.java:2776)

 at com.mysql.jdbc.ConnectionImpl.execSQL(ConnectionImpl.java:2838)

 at com.mysql.jdbc.PreparedStatement.executeInternal(PreparedStatement.java:2082)

 at com.mysql.jdbc.PreparedStatement.executeUpdate(PreparedStatement.java:2334)

 at com.mysql.jdbc.PreparedStatement.executeUpdate(PreparedStatement.java:2262)

 at com.mysql.jdbc.PreparedStatement.executeUpdate(PreparedStatement.java:2246)

 at com.alibaba.druid.filter.FilterChainImpl.preparedStatement_executeUpdate(FilterChainImpl.java:2723)

 at com.alibaba.druid.filter.FilterAdapter.preparedStatement_executeUpdate(FilterAdapter.java:1070)

 at com.alibaba.druid.filter.FilterEventAdapter.preparedStatement_executeUpdate(FilterEventAdapter.java:491)

 at com.alibaba.druid.filter.FilterChainImpl.preparedStatement_executeUpdate(FilterChainImpl.java:2721)

 at com.alibaba.druid.proxy.jdbc.PreparedStatementProxyImpl.executeUpdate(PreparedStatementProxyImpl.java:145)

 at com.alibaba.druid.pool.DruidPooledPreparedStatement.executeUpdate(DruidPooledPreparedStatement.java:253)

 at org.springframework.jdbc.core.JdbcTemplate$2.doInPreparedStatement(JdbcTemplate.java:916)

 at org.springframework.jdbc.core.JdbcTemplate$2.doInPreparedStatement(JdbcTemplate.java:909)

 at org.springframework.jdbc.core.JdbcTemplate.execute(JdbcTemplate.java:644)

 ... 10 more

![](https://gitee.com/hxc8/images8/raw/master/img/202407191058644.jpg)

  在向mysql数据库存储图像文件大于1048576时抛出com.mysql.jdbc.PacketTooBigException: 异常

  以下是解决方案：

  我用的mysql版本是5.0

  在mysql安装目录下找到my.ini文件，在最后加入一行：

 

max_allowed_packet = 10M（该值根据需要设定）

用dos窗口中

使用net stop mysql命令关闭mysql

然后重启

net start mysql

重新插入图像（不超过10M），发现问题已经解决了！

txt文档：D:\Jiaoyu_Rec_time\fromdir\248.txt解析成功!