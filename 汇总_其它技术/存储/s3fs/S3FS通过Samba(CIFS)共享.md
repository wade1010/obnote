S3FS通过Samba(CIFS)共享

 

**一、环境**

1、服务端操作系统 centos7  ；客户端操作系统win10

2、s3fs 版本 ：V1.84

执行命令  s3fs --version 可查看到，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002719.jpg)

3、samba 版本：4.8.3-4

4、对象存储HCP版本 ：8.1.0.9

# **二、s3fs安装及挂载桶**

注：当前登录用户是root

**1、安装命令**

```javascript
sudo yum install epel-release
sudo yum install s3fs-fuse
```

**2、验证S3FS安装结果**

执行 s3fs -h  可以看到帮助信息

执行 s3fs --version 可以看到版本信息

**3、桶的认证信息配置**

1）配置/etc/hosts 文件，加入如下信息。注：如果有dns可忽略此配置 

执行命令  vim /etc/hosts

| 192.168.19.131  t1.hcp-demo.hcpdemo.com 192.168.19.131  n1.t1.hcp-demo.hcpdemo.com | 


2）创建挂载点目录  mkdir /mnt/s3fs

3）创建桶的认证信息文件

执行  vim ~/.passwd-s3fs 然后，加入桶的ak:sk 。例：bmV0c2tpbGw=:5f69a28f21e7ac685a3e51280137e42a

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002627.jpg)

此认证信息对当前用户有效。

执行 vim /etc/passwd-s3fs 然后，加入桶的ak:sk。例：bmV0c2tpbGw=:5f69a28f21e7ac685a3e51280137e42a

此配置在本系统中，全局有效。

4）修改 认证文件权限

执行  chmod 600 /etc/passwd-s3fs 或 chmod 600 ~/.passwd-s3fs

**4、挂载桶n1**

执行命令如下命令进行挂载

```javascript
s3fs n1 /mnt/s3fs/   -o allow_other  -o url=http://t1.hcp-demo.hcpdemo.com -o dbglevel=info -f -o curldbg
```

参数说明：

| allow_other   | 允许非owner用户访问挂载目录 | 
| -- | -- |
| dbglevel=info | 显示运行过程日志，日志级别为Info | 
| -f  | 在屏幕上显示日志信息 | 
| curldbg | 显示curl的调试信息 | 


注：上述挂载方式，适合于调式模式，按 "ctrl + c" 就会退出。

**5、查看挂载结果**

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002810.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002044.jpg)

完全相同的内容，在HCP和centos中均可见。

**三、samba安装**

**1、关闭selinux**

1）查看selinux状态  执行 sestatus

2）临时关闭  执行  setenforce 0

3）永久关闭，需要重启。 修改配置文件/etc/selinux/config，将SELINU置为disabled

| [root@localhost ~]# cat /etc/selinux/config  # This file controls the state of SELinux on the system. # SELINUX= can take one of these three values: #     enforcing - SELinux security policy is enforced. #     permissive - SELinux prints warnings instead of enforcing. #     disabled - No SELinux policy is loaded. # SELINUX=enforcing  | 


**2、安装samba**

1）安装

```javascript
yum install samba

```

2）查看服务状态

```javascript
service smb status 
```

3）查看安装的版本

```javascript
rpm -qa | grep samba
```

4）启动、重启、停止、查看服务状态

```javascript
systemctl start smb
systemctl restart smb
systemctl stop smb
service smb status 
```

5）设置开机自启动

```javascript
systemctl enable smb
```

**3、在samba中为用户root设置密码**

```javascript
smbpasswd -a root
```

注：此用户必须是本系统中已经存在的用户

**4、samba共享目录配置**

修改文件 /etc/samba/smb.conf  在最后面加入如下信息

| [Shared]  | 


**5、重启samba、查看运行状态**

```javascript
systemctl restart smb
service smb status 
```

**6、samba日志配置**

参考：[https://www.oreilly.com/openbook/samba/book/ch09_01.html](https://www.oreilly.com/openbook/samba/book/ch09_01.html)

当有异常时可根据日志进行调试。

1）配置日志级别和日志保存位置，如下图：

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002230.jpg)

2）生成的日志信息

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002228.jpg)

**四、win7挂载测试**

1）打开窗口输入 \\192.168.19.130\Shared

2）提示输入帐号，密码。输入用户名：root 。 输入密码：在samba中设置的密码。

3）显示共享目录文件，和对象存储中文件相同

![](https://gitee.com/hxc8/images6/raw/master/img/202407190002346.jpg)

**六、功能测试**

| 动作 | 结果 | 备注 | 
| -- | -- | -- |
| 文件创建 | 成功 |  | 
| 文件删除 | 成功 |  | 
| 文件改名 | 成功 |  | 
| 文件编辑+保存 | 成功 | 有时挂载成功，但是无法编辑。是因为之前可能挂载多次带来的影响。执行   | 
| 文件读取 | 成功 |  | 
| 文件同名覆盖 | 成功 | 多版本 | 
| 目录创建 | 成功 |  | 
| 非空目录删除 | 不成功 | 批量删除子目录+文件 | 
| 空目录删除 | 不成功 |  | 
| 目录改名 | 成功 |  | 
| 目录复制 | 成功 |  | 
| 目录移动 | 成功 | 批量移动子目录+文件 | 
|  |  |  | 


**七、异常解决**

|  | 


**八、性能测试**

未开始