```
#!/bin/sh

usage="usage: $0 <name> [vstart options]..\n"

usage_exit() {
	printf "$usage"
	exit
}

[ $# -lt 1 ] && usage_exit


instance=$1
shift

vstart_path=`dirname $0`

root_path=`dirname $0`
root_path=`(cd $root_path; pwd)`

[ -z "$BUILD_DIR" ] && BUILD_DIR=build

if [ -e CMakeCache.txt ]; then
    root_path=$PWD
elif [ -e $root_path/../${BUILD_DIR}/CMakeCache.txt ]; then
    cd $root_path/../${BUILD_DIR}
    root_path=$PWD
fi
RUN_ROOT_PATH=${root_path}/run

mkdir -p $RUN_ROOT_PATH

if [ -z "$CLUSTERS_LIST" ]
then
  CLUSTERS_LIST=$RUN_ROOT_PATH/.clusters.list
fi

if [ ! -f $CLUSTERS_LIST ]; then
touch $CLUSTERS_LIST
fi

pos=`grep -n -w $instance $CLUSTERS_LIST`
if [ $? -ne 0 ]; then
  echo $instance >> $CLUSTERS_LIST
  pos=`grep -n -w $instance $CLUSTERS_LIST`
fi

pos=`echo $pos | cut -d: -f1`
base_port=$((6800+pos*20))
rgw_port=$((8000+pos*1))

export VSTART_DEST=$RUN_ROOT_PATH/$instance
export CEPH_PORT=$base_port
export CEPH_RGW_PORT=$rgw_port

mkdir -p $VSTART_DEST

echo "Cluster dest path: $VSTART_DEST"
echo "monitors base port: $CEPH_PORT"
echo "rgw base port: $CEPH_RGW_PORT"

$vstart_path/vstart.sh "$@"

```

mstart.sh 脚本主要用于启动一个 Ceph 虚拟集群，以便在单个节点上进行调试和测试。脚本的具体功能如下：

1. 检查输入参数是否足够，如果参数不足，则输出用法提示并退出。

1. 获取脚本所在目录和 

build 目录的路径，并设置根目录和运行目录。

1. 总是移动到编译目录，以便正确设置 Ceph 组件的路径。

1. 设置集群的名称和端口号，并创建集群目录。

1. 创建 Ceph 虚拟群集，使用 

vstart.sh 脚本，并传递给它可能传递的选项。

具体来说，该脚本会创建一个新的 Ceph 虚拟集群，并为集群设置指定的标识符和一组端口号。然后，脚本将使用 vstart.sh 脚本来启动 mon、osd、mgr 和 rgw 等组件，并使用 run/ 目录中的 Ceph 配置文件来配置这些组件。