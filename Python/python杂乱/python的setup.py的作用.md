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

- def main ():

- print ('Hello, World!')


如若你的项目还处于开发阶段，频繁的安装模块，也是一个麻烦事。

这时候你可以使用这条命令安装，该方法不会真正的安装包，而是在系统环境中创建一个软链接指向包实际所在目录。这边在修改包之后不用再安装就能生效，便于调试。

python setup.py develop --user

现在在命令后运行ugit命令后，程序将输出 Hello, World!