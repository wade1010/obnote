遇到问题多查看日志：

tail -f /usr/local/var/log/apache2/error_log



[Tue Jul 18 11:05:42.631622 2017] [unique_id:alert] [pid 5634] (EAI 8)nodename nor servname provided, or not known: AH01564妈:， unable to find IPv4 address of "bogon"

AH00016: Configuration Failed







解决方法：

修改/etc/hosts，添加一行

127.0.0.1    bogon      localhost