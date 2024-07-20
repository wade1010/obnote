[https://blog.csdn.net/qq_19387933/article/details/123256034](https://blog.csdn.net/qq_19387933/article/details/123256034)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEb7190cf8c9b6cd7b832f2260d71b16f1截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEcc111b8a6e0557061666af62b2280930截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEb0050bedefd04c8339dfd4d74e134d0e截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEaaf5c4ac60e6bd01b9fa6e6990bb463d截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEaf8dce6ed1fd7b2075b45e637c2bb908截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE71136ef7a5aae361ac36f439bd72ab03截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEd3fb3f495025cad489ed787a863b2685截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE3061d316269d0244dcac8e7fcddafae6截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE278c08fc4c4db9749015ffe5366f14b7截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEfff3de600ed4c33197505787d92fde77截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE113e82039f039ccd0698ee91db7c73c1截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEe87e150316fd9d76531c0a1d699e8904截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE20b71cc7ac32f4a48969dcd310b72172截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEe3c40f178f59a47305bdeebf35ea4b5c截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEdfddf9c0d6267fe9b78ebac5637dc36b截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEd8ba01417044cf0ce9b22b8c243bccb7截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE105241d8601f821f5e4d1e626c6761b0截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE173839cf6588809c5bb5597d3d8003c3截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE3a2909ad9c1f676f6989e9360e51734e截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEe2bed92444b26d4b56020339f28a7228截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE893f5190cc28930fea36350e1d402499截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE01d7e51c6fddb764c36550acf82dd515截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE495b1ef6ca43812daaa540f133837a69截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEc9a6e3473fca4b192e873b263fe23936截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCEf006a88372b08b74d41dd9e9a38b79e0截图.png)

常用分为两类

1、实体类 类型，也就是第四种方法

2、@Param  也就是第五种方法

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE3f24e781214d5877f798370bd53defbe截图.png)

## 批量删除

只能使用${}，如果使用#{}，则解析后的sql语句为delete from t_user where id in ('1,2,3')，这样是将1,2,3看做是一个整体，只有id为1,2,3的数据会被删除。正确的语句应该是delete from t_user where id in (1,2,3)，或者delete from t_user where id in ('1','2','3')

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE39697cdc104fcfc2f48084ad95292c55截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE5a5f82eefa1c4ad604e4a21e4a40004a截图.png)

批量添加

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE9e3356bc1bb742e4de4f9a3857cd6437截图.png)

## SQL片段

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/springboot/b站MyBatis/images/WEBRESOURCE2a2e2c55b7e7e290b1031ba12ed02837截图.png)

MyBatis的缓存

MyBatis的一级缓存

一级缓存是SqlSession级别的，通过同一个SqlSession查询的数据会被缓存，下次查询相同的数据，就会从缓存中直接获取，不会从数据库重新访问

使一级缓存失效的四种情况：

不同的SqlSession对应不同的一级缓存

同一个SqlSession但是查询条件不同

同一个SqlSession两次查询期间执行了任何一次增删改操作

同一个SqlSession两次查询期间手动清空了缓存

MyBatis的二级缓存

二级缓存是SqlSessionFactory级别，通过同一个SqlSessionFactory创建的SqlSession查询的结果会被缓存；此后若再次执行相同的查询语句，结果就会从缓存中获取

二级缓存开启的条件

在核心配置文件中，设置全局配置属性cacheEnabled=“true”，默认为true，不需要设置

在映射文件中设置标签

二级缓存必须在SqlSession关闭或提交之后有效

查询的数据所转换的实体类类型必须实现序列化的接口

使二级缓存失效的情况：两次查询之间执行了任意的增删改，会使一级和二级缓存同时失效

二级缓存的相关配置

在mapper配置文件中添加的cache标签可以设置一些属性

eviction属性：缓存回收策略

LRU（Least Recently Used） – 最近最少使用的：移除最长时间不被使用的对象。

FIFO（First in First out） – 先进先出：按对象进入缓存的顺序来移除它们。

SOFT – 软引用：移除基于垃圾回收器状态和软引用规则的对象。

WEAK – 弱引用：更积极地移除基于垃圾收集器状态和弱引用规则的对象。

默认的是 LRU

flushInterval属性：刷新间隔，单位毫秒

默认情况是不设置，也就是没有刷新间隔，缓存仅仅调用语句（增删改）时刷新

size属性：引用数目，正整数

代表缓存最多可以存储多少个对象，太大容易导致内存溢出

readOnly属性：只读，true/false

true：只读缓存；会给所有调用者返回缓存对象的相同实例。因此这些对象不能被修改。这提供了很重要的性能优势。

false：读写缓存；会返回缓存对象的拷贝（通过序列化）。这会慢一些，但是安全，因此默认是false

MyBatis缓存查询的顺序

先查询二级缓存，因为二级缓存中可能会有其他程序已经查出来的数据，可以拿来直接使用

如果二级缓存没有命中，再查询一级缓存

如果一级缓存也没有命中，则查询数据库

SqlSession关闭之后，一级缓存中的数据会写入二级缓存

————————————————

版权声明：本文为CSDN博主「苍茗」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/qq_19387933/article/details/123256034](https://blog.csdn.net/qq_19387933/article/details/123256034)