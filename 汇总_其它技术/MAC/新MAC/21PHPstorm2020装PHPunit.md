### 安装 2020-05-19
#### brew install phpunit 

##### 上面安装的是9.1.4版本 PHPUnit now requires PHP 7.3 (or newer)


```
brew install phpunit
==> Downloading https://phar.phpunit.de/phpunit-9.1.4.phar
######################################################################## 100.0%
🍺  /usr/local/Cellar/phpunit/9.1.4: 3 files, 2.9MB, built in 2 seconds
```


/usr/local/Cellar/phpunit/9.1.4/bin/phpunit



### 配置

PHPstorm2020里面搜索phpunit 找到test frameworks


![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749766.jpg)

发现没有让我选phpunit bin文件的选项

以前的版本有个 "load from include path"


这里就用 “Path to phpunit.phar”

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749252.jpg)




## 其他尝试  使用 use composer autoloader

#### cd 到需要使用的项目根目录

> composer require --dev phpunit/phpunit

tm 太慢了 是国内源 放着出门回来装好了。也不知道花了多久

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749638.jpg)


### 下面给PHP5.6装
#### https://phpunit.de/announcements/phpunit-5.html
#### https://phpunit.de/getting-started/phpunit-5.html

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749213.jpg)

1、进入到项目根目录
2、wget -O phpunit https://phar.phpunit.de/phpunit-5.phar

![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749930.jpg)


---
---
---

#### 官方文档 

https://phpunit.readthedocs.io/zh_CN/latest/installation.html


#### https://phpunit.de/supported-versions.html


![image](https://gitee.com/hxc8/images7/raw/master/img/202407190749634.jpg)