sshpass的安装：

Ubuntu：apt-get  install sshpass



centos:

wget http://sourceforge.net/projects/sshpass/files/sshpass/1.05/sshpass-1.05.tar.gz 

tar xvzf sshpass-1.05.tar.gz 

 cd sshpass-1.05.tar.gz 

 ./configure 

 make 

 make install 





实例1：直接远程连接某台主机：



命令：sshpass -p xxx ssh root@192.168.11.11





实例2：远程连接指定ssh的端口：



命令：sshpass -p 123456 ssh -p 1000 root@192.168.11.11         (当远程主机不是默认的22端口时候)



 

实例3：从密码文件读取文件内容作为密码去远程连接主机



命令：sshpass -f xxx.txt  ssh root@192.168.11.11





实例4：从远程主机上拉取文件到本地



命令： sshpass -p '123456' scp root@host_ip:/home/test/t ./tmp/



