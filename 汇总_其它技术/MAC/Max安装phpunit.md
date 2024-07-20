首先是因为brew install phpunit    安装的不行。不知道什么原因





$ wget https://phar.phpunit.de/phpunit.phar（可以到官网下载包，https://phpunit.de/）



$ chmod +x phpunit.phar(切换到下载的目录)



$ sudo mv phpunit.phar /usr/local/bin/phpunit



$ phpunit —version（看到版本即成功）

有时候装了却跑不起来，可能是因为版本不对！我装的是5.4不行，装5.3可以。

PHPstorm配置

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749342.jpg)

