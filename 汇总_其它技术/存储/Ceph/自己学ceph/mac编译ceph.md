```
brew install llvm
```

```
brew install snappy ccache cmake pkg-config
```

brew install osxfuse --cask 

brew install nss   

```
export PKG_CONFIG_PATH=/usr/local/Cellar/nss/3.82/lib/pkgconfig:/usr/local/Cellar/openssl/1.1.1g/lib/pkgconfig
```

```
cmake .. -DBOOST_J=4 \
  -DCMAKE_C_COMPILER=/usr/local/opt/llvm/bin/clang \
  -DCMAKE_CXX_COMPILER=/usr/local/opt/llvm/bin/clang++ \
  -DCMAKE_EXE_LINKER_FLAGS="-L/usr/local/opt/llvm/lib" \
  -DENABLE_GIT_VERSION=OFF \
  -DSNAPPY_ROOT_DIR=/usr/local/Cellar/snappy/1.1.9 \
  -DWITH_BABELTRACE=OFF \
  -DWITH_BLUESTORE=OFF \
  -DWITH_CCACHE=OFF \
  -DWITH_CEPHFS=OFF \
  -DWITH_KRBD=OFF \
  -DWITH_LIBCEPHFS=OFF \
  -DWITH_LTTNG=OFF \
  -DWITH_LZ4=OFF \
  -DWITH_MANPAGE=ON \
  -DWITH_MGR=OFF \
  -DWITH_MGR_DASHBOARD_FRONTEND=OFF \
  -DWITH_RADOSGW=OFF \
  -DWITH_RDMA=OFF \
  -DWITH_SPDK=OFF \
  -DWITH_SYSTEMD=OFF \
  -DWITH_TESTS=OFF \
  -DWITH_XFS=OFF
```