```
wget http://ftp.gnu.org/gnu/gcc/gcc-4.8.1/gcc-4.8.1.tar.gz
tar xzf gcc-4.8.1.tar.gz  
cd gcc-4.8.1  
./contrib/download_prerequisites    //安装依赖库
cd ..  
mkdir build_gcc4.8.1
cd build_gcc4.8.1
../configure --prefix=/usr  -enable-checking=release --enable-languages=c,c++ --disable-multilib
make -j8
sudo make install
```

[http://wfeii.com/2021/01/25/centos7-gcc10.html](http://wfeii.com/2021/01/25/centos7-gcc10.html)

GCC是一款广泛使用的开源编译器，更新版本可以提供更好的性能和更好的C++17和C++20标准支持等。当前比较新的版本有：

1. GCC 11.x系列

GCC 11系列是当前最新的稳定版本，于2021年4月发布。该版本增加了对C++20的新特性的支持，并对常见编译警告进行了优化。

1. GCC 10.x系列

GCC 10系列于2020年4月发布，它增加了对C++20的初步支持，提高了性能、并添加了许多新的特性和改进。

1. GCC 9.x系列

GCC 9于2019年5月发布，它增加了对C++17标准的支持，并提供了一些新特性和改进，例如增强的（Link-Time Optimization）和代码生成的优化。

1. GCC 8.x系列

GCC 8于2018年3月发布，主要改进了对C++17和C++14标准的支持，并改进了对类和对象元编程的编译器优化。

1. GCC 7.x系列

GCC 7于2017年5月发布，它增加了对C++17标准的支持，并包括其他改进和新特性，如增强代码生成、增强警告等。

以上是当前比较新的几个GCC版本。通常情况下，建议选择最新版本来编译您的代码，以获得最佳的性能和最新的特性

在CentOS7上安装GCC 9，可以按照以下步骤进行操作：

1. 添加SCL存储库

GCC 9可通过使用Software Collections (SCL)抢先体验。 要使用SCL，首先需要安装SCL存储库。使用以下命令来添加SCL存储库：

```
sudo yum install centos-release-scl

```

1. 安装GCC 9

使用以下命令来安装GCC 9和其他必要的依赖项：

```
sudo yum install devtoolset-9-gcc devtoolset-9-gcc-c++ devtoolset-9-binutils -y

```

1. 启用GCC 9

安装GCC 9后，必须使用以下命令在终端上启用它：

```
scl enable devtoolset-9 bash

```

这将在当前终端窗口中启用GCC 9。 如果想短期使用GCC 9，则必须在每个新的终端窗口中重新运行此命令，或者将其添加到bashrc文件中。

配置全局环境变量：

update-alternatives --install /usr/bin/gcc gcc /opt/rh/devtoolset-9/root/usr/bin/gcc 999 --slave /usr/bin/g++ g++ /opt/rh/devtoolset-9/root/usr/bin/g++

1. 验证安装

要验证GCC 9是否正确安装，可以在终端中使用以下命令检查版本：

```
gcc --version

```

GCC 9现在已经安装完成并启用了，您可以开始使用它来编译C++代码。