这篇文章中你将了解到以下内容

查看php基本环境

理解PHP运行模式

brew-php-switcher基本使用

理解bash_profile配置文件

brew安装软件相关的命令

在开始执行PHP版本相关操作之前，我们先检查下本地环境重启Mac之后

执行以下命令

brew services list



![](https://upload-images.jianshu.io/upload_images/5651-51aaeb0fb74b8861.png?imageMogr2/auto-orient/strip|imageView2/2/w/1200/format/webp)



以上我们可以看出 有两个php服务存在，而系统或者命令行会认其中的一个，识别哪个php环境是另外一件事，后边会叙述。

这里先补充php运行的一个基础知识

PHP的运行模式

业界公认的PHP运行模式有4种

1 CGI通用网关接口模式

2 FAST-CGI模式

3 CLI命令行模式

4 模块模式

前两者涉及到协议升级，也就是常说的php-fpm，模块模式涉及到服务器与协议的搭配，比如Apache，nginxCLI命令行模式就是shell命令行，php -v;这样的命令理解这个概念，有助于判断当前运行的PHP实际版本是哪一个。 

进而理解 php -v;结果和 网页phpinfo() 执行结果不一样的根本原因。

php-fpm -v

PHP 7.2.12 (fpm-fcgi) (built: Nov  9 2018 10:58:18)Copyright (c) 1997-2018 The PHP GroupZend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies    with Zend OPcache v7.2.12, Copyright (c) 1999-2018, by Zend Technologies

查看PHP相关版本

首先查看官方支持的php相关版本有哪些

brew search php

![](https://upload-images.jianshu.io/upload_images/5651-f113aa83b2050153.png?imageMogr2/auto-orient/strip|imageView2/2/w/1200/format/webp)

brew-php-switcher是什么

brew-php-switcher是一个php环境版本切换工具，对通过brew安装的php版本进行切换。

github地址 https://github.com/philcook/brew-php-switcher

作为php版本切换管理工具，brew-php-switcher与php version功能一致，可惜php version已经放弃使用，对于mac用户，只能选择brew-php-switcher作为版本切换工具。

brew 不提供5.6的安装源了，所以brew-php-switcher也无法实现5.6的切换了。

安装

brew brew-php-switcher install

基本使用

brew-php-switcher +版本号

brew-php-switcher 

usage: brew-php-switcher version [-s|-s=*] [-c=*]

    version    one of: 7.0,7.1,7.2,7.3

    -s        skip change of mod_php on apache

    -s=*      skip change of mod_php on apache or valet restart i.e (apache|valet,apache|valet)

    -c=*      switch a specific config (apache|valet,apache|valet

如切换7.2版本

brew-php-switcher   7.2

Switching to php@7.2

Switching your shell

Unlinking /usr/local/Cellar/php@5.6/5.6.38... 25 symlinks removed

Unlinking /usr/local/Cellar/php@7.2/7.2.18... 0 symlinks removed

Unlinking /usr/local/Cellar/php/7.3.5... 0 symlinks removed

Linking /usr/local/Cellar/php@7.2/7.2.18... 25 symlinks created

If you need to have this software first in your PATH instead consider running:

  echo 'export PATH="/usr/local/opt/php@7.2/bin:$PATH"' >> ~/.bash_profile

  echo 'export PATH="/usr/local/opt/php@7.2/sbin:$PATH"' >> ~/.bash_profile

按照以上提示修改 环境变量并且

 source ~/.bash_profile

生效之后

执行php -v;  控制台php命令生效(cli)

php -v;

PHP 7.2.18 (cli) (built: May 22 2019 00:08:35) ( NTS )

Copyright (c) 1997-2018 The PHP Group

Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies

    with Zend OPcache v7.2.18, Copyright (c) 1999-2018, by Zend Technologies

执行php-fpm -v;

php-fpm -v;

PHP 7.2.18 (fpm-fcgi) (built: May 22 2019 00:08:38)

Copyright (c) 1997-2018 The PHP Group

Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies

    with Zend OPcache v7.2.18, Copyright (c) 1999-2018, by Zend Technologies



查看软连接

cd /usr/local/opt/php@7.2/sbin/local/opt

ls -l

lrwxr-xr-x  1  admin  19  6  2 06:21 php -> ../Cellar/php/7.3.5

lrwxr-xr-x  1  admin  32  3 20 16:07 php-code-sniffer -> ../Cellar/php-code-sniffer/3.4.1

lrwxr-xr-x  1  admin  24  5 28 09:25 php@5.6 -> ../Cellar/php@5.6/5.6.38

lrwxr-xr-x  1  admin  24  6  2 05:54 php@7.2 -> ../Cellar/php@7.2/7.2.18

lrwxr-xr-x  1  admin  19  6  2 06:21 php@7.3 -> ../Cellar/php/7.3.5



识别PHP服务

上文中提到 brew services显示两个php服务，我们可以使用下边的命令依次关闭，检验web服务识别的是哪个PHP版本

brew services stop php

brew services stop php@7.2

分别使用phpinfo() 查看当前的运行版本，当php服务全部关闭时，网页会直接显示502

brew services restart php

查看进程

ps -ef | grep php-fpm



brew services start php@7.2

If you need to have php@7.2 first in your PATH run:

  echo 'export PATH="/usr/local/opt/php@7.2/bin:$PATH"' >> ~/.bash_profile

  echo 'export PATH="/usr/local/opt/php@7.2/sbin:$PATH"' >> ~/.bash_profile

You can also run `php --ini` inside terminal to see which files are used by PHP in CLI mode.

初步理解bash_profile配置文件

mac环境下，自定义安装的软件都会在 /usr/local/Cellar 路径下，当安装软件与系统原有软件相互冲突时，比如Python2.7与python3.n，PHP5.6与PHP7.n, 就需要指定默认使用哪个版本。

系统配置文件

~/.bash_profile

通过修改~/.bash_profile，使系统识别默认的Python版本为3.7

alias python="/usr/local/Cellar/python/3.7.0/bin/python3.7"

命令生效  source  ~/.bash_profile

PHP7安装mongodb扩展

which pecl

sudo /usr/local/opt/php@7.2/bin/pecl  install mongodb

Build process completed successfully

Installing '/usr/local/Cellar/php@7.2/7.2.14/pecl/20170718/mongodb.so'

install ok: channel://pecl.php.net/mongodb-1.5.3

Extension mongodb enabled in php.ini

YII2 MongoDb扩展https://packagist.org/packages/yiisoft/yii2-mongodb"yiisoft/yii2-mongodb": "~2.1.0" 2.1.0 是一个版本节点，之前使用老的mogo驱动。

php -m | grep mongodb

最后查了一圈资料发现 这个网址下的安装教程挺实用的，还是依靠pecl安装。

https://www.runoob.com/mongodb/php7-mongdb-tutorial.html

PHP7安装redis扩展

 sudo /usr/local/opt/php@7.2/bin/pecl  install  igbinary

sudo /usr/local/opt/php@7.2/bin/pecl  install redis

Build process completed successfully

Installing '/usr/local/Cellar/php@7.2/7.2.14/pecl/20170718/redis.so'

install ok: channel://pecl.php.net/redis-4.2.0

Extension redis enabled in php.ini



补充常用命令

查看php ini配置文件路径

php -i | grep php.ini

输出

Configuration File (php.ini) Path => /usr/local/etc/php/7.2

Loaded Configuration File => /usr/local/etc/php/7.2/php.ini

查看pecl路径

which pecl

输出

/usr/local/opt/php@7.2/bin/pecl

查看通过brew安装的服务列表

brew services list



参考资料

解决php -v查看到版本于phpinfo()打印的版本不一致问题

PHP四种运行模式

https://blog.csdn.net/u013549582/article/details/85097407

dyld: Library not loaded