sudo parted /dev/sdb rm 1

sudo parted /dev/sdb rm 2

sudo parted /dev/sdb rm 3

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353871.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354061.jpg)

后来清理集群再次添加集群

发现报错不太一样

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354796.jpg)

vim /usr/lib/systemd/system/[ceph-osd@.service](http://ceph-osd@.service)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354019.jpg)

查了下资料，说是systemctl版本低，升级下

sudo yum install systemd-*

```

[root@node1-38 ~]# sudo yum install systemd-*
已加载插件：fastestmirror
Determining fastest mirrors
 * base: mirrors.huaweicloud.com
 * extras: mirrors.huaweicloud.com
 * updates: mirrors.huaweicloud.com
base                                                                                                                                                                               | 3.6 kB  00:00:00
extras                                                                                                                                                                             | 2.9 kB  00:00:00
updates                                                                                                                                                                            | 2.9 kB  00:00:00
(1/4): base/7/x86_64/group_gz                                                                                                                                                      | 153 kB  00:00:00
(2/4): extras/7/x86_64/primary_db                                                                                                                                                  | 249 kB  00:00:00
(3/4): base/7/x86_64/primary_db                                                                                                                                                    | 6.1 MB  00:00:01
(4/4): updates/7/x86_64/primary_db                                                                                                                                                 |  22 MB  00:00:02
正在解决依赖关系
--> 正在检查事务
---> 软件包 systemd.x86_64.0.219-78.el7 将被 升级
---> 软件包 systemd.x86_64.0.219-78.el7_9.7 将被 更新
---> 软件包 systemd-devel.x86_64.0.219-78.el7_9.7 将被 安装
---> 软件包 systemd-journal-gateway.x86_64.0.219-78.el7_9.7 将被 安装
--> 正在处理依赖关系 libmicrohttpd.so.10()(64bit)，它被软件包 systemd-journal-gateway-219-78.el7_9.7.x86_64 需要
---> 软件包 systemd-libs.x86_64.0.219-78.el7 将被 升级
---> 软件包 systemd-libs.x86_64.0.219-78.el7_9.7 将被 更新
---> 软件包 systemd-networkd.x86_64.0.219-78.el7_9.7 将被 安装
---> 软件包 systemd-python.x86_64.0.219-78.el7_9.7 将被 安装
---> 软件包 systemd-resolved.x86_64.0.219-78.el7_9.7 将被 安装
---> 软件包 systemd-sysv.x86_64.0.219-78.el7 将被 升级
---> 软件包 systemd-sysv.x86_64.0.219-78.el7_9.7 将被 更新
--> 正在检查事务
---> 软件包 libmicrohttpd.x86_64.0.0.9.33-2.el7 将被 安装
--> 解决依赖关系完成

依赖关系解决

==========================================================================================================================================================================================================
 Package                                                    架构                                      版本                                               源                                          大小
==========================================================================================================================================================================================================
正在安装:
 systemd-devel                                              x86_64                                    219-78.el7_9.7                                     updates                                    216 k
 systemd-journal-gateway                                    x86_64                                    219-78.el7_9.7                                     updates                                    391 k
 systemd-networkd                                           x86_64                                    219-78.el7_9.7                                     updates                                    485 k
 systemd-python                                             x86_64                                    219-78.el7_9.7                                     updates                                    146 k
 systemd-resolved                                           x86_64                                    219-78.el7_9.7                                     updates                                    422 k
正在更新:
 systemd                                                    x86_64                                    219-78.el7_9.7                                     updates                                    5.1 M
 systemd-libs                                               x86_64                                    219-78.el7_9.7                                     updates                                    419 k
 systemd-sysv                                               x86_64                                    219-78.el7_9.7                                     updates                                     97 k
为依赖而安装:
 libmicrohttpd                                              x86_64                                    0.9.33-2.el7                                       base                                        58 k

事务概要
==========================================================================================================================================================================================================
安装  5 软件包 (+1 依赖软件包)
升级  3 软件包

总下载量：7.3 M
Is this ok [y/d/N]: y
Downloading packages:
Delta RPMs disabled because /usr/bin/applydeltarpm not installed.
警告：/var/cache/yum/x86_64/7/updates/packages/systemd-devel-219-78.el7_9.7.x86_64.rpm: 头V3 RSA/SHA256 Signature, 密钥 ID f4a80eb5: NOKEY
systemd-devel-219-78.el7_9.7.x86_64.rpm 的公钥尚未安装
(1/9): systemd-devel-219-78.el7_9.7.x86_64.rpm                                                                                                                                     | 216 kB  00:00:00
(2/9): systemd-journal-gateway-219-78.el7_9.7.x86_64.rpm                                                                                                                           | 391 kB  00:00:00
(3/9): systemd-networkd-219-78.el7_9.7.x86_64.rpm                                                                                                                                  | 485 kB  00:00:00
libmicrohttpd-0.9.33-2.el7.x86_64.rpm 的公钥尚未安装                                   14% [===========                                                                 ] 1.1 MB/s | 1.1 MB  00:00:05 ETA
(4/9): libmicrohttpd-0.9.33-2.el7.x86_64.rpm                                                                                                                                       |  58 kB  00:00:00
(5/9): systemd-219-78.el7_9.7.x86_64.rpm                                                                                                                                           | 5.1 MB  00:00:01
(6/9): systemd-libs-219-78.el7_9.7.x86_64.rpm                                                                                                                                      | 419 kB  00:00:00
(7/9): systemd-python-219-78.el7_9.7.x86_64.rpm                                                                                                                                    | 146 kB  00:00:00
(8/9): systemd-sysv-219-78.el7_9.7.x86_64.rpm                                                                                                                                      |  97 kB  00:00:00
(9/9): systemd-resolved-219-78.el7_9.7.x86_64.rpm                                                                                                                                  | 422 kB  00:00:00
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
总计                                                                                                                                                                      4.7 MB/s | 7.3 MB  00:00:01
从 file:///etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-7 检索密钥
导入 GPG key 0xF4A80EB5:
 用户ID     : "CentOS-7 Key (CentOS 7 Official Signing Key) <security@centos.org>"
 指纹       : 6341 ab27 53d7 8a78 a7c2 7bb1 24c6 a8a7 f4a8 0eb5
 软件包     : centos-release-7-9.2009.0.el7.centos.x86_64 (@anaconda)
 来自       : /etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-7
是否继续？[y/N]：y
Running transaction check
Running transaction test
Transaction test succeeded
Running transaction
警告：RPM 数据库已被非 yum 程序修改。
** 发现 34 个已存在的 RPM 数据库问题， 'yum check' 输出如下：
32:bind-libs-9.11.4-26.P2.el7_9.13.x86_64 是 32:bind-libs-9.9.4-73.el7_6.x86_64 的副本
32:bind-license-9.11.4-26.P2.el7_9.13.noarch 是 32:bind-license-9.9.4-73.el7_6.noarch 的副本
chrony-3.4-1.el7.x86_64 是 chrony-3.1-2.el7.centos.x86_64 的副本
1:cups-libs-1.6.3-51.el7.x86_64 是 1:cups-libs-1.6.3-35.el7.x86_64 的副本
1:dbus-libs-1.10.24-15.el7.x86_64 是 1:dbus-libs-1.10.24-13.el7_6.x86_64 的副本
glibc-2.17-317.el7.x86_64 是 glibc-2.17-260.el7_6.4.x86_64 的副本
glibc-2.17-326.el7_9.x86_64 是 glibc-2.17-317.el7.x86_64 的副本
glibc-common-2.17-317.el7.x86_64 是 glibc-common-2.17-260.el7_6.4.x86_64 的副本
glibc-common-2.17-326.el7_9.x86_64 是 glibc-common-2.17-317.el7.x86_64 的副本
glibc-devel-2.17-326.el7_9.x86_64 是 glibc-devel-2.17-260.el7_6.4.x86_64 的副本
glibc-headers-2.17-326.el7_9.x86_64 是 glibc-headers-2.17-260.el7_6.4.x86_64 的副本
kexec-tools-2.0.15-51.el7.x86_64 是 kexec-tools-2.0.15-43.el7.x86_64 的副本
krb5-libs-1.15.1-50.el7.x86_64 是 krb5-libs-1.15.1-37.el7_6.x86_64 的副本
krb5-libs-1.15.1-55.el7_9.x86_64 是 krb5-libs-1.15.1-50.el7.x86_64 的副本
libX11-1.6.7-2.el7.x86_64 是 libX11-1.6.3-2.el7.x86_64 的副本
libX11-common-1.6.7-2.el7.noarch 是 libX11-common-1.6.3-2.el7.noarch 的副本
libcap-2.22-11.el7.x86_64 是 libcap-2.22-10.el7.x86_64 的副本
libibverbs-22.4-5.el7.x86_64 是 libibverbs-22.1-3.el7.x86_64 的副本
libjpeg-turbo-1.2.90-8.el7.x86_64 是 libjpeg-turbo-1.2.90-5.el7.x86_64 的副本
libkadm5-1.15.1-55.el7_9.x86_64 是 libkadm5-1.15.1-37.el7_6.x86_64 的副本
14:libpcap-1.5.3-12.el7.x86_64 是 14:libpcap-1.5.3-11.el7.x86_64 的副本
2:libpng-1.5.13-8.el7.x86_64 是 2:libpng-1.5.13-5.el7.x86_64 的副本
librdmacm-22.4-5.el7.x86_64 是 librdmacm-22.1-3.el7.x86_64 的副本
libseccomp-2.3.1-4.el7.x86_64 是 libseccomp-2.3.1-3.el7.x86_64 的副本
libstoragemgmt-1.8.1-2.el7_9.x86_64 是 libstoragemgmt-1.8.1-1.el7.x86_64 的副本
libtirpc-0.2.4-0.16.el7.x86_64 是 libtirpc-0.2.4-0.15.el7.x86_64 的副本
libxcb-1.13-1.el7.x86_64 是 libxcb-1.11-4.el7.x86_64 的副本
openldap-2.4.44-22.el7.x86_64 是 openldap-2.4.44-21.el7_6.x86_64 的副本
1:openssl-libs-1.0.2k-26.el7_9.x86_64 是 1:openssl-libs-1.0.2k-19.el7.x86_64 的副本
psmisc-22.20-15.el7.x86_64 是 psmisc-22.20-9.el7.x86_64 的副本
rdma-core-22.4-5.el7.x86_64 是 rdma-core-22.1-3.el7.x86_64 的副本
selinux-policy-3.13.1-268.el7_9.2.noarch 是 selinux-policy-3.13.1-268.el7.noarch 的副本
selinux-policy-targeted-3.13.1-268.el7_9.2.noarch 是 selinux-policy-targeted-3.13.1-268.el7.noarch 的副本
zlib-1.2.7-18.el7.x86_64 是 zlib-1.2.7-15.el7.x86_64 的副本
  正在更新    : systemd-libs-219-78.el7_9.7.x86_64                                                                                                                                                   1/12
  正在更新    : systemd-219-78.el7_9.7.x86_64                                                                                                                                                        2/12
  正在安装    : libmicrohttpd-0.9.33-2.el7.x86_64                                                                                                                                                    3/12
  正在安装    : systemd-journal-gateway-219-78.el7_9.7.x86_64                                                                                                                                        4/12
  正在安装    : systemd-networkd-219-78.el7_9.7.x86_64                                                                                                                                               5/12
  正在安装    : systemd-devel-219-78.el7_9.7.x86_64                                                                                                                                                  6/12
  正在更新    : systemd-sysv-219-78.el7_9.7.x86_64                                                                                                                                                   7/12
  正在安装    : systemd-resolved-219-78.el7_9.7.x86_64                                                                                                                                               8/12
  正在安装    : systemd-python-219-78.el7_9.7.x86_64                                                                                                                                                 9/12
  清理        : systemd-sysv-219-78.el7.x86_64                                                                                                                                                      10/12
  清理        : systemd-219-78.el7.x86_64                                                                                                                                                           11/12
  清理        : systemd-libs-219-78.el7.x86_64                                                                                                                                                      12/12
  验证中      : systemd-networkd-219-78.el7_9.7.x86_64                                                                                                                                               1/12
  验证中      : systemd-devel-219-78.el7_9.7.x86_64                                                                                                                                                  2/12
  验证中      : systemd-libs-219-78.el7_9.7.x86_64                                                                                                                                                   3/12
  验证中      : systemd-sysv-219-78.el7_9.7.x86_64                                                                                                                                                   4/12
  验证中      : libmicrohttpd-0.9.33-2.el7.x86_64                                                                                                                                                    5/12
  验证中      : systemd-219-78.el7_9.7.x86_64                                                                                                                                                        6/12
  验证中      : systemd-journal-gateway-219-78.el7_9.7.x86_64                                                                                                                                        7/12
  验证中      : systemd-resolved-219-78.el7_9.7.x86_64                                                                                                                                               8/12
  验证中      : systemd-python-219-78.el7_9.7.x86_64                                                                                                                                                 9/12
  验证中      : systemd-sysv-219-78.el7.x86_64                                                                                                                                                      10/12
  验证中      : systemd-libs-219-78.el7.x86_64                                                                                                                                                      11/12
  验证中      : systemd-219-78.el7.x86_64                                                                                                                                                           12/12

已安装:
  systemd-devel.x86_64 0:219-78.el7_9.7            systemd-journal-gateway.x86_64 0:219-78.el7_9.7         systemd-networkd.x86_64 0:219-78.el7_9.7         systemd-python.x86_64 0:219-78.el7_9.7
  systemd-resolved.x86_64 0:219-78.el7_9.7

作为依赖被安装:
  libmicrohttpd.x86_64 0:0.9.33-2.el7

更新完毕:
  systemd.x86_64 0:219-78.el7_9.7                                systemd-libs.x86_64 0:219-78.el7_9.7                                systemd-sysv.x86_64 0:219-78.el7_9.7

完毕！

```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354299.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354417.jpg)

直接用这个还是不行，systemctl start ceph-osd.target

systemctl start [ceph-osd@0](http://ceph-osd@0)

systemctl start [ceph-osd@1](http://ceph-osd@1)

systemctl start [ceph-osd@2](http://ceph-osd@2)

启动才行，后续再看看