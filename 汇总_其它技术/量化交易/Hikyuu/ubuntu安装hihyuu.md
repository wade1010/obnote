apt-cache search  xxxx

```
apt-get install -y libhdf5-dev libhdf5-serial-dev libmysqlclient-dev
```

安装如下命令，将gcc和g++升级到9

sudo apt-get update && 

sudo apt-get install build-essential software-properties-common -y && 

sudo add-apt-repository ppa:ubuntu-toolchain-r/test -y && 

sudo apt-get update && 

sudo apt-get install gcc-snapshot -y && 

sudo apt-get update && 

sudo apt-get install gcc-9 g++-9 -y && 

sudo update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-9 60 --slave /usr/bin/g++ g++ /usr/bin/g++-9

什么需要点时间

apt-cache search clang

apt install clang-3.9

然后执行clang++

如果显示下面的提示

# clang++

The program 'clang++' can be found in the following packages:

 * clang-3.3

 * clang-3.4

 * clang-3.5

就自己做个软连

ln -s /usr/bin/clang++-3.9 /usr/bin/clang++

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333336.jpg)

apt install git -y

mkdir /opt/hikyuu  && cd /opt/hikyuu

```
git clone --branch=dev 
cd ./tboox/xmake
./scripts/get.sh __local__
```

cd ../..

```
git clone 
```

cd hikyuu

wget [https://boostorg.jfrog.io/artifactory/main/release/1.78.0/source/boost_1_78_0.tar.gz](https://boostorg.jfrog.io/artifactory/main/release/1.78.0/source/boost_1_78_0.tar.gz)

tar zxvf boost_1_78_0.tar.gz

python setup.py install   发现非root才行。尴尬了

折腾下，切换到vagrant。继续

it clone --branch=dev [https://github.com/tboox/xmake.git](https://github.com/tboox/xmake.git) tboox/xmake --depth 1

cd ./tboox/xmake

./scripts/get.sh __local__

**source ~/.xmake/profile**

cd ../../hikyuu/

python setup.py install

报错

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333962.jpg)

sudo apt install unzip

python setup.py install

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333330.jpg)

git config --global http.sslverify "false"

python setup.py install

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333706.jpg)