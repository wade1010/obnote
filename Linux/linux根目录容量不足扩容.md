1. 首先，必须确保其他分区有足够的空间用来分给根目录/。可以使用以下命令查看：

$  df -lh



```javascript
[root@localhost ~]#df -lh
文件系统                 容量  已用  可用 已用% 挂载点
/dev/mapper/centos-root   50G   48G  2.6G   95% /
devtmpfs                 7.8G     0  7.8G    0% /dev
tmpfs                    7.8G  8.0K  7.8G    1% /dev/shm
tmpfs                    7.8G   42M  7.8G    1% /run
tmpfs                    7.8G     0  7.8G    0% /sys/fs/cgroup
/dev/sda1               1014M  142M  873M   14% /boot
/dev/mapper/centos-home  441G   33M  441G    1% /home
tmpfs                    1.6G     0  1.6G    0% /run/user/0
tmpfs                    1.6G     0  1.6G    0% /run/user/1005
```



可以看到，这里home目录空闲的空间还很大，因此，我们将home的空间分给根目录一些。



2. 扩容根目录的思路如下：



将/home文件夹备份，删除/home文件系统所在的逻辑卷，增大/文件系统所在的逻辑卷，增大/文件系统大小，最后新建/home目录，并恢复/home文件夹下的内容。



3. 备份/home分区内容



这里需要选一个能够容纳下/home文件夹大小的分区，可以看到/run剩余空间为32G，因此，我们将/home备份到/run下面。



$  tar cvf /run/home.tar /home



4. 卸载/home



$ umount /home

5. 删除/home所在的逻辑卷lv：



$ lvremove /dev/mapper/centos-home

选择y。



6. 扩大根目录所在的逻辑卷，这里增大1T：



$ lvextend -L +200G /dev/mapper/centos-root

7.  扩大/文件系统：



$ xfs_growfs /dev/mapper/centos-root

8. 重建/home文件系统所需要的逻辑卷：



由于刚才我们分出去200G，因此这里创建的逻辑卷大小为240G



$ lvcreate -L 240G -n/dev/mapper/centos-home

9.创建文件系统：



$ mkfs.xfs  /dev/mapper/centos-home

10. 将新建的文件系统挂载到/home目录下：



$ mount /dev/mapper/centos-home

11. 恢复/home目录的内容：



$ tar xvf /run/home.tar -C /

12. 删除/run下面的备份：

$ rm -rf /run/home.tar



```javascript
[root@localhost /]#df -lh
文件系统                 容量  已用  可用 已用% 挂载点
/dev/mapper/centos-root  250G   48G  203G   20% /
devtmpfs                 7.8G     0  7.8G    0% /dev
tmpfs                    7.8G  8.0K  7.8G    1% /dev/shm
tmpfs                    7.8G   42M  7.8G    1% /run
tmpfs                    7.8G     0  7.8G    0% /sys/fs/cgroup
/dev/sda1               1014M  142M  873M   14% /boot
tmpfs                    1.6G     0  1.6G    0% /run/user/0
tmpfs                    1.6G     0  1.6G    0% /run/user/1005
/dev/mapper/centos-home  240G   33M  240G    1% /home
```

