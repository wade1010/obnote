Linux清除用户登录记录和命令历史方法

清除登陆系统成功的记录

[root@localhost root]# echo > /var/log/wtmp //此文件默认打开时乱码，可查到ip等信息

[root@localhost root]# last //此时即查不到用户登录信息



清除登陆系统失败的记录

[root@localhost root]# echo > /var/log/btmp 

清除登陆系统成功的记录

[root@localhost root]# echo > /var/log/wtmp //此文件默认打开时乱码，可查到ip等信息

[root@localhost root]# last //此时即查不到用户登录信息



清除登陆系统失败的记录

[root@localhost root]# echo > /var/log/btmp //此文件默认打开时乱码，可查到登陆失败信息

[root@localhost root]# lastb //查不到登陆失败信息



清除历史执行命令

[root@localhost root]# history -c //清空历史执行命令

[root@localhost root]# echo > ./.bash_history //或清空用户目录下的这个文件即可



导入空历史记录

[root@localhost root]# vi /root/history //新建记录文件

[root@localhost root]# history -c //清除记录 

[root@localhost root]# history -r /root/history.txt //导入记录 

[root@localhost root]# history //查询导入结果



example 

[root@localhost root]# vi /root/history

[root@localhost root]# history -c 

[root@localhost root]# history -r /root/history.txt 

[root@localhost root]# history 

[root@localhost root]# echo > /var/log/wtmp  

[root@localhost root]# last

[root@localhost root]# echo > /var/log/btmp

[root@localhost root]# lastb 

[root@localhost root]# history -c 

[root@localhost root]# echo > ./.bash_history

[root@localhost root]# history

展开全文