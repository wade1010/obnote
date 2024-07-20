[https://doc.itprojects.cn/0001.zhishi/python.0008.pyqt5rumen/index.html#/09.build](https://doc.itprojects.cn/0001.zhishi/python.0008.pyqt5rumen/index.html#/09.build)

```
pip install pyinstaller -i 
```

```
pyinstaller --windowed --onefile --clean --noconfirm main.py
pyinstaller --windowed --onefile --clean --noconfirm main.spec复制Error复制成功...
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCE932b5dd5b55a6c10d745a7f485909c36stickPicture.png)

办法是main.spec向其中添加：

info_plist={ 'NSHighResolutionCapable': 'True' } 如下所示：

```
app = BUNDLE(exe,
             name='main.app',
             icon='icon.icns',
             bundle_identifier=None,
             info_plist={
                'NSHighResolutionCapable': 'True',
                })复制Error复制成功...
```

解决双击APP启动慢的问题

> **可以不打包成单个.app文件，而是打包成一个目录中，然后去目录下把感觉没用到的内容删掉，，这样启动更快 ...**


将--onefile改为--onedir

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCE8359ffeaf958343f630c8cb8287d52dbstickPicture.png)

重新运行上述命令，得到的新的app如下

![Oct-29-2020 09-42-49](assets/Oct-29-2020 09-42-49.gif)

```
pip3 install py2app  -i 
```

```
py2applet --make-setup main.py复制Error复制成功...
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCE83629f2c7d714e379784f4deca70e44fstickPicture.png)

```
rm -rf build dist复制Error复制成功...
```

```
python setup.py py2app 复制Error复制成功...
```

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCEdb14070d4c52bd16d737d8206f8dabafstickPicture.png)

找到app

![](D:/download/youdaonote-pull-master/data/Technology/Python/pyqt5/images/WEBRESOURCE2c9e7f179a85700c115a0c11b345f476stickPicture.png)