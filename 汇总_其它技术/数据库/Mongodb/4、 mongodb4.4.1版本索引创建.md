1:索引提高查询速度,降低写入速度,权衡常用的查询字段,不必在太多列上建索引

2. 在mongodb中,索引可以按字段升序/降序来创建,便于排序

默认是用btree来组织索引文件,2.4版本以后,也允许建立hash索引



模拟创建10000条数据



```javascript
for (var i=1;i<10000;i++){
    db.stu.insert({sn:i,name:'student'+i});
}
```





```javascript
> db.stu.find({sn:99});
{ "_id" : ObjectId("5faa4448447637538d4c828a"), "sn" : 99, "name" : "student99" }
```



查看explan 帮助



```javascript
> db.stu.explain().help()
Explainable operations
	.aggregate(...) - explain an aggregation operation
	.count(...) - explain a count operation
	.distinct(...) - explain a distinct operation
	.find(...) - get an explainable query
	.findAndModify(...) - explain a findAndModify operation
	.mapReduce(...) - explain a mapReduce operation
	.remove(...) - explain a remove operation
	.update(...) - explain an update operation
Explainable collection methods
	.getCollection()
	.getVerbosity()
	.setVerbosity(verbosity)
```



查看执行计划



```javascript
> db.stu.find({sn:99}).explain()
{
	"queryPlanner" : {
		"plannerVersion" : 1,
		"namespace" : "stu.stu",
		"indexFilterSet" : false,
		"parsedQuery" : {
			"sn" : {
				"$eq" : 99
			}
		},
		"queryHash" : "E4C49B53",
		"planCacheKey" : "E4C49B53",
		"winningPlan" : {
			"stage" : "COLLSCAN",
			"filter" : {
				"sn" : {
					"$eq" : 99
				}
			},
			"direction" : "forward"
		},
		"rejectedPlans" : [ ]
	},
	"serverInfo" : {
		"host" : "2ca38190de40",
		"port" : 27017,
		"version" : "4.4.1",
		"gitVersion" : "ad91a93a5a31e175f5cbf8c69561e788bbc55ce1"
	},
	"ok" : 1
}
```





对sn创建索引  1 升序



```javascript
> db.stu.createIndex({sn:1}) 
{
	"createdCollectionAutomatically" : false,
	"numIndexesBefore" : 1,
	"numIndexesAfter" : 2,
	"ok" : 1
}
```



对name 创建索引   -1 升序



```javascript
> db.stu.createIndex({name:-1})
{
	"createdCollectionAutomatically" : false,
	"numIndexesBefore" : 2,
	"numIndexesAfter" : 3,
	"ok" : 1
}
```



再查看执行计划



```javascript
> db.stu.find({sn:99}).explain()
{
	"queryPlanner" : {
		"plannerVersion" : 1,
		"namespace" : "stu.stu",
		"indexFilterSet" : false,
		"parsedQuery" : {//查询参数
			"sn" : {
				"$eq" : 99
			}
		},
		"queryHash" : "E4C49B53",
		"planCacheKey" : "5858DE05",
		"winningPlan" : {//查询优化器针对该query所返回的最优执行计划的详细内容。
			"stage" : "FETCH",//最优查询计划的状态，FETCH:根据索引查询、
			"inputStage" : {
				"stage" : "IXSCAN",//查询的子状态，此处是索引扫描
				"keyPattern" : {//扫描的索引内容
					"sn" : 1
				},
				"indexName" : "sn_1",//走的索引名称
				"isMultiKey" : false,//如果索引建立在array上，此处将是true。
				"multiKeyPaths" : {
					"sn" : [ ]
				},
				"isUnique" : false,
				"isSparse" : false,
				"isPartial" : false,
				"indexVersion" : 2,
				"direction" : "forward",//此query的查询顺序，此处是forward，如果用了.sort({w:-1})将显示backward
				"indexBounds" : {//索引包含的字段
					"sn" : [
						"[99.0, 99.0]"
					]
				}
			}
		},
		"rejectedPlans" : [ ]//优化器拒绝的查询计划
	},
	"serverInfo" : {
		"host" : "2ca38190de40",
		"port" : 27017,
		"version" : "4.4.1",
		"gitVersion" : "ad91a93a5a31e175f5cbf8c69561e788bbc55ce1"
	},
	"ok" : 1
}
```



获取所有索引



```javascript
> db.stu.getIndexes()
[
	{
		"v" : 2,
		"key" : {
			"_id" : 1
		},
		"name" : "_id_"
	},
	{
		"v" : 2,
		"key" : {
			"sn" : 1
		},
		"name" : "sn_1"
	}
]

```



删除索引



```javascript
> db.stu.dropIndex({name:-1})
{ "nIndexesWas" : 3, "ok" : 1 }
```



删除所有索引



```javascript
> db.stu.dropIndexes()
{
	"nIndexesWas" : 2,
	"msg" : "non-_id indexes dropped for collection",
	"ok" : 1
}
```





增加多列索引



```javascript
> db.stu.createIndex({sn:1,name:-1})
{
	"createdCollectionAutomatically" : false,
	"numIndexesBefore" : 1,
	"numIndexesAfter" : 2,
	"ok" : 1
}
```







```javascript
> db.stu.getIndexes()
[
	{
		"v" : 2,
		"key" : {
			"_id" : 1
		},
		"name" : "_id_"
	},
	{
		"v" : 2,
		"key" : {
			"sn" : 1,
			"name" : -1
		},
		"name" : "sn_1_name_-1"
	}
]
```







创建子文档索引



```javascript
> db.shop.insert({name:'njy',spec:{weight:200,area:'taiwang'}});
WriteResult({ "nInserted" : 1 })
> db.shop.insert({name:'sanxing',spec:{weight:300,area:'hanguo'}});
WriteResult({ "nInserted" : 1 })
```





```javascript
> db.shop.createIndex({'spec.area':1})
{
	"createdCollectionAutomatically" : false,
	"numIndexesBefore" : 1,
	"numIndexesAfter" : 2,
	"ok" : 1
}
```





```javascript
> db.stu.getIndexes()
[
	{
		"v" : 2,
		"key" : {
			"_id" : 1
		},
		"name" : "_id_"
	},
	{
		"v" : 2,
		"key" : {
			"sn" : 1,
			"name" : -1
		},
		"name" : "sn_1_name_-1"
	}
]
```



唯一索引



db.teacher.insert({email:'a@163.com'});

db.teacher.insert({email:'b@163.com'});



```javascript
> db.teacher.createIndex({email:1},{unique:true});
{
	"createdCollectionAutomatically" : false,
	"numIndexesBefore" : 1,
	"numIndexesAfter" : 2,
	"ok" : 1
}
```



```javascript
> db.teacher.getIndexes()
[
	{
		"v" : 2,
		"key" : {
			"_id" : 1
		},
		"name" : "_id_"
	},
	{
		"v" : 2,
		"unique" : true,
		"key" : {
			"email" : 1
		},
		"name" : "email_1"
	}
]
```





稀疏索引



稀疏索引的特点------如果针对field做索引,针对不含field列的文档,将不建立索引.

与之相对,普通索引,会把该文档的field列的值认为NULL,并建索引.

适宜于: 小部分文档含有某列时.

db.collection.createIndex({field:1/-1},{sparse:true});





hash索引



对范围查询支持很差





重建索引



一张表经过一个表经过很多次修改后,导致表的文件产生空洞,索引文件也如此.

可以通过索引的重建,减少索引文件碎片,并提高索引的效率.

类似mysql中的optimize table



