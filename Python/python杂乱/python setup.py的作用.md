python setup.py的作用

简而言之，setup.py是python模块分发与安装的指导文件

有了setup.py文件，运行下面这条命令，可以进行模块的安装。

python setup.py install

下面给出setup.py的实例代码，利用这段代码，我们将安装一个叫 ugit 的模块，当运行ugit命令时，程序将运行cli.py文件下的main 函数。

```
+ #!/usr/bin/env python3
+ 
+ from setuptools import setup
+ 
+ setup (name = 'ugit',
+        version = '1.0',
+        packages = ['ugit'],
+        entry_points = {
+            'console_scripts' : [
+                'ugit = ugit.cli:main'
+            ]
+        })
```

cli.py文件下的main 函数的代码为：

```
+ def main ():
+     print ('Hello, World!')
```

如若你的项目还处于开发阶段，频繁的安装模块，也是一个麻烦事。

这时候你可以使用这条命令安装，该方法不会真正的安装包，而是在系统环境中创建一个软链接指向包实际所在目录。这边在修改包之后不用再安装就能生效，便于调试。

python setup.py develop --user

现在在命令后运行ugit命令后，程序将输出 Hello, World!

最近写了个超级简陋的 pypi 源管理工具，学习了一波setup.py文件的撰写

### 介绍

python开发者们习惯使用 pip 来安装一些第三方模块，这个安装过程之所以简单，是因为模块开发者写好了模块的setup.py，而这个文件负责的过程就是 打包。

打包，就是将你的源代码进一步封装，并且将所有的项目部署工作都事先安排好，这样使用者拿到后安装即可用，不用再操心如何部署的问题。

#### setuptools

setuptools 是官方提供的一个专业用于包分发的工具，是对包的分发很有用处，定制化程序非常高。

### 源码包与二进制包

Python 包的分发可以分为两种：

1. 以源码包的方式发布

源码包安装的过程，是先解压，再编译，最后才安装，所以它是跨平台的，由于每次安装都要进行编译，相对二进包安装方式来说安装速度较慢。

> 源码包的本质是一个压缩包，其常见的格式有：zip, tar, tar.gz, tar.bz2 等


1. 以二进制包形式发布

二进制包的安装过程省去了编译的过程，直接进行解压安装，所以安装速度较源码包来说更快。

由于不同平台的二进制文件无法通用，所以在发布时，有时可能需编译多个平台的包（当然如果使用whl也可以做到跨平台使用）。

> 二进制包的常见格式有：egg, wheel(whl)


### setup.py文件结构

打包分发最关键的一步是编写 setup.py 文件。

以下是我项目使用的 setup.py 示例

```
from setuptools import setup, find_packages
import pimm
from os import path
this_directory = path.abspath(path.dirname(__file__))
long_description = None
with open(path.join(this_directory, 'README.md'), encoding='utf-8') as f:
    long_description = f.read()

setup(name='pimm', # 包名称
      packages=['pimm'], # 需要处理的包目录
      version='0.0.5', # 版本
      classifiers=[
          'Development Status :: 3 - Alpha',
          'License :: OSI Approved :: MIT License',
          'Programming Language :: Python', 'Intended Audience :: Developers',
          'Operating System :: OS Independent',
          'Programming Language :: Python :: 3.5',
          'Programming Language :: Python :: 3.6',
          'Programming Language :: Python :: 3.7',
          'Programming Language :: Python :: 3.8',
          'Programming Language :: Python :: 3.9'
      ],
      install_requires=['ping3'],
      entry_points={'console_scripts': ['pmm=pimm.pimm_module:main']},
      package_data={'': ['*.json']},
      auth='lollipopnougat', # 作者
      author_email='lollipopnougat@126.com', # 作者邮箱
      description='pypi mirrors manager', # 介绍
      long_description=long_description, # 长介绍，在pypi项目页显示
      long_description_content_type='text/markdown', # 长介绍使用的类型，我使用的是md
      url='
      license='MIT', # 协议
      keywords='pimm source manager') # 关键字 搜索用

```

可以看到 setup.py 调用了 setuptools 包中的setup方法，下面解释一下各个参数的含义

#### 程序分类信息

classifiers 参数说明包的分类信息，接收一个 string 列表

发展时期

- Development Status :: 1 - Planning

- Development Status :: 2 - Pre-Alpha

- Development Status :: 3 - Alpha

- Development Status :: 4 - Beta

- Development Status :: 5 - Production/Stable

- ...

开发目标用户

- Intended Audience :: Customer Service

- Intended Audience :: Developers

- Intended Audience :: Education

- Intended Audience :: End Users/Desktop

- Intended Audience :: Financial and Insurance Industry

- Intended Audience :: Healthcare Industry

- ...

目标编程语言

- Programming Language :: Basic

- Programming Language :: C

- Programming Language :: C#

- Programming Language :: C++

- ...

- Programming Language :: Python :: 3.4

- Programming Language :: Python :: 3.5

- Programming Language :: Python :: 3.6

- Programming Language :: Python :: 3.7

- Programming Language :: Python :: 3.8

- Programming Language :: Python :: 3.9

- ...

该字段所有支持的值

[Intended Audience list](https://pypi.org/pypi?%3Aaction=list_classifiers)

#### 文件分发

安装过程中，需要安装的静态文件，如配置文件、图片等可以使用 data_files 参数，接收一个字典参数

字典的键表示部署后文件的存放位置(目标机器)，空字符串表示放在包的根目录

此外需要再准备一个 MANIFEST.in 文件与 setup.py 同级, 来控制文件的分发

一个demo

- 所有根目录下的以 txt 为后缀名的文件，都会分发

- 根目录下的 examples 目录 和 txt、py文件都会分发

- 路径匹配上 examples/sample?/build 不会分发

```
include *.txt
recursive-include examples *.txt *.py
prune examples/sample?/build

```

#### 依赖包

使用 install_requires 参数指定安装依赖，表明当前模块使用时依赖哪些包，若环境中没有，则会从pypi中下载安装, 此参数接收一个 string 列表

使用 tests_require 参数指定测试依赖，仅在测试时需要使用的依赖，在正常发布的代码中是没有用到的，此参数接收一个 string 列表

使用 extras_require 参数指定可选的依赖，此参数接收一个 string 列表

#### 生成可执行文件的分发

```
    # 用来支持自动生成脚本，安装后会自动生成 /usr/bin/pmm 的可执行文件(windows管理员权限下会在Python文件夹的script里面生成pmm.exe)
    # 该文件入口指向 pimm/pimm_module.py 的main 函数
    entry_points={'console_scripts': ['pmm=pimm.pimm_module:main']},

    # 将 bin/foo.sh 和 bar.py 脚本，生成到系统 PATH中
    # 执行 python setup.py install 后
    # 会生成 如 /usr/bin/foo.sh 和 如 /usr/bin/bar.py
    scripts=['bin/foo.sh', 'bar.py']


```

### 使用 setup.py 构建包

1. 构建源码发布包

用于发布一个 Python 模块或项目，将源码打包成 tar.gz 或者 zip 压缩包

```bash
python setup.py sdist # 打包，默认tar.gz
python setup.py sdist --formats=gztar,zip # 打包，指定压缩格式

```

1. 构建二进制分发包

在windows中我们习惯了双击 exe 进行软件的安装，Python 模块的安装也同样支持 打包成 exe 这样的二进制软件包

```
python setup.py bdist_wininst

```

若你的项目，需要安装多个平台下，既有 Windows 也有 Linux，按照上面的方法，多种格式我们要执行多次命令，为了方便，你可以一步到位，执行如下这条命令，即可生成多个格式的进制

```bash
python setup.py bdist

```

构建 wheel 包

```bash
python setup.py sdist bdist_wheel

```

#### 使用 setup.py 安装包

```bash
python setup.py install

```

如若你的项目还处于开发阶段，频繁的安装模块，也是一个麻烦事。

这时候可以使用下面这条命令安装，该方法不会真正的安装包，而是在系统环境中创建一个软链接指向包实际所在目录。这边在修改包之后不用再安装就能生效，便于调试。

```bash
python setup.py develop

```

#### 发布包到 PyPi

若你觉得自己开发的模块非常不错，想要 share 给其他人使用，你可以将其上传到 PyPi （Python Package Index）上，它是 Python 官方维护的第三方包仓库，用于统一存储和管理开发者发布的 Python 包

如果要发布自己的包，需要先到 pypi 上注册账号。然后创建 ~/.pypirc(windows C:/User/yourname/.pypirc) 文件，此文件中配置 PyPI 访问地址和账号。.pypirc文件内容请根据自己的账号来修改

典型的 .pypirc 文件

```
[distutils]
index-servers = pypi

[pypi]
username:xxx
password:xxx

```

上传

```
setup.py sdist upload

```

想更省劲的话可以考虑安装 twine，然后使用 twine 上传，这个还可以上传whl文件

```bash
twine upload dist/* # 上传dist下的所有文件

```

pypi 好像不能覆盖文件，要上传新的需要修改版本号

上传以后就可以使用pip下载自己的包了，顺便清华源是10分钟更新一次，所以说10分钟后清华的pypi源就能找到自己的包了

[https://www.cnblogs.com/lollipopnougat/p/14269046.html](https://www.cnblogs.com/lollipopnougat/p/14269046.html)