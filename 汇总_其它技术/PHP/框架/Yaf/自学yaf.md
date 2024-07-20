#### 文档
https://www.php.net/manual/en/book.yaf.php

https://pecl.php.net/package/yaf

#### 安装
pecl install yaf

然后在php.ini末尾加入

```
[yaf]
extension="yaf.so"
yaf.use_namespace=1
```
yaf.use_namespace=1这个后面可以关闭，关闭后，在IDE中全局替换下"Yaf\"替换成"Yaf_"即可


>sudo apchectl restart

#### 使用yaf_code generator快速生成yaf框架代码

https://github.com/laruence/yaf/tree/master/tools/cg

下载后(我是整个clone下来，然后复制里面的cg目录)

执行

>php yaf_cg Sample 'home/workspace/yafdemo' n

即可生成框架代码

然后稍微改动下，跟官网哪个保持一致就挺好

![image](https://gitee.com/hxc8/images8/raw/master/img/202407191106804.jpg)

其实就是index.php .htaccess移到public目录下

移动后 修改下index.php代码,如下


>define('APPLICATION_PATH', dirname(dirname(__FILE__)));

