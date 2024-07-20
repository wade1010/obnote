linux安装python3

[https://blog.csdn.net/Alex_Sheng_Sea/article/details/123259315](https://blog.csdn.net/Alex_Sheng_Sea/article/details/123259315)

一、安装依赖环境

输入命令：yum -y install zlib-devel bzip2-devel openssl-devel ncurses-devel sqlite-devel readline-devel tk-devel gdbm-devel db4-devel libpcap-devel xz-devel

二、下载Python3

1.进入opt文件目录下，cd opt/

2.下载python3   （可以到官方先看最新版本多少）

输入命令 wget [https://www.python.org/ftp/python/3.7.1/Python-3.7.1.tgz](https://www.python.org/ftp/python/3.7.1/Python-3.7.1.tgz)

如果出现 找不到wget命令，输入yum -y install wget，安装其依赖将会被安装

3.安装Python3

安装在/usr/local/python3（具体安装位置看个人喜好）

（1）创建目录：  mkdir -p /usr/local/python3

（2）解压下载好的Python-3.x.x.tgz包(具体包名因你下载的Python具体版本不不同⽽而不不同，如：我下载的是Python3.7.1.那我这里就是Python-3.7.1.tgz)

输入命令 tar -zxvf Python-3.7.1.tgz

解压后出现python的文件夹

4.进入解压后的目录，编译安装。（编译安装前需要安装编译器yum install gcc）

（1）安装gcc

输入命令 yum install gcc，确认下载安装输入“y”

（2）3.7版本之后需要一个新的包libffi-devel

安装即可：yum install libffi-devel -y

（3）进入python文件夹，生成编译脚本(指定安装目录)：

cd Python-3.7.1

./configure --prefix=/usr/local/python3

#/usr/local/python3为上面步骤创建的目录

（4）编译：make

（5）编译成功后，编译安装：make install

安装成功：

（6）检查python3.7的编译器：/usr/local/python3/bin/python3.7

5.建立Python3和pip3的软链:

ln -s /usr/local/python3/bin/python3 /usr/bin/python3

ln -s /usr/local/python3/bin/pip3 /usr/bin/pip3

6.并将/usr/local/python3/bin加入PATH

（1）vim /etc/profile

（2）按“I”，然后贴上下面内容：

# vim ~/.bash_profile

# .bash_profile

# Get the aliases and functions

if [ -f ~/.bashrc ]; then

. ~/.bashrc

fi

# User specific environment and startup programs

PATH=$PATH:$HOME/bin:/usr/local/python3/bin

export PATH

（3）按ESC，输入:wq回车退出。

（4）修改完记得执行行下面的命令，让上一步的修改生效：

source ~/.bash_profile

7.检查Python3及pip3是否正常可用：

python3 -V

pip3 -V

————————————————

版权声明：本文为CSDN博主「Alex_Sheng_Sea」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/Alex_Sheng_Sea/article/details/123259315](https://blog.csdn.net/Alex_Sheng_Sea/article/details/123259315)

安装完之后，执行pIp install 发现报错 和openssl有关

得重新编译

wget [https://www.openssl.org/source/openssl-1.1.1n.tar.gz](https://www.openssl.org/source/openssl-1.1.1n.tar.gz) --no-check-certificate 下载openssl1.1.1

tar -zxf openssl-1.1.1n.tar.gz 解压

cd openssl-1.1.1n

./config

make -j && make install

cd Python-3.10.1

make clean

./configure --prefix=/usr/local/python3 --with-openssl=/usr/local/include/openssl --with-openssl-rpath=auto

 make -j && make install