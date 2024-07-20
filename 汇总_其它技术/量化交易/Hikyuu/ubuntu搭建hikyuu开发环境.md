### 安装xmake

参考[https://xmake.io/#/zh-cn/guide/installation?id=ubuntu](https://xmake.io/#/zh-cn/guide/installation?id=ubuntu)

```bash
sudo add-apt-repository ppa:xmake-io/xmake
sudo apt update
sudo apt install xmake
```

### 安装pip

wget [https://bootstrap.pypa.io/get-pip.py](https://bootstrap.pypa.io/get-pip.py)

sudo python get-pip.py

### 安装python依赖包

pip install -r requirement.txt

### 开始配置环境

xmake project -k compile_commands

> 上面命令来生成编译命令文件，即 compile_commands.json。这个文件可以帮助编辑器（如 Visual Studio Code、Clion 等）更好地理解项目的编译配置，提供更好的代码补全、语法检查和调试等功能。


这个过程会下载必要库，会让你选择是否安装，我选择y，也就是安装

注意：使用 xmake project -k compile_commands 命令生成的 compile_commands.json 文件只包含了当前编译环境下的编译命令和选项，如果需要在其他编译环境下使用，可能需要重新生成

下面是执行输出

```
hikyuu (master)]$ xmake project -k compile_commands [11:10:33]
checking for platform ... linux
checking for architecture ... x86_64
updating repositories .. ok
note: install or modify (m) these packages (pass -y to skip confirm)?
in xmake-repo:
  -> doctest 2.4.11 
  -> libffi 3.4.4 [private, vs_runtime:"MD", from:python]
  -> ca-certificates 20230306 [from:python]
  -> python 3.9.13 [headeronly:y, vs_runtime:"MD", from:boost]
  -> boost 1.81.0 [shared:n, python:y, system:y, vs_runtime:"MD", data_ ..)
  -> fmt 8.1.1 [vs_runtime:"MD", header_only:y, from:spdlog]
  -> spdlog v1.11.0 [fmt_external:y, header_only:y, vs_runtime:"MD"]
  -> sqlite3 3.39.0+200 [shared:y, cxflags:"-fPIC", vs_runtime:"MD"]
  -> flatbuffers v2.0.0 [vs_runtime:"MD"]
  -> nng 1.5.2 [cxflags:"-fPIC", vs_runtime:"MD"]
  -> nlohmann_json v3.11.2 
  -> cpp-httplib 0.12.1 
  -> zlib#2 v1.2.13 
in project-repo:
  -> hdf5 1.12.2 
  -> mysql 8.0.31 
please input: y (y/n/m)
y
  => download https://github.com/doctest/doctest/archive/refs/tags/v2.4.11.tar.gz .. ok
  => download https://github.com/libffi/libffi/releases/download/v3.4.4/libffi-3.4.4.tar.gz .. ok
  => install doctest 2.4.11 .. ok
  => downloading hdf5, mysql, ca-certificates, installing libffi .. (4/sh,curl)   => downloading hdf5, mysql, ca-certificates, installing libffi .. (4/sh,curl)   => download https://github.com/xmake-mirror/xmake-cacert/archive/refs/tags/20230306.zip .. ok
  => install ca-certificates 20230306 .. ok
  => download https://gitee.com/fasiondog/hikyuu_extern_libs/releases/download/1.0.0/hdf5-1.12.2-linux-x64.zip .. ok
  => install hdf5 1.12.2 .. ok
  => download https://github.com/fmtlib/fmt/releases/download/8.1.1/fmt-8.1.1.zip .. ok
  => install libffi 3.4.4 .. ok
  => install fmt 8.1.1 .. ok
  => download https://gitee.com/fasiondog/hikyuu_extern_libs/releases/download/1.0.0/mysql-8.0.31-linux-x86_64.zip .. ok
  => install mysql 8.0.31 .. ok
  => download https://sqlite.org/2022/sqlite-autoconf-3390200.tar.gz .. ok
  => download https://github.com/gabime/spdlog/archive/v1.11.0.zip .. ok
  => download https://github.com/google/flatbuffers/archive/v2.0.0.zip .. ok
  => download https://www.python.org/ftp/python/3.9.13/Python-3.9.13.tgz .. ok
  => install spdlog v1.11.0 .. ok
  => download https://github.com/nanomsg/nng/archive/v1.5.2.zip .. ok
  => install flatbuffers v2.0.0 .. ok
  => install nng 1.5.2 .. ok
  => download https://github.com/yhirose/cpp-httplib/archive/v0.12.1.zip .. ok
  => download https://github.com/nlohmann/json/archive/v3.11.2.tar.gz .. ok
  => install nlohmann_json v3.11.2 .. ok
  => install cpp-httplib 0.12.1 .. ok
  => install sqlite3 3.39.0+200 .. ok
  => install python 3.9.13 .. ok
  => download https://github.com/xmake-mirror/boost/releases/download/boost-1.81.0/boost_1_81_0.tar.bz2 .. ok
  => install boost 1.81.0 .. ok
  => download https://github.com/madler/zlib/archive/v1.2.13.tar.gz .. ok
  => install zlib#2 v1.2.13 .. ok
generating /home/runner/workspace/hikyuu/version.h.in ... ok
generating /home/runner/workspace/hikyuu/config.h.in ... ok
create ok!
```

大概耗时6分钟

### 转成clion

参考[https://www.bilibili.com/read/cv19446929](https://www.bilibili.com/read/cv19446929)

xmake f -p linux -m debug -a x86 -y

```
f 是 configure 命令的缩写，用于配置当前项目的构建选项和参数
-p linux: 指定平台为 Linux。
-m debug: 指定构建模式为 Debug。
-a x86: 指定目标架构为 x86。
-y: 在执行过程中不询问，使用默认选项。
```

大概都跟着上面配置了，但是选executable的时候，不知道怎么选，

先执行 python setup.py build 构建下

发现这里又重新下载boost这些，不知道 xmake project -k compile_commands 的下载是什么意思。。

### 编译

根目录执行

python setup.py build

大概耗时1个小时不到

c++都是编译成so文件

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332148.jpg)

### 安装ta-lib

参考[ubuntu安装ta-lib](note://WEBaf6f36408941e43ac3b43cdbd7c5791c)

### hikyuu开发模式

项目根目录执行，cd hikyuu

pip install -e .

从clion看，hikyuu目录变成淡蓝色，且多了一个Hikyuu.egg-info 目录

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332824.jpg)

这样之后就可以以开发者模式运行一些脚本了，比如执行hikyuu/gui/HikyuuTDX.py了

这个时候可以查看是否能看到这个包

```
(hikyuu)‹master*›$ pip list|grep -i hikyuu
Hikyuu                        1.0.9    /home/yaya/workspace/hikyuu/hikyuu
```

卸载

pip uninstall hikyuu

### 报错处理

执行 python hikyuu/gui/HikyuuTDX.py

报错如下

```
qt.qpa.plugin: Could not load the Qt platform plugin "xcb" in "" even though it was found.
This application failed to start because no Qt platform plugin could be initialized. Reinstalling the application may fix this problem.
Available platform plugins are: eglfs, linuxfb, minimal, minimalegl, offscreen, vnc, wayland-egl, wayland, wayland-xcomposite-egl, wayland-xcomposite-glx, webgl, xcb.
```

解决

sudo apt-get install libxcb-xinerama0 libxcb-icccm4 libxcb-image0 libxcb-keysyms1 libxcb-render-util0 libxcb-xkb1 -y

命令行执行还是报错

```
Traceback (most recent call last):
  File "./HikyuuTDX.py", line 21, in <module>
    from hikyuu.gui.data.MainWindow import *
ModuleNotFoundError: No module named 'hikyuu'
```

但是clion里面直接run可以

后来根据chatgpt找到解决办法

可能是因为该包的路径没有被加入到 PYTHONPATH 环境变量中。

可以在终端中执行以下命令，将包的路径加入到 PYTHONPATH 环境变量中：

```
export PYTHONPATH=$PYTHONPATH:/path/to/package
```

其中，/path/to/package 是包的路径，可以根据自己的实际情况进行设置。

执行这个命令后，PYTHONPATH 环境变量就会包含该包的路径，终端中就可以正常调用该包中的模块和函数了。如果需要在每次启动终端时都自动设置 PYTHONPATH 环境变量，可以将上面的命令添加到终端的启动脚本中，例如 .bashrc 或者 .zshrc 文件中。

项目目录 /home/runner/workspace/hikyuu/hikyuu

单次生效

export PYTHONPATH=$PYTHONPATH:/home/runner/workspace/hikyuu/hikyuu

python hikyuu/gui/HikyuuTDX.py  成功

永久生效

把 export PYTHONPATH=$PYTHONPATH:/home/runner/workspace/hikyuu/hikyuu 加入 .bashrc 或者 .zshrc 文件中 重新source下，之后再执行python hikyuu/gui/HikyuuTDX.py  成功

### 设置matplotlib中文

参考[windows解决matplotlib中文乱码](note://WEBc828570eb3132007750cde70f872cf3e)[python matplotlib UserWarning Glyph中文乱码问题](note://WEB356eb69515266ab93b23377fea421683)

```
axes.unicode_minus: True
font.sans-serif: Noto Sans CJK JP,DejaVu Sans, Bitstream Vera Sans, Computer Modern Sans Serif, Lucida Grande, Verdana, Geneva, Lucid, Arial, Helvetica, Avant Garde, sans-serif
```