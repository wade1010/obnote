申威相关部门用的统信比麒麟多

### 先安装pip

1、默认python命令启动python 3.7:

rm -f /usr/bin/python

ln /usr/bin/python3.7 /usr/bin/python

wget [https://bootstrap.pypa.io/get-pip.py](https://bootstrap.pypa.io/get-pip.py)

python get-pip.py

我这里报错，解决如下

apt-get install python3-distutils

继续python get-pip.py

安装OK

### 国内源

mkdir ~/.pip

vim ~/.pip/pip.conf

```
[global]
index-url = http://pypi.douban.com/simple

trusted-host=pypi.douban.com
```

### 修改install-deps.sh脚本

```
UOS)
    echo "UOS!"
    ;;
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355161.jpg)

--exists-action选项允许您在包已经存在的情况下指定pip的行为。默认情况下，如果pip尝试安装一个已经存在的包，它将会忽略这个包并继续安装其他包。但是，有时候您可能希望pip在遇到已经存在的包时停止安装，并给出相应的提示。在这种情况下，您可以使用--exists-action选项来控制pip的行为。该选项有三个参数可用：

- s：跳过已经存在的包。

- i：在安装之前给出警告，然后询问是否覆盖已经存在的包。

- w：在安装之前给出警告，然后询问是否升级已经存在的包。

vim install-deps.sh  改成--exists-action s    跳过已存在的包

增加  -v 打印详细信息

### 开始尝试

./install-deps.sh

发现一直卡在这里如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355412.jpg)

python3-saml是需要依赖xmlsec的，可以单独安装xmlsec看看为什么慢，如下

```
pip install xmlsec==1.3.13 -v
```

> Python3-saml是一个用于处理SAML（


卡在编译lxml上，这个python3-saml是dashboard需要的，先删除，后面再说

vim src/pybind/mgr/dashboard/constraints.txt

删除python3-saml==1.4.1

vim src/pybind/mgr/dashboard/requirements-extra.txt

删除python3-saml

等instal全部成功后，在单独执行pip install --no-deps python3-saml==1.4.1 

time ./install-deps.sh

后面应该卡在这里

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355893.jpg)

主要是里面的cryptography包，而这个包通过下图，知道是pyopenssl需要的

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355927.jpg)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355071.jpg)

上图就是报错，现在较新版本需要用到rust编译器

同之前python3-saml==1.4.1 处理一样，先删除，然后再单独安装

vim src/pybind/mgr/requirements.txt  删除里面的pyOpenSSL

vim src/pybind/mgr/dashboard/requirements.txt 删除里面的pyopenssl

接下来可能遇到mkcodes.git#egg=mkcodes相关的，如下图，我的解决方法也是删除

vim src/pybind/mgr/rook/rook-client-python/requirements.txt 删掉 -e git+[https://github.com/ryneeverett/mkcodes.git#egg=mkcodes](https://github.com/ryneeverett/mkcodes.git#egg=mkcodes)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355732.jpg)

### install-deps.sh成功

继续尝试 time ./install-deps.sh

这次成功了，耗时7分钟

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355275.jpg)

### 生成cmake

```
ARGS="-DCMAKE_BUILD_TYPE=Release -DWITH_TESTS=OFF -DWITH_MGR_DASHBOARD_FRONTEND=OFF  ‐DWITH_BOOST_CONTEXT=OFF" ./do_cmake.sh
```

### 编译

cd build 

clear

time make -j $(nproc)

编译boost的时候会出现如下错误，但是不影响整体编译 error: No best alternative for libs/context/build/asm_sources

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355968.jpg)

### 编译失败

大概20分钟后报错

![](D:/download/youdaonote-pull-master/data/Technology/存储/Ceph/申威统信适配/images/WEBRESOURCE68ae5da217b015a966ff4ac730b42d22截图.png)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355294.jpg)

根据上面图片做出尝试如下

删掉neorados

vim src/tools/CMakeLists.txt 删掉下面内容

```
if(NOT WIN32)
  set(neorados_srcs
      neorados.cc)
  add_executable(neorados ${neorados_srcs})
  target_link_libraries(neorados libneorados spawn fmt::fmt ${CMAKE_DL_LIBS})
  #install(TARGETS neorados DESTINATION bin)
endif()
```

vim src/rgw/CMakeLists.txt

删掉如下内容

```
set(radosgw_es_srcs
  rgw_es_main.cc)
add_executable(radosgw-es ${radosgw_es_srcs})
target_link_libraries(radosgw-es ${rgw_libs} librados
  cls_rgw_client cls_otp_client cls_lock_client cls_refcount_client
  cls_log_client cls_timeindex_client
  cls_version_client cls_user_client
  global ${FCGI_LIBRARY} ${LIB_RESOLV}
  ${CURL_LIBRARIES} ${EXPAT_LIBRARIES} ${BLKID_LIBRARIES})
install(TARGETS radosgw-es DESTINATION bin)
```

cd build 

cmake --build . --target radosgw-admin -- -j64

cmake --build . --target radosgw-object-expirer -- -j64

都是报错

### 尝试直接打deb包

cd 到ceph源码根目录

vim debian/rules

添加如下内容

extraopts += ‐DWITH_BOOST_CONTEXT=OFF

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355910.jpg)

vim debian/control 删除valgrind依赖

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355260.jpg)

time dpkg-buildpackage -b -uc -us -j$(nproc)

也是失败

pip install bcrypt==3.1.4

pip install cryptography==2.8

pip install coverage==4.5.1

pip install pyopenssl==19.0.0

再就是根据install-deps这个脚本报的错不断地处理错，知道这个脚本成功执行

编译xmlsec的时候有点慢 估计6-7分钟

![](https://gitee.com/hxc8/images6/raw/master/img/202407182355839.jpg)

有一个之前没见过的错

```
THESE PACKAGES DO NOT MATCH THE HASHES FROM THE REQUIREMENTS FILE. If you have updated the package versions, please update the hashes. Otherwise, examine the package contents carefully; someone may have tampered with them.
    pytest-cov==2.7.1 from http://pypi.doubanio.com/packages/84/7b/73f8522619d1cbb22b9a36f9c54bc5ce5e24648e53cc1bf566477d2d1f2b/pytest_cov-2.7.1-py2.py3-none-any.whl#sha256=2b097cde81a302e1047331b48cadacf23577e431b61e9c6f49a1170bbe3d3da6 (from -r requirements.txt (line 1)):
        Expected sha256 2b097cde81a302e1047331b48cadacf23577e431b61e9c6f49a1170bbe3d3da6
             Got        e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855

```

解决如下

```
pip install pytest-cov --no-cache-dir
```

Unmet build dependencies: build-essential:native cython3 default-jdk dh-exec golang javahelper junit4 libaio-dev libbabeltrace-ctf-dev libbabeltrace-dev libblkid-dev (>= 2.17) libcryptsetup-dev libcap-ng-dev libcunit1-dev libcurl4-openssl-dev libfuse-dev libibverbs-dev librdmacm-dev libkeyutils-dev libldap2-dev libleveldb-dev liblttng-ust-dev liblua5.3-dev liblz4-dev (>= 0.0~r131) libncurses-dev liboath-dev libsnappy-dev libsqlite3-dev libudev-dev libnl-genl-3-dev librabbitmq-dev librdkafka-dev luarocks python3-all-dev python3-cherrypy3 uuid-runtime valgrind xfslibs-dev

没有valgrind,所以不安装

```
apt install -y build-essential cython3 default-jdk dh-exec golang javahelper junit4 libaio-dev libbabeltrace-ctf-dev libbabeltrace-dev libblkid-dev libcryptsetup-dev libcap-ng-dev libcunit1-dev libcurl4-openssl-dev libfuse-dev libibverbs-dev librdmacm-dev libkeyutils-dev libldap2-dev libleveldb-dev liblttng-ust-dev liblua5.3-dev liblz4-dev libncurses-dev liboath-dev libsnappy-dev libsqlite3-dev libudev-dev libnl-genl-3-dev librabbitmq-dev librdkafka-dev luarocks python3-all-dev python3-cherrypy3 uuid-runtime  xfslibs-dev
```

apt‐get install google‐perftools3 apt‐get install libgoogle‐perftools‐dev

apt-get install g++

./bootstrap.sh  --with-libraries=atomic,chrono,container,context,coroutine,date_time,filesystem,iostreams,program_options,python,random,regex,system,thread

ARGS="-DCMAKE_BUILD_TYPE=Release -DWITH_TESTS=OFF -DWITH_MGR_DASHBOARD_FRONTEND=OFF  ‐DWITH_BOOST_CONTEXT=OFF" ./do_cmake.sh

WITH_BOOST_CONTEXT