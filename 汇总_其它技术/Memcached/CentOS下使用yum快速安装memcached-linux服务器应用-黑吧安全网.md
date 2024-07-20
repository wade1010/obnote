1. 查找Memcached

yum search memcached

首先检查yum软件仓库中是否存在memcached，如果有 直接进入第3步安装即可，否则执行第2步。

2. 安装第三方软件库（可选）

标准的CentOS5软件仓库里面是没有memcache相应的包的,所以，我们的第一步就是导入第三方软件仓库，这里推荐的是RpmForge（RpmForge库现在已经拥有超过10000种的CentOS的软件包，被CentOS社区认为是最安全也是最稳定的一个第三方软件库），安装方法如下：

wget http://dag.wieers.com/rpm/packages/rpmforge-release/rpmforge-release-0.5.2-2.rf.src.rpm

rpm -ivh rpmforge-release-0.5.2-2.rf.src.rpm

3.安装Memcached

yum -y install memcached

4.验证安装

memcached -h

 /etc/rc.d/init.d/memcached status

5. 查看配置文件

cat /etc/sysconfig/memcached

可以根据情况修改相关配置参数：

PORT="11211"USER="memcached"MAXCONN="1024"CACHESIZE="64"OPTIONS=""

6.启动memcached

/etc/rc.d/init.d/memcached start

1

该内容对我有帮助

【声明】:黑吧安全网(http://www.myhack58.com)登载此文出于传递更多信息之目的，并不代表本站赞同其观点和对其真实性负责，仅适于网络安全技术爱好者学习研究使用，学习中请遵循国家相关法律法规。如有问题请联系我们，联系邮箱admin@myhack58.com，我们会在最短的时间内进行处理。