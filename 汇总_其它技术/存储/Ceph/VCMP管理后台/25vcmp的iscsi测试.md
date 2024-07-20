### 1 查看tgtd服务状态

systemctl status tgtd.service

如果不正常可以根据错误来查询解决下

我这次遇到的是缺少perl-Config-General-2.63-10.el8.noarch.rpm

```
curl -O  https://dl.fedoraproject.org/pub/epel/8/Everything/x86_64/Packages/p/perl-Config-General-2.63-10.el8.noarch.rpm

rpm -ivh perl-Config-General-2.63-10.el8.noarch.rpm
```

### 2 创建卷

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352743.jpg)

### 3 添加windows客户端组

#### 3.1 使用IP

查看客户端机器IP

这里是192.168.1.128

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352069.jpg)

#### 3.2 使用IQN

打开iscsi查看windows的IQN

复制里面的IQN，不需要改

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352284.jpg)

然后到web端添加

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352584.jpg)

### 4 添加访问路径

名称就使用随机的

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352600.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352863.jpg)

#### 添加主机

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352784.jpg)

#### 添加卷

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352098.jpg)

#### 添加客户端组

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352127.jpg)

### 5 windows的iscsi客户端使用卷

这次服务端IP是192.168.1.157

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352967.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352230.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352894.jpg)

在客户端桌面，右键单击“计算机”，选择“管理”。弹出“计算机管理”

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352052.jpg)

选择“存储 > 磁盘管理”

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352814.jpg)

选择需要初始化的逻辑硬盘和分区形式，单击“确定”。等待 1 分钟左右，当“磁盘 1”、“磁盘 2”的状态变为“联机”时，初始化成功。

将新增的逻辑硬盘进行分区并格式化。右键单击新增的逻辑硬盘，选择“新建简单卷”。弹出“新建简单卷导向”对话框，按照操作向导进行操作，参数保持默认无需配置。

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353937.jpg)

按照导向新建简单卷完成后，该新磁盘状态为“正在格式化”。

待新磁盘状态显示“状态良好”，格式化完成。

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353104.jpg)

不想用的时候，可以删除卷之后再进行如下图操作

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353212.jpg)

2023-8-21 12:45:09

unix客户端连接：

iscsiadm -m discovery -t sendtargets -p 10.200.152.47  

发现OK了

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353301.jpg)

验证下，在另外一台节点，就是上图执行discovery的节点

执行以下命令登录目标器  iscsiadm -m node -p 10.200.152.47 -l

lsblk  就可以看到多了一个设备

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353288.jpg)

查看session命令 iscsiadm -m node  

执行以下命令，登出应用服务器 iscsiadm -m node -U all

登出后，再lsblk就看不到上面的设备了。