要使 Ceph 编译尽可能小和快，您可以使用以下建议：

1. 禁用不必要的组件：Ceph 有很多可选的组件和功能，可以根据需要启用或禁用它们。在进行编译时，禁用不需要的组件可以减少构建时间和二进制文件大小。您可以使用 CMake 的 

-D 选项来控制组件。

例如，如果您只需要构建 RADOS 和 OSD 组件，则可以使用以下 CMake 命令来禁用其他组件：

```
cmake -DCMAKE_BUILD_TYPE=RelWithDebInfo \
      -DENABLE_RADOS=ON \
      -DENABLE_OSD=ON \
      -DENABLE_RGW=OFF \
      -DENABLE_CEPHFS=OFF \
      -DENABLE_MGR=OFF \
      -DENABLE_TESTS=OFF \
      ..

```

1. 使用最新的 GCC 编译器：Ceph 的性能和速度很大程度上受到底层编译器的影响。使用最新的 GCC 编译器可以提高编译速度和二进制文件的性能。例如，在 Ubuntu 18.04 上，您可以使用以下命令将 GCC 版本升级到 9：

```
sudo add-apt-repository ppa:ubuntu-toolchain-r/test
sudo apt-get update
sudo apt-get install gcc-9 g++-9

```

1. 缩小二进制文件：您可以使用 strip 命令从 Ceph 二进制文件中删除调试符号，以减小其大小。例如，可以使用以下命令从 Ceph 的 RADOS 和 OSD 二进制文件中删除调试符号：

```
strip src/osd/osd
strip src/rados/rados

```

1. 使用构建缓存：如果您多次构建 Ceph，可以考虑使用构建缓存。构建缓存可以将您的第一次构建输出缓存起来，以便下一次使用该缓存进行更快的构建。例如，您可以使用 ccache 工具来实现构建缓存：

```
sudo apt-get install ccache
export CCACHE_DIR=/var/tmp/ccache
export CCACHE_MAX_SIZE=10G
export PATH="/usr/lib/ccache:${PATH}"

```

1. 使用编译机器的多个 CPU 核心：您可以使用以下 

make 命令利用编译机器的多个 CPU 核心，以提高编译速度：

```
make -j$(nproc)

```

在以上步骤执行完毕后，您将得到一个尽可能小和速度尽可能快的 Ceph 二进制文件。