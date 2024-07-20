要在 Ubuntu 上安装 TA-Lib，您可以按照以下步骤进行操作：

1. 打开终端并更新软件包列表：

```
sudo apt-get update

```

1. 安装 TA-Lib 库及其依赖项：

```
sudo apt-get install build-essential libtool autotools-dev automake pkg-config libssl-dev libcurl4-openssl-dev libxml2-dev libxslt1-dev python3-dev

```

1. 下载 TA-Lib 源代码：

```
wget 

```

1. 解压缩源代码：

```
tar -xzf ta-lib-0.4.0-src.tar.gz
cd ta-lib

```

1. 编译和安装 TA-Lib：

```
./configure --prefix=/usr
make
sudo make install

```

1. 安装 Python TA-Lib 绑定：

```
pip3 install TA-Lib

```

请注意，如果您使用的是 Python 2.x，请将 pip3 与 pip 替换。

```
± pip3 install TA-Lib                                                                                21:51
Looking in indexes: http://pypi.douban.com/simple
Collecting TA-Lib
  Downloading http://pypi.doubanio.com/packages/39/6f/6acaee2eac6afb2cc6a2adcb294080577f9983fbd2726395b9047c4e13ec/TA-Lib-0.4.26.tar.gz (272 kB)
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ 272.6/272.6 kB 3.3 MB/s eta 0:00:00
  Installing build dependencies ... done
  Getting requirements to build wheel ... done
  Installing backend dependencies ... done
  Preparing metadata (pyproject.toml) ... done
Requirement already satisfied: numpy in /home/yaya/miniconda3/envs/hikyuu/lib/python3.9/site-packages (from TA-Lib) (1.24.3)
Building wheels for collected packages: TA-Lib
  Building wheel for TA-Lib (pyproject.toml) ... done
  Created wheel for TA-Lib: filename=TA_Lib-0.4.26-cp39-cp39-linux_x86_64.whl size=410561 sha256=d5979d08e132baa25e6c153a571a8f945c0c6f885198abfd0debdf7f9c8c996b
  Stored in directory: /home/yaya/.cache/pip/wheels/40/b3/d9/7a7bb868b2982748cdef98cc1eeb5a71dbd08e96ec192cff06
Successfully built TA-Lib
Installing collected packages: TA-Lib
Successfully installed TA-Lib-0.4.26

```

现在，您已成功安装 TA-Lib 库并在 Python 中安装了 TA-Lib 绑定。您可以在 Python 中导入 TA-Lib 并开始使用：

python

```
import talib

```

请注意，如果您在 Python 中导入 TA-Lib 时遇到了问题，请检查 TA-Lib 库是否正确安装，并确保已正确安装 TA-Lib 绑定。