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


```python
export GRAPHRAG_API_KEY="ollama"
export TAVILY_API_KEY="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"
export GRAPHRAG_LLM_MODEL="qwen2:latest"
export GRAPHRAG_EMBEDDING_MODEL="nomic-embed-text:latest"
export INPUT_DIR="/llm/xhcheng/workspace/GraphRAG4OpenWebUI/data"
```