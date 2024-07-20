

在家里windows环境下搞了一次

见   python MySQLdb在windows环境下的快速安装、问题解决方式

http://blog.csdn.net/wklken/article/details/7253245



在公司开发需要，再搞一次，linux下的。

发现用编译的方式安装真的很蛋疼，不过也算见见世面，各种问题......

![](D:/download/youdaonote-pull-master/data/Technology/Python/python杂乱/images/FD56434155734E829E0CB477AED872AFstruggle.gif)



这里也有两种方式：

A.快速安装

B.自己编译



1.最快速最简单方法（赶时间的话）

sudo yum install MySQL-python



可能遇到问题：

>>> import MySQLdb

Traceback (most recent call last):

  File " ", line 1, in ?

  File "MySQLdb/__init__.py", line 22, in ?

    raise ImportError("this is MySQLdb version %s, but _mysql is version %r" %

ImportError: this is MySQLdb version (1, 2, 3, 'final', 0), but _mysql is version (1, 2, 1, 'final', 1)

原因：之前使用编译的方法进行安装，下的是1.2.3，但是用yum目前最高1.2.1，冲突

解决方法：删除已经编译的文件

                  rm -rf MySQL-python-1.2.3/

再进行

>>> import MySQLdb

无错误，则表示成功了





2.自己编译安装（有时间的话。。。囧）



需要：

A.gcc

B.setuptools   

wget -O setuptools-0.6c8.tar.gz  http://pypi.python.org/packages/source/s/setuptools/setuptools-0.6c8.tar.gz

解压执行 sudo easy_install.py [或者 python setup.py build   && sudo python setup.py install]

C.python-dev   在  sudo apt-get install python-dev

   否则会报异常：fatal error: Python.h: 没有那个文件或目录



步骤：

A.下载：wget  http://sourceforge.net/projects/mysql-python/files/latest/download

B.

$ tar xfz MySQL-python-1.2.3.tar.gz

$ cd MySQL-python-1.2.3

$whereis   mysql_config

mysql_config: /usr/bin/mysql_config

$ vim site.cfg

修改mysql_config为mysql配置文件的路径 /usr/bin/mysql_config 

还要修改

 threadsafe = False

$ python setup.py build

$ sudo python setup.py install





