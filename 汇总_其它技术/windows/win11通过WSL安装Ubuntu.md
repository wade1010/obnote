![](https://gitee.com/hxc8/images3/raw/master/img/202407172243044.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243620.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243099.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243550.jpg)

在CMD输入 wsl -h可以获取到常用指令信息，如：

列出分发：wsl -l

运行指定分发：wsl -d <分发>

更改新分发的默认安装版本：wsl --set-default-version <Version>

将分发设置为默认值：wsl -s <分发>

更改指定分发的版本：wsl --set-version <分发> <版本>

立即终止所有运行的分发及 WSL2：wsl --shutdown

终止指定的分发（相当于关机）：wsl -t <分发>

注销分发并删除根文件系统：wsl --unregister <分发>

将指定的 tar 文件作为新分发导入：wsl --import <Distro> <InstallLocation> <FileName>

将分发导出到 tar 文件：wsl --export <Distro[分发]> <FileName[文件名，包含文件全路径]>

例如：把分发CentOS7导出命令：wsl --export CentOS7 E:\CentOS7\rootfs.tar