[https://blog.csdn.net/i_chips/article/details/127551145](https://blog.csdn.net/i_chips/article/details/127551145)

[https://mariadb.com/resources/blog/how-to-install-mariadb-on-rhel8-centos8/](https://mariadb.com/resources/blog/how-to-install-mariadb-on-rhel8-centos8/)

[https://rhel.pkgs.org/8/epel-x86_64/ivykis-0.42.4-2.el8.x86_64.rpm.html](https://rhel.pkgs.org/8/epel-x86_64/ivykis-0.42.4-2.el8.x86_64.rpm.html)

[http://rpmfind.net/linux/rpm2html/search.php?query=erlang-cuttlefish&submit=Search+...&system=&arch=](http://rpmfind.net/linux/rpm2html/search.php?query=erlang-cuttlefish&submit=Search+...&system=&arch=)

[https://developer.aliyun.com/packageSearch?word=nfs-ganesha](https://developer.aliyun.com/packageSearch?word=nfs-ganesha)

```
要离线安装 `net-snmp` 软件包，并了解其所有依赖项，您可以使用以下命令：

```
yum deplist net-snmp | awk '/provider:/ {print $2}' | sort -u > dependencies.txt
```

这将列出 `net-snmp` 软件包的所有依赖项，并将它们写入名为 `dependencies.txt` 的文件中。`awk` 命令用于提取依赖项的名称，并将其写入文件中。`sort -u` 命令用于去重排序，以避免重复的依赖项。

然后，您可以将 `dependencies.txt` 文件复制到离线环境中，并使用以下命令安装所有依赖项：

```
yum install $(cat dependencies.txt)
```

这将同时安装 `net-snmp` 及其所有依赖项。

请注意，由于依赖项可能存在复杂的依赖关系，因此这个命令可能会安装更多的软件包，这些软件包实际上可能不是 `net-snmp` 的依赖项。因此，在安装软件包之前，请确保您已经了解了它们的用途，并且已经检查了它们是否与您的系统兼容。
```

```
您可以使用以下命令来下载所有以perl开头的rpm包：

```
yum list 'perl-*' | awk '{print $1}' | xargs yumdownloader
```

这个命令的作用是：

1. 使用 `yum list 'perl-*'` 命令列出所有以perl开头的包，其中 `*` 是通配符，表示匹配任何后缀。
2. 使用 `awk '{print $1}'` 命令提取包名的第一个字段，这个字段是列出的每一行的第一个单词。
3. 最后，使用 `xargs yumdownloader` 命令将提取到的包名作为参数传递给 `yumdownloader` 命令，以下载这些包。

请注意，您需要有足够的权限来下载这些包，否则可能会失败。
```

在CentOS 8中，网络管理由NetworkManager服务负责，因此，您可以使用以下命令来使新的IP生效：

```
nmcli connection reload

```

该命令将重新加载NetworkManager配置并更新现有网络连接。如果您刚刚使用nmtui设置了新的IP地址，则应该能够在重新加载后立即看到变化。

如果您想重启整个NetworkManager服务以应用更改，则可以使用以下命令：

```
systemctl restart NetworkManager

```

请注意，重启NetworkManager服务将导致所有网络连接断开并重新连接。因此，在执行此命令之前，请确保您已保存所有未保存的工作并关闭所有正在运行的网络应用程序。

后来发现也不一定生效。virtualbox最下面的网络 关掉，再打开，然后在执行上面 nmcli connection reload好像就可以了

 

```

[root@node157 ~]# pip3 install pecan werkzeug
WARNING: Running pip install with root privileges is generally not a good idea. Try `pip3 install --user` instead.
Collecting pecan
  Downloading https://files.pythonhosted.org/packages/14/14/e1c5336c1b66c380620daf5b880f2371584d42c4c4a265dcf7ce341c9b66/pecan-1.4.2.tar.gz (124kB)
    100% |████████████████████████████████| 133kB 200kB/s
Requirement already satisfied: werkzeug in /usr/lib/python3.6/site-packages
Collecting WebOb>=1.8 (from pecan)
  Downloading https://files.pythonhosted.org/packages/62/9c/e94a9982e9f31fc35cf46cdc543a6c2c26cb7174635b5fd25b0bbc6a7bc0/WebOb-1.8.7-py2.py3-none-any.whl (114kB)
    100% |████████████████████████████████| 122kB 227kB/s
Collecting Mako>=0.4.0 (from pecan)
  Downloading https://files.pythonhosted.org/packages/b4/4d/e03d08f16ee10e688bde9016bc80af8b78c7f36a8b37c7194da48f72207e/Mako-1.1.6-py2.py3-none-any.whl (75kB)
    100% |████████████████████████████████| 81kB 81kB/s
Requirement already satisfied: setuptools in /usr/lib/python3.6/site-packages (from pecan)
Requirement already satisfied: six in /usr/lib/python3.6/site-packages (from pecan)
Collecting logutils>=0.3 (from pecan)
  Downloading https://files.pythonhosted.org/packages/49/b2/b57450889bf73da26027f8b995fd5fbfab258ec24ef967e4c1892f7cb121/logutils-0.3.5.tar.gz
Collecting MarkupSafe>=0.9.2 (from Mako>=0.4.0->pecan)
  Downloading https://files.pythonhosted.org/packages/fc/d6/57f9a97e56447a1e340f8574836d3b636e2c14de304943836bd645fa9c7e/MarkupSafe-2.0.1-cp36-cp36m-manylinux1_x86_64.whl
Installing collected packages: WebOb, MarkupSafe, Mako, logutils, pecan

```