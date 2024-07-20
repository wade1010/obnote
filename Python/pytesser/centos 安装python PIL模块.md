1、安装

 

使用yum安装缺少类库：

#尤其重要，否则会报错
yum install python-devel

yum install libjpeg libjpeg-devel zlib zlib-devel freetype freetype-devel lcms lcms-devel

yum install python-imaging

测试：

#解压
tar xvfz Imaging-1.1.7.tar.gz    
python setup.py build_ext -i

如果报缺少类库则手动安装：

下载资源：

http://www.pythonware.com/products/pil/index.htm 下载最新版的PIL安装程序 tar xfz Imaging-1.1.7.tar.gz

http://www.ijg.org 下载jpeg库jpegsrc.v9a.tar.gz

http://www.gzip.org/zlib/ 下载zlib-1.2.5.tar.gz支持压缩功能的zlib库http://zlib.net/zlib-1.2.8.tar.gz

#我安装9a
tar -zxf jpegsrc.v9a.tar.gz
cd jpeg-9a
./configure && make && make test && make install

 tar xfz zlib-1.2.8.tar.gz
 cd zlib-1.2.8
./configure && make && make install

修改配置后安装image

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/A0DF9EB6FAE54F2AA130FF5EF0513A23copycode.gif)

#修改配置文件setup.py
TCL_ROOT = "/usr/lib/"    
JPEG_ROOT = "/usr/lib/"    
ZLIB_ROOT = "/usr/lib/"    
TIFF_ROOT = "/usr/lib/"    
FREETYPE_ROOT = "/usr/lib/"   
LCMS_ROOT = "/usr/lib/"#安装
python setup.py install

![](D:/download/youdaonote-pull-master/data/Technology/Python/pytesser/images/A0DF9EB6FAE54F2AA130FF5EF0513A23copycode.gif)

 

问题：

1、the imaging c module is not installed[5]

yum install python-imaging

 

 

 

参考：

[1] 64位CentOS下安装python的PIL模块.http://www.360doc.com/content/12/1103/21/8827884_245560257.shtml

[2] 64位centos下安装python的PIL模块.http://www.xuebuyuan.com/1397145.html

[3] python Image模块安装.http://blog.163.com/longsu2010@yeah/blog/static/173612348201193112324679/

[4] Linux 64位系统安装Python PIL模块.http://wangyou871228.blog.163.com/blog/static/27695152201361922011718/

[5] PIL will not import the _imaging C module: “*** The _imaging C module is not installed”.http://stackoverflow.com/questions/4113095/pil-will-not-import-the-imaging-c-module-the-imaging-c-module-is-not-ins

