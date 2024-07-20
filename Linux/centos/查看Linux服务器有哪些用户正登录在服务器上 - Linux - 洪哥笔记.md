查看有哪些人登录在服务器上，这是当年刚接触Linux最感兴趣的事情。当年老师在试用的机器上给我们分配了账号，大家都用自己的账号登录……



[root@localhost scsi]# w

16:58:34 up 18:00,  2 users,  load average: 0.00, 0.00, 0.00

USER     TTY      FROM              LOGIN@   IDLE   JCPU   PCPU WHAT

zaho     pts/0    192.168.92.1     03Apr13 11days  0.68s  2.72s sshd: zaho [pri



root     pts/1    192.168.92.1     03Apr13  0.00s  1.01s  0.28s w

[root@localhost scsi]# who

zaho     pts/0        2013-04-03 14:00 (192.168.92.1)

root     pts/1        2013-04-03 14:27 (192.168.92.1)



[root@localhost scsi]# whoami

root



这三个命令很容易懂吧，尤其是最后一个命令，太TMD人性化了！





要是你看不惯某某人，那就把它踢掉吧^_^

pkill -kill -t TTY标识

举例：

pkill -kill -t tty1  (本地TTY登录用户)

pkill -kill -t pst/0 (远程SSH登录用户)