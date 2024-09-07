# 拉取代码

```shell
git clone --depth=1 https://github.com/severian42/GraphRAG-Local-UI.git   2024-9-7的最新代码
cd GraphRAG-Local-UI
```
# 安装所需的软件包

```
conda create -n graphrag-local -y python=3.10
conda activate graphrag-local
```

## 安装graphrag

```shell
pip install -e ./graphrag
```
## 安装其余的依赖

vim requirements.txt  这里源码里面是没有指定版本的，我试过，但是没运行成功，由于成功之前也做了重新构建indexing目录，不确定是不是都需要改（有兴趣的测试可以确定下）

```
gradio==3.36.1
fastapi==0.111.1
uvicorn==0.30.1
python-dotenv==1.0.1
pydantic==2.8.2
pandas==2.2.2
tiktoken==0.7.0
langchain-community==0.2.16
aiohttp==3.9.5
pyyaml==6.0.1
requests==2.32.3
duckduckgo-search==6.2.11
ollama==0.2.1
plotly==5.22.0
future==1.0.0

```
然后安装
```
pip install -r requirements.txt
```

# 初始化索引文件夹
源码里面是有indexing目录的，我这里重新生成一遍（反正这么折腾后，是跑起来了）
进入项目根目录

```
cp indexing/.env .env-example  # 仅留备份作用
rm -rf indexing/output indexing/prompts indexing/.DS_Store  # 即只保留.env settings.yaml input 
```
初始化索引文件夹

```
python -m graphrag.index --init --root ./indexing
```

# 启动 API 服务器
修改.env

```
vim ./indexing/.env
```
修改后，内容如下

```
LLM_PROVIDER=openai
LLM_API_BASE=http://localhost:11434/v1
LLM_MODEL='qwen2:latest'
LLM_API_KEY=ollama

EMBEDDINGS_PROVIDER=openai
EMBEDDINGS_API_BASE=http://localhost:11434
EMBEDDINGS_MODEL='nomic-embed-text:latest'
EMBEDDINGS_API_KEY=ollama


GRAPHRAG_API_KEY=ollama
ROOT_DIR=indexing
INPUT_DIR=${ROOT_DIR}/output/${timestamp}/artifacts
LLM_SERVICE_TYPE=openai_chat
EMBEDDINGS_SERVICE_TYPE=openai_embedding

API_URL=http://localhost:8012
API_PORT=8012
CONTEXT_WINDOW=4096
SYSTEM_MESSAGE=You are a helpful AI assistant.
TEMPERATURE=0.5
MAX_TOKENS=1024
```

启动
```
python api.py --host 0.0.0.0 --port 8012 --reload
```
第一次启动，报错如下：

ModuleNotFoundError: No module named 'past'
解决方法（2024-9-6 20:46:46有这个问题，预计后面会解决的那么白，）

```
pip install future  # 后来我给加入到了requirements.txt里面了
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
ollama pull nomic-embed-text:latest
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

## 构建索引
打开UI界面，点击 "Run Indexing"按钮，大概10分钟左右执行完成。点击“Check Indexing Status”按钮，结果如下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071638705.png)
## 优化提示词
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071646252.png)

末尾出现 INFO: Generated community summarization prompt, stored in folder /tmp/tmpyh880egi 就代表完成了。
