
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
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408201120815.png)
大概执行5分钟左右安装完成
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408201121539.png)

# 更新开发部署环境依赖库
当开发环境中所需的依赖库发生变化时，一般按照更新主项目目录(`Langchain-Chatchat/libs/chatchat-server/`)下的 pyproject.toml 再进行 poetry update 的顺序执行。（这一步暂时没用到，后续二开再用）

# 设置源代码根目录
如果您在开发时所使用的 IDE 需要指定项目源代码根目录，请将主项目目录(Langchain-Chatchat/libs/chatchat-server/)设置为源代码根目录。
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408201127300.png)

### 设置数据目录

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408201147703.png)


```shell
cd Langchain-Chatchat/libs/chatchat-server/chatchat
export CHATCHAT_ROOT=/parth/to/chatchat_data
```

windows
我是用pycharm来实现的
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408201313489.png)

Working directory: D:/workespace/Langchain-Chatchat/libs/chatchat-server/chatchat
environment variables:PYTHONUNBUFFERED=1;CHATCHAT_ROOT=D:\workespace\Langchain-Chatchat\libs\chatchat-server\chatchat\data
然后断点看看成功没
![image.png](https://gitee.com/hxc8/images10/raw/master/img/202408201314100.png)
继续执行，发现




# 修改chatchat 配置项
从 `0.3.1` 版本开始，所有配置项改为 `yaml` 文件，具体参考 [Settings](https://github.com/chatchat-space/Langchain-Chatchat/blob/master/docs/contributing/settings.md)。

修改为自己的配置后，执行以下命令初始化项目配置文件和数据目录：

```shell
cd libs/chatchat-server
python chatchat/cli.py init
```
![](https://gitee.com/hxc8/images10/raw/master/img/202408201140516.png)

