Redis的持久化有2种方式   1快照  2是日志



 默认情况下，RDB开启，AOF关闭。



Rdb快照的配置选项

 

save 900 1      // 900内,有1条写入,则产生快照

save 300 1000   // 如果300秒内有1000次写入,则产生快照

save 60 10000  // 如果60秒内有10000次写入,则产生快照

(这3个选项都屏蔽,则rdb禁用)

 

stop-writes-on-bgsave-error yes  // 后台备份进程出错时,主进程停不停止写入?

rdbcompression yes    // 导出的rdb文件是否压缩

Rdbchecksum   yes //  导入rbd恢复时数据时,要不要检验rdb的完整性

dbfilename dump.rdb  //导出来的rdb文件名

dir ./  //rdb的放置路径

 

 

 

Aof 的配置

appendonly no # 是否打开 aof日志功能

 

appendfsync always   # 每1个命令,都立即同步到aof. 安全,速度慢

appendfsync everysec # 折衷方案,每秒写1次

appendfsync no      # 写入工作交给操作系统,由操作系统判断缓冲区大小,统一写入到aof. 同步频率低,速度快,

 

 

no-appendfsync-on-rewrite  yes: # 正在导出rdb快照的过程中,要不要停止同步aof

auto-aof-rewrite-percentage 100 #aof文件大小比起上次重写时的大小,增长率100%时,重写

auto-aof-rewrite-min-size 64mb #aof文件,至少超过64M时,重写

 

 



注: 在dump rdb过程中,aof如果停止同步,会不会丢失?

答: 不会,所有的操作缓存在内存的队列里, dump完成后,统一操作.

 

注: aof重写是指什么?

答: aof重写是指把内存中的数据,逆化成命令,写入到.aof日志里.以解决 aof日志过大的问题.

 

问: 如果rdb文件,和aof文件都存在,优先用谁来恢复数据?

答: 在这种情况下,当redis重启的时候会优先载入AOF文件来恢复原始的数据,因为在通常情况下AOF文件保存的数据集要比RDB文件完整

 

问: 2种是否可以同时用?

答: 可以,而且推荐这么做

 

问: 恢复时rdb和aof哪个恢复的快

答: rdb快,因为其是数据的内存映射,直接载入到内存,而aof是命令,需要逐条执行



redis 服务器端命令

redis 127.0.0.1:6380> time  ,显示服务器时间 , 时间戳(秒), 微秒数

1) "1375270361"

2) "504511"

 

redis 127.0.0.1:6380> dbsize  // 当前数据库的key的数量

(integer) 2

redis 127.0.0.1:6380> select 2

OK

redis 127.0.0.1:6380[2]> dbsize

(integer) 0

redis 127.0.0.1:6380[2]>

 

 

BGREWRITEAOF 后台进程重写AOF

BGSAVE       后台保存rdb快照

SAVE         保存rdb快照

LASTSAVE     上次保存时间

 

Slaveof master-Host port  , 把当前实例设为master的slave

 

Flushall  清空所有库所有键

Flushdb  清空当前库所有键

Showdown [save/nosave]

 

注: 如果不小心运行了flushall, 立即 shutdown nosave ,关闭服务器

然后 手工编辑aof文件, 去掉文件中的 “flushall ”相关行, 然后开启服务器,就可以导入回原来数据.

 

如果,flushall之后,系统恰好bgrewriteaof了,那么aof就清空了,数据丢失.

 

Slowlog 显示慢查询

注:多慢才叫慢?

答: 由slowlog-log-slower-than 10000 ,来指定,(单位是微秒)

 

服务器储存多少条慢查询的记录?

答: 由 slowlog-max-len 128 ,来做限制

 

Info [Replication/CPU/Memory..]

查看redis服务器的信息

 

Config get 配置项  

Config set 配置项 值 (特殊的选项,不允许用此命令设置,如slave-of, 需要用单独的slaveof命令来设置)