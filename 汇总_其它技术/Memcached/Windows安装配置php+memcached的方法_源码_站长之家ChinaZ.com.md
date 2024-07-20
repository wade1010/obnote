Windows下Memcached的安装配置方法

1、下载http://download.csdn.net/detail/bbirdsky/7395123

将第一个包解压放某个盘下面，比如在c:\memcached。

2、在终端（也即cmd命令界面）下输入 'c:\memcached\memcached.exe -d install' 安装。

3、再输入： 'c:\memcached\memcached.exe -d start' 启动。（需要注意的: 以后memcached将作为windows的一个服务每次开机时自动启动。这样服务器端已经安装完毕了）。

4、下载php_memcache.dll 文件，

全部文件下载地址：http://pan.baidu.com/share/link?shareid=2718974422&uk=3978399093

把它放入php文件夹的ext目录中。

5、在php.ini加入一行引用扩展，代码如下：

extension=php_memcache.dll

6、接着在 php.ini 文件里加上:

[Memcache]memcache.allow_failover = 1memcache.max_failover_attempts=20memcache.chunk_size =8192memcache.default_port = 11211 

最好就放在刚才写 "extension=php_memcache.dll" 的下面。（这是默认的一些配置）

7、重新启动Apache，然后查看一下phpinfo，如果有 memcache 的说明，那么就说明安装成功啦！

　　写一个 example.php 文件（更多使用方法可以参看 PHP 手册里的 Memcache Functions 使用说明），测试代码如下：

$memcache=new Memcache;$memcache->connect('localhost',11211)ordie("Could not connect");$version=$memcache->getVersion();echo"Server's version: ".$version."

\n";$tmp_object=new stdClass;$tmp_object->str_attr ='test';$tmp_object->int_attr =123;$memcache->set('key',$tmp_object, false,10)ordie("Failed to save data at the server");echo"Store data in the cache (data will expire in 10 seconds)

\n";$get_result=$memcache->get('key');echo"Data from the cache:

\n";var_dump($get_result);?>

如果输出如下，则测试成功：

Server's version: 1.4.5Store data in the cache (data will expire in 10 seconds)Data from the cache:object(stdClass)#3 (2) { ["str_attr"]=> string(4) "test" ["int_attr"]=> int(123) } 

Memcached的基本参数设置：

- -p 监听的端口

- -l 连接的IP地址, 默认是本机

- -d start 启动memcached服务

- -d restart 重起memcached服务

- -d stop|shutdown 关闭正在运行的memcached服务

- -d install 安装memcached服务

- -d uninstall 卸载memcached服务

- -u 以的身份运行 (仅在以root运行的时候有效)

- -m 最大内存使用，单位MB。默认64MB

- -M 内存耗尽时返回错误，而不是删除项

- -c 最大同时连接数，默认是1024

- -f 块大小增长因子，默认是1.25

- -n 最小分配空间，key+value+flags默认是48

- -h 显示帮助<-->