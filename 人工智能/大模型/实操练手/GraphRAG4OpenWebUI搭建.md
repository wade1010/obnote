git clone https://github.com/your-username/GraphRAG4OpenWebUI.git
cd GraphRAG4OpenWebUI

conda create -n GraphRAG4OpenWebUI -y python=3.11
conda activate GraphRAG4OpenWebUI

### Open WebUI安装

```python
pip install open-webui

open-webui serve
```

# Tavily AI API申请 

https://app.tavily.com/home

# 初始化graphrag

mkdir ragtest
mkdir -p ragtest/input
然后传入一个1.txt（自己找一个txt）的文本到ragtest/input目录下

初始化目录
```
python -m graphrag.index --init --root ./ragtest
```


vim ./ragtest/settings.yaml
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072243191.png)

开启covariates(协变量)
```shell
claim_extraction:
  ## llm: override the global llm settings for this task
  ## parallelization: override the global parallelization settings for this task
  ## async_mode: override the global async_mode settings for this task
  enabled: true
  prompt: "prompts/claim_extraction.txt"
  description: "Any claims or facts that could be relevant to information discovery."
  max_gleanings: 1

##Just uncomment the enabled line in your settings.yaml file.
##I'll resolve the issue, but please reopen if this doesn't work
```
去掉注释，如下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072252427.png)

vim ./ragtest/.env
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072011805.png)

```
python -m graphrag.index --root ./ragtest
```
   运行后，显卡情况如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072012163.png)



由于我找到的中文的，要执行下面命令
```bash
python -m graphrag.prompt_tune --root ./ragtest --config ./ragtest/settings.yaml --no-entity-types --language Chinese --output ./ragtest/prompts
```
运行截图如下：


构建索引
```
python -m graphrag.index --root ./ragtest
```
执行过程中截图如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072254006.png)
成功截图如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072255324.png)


# 启动GraphRAG4OpenWebUI
```
export INPUT_DIR="/xxxxxx/workspace/GraphRAG4OpenWebUI/ragtest/output/20240907-225340/artifacts/"
export TAVILY_API_KEY="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"  
export GRAPHRAG_API_KEY="ollama"
export GRAPHRAG_API_KEY_EMBEDDING="ollama"
export GRAPHRAG_LLM_MODEL="qwen2:latest"
export API_BASE="http://localhost:11434/v1"
export API_BASE_EMBEDDING="http://localhost:11434/v1"
export GRAPHRAG_EMBEDDING_MODEL="nomic-embed-text:latest"
```
上面的 INPUT_DIR可能会频繁修改

启动
```
python main-cn.py
```

启动成功，如下图
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072259830.png)

# 配置open-webui

浏览器访问 http://yourip:8080/admin/settings
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072310952.png)
上面的左边红色框，好像不能填127.0.0.1和localhost，报网络错误，不知道是open-webui还是GraphRAG4OpenWebUI的问题，不去细究。填自己IP就行，记得端口号防火墙开放。

可以关掉ollama。
## 测试
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072340783.png)
