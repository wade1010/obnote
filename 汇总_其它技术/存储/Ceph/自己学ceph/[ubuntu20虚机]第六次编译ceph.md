开始使用ubuntu18的，发现如下问题

./install-deps.sh

执行会报错

```
。。。。。。。。
Setting up ceph-libboost-coroutine1.75-dev:amd64 (1.75.0-1.1) ...
Unknown option: build-profiles
Usage:
    mk-build-deps --help|--version

    mk-build-deps [options] control file | package name ...

```

这个mk-build-deps 在ubuntu18好像支持的版本就是比较旧,没有--build-profiles的选项，折腾了一会不弄了，直接用ubuntu20

安装后，修改语言为中文，就能打开terminal了

su 

apt install vim -y

chmod +w /etc/sudoers

vim /etc/sudoers

```
root ALL=(ALL:ALL) ALL
ceph ALL=(ALL:ALL) ALL
```

chmod -w /etc/sudoers

切回普通用户

sudo apt install devscripts -y

mk-build-deps -h  发现有build-profiles选项了

其实也可以不安装，脚本会安装，这里只是说明下，脚本本里面对应的应该是比较新的mk-build-deps，而ubuntu18里面是比较旧的

设置共享剪贴板，安装增强功能并选择双向共享剪贴板

cd && mkdir workspace && cd workspace

wget [https://download.ceph.com/tarballs/ceph-17.2.5.tar.gz](https://download.ceph.com/tarballs/ceph-17.2.5.tar.gz) && tar zxf ceph-17.2.5.tar.gz && mv ceph-17.2.5 ceph &&cd ceph

sudo apt install -y curl

sudo vim /etc/apt/sources.list

```
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ focal main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ focal-updates main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ focal-backports main restricted universe multiverse
deb http://mirrors.tuna.tsinghua.edu.cn/ubuntu/ focal-security main restricted universe multiverse
```

sudo apt-get update

sudo apt-get upgrade

sudo apt-get install --reinstall ca-certificates  

./install-deps.sh   //如果报错，是不是可以看看[https://docs.ceph.com/en/latest/start/documenting-ceph/#build-the-source-first-time](https://docs.ceph.com/en/latest/start/documenting-ceph/#build-the-source-first-time)

我这里报了一次错

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359084.jpg)

重新再执行一遍./install-deps.sh 这次OK

如果是python下载那一步慢或者失败，应该是python pip没有配置国内源

我在ceph这个用户和root用户都配置了

```
cd
mkdir .pip
vim .pip/pip.conf
```

内容如下：

```
[global]
index-url = https://mirrors.aliyun.com/pypi/simple/
[install]
trusted-host=mirrors.aliyun.com
```

然后保存，最后再次执行 ./install-deps.sh

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359710.jpg)

```
./do_cmake.sh
cd build
ninja
```

2023-3-4 11:58:49

2023-3-4 14:30:03发现好像不动了，看ninja进程也不占内存和cpu

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359340.jpg)

再执行一遍ninja，打开top发现是node比较占资源

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359743.jpg)

后来我发现又慢慢的没啥动静了貌似卡着不懂了，我就看了下进程，是在执行一条如下命令，

/bin/sh -c cd /home/ceph/workspace/ceph/src/pybind/mgr/dashboard/frontend && . /home/ceph/workspace/ceph/build/src/pybind/mgr/dashboard/frontend/node-env/bin/activate && CYPRESS_CACHE_FOLDER=/home/ceph/workspace/ceph/build/src/pybind/mgr/dashboard/cypress NG_CLI_ANALYTICS=false npm ci -f --userconfig /home/ceph/workspace/ceph/build/src/pybind/mgr/dashboard/frontend/node-env/.npmrc && deactivate

然后我停止ninja，单独执行了上述命令，发现是下载Cypress的时候慢的很

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359213.jpg)

尝试把这个npm的源改成国内源(build是你ceph的根目录下)

cd build/src/pybind/mgr/dashboard/frontend/node-env/bin

主要步骤是下图红色

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359696.jpg)

先验证下速度，再次单独执行上面一大串命令

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359986.jpg)

好像也不咋地快！

尝试ubuntu开启科学上网 [ubuntu开启科学上网](note://WEBd8c5a530f89a106fad3847bf282718bf)

感觉确实快了点。

cd /home/ceph/workspace/ceph/src/pybind/mgr/dashboard/frontend

/bin/sh -c cd /home/ceph/workspace/ceph/src/pybind/mgr/dashboard/frontend && . /home/ceph/workspace/ceph/build/src/pybind/mgr/dashboard/frontend/node-env/bin/activate && CYPRESS_CACHE_FOLDER=/home/ceph/workspace/ceph/build/src/pybind/mgr/dashboard/cypress NG_CLI_ANALYTICS=false npm ci -f --userconfig /home/ceph/workspace/ceph/build/src/pybind/mgr/dashboard/frontend/node-env/.npmrc && deactivate

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359359.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359520.jpg)

执行完毕，我再重新到build目录执行 ninja -j14  (其实上面步骤可以不执行，我这里就是验证单步能不能完成)

```
ceph@ceph:~/workspace/ceph/build$ ninja -j14
[2/3] dashboard frontend dependencies are being installed
。。。。。。
Compiling ng-bullet : es2015 as esm2015
Compiling ng-click-outside : module as esm5
Compiling ng2-charts : es2015 as esm2015
Compiling ngx-pipe-function : es2015 as esm2015
Compiling ngx-toastr : es2015 as esm2015
Compiling simplebar-angular : es2015 as esm2015
added 3053 packages in 55.51s

```

可以发现确实可以了

过一会ninja就全部完成了，支持编译也算是完成了。真的不容易

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359477.jpg)

../src/vstart.sh --debug --new -x --localhost --bluestore

```
/home/ceph/workspace/ceph/build/bin/init-ceph: ceph conf ./ceph.conf not found; system is not configured.
** going verbose **
rm -f core* 
ip 127.0.0.1
port 40596

NOTE: hostname resolves to loopback; remote hosts will not be able to
  connect.  either adjust /etc/hosts, or edit this script to use your
  machine's real IP.

/home/ceph/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mon. /home/ceph/workspace/ceph/build/keyring --cap mon 'allow *' 
creating /home/ceph/workspace/ceph/build/keyring
/home/ceph/workspace/ceph/build/bin/ceph-authtool --gen-key --name=client.admin --cap mon 'allow *' --cap osd 'allow *' --cap mds 'allow *' --cap mgr 'allow *' /home/ceph/workspace/ceph/build/keyring 
/home/ceph/workspace/ceph/build/bin/monmaptool --create --clobber --addv a [v2:127.0.0.1:40596,v1:127.0.0.1:40597] --addv b [v2:127.0.0.1:40598,v1:127.0.0.1:40599] --addv c [v2:127.0.0.1:40600,v1:127.0.0.1:40601] --print /tmp/ceph_monmap.18138 
/home/ceph/workspace/ceph/build/bin/monmaptool: monmap file /tmp/ceph_monmap.18138
/home/ceph/workspace/ceph/build/bin/monmaptool: generated fsid c6a2f57e-69b1-43af-9caa-6dfd1d804f9d
setting min_mon_release = octopus
epoch 0
fsid c6a2f57e-69b1-43af-9caa-6dfd1d804f9d
last_changed 2023-03-04T20:46:15.033869+0800
created 2023-03-04T20:46:15.033869+0800
min_mon_release 15 (octopus)
election_strategy: 1
0: [v2:127.0.0.1:40596/0,v1:127.0.0.1:40597/0] mon.a
1: [v2:127.0.0.1:40598/0,v1:127.0.0.1:40599/0] mon.b
2: [v2:127.0.0.1:40600/0,v1:127.0.0.1:40601/0] mon.c
/home/ceph/workspace/ceph/build/bin/monmaptool: writing epoch 0 to /tmp/ceph_monmap.18138 (3 monitors)
rm -rf -- /home/ceph/workspace/ceph/build/dev/mon.a 
mkdir -p /home/ceph/workspace/ceph/build/dev/mon.a 
/home/ceph/workspace/ceph/build/bin/ceph-mon --mkfs -c /home/ceph/workspace/ceph/build/ceph.conf -i a --monmap=/tmp/ceph_monmap.18138 --keyring=/home/ceph/workspace/ceph/build/keyring 
rm -rf -- /home/ceph/workspace/ceph/build/dev/mon.b 
mkdir -p /home/ceph/workspace/ceph/build/dev/mon.b 
/home/ceph/workspace/ceph/build/bin/ceph-mon --mkfs -c /home/ceph/workspace/ceph/build/ceph.conf -i b --monmap=/tmp/ceph_monmap.18138 --keyring=/home/ceph/workspace/ceph/build/keyring 
rm -rf -- /home/ceph/workspace/ceph/build/dev/mon.c 
mkdir -p /home/ceph/workspace/ceph/build/dev/mon.c 
/home/ceph/workspace/ceph/build/bin/ceph-mon --mkfs -c /home/ceph/workspace/ceph/build/ceph.conf -i c --monmap=/tmp/ceph_monmap.18138 --keyring=/home/ceph/workspace/ceph/build/keyring 
rm -- /tmp/ceph_monmap.18138 
/home/ceph/workspace/ceph/build/bin/ceph-mon -i a -c /home/ceph/workspace/ceph/build/ceph.conf 
/home/ceph/workspace/ceph/build/bin/ceph-mon -i b -c /home/ceph/workspace/ceph/build/ceph.conf 
/home/ceph/workspace/ceph/build/bin/ceph-mon -i c -c /home/ceph/workspace/ceph/build/ceph.conf 
Populating config ...

[mgr]
	mgr/telemetry/enable = false
	mgr/telemetry/nag = false
Setting debug configs ...
creating /home/ceph/workspace/ceph/build/dev/mgr.x/keyring
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -i /home/ceph/workspace/ceph/build/dev/mgr.x/keyring auth add mgr.x mon 'allow profile mgr' mds 'allow *' osd 'allow *' 
added key for mgr.x
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring config set mgr mgr/dashboard/x/ssl_server_port 41596 --force 
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring config set mgr mgr/prometheus/x/server_port 9283 --force 
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring config set mgr mgr/restful/x/server_port 42596 --force 
Starting mgr.x
/home/ceph/workspace/ceph/build/bin/ceph-mgr -i x -c /home/ceph/workspace/ceph/build/ceph.conf 
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h 
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h 
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h 
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h 
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h 
waiting for mgr dashboard module to start
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -h 
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring dashboard ac-user-create admin -i /home/ceph/workspace/ceph/build/dashboard-admin-secret.txt administrator --force-password 
{"username": "admin", "password": "$2b$12$5a23vpQig1tQjFpi3jv6Qup6mrffwIM5Kw1LJjee4KtoS4XRDxyT2", "roles": ["administrator"], "name": null, "email": null, "lastUpdate": 1677933990, "enabled": true, "pwdExpirationDate": null, "pwdUpdateRequired": false}
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring dashboard create-self-signed-cert 
Self-signed certificate created
add osd0 03920ede-ae63-4d9f-8539-93e8ff8e5e5a
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring osd new 03920ede-ae63-4d9f-8539-93e8ff8e5e5a -i /home/ceph/workspace/ceph/build/dev/osd0/new.json 
0
/home/ceph/workspace/ceph/build/bin/ceph-osd -i 0 -c /home/ceph/workspace/ceph/build/ceph.conf --mkfs --key AQCnPQNkyEPrEhAAPo2khPaarsFuDgTaaEK6Xw== --osd-uuid 03920ede-ae63-4d9f-8539-93e8ff8e5e5a 
2023-03-04T20:46:31.797+0800 7ff354f18240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd0/block: (2) No such file or directory
2023-03-04T20:46:31.797+0800 7ff354f18240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd0/block: (2) No such file or directory
2023-03-04T20:46:31.797+0800 7ff354f18240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd0/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd0/block: (2) No such file or directory
2023-03-04T20:46:31.805+0800 7ff354f18240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd0) _read_fsid unparsable uuid 
start osd.0
/home/ceph/workspace/ceph/build/bin/ceph-osd -i 0 -c /home/ceph/workspace/ceph/build/ceph.conf 
add osd1 d624572d-441a-4de7-824c-00b1f75eb97f
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring osd new d624572d-441a-4de7-824c-00b1f75eb97f -i /home/ceph/workspace/ceph/build/dev/osd1/new.json 
1
/home/ceph/workspace/ceph/build/bin/ceph-osd -i 1 -c /home/ceph/workspace/ceph/build/ceph.conf --mkfs --key AQCtPQNkrSAaLxAA6vUqcwPdiibDSS96j12uOg== --osd-uuid d624572d-441a-4de7-824c-00b1f75eb97f 
2023-03-04T20:46:38.261+0800 7fa837eef240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd1/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd1/block: (2) No such file or directory
2023-03-04T20:46:38.261+0800 7fa837eef240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd1/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd1/block: (2) No such file or directory
2023-03-04T20:46:38.261+0800 7fa837eef240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd1/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd1/block: (2) No such file or directory
2023-03-04T20:46:38.265+0800 7fa837eef240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd1) _read_fsid unparsable uuid 
2023-03-04T20:46:38.904+0800 7fd14b465240 -1 Falling back to public interface
2023-03-04T20:46:41.688+0800 7fd14b465240 -1 osd.0 0 log_to_monitors true
start osd.1
/home/ceph/workspace/ceph/build/bin/ceph-osd -i 1 -c /home/ceph/workspace/ceph/build/ceph.conf 
add osd2 dff19b4e-84b3-4eb6-b877-73b4bd5131b6
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring osd new dff19b4e-84b3-4eb6-b877-73b4bd5131b6 -i /home/ceph/workspace/ceph/build/dev/osd2/new.json 
2
/home/ceph/workspace/ceph/build/bin/ceph-osd -i 2 -c /home/ceph/workspace/ceph/build/ceph.conf --mkfs --key AQC0PQNk8eoPIhAAVDQ/NvMQAXPRdlNjdF0tuA== --osd-uuid dff19b4e-84b3-4eb6-b877-73b4bd5131b6 
2023-03-04T20:46:45.148+0800 7f6602943240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd2/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd2/block: (2) No such file or directory
2023-03-04T20:46:45.148+0800 7f6602943240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd2/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd2/block: (2) No such file or directory
2023-03-04T20:46:45.148+0800 7f6602943240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd2/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd2/block: (2) No such file or directory
2023-03-04T20:46:45.160+0800 7f6602943240 -1 bluestore(/home/ceph/workspace/ceph/build/dev/osd2) _read_fsid unparsable uuid 
2023-03-04T20:46:45.672+0800 7f8959183240 -1 Falling back to public interface
2023-03-04T20:46:47.940+0800 7f8959183240 -1 osd.1 0 log_to_monitors true
start osd.2
/home/ceph/workspace/ceph/build/bin/ceph-osd -i 2 -c /home/ceph/workspace/ceph/build/ceph.conf 
2023-03-04T20:46:52.068+0800 7f8956941700 -1 osd.1 0 waiting for initial osdmap2023-03-04T20:46:52.704+0800 7f676a47f240 -1 Falling back to public interface
2023-03-04T20:46:55.299+0800 7f676a47f240 -1 osd.2 0 log_to_monitors true
OSDs started
mkdir -p /home/ceph/workspace/ceph/build/dev/mds.a 
/home/ceph/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mds.a /home/ceph/workspace/ceph/build/dev/mds.a/keyring 
creating /home/ceph/workspace/ceph/build/dev/mds.a/keyring
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -i /home/ceph/workspace/ceph/build/dev/mds.a/keyring auth add mds.a mon 'allow profile mds' osd 'allow rw tag cephfs *=*' mds allow mgr 'allow profile mds' 
added key for mds.a
/home/ceph/workspace/ceph/build/bin/ceph-mds -i a -c /home/ceph/workspace/ceph/build/ceph.conf 
starting mds.a at 
mkdir -p /home/ceph/workspace/ceph/build/dev/mds.b 
/home/ceph/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mds.b /home/ceph/workspace/ceph/build/dev/mds.b/keyring 
creating /home/ceph/workspace/ceph/build/dev/mds.b/keyring
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -i /home/ceph/workspace/ceph/build/dev/mds.b/keyring auth add mds.b mon 'allow profile mds' osd 'allow rw tag cephfs *=*' mds allow mgr 'allow profile mds' 
added key for mds.b
/home/ceph/workspace/ceph/build/bin/ceph-mds -i b -c /home/ceph/workspace/ceph/build/ceph.conf 
starting mds.b at 
mkdir -p /home/ceph/workspace/ceph/build/dev/mds.c 
/home/ceph/workspace/ceph/build/bin/ceph-authtool --create-keyring --gen-key --name=mds.c /home/ceph/workspace/ceph/build/dev/mds.c/keyring 
creating /home/ceph/workspace/ceph/build/dev/mds.c/keyring
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring -i /home/ceph/workspace/ceph/build/dev/mds.c/keyring auth add mds.c mon 'allow profile mds' osd 'allow rw tag cephfs *=*' mds allow mgr 'allow profile mds' 
added key for mds.c
/home/ceph/workspace/ceph/build/bin/ceph-mds -i c -c /home/ceph/workspace/ceph/build/ceph.conf 
starting mds.c at 
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring fs volume ls 
[]
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring fs volume create a 
Volume created successfully (no MDS daemons created)
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring fs authorize a client.fs_a / rwp 
/home/ceph/workspace/ceph/build/bin/ceph -c /home/ceph/workspace/ceph/build/ceph.conf -k /home/ceph/workspace/ceph/build/keyring fs authorize * client.fs / rwp 

vstart cluster complete. Use stop.sh to stop. See out/* (e.g. 'tail -f out/????') for debug output.

dashboard urls: https://127.0.0.1:41596
  w/ user/pass: admin / admin

export PYTHONPATH=/home/ceph/workspace/ceph/src/pybind:/home/ceph/workspace/ceph/build/lib/cython_modules/lib.3:/home/ceph/workspace/ceph/src/python-common:$PYTHONPATH
export LD_LIBRARY_PATH=/home/ceph/workspace/ceph/build/lib:$LD_LIBRARY_PATH
export PATH=/home/ceph/workspace/ceph/build/bin:$PATH
alias cephfs-shell=/home/ceph/workspace/ceph/src/tools/cephfs/cephfs-shell
CEPH_DEV=1
```

./bin/ceph -s

```
ceph@ceph:~/workspace/ceph/build$ ./bin/ceph -s
*** DEVELOPER MODE: setting PATH, PYTHONPATH and LD_LIBRARY_PATH ***
2023-03-04T20:47:51.756+0800 7f7ae1e88700 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-04T20:47:51.784+0800 7f7ae1e88700 -1 WARNING: all dangerous and experimental features are enabled.
  cluster:
    id:     a2aa673b-bdfb-43f7-8507-7ee90053848f
    health: HEALTH_ERR
            Module 'dashboard' has failed: No module named 'routes'
 
  services:
    mon: 3 daemons, quorum a,b,c (age 90s)
    mgr: x(active, since 83s)
    mds: 1/1 daemons up, 2 standby
    osd: 3 osds: 3 up (since 50s), 3 in (since 67s)
 
  data:
    volumes: 1/1 healthy
    pools:   3 pools, 49 pgs
    objects: 26 objects, 579 KiB
    usage:   3.0 GiB used, 300 GiB / 303 GiB avail
    pgs:     49 active+clean
 
  io:
    client:   255 B/s wr, 0 op/s rd, 0 op/s wr
 
  progress:

```

Module 'dashboard' has failed: No module named 'routes'

../src/stop.sh

ninja vstart        # builds just enough to run vstart

又是一顿折腾。

后来看到[https://tracker.ceph.com/issues/24420](https://tracker.ceph.com/issues/24420)

```
apt-get install -y python3-routes   不是python-routes   这个包是运行时需要的，不是编译时需要的
```

MON=1 OSD=1 MDS=1 ../src/vstart.sh --debug --new -x --localhost --bluestore

上面命令还是会报 

 bluestore(/home/ceph/workspace/ceph/build/dev/osd2/block) _read_bdev_label failed to open /home/ceph/workspace/ceph/build/dev/osd2/block: (2) No such file or directory这种类似的错

但是 bin/ceph -s  从ERR变成WARN，对于测试来说OK的

```
ceph@ceph:~/workspace/ceph/build$ ./bin/ceph -s
*** DEVELOPER MODE: setting PATH, PYTHONPATH and LD_LIBRARY_PATH ***
2023-03-04T22:46:00.795+0800 7f0b438d8700 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-04T22:46:00.839+0800 7f0b438d8700 -1 WARNING: all dangerous and experimental features are enabled.
  cluster:
    id:     08a55a54-1792-4c86-8322-e7be390a1b8f
    health: HEALTH_WARN
            3 pool(s) have no replicas configured
 
  services:
    mon: 1 daemons, quorum a (age 51s)
    mgr: x(active, since 44s)
    mds: 1/1 daemons up
    osd: 1 osds: 1 up (since 26s), 1 in (since 41s)
 
  data:
    volumes: 1/1 healthy
    pools:   3 pools, 3 pgs
    objects: 24 objects, 579 KiB
    usage:   1.0 GiB used, 100 GiB / 101 GiB avail
    pgs:     3 active+clean

```

[](https://127.0.0.1:41721/#/dashboard)[https://127.0.0.1:41721](https://127.0.0.1:41721)  用户 密码 都是admin 这个在启动vstart脚本最后的时候会输出来给我们看

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359556.jpg)

[](https://127.0.0.1:41721/#/dashboard)

但是测试写，不行

```
ceph@ceph:~/workspace/ceph/build$ ./bin/rados -p rbd bench 30 write
2023-03-04T23:05:31.975+0800 7f9c19fa8d00 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-04T23:05:32.007+0800 7f9c19fa8d00 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-04T23:05:32.007+0800 7f9c19fa8d00 -1 WARNING: all dangerous and experimental features are enabled.
error opening pool rbd: (2) No such file or directory

```

尝试自己创建个pool

./bin/ceph osd pool create rbd 128

再执行 ./bin/rados -p rbd bench 30 write 就OK了，只能说github上的步骤，也是人写的，期间他们自己也是折腾挺久的。忘记某些简单步骤也是可能的

```
ceph@ceph:~/workspace/ceph/build$ ./bin/rados -p rbd bench 30 write
2023-03-04T23:11:56.610+0800 7f9b4bc49d00 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-04T23:11:56.662+0800 7f9b4bc49d00 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-04T23:11:56.662+0800 7f9b4bc49d00 -1 WARNING: all dangerous and experimental features are enabled.
hints = 1
Maintaining 16 concurrent writes of 4194304 bytes to objects of size 4194304 for up to 30 seconds or 0 objects
Object prefix: benchmark_data_ceph_21776
  sec Cur ops   started  finished  avg MB/s  cur MB/s last lat(s)  avg lat(s)
    0       0         0         0         0         0           -           0
    1      16        16         0         0         0           -           0
    2      16        31        15   29.8577        30     1.93924     1.60591
    3      16        48        32   42.4942        68     1.24472     1.20964
    4      16        62        46   45.6736        56    0.852632     1.10731
    5      16        75        59   46.9158        52    0.995426     1.14196
    6      16        90        74   49.0821        60    0.477378     1.11069
    7      16       105        89   50.6287        60    0.740828     1.14662
    8      16       116       100   49.8011        44     1.76632     1.15329
    9      16       130       114   50.4257        56     1.07108     1.13998
   10      16       145       129   51.3683        60    0.869747     1.16442
   11      16       159       143   51.7842        56     1.07689     1.15041
   12      16       170       154   51.1309        44     1.08335     1.14867
   13      16       185       169   51.7862        60     3.12424     1.17385
   14      16       196       180    51.199        44     2.63129     1.19195
   15      16       209       193   51.2487        52     2.30092     1.19206
   16      16       220       204   50.7887        44     1.68453     1.20537
   17      16       234       218   51.0892        56     0.62013      1.1892
   18      16       250       234   51.7882        64     1.19892     1.19542
   19      16       265       249   52.2151        60    0.934045     1.18741
2023-03-04T23:12:17.481016+0800 min lat: 0.302025 max lat: 4.64604 avg lat: 1.17215
  sec Cur ops   started  finished  avg MB/s  cur MB/s last lat(s)  avg lat(s)
   20      16       282       266   52.9997        68    0.839291     1.17215
   21      16       298       282   53.5204        64    0.433939     1.15009
   22      16       312       296   53.6324        56     1.62328     1.15732
   23      16       326       310   53.7327        56    0.648093     1.14381
   24      16       336       320   53.1578        40    0.970628     1.15263
   25      16       348       332   52.9506        48     2.14435     1.17158
   26      16       359       343    52.607        44     1.11107     1.17855
   27      16       378       362   53.4691        76    0.638572     1.17398
   28      16       388       372   52.9891        40    0.549214      1.1675
   29      16       401       385   52.9539        52     1.30076      1.1729
   30      16       412       396   52.6528        44     1.02115     1.18274
   31       1       412       411   52.8883        60     2.48946     1.18066
   32       1       412       411   51.2383         0           -     1.18066
   33       1       412       411   49.6845         0           -     1.18066
Total time run:         33.1699
Total writes made:      412
Write size:             4194304
Object size:            4194304
Bandwidth (MB/sec):     49.6837
Stddev Bandwidth:       18.3922
Max bandwidth (MB/sec): 76
Min bandwidth (MB/sec): 0
Average IOPS:           12
Stddev IOPS:            4.61491
Max IOPS:               19
Min IOPS:               0
Average Latency(s):     1.18921
Stddev Latency(s):      0.637908
Max latency(s):         4.70021
Min latency(s):         0.302025
Cleaning up (deleting benchmark objects)
Removed 412 objects
Clean up completed and total clean up time :7.28475
```

```
ceph@ceph:~/workspace/ceph/build$ ./bin/rbd create foo --size 1000
2023-03-04T23:19:34.671+0800 7fa0c50cb340 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-04T23:19:34.671+0800 7fa0c50cb340 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-04T23:19:34.707+0800 7fa0c50cb340 -1 WARNING: all dangerous and experimental features are enabled.
ceph@ceph:~/workspace/ceph/build$ lsblk
NAME   MAJ:MIN RM   SIZE RO TYPE MOUNTPOINT
loop0    7:0    0     4K  1 loop /snap/bare/5
...........
sda      8:0    0   100G  0 disk 
├─sda1   8:1    0   512M  0 part /boot/efi
├─sda2   8:2    0     1K  0 part 
└─sda5   8:5    0  99.5G  0 part /
sdb      8:16   0    50G  0 disk 
sr0     11:0    1  1024M  0 rom
```

真不容易啊。算是简单的走完一个完整步骤！！！！！

PS:附一个默认配置的  ../src/vstart.sh --debug --new -x --localhost --bluestore

```
ceph@ceph:~/workspace/ceph/build$ ./bin/ceph -s
*** DEVELOPER MODE: setting PATH, PYTHONPATH and LD_LIBRARY_PATH ***
2023-03-17T07:45:44.854+0800 7f91dde14700 -1 WARNING: all dangerous and experimental features are enabled.
2023-03-17T07:45:44.902+0800 7f91dde14700 -1 WARNING: all dangerous and experimental features are enabled.
  cluster:
    id:     78489b63-6d86-459b-bbec-b416b965590c
    health: HEALTH_OK
 
  services:
    mon: 3 daemons, quorum a,b,c (age 57s)
    mgr: x(active, since 51s)
    mds: 1/1 daemons up, 2 standby
    osd: 3 osds: 3 up (since 15s), 3 in (since 34s)
 
  data:
    volumes: 1/1 healthy
    pools:   3 pools, 3 pgs
    objects: 23 objects, 579 KiB
    usage:   3.0 GiB used, 300 GiB / 303 GiB avail
    pgs:     3 active+clean
 
  io:
    client:   2.4 KiB/s wr, 0 op/s rd, 8 op/s wr
```

可以跟前面指定数量的对比下