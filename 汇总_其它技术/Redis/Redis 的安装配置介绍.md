![Redis logo](https://gitee.com/hxc8/images8/raw/master/img/202407191116583.jpg)

redis 是一个高性能的key-value数据库。 redis的出现，很大程度补偿了memcached这类keyvalue存储的不足，在部 分场合可以对关系数据库起到很好的补充作用。它提供了Python，Ruby，Erlang，PHP客户端，使用很方便。问题是这个项目还很新，可能还不足够稳定，而且没有在实际的一些大型系统应用的实例。此外，缺乏mc中批量get也是比较大的问题，始终批量获取跟多次获取的网络开销是不一样的。

性能测试结果：

SET操作每秒钟 110000 次，GET操作每秒钟 81000 次，服务器配置如下：

Linux 2.6, Xeon X3320 2.5Ghz.

stackoverflow 网站使用 Redis 做为缓存服务器。

安装过程：

Redis是一种高级key-value数据库。它跟memcached类似，不过数据可以持久化，而且支持的数据类型很丰富。有字符串，链表，集 合和有序集合。支持在服务器端计算集合的并，交和补集(difference)等，还支持多种排序功能。所以Redis也可以被看成是一个数据结构服务 器。

Redis的所有数据都是保存在内存中，然后不定期的通过异步方式保存到磁盘上(这称为“半持久化模式”)；也可以把每一次数据变化都写入到一个append only file(aof)里面(这称为“全持久化模式”)。

一、下载最新版

wget http://redis.googlecode.com/files/redis-2.0.0-rc4.tar.gz

二、解压缩

tar redis-2.0.0-rc4.tar.gz

三、安装C/C++的编译组件（非必须）

apt-get install build-essential

四、编译

cd redis-2.0.0-rc4

make

make命令执行完成后，会在当前目录下生成本个可执行文件，分别是redis-server、redis-cli、redis-benchmark、redis-stat，它们的作用如下：

- redis-server：Redis服务器的daemon启动程序

- redis-cli：Redis命令行操作工具。当然，你也可以用telnet根据其纯文本协议来操作

- redis-benchmark：Redis性能测试工具，测试Redis在你的系统及你的配置下的读写性能

- redis-stat：Redis状态检测工具，可以检测Redis当前状态参数及延迟状况 

在后面会有这几个命令的说明，当然是从网上抄的。。。

五、修改配置文件

/etc/sysctl.conf

添加

vm.overcommit_memory=1

刷新配置使之生效

sysctl vm.overcommit_memory=1 

补充介绍：

 **如果内存情况比较紧张的话，需要设定内核参数：

echo 1 > /proc/sys/vm/overcommit_memory

 内核参数说明如下：

overcommit_memory文件指定了内核针对内存分配的策略，其值可以是0、1、2。

0， 表示内核将检查是否有足够的可用内存供应用进程使用；如果有足够的可用内存，内存申请允许；否则，内存申请失败，并把错误返回给应用进程。

1， 表示内核允许分配所有的物理内存，而不管当前的内存状态如何。

2， 表示内核允许分配超过所有物理内存和交换空间总和的内存

 **编辑redis.conf配置文件（/etc/redis.conf），按需求做出适当调整，比如：

daemonize yes #转为守护进程，否则启动时会每隔5秒输出一行监控信息

save 60 1000 #减小改变次数，其实这个可以根据情况进行指定

maxmemory 256000000 #分配256M内存



在我们成功安装Redis后，我们直接执行redis-server即可运行Redis，此时它是按照默认配置来运行的（默认配置甚至不是后台运 行）。我们希望Redis按我们的要求运行，则我们需要修改配置文件，Redis的配置文件就是我们上面第二个cp操作的redis.conf文件，目前 它被我们拷贝到了/usr/local/redis/etc/目录下。修改它就可以配置我们的server了。如何修改？下面是redis.conf的主 要配置参数的意义：

- daemonize：是否以后台daemon方式运行

- pidfile：pid文件位置

- port：监听的端口号

- timeout：请求超时时间

- loglevel：log信息级别

- logfile：log文件位置

- databases：开启数据库的数量

- save * *：保存快照的频率，第一个*表示多长时间，第三个*表示执行多少次写操作。在一定时间内执行一定数量的写操作时，自动保存快照。可设置多个条件。

- rdbcompression：是否使用压缩

- dbfilename：数据快照文件名（只是文件名，不包括目录）

- dir：数据快照的保存目录（这个是目录）

- appendonly：是否开启appendonlylog，开启的话每次写操作会记一条log，这会提高数据抗风险能力，但影响效率。

- appendfsync：appendonlylog如何同步到磁盘（三个选项，分别是每次写都强制调用fsync、每秒启用一次fsync、不调用fsync等待系统自己同步）

下面是一个略做修改后的配置文件内容：

daemonize yes

pidfile /usr/local/redis/var/redis.pid

port 6379

timeout 300

loglevel debug

logfile /usr/local/redis/var/redis.log

databases 16

save 900 1

save 300 10

save 60 10000

rdbcompression yes

dbfilename dump.rdb

dir /usr/local/redis/var/

appendonly no

appendfsync always

glueoutputbuf yes

shareobjects no

shareobjectspoolsize 1024

将上面内容写为redis.conf并保存到/usr/local/redis/etc/目录下

然后在命令行执行：

|   |   |
| - | - |
| 1 | /usr/local/redis/bin/redis-server /usr/local/redis/etc/redis.conf |


即可在后台启动redis服务，这时你通过

|   |   |
| - | - |
| 1 | telnet 127.0.0.1 6379 |


即可连接到你的redis服务。

六、启动服务并验证

启动服务器

./redis-server 

或 

$redis-server /etc/redis.conf  

查看是否成功启动 

$ ps -ef | grep redis   

或 

./redis-cli ping 

PONG

七、启动命令行客户端赋值取值

redis-cli set mykey somevalue

./redis-cli get mykey

八、关闭服务

$ redis-cli shutdown   

#关闭指定端口的redis-server  

$redis-cli -p 6380 shutdown 

九、客户端也可以使用telnet形式连接。

[root@dbcache conf]# telnet 127.0.0.1 6379 

Trying 127.0.0.1... 

Connected to dbcache (127.0.0.1). 

Escape character is '^]'. 

set foo 3 

bar 

+OK 

get foo 

$3 

bar 

^] 

telnet> quit 

Connection closed.