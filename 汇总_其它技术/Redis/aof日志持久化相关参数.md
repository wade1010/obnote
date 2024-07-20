appendonly no #是否仅要日志

appendfsync no # 系统缓冲,统一写,速度快

appendfsync always # 系统不缓冲,直接写,慢,丢失数据少

appendfsync everysec #折衷,每秒写1次  推荐



no-appendfsync-on-rewrite no #重写aof时同步最新数据

auto-AOF-rewrite-percentage 100 //代表当前aof文件空间（aof_current_size）和上一次重写后aof文件空间（aof-base-size）的比值

auto-AOF-rewrite-min-size 64mb    //aof重写至少要达到的大小









aof-use-rdb-preamble yes  开启混合模式     只是用aof或者rdb的时候可以设置为no 





aof触发机制



手动：

直接调用 bgrewriteaof





自动：

根据 auto-aof-rewrite-percentage和auto-aof-rewrite-min-size参数确定自动触发时机









重写：



去掉重复的set 只保留最后一个   



集合操作单个添加变成批量添加



![](https://gitee.com/hxc8/images8/raw/master/img/202407191112956.jpg)

