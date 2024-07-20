[https://blog.csdn.net/weixin_43425561/article/details/115765148](https://blog.csdn.net/weixin_43425561/article/details/115765148)

一、查看已安装的系统

在win10终端或powershell里面键入：

wsl -l -v

1

示例：为Ubuntu-18.04系统做快照

二、做快照

代码如下（示例）：

wsl --export Ubuntu-18.04 d:\wsl-ubuntu18.04.tar

1

三.回滚

1.注销当前系统

wsl --unregister Ubuntu-18.04

1

2.回滚

wsl --import Ubuntu-18.04 d:\wsl d:\wsl-ubuntu18.04.tar --version 2

1

3.设置默认登陆用户为安装时用户名

ubuntu1804 config --default-user USERNAME

1

如果是ubuntu20.04，命令ubuntu1804改为ubuntu2004即可；USERNAME是登录用户名称，如Raymond