时间：2023年2月

环境：ubuntu 18

版本：ceph-16.2.11

wget [https://download.ceph.com/tarballs/ceph-16.2.11.tar.gz](https://download.ceph.com/tarballs/ceph-16.2.11.tar.gz)

tar zxvf ceph-16.2.11.tar.gz

也可以 git clone 和更新子模块，但是我觉得耗时间，就用tarball

[https://docs.ceph.com/en/latest/install/build-ceph/](https://docs.ceph.com/en/latest/install/build-ceph/)

./install-deps.sh 2023-2-25 20:44:20开始 结束时间没注意，挺快的，预计几分钟

但是好像有问题

./install-deps.sh发现报错，根据报错安装curl 成绩

sudo apt install curl

继续执行./install-deps.s

发现还报错

![](https://gitee.com/hxc8/images6/raw/master/img/202407190000189.jpg)

curl --silent --remote-name --location [https://github.com/ceph/ceph/raw/quincy/src/cephadm/cephadm](https://github.com/ceph/ceph/raw/quincy/src/cephadm/cephadm)

上面如果没反应，就手动打开 上面网址在浏览器打开，是个python源代码，然后找个目录

vim cephadm

然后把python源代码复制进去，让后保存

chmod +x cephadm

sudo ./cephadm add-repo --release quincy   (发现报错)

我就试试上一个版本

sudo ./cephadm add-repo --release pacific

![](https://gitee.com/hxc8/images6/raw/master/img/202407190000685.jpg)

sudo ./cephadm install ceph-common

./do_cmake.sh

报错

C++20 support requires a  minimum Clang version of 12.

那就按提示升级clang

C++20 support requires a minimum GCC version of 11.

那就按提示升级gcc  

那就按提示升级clang和gcc

sudo add-apt-repository ppa:ubuntu-toolchain-r/test

sudo apt update

apt-cache search gcc-11

sudo apt-get install gcc-11  我这里报错了

![](https://gitee.com/hxc8/images6/raw/master/img/202407190000770.jpg)

折腾后

```
cd $HOME
wget http://archive.ubuntu.com/ubuntu/pool/main/i/isl/libisl19_0.19-1_amd64.deb
sudo dpkg -i ./libisl19_0.19-1_amd64.deb
sudo apt-get update
sudo apt-get upgrade
sudo apt-get dist-upgrade
rm ./libisl19_0.19-1_amd64.deb
```

clang和gcc装好，需要把新版本设置为默认

gcc的如下，clang的没记录下来

sudo update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-11 100

sudo update-alternatives --install /usr/bin/g++ g++ /usr/bin/g++-11 100

继续

rm -rf build/

./do_cmake.sh

顺利完成

![](https://gitee.com/hxc8/images6/raw/master/img/202407190000186.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190000363.jpg)

好像最终都退出了wsl系统