本篇文章主要介绍了mongodb对应java的常用增删改查的api，以及和spring集成后mongoTemplate的常用方法使用，废话不多说，直接上代码：

1.首先上需要用到的两个实体类User和Home，对应用户和家乡

import java.util.List;

import org.springframework.data.mongodb.core.mapping.Document;

/**

 * java类转换为mongodb的文档,它有以下几种注释：

 * 1.@Id - 文档的唯一标识，在mongodb中为ObjectId，它是唯一的，通过时间戳+机器标识+进程ID+自增计数器（确保同一秒内产生的Id不会冲突）构成。

 * 2.@Document - 把一个java类声明为mongodb的文档，可以通过collection参数指定这个类对应的文档。

 * 3.@Indexed - 声明该字段需要索引，建索引可以大大的提高查询效率。

 * 4.@Transient - 映射忽略的字段，该字段不会保存到MongoDB

 * 5.@CompoundIndex - 复合索引的声明，建复合索引可以有效地提高多字段的查询效率。

 * 6.@PersistenceConstructor - 声明构造函数，作用是把从数据库取出的数据实例化为对象。该构造函数传入的值为从DBObject中取出的数据。

 * @author zhangguochen

 *

 */

@Document(collection="user")

public class User {



private String id;

private String name;

private int age;

private List interest;

private String wife;

private Home home;



public String getId() {

return id;

}

public void setId(String id) {

this.id = id;

}

public String getName() {

return name;

}

public void setName1(String name) {

this.name = name;

}

public int getAge() {

return age;

}

public void setAge(int age) {

this.age = age;

}

public List getInterest() {

return interest;

}

public void setInterest(List interest) {

this.interest = interest;

}

public String getWife() {

return wife;

}

public void setWife(String wife) {

this.wife = wife;

}

public Home getHome() {

return home;

}

public void setHome(Home home) {

this.home = home;

}

}

public class Home {



private String address;



public Home(String address) {

super();

this.address = address;

}

public String getAddress() {

return address;

}

public void setAddress(String address) {

this.address = address;

}

}



2.以下类MongoDBTest.java展示了mongodb的java api常用的增删改查的方法的使用

import java.util.List;

import java.util.Set;

import java.util.regex.Pattern;

import junit.framework.TestCase;

import org.bson.types.ObjectId;

import org.junit.Before;

import org.junit.BeforeClass;

import com.mongodb.BasicDBObject;

import com.mongodb.DB;

import com.mongodb.DBCollection;

import com.mongodb.DBCursor;

import com.mongodb.DBObject;

import com.mongodb.Mongo;

import com.mongodb.QueryBuilder;

import com.mongodb.QueryOperators;





public class MongoDBTest extends TestCase{



Mongo mongo = null;

DB db = null;

DBCollection user = null;



@BeforeClass

public static void setUpBeforeClass() throws Exception {

}



@Before

public void setUp() throws Exception {

//创建一个MongoDB的数据库连接对象，无参数的话它默认连接到当前机器的localhost地址，端口是27017。

mongo = new Mongo("192.168.225.101",27017);

//得到一个test的数据库，如果mongoDB中没有这个数据库，当向此库中添加数据的时候会自动创建

db = mongo.getDB("test");

db.authenticate("test", "test".toCharArray());

//获取到一个叫做"user"的集合，相当于关系型数据库中的"表"

user = db.getCollection("user");

}



/**

 * 查询所有的集合名称

 */

public void testGetAllCollections() {

Set collectionNames = db.getCollectionNames();

for(String name:collectionNames) {

System.out.println("collectionName:"+name);

}

}



/**

 * 查询所有的用户信息

 */

public void testFind() {

testInitTestData();

//find方法查询所有的数据并返回一个游标对象

DBCursor cursor = user.find();



while(cursor.hasNext()) {

print(cursor.next());

}

//获取数据总条数

int sum = cursor.count();

System.out.println("sum==="+sum);

}



/**

 * 查询第一条数据

 */

public void testFindOne() {

testInitTestData();

//只查询第一条数据

DBObject oneUser = user.findOne();

print(oneUser);

}



/**

 * 条件查询

 */

public void testConditionQuery() {

testInitTestData();

//查询id=50a1ed9965f413fa025166db

DBObject oneUser = user.findOne(new BasicDBObject("_id",new ObjectId("50a1ed9965f413fa025166db")));

print(oneUser);



//查询age=24

List userList1 = user.find(new BasicDBObject("age",24)).toArray();

print("        find age=24: ");

printList(userList1);



//查询age<=23

List userList2 = user.find(new BasicDBObject("age",new BasicDBObject("$gte",23))).toArray();

print("        find age<=23: ");

printList(userList2);



//查询age< br style='font-size:14px;font-style:normal;font-weight:400;color:rgb(51, 51, 51);' />List userList3 = user.find(new BasicDBObject("age",new BasicDBObject("$lte",20))).toArray();

print("        find age< br style='font-size:14px;font-style:normal;font-weight:400;color:rgb(51, 51, 51);' />printList(userList3);



//查询age!=25

List userList4 = user.find(new BasicDBObject("age",new BasicDBObject("$ne",25))).toArray();

print("        find age!=25: ");

printList(userList4);



//查询age in[23,24,27]

List userList5 = user.find(new BasicDBObject("age",new BasicDBObject(QueryOperators.IN,new int[]{23,24,27}))).toArray();

print("        find agein[23,24,27]: ");

printList(userList5);



//查询age not in[23,24,27]

List userList6 = user.find(new BasicDBObject("age",new BasicDBObject(QueryOperators.NIN,new int[]{23,24,27}))).toArray();

print("        find age not in[23,24,27]: ");

printList(userList6);



//查询29<age<=20

List userList7 = user.find(new BasicDBObject("age",new BasicDBObject("$gte",20).append("$lt", 29))).toArray();

print("        find 29<age<=20: ");

printList(userList7);



//查询age<24 and name="zhangguochen"

BasicDBObject query = new BasicDBObject();

query.put("age", new BasicDBObject("$gt",24));

query.put("name", "zhangguochen");

List userList8 = user.find(query).toArray();

print("        find age<24 and name='zhangguochen':");

printList(userList8);



//和上面的查询一样,用的是QueryBuilder对象

QueryBuilder queryBuilder = new QueryBuilder();

queryBuilder.and("age").greaterThan(24);

queryBuilder.and("name").equals("zhangguochen");

List userList82 = user.find(queryBuilder.get()).toArray();

print("        QueryBuilder find age<24 and name='zhangguochen':");

printList(userList82);



//查询所有的用户，并按照年龄升序排列

List userList9 = user.find().sort(new BasicDBObject("age",1)).toArray();

print("        find all sort age asc: ");

printList(userList9);



//查询特定字段

DBObject query1 = new BasicDBObject();//要查的条件 

        query.put("age", new BasicDBObject("$gt",20)); 

        DBObject field = new BasicDBObject();//要查的哪些字段 

        field.put("name", true); 

        field.put("age", true); 

        List userList10=user.find(query1,field).toArray();

        print("        select name,age where age<20");

        printList(userList10);



//查询部分数据

        DBObject query2 = new BasicDBObject();//查询条件

        query2.put("age", new BasicDBObject("$lt",27));

        DBObject fields = new BasicDBObject();//查询字段

        fields.put("name",true);

        fields.put("age", true);

        List userList11 = user.find(query2, fields, 1, 1).toArray();

print("        select age,name from user skip 1 limit 1:");

printList(userList11);



//模糊查询

DBObject fuzzy_query=new BasicDBObject();  

        String keyWord="zhang";  

        Pattern pattern = Pattern.compile("^" + keyWord + ".*$", Pattern.CASE_INSENSITIVE);  

        fuzzy_query.put("name", pattern);

        //根据name like zhang%查询

        List userList12 = user.find(fuzzy_query).toArray();

        print("        select * from user where name like 'zhang*'");

        printList(userList12);



}



/**

 * 删除用户数据

 */

public void testRemoveUser() {

testInitTestData();

DBObject query=new BasicDBObject();  

//删除age<24的数据

query.put("age", new BasicDBObject("$gt",24));

user.remove(query);

printList(user.find().toArray());

}



/**

 * 修改用户数据

 */

public void testUpdateUser() {



//update(query,set,false,true);

//query:需要修改的数据查询条件,相当于关系型数据库where后的语句

//set:需要设的值,相当于关系型数据库的set语句

//false:需要修改的数据如果不存在,是否插入新数据,false不插入,true插入

//true:如果查询出多条则不进行修改,false:只修改第一条



testInitTestData();



//整体更新

DBObject query=new BasicDBObject(); 

query.put("age", new BasicDBObject("$gt",15));

DBObject set=user.findOne(query);//一定是查询出来的DBObject,否则会丢掉一些列,整体更新

set.put("name", "Abc");

set.put("age", 19);

set.put("interest", new String[]{"hadoop","study","mongodb"});

DBObject zhangguochenAddress = new BasicDBObject();

zhangguochenAddress.put("address", "henan");

set.put("home", zhangguochenAddress);

user.update(query, //需要修改的数据条件

set,//需要赋的值

false,//数据如果不存在,是否新建

false);//false只修改第一条,true如果有多条就不修改

printList(user.find().toArray());



//局部更新,只更改某些列

//加上$set会是局部更新,不会丢掉某些列,只把name更新为"jindazhong",年龄更新为123

BasicDBObject set1 = new BasicDBObject("$set", new BasicDBObject("name","jindazhong").append("age", 123));

user.update(query, //需要修改的数据条件

set1,//需要赋的值

false,//数据如果不存在,是否新建

false);//false只修改第一条,true如果有多条就不修改

printList(user.find().toArray());



//批量更新

//user.updateMulti(new BasicDBObject("age",new BasicDBObject("$gt",16)), 

//new BasicDBObject("$set", new BasicDBObject("name","jindazhong").append("age", 123)));

//printList(user.find().toArray());



}



/**

 * 初始化测试数据

 */

public void testInitTestData() {

user.drop();

DBObject zhangguochen = new BasicDBObject();

zhangguochen.put("name", "zhangguochen");

zhangguochen.put("age", 25);

zhangguochen.put("interest", new String[]{"hadoop","study","mongodb"});

DBObject zhangguochenAddress = new BasicDBObject();

zhangguochenAddress.put("address", "henan");

zhangguochen.put("home", zhangguochenAddress);



DBObject jindazhong = new BasicDBObject();

jindazhong.put("name", "jindazhong");

jindazhong.put("age", 21);

jindazhong.put("interest", new String[]{"hadoop","mongodb"});

jindazhong.put("wife", "小龙女");

DBObject jindazhongAddress = new BasicDBObject();

jindazhongAddress.put("address", "shanghai");

jindazhong.put("home", jindazhongAddress);



DBObject yangzhi = new BasicDBObject();

yangzhi.put("name", "yangzhi");

yangzhi.put("age", 22);

yangzhi.put("interest", new String[]{"shopping","sing","hadoop"});

DBObject yangzhiAddress = new BasicDBObject();

yangzhiAddress.put("address", "hubei");

yangzhi.put("home", yangzhiAddress);



DBObject diaoyouwei = new BasicDBObject();

diaoyouwei.put("name", "diaoyouwei");

diaoyouwei.put("age", 23);

diaoyouwei.put("interest", new String[]{"notejs","sqoop"});

DBObject diaoyouweiAddress = new BasicDBObject();

diaoyouweiAddress.put("address", "shandong");

diaoyouwei.put("home", diaoyouweiAddress);



DBObject cuichongfei = new BasicDBObject();

cuichongfei.put("name", "cuichongfei");

cuichongfei.put("age", 24);

cuichongfei.put("interest", new String[]{"ebsdi","dq"});

cuichongfei.put("wife", "凤姐");

DBObject cuichongfeiAddress = new BasicDBObject();

cuichongfeiAddress.put("address", "shanxi");

cuichongfei.put("home", cuichongfeiAddress);



DBObject huanghu = new BasicDBObject();

huanghu.put("name", "huanghu");

huanghu.put("age", 25);

huanghu.put("interest", new String[]{"shopping","study"});

huanghu.put("wife", "黄蓉");

DBObject huanghuAddress = new BasicDBObject();

huanghuAddress.put("address", "guangdong");

huanghu.put("home", huanghuAddress);



DBObject houchangren = new BasicDBObject();

houchangren.put("name", "houchangren");

houchangren.put("age", 26);

houchangren.put("interest", new String[]{"dota","dq"});

DBObject houchangrenAddress = new BasicDBObject();

houchangrenAddress.put("address", "shandong");

houchangren.put("home", houchangrenAddress);



DBObject wangjuntao = new BasicDBObject();

wangjuntao.put("name", "wangjuntao");

wangjuntao.put("age", 27);

wangjuntao.put("interest", new String[]{"sport","study"});

wangjuntao.put("wife", "王语嫣");

DBObject wangjuntaoAddress = new BasicDBObject();

wangjuntaoAddress.put("address", "hebei");

wangjuntao.put("home", wangjuntaoAddress);



DBObject miaojiagui = new BasicDBObject();

miaojiagui.put("name", "miaojiagui");

miaojiagui.put("age", 28);

miaojiagui.put("interest", new String[]{"hadoop","study","linux"});

miaojiagui.put("wife", null);

DBObject miaojiaguiAddress = new BasicDBObject();

miaojiaguiAddress.put("address", "未知");

miaojiagui.put("home", miaojiaguiAddress);



DBObject longzhen = new BasicDBObject();

longzhen.put("name", "longzhen");

longzhen.put("age", 29);

longzhen.put("interest", new String[]{"study","cook"});

longzhen.put("wife", null);

DBObject longzhenAddress = new BasicDBObject();

longzhenAddress.put("address", "sichuan");

longzhen.put("home", longzhenAddress);



user.insert(zhangguochen);

user.insert(jindazhong);

user.insert(yangzhi);

user.insert(diaoyouwei);

user.insert(cuichongfei);

user.insert(huanghu);

user.insert(houchangren);

user.insert(wangjuntao);

user.insert(miaojiagui);

user.insert(longzhen);

}



public void testRemove() {

user.drop();

}



/**

 * 打印数据

 * @param object

 */

public void print(Object object){

System.out.println(object);

}



/**

 * 打印列表

 * @param objectList

 */

public void printList(List objectList) {

for(Object object:objectList) {

print(object);

}

}

}

以上代码小伙伴们直接拷贝到相关工具就可以测试运行哦！！！！



3.以下代码展示了mongodb和spring的集成的使用，主要是mongoTemplate类的使用。

import static org.springframework.data.mongodb.core.query.Criteria.where;

import java.util.ArrayList;

import java.util.List;

import com.jd.bse.incubator.mongo.entity.Home;

import com.jd.bse.incubator.mongo.entity.User;

import org.junit.Test;

import org.junit.runner.RunWith;

import org.springframework.beans.factory.annotation.Autowired;

import org.springframework.data.mongodb.core.MongoTemplate;

import org.springframework.data.mongodb.core.query.Criteria;

import org.springframework.data.mongodb.core.query.Query;

import org.springframework.data.mongodb.core.query.Update;

import org.springframework.test.context.ContextConfiguration;

import org.springframework.test.context.junit4.SpringJUnit4ClassRunner;



@RunWith(SpringJUnit4ClassRunner.class)

@ContextConfiguration(locations={"classpath:/spring/mongodb-config.xml"})//这里要用到mongodb的配置文件

public class MongoSpringTest{



@Autowired

private MongoTemplate mongoTemplate;



/**

 * 插入用户信息

 */

@Test

public void testAddUser() {

User zhanggc = new User();

zhanggc.setName1("zhangguochen");

zhanggc.setAge(29);

List interests = new ArrayList();

interests.add("stuty");

interests.add("hadoop");

zhanggc.setInterest(interests);

Home home = new Home("henan");

zhanggc.setHome(home);





//插入数据

mongoTemplate.insert(zhanggc);

}



/**

 * 查询用户信息

 */

@Test

public void testQueryUser() {

//查询主要用到Query和Criteria两个对象

Query query = new Query();

Criteria criteria = where("age").gt(22);



//criteria.and("name").is("cuichongfei");等于

//List interests = new ArrayList();

//interests.add("study");

//interests.add("linux");

//criteria.and("interest").in(interests);   in查询

//criteria.and("home.address").is("henan"); 内嵌文档查询

//criteria.and("").exists(false);           列存在

//criteria.and("").lte();                   小于等于

//criteria.and("").regex("");               正则表达式

//criteria.and("").ne("");                  不等于



query.addCriteria(criteria);

List userList1 = mongoTemplate.find(query, User.class);

printList(userList1);





//排序查询sort方法,按照age降序排列

//query.sort().on("age", Order.DESCENDING);

//List userList2 = mongoTemplate.find(query, User.class);

//printList(userList2);



//指定字段查询,只查询age和name两个字段

//query.fields().include("age").include("name");

//List userList3 = mongoTemplate.find(query, User.class);

//printList(userList3);



//分页查询

//query.skip(2).limit(3);

//List userList4 = mongoTemplate.find(query, User.class);

//printList(userList4);



//查询所有

//printList(mongoTemplate.findAll(User.class));



//统计数据量

//System.out.println(mongoTemplate.count(query, User.class));



}



/**

 * 更新用户数据

 */

@Test

public void testUpdateUser() {

//update(query,update,class)

//Query query:需要更新哪些用户,查询参数

//Update update:操作符,需要对数据做什么更新

//Class class:实体类



//更新age大于24的用户信息

Query query = new Query();

query.addCriteria(where("age").gt(24));



Update update = new Update();

//age值加2

update.inc("age", 2);

//update.set("name", "zhangsan"); 直接赋值

//update.unset("name");           删去字段   

//update.push("interest", "java"); 把java追加到interest里面,interest一定得是数组

//update.pushAll("interest", new String[]{".net","mq"}) 用法同push,只是pushAll一定可以追加多个值到一个数组字段内

//update.pull("interest", "study"); 作用和push相反,从interest字段中删除一个等于value的值

//update.pullAll("interest", new String[]{"sing","dota"})作用和pushAll相反

//update.addToSet("interest", "study") 把一个值添加到数组字段中,而且只有当这个值不在数组内的时候才增加

//update.rename("oldName", "newName") 字段重命名



//只更新第一条记录,age加2,name值更新为zhangsan

mongoTemplate.updateFirst(query, new Update().inc("age", 2).set("name", "zhangsan"), User.class);



//批量更新,更新所有查询到的数据

mongoTemplate.updateMulti(query, update, User.class);



}





/**

 * 测试删除数据

 */

@Test

public void testRemoveUser() {

Query query = new Query();

//query.addCriteria(where("age").gt(22));

Criteria criteria = where("age").gt(22);

//删除年龄大于22岁的用户

query.addCriteria(criteria);

mongoTemplate.remove(query, User.class);

}





public MongoTemplate getMongoTemplate() {

return mongoTemplate;

}



public void setMongoTemplate(MongoTemplate mongoTemplate) {

this.mongoTemplate = mongoTemplate;

}



public void printList(List userList) {

System.out.println("**********************************************************************************************************");

for(User user:userList) {

System.out.println(user);

}

System.out.println("**********************************************************************************************************");

}

}



4.以下是/mongodb-config.xml配置文件

< xml version="1.0" encoding="UTF-8" >



xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"

xmlns:mongo="http://www.springframework.org/schema/data/mongo"

xmlns:context="http://www.springframework.org/schema/context"

xsi:schemaLocation="

http://www.springframework.org/schema/beans http://www.springframework.org/schema/beans/spring-beans-3.1.xsd

http://www.springframework.org/schema/aop http://www.springframework.org/schema/aop/spring-aop-3.1.xsd

http://www.springframework.org/schema/tx http://www.springframework.org/schema/tx/spring-tx-3.1.xsd

http://www.springframework.org/schema/util http://www.springframework.org/schema/util/spring-util-3.1.xsd

http://www.springframework.org/schema/context http://www.springframework.org/schema/context/spring-context-3.1.xsd

http://www.springframework.org/schema/data/mongo http://www.springframework.org/schema/data/mongo/spring-mongo-1.0.xsd"<

















       

             

              classpath:conf/mongo.properties  

           

       

    



   





 



















5.以下是配置文件mongo.properties，也就是mongodb的连接信息

mongo.host.job=192.168.232.62:27017

mongo.dbname.job=erp

mongo.needauth.job=true

mongo.username.job=erp

mongo.password.job=erp