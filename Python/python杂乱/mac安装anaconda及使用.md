brew search anaconda

```
$ brew search anaconda
==> Casks
anaconda                                                     pycharm-ce-with-anaconda-plugin
anaconda2                                                    pycharm-with-anaconda-plugin
```

anaconda2对应的是Python2.+版本，anaconda对应Python3.+，无法指定Python3的版本，目前是Python3.8。如果想使用Python3.7的版本，创建个指定Python版本为3.7的虚拟环境即可。

brew install anaconda --cask

配置 Anaconda 环境变量

在终端输入以下命令：

echo 'export PATH="/usr/local/anaconda3/bin:$PATH"' >> ~/.zshrc

更新环境变量配置文件

source ~/.zshrc

配置环境变量前：

配置环境变量后：

Conda一些基本命令

在命令行启动anaconda

anaconda-navigator

查看conda的包列表conda list命令

conda常用命令[https://blog.csdn.net/zjc910997316/article/details/93662410](https://blog.csdn.net/zjc910997316/article/details/93662410)

创建新的python环境：

创建一个名为PythonEnvOne的虚拟环境

conda create --name PythonEnvOne

## Package Plan

environment location: /opt/anaconda3/envs/PythonEnvOne

# 激活此环境的命令

conda activate PythonEnvOne

# 退出此环境的命令

conda deactivate

创建指定python版本为3.7的虚拟环境PythonEnvTwo

conda create -n PythonEnvTwo python=3.7

## Package Plan

environment location: /opt/anaconda3/envs/PythonEnvTwo

并且还可以指定python的版本：$ conda create -n myenv python=3.7

创建新环境并指定包含的库：$ conda create -n myenv scipy

并且还可以指定库的版本：$ conda create -n myenv scipy=0.15.0

复制环境：$ conda create --name myclone --clone myenv

查看是不是复制成功了：$ conda info --envs

激活、进入某个环境：$ source activate myenv

退出环境：$ conda deactivate / $ source deactivate

删除环境：$ conda remove --name myenv --all

查看当前的环境列表$ conda info --envs / $ conda env list

为pycharm配置anaconda虚拟环境的python参考链接：

[https://www.jianshu.com/p/ce99bf9d9008](https://www.jianshu.com/p/ce99bf9d9008)

为jupyter lab 配置anaconda虚拟环境

conda安装虚拟环境后 需要在进入虚拟环境安装一些jupyter所必须的模块，该模块为nb_conda

conda install nb_conda

1

然后需要将虚拟环境的信息写入到jupyter配置文件 安装nb_conda后ipython会作为依赖自动安装

ipython kernel install --user --name your_environment_name

1

重新打开lab 会显示新的环境

配置扩展插件

开启允许安装插件的settings

打开：settings->Advanced Settings Editor

右侧填写：{“enabled”: true}，开启插安装插件：

安装扩展的前提是node.js版本在10.0以上，同时安装npm

conda install npm

1

node.js在每个虚拟环境中都需要安装，因为尝试命令行升级失败，所以推荐在anaconda中安装。

按照截图所示，点击apply进行安装：

首次安装时，默认的node.js版本低于10.0，待首次安装成功后，再次点击nodejs前面的对勾，在出现的下拉菜单中，选择版本10.13.0，再次点击右下角的apply，之后会出现一个确认页面，再次点击apply即可。

为jupyterlab安装扩展

# 显示已安装的扩展插件

jupyter labextension list

安装目录插件toc

jupyter labextension install @jupyterlab/toc

jupyterlab_sublime 则可以让你在 Jupyter lab 的 cell 中，使用跟 SublimeText 一样的快捷键，比如⌘ D能够多选其它与当前选中内容一样的内容；比如⌘加鼠标点击，可以生成多个可编辑点……

jupyter labextension install @ryantam626/jupyterlab_sublime

jupyter lab build

anaconda虚拟环境导出与导入

导出base环境

conda env export --file basepy38.yml

导出虚拟环境前，需要先进入该虚拟环境

conda activate envpy37

当前路径

(envpy37) apple@AppledeMacBook ~ % pwd

/Users/apple

导出环境，这里未指出保存路径，因此保存在了虚拟环境当前的路径

conda env export --file envpy37.yml

导入环境：

可以按照链接的指导，打开anaconda APP进行操作

[https://blog.csdn.net/li1014269733/article/details/93880580](https://blog.csdn.net/li1014269733/article/details/93880580)

也可以用下面的命令操作：

将yml文件复制到B机器中，执行以下命令导入

conda env create -f  /Users/apple/Desktop/envpy37.yml

jupyter notebook 安装扩展插件

启用扩展

为了启用扩展，我们需要运行pip命令来安装该功能：

pip install jupyter_contrib_nbextensions

pip install jupyter_nbextensions_configurator

jupyter contrib nbextension install --user

好用的插件：[https://developer.51cto.com/art/202007/622014.htm](https://developer.51cto.com/art/202007/622014.htm)