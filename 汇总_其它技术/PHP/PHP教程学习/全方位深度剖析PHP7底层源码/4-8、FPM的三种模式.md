1.static模式

static模式始终会保持一个固定数量的子进程，这个数量由pm.max_children定义。

 

2.dynamic模式

子进程的数量是动态变化的，启动时，会生成固定数量的子进程，可以理解成最小子进程数，通过pm.start_servers控制，而最大子进程数则由pm.max_children控制，子进程数会在pm.start_servers~pm.max_children范围内波动，另外，闲置的子进程数还可以由pm.min_spare_servers和pm.max_spare_servers两个配置参数控制。换句话说，闲置的子进程也可以由最小数目和最大数目，而如果闲置的子进程超过pm.max_spare_servers，则会被杀掉。

 

3.ondemand模式

这种模式和dynamic模式相反，把内存放在第一位，每个闲置进程在持续闲置了pm.process_idle_timeout秒后就会被杀掉。有了这个模式，到了服务器低峰期，内存自然会降下来，如果服务器长时间没有请求，就只有一个主进程，当然其弊端是，遇到高峰期或者pm.process_idle_timeout设置太小，无法避免服务器频繁创建进程的问题。

