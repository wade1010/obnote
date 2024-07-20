DSL 查询使用 JSON 格式的请求体与 Elasticsearch 交互，可以实现各种各样的查询需求。

DSL查询语句查主要分两种类型：

1、叶子查询语句:用于查询特殊字段的特殊值，例如：match, term, range 等。

2、复合查询语句:可以合并其他的叶子查询或复合查询，从而实现非常复杂的查询逻辑。


## 常用关键词

```
query: 用于包含查询使用到的语法
match_all: 最简单的查询，获取索引所有数据，类似搜索 *。如：”query”:{“match_all”:{}}
bool: 复合查询，可以包含多个查询条件，主要有(must,must_not,should)
must: 用于包含逻辑与查询条件，即所有查询条件都满足才行
must_not: 用于包含逻辑非查询条件，即不包含所有查询的条件数据
should: 用于包含逻辑或查询条件，即其中有一个条件满足即可
filter: 与must一样，包含的所有条件都要满足，但不会参与计算分值，查询速度上会提升不少
match:匹配查询，用于匹配指定属性数据，也可以匹配时间，IP等特殊数据 注意： match匹配不会解析通配符，匹配的效果受到索引属性类型影响，如果索引属性设置了分词，那么match匹配也会分词匹配，他也不解析”“，但可以设置逻辑关系来处理
operator: 匹配逻辑关系，默认是or，可设置为and，这样可达到精确匹配的效果
query_string: 使用查询解析器来解析查询内容，如port:80 AND server:http。注意：此类型请求有很多选项属性，可以设置一些特殊的行为
term: 查找包含在反向索引中指定的确切术语的文档
terms: 筛选具有匹配任何条件的字段，如”terms” : { “user” : [“kimchy”, “elasticsearch”]}
range: 将文档与具有特定范围内的字段相匹配。Lucene查询的类型依赖于字段类型，对于字符串字段，即TermRangeQuery，而对于number/date字段，查询是一个数字的范围。如：”range”:{“port”:{“gte”:10,”lte”:20,”boost”:2.0}}
gte: 大于或等于
gt: 大于
lte: 小于或等于
lt: 小于
boost: 设置查询的boost值，默认值为1.0
exists: 返回在原始字段中至少有一个非空值的文档，注意：”“,”-“这些都不算是空值
prefix: 匹配包含带有指定前缀字段的字段(没有分析)，前缀查询映射到Lucene前缀查询，如：”prefix” : { “user” : “ki” }，查询user数据前缀为ki的doc
wildcard: 匹配具有匹配通配符表达式的字段(未分析)的文档。支持(*通配符)是匹配任何字符序列(包括空的序列)和(?通配符)它匹配任何一个字符。注意，这个查询可能比较慢，因为它需要迭代多个术语。为了防止非常慢的通配符查询，一个通配符项不应该从通配符开始，或者?通配符查询映射到Lucene通配符查询。如：”wildcard” : { “user” : “ki*y” }
regexp: regexp查询允许您使用正则表达式术语查询，意味着Elasticsearch将把regexp应用到该字段的标记器所产生的词汇，而不是该字段的原始文本。regexp查询的性能严重依赖于所选择的正则表达式，通配符往往会降低查询性能。如：”regexp”:{ “name.first”:”s.*y” }
fuzzy: 模糊查询使用基于Levenshtein编辑距离的相似性。如：”fuzzy” : { “user” : “ki” }
type: 过滤文档匹配所提供的文档/映射类型。如：”type”:{ “value” : “my_type” }
ids:过滤只具有提供的id的文档。注意：这个查询使用了_uid字段，类型是可选的，可以省略，也可以接受一组值。如果没有指定类型，那么将尝试在索引映射中定义的所有类型。如：”ids”:{ “type” : “my_type”,”values” : [“1”,”4”,”100”] }。
highlight: 允许在一个或多个字段中突出显示搜索结果，基于lucene plain highlighter。在默认情况下，高亮显示会将高亮显示的文本包装在 and ，可以通过设置pre_tags 与 post_tags来自定义，如：”highlight”:{ “pre_tags” : [““], “post_tags” : [““], “fields” : {“_all”:{}} }
pre_tags: 自定义包含搜索关键字的前缀
post_tags: 自定义包含搜索关键字的后缀
fields: 用于指定要高亮的属性，_all表示所以属性都需要高亮，如：”fields”:{ “_all” : {} }，也可以指定具体属性 “fields”:{ “app” : {} }，也可以给每个属性单独指定设置 “fields”:{ “app” : {“fragment_size” : 150, “number_of_fragments” : 3} }
highlight_query: 可以通过设置highlight_query来突出显示搜索查询之外的查询，通常，最好将搜索查询包含在highlight_query中。如：”highlight_query”:{ “bool”:{“must”:[{“query_string”:{“query”:app:apache,”analyze_wildcard”:True,”all_fields”:True}}]} }
fragment_size: 用于指定高亮显示时，碎片的长度，如果过短，高亮内容会被切分为多个碎片。默认情况下，当使用高亮显示的内容时，碎片大小会被忽略，因为它会输出句子，而不管它们的长度
number_of_fragments 用于指定高亮显示时，碎片的数量，如果指定为0，那么就不会产生任何片段
from: 可以通过使用from和size参数来对结果进行分页，from参数指定您想要获取的第一个结果的偏移量
size: 可以通过使用from和size参数来对结果进行分页，size参数指定要返回结果的最大数量
sort: 允许在特定的字段上添加一个或多个排序，排序是在每个字段级别上定义的，用特殊的字段名来排序，然后根据索引排序进行排序，如”sort”: [ { “date”: { “order”: “desc” } } ],desc降序，asc升序
aggs: aggs主要用于分类集合，可以将查询的数据按指定属性进行分类集合统计.如：”aggs”:{ “deviceType”:{ “terms”:{ “field”:”deviceType”, “size”:6 } } }
field: 用于指定要分类的属性名称
size: 用于指定分类集合的数量，即只集合前N名
```


### 1. term查询和terms查询

> term query回去倒排索引中寻找确切的term，它并不知道分词器的存在。这种查询适合keyword、numeric、date。

###### term:查询某个字段里含有某个关键词的文档


```
GET /test/_search
{
  "query": {
    "term": {
      "name":"bob"
    }
  }
}
```
###### terms:查询某个字段里含有多个关键词的文档


```
GET /test/_search
{
  "query": {
    "terms": {
      "name": [
        "bob",
        "xiaobao"
      ]
    }
  }
}
```


### 2.控制返回的数量(分页)
> from:从哪一个文档开始
> size:需要的个数


```
GET /test/_search
{
  "from": 0,
  "size": 2,
  "query": {
    "terms": {
      "name": [
        "bob",
        "xiaobao"
      ]
    }
  }
}
```

### 3.返回版本号
```
GET /test/_search
{
  "version": true,
  "from": 0,
  "size": 2,
  "query": {
    "terms": {
      "name": [
        "bob",
        "xiaobao"
      ]
    }
  }
}
```

### 4.match查询
> match query知道分词器的存在，会对搜索词进行分词，然后再查询


```
GET /test/_search
{
  "query": {
    "match": {
      "name": "bob xiaobao"
    }
  }
}
```

返回结果 既有 bob也有xiaobao

```
GET /test/_search
{
  "query": {
    "match": {
      "name": "bob bao"
    }
  }
}
```
返回结果 返回结果只有bob 因为分词后变成bob和bao 倒排索引中只有bob和xiaobao

### 4. multi_match 多个字段包含搜索词


```
GET /test/_search
{
  "query": {
    "multi_match": {
      "query": "好人",
      "fields": ["reason","name"]
    }
  },
  "size": 120
}
```

### 5. match_phrase 短语匹配查询

> ES引擎首先分析(analyze)查询字符串,从分析后的文本中构建短语查询，这意味着必须匹配短语中的所有分词,并且保证各个分词的==相对位置不变==。


```
GET /limit_up_reason/_search
{
  "query": {
   "match_phrase": {
     "hy": "航天装备 "
   }
  },
  "size": 120
}
```
```
GET /test/_search
{
  "query": {
   "match_phrase": {
     "hy": "航 天,装备 "
   }
  },
  "size": 120
}
```

上面两个都是有结果的
```
GET /test/_search
{
  "query": {
   "match_phrase": {
     "hy": "装备 航天"
   }
  },
  "size": 120
}
```

相对位置改变就没有结果了


### 6. match_phrase_prefix 前缀匹配查询

官方参考

[https://www.elastic.co/guide/en/elasticsearch/reference/7.9/query-dsl-match-query-phrase-prefix.html](https://www.elastic.co/guide/en/elasticsearch/reference/7.9/query-dsl-match-query-phrase-prefix.html)


其他参考

[https://www.cnblogs.com/benjiming/p/7093122.html](https://www.cnblogs.com/benjiming/p/7093122.html)

```
GET /test/_search
{
  "query": {
    "match_phrase_prefix": {
      "name": "bob2"
    }
  }
}
```

返回结果


```
{
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "EVYpIXUBABh5Y4PSNMvG",
        "_score" : 1.6739764,
        "_source" : {
          "name" : "bob20",
          "age" : 20,
          "sex" : "男"
        }
      }
```


```
GET /test/_search
{
  "query": {
    "match_phrase_prefix": {
      "name": "bob"
    }
  }
}
```

返回结果


```
   {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "EVYpIXUBABh5Y4PSNMvG",
        "_score" : 1.6739764,
        "_source" : {
          "name" : "bob20",
          "age" : 20,
          "sex" : "男"
        }
      },
      {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "nFYiIXUBABh5Y4PS08f-",
        "_score" : 0.37469345,
        "_source" : {
          "name" : "bob",
          "age" : 19,
          "sex" : "男"
        }
      }
```


### 7. 排序

> 使用sort是想排序:desc 降序    asc 升序


```
GET /test/_search
{
  "query": {
    "match_all": {}
  },
  "sort": [
    {
      "age": {
        "order": "desc"
      }
    }
  ]
}
```

### 8. range

- gt :: 大于
- gte:: 大于等于
- lt :: 小于
- lte:: 小于等于

年龄
```
GET /test/_search
{
  "query": {
    "range": {
      "age": {
        "gte": 20,
        "lte": 30
      }
    }
  }
}
```
日期
```
GET /test/_search
{
  "query": {
    "range": {
      "dt": {
        "gte": "2020-10-01",
        "lte": "2020-10-15"
      }
    }
  }
}
```

### 9. wildcard 通配符查询 会对搜索词进行分词

> 允许使用通配符*和?来进行查询

> *代表0个或多个字符

> ?代表任意一个字符

```
GET /test/_search
{
  "query": {
    "wildcard": {
      "name": {
        "value": "bob*"
      }
    }
  }
}
```


### 10.fuzzy模糊查询

> value 查询的关键字

> boost 查询的权值，默认为1.0

> min_similarity:设置匹配的最小相似度，默认值为0.5 对于字符串,取值为0-1之间(包含0,1)；对于数值，取值可能大于1；对于日期型取值为1d、1m等，1d代表1天

> prefix_length:知名区分词项的共同前缀长度,默认为0

> max_expansions:查询中的词项可以扩展的数目，默认可以无限大


```
GET /test/_search
{
   "query": {
    "fuzzy": {
      "name": "bos"
    }
  }
}
```

上面查到的记过是bob

部分结果

```
      {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "2",
        "_score" : 0.2497956,
        "_source" : {
          "id" : 2,
          "name" : "bob",
          "age" : 19,
          "sex" : "男"
        }
      }
```

### 11. highlight
```
GET /test/_search
{
  "query": {
    "multi_match": {
      "query": "雄",
      "fields": ["name","school","address"]
    }
  },
  "highlight": {
    "fields": {
      "name":{},
      "address": {},
      "school": {}
    }
  }
}
```



#### 查找所有

```
GET /test/_search
{
  "query": {
    "match_all": {}
  }
}
```



#### 查询名字包含bob，同时按照价格年龄降序排序

```
GET /test/_search
{
  "query": {
    "match": {
      "name": "bob"
    }
  },
  "sort": [
    {
      "age": {
        "order": "desc"
      }
    }
  ]
}
```

#### 分页查询

```
GET /test/_search
{
  "query": {
    "match_all": {}
  },
  "from": 0, 
  "size": 1
}
```


#### 指定查询结果字段（field）


```
GET /test/_search
{
  "query": {
    "match_all": {}
  },
  "_source": [
    "name",
    "age"
  ]
}
```


#### 包含 不包含某些字段


```
GET /test/_search
{
  "_source": {
    "excludes": [
      "name"
    ],
    "includes": [
      "age",
      "sex"
    ]
  }
}
```

返回部分结果为

```
{
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "nFYiIXUBABh5Y4PS08f-",
        "_score" : 1.0,
        "_source" : {
          "sex" : "男",
          "age" : 19
        }
      },
      {
        "_index" : "test",
        "_type" : "_doc",
        "_id" : "x1YjIXUBABh5Y4PSM8fu",
        "_score" : 1.0,
        "_source" : {
          "sex" : "男",
          "age" : 19
        }
      }
```

```
GET /test/_search
{
  "_source": {
    "excludes": [
      "nam*"
    ],
    "includes": [
      "ag*",
      "*ex"
    ]
  }
}
```

这是用通配符   返回结果 同上



#### 查询名字包含bob,且age大于10小于等于30


```
GET /test/_search
{
  "query": {
    "bool": {
      "must":
        {
          "match": {
            "name": "bob"
          }
        }, 
    "filter": {
      "range": {
        "age": {
          "gt": 10,
          "lte": 30
        }
      }
    }
    }
  }
}
```

#### 更多实例

参考

https://blog.csdn.net/jiaminbao/article/details/80105636

https://blog.csdn.net/lamp_yang_3533/article/details/97618687

#### term和match的区别

参考 https://www.jianshu.com/p/d5583dff4157


match
- match的查询词会被分词
- match_phrase 不会分词
- match_phrase 可对多个字段进行匹配

term
- term代表完全匹配，不进行分词器分析
- term 查询的字段需要在mapping的时候定义好，否则可能词被分词。传入指定的字符串，查不到数据

bool联合查询
- must should must_not filter
- must 完全匹配 返回的文档必须满足must子句的条件，并且参与计算分值
- should 至少满足一个。返回的文档可能满足should子句的条件。在一个Bool查询中，如果没有must或者filter，有一个或者多个should子句，那么只要满足一个就可以返回。minimum_should_match参数定义了至少满足几个子句。
- must_not不匹配。返回的文档必须不满足must_not定义的条件
- filter 返回的文档必须满足filter子句的条件。但是不会像Must一样，参与计算分值


#### 嵌套bool多条件检索DSL模板

```
{
  "query": {
    "bool": {
      "must": [],
      "must_not": [],
      "should": []
    }
  },
  "aggs": {
    "my_agg": {
      "terms": {
        "field": "user",
        "size": 10
      }
    }
  },
  "highlight": {
    "pre_tags": [
      "<em>"
    ],
    "post_tags": [
      "</em>"
    ],
    "fields": {
      "body": {
        "number_of_fragments": 1,
        "fragment_size": 20
      },
      "title": {}
    }
  },
  "size": 20,
  "from": 100,
  "_source": [
    "title",
    "id"
  ],
  "sort": [
    {
      "_id": {
        "order": "desc"
      }
    }
  ]
}
```
