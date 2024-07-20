```
if [ -n "$VSTART_DEST" ]; then
    SRC_PATH=`dirname $0`
    SRC_PATH=`(cd $SRC_PATH; pwd)`    
    CEPH_DIR=$SRC_PATH
    CEPH_BIN=${PWD}/bin
    CEPH_LIB=${PWD}/lib

    CEPH_CONF_PATH=$VSTART_DEST
    CEPH_DEV_DIR=$VSTART_DEST/dev
    CEPH_OUT_DIR=$VSTART_DEST/out
    CEPH_ASOK_DIR=$VSTART_DEST/out
fi
```

通过 dirname 命令得到了脚本所在目录的路径，并用 cd 命令进入该目录。然后使用 pwd 命令获取该目录的绝对路径，并将其赋值给 $SRC_PATH 变量。

```

# for running out of the CMake build directory
if [ -e CMakeCache.txt ]; then
    # Out of tree build, learn source location from CMakeCache.txt
    CEPH_ROOT=$(get_cmake_variable ceph_SOURCE_DIR)
    CEPH_BUILD_DIR=`pwd`
    [ -z "$MGR_PYTHON_PATH" ] && MGR_PYTHON_PATH=$CEPH_ROOT/src/pybind/mgr
fi
```

通过调用 get_cmake_variable 函数获取 CMake 变量 ceph_SOURCE_DIR 的值，并将其赋值给 $CEPH_ROOT 变量，即代表 ceph 项目源代码目录的路径。

判断环境变量 $MGR_PYTHON_PATH 的值是否为空，如果为空，就将 $CEPH_ROOT/src/pybind/mgr 赋值给它，即代表 ceph 项目 Python 环境的路径。

```
if [ -z "${CEPH_VSTART_WRAPPER}" ]; then
    PATH=$(pwd):$PATH
fi
```

设置环境变量 PATH

```
if [ -z "$CEPH_PORT" ]; then
    while [ true ]
    do
        CEPH_PORT="$(echo $(( RANDOM % 1000 + 40000 )))"
        ss -a -n | egrep "\<LISTEN\>.+:${CEPH_PORT}\s+" 1>/dev/null 2>&1 || break
    done
fi

```

用了一个循环的方式，用来查找一个可用的端口号。

$SUDO find "$CEPH_OUT_DIR" -type f -delete 该命令用于删除 $CEPH_OUT_DIR 目录下的所有文件

```
if [ $inc_osd_num -gt 0 ]; then
    start_osd
    exit
fi
```

../src/vstart.sh --inc-osd  增加一个osd并自动启动

../src/vnewosd.sh 是增加一个OSD但是不自动启动，执行ceph-osd -i 2    2是osd的id