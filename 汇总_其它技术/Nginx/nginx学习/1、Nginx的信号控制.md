

| TERM, INT | Quick shutdown    轻易不要这样用 |
| - | - |
| QUIT | Graceful shutdown  优雅的关闭进程,即等请求结束后再关闭 |
| HUP | Configuration reload ,Start the new worker processes with<br> a new configuration Gracefully shutdown the old worker processes<br>改变配置文件,平滑的重读配置文件 |
| USR1 | Reopen the log files 重读日志,在日志按月/日分割时有用 |
| USR2 | Upgrade Executable on the fly 平滑的升级 |
| WINCH | Gracefully shutdown the worker processes 优雅关闭旧的进程(配合USR2来进行升级) |






```javascript
➜  nginx ps aux|grep nginx
root       9389  0.0  0.0  20552   432 ?        Ss   16:45   0:00 nginx: master process ./sbin/nginx
nobody     9391  0.0  0.1  23008  1016 ?        S    16:45   0:00 nginx: worker process
➜  nginx kill -HUP 9389
➜  nginx ps aux|grep nginx
root       9389  0.0  0.1  20684  1316 ?        Ss   16:45   0:00 nginx: master process ./sbin/nginx
nobody     9641  0.0  0.1  23148  1560 ?        S    16:58   0:00 nginx: worker process
➜  nginx ps aux|grep nginx
root       9389  0.0  0.1  20684  1316 ?        Ss   16:45   0:00 nginx: master process ./sbin/nginx
nobody     9641  0.0  0.1  23148  1560 ?        S    16:58   0:00 nginx: worker process

```



master不变 worker 新开了一个





具体语法:

Kill -信号选项 nginx的主进程号

Kill -HUP 4873

 

Kill -信号控制 `cat /xxx/path/log/nginx.pid`

 

Kil; -USR1 `cat /xxx/path/log/nginx.pid`     nginx.conf里面配置的路径