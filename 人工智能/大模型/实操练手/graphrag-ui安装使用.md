
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

默认 ollama和本软件是需要安装在同一服务器的，如果不是就按上面章节的方法修改下。

## 下载模型

默认模型分别是 qwen2:latest 和 nomic-embed-text:latest ，跑起来占5G多显存。


```
ollama pull qwen2:latest
```


```
ollama pull nomic-embed-text:latest
```


## 模型修改
如果不想使用默认模型，可以先试用ollama下载想要的模型，然后根据下面方法修改配置。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409131754366.png)

## 上传txt文件
GraphRAG 需要使用txt文件进行解析。自己找一些，然后通过下面步骤
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409131756832.png)

上传成功后，可以查看自己传的文件，如下图
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409131757118.png)


## 构建索引
步骤如下图
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409131758894.png)

这一步骤根据文件大小来决定时间，建议初期测试可以用个小文件。
执行期间可以看下图日志，执行完成会有如下成功标识：  All workflows completed successfully.
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409132011527.png)

## 测试

### 提炼相关问题
点击 "Data Management"。按下图步骤，先看看上传文档的内容，好从中提问。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409132023937.png)

这里根据文档，我想到的问题是，“除了东胜神洲，天下还有几个洲？”

### 提问
#### local 提问
按如下步骤，可以进行 local 提问
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409132027071.png)
#### global 提问
跟上图，不同支出在于下面红色部分

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409132028295.png)


## 可视化
1、选取.graphml文件
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409132031709.png)

2、进行可视化
点击如下图的按钮
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202409132032853.png)

