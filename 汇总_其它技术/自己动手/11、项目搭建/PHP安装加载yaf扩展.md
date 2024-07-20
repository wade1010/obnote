Yaf，全称 Yet Another Framework，是一个C语言编写的PHP框架，是一个用PHP扩展形式提供的PHP开发框架, 相比于一般的PHP框架, 它更快. 它提供了Bootstrap, 路由, 分发, 视图, 插件, 是一个全功能的PHP框架。最大特点就是简单、高效、快速，已经在百度和新浪微博经过大平台验证。

      Yaf的作者Laruence(惠新宸)，是国内首位PHP语言开发组成员,Zend兼职顾问, Yaf, Yar, Yac, Opcache等项目作者、维护者，曾经供职与雅虎、百度，目前是新浪微博首席PHP技术顾问。

      “微博每天PV数十亿，产生数T级别的数据， 处理过程中任何一个毫秒的优化，一个byte的减少，对我们都是意义重大，这个工作非常有意思也很有挑战， 到目前为止，我们团队已经通过一些不改动业务逻辑的优化方法，把微博首页的响应时间降低了44%，TPS提升了78%。另外，我们团队还负责技术提升和沉淀工作，这也是一项很有意思的工作，因为分享能让我们收获更多。

 

       1 .Yaf其实算是PHP官方的一个扩展，我们可以直接在PHP官网下载。 http://pecl.php.net/package/yaf

 

       2 .Git 仓库 https://github.com/laruence/php-yaf

 

       3 . yaf官方文档 ：http://www.laruence.com/manual/



---

2019-3-24 16:47:44

补充：php7需要安装最新版

1. wget http://pecl.php.net/get/yaf-3.0.8.tgz

1. tar -zxvf yaf-3.0.8.tgz&& cd yaf-3.0.8

1. phpize

1.  ./configure --with-php-config=/usr/bin/php-config && make && make test

1. 我这里安装报错，需要修改php.ini，去掉disable中的proc_open

1. 查看扩展安装的路径 make install

1. 添加扩展(可参照下面教程)

---

安装步骤：

1. 下载  wget http://pecl.php.net/get/yaf-2.3.3.tgz 

1. 解压并进入    tar -zxvf yaf-2.3.3* && cd yaf-2.3.3 

1. 预处理   phpize

1. 执行配置信息并安装      ./configure --with-php-config=/usr/bin/php-config && make && make test

1. 查看扩展安装的路径        make install

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755803.jpg)

1. 配置php.ini支持yaf扩展

1. php --ini    查看ini文件位置

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755252.jpg)

1. vim  /usr/local/php/etc/php.ini   加入如下代码

                      [yaf]

yaf.environ = product

yaf.library = NULL

yaf.cache_config = 0

yaf.name_suffix = 1

yaf.name_separator = ""

yaf.forward_limit = 5

yaf.use_namespace = 0

yaf.use_spl_autoload = 0

extension=yaf.so //关键步骤,以上可以忽略，最主要是加载yaf.so模块

1. 重启apache

1. 查看是否安装成功  如下图，已成功！

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755609.jpg)



