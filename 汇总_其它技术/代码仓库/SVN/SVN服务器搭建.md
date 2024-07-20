安装步骤如下：

1、yum install subversion（默认是最经典的1.6版本）



2、输入rpm -ql subversion查看安装位置，如下图：

 

![](https://gitee.com/hxc8/images5/raw/master/img/202407180001640.jpg)

我们知道svn在bin目录下生成了几个二进制文件。

输入 svn --help可以查看svn的使用方法，如下图。

![](https://gitee.com/hxc8/images5/raw/master/img/202407180001558.jpg)





3、创建svn版本库目录

mkdir  /var/svn

cd /var/svn

创建一个子版本库目录

mkdir  /var/svn/svnrepos/

ps:svn目录是根目录，下面可以有很多个版本库 比如svnrepos1、svnrepos2。。。。。

4、创建版本库

svnadmin create /var/svn/svnrepos

执行了这个命令之后会在/var/svn/svnrepos目录下生成如下这些文件

![](https://gitee.com/hxc8/images5/raw/master/img/202407180001716.jpg)



5、进入conf目录（该svn版本库配置文件）

authz文件是权限控制文件

passwd是帐号密码文件

svnserve.conf SVN服务配置文件



6、设置帐号密码

vi passwd

在[users]块中添加用户和密码，格式：帐号=密码，如user=111111



7、设置权限

vi authz

在末尾添加如下代码：

[/]

user=rw

user2=r

意思是版本库的根目录user对其有读写权限，user2只有读权限。



8、修改svnserve.conf文件

vi svnserve.conf

打开下面的几个注释：

anon-access = read #匿名用户可读

auth-access = write #授权用户可写

password-db = passwd #使用哪个文件作为账号文件

authz-db = authz #使用哪个文件作为权限文件

realm = /var/svn/svnrepos # 认证空间名，版本库所在目录（或者realm = svnrepos # 认证空间名，版本库所在目录）



9、启动svn版本库

svnserve -d -r /var/svn（多版本，下面的svnrepos1、svnrepos2都能访问）

svnserve -d -r /var/svn/svnrepos（单个版本）



10、自启动

vi /etc/rc.d/rc.local

在最后添加一行内容如下：

svnserve -d -r  /var/svn



11、如果链接有问题，防火墙开放端口

vi /etc/sysconfig/iptables

加入一行内容如下：

-A INPUT -s 127.0.0.1/32 -p tcp -m tcp --dport 3690 -j ACCEPT



12、客户端连接 svn://121.40.31.66/svnrepos(端口默认就不写了)



13、之后就可以提交更新了。