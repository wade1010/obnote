spring-data-MongoDB 更新的问题（1.0.0 M2）

下午遇到一个问题，用spring data去更新MongDB

在使用的过程中 用的是 mongoTemolate.updateFirst（）的方法：

mongoTemplate.updateFirst(new Query (Criteria.where("name").is("miller")),new Update().set("name","miller_cn"));



这些都是没有问题了 ，都能成功的更新数据库。

在后来遇到一个非常郁闷的问题，用上面的语句通过（_id）去更新数据库的时候却是怎么也不能成功。

以为自己的query语句写错了，遂做了以下测试：

把Query提出来



Query q = new Query(Criteria.where("_id").is("123456.....")));



通过 findone方法测试

mongoTemplate.findOne(q,Person.class);

能够返回结果，通过

mongoTemplate.updateFirst(q,new Update().set("name","miller_cn"));



测试不通过。

我就纳闷了，相同的Query语句，却又一个能通过另外的却不行。后来又试了

Query q1 = new Query(Criteria.where("id").is("123456.....")));



结果同上。



经过了一番搜索

在http://forum.springsource.org/showthread.php?107358-Id-bug-in-where-criteria-Spring-Data-MongoDB-1.0.0.M2看到了和我遇到一样问题的兄弟，看了看他遇到的问题，下面有人回答 ：it's a bug.我晕，这不麻烦了，依据id更新数据可是经常用的啊

唉，继续看，发现在这里有个leewill老兄给出了解决办法

Query q = query(where("_id").is(new ObjectId(“id”)));



http://forum.springsource.org/showthread.php?108176-mongoTemplate-update-by-ID-not-work&p=358657

依照他写的改代码，发现不行 我的new Query（）里面不能跟静态的where方法，遂这个问题变成了 Query（where）的问题了，查看spring-data的文档 解决了

引入

import static org.springframework.data.document.mongodb.query.Criteria.where;



这个包

到此 问题解决

呵呵  记录一下 希望能给大家使用spring data mongo的时候提供一点帮助

我用的是M2版本







感谢leewill 老兄

