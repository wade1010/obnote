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
mkdir input inputs cache
```
# 准备测试文档
这里以西游记白话文前九回内容为例，将other/text/下的1-9.txt文件直接放入ollama_test/input文件夹下


```shell
cp ../other/text/* input/
```
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071255643.png)

# 初始化graphrag

```shell
在ollama_text目录下执行 如果不再，则cd ollama_test进行
python -m graphrag.index --init --root .
```
初始化完成后，多出如下几个目录
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409070914005.png)
# 设置参数
设置.env和settings.yaml,使用本地大模型(Ollama方案)
我这里已经安装过ollama，并且也使用ollama下载了一些模型

```
ollama pull nomic-embed-text
ollama run qwen2:7b
```

将other/temp下的.env和settings.yaml文件复制到ollama_text目录下

```
cp ../other/temp/.env ../other/temp/settings.yaml .
```

需要对.env文件做如下调整：  
GRAPHRAG_CHAT_MODEL=qwen2:7b
GRAPHRAG_EMBEDDING_MODEL=nomic-embed-text:latest

修改后.env文件如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071258327.png)

PS：这里我还下载了一个llama3.1 8B，用来做模型对比的，不做对比，可以不需要

```
ollama run llama3.1:8b
```

# 优化提示词

```
python -m graphrag.prompt_tune --config ./settings.yaml --root ./ --no-entity-types --language Chinese --output ./prompts
```
如果报错，根据提示安装必要的包，我这出现没有安装httpx，解决如下

```
pip install httpx[socks]
```
## 提示微调 prompt tuning
为生成的知识图谱创建领域自适应prompt模版的功能，提供两种方式进行调整：
自动调整： 通过加载输入，将输入分割成文本块，然后运行一系列LLM调用和prompt模版替换来生成最终的prompt模版
手动调整： 手动调整prompt模版
具体用法如下：
python -m graphrag.prompt_tune --config ./settings.yaml --root ./ --no-entity-types --language Chinese --output ./prompts
根据实际情况选择相关参数：
--config :(必选) 所使用的配置文件，这里选择setting.yaml文件
--root :(可选)数据项目根目录，包括配置文件（YML、JSON 或 .env）。默认为当前目录
--domain :(可选)与输入数据相关的域，如 “空间科学”、“微生物学 ”或 “环境新闻”。如果留空，域将从输入数据中推断出来
--method :(可选)选择文档的方法。选项包括全部(all)、随机(random)或顶部(top)。默认为随机
--limit :(可选)使用随机或顶部选择时加载文本单位的限制。默认为 15
--language :(可选)用于处理输入的语言。如果与输入语言不同，LLM 将进行翻译。默认值为“”，表示将从输入中自动检测
--max-tokens :(可选)生成提示符的最大token数。默认值为 2000
--chunk-size :(可选)从输入文档生成文本单元时使用的标记大小。默认值为 20
--no-entity-types（无实体类型） :(可选)使用无类型实体提取生成。建议在数据涵盖大量主题或高度随机化时使用
--output :(可选)保存生成的提示信息的文件夹。默认为 “prompts”


执行上面命令之后，显卡情况大概如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071135046.png)
不到2分钟大概完成
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071136790.png)


# 构建索引

```shell
python -m graphrag.index --root ./                    
```
这个操作耗时，慢慢等待吧。2024-9-7 13:00开始的，2024-9-7 13:13结束
执行情况如下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071138457.png)
显卡情况如下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071138490.png)
成功执行，截图如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071314973.png)



执行期间可以通过 journalctl -ex 查看下日志，如下

```shell
journalctl -ex
```
可以看出，频繁的请求/v1/embeddings 接口
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409071311930.png)

# 测试graphrag
测试代码在utils文件夹，将other/utils文件夹直接拷贝到ollama_test文件夹下

```
cp -r ../other/utils .
```
## 运行main.py脚本
先修改main.py里面的INPUT_DIR

```
vim utils/main.py
```
将

```
INPUT_DIR = "/Users/janetjiang/Desktop/agi_code/GraphragTest/ragtest/inputs/artifacts"
```
修改为

```
INPUT_DIR = "自己的路径/inputs/artifacts"
```
将

```
COMMUNITY_REPORT_TABLE = "create_final_community_reports"
```

改为

```
COMMUNITY_REPORT_TABLE = "create_final_communities"
```



运行

```
cd utils
python main.py
```


这里运行有问题，后续有时间再看看 2024-9-7 14:15:34

后来用 https://github.com/wade1010/graphrag-ui  研究期间，也算是把这篇遇到的问题解决了。 2024-9-12 16:44:36