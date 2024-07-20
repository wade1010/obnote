工具框架用spring-batch，数据库是mysql（未做特殊优化）。

比较数据框架mybatis和spring jdbc的插入效率。

Mybatis三种实现：

1、mybatis的官方写法

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/9203E1F9CDE94205BE89F82EBA1CCDCCicon_star.png)

1. publicvoid batchInsert1(List poilist) throws Exception {  

1.     SqlSession sqlSession = sqlSessionFactory.getObject().openSession(ExecutorType.BATCH);  

1.     PoiMapper pmapper = sqlSession.getMapper(PoiMapper.class);  

1. try {  

1. for (Poi poi : poilist) {  

1.             pmapper.insertPoi(poi);  

1.         }  

1.         sqlSession.commit();  

1.     } finally {  

1.         sqlSession.close();  

1.     }  

1. }  

 其中Poi是一个bean。

 PoiMapper定义：

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/C84272B8119348488C876EBE15241A0Ficon_star.png)

1. publicinterface PoiMapper {  

1. @Insert("insert into tb_poi (tag, poi, poiid, meshid, owner, featcode, sortcode, namec, namee, namep, names) values (#{tag}, GeomFromText(#{point}), #{poiid}, #{meshid}, #{owner}, #{featcode}, #{sortcode}, #{namec}, #{namee}, #{namep}, #{names}) ")  

1. publicvoid insertPoi(Poi poi);  

1. }  

2、利用mysql特性，拼写insert sql。

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/EDA6B956EDCF443B96658F94AA75D65Cicon_star.png)

1. publicvoid batchInsert2(List poilist) throws Exception {  

1.     SqlSession sqlSession = sqlSessionFactory.getObject().openSession(ExecutorType.BATCH);  

1. try {  

1.         sqlSession.insert("com.emg.trans.mapper.batchMapper.batchInsert", poilist);  

1.         sqlSession.commit();  

1.     } finally {  

1.         sqlSession.close();  

1.     }  

1. }  

 “com.emg.trans.mapper.batchMapper.batchInsert”在mybatis的xml中定义的sql，定义如下：

Xml代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/B6983BE9A41D4BC981D52DFCB8D093A3icon_star.png)

1. < span>mappernamespace="com.emg.trans.mapper.batchMapper"<

1. < span>insertid="batchInsert"parameterType="List"<

1.     insert into tb_poi (tag, poi, poiid, meshid, owner, featcode, sortcode, namec, namee, namep, names) values  

1. < span>foreachcollection="list"item="poi"index="index"separator=","<

1.          (#{poi.tag}, GeomFromText(#{poi.point}), #{poi.poiid}, #{poi.meshid}, #{poi.owner}, #{poi.featcode}, #{poi.sortcode}, #{poi.namec}, #{poi.namee}, #{poi.namep}, #{poi.names})  

1. foreach<

1. insert<

1. mapper<

3、利用spring的事务，直接执行插入操作。

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/01763406D707426BBA4F973EB2FE8CABicon_star.png)

1. @Transactional("dbTransaction")  

1. publicvoid batchInsert3(List poilist) throws Exception {  

1. for (Poi poi : poilist) {  

1.         apmapper.insertPoi(poi);  

1.     }  

1. }  

 apmapper是PoiMapper的实例，用@Autowired配置。

Spring-JDBC的三种实现：

A、用spring事务执行插入操作

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/C0AE4D1B633648E1B569C9C08C36F001icon_star.png)

1. @Transactional("dbTransaction")  

1. publicvoid batchInsertJDBC1(List poilist) throws DataAccessException {  

1.     String sql = "insert into tb_poi (tag, poi, poiid, meshid, owner, featcode, sortcode, namec, namee, namep, names) values (?, GeomFromText(?), ?, ?, ?, ?, ?, ?, ?, ?, ?)";  

1. for (Poi poi : poilist) {  

1.         Object[] args = {poi.getTag(), poi.getPoint(), poi.getPoiid(), poi.getMeshid(), poi.getOwner(), poi.getFeatcode(), poi.getSortcode(), poi.getNamec(),  

1.                 poi.getNamee(), poi.getNamep(), poi.getNames()};  

1.         jdbcTemplate.update(sql, args);   

1.     }  

1. }  

B、用spring事务和springjdbc的batchUpdate

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/DC9E4E203A874BFB8EA99F33388974EEicon_star.png)

1. @Transactional("dbTransaction")  

1. publicvoid batchInsertJDBC2(List poilist) throws DataAccessException {  

1.     String sql = "insert into tb_poi (tag, poi, poiid, meshid, owner, featcode, sortcode, namec, namee, namep, names) values (?, GeomFromText(?), ?, ?, ?, ?, ?, ?, ?, ?, ?)";  

1.     List batchArgs = new ArrayList();  

1. for (Poi poi : poilist) {  

1.         Object[] args = {poi.getTag(), poi.getPoint(), poi.getPoiid(), poi.getMeshid(), poi.getOwner(), poi.getFeatcode(), poi.getSortcode(), poi.getNamec(),  

1.                 poi.getNamee(), poi.getNamep(), poi.getNames()};  

1.         batchArgs.add(args);  

1.     }  

1.     jdbcTemplate.batchUpdate(sql, batchArgs);  

1. }  

C、用spring事务，利用mysql特性，拼写insert sql。

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/mybatis/images/BB5EFF9C29974570830E4B3FBBC9F867icon_star.png)

1. @Transactional("dbTransaction")  

1. publicvoid batchInsertJDBC3(List poilist) throws DataAccessException {  

1.     StringBuffer sqlbuf = new StringBuffer()  

1.         .append("insert into tb_poi (tag, poi, poiid, meshid, owner, featcode, sortcode, namec, namee, namep, names) values ");  

1.     MessageFormat form = new MessageFormat("(''{0}'', GeomFromText(''{1}''), ''{2}'', ''{3}'', ''{4}'', ''{5}'', ''{6}'', ''{7}'', ''{8}'', ''{9}'', ''{10}''),");  

1. for (Poi poi : poilist) {  

1.         Object[] args = {poi.getTag(), poi.getPoint(), poi.getPoiid(), poi.getMeshid(), poi.getOwner(), poi.getFeatcode(), poi.getSortcode(), poi.getNamec().replaceAll("'", "\\\\'"),  

1.                 poi.getNamee().replaceAll("'", "\\\\'"), poi.getNamep().replaceAll("'", "\\\\'"), poi.getNames().replaceAll("'", "\\\\'")};  

1.         sqlbuf.append(form.format(args));  

1.     }  

1.     String sql = sqlbuf.toString();  

1.     sql = sql.substring(0, sql.length()-1);  

1.     jdbcTemplate.update(sql);  

1. }  

测试一：每次处理100条，共600条。

测试二：每次处理1000条，共9000条。

直接上结论：

mybatis.1最慢。而且慢很多，很多。

mybatis.2很快。是mybatis中最快的。

mybatis.3比2慢一点。

jdbc.A比B稍快，两者差不多，和mybatis.3也在伯仲之间。

jdbc.C最快，比其他5种都快。

大排行，从用时少到用时多：

jdbc.C < mybatis jdbcA jdbcB mybatis mybatisspan>