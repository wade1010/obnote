背景：brew 安装的httpd我卸载了。用的是系统自带的Apache

> brew info brew-php-switcher

查看目录


```
 brew info brew-php-switcher
brew-php-switcher: stable 2.2, HEAD
Switch Apache / Valet / CLI configs between PHP versions
https://github.com/philcook/php-switcher
/usr/local/Cellar/brew-php-switcher/2.2 (6 files, 11.2KB) *
  Built from source on 2020-05-14 at 16:10:54

```

> /usr/local/Cellar/brew-php-switcher/2.2/bin

> sudo vim phpswitch.sh

在 echo "All done!" 前一行加入下面的代码


```
if [[ "$php_version" == "php@5.6" ]]; then
    echo "switch icu4c 64.2"
    brew switch icu4c 64.2
    echo "switch openssl 1.0.2t"
    brew switch openssl 1.0.2t
    
else
    echo "switch icu4c 66.1"
    brew switch icu4c 66.1
    echo "switch openssl 1.1.1g"
    brew switch openssl 1.1.1g
fi
echo "All done!"
```

之后就可以自由切换PHP版本了
        
        