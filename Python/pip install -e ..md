源码安装（用于开发）

pip install -e .

如果使用 pip install -e . 安装了一个可编辑的 Python 包，并且需要将该包还原为普通的安装包，可以执行以下命令：

Copy

```
pip uninstall <package_name>

```

其中，<package_name> 表示要卸载的包的名称。执行这个命令后，会将该包从 Python 环境中卸载，并删除链接到当前目录的软链接。

需要注意的是，如果在安装可编辑包时使用了 sudo 命令或者其他权限命令，卸载时也需要相应的权限命令。另外，如果在安装包时使用了其他参数（如 -r requirements.txt），在卸载包时也需要使用相应的参数。

总之，可以使用 pip uninstall 命令将使用 pip install -e . 安装的可编辑包还原为普通的安装包。

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCE45fbdc4ddd1079bf5e09f021f7c17934截图.png)