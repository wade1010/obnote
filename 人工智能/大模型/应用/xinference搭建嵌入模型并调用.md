
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202412161708975.png)

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202412161708996.png)

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202412161709642.png)


调用测试：

curl -X 'POST' \
  'http://localhost:9997/v1/embeddings' \
  -H 'accept: application/json' \
  -H 'Content-Type: application/json' \
  -d '{
    "model": "bge-large-zh-v1.5",
    "input": "北京首都是哪里？"
  }'

部分结果截图：
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202412161709246.png)
