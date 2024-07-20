```
wget -q -O- '
```

echo deb [https://download.ceph.com/debian-pacific/](https://download.ceph.com/debian-pacific/) $(lsb_release -sc) main | sudo tee /etc/apt/sources.list.d/ceph.list

sudo vim  /etc/apt/sources.list 替换为下面内容

deb [https://mirrors.tuna.tsinghua.edu.cn/ubuntu/](https://mirrors.tuna.tsinghua.edu.cn/ubuntu/) focal main restricted universe multiverse

# deb-src [https://mirrors.tuna.tsinghua.edu.cn/ubuntu/](https://mirrors.tuna.tsinghua.edu.cn/ubuntu/) focal main restricted universe multiverse

deb [https://mirrors.tuna.tsinghua.edu.cn/ubuntu/](https://mirrors.tuna.tsinghua.edu.cn/ubuntu/) focal-updates main restricted universe multiverse

# deb-src [https://mirrors.tuna.tsinghua.edu.cn/ubuntu/](https://mirrors.tuna.tsinghua.edu.cn/ubuntu/) focal-updates main restricted universe multiverse

deb [https://mirrors.tuna.tsinghua.edu.cn/ubuntu/](https://mirrors.tuna.tsinghua.edu.cn/ubuntu/) focal-backports main restricted universe multiverse

# deb-src [https://mirrors.tuna.tsinghua.edu.cn/ubuntu/](https://mirrors.tuna.tsinghua.edu.cn/ubuntu/) focal-backports main restricted universe multiverse

deb [https://mirrors.tuna.tsinghua.edu.cn/ubuntu/](https://mirrors.tuna.tsinghua.edu.cn/ubuntu/) focal-security main restricted universe multiverse

# deb-src [https://mirrors.tuna.tsinghua.edu.cn/ubuntu/](https://mirrors.tuna.tsinghua.edu.cn/ubuntu/) focal-security main restricted universe multiverse

sudo apt update

sudo apt upgrade

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

cd ceph

./install-deps.sh

sudo apt install jq

sudo apt install ceph-common

cmake 参数 -DCMAKE_BUILD_TYPE=Debug -DWITH_TESTS=OFF     关掉测试，因为有如下报错

```
[ 98%] Linking CXX executable ../../bin/ceph_test_cls_rgw_meta
/usr/bin/ld: ../../lib/librgw_a.a(rgw_sync_module.cc.o): in function `RGWElasticSyncModule::RGWElasticSyncModule()':
/home/runner/workspace/ceph-16.2.9/src/rgw/rgw_sync_module_es.h:38: undefined reference to `vtable for RGWElasticSyncModule'
collect2: error: ld returned 1 exit status
make[2]: *** [src/test/CMakeFiles/ceph_test_cls_rgw_meta.dir/build.make:150: bin/ceph_test_cls_rgw_meta] Error 1
make[1]: *** [CMakeFiles/Makefile2:9937: src/test/CMakeFiles/ceph_test_cls_rgw_meta.dir/all] Error 2
make[1]: *** Waiting for unfinished jobs....
[ 98%] Built target rgw




/usr/bin/ld: ../../../lib/librgw_a.a(rgw_sync_module.cc.o): in function `RGWElasticSyncModule::RGWElasticSyncModule()':
/home/runner/workspace/ceph-16.2.9/src/rgw/rgw_sync_module_es.h:38: undefined reference to `vtable for RGWElasticSyncModule'
collect2: error: ld returned 1 exit status
make[2]: *** [src/tools/ceph-dencoder/CMakeFiles/ceph-dencoder.dir/build.make:322: bin/ceph-dencoder] Error 1
make[1]: *** [CMakeFiles/Makefile2:9285: src/tools/ceph-dencoder/CMakeFiles/ceph-dencoder.dir/all] Error 2
make: *** [Makefile:146: all] Error 2
```

 cd /home/xxx/workspace/ceph-16.2.9/cmake-build-debug/src/pybind/mgr/dashboard/node-env/bin

sudo cp node /usr/bin

sudo ./npm install -g @angular/[cli@12.2.13](http://cli@12.2.13)      版本可以从/src/pybind/mgr/dashboard/frontend/package.json 中查看

sudo rm -rf /usr/bin/node



```
110  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 3
  111  cd /home/runner/workspace/ceph-16.2.9/src/pybind/mgr/dashboard/frontend
  112  node cd --env --pre && ng build --localize "--progress=false"
  113  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 3
  114  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 1
  115  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  116  ng
  117  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 1
  118  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  119  /home/runner/workspace/ceph-16.2.9/src/pybind/mgr/dashboard/frontend
  120  ll
  121  vim  /home/runner/.npm/_logs/2023-04-08T04_51_38_928Z-debug.log
  122  /home/runner/workspace/ceph-16.2.9/cmake-build-debug/src/pybind/mgr/dashboard/node-env/bin/npm
  123  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 1
  124  cd /home/runner/workspace/ceph-16.2.9/cmake-build-debug/src/pybind/mgr/dashboard/node-env/bin
  125  ll
  126  npm
  127  ./npm
  128  ./npm -v
  129  cd ../lib/node_modules/npm/bin
  130  ll
  131  ./npm-cli.js
  132  cd -
  133  ll
  134  ./node
  135  cp node /usr/bin
  136  sudo cp node /usr/bin
  137  node -v
  138  ./npm -v
  139  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  140  ./npm install -g @angular/cli
  141  export https_proxy=http://192.168.1.128:7890;export http_proxy=http://192.168.1.128:7890;export all_proxy=socks5://192.168.1.128:7890
  142  ./npm install -g @angular/cli
  143  sudo ./npm install -g @angular/cli
  144  export https_proxy=http://192.168.1.128:7890;export http_proxy=http://192.168.1.128:7890;export all_proxy=socks5://192.168.1.128:7890
  145  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  146  j ceph
  147  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  148  node -v
  149  rm -rf /usr/bin/node
  150  sudo rm -rf /usr/bin/node
  151  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  152  ll
  153  no -v
  154  node -v
  155  ./node -v
  156  ./node update
  157  ./node upgrade node
  158  ./nodeenv -v
  159  ./node -h
  160  ./npm -v
  161  ll
  162  apt install node
  163  sudo apt install node
  164  sudo apt install npm
  165  node -v
  166  npm -h
  167  npm update node
  168  npm update node -f
  169  npm update node --force
  170  node -v
  171  npm -v
  172  sudo apt remove npm
  173  node -v
  174  npm -v
  175  sudo apt remove node
  176  npm -v
  177  node -v
  178  which node
  179  rm -rf /usr/bin/node
  180  sudo rm -rf /usr/bin/node
  181  ll
  182  export https_proxy=http://192.168.1.128:7890;export http_proxy=http://192.168.1.128:7890;export all_proxy=socks5://192.168.1.128:7890
  183  wget https://nodejs.org/dist/v18.15.0/node-v18.15.0-linux-x64.tar.xz
  184  ll
  185  tar zxf node-v18.15.0-linux-x64.tar.xz
  186  tar xf node-v18.15.0-linux-x64.tar.xz
  187  ll
  188  cd node-v18.15.0-linux-x64
  189  ll
  190  cd bin
  191  ll
  192  ./node -v
  193  ./npm -v
  194  sudo cp node /usr/bin
  195  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  196  sudo rm -rf /usr/bin/node
  197  ll
  198  cp node ../../
  199  cd ../../
  200  ll
  201  ./node -v
  202  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  203  sudo ./npm remove -g @angular/cli
  204  ll
  205  cd ../lib/node_modules/
  206  ll
  207  cd npm
  208  ll
  209  cd bin
  210  ll
  211  ./npm -v
  212  cd -
  213  ll
  214  cd ..
  215  ll
  216  cd ..
  217  ll
  218  cd ..
  219  ll
  220  cd ..
  221  ll
  222  cd -
  223  ll
  224  cd bin
  225  ll
  226  rm -rf node-v18.15.0-linux-x64
  227  rm -rf node-v18.15.0-linux-x64.tar.xz
  228  ll
  229  sudo cp node /usr/bin
  230  sudo ./npm remove -g @angular/cli
  231  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  232  ll
  233  wget https://nodejs.org/dist/v14.20.0/node-v14.20.0-linux-x64.tar.gz
  234  tar xf node-v14.20.0-linux-x64.tar.gz
  235  cd node-v14.20.0-linux-x64
  236  cp node ../../
  237  cd bin
  238  ll
  239  cp node ../../
  240  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2
  241  ll
  242  ./npm -v
  243  sudo ./npm install -g @angular/cli
  244  /home/runner/clion-2022.3.3/bin/cmake/linux/x64/bin/cmake --build /home/runner/workspace/ceph-16.2.9/cmake-build-debug --target all -- -j 2

```

357  cd /usr/bin

358  ll python*

359  sudo rm -rf python python-config

360  sudo ln -s python3 python

361  sudo ln -s python3-config python-config

363  python -V