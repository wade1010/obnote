# 一、安装

step 1. 切换到要安装jupyterlab的虚拟环境

```
conda activate my_env
```

step 2. 安装jupterlab（安装jupyterlab前需要安装nodejs）

```
conda install jupyterlab
```

step 3. 安装ipytkernel

```
conda install ipykernel
```

step 4. 将ipykernel注入虚拟环境

python -m ipykernel install --user --name 【环境名称】 --display-name 【在jupyter中显示名字】

如：python -m ipykernel install --user --name py310 --display-name py310(可以和环境名一样，也可以不一样。)

# 二、配置

## 1. 修改默认打开的文件夹

如果想让jupyter lab每次打开的时候文件浏览器选项卡中加载某个路径的文件夹，可通过下面的方法设置：

参考链接：修改Jupyter Lab、Jupyter Notebook的工作目录_奶茶可可的博客-CSDN博客_jupyterlab目录

step1：运行以下命令，生成jupyter的配置文件。配置文件在【用户\.jupyter\jupyter_lab_config.py】

```
jupyter lab --generate-config
```

密码生成 jupyter lab password   参考了 [https://blog.51cto.com/u_16513038/11138891](https://blog.51cto.com/u_16513038/11138891)

argon2:$argon2id$v=19$m=10240,t=10,p=8$mIJLEX7EUXkJ7XbE8uOvPA$L2UGAX6XKsMLsvisIOuvTeh0STaVfydQBN8dPqv2esw

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCEefc8772dbdc1ebeee6c9c1c6e18bda91image.png)

```
c.ServerApp.allow_remote_access = True
c.ServerApp.ip = '*'
c.ServerApp.open_browser = False
c.ServerApp.password = 'argon2:$argon2id$v=19$m=10240,t=10,p=8$Cr4UXBbepw4/D56Ee2Z+Kg$xAffIg8r4N6oTHwUqtF+MmQCMRYLEKSGPOT78SMO164'
c.ServerApp.port = 8888
c.ServerApp.root_dir='/home/cstu/jupyterspace/data'
```

# 启动

nohup jupyter lab > xxx.log 2>&1 &

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCE89ff5be415f930f87c79928c5e701b40image.png)

# 三、插件

### 设置中文

下载中文包：

```shell
pip install jupyterlab-language-pack-zh-CN -i https://pypi.tuna.tsinghua.edu.cn/simple
```

选择语言：

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCEf4d63d107f148a561b5d30c2cd756c58stickPicture.png)

### 自动补全，代码提示

```
pip install jupyterlab-lsp -i https://pypi.tuna.tsinghua.edu.cn/simple
pip install -U jedi-language-server -i https://pypi.tuna.tsinghua.edu.cn/simple
```

重启之后就能使用，但是有一个问题，想要获取提示必须要按Tab键才显示，

做如下调整：

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCEfc7acfc8694ff11e147576378cbe6f7astickPicture.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCE4ffac4888047a012dfa504b1c04e6d21image.png)

ps:

之前看大家教程都是切换到json，然后添加

**{"continuousHinting": true}**

现在版本好像不行了，报错conntinuousHinting 不是一个合法的属性

根据图形化界面设置后，切换到json,可以看到会增加右边的一些配置

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCE23c8bb13b0dfaaa2e5e9c5cea88ea81bimage.png)