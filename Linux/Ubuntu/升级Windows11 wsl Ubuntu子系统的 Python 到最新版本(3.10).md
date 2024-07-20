[http://www.gsgundam.com/2023/01/2023-01-18-z08-upgrade-python-3-in-ubuntu-of-wsl-windows/](http://www.gsgundam.com/2023/01/2023-01-18-z08-upgrade-python-3-in-ubuntu-of-wsl-windows/)

# 安装 WSL

这个过程挺简单，因为已经在商店安装过WSL了，因此只需要打开命令行，先检查一下是不是 v2 版本，不是就切换一下，然后升级。

|   | 


搞定。然后就是折腾 Python 版本的问题。

首先，Python3.10 并不在 Ubuntu 20.04 的默认官方源中，需要添加源单独安装。

|   | 


|   | 


很简单的就安装完了 Python 3.10 ，现在可以使用命令 python3 --version 打印版本，这个时候发现仍然是老版本。

| gsgundam@NUCHome:~$ sudo update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.8 1 | 


这里将 3.10 作为可选版本加入了，并设置为了自动选择的版本。

这个时候如果用 pip 命令来安装依赖，还是会报错。执行以下命令来修复：

|   | 


再跑 pip install -r requirements.txt，然后 python3 main.py，一切正常。

rm -rf get-pip.py

收工。