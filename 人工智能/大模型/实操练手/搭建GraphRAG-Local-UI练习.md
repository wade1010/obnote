# 拉取代码

```shell
git clone --depth=1 https://github.com/severian42/GraphRAG-Local-UI.git
cd GraphRAG-Local-UI
```
# 安装所需的软件包

```
conda create -n graphrag-local -y python=3.10  #这里目前（2024-9-7）需要指定3.10  不能用更高
conda activate graphrag-local
```

## 安装graphrag

```shell
pip install -e ./graphrag
```
## 安装其余的依赖

```
pip install -r requirements.txt
```


# 启动 API 服务器

```
python api.py --host 0.0.0.0 --port 8012 --reload
```
第一次启动，报错如下：

ModuleNotFoundError: No module named 'past'
解决方法（2024-9-6 20:46:46有这个问题，预计后面会解决的那么白，）

```
pip install future
```

成功启动，如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409062058756.png)


（暂时不用）如果使用 Ollama 进行嵌入，请启动嵌入代理: 

```
python embedding_proxy.py --port 11435 --host http://localhost:11434
```

ps:我这里是ubuntu，没有安装ollama，使用 下面命令 来安装的

```shell
curl -fsSL https://ollama.com/install.sh | sh
```


# 启动 Indexing and Prompt Tuning UI

```
vim index_app.py
```
将最后一行修改demo.launch(server_name='0.0.0.0',server_port=7862)

```shell
python index_app.py
```
如果需要使用IP访问，或者改端口，可以进行如下操作

# 访问 UI
打开 Web 浏览器并输入 http://yourip:7862


截图如下，感觉还是不美观的
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409062116950.png)

# 使用ollama下载模型
我这里测试，在终端使用下面命令安装了个小模型

```
ollama run qwen2:0.5b
或者
ollama run qwen2:1.5b
```
之后就可以看到多一个LLM
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409062139624.png)

再下载个向量模型

```
ollama pull bge-large:335m
```

后面可以修改下indexing目录下面的.env文件的配置，后期刷新页面，显示的模型就是我们想要的，要不然刷新下，就会默认设置为.env里面的模型

```
LLM_PROVIDER=openai
LLM_API_BASE=http://localhost:11434/v1
LLM_MODEL=qwen2:latest
LLM_API_KEY=12345

EMBEDDINGS_PROVIDER=openai
EMBEDDINGS_API_BASE=http://localhost:11434
EMBEDDINGS_MODEL=nomic-embed-text:latest
EMBEDDINGS_API_KEY=12345

```


# 正式开始使用
进入项目根目录

```
python -m graphrag.index --init --root ./indexing

```
