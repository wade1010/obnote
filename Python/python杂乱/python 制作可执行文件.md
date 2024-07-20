python 制作可执行文件

[https://www.byhy.net/tut/py/etc/toexe/](https://www.byhy.net/tut/py/etc/toexe/)

大家都知道Python代码的运行需要解释器。

如果我们编写了一个Python代码开发的工具，给别人使用的时候，我们需要让别人做如下两步：

1. 安装Python解释器

1. 在命令行运行python程序文件，比如： python xxx.py

如果别人是一个非IT人士，使用这样的工具，就太麻烦了。

如果我们能直接让别人直接双击运行我们的程序就好了。

有没有这样的好事？

有！

有几款工具可以把 解释器、Python 代码 和 依赖的库 制作到一个目录中，我们只需要双击其中的 可执行程序，就可以运行我们的Python程序了。

其中 PyInstaller 是目前比较常用的一款。

PyInstaller 支持 Python 2.7 和 Python 3.3 以后的版本。

支持在 Windows, Mac OS X, and Linux 系统上打包出 可执行程序。

其官方网站在这里： [http://www.pyinstaller.org](http://www.pyinstaller.org/)

下面我们就来看看在Windows系统中使用它制作可执行程序的例子。

## 命令行程序打包

首先我们需要安装 PyInstaller， 当然用pip命令安装喽，如下：

```
pip install pyinstaller

```

假设我们需要开发一个可以让用户输入数学运算公式，并进行计算的程序。

我们先创建一个名为 byhy.py 的文件，写入如下代码

```
welcome = '''

    ########################################
    #                                      #
    #     白月黑羽 PyInstaller 演示程序    #
    #                                      #
    ########################################

'''

print(welcome)

while True:
    exp = input('\n\n请输入一个数学运算式 [输入quit退出]：')
    if exp == 'quit':
        break
    try:
        result = eval(exp)
    except:
        print('\n！！无效的运算式')
        continue

    print(f'结果为: {result}')

```

然后我们在cmd窗口， cd进入到该代码文件所在的目录下面，执行如下的命令

```
pyinstaller byhy.py --workpath d:\pybuild  --distpath d:\pybuild\dist

```

注意：

参数 --workpath 指定了制作过程中临时文件的存放目录

参数 --distpath 指定了最终的可执行文件目录所在的父目录

上面的命令执行结束后，我们进入到 目录 d:\pybuild\dist 中，就会发现有一个目录叫byhy （和我们的入口文件byhy同名），该目录中包含了如下文件

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCE38e86f15f0a20881cd693ec5f2cf340astickPicture.png)

里面有一个可执行文件 就叫 byhy.exe ，和我们的入口文件byhy同名。

如下图所示

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/WEBRESOURCE0f2e8826811777f306c8f72d4d40b99bstickPicture.png)

双击运行它，就可以发现效果和我们在命令行中运行一样。

但是它却不需要使用Python解释器了。 因为解释器就内置在这个目录中了。

以后我们要把这个程序给别人使用，只需要把目录 byhy 打包成 zip 文件 发给别人。

别人收到后，解压，执行里面的 byhy.exe 就可以直接运行了。

是不是很方便呢 ：)

## 将库文件单独存放在目录中

上面这样操作后，大家可以发现我们只要运行byhy.exe文件，但是该目录下却有很多其他的.dll 之类的库文件。

这使得我们要寻找并运行程序时，要在这么多文件中找到 .exe 文件，比较费劲。

我们可以通过如下方法，把这些库文件放到 单独的一个 目录中。 这样.exe所在的目录就显得比较清爽了，也好找到.exe文件了。

我们添加一个Python代码文件，名为 runtimehook1.py

其内容如下

```
import sys
import os

currentdir = os.path.dirname(sys.argv[0])
libdir = os.path.join(currentdir, "lib")
print(currentdir)
sys.path.append(libdir)
os.environ['path'] += ';./lib'

```

然后我们在cmd窗口， cd进入代码byhy文件所在的目录下面，执行制作exe命令时，加上 参数 --runtime-hook="runtimehook1.py"

如下所示

```
pyinstaller byhy.py --workpath d:\pybuild  --distpath d:\pybuild\dist --runtime-hook="runtimehook1.py"

```

上面的命令指定 生成的.exe运行时，会先执行 runtimehook1.py 里面的代码。

这样就会指定 .exe 程序所在的目录下面的lib目录 为

- Python库搜索路径，这是由

 sys.path.append(libdir) 指定的。

- 动态链接库文件的 搜索路径，这是由

 os.environ['path'] += ';./lib' 指定的。

上面的命令执行结束后，我们进入到 目录 d:\pybuild\dist\byhy 中，新建一个名为 lib 的目录，把**除了下面的几个文件**之外的所有其他文件都放到lib目录里面。

```
base_library.zip
byhy.exe
byhy.exe.manifest
python36.dll

```

这样，可执行程序的目录就显得清爽多了。

## 图形界面程序打包

有时候，我们开发的是一个图形界面的程序，使用类似上面的命令运行的时候，也会有一个console窗口（就是俗称的DOS黑窗口），这样很不美观。

我们在 执行打包命令的时候，可以加上参数 --noconsole 就可以去掉该窗口。

比如

```
pyinstaller guitool.py --noconsole --workpath d:\pybuild  --distpath d:\pybuild\dist

```

## 应用程序图标

应用程序图标是放在可执行程序里面的资源。

可以在PyInstaller创建可执行程序时，通过参数 --icon="logo.ico" 指定。

比如

```
pyinstaller httpclient.py --noconsole --hidden-import PySide2.QtXml --icon="logo.ico"

```

注意参数一定是存在的ico文件，不能是png等图片文件。

如果你只有png文件，可以通过在线的png转ico文件网站，生成ico，比如下面两个网站

[](https://www.zamzar.com/convert/png-to-ico/)[https://www.zamzar.com/convert/png-to-ico/](https://www.zamzar.com/convert/png-to-ico/)

[](https://www.easyicon.net/covert/)[https://www.easyicon.net/covert/](https://www.easyicon.net/covert/)

## 动态导入的库

有的时候，我们运行打包好的程序，会出现导入库错误的提示，比如下面

```
ImportError: could not import module 'PySide2.QtXml'

```

这意思是PyInstaller打包的时候，没有把 PySide2.QtXml库打包。

因为PyInstaller是通过分析我们的代码里面的 import 语句，推断我们的程序需要哪些库的。

但是有些代码，导入库的时候，是 动态导入 。

所谓动态导入就是，写代码的时候并不确定要导入什么库，而是在运行的时候才知道。

这种情况，不是用 import语句，而是用 __import__ 或者 exec 、 eval 这样的方式，来导入库。

PyInstaller对此有说明，[参考这里](https://pythonhosted.org/PyInstaller/when-things-go-wrong.html#listing-hidden-imports)

PyInstaller 没法分析出动态导入的库有哪些，我们可以通过命令行参数 --hidden-import 告诉它。

比如，如果我们运行出现 could not import module 'PySide2.QtXml' 的错误 ， 就可以这样

```
pyinstaller httpclient.py  --hidden-import PySide2.QtXml
```