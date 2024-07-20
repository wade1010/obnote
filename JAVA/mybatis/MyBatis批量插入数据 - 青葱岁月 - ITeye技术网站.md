在程序中封装了一个List集合对象，然后需要把该集合中的实体插入到数据库中，由于项目使用了Spring+MyBatis的配置，所以打算使用MyBatis批量插入，由于之前没用过批量插入，在网上找了一些资料后最终实现了，把详细过程贴出来。

实体类TrainRecord结构如下：

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/41A5104056D242B38061554A46C0498Ficon_star.png)

1. publicclass TrainRecord implements Serializable {  

1. privatestaticfinallong serialVersionUID = -1206960462117924923L;  

1. privatelong id;  

1. privatelong activityId;  

1. privatelong empId;  

1. privateint flag;  

1. private String addTime;  

1. //setter and getter 

1. }  

对应的mapper.xml中定义如下：

Xml代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/E989058506AB42D4B3547979BB8543C0icon_star.png)

1. < span>resultMaptype="TrainRecord"id="trainRecordResultMap"<

1. < span>idcolumn="id"property="id"jdbcType="BIGINT"/<

1. < span>resultcolumn="add_time"property="addTime"jdbcType="VARCHAR"/<

1. < span>resultcolumn="emp_id"property="empId"jdbcType="BIGINT"/<

1. < span>resultcolumn="activity_id"property="activityId"jdbcType="BIGINT"/<

1. < span>resultcolumn="flag"property="status"jdbcType="VARCHAR"/<

1. resultMap<

mapper.xml中批量插入方法的定义如下：

Xml代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/EA36FD8DD1AA4E5CA9B845CFEBE300EAicon_star.png)

1. < span>insertid="addTrainRecordBatch"useGeneratedKeys="true"parameterType="java.util.List"<

1. < span>selectKeyresultType="long"keyProperty="id"order="AFTER"<

1.         SELECT  

1.         LAST_INSERT_ID()  

1. selectKey<

1.     insert into t_train_record (add_time,emp_id,activity_id,flag)   

1.     values  

1. < span>foreachcollection="list"item="item"index="index"separator=","<

1.         (#{item.addTime},#{item.empId},#{item.activityId},#{item.flag})  

1. foreach<

1. insert<

对于foreach标签的解释参考了网上的资料，具体如下：

foreach的主要用在构建in条件中，它可以在SQL语句中进行迭代一个集合。foreach元素的属性主要有 item，index，collection，open，separator，close。item表示集合中每一个元素进行迭代时的别名，index指 定一个名字，用于表示在迭代过程中，每次迭代到的位置，open表示该语句以什么开始，separator表示在每次进行迭代之间以什么符号作为分隔 符，close表示以什么结束，在使用foreach的时候最关键的也是最容易出错的就是collection属性，该属性是必须指定的，但是在不同情况 下，该属性的值是不一样的，主要有一下3种情况：

1.如果传入的是单参数且参数类型是一个List的时候，collection属性值为list

2.如果传入的是单参数且参数类型是一个array数组的时候，collection的属性值为array

3.如果传入的参数是多个的时候，我们就需要把它们封装成一个Map了，当然单参数也可以封装成map

关于foreach的具体例子在这里就先不举，以后有机会可以把每一种情况都举一个例子列出来。

MysqlBaseDAO：

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/9787A8173A644DCD865620433AE83E92icon_star.png)

1. publicclass MySqlBaseDAO extends SqlSessionDaoSupport {  

1. /**

1.      * insert:插入操作. 

1.      *

1.      * @author chenzhou

1.      * @param method 插入操作的方法名

1.      * @param entity 查询参数或实体类

1.      * @return 返回影响的行数

1.      * @since JDK 1.6

1.      */

1. publicint insert(String method,Object entity){  

1. returnthis.getSqlSession().insert(method, entity);  

1.     }  

1. //其余方法省略

1. }  

TrainRecord实体类对应的TrainRecordDAO 定义如下：

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/F9C805BBD89849B8B0ADC188C31304E4icon_star.png)

1. publicclass TrainRecordDAO extends MySqlBaseDAO {  

1. /**

1.      * addTrainRecordBatch:批量插入培训记录. 

1.      *

1.      * @author chenzhou

1.      * @param trainRecordList 培训记录list集合

1.      * @return 影响的行数

1.      * @since JDK 1.6

1.      */

1. publicint addTrainRecordBatch(List trainRecordList){  

1. returnthis.insert("addTrainRecordBatch", trainRecordList);  

1.     }  

1. //省略其余的方法

1. }  

然后直接调用TrainRecordDAO 中的 addTrainRecordBatch方法就可以批量插入了。

特别说明的是在尝试时碰到了一个让人无语的错误，折腾了我差不多1个小时才解决。就是我在定义mapper.xml中的插入方法时一般都会默认用标签把sql语句括起来，如下所示：

Xml代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/95FD0514DC64458A9CCB60E4F08906D4icon_star.png)

1.     select * from t_train_record t where t.activity_id=#{activityId}

1. ]]<

这样做的目的主要是因为在 XML 元素中，"< sql sql CDATACDATA p>

当时我在addTrainRecordBatch方法中也用了这种用法：

Xml代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/860CEFFFDC224984B926E7560C6E4206icon_star.png)

1.     insert into t_train_record (add_time,emp_id,activity_id,flag) 

1.     values

1.     

1.         (#{item.addTime},#{item.empId},#{item.activityId},#{item.flag})

1.     

1. ]]<

结果程序在执行时老是报错: com.mysql.jdbc.exceptions.jdbc4.MySQLSyntaxErrorException，查看错误信息就是传入的参数都是null。纠结了很久，后面才发现原来是把xml中的标签括起来后把标签直接当成字符串处理了。后面把外面的去掉后就能正常执行了。