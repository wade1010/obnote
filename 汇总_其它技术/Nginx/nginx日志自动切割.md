#### 原因
nginx的日志文件没有rotate功能。如果你不处理，日志文件将变得越来越大
#### 解决
用shell写一个日志分隔脚本，再用crontab每天0点整执行。

- 移动日志到指定的备份目录，不用担心移动后nginx找不到日志文件。在你未重新打开原名字的日志文件前，nginx还是会向你移动的目标文件写日志，linux是靠文件描述符而不是文件名定位文件。
- 向nginx主进程发送USR1信号。nginx主进程接到信号后会从配置文件中读取日志文件名称，重新打开日志文件(以配置文件中的日志名称命名)，并以工作进程的用户作为日志文件的所有者。
重新打开日志文件后，nginx主进程会关闭重名的日志文件并通知工作进程使用新打开的日志文件。
工作进程立刻打开新的日志文件并关闭重名名的日志文件。
然后你就可以处理旧的日志文件了。

#### 具体实现

>vim  /usr/local/nginx/shell/cut_ngnix_log.sh  添加如下代码

```
#!/bin/bash
year=`date +%Y`
month=`date +%m`
day=`date +%d`
logs_backup_path="/usr/local/nginx/logs_backup/$year$month"               #日志存储路径

logs_path="/usr/local/nginx/logs/"                                                             #要切割的日志路径
logs_access="access"                                                                            #要切割的日志
logs_error="error"
pid_path="/usr/local/nginx/logs/nginx.pid"                                                 #nginx的pid

[ -d $logs_backup_path ]||mkdir -p $logs_backup_path
log_date=`date +%Y%m%d`
mv ${logs_path}${logs_access}.log ${logs_backup_path}/${logs_access}_${log_date}.log
mv ${logs_path}${logs_error}.log ${logs_backup_path}/${logs_error}_${log_date}.log
#u can add tar 
kill -USR1 $(cat /usr/local/nginx/logs/nginx.pid)
```

#### 添加crontab
0 0 * * * bash /usr/local/nginx/shell/cut_ngnix_log.sh   #每天00：00分开始执行