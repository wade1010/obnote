### 版本  7.5.0


#### 创建索引

```
PUT /test
```


#### 获取索引


```
GET /test
```

#### 删除索引

```
DELETE /test
```
#### 更新数据


```

POST /test/_doc/1
{
  "name":"bob",
  "age":30,
  "sex":"男"
}
```


#### 添加数据

```
PUT /test/_doc/1
{
  "name":"bob",
  "age":19,
  "sex":"男"
}
```

#### 添加数据自动生成_id


```
POST /test/_doc
{
  "name":"bob",
  "age":19,
  "sex":"男"
}
```

返回结果


```
{
  "_index" : "test",
  "_type" : "_doc",
  "_id" : "r1YkIXUBABh5Y4PSx8iM",
  "_version" : 1,
  "result" : "created",
  "_shards" : {
    "total" : 2,
    "successful" : 1,
    "failed" : 0
  },
  "_seq_no" : 14,
  "_primary_term" : 1
}
```



#### 获取数据


```
GET /test/_doc/1
```

#### 获取索引下所有


```
GET /test/_search
```

结果


```
{
  "took" : 0,
  "timed_out" : false,
  "_shards" : {
    "total" : 1,
    "successful" : 1,
    "skipped" : 0,
    "failed" : 0
  },
  "hits" : {
    "total" : {
      "value" : 5,
      "relation" : "eq"
    },
    "max_score" : 1.0,
    "hits" : [
      {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "1",
        "_score" : 1.0,
        "_source" : {
          "name" : "bob",
          "age" : 19,
          "sex" : "男"
        }
      },
      {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "nFYiIXUBABh5Y4PS08f-",
        "_score" : 1.0,
        "_source" : {
          "name" : "bob",
          "age" : 19,
          "sex" : "男"
        }
      },
      {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "x1YjIXUBABh5Y4PSM8fu",
        "_score" : 1.0,
        "_source" : {
          "name" : "bob",
          "age" : 19,
          "sex" : "男"
        }
      },
      {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "2",
        "_score" : 1.0,
        "_source" : {
          "id" : 2,
          "name" : "bob",
          "age" : 19,
          "sex" : "男"
        }
      },
      {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "r1YkIXUBABh5Y4PSx8iM",
        "_score" : 1.0,
        "_source" : {
          "name" : "bob",
          "age" : 19,
          "sex" : "男"
        }
      }
    ]
  }
}

```

#### 根据多个ID进行查询


```
GET /test/_mget
{
  "ids":[1,2]
}
```
返回

```
{
  "docs" : [
    {
      "_index" : "test",
      "_type" : "_doc",
      "_id" : "1",
      "_version" : 11,
      "_seq_no" : 10,
      "_primary_term" : 1,
      "found" : true,
      "_source" : {
        "name" : "bob",
        "age" : 19,
        "sex" : "男"
      }
    },
    {
      "_index" : "test",
      "_type" : "_doc",
      "_id" : "2",
      "_version" : 1,
      "_seq_no" : 13,
      "_primary_term" : 1,
      "found" : true,
      "_source" : {
        "id" : 2,
        "name" : "bob",
        "age" : 19,
        "sex" : "男"
      }
    }
  ]
}

```

### 复杂条件查询

#### 查询年龄为20的


```
GET /test/_search?q=age:20
```
#### 查询年龄为10-19的 包括10 和19
```
GET /test/_search?q=age[10 TO 19]
```

#### 查询年龄10-30自荐 按年龄降序 从0条数据到第1条数据

```
GET /test/_search?q=age[10 TO 30]&sort=age:desc&from=0&size=1
```

#### 查询年龄10-30自荐 按年龄降序 从0条数据到第1条数据 显示name和age字段

```
GET /test/_search?q=age[10 TO 30]&sort=age:desc&from=0&size=2&_source=name,age
```