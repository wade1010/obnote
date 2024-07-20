MapReduce可以简单理解为是个可以分布式group的东西



Map-Reduce是一种计算模型，简单的说就是将大批量的工作（数据）分解（MAP）执行，然后再将结果合并成最终结果（REDUCE）。

MongoDB提供的Map-Reduce非常灵活，对于大规模数据分析也相当实用。



参考  https://www.runoob.com/mongodb/mongodb-map-reduce.html



mapReduce 随着"大数据"概念而流行.

其实mapReduce的概念非常简单,

从功能上说,相当于RDBMS(传统型数据库)的 group 操作



mapReduce的真正强项在哪?

答:在于分布式,当数据非常大时,像google,有N多数据中心,

数据都不在地球的一端,用group力所不及.



group既然不支持分布式,单台服务器的运算能力必然是有限的.



而mapRecuce支持分布式,支持大量的服务器同时工作,

用蛮力来统计.



mapRecuce的工作过程:

map-->映射

reduce->归约



map: 先是把属于同一个组的数据,映射到一个数组上.cat_id-3 [23,2,6,7]

reduce: 把数组(同一组)的数据,进行运算.





1、计算每个栏目的库存总量



```javascript
db.goods.mapReduce(
function() {
   emit(this.cat_id,this.goods_number)
},
  function(cat_id, goods_number) {
   return Array.sum(goods_number)
},
{  
    out:"total"
}
)
```



out:"total" 是将结果存在total这个表中



输出



```javascript
{ "result" : "total", "ok" : 1 }
```



他是把结果放在了total的这个表中



```javascript
> db.total.find()
{ "_id" : 4, "value" : 3 }
{ "_id" : 3, "value" : 203 }
{ "_id" : 2, "value" : 0 }
{ "_id" : 5, "value" : 8 }
{ "_id" : 13, "value" : 4 }
{ "_id" : 15, "value" : 2 }
{ "_id" : 8, "value" : 61 }
{ "_id" : 11, "value" : 23 }
{ "_id" : 14, "value" : 9 }
```





添加点东西



```javascript
db.goods.mapReduce(
function() {
   emit(this.cat_id,this.goods_number)
},
  function(cat_id, goods_number) {
   return Array.sum(goods_number)
},
{  
   out:{inline:1}
}
)
```



out:{inline:1} 是将结果打印出来



2、计算每个栏目商品平均价格



```javascript
db.goods.mapReduce(
function() {
   emit(this.cat_id,this.shop_price)
},
  function(cat_id, shop_price_arr) {
   return Array.avg(shop_price_arr)
},
{  
   out:{inline:1}
}
)
```



输出



```javascript
{
	"results" : [
		{
			"_id" : 8,
			"value" : 75.33333333333333
		},
		{
			"_id" : 11,
			"value" : 31
		},
		{
			"_id" : 14,
			"value" : 54
		},
		{
			"_id" : 15,
			"value" : 70
		},
		{
			"_id" : 4,
			"value" : 2297
		},
		{
			"_id" : 3,
			"value" : 1746.0666666666666
		},
		{
			"_id" : 2,
			"value" : 823.33
		},
		{
			"_id" : 5,
			"value" : 3700
		},
		{
			"_id" : 13,
			"value" : 33.5
		}
	],
	"ok" : 1
}
```

