linux下安装pytesser。系统为CentOS

安装步骤：

pytesser依赖于PIL,因此需要先安装PIL模块

pytesser调用了tesseract，因此需要安装tesseract：

【一、安装PIL】

1、安装PIL所需的系统库





$ yum install zlib zlib-devel 

$ yum install libjpeg libjpeg-level 

$ yum install freetype freetype-devel





2、删除Python下安装的PIL（一般来说，如果之前没有装过PIL，那么是不存在一下文件的，也就是说不需要删除的）





rm -rf /usr/lib/python2.7/site-packages/PIL

rm /usr/lib/python2.7/site-packages/PIL.pth

#或者

rm -rf /usr/lib/python2.7/dist-packages/PIL

rm /usr/lib/python2.7/dist-packages/PIL.pth

#如果/usr/local/python2.7/dist-packages/PIL存在最好也全部删除





3、下载安装PIL

在102机上下载的位置： /mnt/diska/ftpdata/fancy_chuan

$ wget http://effbot.org/media/downloads/Imaging-1.1.7.tar.gz

$ tar -zxvf Imaging-1.1.7.tar.gz

$ cd Imaging-1.1.7

$ python setup.py build_ext -i  #用来进行安装前的检查

$ #修改setup.py,通过指令  vim setup.py  

TCL_ROOT = "/usr/lib64/"

JPEG_ROOT = "/usr/lib64/"

ZLIB_ROOT = "/usr/lib64/"

TIFF_ROOT = "/usr/lib64/"

FREETYPE_ROOT = "/usr/lib64/"

LCMS_ROOT = "/usr/lib64/"

$ #安装

$ python setup.py install





【二、安装tesseract】参考资料：http://www.oschina.net/question/54100_59400?sort=time

从资料看，是需要通过一下4个指令下载相关驱动的。

sudo apt-get install libpng12-dev

sudo apt-get install libjpeg62-dev

sudo apt-get install libtiff4-dev

sudo apt-get install zlibg-dev

但是，在CentOS（红帽系统）并没有指令apt-get ,相对于的是yum。但是，通过yum去安装相关驱动得到的信息是没有找到相关的文件

这里索性不安装上面的4个文件，后来事实证明，没有影响

1.下载tesseract的源码包：

wget http://tesseract-ocr.googlecode.com/files/tesseract-3.00.tar.gz

如果连接超时，则直接下载

[tesseract-3.00.tar.gz](attachments/A084AA24EC974D20AD66DD07FEE12982tesseract-3.00.tar.gz)

 



解压、cd到解压后目录下tesseract-3.00/

tar -zxvf tesseract-3.00.tar.gz

cd tesseract-3.00/



2.运行./configure --prefix=你想要安装到的路径

在102机的安装位置是   /mnt/diska/ftpdata/apps/tesseract

即:

./configure --prefix=/mnt/diska/ftpdata/apps/tesseract

3.不退出当前路径，输入一下指令编译、安装

make

make install

4.将tesseract的运行脚本加到环境变量

vim /etc/profile

在文件的最后加上以下内容(注意路径是安装的位置哈，只不过会多了一个文件夹/bin,该脚本就是在bin文件夹里面)

export PATH=$PATH:/mnt/diska/ftpdata/apps/tesseract/bin

保存推出以后输入下面的指令让添加的环境变量生效

source /etc/profile

5.到http://code.google.com/p/tesseract-ocr/downloads/list页面去下载最新的eng.traineddata.gz文件，

[eng.traineddata.gz](attachments/5CA4918F71464791AADB93CD5E8117E1eng.traineddata.gz)

 

解压后的eng.traineddata放到/mnt/diska/ftpdata/apps/tesseract/share/tessdata目录下

原因请看参考资料。

6.测试是否安装成功

直接输入 tesseract  不报错就说明可以了。

但这里因为前面并没有安装4个相关的驱动文件，所以，我这里就出现一下信息

[root@iZ28msw7pqhZ tesseract]# tesseract

Usage:tesseract imagename outputbase [-l lang] [configfile [[+|-]varfile]...]

Warning - no liblept or libtiff - cannot read compressed tiff files.



但是后面证明，没有多大关系

【三、下载pytesser】

在102机上面，我是把pytesser也下载在  /mnt/diska/ftpdata/apps   路径下

wget http://pytesser.googlecode.com/files/pytesser_v0.0.1.zip

unzip  pytesser_v0.0.1.zip

cd pytesser_v0.0.1



在这个路径下就可以去测试了！还是把测试代码也贴一下吧

vim test.py  然后在文件里面输入以下的内容



from pytesser import *

im = Image.open('phototest.tif')

text = image_to_string(im)

print text



保存退出，通过   python test.py 这个指令运行测试脚本，

结果：

结果：

Thls IS a lot of 12 pornt text to test the

ocr code and see lf It works on all types

of frle format

lazy fox The qurck brown dog jumped

over the lazy fox The qulck brown dog

jumped over the lazy fox The QUICK

brown dog jumped over the lazy fox

The quick brown dog jumped over the



大功告成！



经过测试，识别数字类型的验证码，准确率50%左右。。。简单的验证码还可以，但是稍复杂就有些吃力！