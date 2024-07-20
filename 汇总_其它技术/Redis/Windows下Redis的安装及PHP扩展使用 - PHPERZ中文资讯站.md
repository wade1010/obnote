|   |
| - |
| 本文为大家讲解的是在Windows下安装Redis和PHP扩展及简单使用方法，感兴趣的同学参考下。<br>1、下载redis的windows应用程序，支持32位和64位，根据实际情况下载<br>下载地址：https://github.com/dmajkic/redis/downloads<br>2、将相应的程序copy到你所需要的目录中，在这里我使用的64位，放到E:\\redis目录<br>3、启动redis服务端：<br>打开一个cmd窗口，先切换到redis所放目录（E:\\redis），运行 redis-server.exe redis.conf <br>注意redis.conf为配置文件，主要配置了redis所使用的端口等信息（如果不写则默认redis.conf）<br>有的下载的redis压缩包里没有redis.conf，我把默认的redis.conf的文件内容放在文章最后。<br>注意：此窗口为redis服务端运行窗口，关闭后则redis关闭。<br>4、启动redis客户端：另开一个cmd窗口，进入目录之后运行命令redis-cli.exe -h 127.0.0.1 -p 6379，然后就可以进行操作了<br>5、下载redis的php扩展：<br>下载地址：<br>http://windows.php.net/downloads/pecl/snaps/redis/2.2.5/<br>或者<br>https://github.com/nicolasff/phpredis/downloads<br>根据php的版本来下载相应的扩展，版本必须对应<br>6、将php\_redis.dll放入php的ext文件夹中，然后再php.ini添加代码extension=php\_redis.dll<br>7、重启web服务器<br>8、php测试<br>&lt;?php  <br>    $redis = new Redis();  <br>    $redis-&gt;connect('127.0.0.1',6379);  <br>    $redis-&gt;set('test','hello redis');  <br>    echo $redis-&gt;get('test');  <br>?&gt; |


![](https://gitee.com/hxc8/images8/raw/master/img/202407191111635.jpg)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191112586.jpg)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191112636.jpg)

![](https://gitee.com/hxc8/images8/raw/master/img/202407191112047.jpg)

