conda create -n graphrag python=3.10
conda activate graphrag

```bash
pip install graphrag
```

```sh
mkdir -p ./ragtest/input
```

```sh
curl https://www.gutenberg.org/cache/epub/24022/pg24022.txt > ./ragtest/input/book.txt
```

```sh
python -m graphrag.index --init --root ./ragtest
```

vim ragtest/.env
修改为你自己的key
```
GRAPHRAG_API_KEY=sk-1UIybqycAYSTCUBf42C5757cBf94492a9c2534A8xxxxxxx
```
vim ragetest/settings.yaml
修改为下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071846323.png)


```
python -m graphrag.prompt_tune --root ragtest  --no-entity-types
```

Running the Indexing pipeline
```sh
python -m graphrag.index --root ./ragtest
```

![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071848902.png)


# Using the Query Engine

## Running the Query Engine

Now let's ask some questions using this dataset.

Here is an example using Global search to ask a high-level question:

```sh
python -m graphrag.query \
--root ./ragtest \
--method global \
"What are the top themes in this story?"
```

Here is an example using Local search to ask a more specific question about a particular character:

```sh
python -m graphrag.query \
--root ./ragtest \
--method local \
"Who is Scrooge, and what are his main relationships?"
```


后来改成了一个西游记中的一个章节的内容
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071906663.png)

发现local的比global要准确很多

![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071908744.png)
