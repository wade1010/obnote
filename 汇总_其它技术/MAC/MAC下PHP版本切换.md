homebrew 升级到2.1.4之后。已经不维护php5.6 tap了。所以brew install php56是不行的。

Mac系统10.13.6 我的系统自带Apache2.4+php7.1.16

#### 1、安装php7.2

> brew install php@7.2

安装完 会有info根据提示操作就行(也可以使用命令 brew info php@7.2查看)
```
To enable PHP in Apache add the following to httpd.conf and restart Apache:
    LoadModule php7_module /usr/local/opt/php@7.2/lib/httpd/modules/libphp7.so

    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>

Finally, check DirectoryIndex includes index.php
    DirectoryIndex index.php index.html

The php.ini and php-fpm.ini file can be found in:
    /usr/local/etc/php/7.2/

php@7.2 is keg-only, which means it was not symlinked into /usr/local,
because this is an alternate version of another formula.

If you need to have php@7.2 first in your PATH run:
  echo 'export PATH="/usr/local/opt/php@7.2/bin:$PATH"' >> ~/.zshrc
  echo 'export PATH="/usr/local/opt/php@7.2/sbin:$PATH"' >> ~/.zshrc

For compilers to find php@7.2 you may need to set:
  export LDFLAGS="-L/usr/local/opt/php@7.2/lib"
  export CPPFLAGS="-I/usr/local/opt/php@7.2/include"


To have launchd start php@7.2 now and restart at login:
  brew services start php@7.2
Or, if you don't want/need a background service you can just run:
  php-fpm

```

#### 2、安装php@5.6

这个就查了好久，因为homebrew新版本废弃了php56

```
$ brew tap exolnet/homebrew-deprecated

$ brew install php@5.6
```
上面安装完之后也可以。

```
To enable PHP in Apache add the following to httpd.conf and restart Apache:
    LoadModule php5_module /usr/local/opt/php@5.6/lib/httpd/modules/libphp5.so

    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>

Finally, check DirectoryIndex includes index.php
    DirectoryIndex index.php index.html

The php.ini and php-fpm.ini file can be found in:
    /usr/local/etc/php/5.6/

php@5.6 is keg-only, which means it was not symlinked into /usr/local,
because this is an alternate version of another formula.

If you need to have php@5.6 first in your PATH run:
  echo 'export PATH="/usr/local/opt/php@5.6/bin:$PATH"' >> ~/.zshrc
  echo 'export PATH="/usr/local/opt/php@5.6/sbin:$PATH"' >> ~/.zshrc

For compilers to find php@5.6 you may need to set:
  export LDFLAGS="-L/usr/local/opt/php@5.6/lib"
  export CPPFLAGS="-I/usr/local/opt/php@5.6/include"


To have launchd start exolnet/deprecated/php@5.6 now and restart at login:
  brew services start exolnet/deprecated/php@5.6
Or, if you don't want/need a background service you can just run:
  php-fpm
```

#### 3、安装php56的php56-mcrypt php56-yaf扩展

>brew services stop php@7.2

>brew services start exolnet/deprecated/php@5.6

>brew unlink php@7.2

>brew link php@5.6 --force


1、安装yaf

进入 https://github.com/laruence/yaf/tree/php5 下载源码进行编译安装(2019-06-01还是有的，以后不维护了不知道还有没有这个链接)


find / -name 'php-config' 2>>/dev/null
find / -name 'phpize' 2>>/dev/null

上面找出来的都会有两个。一个是PHP7.2一个是PHP5.6

/usr/local/bin/phpize和/usr/local/bin/php-config是php56

$/usr/local/bin/phpize 
$./configure --with-php-config=/usr/local/bin/php-config
make && make install

2、查找php56的php.ini位置后

>vim php.ini

加入下面代码，我是在文件末尾加入的

```
[yaf]
yaf.environ = product
yaf.library = NULL
yaf.cache_config = 0
yaf.name_suffix = 1
yaf.name_separator = ""
yaf.forward_limit = 5
yaf.use_namespace = 0
yaf.use_spl_autoload = 0
extension=yaf.so //关键步骤
```


---

##### 第二天改成用brew-php-switch来切换PHP版本，然后修改PATH，就可以用pecl来安装了

2、安装xdebug

https://pecl.php.net/package/Xdebug 查找需要的版本

然后选个版本

比如pecl install xdebug-2.6.0   (这个2.6.0及以上版本就需要php7了)

如果不能安装一般是PHP版本不对，就换个版本(xdebug-2.5.5)
