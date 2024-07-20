```
#!/usr/bin/env bash
# -e：表示在脚本执行过程中，如果任何一条命令的返回值不为0（即执行失败），则立即退出脚本。
# -x：表示在脚本执行过程中，输出每一条执行的命令及其参数，以方便调试。
set -ex

if [ -d .git ]; then
    git submodule update --init --recursive
fi
# : 是一个Shell内置命令，通常用于占位符或者作为空命令使用。
# ${VAR_NAME:=value}：表示如果VAR_NAME变量未定义或为空，则将其设置为value，否则保持原值不变。
# 因此，这段代码的含义是:

# 如果BUILD_DIR变量未定义或为空，则将其设置为build。
# 如果CEPH_GIT_DIR变量未定义或为空，则将其设置为..（即上级目录）。
# 这在shell脚本中非常常见，可以保证脚本中使用的变量都有默认值，避免因为变量未定义而导致脚本出错。
: ${BUILD_DIR:=build}
: ${CEPH_GIT_DIR:=..}

if [ -e $BUILD_DIR ]; then
    echo "'$BUILD_DIR' dir already exists; either rm -rf '$BUILD_DIR' and re-run, or set BUILD_DIR env var to a different directory name"
    exit 1
fi

PYBUILD="3"
ARGS="-GNinja"
# 检查/etc/os-release文件是否存在并且可读
if [ -r /etc/os-release ]; then
# 读取/etc/os-release文件中的变量值。
  source /etc/os-release
  case "$ID" in
      fedora)
          if [ "$VERSION_ID" -ge "35" ] ; then
            PYBUILD="3.10"
          elif [ "$VERSION_ID" -ge "33" ] ; then
            PYBUILD="3.9"
          elif [ "$VERSION_ID" -ge "32" ] ; then
            PYBUILD="3.8"
          else
            PYBUILD="3.7"
          fi
          ;;
      rhel|centos)
          MAJOR_VER=$(echo "$VERSION_ID" | sed -e 's/\..*$//')
          if [ "$MAJOR_VER" -ge "9" ] ; then
              PYBUILD="3.9"
          elif [ "$MAJOR_VER" -ge "8" ] ; then
              PYBUILD="3.6"
          fi
          ;;
      opensuse*|suse|sles)
          PYBUILD="3"
          ARGS+=" -DWITH_RADOSGW_AMQP_ENDPOINT=OFF"
          ARGS+=" -DWITH_RADOSGW_KAFKA_ENDPOINT=OFF"
          ;;
  esac
elif [ "$(uname)" == FreeBSD ] ; then
  PYBUILD="3"
  ARGS+=" -DWITH_RADOSGW_AMQP_ENDPOINT=OFF"
  ARGS+=" -DWITH_RADOSGW_KAFKA_ENDPOINT=OFF"
else
  echo Unknown release
  exit 1
fi
#上方代码根据不同的操作系统或发行版执行不同的操作或设置不同的参数。

ARGS+=" -DWITH_PYTHON3=${PYBUILD}"
# 如果ccache命令存在，则执行if语句块中的命令；其中"> /dev/null 2>&1"表示将标准输出和标准错误输出重定向到/dev/null。
if type ccache > /dev/null 2>&1 ; then
    echo "enabling ccache"
    ARGS+=" -DWITH_CCACHE=ON"
fi

mkdir $BUILD_DIR
cd $BUILD_DIR
# 如果cmake3命令存在，则将变量CMAKE设置为cmake3，否则设置为cmake。
if type cmake3 > /dev/null 2>&1 ; then
    CMAKE=cmake3
else
    CMAKE=cmake
fi
# 执行CMAKE命令，并将变量ARGS和$@(表示所有传递给脚本的参数列表)及$CEPH_GIT_DIR作为参数传递给它；其中|| exit 1表示如果CMAKE执行失败，则退出脚本并返回1。
${CMAKE} $ARGS "$@" $CEPH_GIT_DIR || exit 1
# 关闭调试模式。
set +x

# minimal config to find plugins
cat <<EOF > ceph.conf
[global]
plugin dir = lib
erasure code dir = lib
EOF

echo done.

if [[ ! "$ARGS $@" =~ "-DCMAKE_BUILD_TYPE" ]]; then
  cat <<EOF

****
WARNING: do_cmake.sh now creates debug builds by default. Performance
may be severely affected. Please use -DCMAKE_BUILD_TYPE=RelWithDebInfo
if a performance sensitive build is required.
****
EOF
fi
# 检查变量$ARGS和"$@"中是否包含"-DCMAKE_BUILD_TYPE"参数，如果不包含则输出一段警告信息。




```

后面的do_freebsd.sh 跳过了，简单解释下里面几条shell

```
killall ceph-osd || true
```

如果killall ceph-osd命令成功，则会正常退出，并继续执行脚本中的下一行命令；如果

killall ceph-osd命令失败，则true命令将返回成功状态，并继续执行脚本中的下一行命令。即无论

killall ceph-osd命令是否成功，脚本都将继续执行。