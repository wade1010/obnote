运行时更改master-slave

修改一台slave(假设设为A)为new master 

1)命令该服务不做其他redis服务的slave 
   
   命令: slaveof no one 

2)修改其slave-read-only为yes

其他的slave再指向new master A

1)命令该服务为new master A的slave
   
   命令格式 slaveof IP port