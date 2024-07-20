要查看编译 Ceph 时需要使用的库和软件包，可以使用 Ceph 源代码中的 ceph.spec 文件来查看。

ceph.spec 文件是用于构建 Ceph 软件包的 SPEC 文件，其中包含所有 Ceph 的编译和打包信息，包括编译配置选项、编译时所需的库、依赖包等信息。可以通过查看 ceph.spec 文件来获取 Ceph 编译所需的依赖信息，进而在安装 Ceph 之前安装这些依赖。

下面是查看 Ceph 编译所需库和软件包的步骤：

1. 下载 Ceph 源代码并解压缩。

```
wget 
tar xzf ceph-<version>.tar.gz
cd ceph-<version>

```

1. 在源代码的根目录下，找到 

ceph.spec 文件。

```
ls -l ceph.spec

```

1. 使用文本编辑器打开 

ceph.spec 文件，以查看其中包含的依赖关系和构建配置。

```
vim ceph.spec

```

1. 在 SPEC 文件中，可以找到 

BuildRequires: 和 Requires: 关键字后面跟着的软件包名称，这些是编译和运行 Ceph 所需要的软件包和库。

```
BuildRequires: autoconf
BuildRequires: automake
BuildRequires: bzip2
BuildRequires: cmake >= 2.8.10
BuildRequires: e2fsprogs-devel >= 1.41.2
BuildRequires: gcc-c++
BuildRequires: git
...
Requires: librados2 = %{?epoch:}%{version}-%{release}
Requires: librgw2 = %{?epoch:}%{version}-%{release}
Requires: librbd1 = %{?epoch:}%{version}-%{release}
Requires: libcephfs1 = %{?epoch:}%{version}-%{release}

```

1. 根据需要安装 SPEC 文件中列出的这些软件包和库。

```
apt-get install autoconf automake bzip2 cmake e2fsprogs-dev \
gcc-c++ git librados2 librbd1 librgw2 libcephfs1 ...

```

使用 ceph.spec 文件可以查看 Ceph 所需的依赖关系和构建配置，并安装适当的软件包和库满足这些依赖关系和配置需求。

**BuildRequires:** 和 **Requires:** 是 RPM 包管理器中常见的两个关键字，它们之间的区别在于：

- BuildRequires: 指定构建当前软件包时所需要的依赖包列表，而 

Requires: 指定在安装和运行软件包时需要的依赖包列表。

- BuildRequires: 列表中的依赖关系只有在构建软件包时才会被考虑，而 

Requires: 列表中的依赖关系在安装、升级或运行软件包时都需要被满足。

- BuildRequires: 列表中的依赖关系通常包含一些构建前的工具和库，例如编译器、头文件、构建工具、开发库等；而 

Requires: 列表中的依赖关系通常包含一些运行和使用软件包所需的库和服务，例如运行库、配置文件、守护进程、命令行工具等。

在 SPEC 文件中，**BuildRequires:** 和 **Requires:** 都是用来列出依赖的关键字，但它们的作用不同。这两个关键字都可以列出软件包的依赖关系，包括软件包名称、版本号、依赖关系等信息，以便更好地构建、安装和运行软件包。在 RPM 包管理器中，只有当所有的依赖关系都被正确满足，软件包才能被成功构建、安装和运行。因此，正确处理这些依赖关系是 RPM 包管理器中的一个重要工作。

要将 Ceph 移植到申威 CPU 上，您需要明确两个主要方面：申威 CPU 的体系结构和操作系统，以及修改或编写 Ceph SPEC 文件以便支持该体系结构和操作系统。

1. 查看申威CPU的体系结构和操作系统

首先，您需要了解申威 CPU 的体系结构和操作系统类型，然后查看申威 CPU 是否与 Ceph 目标体系结构相同或兼容。

申威 CPU 使用的体系结构是 ARM64，因此您需要查看 Ceph 是否已支持 ARM64 架构。此外，您需要检查操作系统，以确定是否存在匹配的软件包、库和依赖项来构建和运行 Ceph。

1. 修改或编写 Ceph SPEC 文件

如果 Ceph 还未支持申威 CPU 的体系结构和操作系统，则需要进行修改或编写 SPEC 文件以便支持该架构和操作系统。以下是一些示例步骤：

- 找到 

ceph.spec 文件并在开始编写前制作备份。

- 必要时更新以下 RHEL 系统架构的预定义宏，以包括申威CPU的架构：

```
%define _target_cpu %(echo %{__arch} | sed -e "s/i.86/x86/" -e "s/aarch64/arm/" -e "s/ppc64le/powerpc/" -e "s/s390x/s390/")

```

- 检查通过 

grep 命令列出的 BuildRequires 和 Requires 段，并添加或删除必要的软件包和依赖。

- 检查 SPEC 文件中的文件和目录，确保不依赖于目标操作系统中不存在的目录、文件或库，并相应地更改它们。

- 检查 

configure 和 make 命令，修改编译参数以兼容申威 CPU 的体系结构，并将相关的编译器选项添加到 CFLAGS 和 LDFLAGS 中。

- 如果 Ceph 依赖于其他软件库，则需要查看供应商提供的软件包和库是否与申威 CPU 兼容，或者解决库兼容性问题。

这些步骤的具体实现会因体系结构、操作系统和 SPEC 文件的不同而有所不同，但您可以使用这些步骤作为起点来移植 Ceph 到申威 CPU 上。在推出 Ceph 之前，您还需要进行严格的测试，并确保其在申威 CPU 上正常工作，并正确满足所有的依赖关系。

java-devel sharutils checkpolicy selinux-policy-devel gperf cmake cryptsetup fuse-devel gcc-toolset-9-gcc-c++ gcc-c++ gperftools-devel gperftools-devel gperftools-devel leveldb-devel libaio-devel libblkid-devel cryptsetup-devel libcurl-devel libcap-ng-devel fmt-devel libnl3-devel liboath-devel libtool libxml2-devel make ncurses-devel libicu-devel parted patch perl pkgconfig procps python3 python3-devel snappy-devel sqlite-devel sudo util-linux valgrind-devel which xfsprogs xfsprogs-devel xmlstarlet nasm lua-devel librabbitmq-devel librdkafka-devel luarocks jq libuuid-devel python3-bcrypt python3-pecan python3-requests python3-dateutil python3-coverage python3-pyOpenSSL socat libzbd-devel bison flex json-devel nlohmann_json-devel libevent-devel yaml-cpp-devel libpmem-devel libpmemobj-devel c-ares-devel gnutls-devel hwloc-devel libpciaccess-devel lksctp-tools-devel protobuf-devel ragel systemtap-sdt-devel yaml-cpp-devel libubsan libasan libatomic gcc-toolset-9-annobin gcc-toolset-9-libubsan-devel gcc-toolset-9-libasan-devel gcc-toolset-9-libatomic-devel systemd-rpm-macros fdupes net-tools libbz2-devel mozilla-nss-devel keyutils-devel libopenssl-devel openldap2-devel cunit-devel python3-setuptools python3-Cython python3-PrettyTable python3-Sphinx rdma-core-devel liblz4-devel golang-github-prometheus-prometheus jsonnet boost-random nss-devel keyutils-libs-devel libibverbs-devel librdmacm-devel openldap-devel openssl-devel CUnit-devel python3-devel python3-setuptools python3-Cython python3-prettytable python3-sphinx lz4-devel golang golang-github-prometheus libtool-ltdl-devel xmlsec1 xmlsec1-devel xmlsec1-nss xmlsec1-openssl xmlsec1-openssl-devel python3-cherrypy python3-jwt python3-routes python3-scipy python3-werkzeug python3-pyOpenSSL golang-github-prometheus-prometheus jsonnet libxmlsec1-1 libxmlsec1-nss1 libxmlsec1-openssl1 python3-CherryPy python3-PyJWT python3-Routes python3-Werkzeug python3-numpy-devel xmlsec1-devel xmlsec1-openssl-devel lttng-ust-devel libbabeltrace-devel lttng-ust-devel babeltrace-devel libexpat-devel expat-devel redhat-rpm-config cryptopp-devel numactl-devel protobuf-compiler libcryptopp-devel libnuma-devel junit

yum install -yq java-devel sharutils gperf fuse-devel gcc-toolset-9-gcc-c++ gperftools-devel gperftools-devel gperftools-devel leveldb-devel libaio-devel libblkid-devel cryptsetup-devel libcurl-devel libcap-ng-devel fmt-devel libnl3-devel liboath-devel libtool ncurses-devel pkgconfig procps snappy-devel valgrind-devel xfsprogs-devel xmlstarlet nasm lua-devel librabbitmq-devel librdkafka-devel luarocks jq libuuid-devel python3-bcrypt python3-pecan python3-pyOpenSSL socat libzbd-devel bison flex json-devel nlohmann_json-devel libevent-devel yaml-cpp-devel libpmem-devel libpmemobj-devel c-ares-devel gnutls-devel hwloc-devel libpciaccess-devel lksctp-tools-devel protobuf-devel ragel yaml-cpp-devel libubsan libasan libatomic gcc-toolset-9-annobin gcc-toolset-9-libubsan-devel gcc-toolset-9-libasan-devel gcc-toolset-9-libatomic-devel systemd-rpm-macros fdupes libbz2-devel mozilla-nss-devel keyutils-devel libopenssl-devel openldap2-devel cunit-devel python3-Cython python3-PrettyTable python3-Sphinx rdma-core-devel liblz4-devel golang-github-prometheus-prometheus jsonnet boost-random nss-devel keyutils-libs-devel libibverbs-devel librdmacm-devel openldap-devel openssl-devel CUnit-devel python3-Cython python3-prettytable python3-sphinx lz4-devel golang golang-github-prometheus libtool-ltdl-devel xmlsec1 xmlsec1-devel xmlsec1-nss xmlsec1-openssl xmlsec1-openssl-devel python3-cherrypy python3-jwt python3-routes python3-scipy python3-werkzeug python3-pyOpenSSL golang-github-prometheus-prometheus jsonnet libxmlsec1-1 libxmlsec1-nss1 libxmlsec1-openssl1 python3-CherryPy python3-PyJWT python3-Routes python3-Werkzeug python3-numpy-devel xmlsec1-devel xmlsec1-openssl-devel lttng-ust-devel libbabeltrace-devel lttng-ust-devel babeltrace-devel libexpat-devel redhat-rpm-config cryptopp-devel numactl-devel protobuf-compiler libcryptopp-devel libnuma-devel junit

```
Error: Unable to find a match: gcc-toolset-9-gcc-c++ fmt-devel valgrind-devel luarocks python3-bcrypt libzbd-devel json-devel nlohmann_json-devel libpmem-devel libpmemobj-devel libubsan libasan gcc-toolset-9-annobin gcc-toolset-9-libubsan-devel gcc-toolset-9-libasan-devel gcc-toolset-9-libatomic-devel systemd-rpm-macros libbz2-devel mozilla-nss-devel keyutils-devel libopenssl-devel openldap2-devel cunit-devel python3-PrettyTable python3-Sphinx liblz4-devel golang-github-prometheus-prometheus jsonnet golang-github-prometheus python3-cherrypy libxmlsec1-1 libxmlsec1-nss1 libxmlsec1-openssl1 python3-CherryPy python3-PyJWT python3-Routes python3-Werkzeug python3-numpy-devel babeltrace-devel libexpat-devel redhat-rpm-config libcryptopp-devel libnuma-devel junit

```

这里不能用gcc9，所以过滤后

```
fmt-devel valgrind-devel luarocks python3-bcrypt libzbd-devel json-devel nlohmann_json-devel libpmem-devel libpmemobj-devel libubsan libasan systemd-rpm-macros libbz2-devel mozilla-nss-devel keyutils-devel libopenssl-devel openldap2-devel cunit-devel python3-PrettyTable python3-Sphinx liblz4-devel golang-github-prometheus-prometheus jsonnet golang-github-prometheus python3-cherrypy libxmlsec1-1 libxmlsec1-nss1 libxmlsec1-openssl1 python3-CherryPy python3-PyJWT python3-Routes python3-Werkzeug python3-numpy-devel babeltrace-devel libexpat-devel redhat-rpm-config libcryptopp-devel libnuma-devel junit
```

手动安装fmt-devel ok

手动安装luarocks OK

手动安装 valgrind-devel [https://valgrind.org/](https://valgrind.org/)    failed

```
。。。。。。。。。
checking for ranlib... ranlib
checking for perl... /usr/bin/perl
checking for gdb... /usr/bin/gdb
checking for a supported version of gcc... ok (gcc (GCC) 8.3.0 20190222 (Kylin 8.3.0-4))
checking build system type... ./config.guess: unable to guess system type

This script, last modified 2004-08-11, has failed to recognize
the operating system you are using. It is advised that you
download the most up to date version of the config scripts from

    ftp://ftp.gnu.org/pub/gnu/config/

If the version you run (./config.guess) is already up to date, please
send the following data and any information you think might be
pertinent to <config-patches@gnu.org> in order to provide the needed
information to handle your system.

config.guess timestamp = 2004-08-11

uname -m = sw_64
uname -r = 4.19.90-25.0.v2111.ky10.sw_64
uname -s = Linux
uname -v = #1 SMP Tue Dec 7 17:27:16 CST 2021

/usr/bin/uname -p = sw_64
/bin/uname -X     =

hostinfo               =
/bin/universe          =
/usr/bin/arch -k       =
/bin/arch              = sw_64
/usr/bin/oslevel       =
/usr/convex/getsysinfo =

UNAME_MACHINE = sw_64
UNAME_RELEASE = 4.19.90-25.0.v2111.ky10.sw_64
UNAME_SYSTEM  = Linux
UNAME_VERSION = #1 SMP Tue Dec 7 17:27:16 CST 2021
configure: error: cannot guess build type; you must specify one
```

pip3 install setuptools==44.1.1

```
yum install rpm-build rpmdevtools
```

export https_proxy=[http://192.168.10.95:7890;export](http://192.168.10.95:7890;export) http_proxy=[http://192.168.10.95:7890;export](http://192.168.10.95:7890;export) all_proxy=socks5://192.168.10.95:7890

sudo yum install -yq CUnit-devel boost-random cmake expat-devel fuse-devel gperf libaio-devel libbabeltrace-devel libblkid-devel libcap-ng-devel libcurl-devel libibverbs-devel libnl3-devel librabbitmq-devel librdkafka-devel librdmacm-devel libxml2-devel lttng-ust-devel lz4-devel ncurses-devel nss-devel openldap-devel python3-Cython python3-devel python3-prettytable python3-sphinx  snappy-devel xfsprogs-devel xmlstarlet yasm systemd-devel leveldb-devel spax at time mailx ed sendmail util-linux-user cups-client gperftools-devel gperftools-libs nasm  lua-devel libicu-devel gperftools-devel cryptsetup-devel

option(WITH_MANPAGE "Build man pages." ON)

option(WITH_RDMA "Enable RDMA in async messenger" ON) 

WITH_MGR_DASHBOARD_FRONTEND

ARGS="-DCMAKE_BUILD_TYPE=RelWithDebInfo -DWITH_TESTS=OFF -DWITH_MGR_DASHBOARD_FRONTEND=OFF -DWITH_SYSTEM_BOOST=ON" ./do_cmake.sh

-DCMAKE_INSTALL_PREFIX=/usr -DCMAKE_INSTALL_LIBDIR=/usr/lib -DCMAKE_INSTALL_LIBEXECDIR=/usr/libexec -DCMAKE_INSTALL_LOCALSTATEDIR=/var -DCMAKE_INSTALL_SYSCONFDIR=/etc -DCMAKE_INSTALL_MANDIR=/usr/share/man -DCMAKE_INSTALL_DOCDIR=/usr/share/doc/ceph -DCMAKE_INSTALL_INCLUDEDIR=/usr/include -DCMAKE_INSTALL_SYSTEMD_SERVICEDIR=/usr/lib/systemd/system -DWITH_MANPAGE=ON -DWITH_SYSTEM_BOOST=ON -DWITH_TESTS=OFF -DWITH_PYTHON3=3.7 -DWITH_MGR_DASHBOARD_FRONTEND=OFF

error: No best alternative for libs/context/build/asm_sources


    next alternative: required properties: <abi>aapcs <address-model>32 <architecture>arm <binary-format>elf <threading>multi <toolset>clang


        not matched


    next alternative: required properties: <abi>aapcs <address-model>32 <architecture>arm <binary-format>elf <threading>multi <toolset>gcc


        not matched


    next alternative: required properties: <abi>aapcs <address-model>32 <architecture>arm <binary-format>elf <threading>multi <toolset>qcc


        not matched


[ 19%] Building CXX object CMakeFiles/rocksdb.dir/db/memtable.cc.o

[ 19%] Building CXX object CMakeFiles/rocksdb.dir/db/memtable_list.cc.o

At global scope:

cc1plus: warning: unrecognized command line option '-Wno-pessimizing-move'

cc1plus: warning: unrecognized command line option '-Wno-deprecated-copy'

[ 19%] Building CXX object CMakeFiles/rocksdb.dir/db/merge_helper.cc.o

```
sudo yum install boost-devel
sudo apt-get install libboost-dev
```

```
[bob@bogon ceph-16.2.9]$ cmake --build ./build/ --target rbd-mirror
[  2%] Built target rbd_types
[  2%] Built target rados_snap_set_diff_obj
[  2%] Built target compressor_objs
[  2%] Built target oprequest-tp
[  8%] Built target common-objs
[  8%] Built target common_utf8
[  8%] Built target json_spirit
[  8%] Built target fmt
[ 11%] Built target common-auth-objs
[ 11%] Built target common_texttable_obj
[ 11%] Built target common_buffer_obj
[ 30%] Built target common-common-objs
[ 30%] Built target arch
[ 33%] Built target crc32
[ 36%] Built target common_mountcephfs_objs
[ 36%] Built target crush_objs
[ 41%] Built target common-msg-objs
[ 41%] Built target erasure_code
[ 41%] Built target ceph-common
[ 41%] Built target librbd-tp
[ 44%] Built target osdc
[ 83%] Built target rbd_internal
[ 83%] Built target libglobal_objs
[ 83%] Built target global
[ 83%] Built target cls_lock_client
[ 83%] Built target cls_rbd_client
[ 83%] Built target cls_journal_client
[ 83%] Built target heap_profiler
[ 83%] Built target librados-tp
[ 83%] Built target librados_impl
[ 86%] Built target librados
[ 86%] Built target neorados_api_obj
[ 86%] Built target neorados_objs
[ 86%] Built target libneorados
[ 86%] Built target common_prioritycache_obj
[ 97%] Built target rbd_mirror_internal
[ 97%] Built target rbd_mirror_types
[100%] Built target journal
[100%] Built target rbd_api
[100%] Linking CXX executable ../../../bin/rbd-mirror
/usr/bin/ld: ../../../lib/librbd_internal.a(ImageCtx.cc.o):/usr/include/c++/8.3.0/atomic:250: undefined reference to `__atomic_load_16'
。。。。。。。。。。。。。。。。。。。。。。。。。。。。
/usr/bin/ld: ../../../lib/librbd_internal.a(internal.cc.o):/usr/include/c++/8.3.0/atomic:320: undefined reference to `__atomic_compare_exchange_16'
/usr/bin/ld: ../../../lib/librbd_internal.a(internal.cc.o):/usr/include/c++/8.3.0/atomic:320: undefined reference to `__atomic_compare_exchange_16'
collect2: error: ld returned 1 exit status
gmake[3]: *** [src/tools/rbd_mirror/CMakeFiles/rbd-mirror.dir/build.make:143：bin/rbd-mirror] 错误 1
gmake[2]: *** [CMakeFiles/Makefile2:7160：src/tools/rbd_mirror/CMakeFiles/rbd-mirror.dir/all] 错误 2
gmake[1]: *** [CMakeFiles/Makefile2:7167：src/tools/rbd_mirror/CMakeFiles/rbd-mirror.dir/rule] 错误 2
gmake: *** [Makefile:2431：rbd-mirror] 错误 2

```

暂时：删掉 add_subdirectory(rbd_mirror)  不行

rpm -qa | grep libatomic

sudo sudo yum install libatomic

rpm -qa | grep libatomic

```
target_link_libraries(your_target_name PUBLIC atomic)
```

在 vim src/tools/rbd_mirror/CMakeLists.txt

最后加上 atomic

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351628.jpg)

这样 cmake --build ./build/ --target rbd-mirror 是可以编译通过的

没加之前 报错如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351834.jpg)

服务器上调试，在CMakeLists.txt里面打印输出变量

message(FATAL_ERROR "HAVE_CXX11_ATOMIC = ${HAVE_CXX11_ATOMIC}")

ARGS="-DCMAKE_BUILD_TYPE=RelWithDebInfo -DWITH_TESTS=OFF -DWITH_MGR_DASHBOARD_FRONTEND=OFF -DWITH_SYSTEM_BOOST=ON" ./do_cmake.sh

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351450.jpg)

Performing Test COMPILER_SUPPORTS_PESSIMIZING_MOVE - Failed

```
CMake 在构建过程中执行测试以确定编译器是否支持某些功能或选项。`Performing Test COMPILER_SUPPORTS_PESSIMIZING_MOVE - Failed` 消息通常意味着 CMake 在测试编译器是否支持悲观移动语义时失败了。

悲观移动语义是一个用于对可移动对象进行赋值的技术，它在 C++11 中引入。它可以提高编译器的优化能力，并减少不必要的对象复制。如果你的编译器不支持悲观移动语义，可能会导致构建失败。

要解决这个问题，可以尝试以下几种方法：

1. 更新编译器版本：如果你使用的是旧版本的编译器，则可能不支持悲观移动语义。请确保使用最新版本的编译器，并尝试重新构建项目。可以从官方网站下载最新的 GCC 或 Clang 编译器。

2. 确认编译器支持悲观移动语义：确认编译器是否确实支持悲观移动语义。可以使用以下命令在终端中检查：

```
echo "#include <utility>\nint main() { return !__has_cpp_attribute(maybe_unused); }" | <your_compiler> -x c++ - -o /dev/null
```

将 `<your_compiler>` 替换为你的编译器名称，例如 `g++` 或 `clang++`。如果编译器支持悲观移动语义，则输出应为 `0`。

3. 禁用悲观移动语义：如果你确定编译器不支持悲观移动语义，则可以在 CMakeLists.txt 文件中添加以下行以禁用该功能：

```
add_compile_definitions(_GLIBCXX_USE_CXX11_ABI=0)
```

这将告诉编译器不要使用悲观移动语义，从而避免编译错误。但请注意，禁用悲观移动语义也可能会影响代码的性能。

4. 更新 CMake 版本：如果你使用的是旧版本的 CMake，则可能无法正确检测编译器是否支持悲观移动语义。建议升级到支持悲观移动语义的 CMake 版本。
```

Performing Test COMPILER_SUPPORTS_REDUNDANT_MOVE - Failed

```
CMake 在构建过程中执行测试以确定编译器是否支持某些功能或选项。`Performing Test COMPILER_SUPPORTS_REDUNDANT_MOVE - Failed` 消息通常意味着 CMake 在测试编译器是否支持冗余移动语义时失败了。

冗余移动语义是一个用于对可移动对象进行赋值的技术，它在 C++17 中引入。它可以提高编译器的优化能力，并减少不必要的对象复制。如果你的编译器不支持冗余移动语义，可能会导致构建失败。

要解决这个问题，可以尝试以下几种方法：

1. 更新编译器版本：如果你使用的是旧版本的编译器，则可能不支持冗余移动语义。请确保使用最新版本的编译器，并尝试重新构建项目。可以从官方网站下载最新的 GCC 或 Clang 编译器。

2. 确认编译器支持冗余移动语义：确认编译器是否确实支持冗余移动语义。可以使用以下命令在终端中检查：

```
echo "#include <utility>\nint main() { return !__has_cpp_attribute(maybe_unused); }" | <your_compiler> -x c++ -std=c++17 - -o /dev/null
```

将 `<your_compiler>` 替换为你的编译器名称，例如 `g++` 或 `clang++`。如果编译器支持冗余移动语义，则输出应为 `0`。

3. 禁用冗余移动语义：如果你确定编译器不支持冗余移动语义，则可以在 CMakeLists.txt 文件中添加以下行以禁用该功能：

```
add_compile_definitions(_GLIBCXX_USE_CXX11_ABI=0)
```

这将告诉编译器不要使用冗余移动语义，从而避免编译错误。但请注意，禁用冗余移动语义也可能会影响代码的性能。

4. 更新 CMake 版本：如果你使用的是旧版本的 CMake，则可能无法正确检测编译器是否支持冗余移动语义。建议升级到支持冗余移动语义的 CMake 版本。
```

后面尝试升级GCC

warning: unrecognized command line option '-Wno-pessimizing-move'

```
`-Wno-pessimizing-move` 是 GCC 编译器的一个选项，用于禁止在构建过程中针对悲观移动语义发出警告。然而，如果 GCC 编译器版本过旧，则可能不支持此选项，因此会导致类似于 `warning: unrecognized command line option '-Wno-pessimizing-move'` 的警告消息。

要解决此问题，你可以使用以下方法：

1. 更新 GCC 编译器版本。如果你使用的是旧版本的 GCC 编译器，则可能不支持 `-Wno-pessimizing-move` 选项。请确保使用较新版本的 GCC 编译器。可以从 GCC 官网下载最新版本的编译器，并按照官方文档的说明进行安装和配置。

2. 禁用 `-Wno-pessimizing-move` 选项。如果你确定 GCC 编译器不支持 `-Wno-pessimizing-move` 选项，则可以尝试禁用该选项。可以将该选项从命令行或 CMakeLists.txt 文件中删除。

如果你决定禁用该选项，则应该注意，如果代码中存在悲观移动语义，则可能会导致构建失败或可能的运行时错误。因此，建议按照第一种方法更新 GCC 编译器版本。
```

warning: unrecognized command line option '-Wno-deprecated-copy'

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351799.jpg)

vim src/tools/rbd/CMakeLists.txt

vim src/rbd_fuse/CMakeLists.txt

vim src/tools/rbd_mirror/CMakeLists.txt

vim src/tools/rbd_nbd/CMakeLists.txt

vim src/tools/rbd-replay-prep/CMakeLists.txt   3处   ceph16.2.9里面没有

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351852.jpg)

vim src/librbd/CMakeLists.txt

![](https://gitee.com/hxc8/images6/raw/master/img/202407182351601.jpg)

../configure --target=sw_64-sunway-linux-gnu --prefix=${PREFIX}/usr --with-sysroot=${PREFIX} --disable-werror --with-cpu=sw6b