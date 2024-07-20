FTP服务器的安装与配置(Ubuntu)

1.查询是否安装vsftpd: rpm -qa |grep vsftpd

（rpm的安装：apt-get install rpm）

或者查询当前ftp进程：ps -ef|grep vsftpd

2.安装vsftpd服务器： rpm -ivh vsftpd-*.rpm 或从互联网寻找对应资源直接安装vsftpd：apt-get install vsftpd

++++++++++++++++++++++++++++

可以通过配置yum进行在线安装包.

[root@szmspv1 yum.repos.d]# pwd

/etc/yum.repos.d

[root@szmspv1 yum.repos.d]# more rhel54.repo

[Server]

name=Red Hat Enterprise Linux Server

baseurl=ftp://172.26.0.11/Server64/

enabled=1

gpgcheck=1

gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-redhat-release

+++++++++++++++++++++++++++++++++++++++++++++++++

3.测试

root@localhost root:~# ftp localhost

Connected to localhost.

220 (vsFTPd 2.2.2)

Name (localhost:root): root

331 Please specify the password.

Password:

230 Login successful.

Remote system type is UNIX.

Using binary mode to transfer files.

ftp>bye

221 Goodbye.

4.vsftpd服务器的启动、停止、重启、状态

service vsftpd start 或./etc/init.d/vsftpd start

service vsftpd stop 或./etc/init.d/vsftpd stop

service vsftpd restart 或./etc/init.d/vsftpd restart

service vsftpd status 或./etc/init.d/vsftpd status

5.vsftpd的三个主配置文件

/etc/vsftpd.conf //服务器的主配置文件

/etc/ftpd.ftpusers //此文件内的用户都不能访问vsftpd服务器

/etc/vsftpd.user_list //可能会被拒绝访问服务喊叫或允许访问

6.vsftpd.conf的配置参数

anonymous_enable=YES //启用匿名用户

local_enable=YES //允许本地用户访问vsftpd服务器

write_enable=YES //允许上传

download_enable=YES //允许下载

anon_upload_enable=YES //允许匿名用户上传

anon_mkdir_write_enable=YES //允许匿名用户创建目录和上传

anon_other_write_enable=NO //不允许匿名用户删除和改名

local_max_rate=20000 //本地用户的最大传输速率，单位是字节/秒

anon_max_rate=5000 //匿名用户的最大传输速率，单位是字节/秒

local_umask=022 //去掉写的权限

file_open_mode=0666 //上传文件的权限

xferlog_enable=YES //维护日志文件，详细记录上传和下载操作

xferlog_std_format=YES //传输日志文件将以标准的xferlog格式书写，日志文件默

认为/var/log/xferlog

hide_ids=YES //隐藏文件夹和目录属主

port_enable=YES //允许使用主动传输模式

pasv_min_port=(1024<65535) style='font-size:12px;font-style:normal;font-weight:normal;color:rgb(102, 102, 102);'> pasv_max_port=(1024<65535) style='font-size:12px;font-style:normal;font-weight:normal;color:rgb(102, 102, 102);'> connect_from_port_20=YES //定义FTP传输数据的端口，默认是20

ascii_download_enable=NO //设置不可使用ASCII模式下载

listen=YES //让FTP工作在独立模式下<65535)><65535)>

pam_service_name=vsftpd //用户配置文件认证

userlist_enable=YES

tcp_wrappers=YES //将使用wrappers作为主机访问控制方式

idle_session_timeout=600 //表明空闲时间为600秒

data_connection_timeout=120 //表明数据连接超时时间为120秒

chroot_local_user=YES //用户登录后不能访问自己目录以外的文件或目录

listen_port=4444 //修改FTP服务器的端口号

7.设置FTP服务器在3、5级别上自动运行

chkconfig --level 3 5 vsftpd on

8.ftp客户连接常见故障现象

现象0：

> ftp: connect :连接被拒绝

原因： 服务没启动

解决： # chkconfig vsftpd on

现象1：

500 OOPS: cannot open user list file

原因： 不存在文件“/etc/vsftpd.user_list”或文件中不存在该帐户

解决: # echo username >> /etc/vsftpd.user_list

现象2：

530 Permission denied.

Login failed.

原因： “/etc/vsftpd.user_list”文件中不存在当前登陆用户

解决： # echo username >> /etc/vsftpd.user_list

现象3：

500 OOPS: cannot open chroot() user list file

Login failed.

原因： 不存在文件“/etc/vsftpd.chroot_list”

解决： # echo username >> /etc/vsftpd.chroot_list

现象4：

500 OOPS: missing value in config file

Connection closed by remote host.

原因： “=”等号前值有问题，或只有一个空格

解决： 修正相应的值即可，可能过 diff 来比较查找

现象5：

500 OOPS: bad bool value in config file

Connection closed by remote host.

原因： “=”等号后值有问题

解决: 将“=”等号后值确认修改

现象6:

500 OOPS: unrecognised variable in config file

Connection closed by remote host.

原因： 参数前有空格

解决： 将参数前空格删除

现象7、

确认存在“local_enable=YES”，但本地用户无法登陆

原因： 验证参数被误删除

解决： 添加“pam_service_name=vsftpd”



现象8、

500 OOPS: chdir

500 OOPS: child died

Connection closed by remote host.

原因： 用户主目录没有权限或没有主目录

解决： 正确设置用户主目录权限

9.vsftpd虚拟用户账号的设置步骤

(1).建立虚拟用户口令库文件

vi /pub/vu_list.txt

wang5

123

zhao6

456

(2).生成vsftpd的认证文件

db_load -T -t hash -f /pub/vu_list.txt /etc/vsftpd/vu_list.db

chmod 600 /etc/vsftpd/vu_list.db

(3).建立虚拟用户所需的PAM配置文件

vi /etc/pam.d/vsftpd.vu

auth required /lib/security/pam_userdb.so db=/etc/vsftpd/vu_list

account required /lib/security/pam_userdb.so db=/etc/vsftpd/vu_list

(4).建立虚拟用户所要访问的目录并设置相应权限

useradd ftpuser

(5).设置vsftpd.conf配置文件

guest_username=ftpuser

pam_service_name=vsftpd.vu

(6).重启vsftpd服务器

service vsftpd restart

10.对虚拟用户设置不同权限

(1).设置vsftpd.conf文件

user_config_dir=/etc/vsftpd_vu

(2).创建目录

mkdir /etc/vsftpd_vu

(3).进入目录进行编辑

cd /etc/vsftpd_vu

vi wang5

anon_world_readable_only=NO

anon_upload_enable=YES

anon_mkdir_write_enable=YES

anon_other_write_enable=YES

vi zhao6

anon_world_readable_only=YES

anon_upload_enable=NO

anon_mkdir_write_enable=NO

anon_other_write_enable=NO

10.配置基于IP的虚拟ftp服务器

(1).绑定其它IP

ifconfig eth0:0 192.168.1.71

(2).建立虚拟FTP服务器目录

mkdir -p /var/ftp2/pub1

(3).创建虚拟服务器的匿名用户所映射的本地用户

ftp2

useradd -d /var/ftp2 -M ftp2

(4).修改原独立运行服务器的配置文件

listen_address=192.168.1.70

(5).复制生成虚拟服务器的主配置文件

cp /etc/vsftpd.conf /etc/vsftpd/vsftpd2.conf

(6).设定虚拟服务器的IP并使虚拟服务器的匿名用户映射到本地用户ftp2

vi /etc/vsftpd/vsftpd2.conf

pam_service_name=vsftpd

listen_address=192.168.1.71

ftp_username=ftp2

(7).重启服务生效：service vsftpd restart

DOS下使用ftp命令：

1. 切换到指定目录下

2. 连接目标ftp服务器：ftp 10.137.97.29

3. 输入帐号、密码

4. 切换传输方式，二进制传输使用bin命令

5. 上传文件：put test_setup.zip

下载文件：get **.zip

6. 退出ftp：bye

7. cd 切换目录

8. del 删除文件

9. dir 查看远程主机当前目录

10. ascii 使用ascii方式传输文件

11. mput、mget： 将多个文件上传、下载

12. mkdir 在远程主机中建立目录

13. pwd 显示远程主机的当前工作目录路径