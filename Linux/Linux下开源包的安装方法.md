分成3大类

1、有makefile的，直接make && make install；如果没有makefile的，./configure && make && make install

2、以cmake为例，里面会有CMakeList.txt

mkdir build && cd build && cmake .. 

make && sudo make install

3、以固定包的方式 deb的二进制文件

dpkg -i xxx.deb