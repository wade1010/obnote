登录到AI服务器

cd /llm/xhcheng/graphrag

查看配置
cat indexing/.env


创建索引
./graphragpyenv/bin/python -m graphrag.index --root ./indexing/


使用

Here is an example using Global search to ask a high-level question:

```sh
./graphragpyenv/bin/python -m graphrag.query \
--root ./indexing \
--method global \
"讲了什么故事?"
```

Here is an example using Local search to ask a more specific question about a particular character:

```sh
./graphragpyenv/bin/python -m graphrag.query \
--root ./indexing \
--method local \
"讲讲美猴王?"
```
