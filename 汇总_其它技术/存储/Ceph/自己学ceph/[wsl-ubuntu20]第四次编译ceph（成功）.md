时间：2023年2月28日

环境：ubuntu 20.04

版本：ceph-16.2.9

官网：[https://docs.ceph.com/en/pacific/install/build-ceph/](https://docs.ceph.com/en/pacific/install/build-ceph/)

sudo vim /etc/apt/sources.list

```
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-updates main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-backports main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-security main restricted universe multiverse
```

sudo apt-get update

sudo apt-get install --reinstall ca-certificates

cd

wget [https://download.ceph.com/tarballs/ceph-16.2.9.tar.gz](https://download.ceph.com/tarballs/ceph-16.2.9.tar.gz)

tar zxf ceph-16.2.9.tar.gz && mv ceph-16.2.9 ceph &&cd ceph

./install-deps.sh 会报错（wsl里面会报错）

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359370.jpg)

cd /usr/bin/

ll fake*

sudo rm -rf fakeroot

sudo ln -s fakeroot-tcp fakeroot

ll fake*

cd -

./install-deps.sh

还是有报错

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359379.jpg)

我这里尝试好几次 

最终我用vscode打开这个项目（wsl里面code ceph），然后找到报错的地方，通过替换或者修改来尝试

主要有两个错：

1 pytest版本的问题 

```
ERROR: Double requirement given: pytest (from -r requirements-test.txt (line 1)) (already in pytest==6.2.4 (from -r requirements-lint.txt (line 11)), name='pytest')
```

文件地址 src/pybind/mgr/dashboard/requirements-lint.txt

我的做法是将pytest限定版本去掉

原来内容如下：

```
pylint==2.6.0
flake8==3.9.0
flake8-colors==0.1.6
#TODO: Fix docstring issues: https://tracker.ceph.com/issues/41224
#flake8-docstrings
#pep8-naming
rstcheck==3.3.1
autopep8==1.5.7
pyfakefs==4.5.0
isort==5.5.3
pytest==6.2.4

```

把最后一行的版本号去掉

```
pylint==2.6.0
flake8==3.9.0
flake8-colors==0.1.6
#TODO: Fix docstring issues: https://tracker.ceph.com/issues/41224
#flake8-docstrings
#pep8-naming
rstcheck==3.3.1
autopep8==1.5.7
pyfakefs==4.5.0
isort==5.5.3
pytest

```

第2个报错是跟mkcodes有关，就是上面图2所示，我这里再复制一遍

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359391.jpg)

我看了src/pybind/mgr/rook/rook-client-python/requirements.txt

内容如下：

```
pytest
requests
pyyaml
markdown
-e git+https://github.com/ryneeverett/mkcodes.git#egg=mkcodes
docopt
tox

```

尝试去掉#egg=mkcodes，不行，

最终我是把科学上网的代理复制到terminal里面，然后OK了

经过3天，每天尝试一会，发现还是有问题在原理里面搜，然后修改比较好，百度啥的，不一定找到答案，毕竟ceph网上编译的我也看了，跟我这个不一样。

最终看到一个Successfully

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359634.jpg)

我不放心，又执行了一遍./install-deps.sh  也没报错

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359731.jpg)

进行步骤2：

./do_cmake.sh 

成功，如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359404.jpg)

cd build

make -j4  2023年2月28日20:20开始   不一会风扇就开始转了，温度飙到多度

后来突然想起来，instal-deps.sh里面我还改了一个地方，暂时没研究有没有影响，

大概是288行

```
lang/python36 \
```

我把他改成

lang/python38 \

2023-2-28 22:50:54  编译完成

然后再执行一遍编译，由于没有修改，大概1分钟的样子完成。