写一个脚本auto_coreseek.sh：



#!/bin/sh

#chkconfig:2345 80 90

#description:auto_coreseek

sh /opt/cv_parser/start_tika.sh

sh /opt/cv_parser/cn/start_docker_server.sh

sh /opt/cv_parser/en/start_docker_server.sh





放到/etc/init.d/auto_coreseek.sh

然后chmod +x /etc/init.d/auto_coreseek.sh

 

然后加到开机启动中：

chkconfig --add auto_coreseek.sh

 

完事。

 

然后研究下这都是些什么意思。

chkconfig有几个等级：

0：表示关机

1：表示单用户模式

2：表示无网络链接多用户命令行模式

3：表示有网络链接多用户命令行模式

4：表示不可用情况

5：表示带图形界面的多用户模式

6：表示重新启动

 

所以chkconfig:2345就代表在2345的等级下启动这个服务

后面的80 90分别是启动优先级和关闭优先级

 

说说程序的优先级，优先级也很好理解，就是程序被CPU执行的先后顺序，此值越小有限级别越高。所以这里的启动优先级和关闭优先级的意思就是启动脚本这个进程的优先级，和关闭脚本这个进程的优先级。