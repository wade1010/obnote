当发现linux 里有中文命名文件，乱码文件想删除时，请使用inode 删除

[root@localhost tmp]# ls -l

total 1024

-r--r--r-- 1 root  root  624541 May  3 09:33 mkisofs-2.01-10.7.el5.x86_64.rpm

srwxrwxrwx 1 mysql mysql      0 Jul  2 12:26 mysql.sock

--rw-r--r-- 1 root  root       0 Jul 27 08:27 乱码file

-rw-r--r-- 1 root  root       0 Jul 27 08:26 嘎儿

我的系统里有2个中文命名的文件，查找2个文件的inode

[root@localhost tmp]#    

total 1024

917852 -r--r--r-- 1 root  root  624541 May  3 09:33 mkisofs-2.01-10.7.el5.x86_64.rpm

919797 srwxrwxrwx 1 mysql mysql      0 Jul  2 12:26 mysql.sock

920533 -rw-r--r-- 1 root  root       0 Jul 27 08:27 乱码file

918587 -rw-r--r-- 1 root  root       0 Jul 27 08:26 嘎儿

乱码文件的 inode 为920533  我们就来先删除它，用find 命令来找到这个inode 接着删除

[root@localhost tmp]# find . -inum 920533

./乱码file

[root@localhost tmp]# find   .   -inum  920533  -exec  rm   -f   {}  \;

[root@localhost tmp]# ls -l

total 1024

-r--r--r-- 1 root  root  624541 May  3 09:33 mkisofs-2.01-10.7.el5.x86_64.rpm

srwxrwxrwx 1 mysql mysql      0 Jul  2 12:26 mysql.sock

-rw-r--r-- 1 root  root       0 Jul 27 08:26 嘎儿

名为乱码file 的文件已经删除了

