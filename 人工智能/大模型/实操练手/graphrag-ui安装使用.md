

如果ollama和该软件安装不在同一台机器上，需要修改下设置。


```
graphrag-ui-server
```


```
graphrag-ui
```


启动后，浏览器打开 http://yourip:7862/

先修改 LLM API Base URL ，将localhost改为你ollama所在服务器的IP，端口如果做了修改也改下。没改就用默认的即可
Embeddings API Base URL同理。
修改完之后，点击 Update LLM Settings 进行保存

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409131605016.png)
