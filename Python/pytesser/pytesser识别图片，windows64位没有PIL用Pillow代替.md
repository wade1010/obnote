pytesser以及其依赖插件下载地址：链接: http://pan.baidu.com/s/1i3zgpjJ密码: ueyy

在学习Webdriver的过程中遇到验证码的识别问题，问了度娘知道了pytesser能用于验证码的识别，而且代码用起来比较简单，就查了好多资料整了一天终于配置好了，记录一下。

首先安装vc 2008外部运行库（全名不记得了）vcsetup_V9.0.30729.1.239631479.exe，不然安装PIL会提示找不到xxx.bat文件，文件名也忘记了。

接着就安装PIL了，本机环境 win7 64位系统，Python2.7，安装PIL时发现没有对应的版本，查到PIL的替代：Pillow,找到对应版本安装

pytesser同时依赖tesseract-ocr，自然是继续安装tesseract-ocr了

这些都安装完了之后就是pytesser的问题了，只需要把pytesser解压到Python安装目录（自己新建工程的话就解压到工程目录就ok）就行，另外注意一下pytesser.py中第六行的import Image,改成from PIL import Image(因为我们安装的不是原版的PIL而是pillow所以要把这个也改一下）

做完这些就能使用啦！

from pytesser import *image=Image.open("D:\\ProgramFiles\\Python27\\workspace\\fnord.tif")print image_to_string(image)





1. setuptools安装 :

在它的官网可以下载到安装包:

https://pypi.python.org/pypi/setuptools

windows下可以简单的下载他们提供的ez_setup.py

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/B9B09126583C4CFFAD7E18E250E31690clipboard.png)



这里我已经下好，

[ez_setup.py](attachments/824A94DBF3F74E9F97DC417E7DBBC4FDez_setup.py)

打开CMD命令进入ez_setup.py说在的目录 执行命令  python ez_setup.py install即可



2、安装pip

需要在Python的官网上去下载，下载地址是：https://pypi.python.org/pypi/pip#downloads ：

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/7407EA465CA94926ADCBDDA001684DF2clipboard.png)

这里附上已下载好的

[pip-7.1.2.tar.gz](attachments/8AEF314E63684681AA67B4C1F01A12A9pip-7.1.2.tar.gz)

解压后，进入目录

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/66723BEBC51C4414A230E3366ED5A0AFclipboard.png)

打开CMD 进入相应目录，输入命令python setup.py install 即可

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/BF1413207CA64EB39EAB055F2BEACA77clipboard.png)



安装好之后，我们直接在命令行输入pip，同样会显示‘pip’不是内部命令，也不是可运行的程序。因为我们还没有添加环境变量。

环境变量设置自己百度下，我们在PATH最后添加：C:\Python27\Scripts     其中C:\Python27是你python的安装目录

3、安装pillow

从 PyPI 下载 compressed archive from PyPI ，我这里下的是Pillow-3.1.0.win-amd64-py2.7.exe (md5)，点击运行；

[Pillow-3.1.0.win-amd64-py2.7.exe](attachments/099C14A26984421FB9764AE2B3650A6CPillow-3.1.0.win-amd64-py2.7.exe)

这里本来是安装PIL的，但是官方貌似没有64位，后来查资料又找到非官方64位的PIL

PIL-1.1.7.win-amd64-py2.7.exe

[PIL-1.1.7.win-amd64-py2.7.exe](attachments/4D7FA852213245769B703C0308953FEFPIL-1.1.7.win-amd64-py2.7.exe)

PIL-1.1.7.win32-py2.7.exe

[PIL-1.1.7.win32-py2.7.exe](attachments/C3EEDC13C39B43DFABB2926FC4EE5F91PIL-1.1.7.win32-py2.7.exe)



4、安装pytesser

[pytesser_v0.0.1.zip](attachments/4D9DD5BBD283463086E4304AFF64D485pytesser_v0.0.1.zip)

解压后可以直接进入解压后的目录测试

这是我的测试结果

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/99B5368954E7486A88BC05A4CEBB5432clipboard.png)



但是要在其他地方用，需要把目录下的内容复制到 python的安装目录\Lib\site-packages  下（不带文件夹，直接复制文件夹下的所有文件）

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/B862812E7EA146D49C0876C561CD92FCclipboard.png)



5、项目中测试

项目目录：

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/D276605A0B32481A978CCBA69360F3B5clipboard.png)

测试代码：

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/2171390183D54F7C886B9F4692F53C4Aclipboard.png)

注意

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/28B5E8DB17014E63988395C1EF915A95clipboard.png)





2016-1-14 16:31:23添加：

from pytesser import *

import ImageEnhance

image = Image.open('D:\\xiehao\\workspace\\python\\5.png')

#使用ImageEnhance可以增强图片的识别率

enhancer = ImageEnhance.Contrast(image)

image_enhancer = enhancer.enhance(4)

print image_to_string(image_enhancer)



测试结果：

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/E9C0A53CA7874E20A1085B6E195AC731clipboard.png)

