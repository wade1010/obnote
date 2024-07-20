### 安装构建工具 xmake

xmake >= 2.2.2，网址：[https://github.com/xmake-io/xmake](https://github.com/xmake-io/xmake)

```
git clone --branch=dev 
cd ./tboox/xmake
./scripts/get.sh __local__
```

### 克隆 Hikyuu 源码

执行以下命令克隆 hikyuu 源码：（请勿在中文目录下克隆代码）

```
git clone 
```

### 下载 Boost 源码

[https://boostorg.jfrog.io/artifactory/main/release/1.80.0/source/](https://boostorg.jfrog.io/artifactory/main/release/1.80.0/source/)

这里选择tar.gz  [boost_1_80_0.tar.gz](https://boostorg.jfrog.io/artifactory/main/release/1.80.0/source/boost_1_80_0.tar.gz)

1. 将下载的 boost 源码包解压至上一步中克隆的 hikyuu目录下，如下图所示：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333819.jpg)

## 编译与安装

须先安装 python click包（pip install click)

直接在克隆的 hikyuu 目录下执行 python setup.py command , 支持的 command：

- python setup.py help – 查看帮助

- python setup.py build – 执行编译

- python setup.py install – 编译并执行安装（安装到 python 的 site-packages 目录下）

- python setup.py uninstall – 删除已安装的Hikyuu

- python setup.py test – 执行单元测试（可带参数 –compile=1，先执行编译）

- python setup.py clear – 清除本地编译结果

- python setup.py wheel – 生成wheel安装包