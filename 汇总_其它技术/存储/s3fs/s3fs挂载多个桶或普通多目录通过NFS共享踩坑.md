s3fs挂载单个桶，然后通过NFS共享出来，网上帖子很多，挺简单的。

之前测试过s3fs共享多个桶，然后把父目录通过samba和ftp共享出来，这个都没问题，但是换成nfs的时候遇到了一些坑。

今天踩下s3fs挂载多个桶通过NFS共享的坑

准备活动：

1 服务端和客户端两台机器（一台应该也是可以的，共享和挂载都在同一个机器上）

2 s3对象存储有两个桶，分别为 bk1和bk2，每个桶里面分别传点文件

3 安装NFS S3FS

踩坑记录：

服务端10.0.11.34：

共享该服务器上面的两个普通目录tbk1和tbk2

vim /etc/exports

```
/mnts3fs/nfs/tbk1 *(fsid=0,rw,no_root_squash,no_all_squash,sync,insecure)
/mnts3fs/nfs/tbk2 *(fsid=0,rw,no_root_squash,no_all_squash,sync,insecure)
```

exportfs -rv

客户端10.0.11.33：

mount 10.0.11.34:/mnts3fs/nfs/tbk1 /mnt/nfs/bk1

挂载后df

```
[root@centos7 nfs]# df
文件系统                            1K-块     已用      可用 已用% 挂载点
devtmpfs                           929340        0    929340    0% /dev
tmpfs                              940944        0    940944    0% /dev/shm
tmpfs                              940944    16876    924068    2% /run
tmpfs                              940944        0    940944    0% /sys/fs/cgroup
/dev/mapper/centos_centos7-root 131042304  1769424 129272880    2% /
/dev/sda1                         1038336   133288    905048   13% /boot
tmpfs                              188192        0    188192    0% /run/user/1000
10.0.11.34:/mnts3fs/nfs/tbk1    208561152 52526080 156035072   26% /mnt/nfs/bk1
```

挂载第二个普通目录

mount 10.0.11.34:/mnts3fs/nfs/tbk2 /mnt/nfs/bk2

再df

```
[root@centos7 nfs]# df
文件系统                            1K-块     已用      可用 已用% 挂载点
devtmpfs                           929340        0    929340    0% /dev
tmpfs                              940944        0    940944    0% /dev/shm
tmpfs                              940944    16876    924068    2% /run
tmpfs                              940944        0    940944    0% /sys/fs/cgroup
/dev/mapper/centos_centos7-root 131042304  1769424 129272880    2% /
/dev/sda1                         1038336   133288    905048   13% /boot
tmpfs                              188192        0    188192    0% /run/user/1000
10.0.11.34:/mnts3fs/nfs/tbk1    208561152 52571136 155990016   26% /mnt/nfs/bk1
```

发现还是一个挂载点

查看挂载点的内容：

```
[root@centos7 nfs]# ll /mnt/nfs/bk1
总用量 4
-rwxrwxrwx 1 root root 2 11月 14 16:27 1.txt
-rw-r--r-- 1 root root 0 11月 14 16:38 2.txt
[root@centos7 nfs]# ll /mnt/nfs/bk2
总用量 4
-rwxrwxrwx 1 root root 2 11月 14 16:27 1.txt
-rw-r--r-- 1 root root 0 11月 14 16:38 2.txt
```

发现都是bk1的内容

umount /mnt/nfs/bk1

```
[root@centos7 nfs]# ll /mnt/nfs/bk2
总用量 4
-rwxrwxrwx 1 root root 2 11月 14 16:27 1.txt
-rw-r--r-- 1 root root 0 11月 14 16:38 2.txt
```

umount /mnt/nfs/bk2

全部卸载后，换个顺序挂载，先挂载bk2再挂载bk1

mount 10.0.11.34:/mnts3fs/nfs/tbk2 /mnt/nfs/bk2

mount 10.0.11.34:/mnts3fs/nfs/tbk1 /mnt/nfs/bk1

```
[root@centos7 nfs]# mount 10.0.11.34:/mnts3fs/nfs/tbk2 /mnt/nfs/bk2
[root@centos7 nfs]# df
文件系统                            1K-块     已用      可用 已用% 挂载点
devtmpfs                           929340        0    929340    0% /dev
tmpfs                              940944        0    940944    0% /dev/shm
tmpfs                              940944    16876    924068    2% /run
tmpfs                              940944        0    940944    0% /sys/fs/cgroup
/dev/mapper/centos_centos7-root 131042304  1769424 129272880    2% /
/dev/sda1                         1038336   133288    905048   13% /boot
tmpfs                              188192        0    188192    0% /run/user/1000
10.0.11.34:/mnts3fs/nfs/tbk2    208561152 52316160 156244992   26% /mnt/nfs/bk2
[root@centos7 nfs]# mount 10.0.11.34:/mnts3fs/nfs/tbk1 /mnt/nfs/bk1
[root@centos7 nfs]# df
文件系统                            1K-块     已用      可用 已用% 挂载点
devtmpfs                           929340        0    929340    0% /dev
tmpfs                              940944        0    940944    0% /dev/shm
tmpfs                              940944    16876    924068    2% /run
tmpfs                              940944        0    940944    0% /sys/fs/cgroup
/dev/mapper/centos_centos7-root 131042304  1769424 129272880    2% /
/dev/sda1                         1038336   133288    905048   13% /boot
tmpfs                              188192        0    188192    0% /run/user/1000
10.0.11.34:/mnts3fs/nfs/tbk2    208561152 52304896 156256256   26% /mnt/nfs/bk2
```

发现还是一样

```
[root@centos7 nfs]# ll /mnt/nfs/bk2
总用量 4
-rwxrwxrwx 1 root root 2 11月 14 16:27 1.txt
-rw-r--r-- 1 root root 0 11月 14 16:38 2.txt
[root@centos7 nfs]# ll /mnt/nfs/bk1
总用量 4
-rwxrwxrwx 1 root root 2 11月 14 16:27 1.txt
-rw-r--r-- 1 root root 0 11月 14 16:38 2.txt
```

单独mount bk2  发现也是bk1的内容，因此感觉还是服务端配置问题。

```
[root@centos7 nfs]# mount 10.0.11.34:/mnts3fs/nfs/tbk2 /mnt/nfs/bk2
[root@centos7 nfs]# ll /mnt/nfs/bk2
总用量 4
-rwxrwxrwx 1 root root 2 11月 14 16:27 1.txt
-rw-r--r-- 1 root root 0 11月 14 16:38 2.txt
```

后来查看nfs日志

找到如下报错：(我是通过 cat /var/log/messages|grep nfs 查看的)

/mnts3fs/**nfs**/tbk1 and /mnts3fs/**nfs**/tbk2 have same filehandle for *, using first

所以确定了是服务端配置没配对。

s3fs nfs 的踩坑2 共享s3fs挂载过的目录bk1和bk2

s3fs挂载就不说了，大概看下进程把

```
[root@N02 ~]# ps aux|grep s3fs
root       659  0.0  0.0 112824   980 pts/0    R+   13:44   0:00 grep --color=auto s3fs
root     32131  0.0  0.0 314988  6044 pts/3    Sl   13:34   0:00 s3fs bk1 /mnts3fs/nfs/bk1 -o use_cache=/tmp/s3fs_nfs_cache/bk1 -o allow_other,umask=0,mp_umask=0,url=http://10.0.11.33:19002,passwd_file=/etc/s3fs-passwd-nfs,dbglevel=info -f -o curldbg -o use_path_request_style
root     32132  0.0  0.0 314852  6320 pts/3    Sl   13:34   0:00 s3fs bk2 /mnts3fs/nfs/bk2 -o use_cache=/tmp/s3fs_nfs_cache/bk2 -o allow_other,umask=0,mp_umask=0,url=http://10.0.11.33:19002,passwd_file=/etc/s3fs-passwd-nfs,dbglevel=info -f -o curldbg -o use_path_request_style
```

```
[root@N02 ~]# ll /mnts3fs/nfs/
总用量 1
drwxrwxrwx 1 root root  0 1月   1 1970 bk1
drwxrwxrwx 1 root root  0 1月   1 1970 bk2
drwxrwxrwx 2 root root 32 11月 14 16:38 tbk1
drwxrwxrwx 2 root root 34 11月 15 10:51 tbk2
```

上面bk1和bk2是 s3fs的挂载点，也就是把对象存储的bk1桶挂载到目录/mnts3fs/nfs/bk1上，bk2桶挂载到目录/mnts3fs/nfs/bk2上

/mnts3fs/nfs/tbk1和/mnts3fs/nfs/tbk2是普通的目录。

下面开始测试

服务端：

vim /etc/exports

```
/mnts3fs/nfs *(fsid=0,rw,no_root_squash,no_all_squash,sync,insecure)
```

尝试挂载父目录

exportfs -rv

然后到客户端挂载测试

客户端：

先挂载根目录到客户端的/mnt2目录

mount 10.0.11.34:/mnts3fs/nfs /mnt2

```
[root@centos7 nfs]# ll /mnt2/bk1
总用量 0
[root@centos7 nfs]# ll /mnt2/bk2
总用量 0
[root@centos7 nfs]# ll /mnt2/tbk1
总用量 4
-rwxrwxrwx 1 root root 2 11月 14 16:27 1.txt
-rw-r--r-- 1 root root 0 11月 14 16:38 2.txt
[root@centos7 nfs]# ll /mnt2/tbk2
总用量 4
-rwxrwxrwx 1 root root 2 11月 14 16:27 1.txt
-rw-r--r-- 1 root root 0 11月 15 10:51 bk2.txt
```

从上面可以看到bk1和bk2也被识别为普通目录，没有将s3fs挂载的内容共享出来，samba和ftp测试都是可以的，应该还是底层原理不同，NFS走的是自己的RPC，详细的也没了解。

挂载服务端的bk1

```
mount 10.0.11.34:/mnts3fs/nfs/bk1 /mnt/nfs/bk1
mount.nfs: mounting 10.0.11.34:/mnts3fs/nfs/bk1 failed, reason given by server: No such file or directory
```

修改 服务端nfs配置

vim /etc/exports

去掉fsid=0,

```
/mnts3fs/nfs *(rw,no_root_squash,no_all_squash,sync,insecure)
```

exportfs -rv

mount 10.0.11.34:/mnts3fs/nfs/bk1 /mnt/nfs/bk1

发现可以mount了，但是bk1还是被识别为普通目录。

后来查阅很多外国的帖子加上仔细查看了exports的使用

NFS won't let you share parent and sub directories separately. You need to share the parent directory and check the "All directories" box to mount a directory under the parent.

终端执行：

man exports

crossmnt

              This option is similar to nohide but it makes it possible for clients to access all filesystems mounted

              on  a  filesystem  marked  with crossmnt.  Thus when a child filesystem "B" is mounted on a parent "A",

              setting crossmnt on "A" has a similar effect to setting "nohide" on B.

              With nohide the child filesystem needs to be explicitly exported.  With crossmnt it  need  not.   If  a

              child  of a crossmnt file is not explicitly exported, then it will be implicitly exported with the same

              export options as the parent, except for fsid=.  This makes it impossible to **not** export a  child  of  a

              crossmnt  filesystem.  If some but not all subordinate filesystems of a parent are to be exported, then

              they must be explicitly exported and the parent should not have crossmnt set.

              The nocrossmnt option can explictly disable crossmnt if it was previously set.  This is rarely useful.

fsid=num|root|uuid

              NFS  needs to be able to identify each filesystem that it exports.  Normally it will use a UUID for the

              filesystem (if the filesystem has such a thing) or the device number of the device holding the filesys‐

              tem (if the filesystem is stored on the device).

              As  not all filesystems are stored on devices, and not all filesystems have UUIDs, it is sometimes nec‐

              essary to explicitly tell NFS how to identify a filesystem.  This is done with the fsid= option.

              For NFSv4, there is a distinguished filesystem which is the root of all exported filesystem.   This  is

              specified with fsid=root or fsid=0 both of which mean exactly the same thing.

              Other  filesystems can be identified with a small integer, or a UUID which should contain 32 hex digits

              and arbitrary punctuation.

              Linux kernels version 2.6.20 and earlier do not understand the UUID setting so a small integer must  be

              used  if  an  fsid  option needs to be set for such kernels.  Setting both a small number and a UUID is

              supported so the same configuration can be made to work on old and new kernels alike

最终琢磨出来如下配置

vim /etc/exports

```
/mnts3fs/nfs    *(crossmnt,rw,sync,no_subtree_check,insecure)
/mnts3fs/nfs/bk1  *(fsid=0,rw,sync,no_subtree_check,insecure)
/mnts3fs/nfs/bk2  *(fsid=1,rw,sync,no_subtree_check,insecure)
```

```
[root@N02 ~]# exportfs -rv
exporting *:/mnts3fs/nfs/bk2
exporting *:/mnts3fs/nfs/bk1
exporting *:/mnts3fs/nfs
```

然后客户端字节挂载/mnt/nfs目录

mount 10.0.11.34:/mnts3fs/nfs/ /mnt2

```
[root@centos7 ~]# ll /mnt2/bk1
总用量 2
-rwxrwxrwx 1 root root 4 11月 14 16:58 1.txt
-rwxrwxrwx 1 root root 6 11月 11 17:23 2.txt
-rwxrwxrwx 1 root root 5 11月 15 12:56 echo.txt
[root@centos7 ~]# ll /mnt2/bk2
总用量 4
-rwxrwxrwx 1 root root 3353 11月 14 16:58 si.py
[root@centos7 ~]# ll /mnt2
总用量 1
drwxrwxrwx 1 root root  0 1月   1 1970 bk1
drwxrwxrwx 1 root root  0 1月   1 1970 bk2
drwxrwxrwx 2 root root 32 11月 14 16:38 tbk1
drwxrwxrwx 2 root root 34 11月 15 10:51 tbk2
```

大功告成

后来发现，这种情况下，要是卸载s3fs挂载的目录，得先修改/etc/exports，去掉里面对应桶的配置，然后exportfs -rv

再umount 就行了