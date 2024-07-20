主服务器的日志格式用哪种好?

有 statement,row, mixed3种,其中mixed是指前2种的混合.

以insert into xxtable values (x,y,z)为例, 

影响: 1行,且为新增1行, 对于其他行没有影响.  

这个情况,用row格式,直接复制磁盘上1行的新增变化.

以update xxtable set age=21 where name=’sss’;

这个情况,一般也只是影响1行. 用row也比较合适.

以过年发红包,全公司的人,都涨薪100元.

update xxtable set salary=salary+100;

这个语句带来的影响,是针对每一行的, 因此磁盘上很多row都发生了变化.
此处,适合就statment格式的日志.

2种日志,各有各的高效的地方,mysql提供了mixed类型.

可以根据语句的不同,而自动选择适合的日志格式.