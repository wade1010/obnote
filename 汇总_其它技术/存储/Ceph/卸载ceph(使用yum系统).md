提示：每台ceph节点都要执行

yum -y install ceph-deploy

hostname=hostname

ceph-deploy purge $hostname

ceph-deploy purgedata $hostname

ceph-deploy forgetkeys

osd 卸载命令

dmsetup status #查询所有ceph osd  或者vgscan + vgremove 加查询出来的id

dmsetup remove_all #删除所有查询的内容

————————————————

版权声明：本文为CSDN博主「人生匆匆」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/a13568hki/article/details/119459832](https://blog.csdn.net/a13568hki/article/details/119459832)

另外方案

```bash
# 方式一（删除最彻底）：
rm -rf /etc/ceph/*
rm -rf /var/lib/ceph/*/*
rm -rf /var/log/ceph/*
rm -rf /var/run/ceph/*
rm -rf /usr/bin/ceph*
rm -rf /usr/sbin/ceph*
rm -rf /etc/systemd/system/ceph*
rm -rf /usr/lib/systemd/system/ceph*
# 方式二（登录主机一台一台操作,清除得不彻底）：
rpm -qa|grep ceph
rpm -e python-cephfs-12.2.10-0.el7.x86_64 --nodeps
rpm -e ceph-base-12.2.10-0.el7.x86_64 --nodeps
rpm -e libcephfs2-12.2.10-0.el7.x86_64 --nodeps
rpm -e ceph-common-12.2.10-0.el7.x86_64 --nodeps
rpm -e ceph-selinux-12.2.10-0.el7.x86_64 --nodeps
# 方式三(一下删除多台)：
ceph-deploy purge node-1 node-2 node-3
ceph-deploy purgedata node-1 node-2 node-3
```