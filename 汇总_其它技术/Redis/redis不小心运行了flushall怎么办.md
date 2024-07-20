如果不小心运行了flushall, 立即 shutdown nosave ,关闭服务器

然后 手工编辑aof文件, 去掉文件中的 “flushall ”相关行, 然后开启服务器,就可以导入回原来数据.

 

如果,flushall之后,系统恰好bgrewriteaof了,那么aof就清空了,数据丢失.