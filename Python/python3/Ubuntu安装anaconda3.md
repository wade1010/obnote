# Ubuntu安装anaconda3

[https://mirrors.tuna.tsinghua.edu.cn/anaconda/archive/?C=M&O=D](https://mirrors.tuna.tsinghua.edu.cn/anaconda/archive/?C=M&O=D)  找个最新的

wget [https://mirrors.tuna.tsinghua.edu.cn/anaconda/archive/Anaconda3-2022.10-Linux-x86_64.sh](https://mirrors.tuna.tsinghua.edu.cn/anaconda/archive/Anaconda3-2022.10-Linux-x86_64.sh)

sh Anaconda3-2022.10-Linux-x86_64.sh

注意不要按太多下回车，一次按一下，要不然可能自动给你确认下一条命令了

我之前按多了，导致下图的自动给我选no了

![](D:/download/youdaonote-pull-master/data/Technology/Python/python3/images/WEBRESOURCE52dc2d117e0b9266bfbd71a5decbe1d4截图.png)

echo 'export PATH="~/anaconda3/bin":$PATH' >> ~/.bashrc

验证

conda list

如果不想自动切到base环境，conda config --set auto_activate_base false关闭

[https://www.jb51.net/article/230757.htm](https://www.jb51.net/article/230757.htm)