启动 memcached -m 64 -p 11211 -u nobody -vv





telnet链接  telnet localhost 11211



telnet请求命令格式

<commandname> <key> <flags> <exptime><bytes>\r\n <data block>\r\n

1,<commandname> 可以是”set”,“add”, “replace”。

“set”表示按照相应的<key>存储该数据，没有的时候增加，有的覆盖。

“add”表示按照相应的<key>添加该数据,但是如果该<key>已经存在则会操作失败。

“replace”表示按照相应的<key>替换数据,但是如果该<key>不存在则操作失败

2,<key>客户端需要保存数据的key。

3,<flags>是一个16位的无符号的整数(以十进制的方式表示)。

该标志将和需要存储的数据一起存储,并在客户端get数据时返回。

客户可以将此标志用做特殊用途，此标志对服务器来说是不透明的。

4,<exptime>过期的时间。

若为0表示存储的数据永远不过时(但可被服务器算法：LRU等替换)。

如果非0(unix时间或者距离此时的秒数),当过期后,服务器可以保证用户得不到该数据(以服务器时间为标准)。

5,<bytes>需要存储的字节数(不包含最后的”\r\n”),当用户希望存储空数据时,<bytes>可以为0

6,“STORED\r\n”:表示存储成功.“NOT_STORED\r\n”:表示存储失败,但是该失败不是由于错误。



增: add 往内存增加一行新记录

语法: add key flag expire length 回车

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/D29AE572DF0248459AEDE2355FB1E799image.png)

key 给值起一个独特的名字

flag 标志,要求为一个正整数

expire 有效期

length 缓存的长度(字节为单位)

flag 的意义:

memcached 基本文本协议,传输的东西,理解成字符串来存储.

想:让你存一个 php 对象,和一个 php 数组,怎么办?

答:序列化成字符串,往出取的时候,自然还要反序列化成 对象/数组/json 格式等等.

这时候, flag 的意义就体现出来了.

比如, 1 就是字符串, 2 反转成数组 3,反序列化对象.....

expire 的意义:

设置缓存的有效期,有 3 种格式

1:设置秒数, 从设定开始数,第 n 秒后失效.

2:时间戳, 到指定的时间戳后失效.

比如在团购网站,缓存的某团到中午 12:00 失效. add key 0 1379209999 6

3: 设为 0. 不自动失效.

注: 有种误会,设为 0,永久有效.错误的



1:编译 memcached 时,指定一个最长常量,默认是 30 天.

所以,即使设为 0,30 天后也会失效.

2:可能等不到 30 天,就会被新数据挤出去.



delete 删除

delete key [time seconds]

删除指定的 key. 如加可选参数 time,则指删除 key,并在删除 key 后的 time 秒内,不允许

get,add,replace 操作此 key.



replace 替换

replace key flag expire length

参数和 add 完全一样,不单独写



get 查询

get key

返回 key 的值





set 是设置和修改值

参数和 add ,replace 一样,但功能不一样.



如下比较:

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/A917E571160F42B882B8A2275960F513image.png)



用 add 时, key 不存在,才能建立此键值.

但对于已经存在的键,可以用 replace 进行替换/更改

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/1E2A821EE32A4653B2E5FCFB9067B441image.png)

repalce,key 存在时,才能修改此键值,如上图,date 不存在,则没改成功.

而 set 想当于有 add replace 两者的功能.

set key flag expire leng 时

如果服务器无此键 ----> 增加的效果

如果服务器有此键 ----> 修改的效果.

如下图的演示,该图中,name 是已经存在,而 date 原本不存在. set 都可以成功设置他们.

![](D:/download/youdaonote-pull-master/data/Technology/Memcached/images/55F97AAB7A5A4977AD397C6BA6CF8981image.png)



incr ,decr 命令:增加/减少值的大小

语法: incr/decr key num



应用场景------秒杀功能,

一个人下单,要牵涉数据库读取,写入订单,更改库存,及事务要求, 对于传统型数据库来说,

压力是巨大的.

可以利用 memcached 的 incr/decr 功能, 在内存存储 count 库存量, 秒杀 1000 台

每人抢单主要在内存操作,速度非常快,

抢到 count<=1000 的号人,得一个订单号,再去另一个页面慢慢支付





统计命令: stats

把 memcached 当前的运行信息统计出来

stats

stat pid 2296 进程号

stat uptime 4237 持续运行时间

stat time 1370054990

stat version 1.2.6

stat pointer_size 32

stat curr_items 4 当前存储的键个数

stat total_items 13

stat bytes 236

stat curr_connections 3

stat total_connections 4

stat connection_structures 4

stat cmd_get 20

stat cmd_set 16

stat get_hits 13

stat get_misses 7 // 这 2 个参数 可以算出命中率

stat evictions 0

stat bytes_read 764

stat bytes_written 618

stat limit_maxbytes 67108864

stat threads 1



缓存有一个重要的概念: 命中率.

命中率是指: (查询到数据的次数/查询总数)*100%

如上, 13/(13+7) = 60+% , 的命中率.



flush_all 清空所有的存储对象









-p 监听的端口

-l 连接的IP地址, 默认是本机

-d start 启动memcached服务

-d restart 重起memcached服务

-d stop|shutdown 关闭正在运行的memcached服务

-d install 安装memcached服务

-d uninstall 卸载memcached服务

-u 以的身份运行 (仅在以root运行的时候有效)

-m 最大内存使用，单位MB。默认64MB

-M 内存耗尽时返回错误，而不是删除项

-c 最大同时连接数，默认是1024

-f 块大小增长因子，默认是1.25

-n 最小分配空间，key+value+flags默认是48

-h 显示帮助

启动命令： memcached -d -m 10 -u root -l 115.28.132.84 -p 11211 -c 256 -P /tmp/memcached.pid





```javascript
stats  
STAT pid 1532 //进程id  
STAT uptime 348167 //服务运行秒数  
STAT time 1372215144 //当前unix时间戳  
STAT version 1.4.14 //服务器版本  
STAT libevent 2.0.10-stable   
STAT pointer_size 32 //操作系统字大小  
STAT rusage_user 3.997392 //进程累计用户时间  
STAT rusage_system 2.258656 //进程累计系统时间  
STAT curr_connections 5 //当前打开连接数  
STAT total_connections 265 //链接总数  
STAT connection_structures 7 //服务器分配的链接结构数  
STAT reserved_fds 20 //  
STAT cmd_get 1911 //执行get命令次数  
STAT cmd_set 195  //执行set命令次数  
STAT cmd_flush 3 //执行flush命令次数  
STAT cmd_touch 0  
STAT get_hits 1708 //get命中次数  
STAT get_misses 203 //get未命中次数  
STAT delete_misses 11 //delete 未命中次数  
STAT delete_hits 14 //delete命中次数  
STAT incr_misses 0  //incr 自增命令 未命中次数  
STAT incr_hits 0    //incr 命中次数  
STAT decr_misses 0  //decr  自减 未命中次数  
STAT decr_hits 0    //decr 命中次数  
STAT cas_misses 0   //cas 未命中次数  
STAT cas_hits 2     //case  命中次数  
STAT cas_badval 1   //使用擦拭次数  
STAT touch_hits 0  
STAT touch_misses 0  
STAT auth_cmds 0  
STAT auth_errors 0  
STAT bytes_read 164108   //读取字节数  
STAT bytes_written 1520916 //写入字节书  
STAT limit_maxbytes 67108864 //分配的内存数  
STAT accepting_conns 1 //目前接受的连接数  
STAT listen_disabled_num 0  
STAT threads 4 //线程数  
STAT conn_yields 0  
STAT hash_power_level 16  
STAT hash_bytes 262144  
STAT hash_is_expanding 0  
STAT expired_unfetched 4  
STAT evicted_unfetched 0  
STAT bytes 23995  //存储字节数  
STAT curr_items 31 //item个数  
STAT total_items 189 //item总数  
STAT evictions 0 //为获取空间删除的item个数  
STAT reclaimed 17  
END 
```



