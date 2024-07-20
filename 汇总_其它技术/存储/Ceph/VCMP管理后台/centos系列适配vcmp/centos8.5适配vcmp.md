```
mkdir /etc/yum.repos.d.bak
mv /etc/yum.repos.d/* /etc/yum.repos.d.bak/
curl -o /etc/yum.repos.d/CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-8.repo
sed -i -e '/mirrors.cloud.aliyuncs.com/d' -e '/mirrors.aliyuncs.com/d' /etc/yum.repos.d/CentOS-Base.repo
sed -i 's/releasever\//releasever-stream\//g' /etc/yum.repos.d/CentOS-Base.repo
yum clean all
yum makecache
sudo dnf repolist
yum install tar -y
yum install yum-utils -y
```

比如我安装snmp,对应的是net-snmp这个包，想下载它以及它所依赖的包，可以用下面命令

sudo dnf download --resolve net-snmp --downloaddir=.

来源于chatgpt

```
在centos8上面，我想搭建本地安装库，我需要安装snmp，我想把安装它的所有依赖都下载下来

要在 CentOS 8 上安装 `snmp` 软件包及其所有依赖项，您可以使用 `dnf` 命令的 `download` 子命令来下载软件包及其依赖项到本地目录中。以下是具体步骤：

1. 创建一个新的目录，用于存储下载的软件包及其依赖项。例如，您可以创建一个名为 `localrepo` 的目录：

   `````
   mkdir localrepo
   cd localrepo
   ```

2. 使用以下命令下载 `snmp` 软件包及其所有依赖项（包括间接依赖项）到 `localrepo` 目录中：

   ````
   sudo dnf download --resolve snmp --downloaddir=.
   ````

   这将下载所有需要的 RPM 包到当前目录中。请注意，此命令会下载所有架构（如 x86_64、i686 等）的 RPM 包。如果您只需要特定架构的 RPM 包，请使用 `--arch` 选项指定架构。

3. 使用 `createrepo` 命令创建一个新的本地存储库。例如：

   ````
   sudo dnf install createrepo
   createrepo .
   ````

   这将在当前目录中创建一个新的本地存储库，其中包含您下载的所有 RPM 包。

4. 打开 `/etc/yum.repos.d` 目录，创建一个新的 .repo 文件，其中包含本地存储库的配置。例如：

   ````
   sudo vi /etc/yum.repos.d/localrepo.repo
   ````

   在该文件中，添加以下配置：

   ````
   [localrepo]
   name=Local Repository
   baseurl=file:///path/to/localrepo
   enabled=1
   gpgcheck=0
   ````

   这将创建一个新的存储库，其中 `baseurl` 指向您创建的本地存储库的路径。请确保将 `path/to/localrepo` 替换为实际路径。

5. 运行 `sudo dnf update` 命令来刷新缓存并测试您的本地存储库是否正常工作。例如：

   ````
   sudo dnf update
   ````

   此时，您应该能够从本地存储库安装 `snmp` 软件包及其所有依赖项，而无需连接到互联网。

希望这可以帮助您搭建本地安装库并下载 `snmp` 软件包及其所有依赖项。

```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352536.jpg)

发现上面我问题，可以进入到该目录，删除所有rpm包，然后使用下面命令把包以及依赖下载下来

```
sudo dnf download --resolve net-snmp --downloaddir=.
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352643.jpg)

可以利用provides来查看失败的包是哪个包包含的

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352401.jpg)

sudo dnf provides "**libXaw3d**"