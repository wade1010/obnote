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


vim ./ragtest/settings.yaml
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072243191.png)

vim ./ragtest/.env
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072011805.png)
python -m graphrag.index --root ./ragtest   运行后，显卡情况如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072012163.png)



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
```