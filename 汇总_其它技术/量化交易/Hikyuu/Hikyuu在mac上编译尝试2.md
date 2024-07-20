### C++编译器

g++ > = 5.4.0 、 clang++ >= 3.8.0

```
(base) ~ ➤ g++ --version                                                                                               
g++-12 (Homebrew GCC 12.1.0) 12.1.0
Copyright (C) 2022 Free Software Foundation, Inc.
This is free software; see the source for copying conditions.  There is NO
warranty; not even for MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

(base) ~ ➤ clang++ --version                                                                                           
Apple clang version 12.0.0 (clang-1200.0.32.29)
Target: x86_64-apple-darwin19.6.0
Thread model: posix
InstalledDir: /Library/Developer/CommandLineTools/usr/bin
```

### 安装构建工具 xmake

xmake >= 2.2.2，网址：[https://github.com/xmake-io/xmake](https://github.com/xmake-io/xmake)

```
git clone --branch=dev 
cd ./tboox/xmake
./scripts/get.sh __local__
```

### 克隆 Hikyuu 源码

```
git clone 
```

### 下载 Boost 源码

1. 下载 Boost 源码 

1. 将下载的 boost 源码包解压至上一步中克隆的 hikyuu目录下，如下图所示：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333694.jpg)

### 安装依赖软件包

brew install **hdf5 mysql-client libiconv**

python setup.py install

会报错

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333403.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333774.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333471.jpg)

**尝试修复**

cd boost_1_80_0/

./bootstrap.sh之后会产生b2工具

sudo ./b2 install（确定已经安装了g++与gcc,此过程会花费一些时间）

这个时候你的/usr/local/include下会产生boost的头文件，/usr/local/lib下面会产生boost库

**vim ~/.zshrc**

**末尾加入**

```
BOOST_ROOT=/Users/bob/workspace/CPPProjects/hikyuu/boost_1_80_0
BOOST_INCLUDE=/usr/local/include/boost
BOOST_LIB=/usr/local/lib
export BOOST_INCLUDE BOOST_LIB BOOST_ROOT
```

source ~/.zshrc