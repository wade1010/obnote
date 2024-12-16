登录到AI服务器

cd /llm/xhcheng/graphrag

查看配置
cat indexing/.env


创建索引
sh start_indexing.sh


使用

-global模式：
Here is an example using Global search to ask a high-level question:

```
sh query_global.sh 讲了什么故事?
```



-local模式：
Here is an example using Local search to ask a more specific question about a particular character:


```
sh query_local.sh 讲讲美猴王?
```
