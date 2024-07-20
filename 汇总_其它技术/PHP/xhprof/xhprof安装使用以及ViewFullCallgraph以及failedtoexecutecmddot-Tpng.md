#### 环境
mac+php7.2+apache2.4+homebrew

#### 安装
方法1、pecl install xhprof （好像不支持php7）

方法2、点击查看[参考文档](https://github.com/longxinH/xhprof.git)

>install

如果不知道phpize和php-config目录可以用下面代码找到。我是多版本PHP切换用，有时候系统找到的那个不是我需要的

>find / -name 'phpize' 2>>/dev/null

>find / -name 'php-config' 2>>/dev/null

```
git clone https://github.com/longxinH/xhprof.git ./xhprof
cd xhprof/extension/
/path/to/php7/bin/phpize
./configure --with-php-config=/path/to/php7/bin/php-config
make && sudo make install
```

>configuration add to your php.ini
```
[xhprof]
extension = xhprof.so
xhprof.output_dir = /tmp/xhprof
```

>sudo apachectl restart

>php -m|grep xhprof

查得到就是安装成功了

#### 配置虚拟站点访问分析报告

我是用apache的,项目都放在home/www下，我在这个目录下新建个文件夹叫xhprof

>cd /home/www

>mkdir xhprof

>克隆xhprof的xhprof_html 和 xhpro_lib（这写可以在安装步骤里面的git中找到）

>cp -r 之前解压缩的xhprof目录/xhprof_html /home/www/xhprof

>cp -r 之前解压缩的xhprof目录/xhprof_lib /home/www/xhprof

>配置apache站点

```
<VirtualHost *:80>
    DocumentRoot "/home/www/xhprof"
    ServerName local.xhprof.com
    <Directory /home/www/xhprof>
        Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>
</VirtualHost>

```

>vim /etc/hosts

add 下面的配置
```
127.0.0.1 local.xhprof.com
```

>sudo apachectl restart


#### 访问报告

浏览器打开 http://local.xhprof.com/xhprof_html

就可以看到下面的报告了

tip:我把xhprof.output_dir也设置成了xhprof目录下。

![image](https://gitee.com/hxc8/images8/raw/master/img/202407191105847.jpg)


点击其中一个报告会有下图的信息

![image](https://gitee.com/hxc8/images8/raw/master/img/202407191105122.jpg)

如果想用图中的[View Full Callgraph],直接点击，如果报错failed to execute cmd: ” dot -Tpng”

- brew install graphviz

如果还是报错，我是这么处理的

修改xhprof/xhprof_lib/utils/callgraph_utils.php

大概113行 将
```
$cmd = " dot -T" . $type;
```
改成
```
$cmd = "/usr/local/Cellar/graphviz/2.40.1/bin/dot -T" . $type;
```

再重新点击[View Full Callgraph]就能生成了，如下图

![image](https://gitee.com/hxc8/images8/raw/master/img/202407191105949.jpg)


#### 名词解释

```
Function Name 函数名
Calls 调用次数
Calls% 调用百分比
Incl. Wall Time (microsec) 调用的包括子函数所有花费时间 以微秒算(一百万分之一秒)
IWall% 调用的包括子函数所有花费时间的百分比
Excl. Wall Time (microsec) 函数执行本身花费的时间，不包括子树执行时间,以微秒算(一百万分之一秒)
EWall% 函数执行本身花费的时间的百分比，不包括子树执行时间
Incl. CPU(microsecs) 调用的包括子函数所有花费的cpu时间。减Incl. Wall Time即为等待cpu的时间
减Excl. Wall Time即为等待cpu的时间
ICpu% Incl. CPU(microsecs)的百分比
Excl. CPU(microsec) 函数执行本身花费的cpu时间，不包括子树执行时间,以微秒算(一百万分之一秒)。
ECPU% Excl. CPU(microsec)的百分比
Incl.MemUse(bytes) 包括子函数执行使用的内存。
IMemUse% Incl.MemUse(bytes)的百分比
Excl.MemUse(bytes) 函数执行本身内存,以字节算
EMemUse% Excl.MemUse(bytes)的百分比
Incl.PeakMemUse(bytes) Incl.MemUse的峰值
IPeakMemUse% Incl.PeakMemUse(bytes) 的峰值百分比
Excl.PeakMemUse(bytes) Excl.MemUse的峰值
EPeakMemUse% EMemUse% 峰值百分比
```