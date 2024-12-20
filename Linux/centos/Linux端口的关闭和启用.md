Linux下 面貌似没有什么直接开启或者关闭端口的命令，因为若仅仅只是开启了端口而不把它与进程相联系的话，端口的开启与关闭就显得毫无意义了（开了端口却没有程序 处理进来的数据）。也就是说，Linux里面端口的活动与进程是紧密相连的，如果想要关闭某个端口，那么只要杀掉它对应的进程就可以了。

 

 

 

例如要关闭22号端口：

 

 

 

$ netstat -anp | grep :22

tcp   0    0 0.0.0.0:22      0.0.0.0:*     LISTEN     1666/sshd

 

 

# -a 显示所有活动的TCP连接，以及正在监听的TCP和UDP端口

 

# -n 以数字形式表示地址和端口号，不试图去解析其名称（number）

 

# -p 列出与端口监听或连接相关的进程（有个地方需要注意，下面会提到）（pid）

 

 

 

知道了22号端口对应的进程ID 1666，只要：

 

$ kill 1666

即可。

 

 

 

其中“-p”选项需要注意一个权限的问题，如果在普通用户登录的shell里面执行netstat命令，那么只能列出拥有该普通用户权限的相关进程，如果想要看到所有的端口情况，最好还是切到root。

 

 

 

附带几个netstat常用选项用法：

 

 

 

$ netstat -tn    # 列出所有TCP协议的连接状态

 

 

# -t 只显示与TCP协议相关的连接和端口监听状态，注意和-a有区别（tcp）

 

 

 

$ netstat -tuln    # 列出所有inet地址类的端口监听状态