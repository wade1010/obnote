分配权限没搞懂



1.查看是否安装vsftp

 rpm -qa | grep vsftpd

 如果出现vsftpd-2.0.5-21.el5，说明已经安装 vsftp

 

 安装vsftp

 yum -y install vsftpd

 

 2.测试 是否安装成功 （ip 改成自己啊，不要用俺的此次登录为匿名登录 user: anonymous 密码为空 如果成功登录会有下面内容 这说明vsftpd安装成功）

 [root@localhost ~]#service vsftpd start

 为 vsftpd 启动 vsftpd：[确定]

 

 3,配置vsftpd

 # whereis vsftpd

 vsftpd: /usr/sbin/vsftpd /etc/vsftpd /usr/share/man/man8/vsftpd.8.gz

 yum安装的主要目录为上述的3个目录，其中配置文件vsftpd.conf在/etc/vsftpd中，下面看下怎么配置vsftpd.conf



4。建立用户和指定目录

#useradd -d /home/ftpdata3 ftp3

#passwd ftp3

建立用户ftp3，并指定其ftp目录为ftpdata3

 

#useradd -d /home/ftpdata3 ftp4

#passwd ftp4

建立用户ftp4，并指定其ftp目录为ftpdata4

 

 

这样ftp3和ftp4用户就被指定到相应的文件夹下。

 

5。修改vsftpd.conf配置文件

将anonymous_enable改为NO，阻止匿名上传

将chroot_list_enable和chroot_list_file的注释去掉，阻止用户访问上级目录

 

6。在/etc/vsftpd下建立chroot_list文件

建立完成后，在其中添加用户ftp3,ftp4，使其只允许访问指定目录。

 

六。启动或是重启ftp服务。

#service vsftpd restart(start)

 

启动成功后，就可以访问ftp服务了。

 

注：配置文件中的解释

anonymous_enable=YES开启匿名用户登录 

local_enable=YES开启本地用户登录 

write_enable=YES开启写权限以便上传 

local_umask=022设置上传后文件为user=rwx, group=, other= 

这样，用户上传文件后，是不能删除和修改了。因为用户属于group组。 

解决方法是，设置local_umask=002。 

最终文件权限是777-文件夹掩码-local_umask掩码 

anon_upload_enable=YES开启匿名用户上传权限 

统一匿名上传用户上传的文件的属性 

chown_uploads=YES 

chown_username=ftp 

设定chroot配置，禁止特定用户访问上一级目录 

chroot_list_enable=YES 

chroot_list_file=/etc/vsftpd/chroot_list 

userlist_enable=YES这个选项如果是YES，那/etc/vsftpd/user_list中的用户将被禁止访问ftp。如果是NO，则只有user_list里面的用户才能访问ftp 



