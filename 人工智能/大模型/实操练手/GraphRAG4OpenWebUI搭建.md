git clone https://github.com/your-username/GraphRAG4OpenWebUI.git
cd GraphRAG4OpenWebUI

conda create -n GraphRAG4OpenWebUI -y python=3.11
conda activate GraphRAG4OpenWebUI

### Open WebUI安装

```python
pip install open-webui

open-webui serve
```

Tavily AI API申请 https://app.tavily.com/home


mkdir ragtest
mkdir -p ragtest/input
然后传入一个1.txt（自己找一个txt）的文本到ragtest/input目录下

初始化目录
```
python -m graphrag.index --init --root ./ragtest
```

由于我找到的中文的，要执行下面命令
```bash
python -m graphrag.prompt_tune --root ./ragtest/ --config ragtest/settings.yaml --no-entity-types
```

构建索引
```
python -m graphrag.index --root ./ragtest
```

```python
export TAVILY_API_KEY="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"
export INPUT_DIR="/llm/xhcheng/workspace/GraphRAG4OpenWebUI/ragtest/input"
# 设置llm API密钥
export GRAPHRAG_API_KEY="ollama"
# 设置嵌入API密钥（如果与GRAPHRAG_API_KEY不同）
export GRAPHRAG_API_KEY_EMBEDDING="ollama"
# 设置LLM模型（默认为"gemma2"）
export GRAPHRAG_LLM_MODEL="qwen2:latest"
# 设置API基础URL（默认为本地服务器）
export API_BASE="http://localhost:11434/v1"
# 设置嵌入API基础URL（默认为OpenAI的API）
export API_BASE_EMBEDDING="http://localhost:11434/v1"
# 设置嵌入模型（默认为"text-embedding-3-small"）
export GRAPHRAG_EMBEDDING_MODEL="nomic-embed-text:latest"
```