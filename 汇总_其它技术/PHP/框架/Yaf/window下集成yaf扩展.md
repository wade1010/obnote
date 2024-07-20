1、找到和你PHP版本对应的yaf扩展

[php_yaf-2.3.2-5.6-nts-vc11-x64.zip](attachments/WEBRESOURCE85f30d5937183ffc757027ba0e3b4a48php_yaf-2.3.2-5.6-nts-vc11-x64.zip)



http://pecl.php.net/package/yaf/2.3.2/windows   可以选择适合自己的版本，还有可能要翻墙连接。

2、下载后解压，要用到的只有php_yaf.dll这个文件，将它复制到php安装目录下的ext目录下，如：D:\Program Files\phpStudy\php56n\ext

3、修改php.ini文件。如下图：



![](https://gitee.com/hxc8/images8/raw/master/img/202407191107924.jpg)

4、重启Apache，查看phpinfo,可以搜索到yaf即安装成功

![](https://gitee.com/hxc8/images8/raw/master/img/202407191107143.jpg)





