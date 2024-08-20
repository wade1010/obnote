
纯纯记录
# 拉取代码


```shell
git clone --depth 1 https://github.com/chatchat-space/Langchain-Chatchat.git
```
# 初始化开发环境
Langchain-Chatchat 自 0.3.0 版本起，为方便支持用户使用 pip 方式安装部署，以及为避免环境中依赖包版本冲突等问题， 在源代码/开发部署中不再继续使用 requirements.txt 管理项目依赖库，转为使用 Poetry 进行环境管理。

## 安装 Poetry
在安装 Poetry 之前，如果您使用 Conda，请创建并激活一个新的 Conda 环境，例如使用 `conda create -n chatchat python=3.9` 创建一个新的 Conda 环境。


```shell
pip install poetry
#安装完按成后执行下面命令，使 Poetry 使用 virtualenv python environment
poetry config virtualenvs.prefer-active-python true
```


# 安装源代码/开发部署所需依赖库
进入主项目目录，并安装 Langchain-Chatchat 依赖

```shell
cd  Langchain-Chatchat/libs/chatchat-server/
poetry install -E xinference
```
