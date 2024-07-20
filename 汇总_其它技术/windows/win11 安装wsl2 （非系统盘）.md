### ubuntu20.04（D盘）

下面是将 Ubuntu20.04 安装在D盘的部分：

首先创建一个文件夹，比如D:\Linux ,这样即便是重装系统我也不用重新装软件。

然后进到这个文件夹，下载ubuntu20.04：

ubuntu20:

Invoke-WebRequest -Uri [https://wsldownload.azureedge.net/Ubuntu_2004.2020.424.0_x64.appx](https://wsldownload.azureedge.net/Ubuntu_2004.2020.424.0_x64.appx) -OutFile Ubuntu20.04.appx -UseBasicParsing

等他下载完即可，**文件有4G多，等一会是正常的**

然后依次执行下面四条命令：

```
Rename-Item .\Ubuntu20.04.appx Ubuntu.zip
Expand-Archive .\Ubuntu.zip -Verbose
cd .\Ubuntu\
.\ubuntu2004.exe
```

ubuntu18:

Invoke-WebRequest -Uri [https://wsldownload.azureedge.net/CanonicalGroupLimited.Ubuntu18.04onWindows_1804.2018.817.0_x64__79rhkp1fndgsc.Appx](https://wsldownload.azureedge.net/CanonicalGroupLimited.Ubuntu18.04onWindows_1804.2018.817.0_x64__79rhkp1fndgsc.Appx) -OutFile Ubuntu18.04.appx -UseBasicParsing

```
Rename-Item .\Ubuntu18.04.appx Ubuntu.zip
Expand-Archive .\Ubuntu.zip -Verbose
cd .\Ubuntu\
.\ubuntu1804.exe
```

**然后输你想要的入用户名和密码就行**

-----------------------------------------------------------------------------------------

当然这个时候可能会报错：

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243219.jpg)

造成该问题的原因是WSL版本由原来的WSL1升级到WSL2后，内核没有升级，前往微软WSL官网下载安装适用于 x64 计算机的最新 WSL2 Linux 内核更新包即可。

下载链接：[https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi](https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi) 