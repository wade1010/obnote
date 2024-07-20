在运维实战中，如果有若干台数据库服务器，想对这些服务器进行同等动作，比如查看它们当前的即时负载情况，查看它们的主机名，分发文件等等，这个时候该怎么办？一个个登陆服务器去操作，太傻帽了！写个shell去执行，浪费时间~~

这种情况下，如果集群数量不多的话，选择一个轻量级的集群管理软件就显得非常有必要了。ClusterShell就是这样一种小的集群管理工具，原理是利用ssh，可以说是Linux系统下非常好用的运维利器！

选择了clustershell这个软件（也简称clush），原因如下：

1）安装方便。一条指令就能轻松安装。

2）配置方便。很多集群管理软件都需要在所有的服务器上都安装软件，而且还要进行很多的连接操作，clustershell就相当的方便了，仅仅需要所有机器能够ssh无密码登录即可，然后只在一台服务器上安装clustershell即可。

3）使用方便。clustershell的命令相对来说非常简单，只有一两个指令以及三四个参数需要记。

一、部署clush环境

安装clush（可以yum直接安装，也可以源码安装）

# yum install -y clustershell

配置clush：

在/etc/clustershell目录下，手动创建groups文件

# touch /etc/clustershell/groups

# vim /etc/clustershell/groups

all: a1 host1 host2

name:host3 host4

需要注意的是all 是必须配置的，clush 有 -a 这个参数，主机间用空格分离。

clush命令：

clush -a 全部 等于 clush -g all

clush -g 指定组

clush -w 操作主机名字，多个主机之间用逗号隔开

clush -g 组名 -c  --dest 文件群发     （-c等于--copy）

注意：clush 是不支持环境变量的$PATH

二、下面依据实例对clush的使用进行说明

现在有四台服务器，主机名分别是ops-server1、ops-server2、ops-server3、ops-server4

需求：

利用ops-server1服务器控制其他三台服务器进行集群操作.

那么只需要在ops-server1上安装clustershell，并前提做好ops-server1主机和其他三台机器的ssh无密码登陆的信任关系即可.

下面是ops-server1服务器上的操作记录：

1）做好主机映射关系，将ip和主机名对应起来，使用比较方便。

[root@ops-server1 ~]# cat /etc/hosts

127.0.0.1 localhost localhost.localdomain localhost4 localhost4.localdomain4

::1 localhost localhost.localdomain localhost6 localhost6.localdomain6

192.168.1.102 ops-server2

192.168.1.118 ops-server3

192.168.1.108 ops-server4

2）做好ssh信任关系（最好事后验证下无密码信任关系）

[root@ops-server1 ~]# ssh-keygen -t rsa (产生本机的公私钥文件,否则没法做ssh信任关系，也没法使用ssh-copy-id命令)

[root@ops-server1 ~]# ssh-copy-id ops-server2

[root@ops-server1 ~]# ssh-copy-id ops-server3

[root@ops-server1 ~]# ssh-copy-id ops-server4

3)安装clush，然后配置clush（手动创建groups文件）

可以采用yum方式安装（yum install clustershell -y）

这里采用yum源码安装方式，下载clustershell-1.6.tar.gz,下载到／usr/local/src目录下

源码下载地址：http://openstorage.gunadarma.ac.id/pypi/simple/ClusterShell/

[root@ops-server1 ~]# cd /usr/local/src/

[root@ops-server1 src]# ls

clustershell-1.6.tar.gz

[root@ops-server1 src]# tar -zvxf clustershell-1.6.tar.gz

[root@ops-server1 src]# cd clustershell-1.6

[root@ops-server1 clustershell-1.6]# python setup.py install

-----------------------------------------------------------------------------------------------------

如果出现下面报错，说明Python默认没有安装setuptools这个第三方模块。

Traceback (most recent call last):

File "setup.py", line 35, in <module>

from setuptools import setup, find_packages

ImportError: No module named setuptools

解决办法：

安装setuptools（可以百度网盘下载，http://pan.baidu.com/s/1mhTDRBE  提取密码：xpmd），如下安装setuptools后，再次安装上面的clustershell就ok了。

[root@ops-server1 src]# wget http://pypi.python.org/packages/source/s/setuptools/setuptools-0.6c11.tar.gz

[root@ops-server1 src]# tar -zxvf setuptools-0.6c11.tar.gz

[root@ops-server1 src]# cd setuptools-0.6c11

[root@ops-server1 setuptools-0.6c11]# python setup.py build

[root@ops-server1 setuptools-0.6c11# python setup.py install

-----------------------------------------------------------------------------------------------------

[root@ops-server1 clustershell-1.6]# mkdir /etc/clustershell

[root@ops-server1 clustershell-1.6]# cp -r conf/* /etc/clustershell

[root@ops-server1 clustershell-1.6]# cd /etc/clustershell/

[root@ops-server1 clustershell]# ls

clush.conf groups groups.conf groups.conf.d

可以将groups文件里默认的示例内容全部注释，然后按照自己的集群管理需求自定义配置的组对应关系，（再次强调下：groups文件中的all组对应是必须要配置的，clush 有 -a 这个参数，主机间用空格分离。）

如下，配置组all，组db等的对应关系，这些组不是真实存在机器上的用户组，而是在groups文件中设置的别名而已，用以批量操作。

总之，可以在groups文件里设置多组对应关系，然后对组对应的主机进行远程操控！！！

[root@ops-server1 clustershell]# cat groups

#adm: example0

#oss: example4 example5

#mds: example6

#io: example[4-6]

#compute: example[32-159]

#gpu: example[156-159]

#all: example[4-6,32-159]

db: ops-server[2,3]

all: ops-server[2,3,4]

解下来就可以利用clush管理命令进行远程机器集群管理了，常用的是下面几个参数：

-g 后面指定设置的组

-a 表示所有的组

-w 后面跟主机节点，多个主机中间用逗号隔开

-x 表示去掉某个节点进行操作。后面跟主机节点，多个主机中间用逗号隔开

-X 表示去掉某个组进行操作，多个组之间用逗号隔开

-b 相同输出结果合并

注意，clush操作远程机器，执行动作要放在双引号或单引号内进行

|   |   |
| - | - |
| 1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20<br>21 | ------------------------------------------------------------------------------------------------<br>如果出现下面报错：<br>[root@ops-server1 clustershell]\# clush -g db uptime<br>ops-server2: Host key verification failed.<br>clush: ops-server2: exited with exit code 255<br>ops-server3: Host key verification failed.<br>clush: ops-server3: exited with exit code 255<br> <br>原因可能是ssh首次登陆的时候，会提示输入"yes/no"，需要提前将这个执行。<br>[root@ops-server1 clustershell]\# ssh ops-server2<br>The authenticity of host 'ops-server2 (192.168.1.102)' can't be established.<br>RSA key fingerprint is 89:29:5b:26:c1:3a:94:10:10:bd:7c:aa:6b:e5:0c:1c.<br>Are you sure you want to continue connecting (yes/no)? yes                 \#这里要输入yes<br>.......<br> <br>[root@ops-server3 clustershell]\# ssh ops-server3<br>The authenticity of host 'ops-server2 (192.168.1.118)' can't be established.<br>RSA key fingerprint is 89:29:5b:26:c1:3a:94:10:10:bd:7c:aa:6b:e5:0c:1c.<br>Are you sure you want to continue connecting (yes/no)? yes                 \#这里要输入yes<br>.......<br>------------------------------------------------------------------------------------------------ |


[root@ops-server1 clustershell]# clush -g db uptime

ops-server2: 22:49:35 up 4 days, 14:24, 0 users, load average: 0.00, 0.01, 0.05

ops-server3: 22:49:42 up 11:13, 1 user, load average: 0.00, 0.01, 0.05

[root@ops-server1 clustershell]# clush -a uptime

ops-server2: 22:49:49 up 4 days, 14:24, 0 users, load average: 0.00, 0.01, 0.05

ops-server4: 22:49:42 up 8 days, 30 min, 0 users, load average: 0.00, 0.01, 0.05

ops-server3: 22:49:57 up 11:13, 1 user, load average: 0.00, 0.01, 0.05

[root@ops-server1 clustershell]# clush -a hostname

ops-server4: ops-server4

ops-server3: ops-server3

ops-server2: ops-server2

[root@ops-server1 clustershell]# clush -a "echo asdfsdf > /tmp/test"

[root@ops-server1 clustershell]# clush -a "cat /tmp/test"

ops-server4: asdfsdf

ops-server3: asdfsdf

ops-server2: asdfsdf

[root@ops-server1 clustershell]# clush -w ops-server3 'ifconfig|grep "inet addr"|grep 192.168'

ops-server3: inet addr:192.168.1.118 Bcast:192.168.1.255 Mask:255.255.255.0

[root@ops-server1 clustershell]# clush -w ops-server3,ops-server4 'ifconfig|grep "inet addr"|grep 192.168'

ops-server4: inet addr:192.168.1.108 Bcast:192.168.1.255 Mask:255.255.255.0

ops-server3: inet addr:192.168.1.118 Bcast:192.168.1.255 Mask:255.255.255.0

[root@ops-server1 ~]# clush -a hostname

ops-server2: ops-server2

ops-server3: ops-server3

ops-server4: ops-server4

[root@ops-server1 ~]# clush -b -a hostname

---------------

ops-server2

---------------

ops-server2

---------------

ops-server3

---------------

ops-server3

---------------

ops-server4

---------------

ops-server4

[root@ops-server1 ~]# clush -a "cat /etc/issue"

ops-server2: CentOS release 6.8 (Final)

ops-server2: Kernel \r on an \m

ops-server2:

ops-server4: CentOS release 6.8 (Final)

ops-server4: Kernel \r on an \m

ops-server4:

ops-server3: CentOS release 6.8 (Final)

ops-server3: Kernel \r on an \m

ops-server3:

[root@ops-server1 ~]# clush -b -a "cat /etc/issue"

---------------

ops-server[2-4] (3)

---------------

CentOS release 6.8 (Final)

Kernel \r on an \m

[root@ops-server1 ~]# clush -a -x ops-server4 hostname

ops-server3: ops-server3

ops-server2: ops-server2

[root@ops-server1 ~]# clush -a -x ops-server2,ops-server4 hostname

ops-server3: ops-server3

clush进行文件或目录分发：

--copy 表示从本地拷贝文件或目录到远程集群节点上，等于-c

--rcopy 表示从远程集群节点上拷贝文件或目录到本机上

--dest 前面表示本地要复制的文件或目录路径，后面表示远程机器的存放路径。--dest后面可以空格跟目标路径，也可以是=目标路径。  比如--dest /tmp 等同于 --dest=/tmp

本地拷贝文件到远程节点上

[root@ops-server1 ~]# cat test.file

test1

test2

123456

[root@ops-server1 ~]# clush -g db -c /root/test.file --dest /root/

[root@ops-server1 ~]# clush -g db "cat /root/test.file"

ops-server2: test1

ops-server2: test2

ops-server2: 123456

ops-server3: test1

ops-server3: test2

ops-server3: 123456

[root@ops-server1 ~]# clush -w ops-server4 --copy test.file --dest /root/

[root@ops-server1 ~]# clush -w ops-server4 "cat /root/test.file"

ops-server4: test1

ops-server4: test2

ops-server4: 123456

本地拷贝目录到远程节点上（注意，这里面拷贝目录时，不需要跟参数-r）

[root@ops-server1 ~]# mkdir /root/huanqiu

[root@ops-server1 ~]# clush -g db -c /root/huanqiu --dest /root/

[root@ops-server1 ~]# clush -g db "ls -l /root/huanqiu"

ops-server2: total 0

ops-server3: total 0

比如远程拷贝ops-server3和ops-server4节点上的/root/test到本机的/tmp目录下

[root@ops-server1 tmp]# pwd

/tmp

[root@ops-server1 tmp]# clush -w ops-server3,ops-server4 --rcopy /root/test --dest=/tmp/

[root@ops-server1 tmp]# ll

-rw-r--r--. 1 root root 9 Nov 25 02:06 test.ops-server3

-rw-r--r--. 1 root root 19 Nov 25 02:06 test.ops-server4

将所有节点的/etc/passwd文件拷贝到本机的/tmp目录下

[root@ops-server1 tmp]# clush -a --rcopy /etc/passwd --dest=/tmp

[root@ops-server1 tmp]# ll

total 16

-rw-r--r--. 1 root root 901 Nov 25 02:04 passwd.ops-server2

-rw-r--r--. 1 root root 854 Nov 25 02:04 passwd.ops-server3

-rw-r--r--. 1 root root 854 Nov 25 02:04 passwd.ops-server4

-rw-r--r--. 1 root root 9 Nov 25 02:06 test.ops-server3

-rw-r--r--. 1 root root 19 Nov 25 02:06 test.ops-server4

由此可以发现，远程拷贝文件到本机后，会在文件名的后面打上主机名的标记！

另外注意一个参数：

--user=username，这个表示使用clush命令操作时，登陆ssh时使用的用户。

比如：

ops-server1本机(root账号)管理ops-server2节点机的wangshibo账号下的操作，

首先做本机到wangshibo@ops-server2的ssh无密码登陆的信任关系

[root@ops-server1 ~]# ssh-copy-id -i /root/.ssh/id_rsa.pub wangshibo@ops-server2

wangshibo@ops-server2's password:

Now try logging into the machine, with "ssh 'wangshibo@ops-server2'", and check in:

.ssh/authorized_keys

to make sure we haven't added extra keys that you weren't expecting.

[root@ops-server1 ~]# ssh wangshibo@ops-server2

[wangshibo@ops-server2 ~]$

接着就可以进行远程管控了。注意：--user参数要紧跟clush后面

[root@ops-server1 ~]# clush -w --user=wangshibo ops-server2 hostname

Usage: clush [options] command

clush: error: option -w: invalid value: '--user=wangshibo'

[root@ops-server1 ~]# clush --user=wangshibo -w ops-server2 hostname

ops-server2: ops-server2

[root@ops-server1 ~]# clush --user=wangshibo -w ops-server2 "echo 123456 > test"

[root@ops-server1 ~]# clush --user=wangshibo -w ops-server2 "cat test"

ops-server2: 123456

登陆ops-server2的wangshibo用户下查看：

[wangshibo@ops-server2 ~]$ ls

test

[wangshibo@ops-server2 ~]$ cat test

123456

===================================================================

由于clush是基于ssh和scp命令进行封装的一个工具，默认的ssh端口如果不是22，那么在执行clush命令的时候需要指明端口：

1）进行文件传输时，                                           需要加 -o -P57891                      即大写P

2）进行直接访问（批量执行操作命令）时，       需要加 -o -p57891                      即小写P

# clush -g virtual -o -P22222 -c /data/ntpcheck.sh --dest /data/

# clush -g virtual -o -p22222  /etc/init.d/nginx restart

