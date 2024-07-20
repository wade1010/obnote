### 编译

ARGS="-DCMAKE_BUILD_TYPE=Release -DWITH_TESTS=OFF -DWITH_MGR_DASHBOARD_FRONTEND=OFF -DWITH_SYSTEM_BOOST=ON" ./do_cmake.sh

cmake --build ./build/ --target rbd-mirror  发现失败

vim src/tools/rbd/CMakeLists.txt

vim src/rbd_fuse/CMakeLists.txt

vim src/tools/rbd_mirror/CMakeLists.txt

vim src/tools/rbd_nbd/CMakeLists.txt

vim src/librbd/CMakeLists.txt

vim src/tools/rbd-replay-prep/CMakeLists.txt    ceph16.2.9没有

在上面文件里面的target_link_libraries里面适当位置添加上atomic

再次执行cmake --build ./build/ --target rbd-mirror  发现成功

test

```
vim src/test/rbd_mirror/CMakeLists.txt
vim  src/test/librbd/CMakeLists.txt   2处
最好所有包含librados的 target_link_libraries 里面都加下
```

之前编译失败的boost1.73，在/usr/local/lib下面会有好多相关的目录

就算你安装了boost1.78，在do_cmake.sh的时候还是去/usr/local/lib下面找，所以需要删除该目录下所有boost相关的，然后再do_cmake就行了

中途报了一个python包相关的问题

我这里直接pip install markupsafe==1.1.1 解决了

后面 make -j $(nproc) 就解决了

### deb包

vim  debian/control

注释掉

```
#               valgrind,
```

在106行

vim debian/rules

```
ifneq (,$(filter $(DEB_HOST_ARCH), arm armel armhf arm64 i386 amd64 mips
 mipsel powerpc ppc64))
    extraopts += -DWITH_SYSTEM_BOOST=OFF
else
    extraopts += -DWITH_SYSTEM_BOOST=ON
endif
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354539.jpg)

删掉 

```
    dh_strip -pceph-test --dbg-package=ceph-test-dbg
```

##### 开始打包

**time dpkg-buildpackage -b -uc -us -j$(nproc)**