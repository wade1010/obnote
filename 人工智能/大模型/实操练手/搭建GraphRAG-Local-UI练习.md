# 拉取代码

```shell
git clone --depth=1 https://github.com/severian42/GraphRAG-Local-UI.git
cd GraphRAG-Local-UI
```
# 安装所需的软件包
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
 pip install feature
```

成功启动，如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409062058756.png)


如果使用 Ollama 进行嵌入，请启动嵌入代理:

```
python embedding_proxy.py --port 11435 --host http://localhost:11434
```

ps:我这里是ubuntu，没有安装ollama，使用 sudo snap install ollama 来安装的


