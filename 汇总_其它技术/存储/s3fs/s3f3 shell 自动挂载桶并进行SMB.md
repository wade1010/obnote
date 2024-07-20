s3f3 shell 自动挂载桶并进行SMB/FTP/NFS共享

验证samba是否共享成功

sudo yum install samba-client -y

smbclient //172.0.16.211/s3fs_share -U ftpuser

其中 share_bk1 时下图红色标记部分

vim /etc/samba/smb.conf

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002691.jpg)

按提示输入密码

连接上 ls查看

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002965.jpg)