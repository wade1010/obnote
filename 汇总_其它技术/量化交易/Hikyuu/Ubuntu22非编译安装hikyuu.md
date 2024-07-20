##### 安装conda

wget [https://repo.anaconda.com/miniconda/Miniconda3-py39_23.3.1-0-Linux-x86_64.sh](https://repo.anaconda.com/miniconda/Miniconda3-py39_23.3.1-0-Linux-x86_64.sh)

bash Miniconda3-py39_23.3.1-0-Linux-x86_64.sh

conda config --set auto_activate_base false

##### 创建python39环境

conda create --name hikyuu python=3.9

conda activate hikyuu

2024-1-12，这里要用python3.9不能3.10，要不然报错如下

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331114.jpg)

如果版本错误，可以更新当前环境的Python版本（conda更新python版本）

```
conda install python=<Python版本号>
```

##### 配置python国内源

mkdir ~/.pip

vim ~/.pip/pip.conf

```
[global]
timeout = 60
index-url = http://pypi.douban.com/simple
trusted-host = pypi.douban.com
```

##### 安装hikyuu

pip install hikyuu

执行一遍 HikyuuTDX

添加~/.hikyuu/xxxx