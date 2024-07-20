[https://blog.csdn.net/qq_35240555/article/details/123474660](https://blog.csdn.net/qq_35240555/article/details/123474660)

最近在用pycharm进行项目开发，在创建新项目的时候，pycharm提供了三种环境管理工具：Conda、Virtualenv、Pipenv，这三个玩意到底是什么？它们有什么不同？今天小D我就来总结一下！

其实，Conda、Virtualenv、Pipenv都是pyhton包，它们都是环境管理工具（conda还可以做包管理工具），当我们新建一个项目时，通过任何一个工具包都能创建一个python虚拟环境，对该项目设置指定版本的python库和模块。

## 虚拟环境的作用是啥？‍

Python 应用程序经常会使用某个特定版本的库或模块，但安装一个 Python 版本可能无法满足每个应用程序的要求。比如应用程序 A 需要一个特定模块的 1.0 版本，但是应用程序 B 需要该模块的 2.0 版本，这两个应用程序的要求是冲突的，安装版本 1.0 或者版本 2.0 将会导致其中一个应用程序不能运行。

这个问题的解决方案就是创建一个虚拟环境 （通常简称为“virtualenv”），包含一个特定版本的 Python，以及一些附加包的独立目录树。

不同的应用程序可以使用不同的虚拟环境。为了解决前面例子中的冲突，应用程序 A 可以有自己的虚拟环境，其中安装了特定模块的 1.0 版本。而应用程序 B 拥有另外一个安装了特定模块 2.0 版本的虚拟环境。如果应用程序 B 需求一个库升级到 3.0 的话，也不会影响到应用程序 A 的环境。

## 虚拟环境和非虚拟环境该怎么选？

如果你直接在 Pycharm 创建一个项目而不创建虚拟环境，那么你安装的第三方包都会安装到系统 Python 解释器的 site-packages 文件夹下，比如C:\Python\Python39\Lib\site-packages。

创建越多的项目，安装的库越多。当你又新建一个项目，必定会把 site-packages 下的所有库都导进来，可能有一些库你这个项目根本就不需要，但是又不能删除（因为别的项目有在用），这时候就需要虚拟环境了。

## 通过 Virtualenv 方式创建虚拟环境

在 Pycharm 创建一个新项目。

- File 》New Project 》Pure Pyhon，如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCE6cc1ae1e9197096814a520c899ddd453截图.png)

通过 Virtualenv 创建的虚拟目录放在本项目的下的 venv 文件夹中。

如果项目地址是 E:\PycharmProjects\pythonProject

虚拟环境的地址就是 E:\PycharmProjects\pythonProject\venv

虚拟环境中的库 E:\PycharmProjects\pythonProject\venv\Lib\site-packages

图中的 基本解释器 为 系统 Python 解释器，即自行在官网下载并配置好环境变量了的，一般系统 Python 解释器的第三方库都在 site-packages 目录下，比如我的 C:\Python\Python39\Lib\site-packages.

**在 Virtualenv 环境中进行包的管理**

- Ctrl+Alt+S 或者 File 》Setting 》Python Interpreter

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCE40249328496a56743a69ace278f5204b截图.png)

Virtualenv 一般配合 requirements.txt 文件对项目的依赖库进行管理。

requirements.txt 的格式如下：

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCE9ac55bc6ce025f46ac2c333c9cee68a3截图.png)

这样的结构让人一目了然，且方便项目移植，当你克隆一个含有 requirements.txt 文件的项目，可以通过相关命令一键下载所有的依赖库。

requirements.txt 文件生成

打开 Pycharm ，Tool 》Sync Python Requirements

根据步骤自动生成一个 requirements.txt 文件。

打开 Pycharm 左下角的 Terminal（终端），输入以下代码：

pip freeze > requirements.txt

根据 requirements.txt 文件安装依赖库

若是导入一个新项目，且含有 requirements.txt 文件，则可根据 requirements.txt 安装所有的依赖库  pip install -r requirement.txt

更多详情参考 [https://www.jetbrains.com/help/pycharm/2021.1/managing-dependencies.html](https://www.jetbrains.com/help/pycharm/2021.1/managing-dependencies.html)

## 通过 Pipenv 方式创建虚拟环境

注意 Pycharm（2021.1.1版本） 没有内置 Pipenv，需要安装。

1.打开 cmd ，运行以下命令以确保系统中已安装 pip：

pip --version

2.pipenv 通过运行以下命令进行安装：

pip install --user pipenv

3.安装成功后在 cmd 输入以下命令：

py -m site --user-site，会返回 pipenv.exe 所在文件夹。

如：C:\Users\admin\AppData\Roaming\Python\Python39\site-packages

4.为了方便起见，可以将 pipenv.exe 所在文件夹 Scripts 添加到 PATH 环境变量中

"%PATH%;C:\Users\admin\AppData\Roaming\Python\Python39\Scripts"

打开 Pycharm ，创建一个新项目。

- File 》New Project 》Pure Pyhon，如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCEb287a1a57c058c8ddb727bbdebed762c截图.png)

通过 Pipenv 创建的项目，虚拟环境并不在本项目的目录下，而是在 C:\Users\用户名.virtualenvs 文件夹下。

Pipenv是 requests 库 的作者写的，因为 requirements.txt 的管理并不能尽善尽美，可能存在一些问题。在 Pipenv 虚拟环境中不用 requirements.txt，Pipfile 是 Pipenv 虚拟环境用于管理项目依赖项的专用文件。该文件对于使用 Pipenv 是必不可少的。当为新项目或现有项目创建 Pipenv 环境时，会自动生成 Pipfile。

Pipfile 的用法如下：

1、新建项目的 Pipfile 文件：

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCEd7d3cf30d3f51cea91b22a5bc0b87e78截图.png)

2、通过修改此 packages 部分来添加新的程序包依赖项。

[packages]

requests = “*”

每当您修改 Pipfile 文件时，PyCharm 都会建议执行以下操作之一：

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCEc6a12b26002bf9a1ea7c3ceb76198c8f截图.png)

pipenv lock——将新要求记录到 Pipfile.lock 文件中。

pipenv update——将新要求记录到 Pipfile.lock 文件中，并将缺少的依赖项安装在 Python 解释器上。

更多详情[https://www.jetbrains.com/help/pycharm/2021.1/using-pipfile.html](https://www.jetbrains.com/help/pycharm/2021.1/using-pipfile.html)

## 通过 Conda 方式创建虚拟环境

注意，Pycharm（2021.1.1） 也没有内置 Conda ，需要安装。

1打开 Anaconda 官网选择适合你电脑的版本即可。

2也可在清华大学开源软件镜像站下载，这个速度较快。

打开 Pycharm ，创建一个新项目。

File 》 New Project 》Pure Pyhon，如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCE8833a058055eaf2131c0c4f4c937836f截图.png)

利用 Anaconda 进行虚拟环境包的管理

打开 Anaconda Navigator 图形界面 》Environment 》选需要安装包的环境 》 点绿色按钮 》Open Terminal，在 Terminal（终端）中用 pip 命令安装包即可。

Ctrl+Alt+S 或者 File 》Setting 》Python Interpreter

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCE39338d0064c07fbfb0cd60fa541f95a8截图.png)