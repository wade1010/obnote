save 900 1    #刷新快照到硬盘中，必须满足两者要求才会触发，即900秒之后至少1个关键字发生变化。

save 300 10  #必须是300秒之后至少10个关键字发生变化。

save 60 10000 #必须是60秒之后至少10000个关键字发生变化。

stop-writes-on-bgsave-error yes    #后台存储错误停止写。

rdbcompression yes    #使用LZF压缩rdb文件。

rdbchecksum yes    #存储和加载rdb文件时校验。

dbfilename dump.rdb    #设置rdb文件名。

dir ./    #设置工作目录，rdb文件会写入该目录。

