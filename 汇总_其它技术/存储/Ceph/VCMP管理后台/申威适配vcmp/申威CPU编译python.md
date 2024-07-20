好的，下面是在申威 CPU 上编译安装 Python 3.6 的详细步骤：

1. 下载 Python 3.6 源代码包并解压缩

您可以从官方网站下载 Python 3.6 的源代码包，下载链接为：[https://www.python.org/ftp/python/3.6.0/Python-3.6.0.tgz](https://www.python.org/ftp/python/3.6.0/Python-3.6.0.tgz)

下载完成后，使用以下命令解压缩：

```
tar -xzf Python-3.6.0.tgz
cd Python-3.6.0

```

1. 配置编译选项

在解压缩后的 Python 3.6 源代码目录中，使用以下命令进行配置：

```
./configure --build=arm-linux-gnueabi --prefix=/usr/local/python3.6

```

其中，--build 参数指定了构建类型为 arm-linux-gnueabi，--prefix 参数指定了 Python 3.6 的安装路径为 /usr/local/python3.6，--with-openssl 参数指定了 OpenSSL 库的安装路径（如果您的系统中已经安装了 OpenSSL，可以省略该参数），--enable-shared 参数开启了共享库的支持。

./configure --enable-optimizations --build=arm-linux-gnueabi

1. 编译和安装

使用以下命令进行编译和安装：

```
make
make install

```

1. 配置环境变量

在 /etc/profile 文件中添加以下几行代码：

```
export PATH=/usr/local/python3.6/bin:$PATH
export LD_LIBRARY_PATH=/usr/local/python3.6/lib:$LD_LIBRARY_PATH

```

保存并退出文件，然后执行以下命令使环境变量生效：

```
source /etc/profile

```

1. 验证安装

使用以下命令验证 Python 3.6 是否安装成功：

```
python3.6 -V

```

如果输出 Python 3.6 的版本号，则表示安装成功。

希望这些步骤能够帮助您在申威 CPU 上成功编译安装 Python 3.6。如果您有任何问题或疑问，请随时提问。