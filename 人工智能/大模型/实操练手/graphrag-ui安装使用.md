
# 介绍

项目地址： https://github.com/wade1010/graphrag-ui 

GraphRAG-UI 是 [GraphRAG](https://github.com/microsoft/graphrag) 的用户友好界面,GraphRAG 是一个强大的工具,可使用检索增强生成(RAG)方法对大量文本数据进行索引和查询。本项目支持最新版graphrag，旨在为 GraphRAG 提供方便的管理和交互方式,支持配置 ollama 等本地大模型服务,使其更容易为广大用户所使用。

# pip 安装

## 1. 安装ollama（可选）:

    访问 [Ollama官网](https://ollama.com/) 来安装。如果是 Linux ，可以直接运行下面命令

   ```bash
   curl -fsSL https://ollama.com/install.sh | sh
   ```

## 2. pip 安装本软件：

   ```bash
   pip install graphrag-ui
   或者
   pip install graphrag-ui -i https://pypi.org/simple
   ```

## 3. 启动 API Server

```bash
    graphrag-ui-server
   ```
   
## 4. 启动 UI

    启动综合版 UI

```bash
graphrag-ui
```

    或启动纯净版 UI

```bash
graphrag-ui-pure
   ```


# ollama和本软件非同一机器启动的情况

如果ollama和该软件安装不在同一台机器上，需要修改下设置。

执行下面两个命令
```
graphrag-ui-server
graphrag-ui
```

启动后，浏览器打开 http://yourip:7862/

先修改 LLM API Base URL ，将localhost改为你ollama所在服务器的IP，端口如果做了修改也改下。没改就用默认的即可
Embeddings API Base URL同理。
修改完之后，点击 Update LLM Settings 进行保存

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409131605016.png)

￣□￣｜｜，测试发现目前有点bug，这个更新后，加入手动刷新页面，页面这里还是显示旧值，不影响使用，后期修复下。


# 使用

