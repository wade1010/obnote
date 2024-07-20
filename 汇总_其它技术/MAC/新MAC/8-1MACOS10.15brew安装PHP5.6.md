最简单的是用下面的安装
```
curl -s http://php-osx.liip.ch/install.sh | bash -s 5.6
```

我这里用的是brew


MAC新版本10.15.4 系统自带的PHP是7.3

下面是安装步骤

$ brew tap exolnet/homebrew-deprecated

$ brew install php@5.6



安装失败，大致安装信息如下


```
==> Reinstalling exolnet/deprecated/php@5.6 
==> Pouring php@5.6-5.6.40.mojave.bottle.2.tar.gz
==> /usr/local/Cellar/php@5.6/5.6.40/bin/pear config-set php_ini /usr/local/etc/
Last 15 lines from /Users/xxx/Library/Logs/Homebrew/php@5.6/post_install.01.pear:
2020-05-14 23:21:38 +0800

/usr/local/Cellar/php@5.6/5.6.40/bin/pear
config-set
php_ini
/usr/local/etc/php/5.6/php.ini
system

dyld: Library not loaded: /usr/local/opt/openssl/lib/libcrypto.1.0.0.dylib
  Referenced from: /usr/local/Cellar/php@5.6/5.6.40/bin/php
  Reason: image not found
Warning: The post-install step did not complete successfully
You can try again using `brew postinstall exolnet/deprecated/php@5.6`
==> Caveats
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
==> Summary
🍺  /usr/local/Cellar/php@5.6/5.6.40: 498 files, 60.5MB
% brew install https://github.com/tebelorg/Tump/releases/download/v1.0.0/openssl.rb

```
执行下面命令 会报错
> /usr/local/opt/php@5.6/bin/php -v     

错误如下

```
dyld: Library not loaded: /usr/local/opt/openssl@1.1/lib/libssl.1.1.dylib
  Referenced from: /usr/local/opt/libpq/lib/libpq.5.dylib

```

```
onhttpproxy   (我自己的命令，开启终端代理加速下载而已，没有可不执行)
```

```
brew install https://github.com/tebelorg/Tump/releases/download/v1.0.0/openssl.rb
```

如果下载不下来

可以分开操作

```
mkdir tem
cd tem
wget https://github.com/tebelorg/Tump/releases/download/v1.0.0/openssl.rb
brew install ./openssl.rb

```

```
brew switch openssl 1.0.2t
```


再执行 报错

```
 /usr/local/opt/php@5.6/bin/php -v                                                              /usr/local/Homebrew/Library/Taps/homebrew/homebrew-core/Formula
dyld: Library not loaded: /usr/local/opt/icu4c/lib/libicui18n.64.dylib
  Referenced from: /usr/local/opt/php@5.6/bin/php
  Reason: image not found

```

先备份原来的高版本(目前是66.1)，php7还需要

> cd /usr/local/Cellar/

> cp -rf icu4c icu4c66


安装需要的版本（64）


> cd $(brew --prefix)/Homebrew/Library/Taps/homebrew/homebrew-core/Formula

> git log --follow icu4c.rb

```
commit 22fb699a417093cd1440857134c530f1e3794f7d
Author: Bo Anderson <mail@boanderson.me>
Date:   Thu Apr 23 14:37:03 2020 +0000

    icu4c: update 66.1 bottle.

commit c78114de1252ac63590b06c1f2325e576a5d5226
Author: Pavel Omelchenko <p.Omelchenko@gmail.com>
Date:   Fri Apr 3 00:58:01 2020 +0300

    icu4c 66.1

commit a806a621ed3722fb580a58000fb274a2f2d86a6d
Author: Thierry Moisan <thierry.moisan@gmail.com>
Date:   Wed Oct 2 13:07:31 2019 -0400

    icu4c: update homepage and url (#44812)

commit 896d1018c7a4906f2c3fa1386aaf283497db60a2
Author: BrewTestBot <homebrew-test-bot@lists.sfconservancy.org>
Date:   Sat Sep 28 13:49:39 2019 +0000

    icu4c: update 64.2 bottle.

commit c81a048b0ebea0ba976af220806fb8ef35201e9a (icu4c-64)
Author: BrewTestBot <homebrew-test-bot@lists.sfconservancy.org>
Date:   Fri Apr 19 03:35:49 2019 +0000

    icu4c: update 64.2 bottle.

commit 44895fce117ab92a44d733315b702c48dbb3898d
Author: Chongyu Zhu <i@lembacon.com>
Date:   Thu Mar 28 09:47:20 2019 +0800

    icu4c 64.2
    
    后面省略 没用
```
 选一个 64.2版本
 
> git checkout -b icu4c-64 c81a048b0ebea0ba976af220806fb8ef35201e9a


(之后记得切换回master分支)

> brew reinstall ./icu4c.rb

> cd /usr/local/Cellar/icu4c

> cp -rf ../icu4c66/66.1 .

记得删除备份的icu4c66目录

接下来就可以切换 不同的icu4c了

> brew switch icu4c 66.1   (php7时需要)

> brew switch icu4c 64.2   (php5.6是需要)

tip:这个切换可以写在脚本里，比如brew-php-switcher

再执行 就OK了


```
后续使用一段时间发现有问题，补充：
执行brew cleanup
会删掉icu4c 64.2 所以可以先备份icu4c这个目录。
假如删除了，可以cp过去
我这里brew cleanup加了-n 只输出要删除的
假如删除了

cp -rf /usr/local/Cellar/icu4cbak/64.2 /usr/local/Cellar/icu4c
```


```
/usr/local/opt/php@5.6/bin/php -v     
PHP 5.6.40 (cli) (built: Apr 23 2019 11:14:34) 
Copyright (c) 1997-2016 The PHP Group
Zend Engine v2.6.0, Copyright (c) 1998-2016 Zend Technologies
```


接下来配置Apache

我brew 安装php56的时候 会默认给我装个新的Apache。我这里使用系统自带的，所以卸载了新装的

> brew uninstall httpd

更改Apache配置

> sudo vim /private/etc/apache2/httpd.conf

加入下面内容

```
LoadModule php5_module /usr/local/opt/php@5.6/lib/httpd/modules/libphp5.so

<FilesMatch \.php$>
    SetHandler application/x-httpd-php
</FilesMatch>
```

找到 DirectoryIndex 添加一个index.php

```
<IfModule dir_module>
    DirectoryIndex index.php index.html
</IfModule>
```

然后保存配置，并重启Apache

---

```
<FilesMatch \.php$>
    SetHandler application/x-httpd-php
</FilesMatch>
这一段是解释PHP的。否则Apache会原样输出PHP文件的内容
```


找到httpd.conf 中DocumentRoot的文件夹

在里面新建一个index.php

内容如下

```
<?php
phpinfo();
```


浏览器打开localhost测试

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749585.jpg)


> brew untap exolnet/homebrew-deprecated