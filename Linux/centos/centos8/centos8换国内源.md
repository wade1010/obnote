运行yum报错

```
CentOS Linux 8 - AppStream                                                                                                                                                            80  B/s |  38  B     00:00
错误：为仓库 'appstream' 下载元数据失败 : Cannot prepare internal mirrorlist: No URLs in mirrorlist
```

mv /etc/yum.repos.d/ /etc/yum.repos.d_bak

mkdir /etc/yum.repos.d

curl -O [https://vault.centos.org/centos/8/AppStream/x86_64/os/Packages/wget-1.19.5-10.el8.x86_64.rpm](https://vault.centos.org/centos/8/AppStream/x86_64/os/Packages/wget-1.19.5-10.el8.x86_64.rpm)

curl -O [https://vault.centos.org/centos/8/BaseOS/x86_64/os/Packages/libmetalink-0.1.3-7.el8.x86_64.rpm](https://vault.centos.org/centos/8/BaseOS/x86_64/os/Packages/libmetalink-0.1.3-7.el8.x86_64.rpm)

rpm -ivh libmetalink-0.1.3-7.el8.x86_64.rpm

rpm -ivh wget-1.19.5-10.el8.x86_64.rpm

cd /etc/yum.repos.d

wget [http://mirrors.aliyun.com/repo/Centos-8.repo](http://mirrors.aliyun.com/repo/Centos-8.repo)

yum clean all

yum makecache

OK