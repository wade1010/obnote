Field是collection的一个字段,系统将会利用filed的值,来计算应该分到哪一个片上.

这个filed叫”片键”, shard key



mongodb不是从单篇文档的级别,绝对平均的散落在各个片上,

 

而是N篇文档,形成一个块"chunk",

优先放在某个片上,

当这片上的chunk,比另一个片的chunk,区别比较大时, (>=3) ,会把本片上的chunk,移到另一个片上, 以chunk为单位,

维护片之间的数据均衡

 

问: 为什么插入了10万条数据,才2个chunk?

答: 说明chunk比较大(默认是64M)

在config数据库中,修改chunksize的值.

 

问: 既然优先往某个片上插入,当chunk失衡时,再移动chunk,

自然,随着数据的增多,shard的实例之间,有chunk来回移动的现象,这将带来什么问题?

答: 服务器之间IO的增加,

 

接上问: 能否我定义一个规则, 某N条数据形成1个块,预告分配M个chunk,

M个chunk预告分配在不同片上.

以后的数据直接入各自预分配好的chunk,不再来回移动?

 

答: 能, 手动预先分片!



Mongodb 分片与chunk

问题---我们向mongos路由器,添加了10条数据,发现并没有均匀的分布在B,D两个片上, 而是都在B上.

 

原因: mongodb并不是按行的级别,在片上绝对的平均分配.

而是以块为单位,来各片上寻求平衡.

 

过程是这样的--------

1: 数据先往主片上添加,都放在一个chunk(块)里, 这个块达到一定大小(默认是64M), 再生成新块.

2: 新块仍然是主片上,

3: configdb判断主片上有2块,而其他片的chunk过少,则会自动移动chunk过去.

 

 

在大型系统中,chunk的自动移动,(后台balance程序控制的), 会加剧IO的压力.

 

----我们可以根据业务量,合适的推测数量的增长,对数据进行预先分片, pre-split

即手工分片,而不自动分片.

 

注意: 预先分片的collection得是空的

Use admin  //切换到admin数据库,进行管理

db.runCommand({split:’database.collection’,middle:{_id:值}});

例:

db.runCommand({split:’shop.goods’,middle:{_id:10000}});

db.runCommand({split:’shop.goods’,middle:{_id:20000}});

....

db.runCommand({split:’shop.goods’,middle:{_id:120000}});

这样将会把

[0,10000]-->切成1个块chunk

(10000,20000]->切成一个块chunk.

....

(110000,120000]->切成一个块chunk.

 

,系统会预告生成这些空块,并在shard片之间达到平衡.

然后再插入数据,chunk不会再自动转移.



我是跟着下面两个链接搭建练手的



参考1  https://blog.csdn.net/quanmaoluo5461/article/details/85164588   

参考2 https://www.cnblogs.com/liushuchen/p/11811451.html



![](https://gitee.com/hxc8/images7/raw/master/img/202407190809541.jpg)

