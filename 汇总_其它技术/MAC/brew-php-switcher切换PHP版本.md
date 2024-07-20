这个brew-php-switch是使用系统自带的Apache配置。

/private/etc/apache2/httpd.conf


一定要注释掉系统自带的，我的是下面的libphp7.so

#LoadModule php7_module libexec/apache2/libphp7.so

官网 https://github.com/philcook/brew-php-switcher

我安装后。使用brew-php-switch xxx (brew-php-switch 5.6)

```
[bin] brew-php-switcher 5.6                                                                                            
Switching to php@5.6
Switching your shell
Unlinking /usr/local/Cellar/php@5.6/5.6.40... 0 symlinks removed
Unlinking /usr/local/Cellar/php@7.2/7.2.19... 25 symlinks removed
Unlinking /usr/local/Cellar/php/7.3.6... 0 symlinks removed
Linking /usr/local/Cellar/php@5.6/5.6.40... 25 symlinks created

If you need to have this software first in your PATH instead consider running:
  echo 'export PATH="/usr/local/opt/php@5.6/bin:$PATH"' >> ~/.zshrc
  echo 'export PATH="/usr/local/opt/php@5.6/sbin:$PATH"' >> ~/.zshrc
You will need sudo power from now on
Switching your apache conf
Restarting apache
All done!

```

显示了重启Apache。不知道为什么刷新页面还是之前的版本。我自己手动 sudo apachectl restart 也是不行 后来我试了下sudo apachectl -k restart  就可以了，所以我想改下源码


sudo vim /usr/local/Cellar/brew-php-switcher/2.1.1/bin/phpswitch.sh   (也许你的版本不一样，自己找到这个文件就行)

搜索下 sudo apachectl restart   

然后加上-k，再保存，强制保存就行


然后再试一次就行了