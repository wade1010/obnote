# Ceph - a scalable distributed storage systemCeph——一个可扩展的分布式存储系统

Please see [http://ceph.com/](http://ceph.com/) for current info.有关当前信息，请参阅 [http://ceph.com/](http://ceph.com/) 。

Most of Ceph is dual licensed under the LGPL version 2.1 or 3.0. Some miscellaneous code is under BSD-style license or is public domain. The documentation is licensed under Creative Commons Attribution Share Alike 3.0 (CC-BY-SA-3.0). There are a handful of headers included here that are licensed under the GPL. Please see the file COPYING for a full inventory of licenses by file.Ceph 的大部分内容都在 LGPL 2.1 或 3.0 版下获得双重许可。一些杂项代码在 BSD 风格的许可下或属于公共领域。该文档根据 Creative Commons Attribution Share Alike 3.0 (CC-BY-SA-3.0) 获得许可。此处包含的一些标头已根据 GPL 获得许可。请参阅文件 COPYING 以获取按文件列出的完整许可证清单。

Code contributions must include a valid "Signed-off-by" acknowledging the license for the modified or contributed file. Please see the file SubmittingPatches.rst for details on what that means and on how to generate and submit patches.代码贡献必须包括一个有效的“签名者”，以确认修改或贡献文件的许可。请参阅文件 SubmittingPatches.rst 以了解有关这意味着什么以及如何生成和提交补丁的详细信息。

We do not require assignment of copyright to contribute code; code is contributed under the terms of the applicable license.我们不需要转让版权来贡献代码；代码是根据适用许可的条款提供的。

You can clone from github with你可以从 github 克隆

```
git clone git@github.com:ceph/ceph

```

or, if you are not a github user,或者，如果你不是 github 用户，

```
git clone git://github.com/ceph/ceph

```

Ceph contains many git submodules that need to be checked out withCeph 包含许多需要检查的 git 子模块

```
git submodule update --init --recursive

```

The list of Debian or RPM packages dependencies can be installed with:Debian 或 RPM 软件包依赖项列表可以通过以下方式安装：

```
./install-deps.sh

```

Note that these instructions are meant for developers who are compiling the code for development and testing. To build binaries suitable for installation we recommend you build deb or rpm packages, or refer to the ceph.spec.in or debian/rules to see which configuration options are specified for production builds.请注意，这些说明适用于为开发和测试编译代码的开发人员。要构建适合安装的二进制文件，我们建议您构建 deb 或 rpm 包，或参考 ceph.spec.in 或 debian/rules 以查看为生产构建指定了哪些配置选项。

Build instructions: 构建说明：

```
./do_cmake.sh
cd build
make

```

(Note: do_cmake.sh now defaults to creating a debug build of ceph that can be up to 5x slower with some workloads. Please pass "-DCMAKE_BUILD_TYPE=RelWithDebInfo" to do_cmake.sh to create a non-debug release.)（注意：do_cmake.sh 现在默认创建 ceph 的调试版本，在某些工作负载下可能会慢 5 倍。请将“-DCMAKE_BUILD_TYPE=RelWithDebInfo”传递给 do_cmake.sh 以创建非调试版本。）

(Note: make alone will use only one CPU thread, this could take a while. use the -j option to use more threads. Something like make -j$(nproc) would be a good start.（注意： make 单独将只使用一个 CPU 线程，这可能需要一段时间。使用 -j 选项使用更多线程。 make -j$(nproc) 之类的东西将是一个好的开始。

This assumes you make your build dir a subdirectory of the ceph.git checkout. If you put it elsewhere, just point CEPH_GIT_DIR to the correct path to the checkout. Any additional CMake args can be specified setting ARGS before invoking do_cmake. See [cmake options](https://github.com/ceph/ceph/tree/v16.2.12#cmake-options) for more details. Eg.这假设您将构建目录设为 ceph.git 签出的子目录。如果您将它放在其他地方，只需将 CEPH_GIT_DIR 指向结帐的正确路径。在调用 do_cmake 之前，可以指定任何额外的 CMake args 设置 ARGS。有关详细信息，请参阅 cmake 选项。例如。

```
ARGS="-DCMAKE_C_COMPILER=gcc-7" ./do_cmake.sh

```

To build only certain targets use:要仅构建某些目标，请使用：

```
make [target name]

```

To install:

```
make install

```

If you run the cmake command by hand, there are many options you can set with "-D". For example the option to build the RADOS Gateway is defaulted to ON. To build without the RADOS Gateway:如果您手动运行 cmake 命令，您可以使用“-D”设置许多选项。例如，构建 RADOS 网关的选项默认为开启。在没有 RADOS 网关的情况下构建：

```
cmake -DWITH_RADOSGW=OFF [path to top level ceph directory]

```

Another example below is building with debugging and alternate locations for a couple of external dependencies:下面的另一个示例是为几个外部依赖项构建调试和备用位置：

```
cmake -DLEVELDB_PREFIX="/opt/hyperleveldb" \
-DCMAKE_INSTALL_PREFIX=/opt/ceph -DCMAKE_C_FLAGS="-O0 -g3 -gdwarf-4" \
..

```

To view an exhaustive list of -D options, you can invoke cmake with:要查看 -D 选项的详尽列表，您可以使用以下命令调用 cmake ：

```
cmake -LH

```

If you often pipe make to less and would like to maintain the diagnostic colors for errors and warnings (and if your compiler supports it), you can invoke cmake with:如果您经常通过管道将 make 传递给 less 并希望保留错误和警告的诊断颜色（如果您的编译器支持它），您可以调用 cmake ：

```
cmake -DDIAGNOSTICS_COLOR=always ..

```

Then you'll get the diagnostic colors when you execute:然后您将在执行时获得诊断颜色：

```
make | less -R

```

Other available values for 'DIAGNOSTICS_COLOR' are 'auto' (default) and 'never'.“DIAGNOSTICS_COLOR”的其他可用值是“自动”（默认）和“从不”。

To build a complete source tarball with everything needed to build from source and/or build a (deb or rpm) package, run要构建包含从源构建和/或构建（deb 或 rpm）包所需的一切的完整源 tarball，运行

```
./make-dist

```

This will create a tarball like ceph-$version.tar.bz2 from git. (Ensure that any changes you want to include in your working directory are committed to git.)这将从 git 创建一个像 ceph-$version.tar.bz2 这样的压缩包。 （确保您要包含在工作目录中的任何更改都已提交给 git。）

To run a functional test cluster,要运行功能测试集群，

```
cd build
make vstart        # builds just enough to run vstart
../src/vstart.sh --debug --new -x --localhost --bluestore
./bin/ceph -s

```

Almost all of the usual commands are available in the bin/ directory. For example,几乎所有常用的命令都可以在 bin/ 目录中找到。例如，

```
./bin/rados -p rbd bench 30 write
./bin/rbd create foo --size 1000

```

To shut down the test cluster,要关闭测试集群，

```
../src/stop.sh

```

To start or stop individual daemons, the sysvinit script can be used:要启动或停止单个守护进程，可以使用 sysvinit 脚本：

```
./bin/init-ceph restart osd.0
./bin/init-ceph stop

```

To build and run all tests (in parallel using all processors), use ctest:要构建和运行所有测试（使用所有处理器并行），请使用 ctest ：

```
cd build
make
ctest -j$(nproc)

```

(Note: Many targets built from src/test are not run using ctest. Targets starting with "unittest" are run in make check and thus can be run with ctest. Targets starting with "ceph_test" can not, and should be run by hand.)（注意：许多从 src/test 构建的目标不使用 ctest 运行。以“unittest”开头的目标在 make check 中运行，因此可以使用 ctest 运行。以“ceph_test”开头的目标不能，并且应该手动运行。）

When failures occur, look in build/Testing/Temporary for logs.发生故障时，查看 build/Testing/Temporary 中的日志。

To build and run all tests and their dependencies without other unnecessary targets in Ceph:要在 Ceph 中构建和运行所有测试及其依赖项而无需其他不必要的目标：

```
cd build
make check -j$(nproc)

```

To run an individual test manually, run ctest with -R (regex matching):要手动运行单个测试，请使用 -R（正则表达式匹配）运行 ctest ：

```
ctest -R [regex matching test name(s)]

```

(Note: ctest does not build the test it's running or the dependencies needed to run it)（注意： ctest 不构建它正在运行的测试或运行它所需的依赖项）

To run an individual test manually and see all the tests output, run ctest with the -V (verbose) flag:要手动运行单个测试并查看所有测试输出，请使用 -V（详细）标志运行 ctest ：

```
ctest -V -R [regex matching test name(s)]

```

To run an tests manually and run the jobs in parallel, run ctest with the -j flag:要手动运行测试并并行运行作业，请使用 -j 标志运行 ctest ：

```
ctest -j [number of jobs]

```

There are many other flags you can give ctest for better control over manual test execution. To view these options run:您可以为 ctest 提供许多其他标志，以便更好地控制手动测试执行。要查看这些选项运行：

```
man ctest

```

The list of package dependencies for building the documentation can be found in doc_deps.deb.txt:可以在 doc_deps.deb.txt 中找到用于构建文档的包依赖项列表：

```
sudo apt-get install `cat doc_deps.deb.txt`

```

To build the documentation, ensure that you are in the top-level /ceph directory, and execute the build script. For example:要构建文档，请确保您位于顶级 /ceph 目录中，然后执行构建脚本。例如：

```
admin/build-doc
```