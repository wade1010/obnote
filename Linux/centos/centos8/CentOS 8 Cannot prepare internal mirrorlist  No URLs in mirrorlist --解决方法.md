CentOS 8: Cannot prepare internal mirrorlist: No URLs in mirrorlist --解决方法





输入以下命令

sudo dnf install -y curl policycoreutils openssh-server perl


报错 Cannot prepare internal mirrorlist: No URLs in mirrorlist

查了下资料：

问题：

在CentOS 8中，使用yum时出现错误，镜像列表中没有url，类似如下:

Error: Failed to download metadata for repo 'appstream': Cannot prepare internal mirrorlist: No URLs in mirrorlist

原因

在2022年1月31日，CentOS团队终于从官方镜像中移除CentOS 8的所有包。

CentOS 8已于2021年12月31日寿终正非，但软件包仍在官方镜像上保留了一段时间。现在他们被转移到https://vault.centos.org

解决方法

如果你仍然需要运行CentOS 8，你可以在/etc/yum.repos.d中更新一下源。使用vault.centos.org代替mirror.centos.org。

```javascript
sed -i -e "s|mirrorlist=|#mirrorlist=|g" /etc/yum.repos.d/CentOS-*
sed -i -e "s|#baseurl=http://mirror.centos.org|baseurl=http://vault.centos.org|g" /etc/yum.repos.d/CentOS-*
```

如图：

![](D:/download/youdaonote-pull-master/data/Technology/Linux/centos/centos8/images/0BAE67FF4A944769B9BCBAE658D4971A106602-20220211201435606-1254755766.png)

参考资料：https://blog.csdn.net/xiaocao_debug/article/details/122807870