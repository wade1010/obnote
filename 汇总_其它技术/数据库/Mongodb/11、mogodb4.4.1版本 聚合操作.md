分组统计: group()

简单聚合: aggregate()

强大统计: mapReduce()



参考 https://www.runoob.com/mongodb/mongodb-aggregate.html



插入数据

```javascript
db.goods.insert([{"goods_id":1,"cat_id":4,"goods_name":"KD876","goods_number":1,"click_count":7,"shop_price":1388.00,"add_time":1240902890},{"goods_id":4,"cat_id":8,"goods_name":"\u8bfa\u57fa\u4e9aN85\u539f\u88c5\u5145\u7535\u5668","goods_number":17,"click_count":0,"shop_price":58.00,"add_time":1241422402},{"goods_id":3,"cat_id":8,"goods_name":"\u8bfa\u57fa\u4e9a\u539f\u88c55800\u8033\u673a","goods_number":24,"click_count":3,"shop_price":68.00,"add_time":1241422082},{"goods_id":5,"cat_id":11,"goods_name":"\u7d22\u7231\u539f\u88c5M2\u5361\u8bfb\u5361\u5668","goods_number":8,"click_count":3,"shop_price":20.00,"add_time":1241422518},{"goods_id":6,"cat_id":11,"goods_name":"\u80dc\u521bKINGMAX\u5185\u5b58\u5361","goods_number":15,"click_count":0,"shop_price":42.00,"add_time":1241422573},{"goods_id":7,"cat_id":8,"goods_name":"\u8bfa\u57fa\u4e9aN85\u539f\u88c5\u7acb\u4f53\u58f0\u8033\u673aHS-82","goods_number":20,"click_count":0,"shop_price":100.00,"add_time":1241422785},{"goods_id":8,"cat_id":3,"goods_name":"\u98de\u5229\u6d669@9v","goods_number":1,"click_count":9,"shop_price":399.00,"add_time":1241425512},{"goods_id":9,"cat_id":3,"goods_name":"\u8bfa\u57fa\u4e9aE66","goods_number":4,"click_count":20,"shop_price":2298.00,"add_time":1241511871},{"goods_id":10,"cat_id":3,"goods_name":"\u7d22\u7231C702c","goods_number":7,"click_count":11,"shop_price":1328.00,"add_time":1241965622},{"goods_id":11,"cat_id":3,"goods_name":"\u7d22\u7231C702c","goods_number":1,"click_count":0,"shop_price":1300.00,"add_time":1241966951},{"goods_id":12,"cat_id":3,"goods_name":"\u6469\u6258\u7f57\u62c9A810","goods_number":8,"click_count":13,"shop_price":983.00,"add_time":1245297652}])
```



```javascript
db.goods.insert([{"goods_id":13,"cat_id":3,"goods_name":"\u8bfa\u57fa\u4e9a5320 XpressMusic","goods_number":8,"click_count":13,"shop_price":1311.00,"add_time":1241967762},{"goods_id":14,"cat_id":4,"goods_name":"\u8bfa\u57fa\u4e9a5800XM","goods_number":1,"click_count":6,"shop_price":2625.00,"add_time":1241968492},{"goods_id":15,"cat_id":3,"goods_name":"\u6469\u6258\u7f57\u62c9A810","goods_number":3,"click_count":8,"shop_price":788.00,"add_time":1241968703},{"goods_id":16,"cat_id":2,"goods_name":"\u6052\u57fa\u4f1f\u4e1aG101","goods_number":0,"click_count":3,"shop_price":823.33,"add_time":1241968949},{"goods_id":17,"cat_id":3,"goods_name":"\u590f\u65b0N7","goods_number":1,"click_count":2,"shop_price":2300.00,"add_time":1241969394},{"goods_id":18,"cat_id":4,"goods_name":"\u590f\u65b0T5","goods_number":1,"click_count":0,"shop_price":2878.00,"add_time":1241969533},{"goods_id":19,"cat_id":3,"goods_name":"\u4e09\u661fSGH-F258","goods_number":12,"click_count":7,"shop_price":858.00,"add_time":1241970139},{"goods_id":20,"cat_id":3,"goods_name":"\u4e09\u661fBC01","goods_number":12,"click_count":14,"shop_price":280.00,"add_time":1241970417},{"goods_id":21,"cat_id":3,"goods_name":"\u91d1\u7acb A30","goods_number":40,"click_count":4,"shop_price":2000.00,"add_time":1241970634},{"goods_id":22,"cat_id":3,"goods_name":"\u591a\u666e\u8fbeTouch HD","goods_number":1,"click_count":15,"shop_price":5999.00,"add_time":1241971076}])
```



```javascript
db.goods.insert([{"goods_id":23,"cat_id":5,"goods_name":"\u8bfa\u57fa\u4e9aN96","goods_number":8,"click_count":17,"shop_price":3700.00,"add_time":1241971488},{"goods_id":24,"cat_id":3,"goods_name":"P806","goods_number":100,"click_count":35,"shop_price":2000.00,"add_time":1241971981},{"goods_id":25,"cat_id":13,"goods_name":"\u5c0f\u7075\u901a\/\u56fa\u8bdd50\u5143\u5145\u503c\u5361","goods_number":2,"click_count":0,"shop_price":48.00,"add_time":1241972709},{"goods_id":26,"cat_id":13,"goods_name":"\u5c0f\u7075\u901a\/\u56fa\u8bdd20\u5143\u5145\u503c\u5361","goods_number":2,"click_count":0,"shop_price":19.00,"add_time":1241972789},{"goods_id":27,"cat_id":15,"goods_name":"\u8054\u901a100\u5143\u5145\u503c\u5361","goods_number":2,"click_count":0,"shop_price":95.00,"add_time":1241972894},{"goods_id":28,"cat_id":15,"goods_name":"\u8054\u901a50\u5143\u5145\u503c\u5361","goods_number":0,"click_count":0,"shop_price":45.00,"add_time":1241972976},{"goods_id":29,"cat_id":14,"goods_name":"\u79fb\u52a8100\u5143\u5145\u503c\u5361","goods_number":0,"click_count":0,"shop_price":90.00,"add_time":1241973022},{"goods_id":30,"cat_id":14,"goods_name":"\u79fb\u52a820\u5143\u5145\u503c\u5361","goods_number":9,"click_count":1,"shop_price":18.00,"add_time":1241973114},{"goods_id":31,"cat_id":3,"goods_name":"\u6469\u6258\u7f57\u62c9E8 ","goods_number":1,"click_count":5,"shop_price":1337.00,"add_time":1242110412},{"goods_id":32,"cat_id":3,"goods_name":"\u8bfa\u57fa\u4e9aN85","goods_number":4,"click_count":9,"shop_price":3010.00,"add_time":1242110760}])
```



1 :计算每个栏目下的商品数 count()操作

```javascript
db.goods.aggregate( [ {$group : { _id : "$cat_id" ,cnt : {$sum : 1}}}] )
```



```javascript
> db.goods.aggregate( [ {$group : { _id : "$cat_id" ,cnt : {$sum : 1}}}] )
{ "_id" : 8, "cnt" : 3 }
{ "_id" : 11, "cnt" : 2 }
{ "_id" : 14, "cnt" : 2 }
{ "_id" : 15, "cnt" : 2 }
{ "_id" : 4, "cnt" : 3 }
{ "_id" : 3, "cnt" : 15 }
{ "_id" : 2, "cnt" : 1 }
{ "_id" : 5, "cnt" : 1 }
{ "_id" : 13, "cnt" : 2 }
```



根据cat_id 升序



```javascript
db.goods.aggregate( [ {$group : { _id : "$cat_id" ,cnt : {$sum : 1}}},{ $sort: { _id: 1 } }] )
```





```javascript
> db.goods.aggregate( [ {$group : { _id : "$cat_id" ,cnt : {$sum : 1}}},{ $sort: { _id: 1 } }] )
{ "_id" : 2, "cnt" : 1 }
{ "_id" : 3, "cnt" : 15 }
{ "_id" : 4, "cnt" : 3 }
{ "_id" : 5, "cnt" : 1 }
{ "_id" : 8, "cnt" : 3 }
{ "_id" : 11, "cnt" : 2 }
{ "_id" : 13, "cnt" : 2 }
{ "_id" : 14, "cnt" : 2 }
{ "_id" : 15, "cnt" : 2 }
```







1 :计算每个栏目下shop_price>50的商品数 count()操作

 

```javascript
db.goods.aggregate( [  { $match : { shop_price : { $gt : 50 } } },{$group : { _id : "$cat_id" ,cnt : {$sum : 1}}},{ $sort: { _id: 1 } }] )
```



2 :计算每个栏目下的商品库存量 sum()操作



```javascript
db.goods.aggregate(
    [
    {
        "$group": {
            "_id": "$cat_id",
            "cnt": {
                "$sum": "$goods_number"
            }
        }
    }
]
)
```



输出结果

```javascript
{ "_id" : 8, "cnt" : 61 }
{ "_id" : 11, "cnt" : 23 }
{ "_id" : 14, "cnt" : 9 }
{ "_id" : 15, "cnt" : 2 }
{ "_id" : 4, "cnt" : 3 }
{ "_id" : 3, "cnt" : 203 }
{ "_id" : 2, "cnt" : 0 }
{ "_id" : 5, "cnt" : 8 }
{ "_id" : 13, "cnt" : 4 }
```





3 :计算每个栏目下最贵的商品价格 max()操作

```javascript
db.goods.aggregate(
[
    {
        "$group": {
            "_id": "$cat_id",
            "max_price": {
                "$max": "$shop_price"
            }
        }
    }
]
)
```



输出



```javascript
{ "_id" : 8, "max_price" : 100 }
{ "_id" : 11, "max_price" : 42 }
{ "_id" : 14, "max_price" : 90 }
{ "_id" : 15, "max_price" : 95 }
{ "_id" : 4, "max_price" : 2878 }
{ "_id" : 3, "max_price" : 5999 }
{ "_id" : 2, "max_price" : 823.33 }
{ "_id" : 5, "max_price" : 3700 }
{ "_id" : 13, "max_price" : 48 }
```





4、查询每个栏目下商品的平均价格



```javascript
db.goods.aggregate(
[
    {
        "$group": {
            "_id": "$cat_id",
            "avg_price": {
                "$avg": "$shop_price"
            }
        }
    }
]
)
```



输出



```javascript
{ "_id" : 2, "avg_price" : 823.33 }
{ "_id" : 5, "avg_price" : 3700 }
{ "_id" : 13, "avg_price" : 33.5 }
{ "_id" : 15, "avg_price" : 70 }
{ "_id" : 14, "avg_price" : 54 }
{ "_id" : 8, "avg_price" : 75.33333333333333 }
{ "_id" : 11, "avg_price" : 31 }
{ "_id" : 3, "avg_price" : 1746.0666666666666 }
{ "_id" : 4, "avg_price" : 2297 }
```





5、查询每个栏目下 价格大于50元的商品个数   并筛选出"满足条件的商品个数" 大于等于3的栏目 





```javascript
db.goods.aggregate(
[
    {
        "$match": {
            "shop_price": {
                "$gt": 50
            }
        }
    },
    {
        "$group": {
            "_id": "$cat_id",
            "cnt": {
                "$sum": 1
            }
        }
    },
    {
        "$match": {
            "cnt": {
                "$gte": 3
            }
        }
    }
]
)
```



输出



```javascript
{ "_id" : 8, "cnt" : 3 }
{ "_id" : 3, "cnt" : 15 }
{ "_id" : 4, "cnt" : 3 }
```





6、查询每个cat_id下的库存，并按库存量来排序



```javascript
db.goods.aggregate(
[
    {
        "$group": {
            "_id": "$cat_id",
            "cnt": {
                "$sum": "$goods_number"
            }
        }
    },
    {
        "$sort": {
            "cnt": 1
        }
    }
]
)
```



输出



```javascript
{ "_id" : 2, "cnt" : 0 }
{ "_id" : 15, "cnt" : 2 }
{ "_id" : 4, "cnt" : 3 }
{ "_id" : 13, "cnt" : 4 }
{ "_id" : 5, "cnt" : 8 }
{ "_id" : 14, "cnt" : 9 }
{ "_id" : 11, "cnt" : 23 }
{ "_id" : 8, "cnt" : 61 }
{ "_id" : 3, "cnt" : 203 }
```



6、查询每个cat_id下的库存，取库存量从大到小排序第三到第五名



```javascript
db.goods.aggregate(
[
    {
        "$group": {
            "_id": "$cat_id",
            "cnt": {
                "$sum": "$goods_number"
            }
        }
    },
    {
        "$sort": {
            "cnt": -1
        }
    },
    {
        "$skip": 2
    },
    {
        "$limit": 3
    }
])
```



输出



```javascript
{ "_id" : 11, "cnt" : 23 }
{ "_id" : 14, "cnt" : 9 }
{ "_id" : 5, "cnt" : 8 }
```





7、查询每个cat_id的商品平均价，并按均价由高到低排序



```javascript
db.goods.aggregate(
[
    {
        "$group": {
            "_id": "$cat_id",
            "avg_price": {
                "$avg": "$shop_price"
            }
        }
    },
    {
        "$sort": {
            "avg_price": -1
        }
    }
])
```



输出

```javascript
{ "_id" : 5, "avg_price" : 3700 }
{ "_id" : 4, "avg_price" : 2297 }
{ "_id" : 3, "avg_price" : 1746.0666666666666 }
{ "_id" : 2, "avg_price" : 823.33 }
{ "_id" : 8, "avg_price" : 75.33333333333333 }
{ "_id" : 15, "avg_price" : 70 }
{ "_id" : 14, "avg_price" : 54 }
{ "_id" : 13, "avg_price" : 33.5 }
{ "_id" : 11, "avg_price" : 31 } 
```

