Ubuntu安装python3.9

sudo apt install -y wget build-essential libreadline-dev libncursesw5-dev libssl-dev libsqlite3-dev tk-dev libgdbm-dev libc6-dev libbz2-dev libffi-dev zlib1g-dev

wget [https://www.python.org/ftp/python/3.9.0/Python-3.9.0b4.tgz](https://www.python.org/ftp/python/3.9.0/Python-3.9.0b4.tgz)

tar -zxvf Python-3.9.0b4.tgz # 解压源码包

#编译参数设置

./configure --prefix=/usr/local/python3

#编译

make

#安装

sudo make install

sudo rm python

sudo rm python3 #并不会删除 python2.7 和 python3.5

sudo ln -s /usr/local/python3/bin/python3.9 /usr/bin/python3

sudo ln -s /usr/local/python3/bin/python3.9 /usr/bin/python

#为 pip 设置软链接

sudo ln -s /usr/local/python3/bin/pip3.9 /usr/bin/pip3

sudo ln -s /usr/local/python3/bin/pip3.9 /usr/bin/pip

[https://www.jb51.net/article/202175.htm](https://www.jb51.net/article/202175.htm)