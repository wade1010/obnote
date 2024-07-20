感谢王老师

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCE607652f53396361a7a3f692eda703619stickPicture.png)

Qt（官方发音 [kju:t]）是一个跨平台的C++开发库，主要用来开发图形用户界面（Graphical User Interface，GUI）程序

Qt 是纯 C++ 开发的，正常情况下需要先学习C语言、然后在学习C++然后才能使用Qt开发带界面的程序

多亏了开源社区使得**Qt 还可以用Python、Ruby、Perl 等脚本语言进行开发。**

**Qt 支持的操作系统有很多，例如通用操作系统 Windows、Linux、Unix，智能手机系统Android、iOS， 嵌入式系统等等**。可以说是跨平台的

QT官网：[https://doc.qt.io/qt-5/index.html](https://doc.qt.io/qt-5/index.html)

PyQt的开发者是英国的“Riverbank Computing”公司。它提供了GPL（简单的说，以GPL协议发布到网上的素材，你可以使用，也可以更改，但是经过你更改然后再次发布的素材必须也遵守GPL协议，主要要求是必须开源，而且不能删减原作者的声明信息等）与商业协议两种授权方式，因此它可以免费地用于自由软件的开发。

**PyQt可以运行于Microsoft Windows、Mac OS X、Linux以及Unix的多数变种上**。

PyQt是Python语言的GUI（Graphical User Interface，简称 GUI，又称图形用户接口）编程解决方案之一

可以用来代替Python内置的Tkinter。其它替代者还有PyGTK、wxPython等，与Qt一样，PyQt是一个自由软件

文档相关地址：[https://www.riverbankcomputing.com/software/pyqt/](https://www.riverbankcomputing.com/software/pyqt/)

比较不错的参考资料：[https://wiki.python.org/moin/PyQt/Tutorials](https://wiki.python.org/moin/PyQt/Tutorials)

- Qt (C++ 语言 GUI )

- PyQt = Python + Qt技术

- Tkinter

Python官方采用的标准库，优点是作为Python标准库、稳定、发布程序较小，缺点是控件相对较少。

- wxPython

基于wxWidgets的Python库，优点是控件比较丰富，缺点是稳定性相对差点、文档少、用户少。

- PySide2、PyQt5

基于Qt 的Python库，优点是控件比较丰富、跨平台体验好、文档完善、用户多。

缺点是 库比较大，发布出来的程序比较大。

PyQt5 的开发者是英国的“Riverbank Computing”公司 ， 而 PySide2 则是 qt 针对python语言提供的专门

下面我们以在Python虚拟环境中，使用pip进行安装PyQT

```
mkvirtualenv -p python3 py3-qt --no-download
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCE3981414f20e632ae2ecfc62209d06873stickPicture.png)

1. 切换到指定的虚拟环境

```
workon py3-qt复制Error复制成功...
```

1. 安装pyqt5

```
pip install pyqt5 -i https://pypi.tuna.tsinghua.edu.cn/simple
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCE78ff5498a67d45da1997b5d20b88f791stickPicture.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCEc1cb173f23f48287fd1119fd8f7a43b9stickPicture.png)

在当前安装PyQt的虚拟环境中输入如下测试代码：

```python
# 如果执行成功，没有任何错误提示，则表明环境搭建成功
from PyQt5 import QtWidgets

# 当然也可以查看PyQt版本
from PyQt5.QtCore import *
print(QT_VERSION_STR)
```

如果安装缓慢，可以尝试修改pip加速，加速地址如下

```
阿里云  

中国科技大学 

豆瓣(douban) 

清华大学 

中国科学技术大学 
```