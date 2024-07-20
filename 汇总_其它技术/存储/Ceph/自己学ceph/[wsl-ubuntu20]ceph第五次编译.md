时间：2023年3月1日

环境：ubuntu 20.04

版本：ceph-17.2.5  当前最新版

sudo vim /etc/apt/sources.list

```
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-updates main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-backports main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-security main restricted universe multiverse
```

sudo apt-get update

wget [https://download.ceph.com/tarballs/ceph-17.2.5.tar.gz](https://download.ceph.com/tarballs/ceph-17.2.5.tar.gz)&&tar zxf ceph-17.2.5.tar.gz && mv ceph-17.2.5 ceph &&cd ceph

sudo apt-get install --reinstall ca-certificates  

要不然会报错

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359143.jpg)

下面这一步好像需要执行一遍 ./install-deps.sh ,要不然是没有fake*这些文件的

cd /usr/bin/

ll fake*

sudo rm -rf fakeroot

sudo ln -s fakeroot-tcp fakeroot

ll fake*

cd -

./install-deps.sh

./do_cmake.sh

cd build

ninja

../src/vstart.sh -d -n -x -l -b

如果报错如下

build/bin/init-ceph: 40: export: (x86)/Razer: bad variable name

改成root用户即可

最终发现wsl下面系统还是跟Linux下面不一样，执行下面

```
../src/vstart.sh --debug --new -x --localhost --bluestore
```

会报错

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359893.jpg)