windows python pip install 国内镜像源

[https://blog.csdn.net/z929162742/article/details/109492697](https://blog.csdn.net/z929162742/article/details/109492697)

Windows中设置国内pip源

我们用pip安装模块的时候，默认的是国外的元，速度非常慢，还经常连不上，切换成国内的源，速度会快很多，因此我们来配置一下Windows系统的国内pip源

第一步：

打开我的电脑 在地址栏输入 %appdata% 然后回车，我们就来到了 C:\Users\PC\AppData\Roaming 目录中，当然你也可以一个个目录去打开，然后进入此目录

第二步：

在此目录中创建一个 pip 文件夹

第三步：

进入新创建的 pip 文件夹，创建一个 pip.ini 配置文件；这里必须是 ini 结尾的，如果电脑有隐藏后缀名，用txt文本创建的需要打开后文件缀名将其删除掉，操作方式：点击我的电脑中的查看，勾选“文件扩展名”前面的复选框即可查看后缀名

第四步：在配置文件中输入如下内容并保存：

[global]

index-url=[https://mirrors.aliyun.com/pypi/simple/](https://mirrors.aliyun.com/pypi/simple/)

trusted-host=mirrors.aliyun.com

已知的源全部如下：

# 阿里源

[global]

index-url=[https://mirrors.aliyun.com/pypi/simple/](https://mirrors.aliyun.com/pypi/simple/)

trusted-host=mirrors.aliyun.com

# 豆瓣源

[global]

index-url=[http://pypi.douban.com/simple/](http://pypi.douban.com/simple/)

trusted-host=pypi.douban.com

# 清华大学源

[global]

index-url=[https://pypi.tuna.tsinghua.edu.cn/simple/](https://pypi.tuna.tsinghua.edu.cn/simple/)

trusted-host=pypi.tuna.tsinghua.edu.cn

# 中国科技大学源

[global]

index-url=[https://pypi.mirrors.ustc.edu.cn/simple/](https://pypi.mirrors.ustc.edu.cn/simple/)

trusted-host=pypi.mirrors.ustc.edu.cn

原文链接：[https://blog.csdn.net/z929162742/article/details/109492697](https://blog.csdn.net/z929162742/article/details/109492697)

[https://blog.csdn.net/u011141739/article/details/126481424](https://blog.csdn.net/u011141739/article/details/126481424)

[https://blog.csdn.net/lxy210781/article/details/102848158](https://blog.csdn.net/lxy210781/article/details/102848158)

经验证: 阿里的云最快（记得是https不是http)

对于Python开发用户来讲，PIP安装软件包是家常便饭。而国外的源下载速度太慢，浪费时间。而且常出现下载后安装出错问题。故把pip安装源替换成国内镜像，可大幅提高下载速度，还可以提高安装成功率。

国内源：

清华：[https://pypi.tuna.tsinghua.edu.cn/simple](https://pypi.tuna.tsinghua.edu.cn/simple)

阿里云：[https://mirrors.aliyun.com/pypi/simple/](https://mirrors.aliyun.com/pypi/simple/)

中国科技大学 [https://pypi.mirrors.ustc.edu.cn/simple/](https://pypi.mirrors.ustc.edu.cn/simple/)

华中理工大学：[http://pypi.hustunique.com/](http://pypi.hustunique.com/)

山东理工大学：[http://pypi.sdutlinux.org/](http://pypi.sdutlinux.org/)

豆瓣：[http://pypi.douban.com/simple/](http://pypi.douban.com/simple/)

官方：[https://pypi.python.org/simple](https://pypi.python.org/simple)

临时使用：

可以在使用pip的时候加参数-i [https://pypi.tuna.tsinghua.edu.cn/simple](https://pypi.tuna.tsinghua.edu.cn/simple)

例如：pip install -i [https://pypi.tuna.tsinghua.edu.cn/simple](https://pypi.tuna.tsinghua.edu.cn/simple) pyspider，这样就会从清华这边的镜像去安装pyspider库。

永久修改，一劳永逸：

Linux下，修改 ~/.pip/pip.conf (没有就创建一个文件夹及文件。文件夹要加“.”，表示是隐藏文件夹)

内容如下：

windows下，直接在user目录中创建一个pip目录，如：C:\Users\xx\pip，新建文件pip.ini。内容同上。

如果报

WARNING: pip is configured with locations that require TLS/SSL, however the ssl module in Python is not available.

就把上面内容替换为

```
[global]
index-url = http://pypi.douban.com/simple
[install]
trusted-host=pypi.douban.com
```