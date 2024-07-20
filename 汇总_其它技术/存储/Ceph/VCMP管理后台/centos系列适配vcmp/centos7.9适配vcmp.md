### ps:使用一段时间后发现，安装包是适配完了，但是在集群初始化的时候，发现OSD启动失败，后来发现是systemctl版本比较低，ceph14之后systemctl版本高一点，有些参数不支持，尝试升级了，发现有些依赖挺多，也不搞了，直接升级到centos8.5 ，错误参考

### 下载centos7.9镜像

这里我下载的是minimal版本

[https://mirrors.aliyun.com/centos/7.9.2009/isos/x86_64/CentOS-7-x86_64-Minimal-2009.iso?spm=a2c6h.25603864.0.0.18de6aeaCyQ1UD](https://mirrors.aliyun.com/centos/7.9.2009/isos/x86_64/CentOS-7-x86_64-Minimal-2009.iso?spm=a2c6h.25603864.0.0.18de6aeaCyQ1UD)

### 虚拟机相关安装配置

安装镜像并添加三个盘配置桥接模式

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352983.jpg)

### 替换国内yum源

cp /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup

wget -O /etc/yum.repos.d/CentOS-Base.repo [https://mirrors.aliyun.com/repo/Centos-7.repo](https://mirrors.aliyun.com/repo/Centos-7.repo)    (第一次先 yum install -y wget )

yum makecache

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352212.jpg)

yum -y update

最好重启下

缺什么包，可以到那个目录

使用

```
yumdownloader  xxx
```

yyyyy 被   xxxxx需要

有两种情况

1、SDS里面个包版本比系统的低，这个时候需要yumdownloader 下载后面的xxxx，然后删除SDS里面旧的包

2、缺失yyyyy，使用yumdownloader下载yyyyy

比如

yumdownloader gcc gcc-c++

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352608.jpg)

再比如

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352094.jpg)

问题，不知道解决没，后续重新用centos验证下

7_Beegfs 安装的时候

sudo yum install -y glibc-devel-2.17-326.el7_9.i686 glibc-headers-2.17-326.el7_9.x86_64 libmpc-1.0.1-3.el7.i686 libstdc++-devel-4.8.5-44.el7.i686

有些包版本对不上，可通过yumdownloader(yum install yum-utils -y)下载对应的rpm包，然后删除就得rpm包（yumdownloader可能会下载32为系统的包，也就是包含i686的，删除掉即可）

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352184.jpg)

总结下来一般报错  xxxxx被 xxxxx需要，一般是系统的包版本比安装脚本里面的rpm包要新，可以使用yumdownloader下载最新版，删掉旧版，如下图，

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352166.jpg)

安装/root/build-x86/web/depends/lsb_release也有问题

sudo yum install redhat-lsb-core安装成功后才能install成功

```
yumdownloader avahi-libs at cups-client ed m4 patch mailx redhat-lsb-core redhat-lsb-submod-security time spax
然后删除32位的包
```

也有不一定是按名字来yumdownloader的。。。

情况1

```
错误：依赖检测失败：
        perl(Data::Dumper) 被 mariadb-server-1:5.5.68-1.el7.x86_64 需要
        perl(Data::Dumper) 被 perl-DBI-1.627-4.el7.x86_64 需要
```

yumdownloader perl-Data-Dumper 才能解决

情况2

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352694.jpg)

yum provides '**libavahi-client.so.3()(64bit)**'