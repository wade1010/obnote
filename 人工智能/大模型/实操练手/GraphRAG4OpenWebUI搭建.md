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

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

```python
export GRAPHRAG_API_KEY="your_graphrag_api_key"
export TAVILY_API_KEY="your_tavily_api_key"
export GRAPHRAG_LLM_MODEL="gpt-3.5-turbo"
export GRAPHRAG_EMBEDDING_MODEL="text-embedding-3-small"
export INPUT_DIR="/path/to/your/input/directory"
```