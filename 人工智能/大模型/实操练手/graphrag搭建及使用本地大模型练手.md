这里使用ollama启动本地大模型，然后graphrag使用本地大模型作为底座。

选用了https://github.com/NanGePlus/GraphragTest 感谢开源分享


# 拉取代码

```
git clone https://github.com/NanGePlus/GraphragTest
```
当前时间的requirements.txt内容如下

```
fastapi==0.112.0
uvicorn==0.30.6
pandas==2.2.2
tiktoken==0.7.0
graphrag==0.3.0
pydantic==2.8.2
python-dotenv==1.0.1
asyncio==3.4.3
aiohttp==3.10.3
numpy==1.26.4
scikit-learn==1.5.1
matplotlib==3.9.2
seaborn==0.13.2
nltk==3.8.1
spacy==3.7.5
transformers==4.44.0
torch==2.2.2
torchvision==0.17.2
torchaudio==2.2.2
```
这里使用的是graphrag 0.3.0，当前最新版本是0.3.2
# 安装依赖

```
conda create -n graphrag-local python=3.11
conda activate graphrag-local
pip install -r requirements.txt
```

# 创建graphrag所需文件夹
进入到项目根目录

```shell
mkdir ollama_test  
cd ollama_test  
mkdir input  
mkdir inputs  
mkdir cache
```
# 准备测试文档
这里以西游记白话文前九回内容为例，将other/text/下的1-9.txt文件直接放入ollama_test/input文件夹下

返回项目根目录
```shell
cp other/text/* ollama_test/input/
```
# 初始化graphrag

```shell
cd ollama_test
python -m graphrag.index --init --root .
```
初始化完成后，多出如下几个目录
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409070914005.png)
# 设置参数
设置.env和settings.yaml,使用本地大模型(Ollama方案)
我这里已经安装过ollama，并且也使用ollama下载了一些模型

将other/temp下的.env和settings.yaml文件内容拷贝后,需要对.env文件做如下调整：  
GRAPHRAG_CHAT_MODEL=qwen2:latest  
GRAPHRAG_EMBEDDING_MODEL=nomic-embed-text:latest

