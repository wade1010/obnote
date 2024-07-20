### 版本
7.5.0

> _primary_term：_primary_term也和_seq_no一样是一个整数，每当Primary Shard发生重新分配时，比如重启，Primary选举等，_primary_term会递增1。

>_primary_term主要是用来恢复数据时处理当多个文档的_seq_no一样时的冲突，比如当一个shard宕机了，raplica需要用到最新的数据，就会根据_primary_term和_seq_no这两个值来拿到最新的document



旧版本(具体哪个版本改的不清楚)的elasticsearch是采用乐观锁+version来解决并发问题

使用如下操作

```
PUT /test/_doc/1?version=4
{
  "name":"bob",
  "age":19,
  "sex":"男"
}
```
会报错

![image](https://gitee.com/hxc8/images9/raw/master/img/202407191644360.jpg)


新版本使用_seq_no和_primary_term来代替version处理并发问题



```
PUT /test/_doc/1?if_seq_no=3&if_primary_term=1
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
  "_id" : "1",
  "_version" : 5,
  "result" : "updated",
  "_shards" : {
    "total" : 2,
    "successful" : 1,
    "failed" : 0
  },
  "_seq_no" : 4,
  "_primary_term" : 1
}

```


PS 外部版本控制扔跟就版本相同



```
PUT /test/_doc/1?version_type=external&version=11
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
  "_id" : "1",
  "_version" : 11,
  "result" : "updated",
  "_shards" : {
    "total" : 2,
    "successful" : 1,
    "failed" : 0
  },
  "_seq_no" : 10,
  "_primary_term" : 1
}

```
