用于生成 Ceph 发行版源代码包的脚本。它的主要作用是将 Ceph 源代码打包为一个.tar.gz 文件，以便进行分发和安装。生成的源代码包包含了 Ceph 的所有源代码文件、Makefile、configure 脚本等，可以用于在其他系统上编译和安装 Ceph。

具体执行以下操作：

检查当前目录是否为 Ceph 源代码目录，如果不是则退出。

读取当前目录下的 configure.ac 文件，获取 Ceph 的版本号和发行日期等信息。

根据版本号和发行日期等信息创建一个源代码包目录，将 Ceph 的所有源代码文件和必要的文件复制到该目录下。同时，会生成一个 configure 脚本和 Makefile 文件，用于在其他系统上编译和安装 Ceph。

将源代码包目录打包为一个.tar.gz 文件，命名为 ceph-x.y.z.tar.gz，其中 x.y.z 为 Ceph 的版本号。

打印生成的.tar.gz 文件的路径和 MD5 校验和等信息。

详细学习：

```
#!/bin/bash -e

SCRIPTNAME="$(basename "${0}")"
BASEDIR="$(readlink -f "$(dirname "${0}")")"

if [ ! -d .git ]; then
    echo "$SCRIPTNAME: Full path to the script: $BASEDIR/$SCRIPTNAME"
    echo "$SCRIPTNAME: No .git present. Run this from the base dir of the git checkout."
    exit 1
fi

# Running the script from a directory containing a colon anywhere in the path
# will expose us to the dreaded "[BUG] npm run [command] failed if the directory
# path contains colon" bug https://github.com/npm/cli/issues/633
# (see https://tracker.ceph.com/issues/39556 for details)
# 检测脚本所在的路径中是否包含冒号字符 :，如果包含则提示用户可能会遇到一个与 npm 相关的错误，并退出脚本
if [[ "$BASEDIR" == *:* ]] ; then
    echo "$SCRIPTNAME: Full path to the script: $BASEDIR/$SCRIPTNAME"
    echo "$SCRIPTNAME: The path to the script contains a colon. Their presence has been known to break the script."
    exit 1
fi

#下面代码作用是获取版本号
# 如果 version 变量为空，则运行 git describe --long --match 'v*' | sed 's/^v//' 命令获取最新的版本号，并将结果赋值给 version 变量
version=$1
[ -z "$version" ] && version=$(git describe --long --match 'v*' | sed 's/^v//')
if expr index $version '-' > /dev/null; then
    rpm_version=$(echo $version | cut -d - -f 1-1)
    rpm_release=$(echo $version | cut -d - -f 2- | sed 's/-/./')
else
    rpm_version=$version
    rpm_release=0
fi

outfile="ceph-$version"
echo "version $version"

# update submodules
echo "updating submodules..."
force=$(if git submodule usage 2>&1 | grep --quiet 'update.*--force'; then echo --force ; fi)
if ! git submodule sync || ! git submodule update $force --init --recursive; then
    echo "Error: could not initialize submodule projects"
    echo "  Network connectivity might be required."
    exit 1
fi
# 从指定的 URL 下载文件，并验证其 SHA256 校验和
download_from() {
    fname=$1
    shift
    sha256=$1
    shift
    # 关闭 shell 的错误检查。
    set +e
    while true; do
        url_base=$1
        shift
        if [ -z $url_base ]; then
            echo "Error: failed to download $name."
            exit
        fi
        url=$url_base/$fname
        wget -c --no-verbose -O $fname $url
        if [ $? != 0 -o ! -e $fname ]; then
            echo "Download of $url failed"
        elif [ $(sha256sum $fname | awk '{print $1}') != $sha256 ]; then
            echo "Error: failed to download $name: SHA256 mismatch."
        else
            break
        fi
    done
    # 重新启用 shell 的错误检查。
    set -e
}

download_boost() {
    boost_version=$1
    shift
    boost_sha256=$1
    shift
    boost_version_underscore=$(echo $boost_version | sed 's/\./_/g')
    boost_fname=boost_${boost_version_underscore}.tar.bz2
    download_from $boost_fname $boost_sha256 $*
    tar xjf $boost_fname -C src \
        --exclude="$boost_version_underscore/libs/*/doc" \
        --exclude="$boost_version_underscore/libs/*/example" \
        --exclude="$boost_version_underscore/libs/*/examples" \
        --exclude="$boost_version_underscore/libs/*/meta" \
        --exclude="$boost_version_underscore/libs/*/test" \
        --exclude="$boost_version_underscore/tools/boostbook" \
        --exclude="$boost_version_underscore/tools/quickbook" \
        --exclude="$boost_version_underscore/tools/auto_index" \
        --exclude='doc' --exclude='more' --exclude='status'
    mv src/boost_${boost_version_underscore} src/boost
    tar cf ${outfile}.boost.tar ${outfile}/src/boost
    rm -rf src/boost
}
# 作用是下载 liburing 库，并将其解压到指定的目录中，并对解压后的文件进行规范化处理。
# liburing 是一个用于 Linux 内核 io_uring 子系统的用户空间库。io_uring 是 Linux 内核提供的一种高性能异步 I/O 模型，它允许应用程序以零拷贝方式进行高效、可扩展的 I/O 操作。而 liburing 库提供了方便的接口，使得应用程序可以更容易地使用 io_uring 子系统，从而充分发挥其性能优势。
download_liburing() {
    liburing_version=$1
    shift
    liburing_sha256=$1
    shift
    liburing_fname=liburing-${liburing_version}.tar.gz
    download_from $liburing_fname $liburing_sha256 $*
    tar xzf $liburing_fname -C src  \
        --exclude=debian \
        --exclude=examples \
        --exclude=man \
        --exclude=test
    # normalize the names, liburing-0.7 if downloaded from git.kernel.dk,
    # liburing-liburing-0.7 from github.com
    mv src/liburing-* src/liburing
    tar cf ${outfile}.liburing.tar ${outfile}/src/liburing
    rm -rf src/liburing
}
# PMDK（Persistent Memory Development Kit）是一个用于开发和管理持久内存（Persistent Memory）的开源库。持久内存是一种新型的存储技术，它可以将数据存储在内存中，并保证在断电或系统崩溃的情况下数据不会丢失。PMDK 库提供了一组 C/C++ API，使得应用程序可以方便地使用持久内存，并将其视为一种持久化的数据存储介质。
# PMDK 库提供了对多种持久内存硬件的支持，包括 Intel Optane DC、NVDIMM 等。它还提供了多种数据结构和算法的实现，包括 B+ 树、哈希表、事务内存等，使得应用程序可以轻松地实现基于持久内存的数据存储和管理。
# 除此之外，PMDK 库还提供了一组工具，用于检测和管理持久内存。例如，pmempool 工具可以帮助用户创建和管理持久内存池，而 pmreorder 工具可以对持久内存中的数据进行重排，以提高访问性能。PMDK 库还提供了详细的文档和示例程序，帮助用户更好地了解和使用持久内存。
download_pmdk() {
    pmdk_version=$1
    shift
    pmdk_sha256=$1
    shift
    pmdk_fname=pmdk-${pmdk_version}.tar.gz
    download_from $pmdk_fname $pmdk_sha256 $*
    tar xzf $pmdk_fname -C src \
        --exclude="pmdk-${pmdk_version}/doc" \
        --exclude="pmdk-${pmdk_version}/src/test" \
        --exclude="pmdk-${pmdk_version}/src/examples" \
        --exclude="pmdk-${pmdk_version}/src/benchmarks"
    mv src/pmdk-${pmdk_version} src/pmdk
    tar cf ${outfile}.pmdk.tar ${outfile}/src/pmdk
    rm -rf src/pmdk
}
# 构建 ceph-dashboard 前端部分，并将构建结果打包为一个 tar 文件。
build_dashboard_frontend() {
  CURR_DIR=`pwd`
  TEMP_DIR=`mktemp -d`

  $CURR_DIR/src/tools/setup-virtualenv.sh $TEMP_DIR
#   使用虚拟环境中的 pip 工具安装 nodeenv。
  $TEMP_DIR/bin/pip install nodeenv
#   使用 nodeenv 工具创建一个包含指定版本 Node.js 的虚拟环境。
  $TEMP_DIR/bin/nodeenv --verbose -p --node=12.18.2
  cd src/pybind/mgr/dashboard/frontend
# 激活虚拟环境，以便后续操作可以在虚拟环境中进行。
  . $TEMP_DIR/bin/activate
#   NG_CLI_ANALYTICS=false 表示禁用 Angular CLI 的分析功能，以提高执行速度。
  NG_CLI_ANALYTICS=false timeout 1h npm ci
  echo "Building ceph-dashboard frontend with build:localize script";
  # we need to use "--" because so that "--prod" survives accross all
  # scripts redirections inside package.json
  npm run build:localize -- --prod
# 退出虚拟环境。
  deactivate
  cd $CURR_DIR
  rm -rf $TEMP_DIR
  tar cf dashboard_frontend.tar $outfile/src/pybind/mgr/dashboard/frontend/dist
}

generate_rook_ceph_client() {
  $outfile/src/pybind/mgr/rook/generate_rook_ceph_client.sh
  tar cf rook_ceph_client.tar $outfile/src/pybind/mgr/rook/rook_client/*.py
}

# clean out old cruft...
echo "cleanup..."
rm -f $outfile*

# build new tarball
echo "building tarball..."
bin/git-archive-all.sh --prefix ceph-$version/ \
               --verbose \
               --ignore corpus \
               $outfile.tar

# populate files with version strings
echo "including src/.git_version, ceph.spec"
# 使用 git rev-parse 命令获取当前代码库的 HEAD（最新提交）的 SHA1 值，并将当前版本字符串添加到 ".git_version" 文件中。如果无法获取这些值，则将错误消息重定向到 /dev/null。
(git rev-parse HEAD ; echo $version) 2> /dev/null > src/.git_version
# 循环将 ".spec.in" 文件中的特定字符串替换为变量的值，并将结果写入 ".spec" 文件中
for spec in ceph.spec.in; do
    cat $spec |
        sed "s/@PROJECT_VERSION@/$rpm_version/g" |
        sed "s/@RPM_RELEASE@/$rpm_release/g" |
        sed "s/@TARBALL_BASENAME@/ceph-$version/g" > `echo $spec | sed 's/.in$//'`
done
ln -s . $outfile
tar cvf $outfile.version.tar $outfile/src/.git_version $outfile/ceph.spec
# NOTE: If you change this version number make sure the package is available
# at the three URLs referenced below (may involve uploading to download.ceph.com)
boost_version=1.75.0
download_boost $boost_version 953db31e016db7bb207f11432bef7df100516eeb746843fa0486a222e3fd49cb \
               https://boostorg.jfrog.io/artifactory/main/release/$boost_version/source \
               https://downloads.sourceforge.net/project/boost/boost/$boost_version \
               https://download.ceph.com/qa
download_liburing 0.7 8e2842cfe947f3a443af301bdd6d034455536c38a455c7a700d0c1ad165a7543 \
                  https://github.com/axboe/liburing/archive \
                  https://git.kernel.dk/cgit/liburing/snapshot
pmdk_version=1.10
download_pmdk $pmdk_version 08dafcf94db5ac13fac9139c92225d9aa5f3724ea74beee4e6ca19a01a2eb20c \
               https://github.com/pmem/pmdk/releases/download/$pmdk_version
build_dashboard_frontend
generate_rook_ceph_client
# 将多个 tar 文件合并为一个 tar 文件，并删除原始的单个 tar 文件。
for tarball in $outfile.version   \
               $outfile.boost     \
               $outfile.liburing  \
               $outfile.pmdk  \
               dashboard_frontend \
               rook_ceph_client   \
               $outfile; do
    tar --concatenate -f $outfile.all.tar $tarball.tar
    rm $tarball.tar
done
mv $outfile.all.tar $outfile.tar
rm $outfile

echo "compressing..."
bzip2 -9 $outfile.tar

echo "done."

```

sh make-dist 会打包一个最精简的tar包   其中 bin/git-archive-all.sh会获取git当前版本，比如a文件里面有一段代码int b = 10;你本地文件改成了 int b = 111;  打包后是int b =10;

后来看到github上有说明

This will create a tarball like ceph-$version.tar.bz2 from git. (Ensure that any changes you want to include in your working directory are committed to git.)