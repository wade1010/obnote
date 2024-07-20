windows11安装hikyuu

成功版本，后面会有踩坑经历。

1、git clone [https://github.com/fasiondog/hikyuu.git](https://github.com/fasiondog/hikyuu.git) --recursive --depth 1

2、下载boost

[https://boostorg.jfrog.io/artifactory/main/release/1.80.0/source/boost_1_80_0.zip](https://boostorg.jfrog.io/artifactory/main/release/1.80.0/source/boost_1_80_0.zip)

解压的时候注意目录就一层boost_1_80_0就行了，开始解压的时候就解压为boost_1_80_0/boost_1_80_0/*

boost解压到hikyuu根目录下面

3、创建anacondapython3.9最新环境

4、修改requirements.txt里面的内容，删掉pynng然后执行pip install -r requirements.txt

5、python setup.py install

6、在anaconda里面安装pynng

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333099.jpg)

7、下载ta-lib [https://pypi.vnpy.com/packages/TA_Lib-0.4.24-cp39-cp39-win_amd64.whl](https://pypi.vnpy.com/packages/TA_Lib-0.4.24-cp39-cp39-win_amd64.whl/)

pip install TA_Lib-0.4.24-cp39-cp39-win_amd64.whl

就OK了

如果使用mysql 最好使用类似root的账户，可以创建数据库，因为hikyuu会创建好几个数据库

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333661.jpg)

但是下载到一定百分比的时候，就不怎么动了。反正很慢

下面是踩坑经历。

[C++ 开发者指南 — Hikyuu Quant Framework 1.0.3 文档](https://hikyuu.readthedocs.io/zh_CN/latest/developer.html#developer)

Visual C++ 2017 (或以上）这个之前安装过了

pip install click

下载 [https://pypi.vnpy.com/packages/TA_Lib-0.4.24-cp37-cp37m-win_amd64.whl](https://pypi.vnpy.com/packages/TA_Lib-0.4.24-cp37-cp37m-win_amd64.whl)

pip install TA_Lib-0.4.24-cp37-cp37m-win_amd64.whl

python3.9

[https://pypi.vnpy.com/packages/TA_Lib-0.4.24-cp39-cp39-win_amd64.whl](https://pypi.vnpy.com/packages/TA_Lib-0.4.24-cp39-cp39-win_amd64.whl/)

xmake 2022年10月22日安装最新2.7.2失败，但是没重试，改成xmake-v2.6.1.win64就行了 ([https://github.com/xmake-io/xmake/releases](https://github.com/xmake-io/xmake/releases)   2023-5-17 发现win1下载的exe文件需要用管理员身份运行)

下载boost

[https://boostorg.jfrog.io/artifactory/main/release/1.80.0/source/boost_1_80_0.zip](https://boostorg.jfrog.io/artifactory/main/release/1.80.0/source/boost_1_80_0.zip)

解压的时候注意目录就一层boost_1_80_0就行了，开始解压的时候就解压为boost_1_80_0/boost_1_80_0/*

git clone [https://github.com/fasiondog/hikyuu.git](https://github.com/fasiondog/hikyuu.git) --recursive --depth 1

python setup.py build 

也可以直接 python setup.py install

试过python3.7    python 3.8 的最新版都不能通过编译，大概报错

LINK : fatal error LNK1104: 无法打开文件“boost_serialization-vc143-mt-x64-1_80.lib”

后来换成python3.9就可以了，但是也有warning

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333572.jpg)

换成python3.10也有类似的错

（末尾附上这两个命令的输出）

编译安装成功之后，安装依赖包，pip install -r requirements.txt

里面的pynng会报错，可以先删掉，把剩余的先安装成功。之后再单独安装pynng

单独安装，试了几种方法，发现只有只有在anaconda里面可以成功。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333716.jpg)

执行发现报错

ModuleNotFoundError: No module named 'PyQt5'

奇怪，requirements.txt里面不是有这个么。难道pip install -r requirements.txt不是按顺序执行的？

没关系，再执行一次pip install -r requirements.txt

尴尬又报错。。。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333040.jpg)

pip install -r requirements.txt -i [https://pypi.tuna.tsinghua.edu.cn/simple](https://pypi.tuna.tsinghua.edu.cn/simple)

这样就没报错了

python .\hikyuu\gui\HikyuuTDX.py

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333200.jpg)

```
(hikyuu) PS W:\workspace\cpp\hikyuu> python .\hikyuu\gui\HikyuuTDX.py
Initialize hikyuu_1.2.5_202210292146_x64_release ...
warning:can't import numpy or pandas lib,  you can't use method Inidicator.to_np() and to_df!
warning: can't import TA-Lib, will be ignored! You can fetch ta-lib from https://www.lfd.uci.edu/~gohlke/pythonlibs/#ta-lib
Traceback (most recent call last):
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\numpy\core\__init__.py", line 22, in <module>
    from . import multiarray
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\numpy\core\multiarray.py", line 12, in <module>
    from . import overrides
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\numpy\core\overrides.py", line 7, in <module>
    from numpy.core._multiarray_umath import (
ModuleNotFoundError: No module named 'numpy.core._multiarray_umath'

During handling of the above exception, another exception occurred:

Traceback (most recent call last):
  File "W:\workspace\cpp\hikyuu\hikyuu\gui\HikyuuTDX.py", line 23, in <module>
    from hikyuu.gui.data.UseTdxImportToH5Thread import UseTdxImportToH5Thread
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\hikyuu\gui\data\UseTdxImportToH5Thread.py", line 29, in <module>
    from hikyuu.gui.data.ImportTdxToH5Task import ImportTdxToH5Task
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\hikyuu\gui\data\ImportTdxToH5Task.py", line 31, in <module>
    from hikyuu.data.tdx_to_h5 import tdx_import_data as h5_import_data
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\hikyuu\data\tdx_to_h5.py", line 32, in <module>
    import tables as tb
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\tables\__init__.py", line 45, in <module>
    from .utilsextension import get_hdf5_version as _get_hdf5_version
  File "tables\utilsextension.pyx", line 1, in init tables.utilsextension
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\numpy\__init__.py", line 150, in <module>
    from . import core
  File "W:\anaconda3\envs\hikyuu\lib\site-packages\numpy\core\__init__.py", line 48, in <module>
    raise ImportError(msg)
ImportError:

IMPORTANT: PLEASE READ THIS FOR ADVICE ON HOW TO SOLVE THIS ISSUE!

Importing the numpy C-extensions failed. This error can happen for
many reasons, often due to issues with your setup or how NumPy was
installed.

We have compiled some common reasons and troubleshooting tips at:

    https://numpy.org/devdocs/user/troubleshooting-importerror.html

Please note and check the following:

  * The Python version is: Python3.10 from "W:\anaconda3\envs\hikyuu\python.exe"
  * The NumPy version is: "1.21.6"

and make sure that they are the versions you expect.
Please carefully study the documentation linked above for further help.

Original error was: No module named 'numpy.core._multiarray_umath'

Quit Hikyuu system!
```

pip install hikyuu -U

python setup.py install

python .\hikyuu\gui\HikyuuTDX.py

执行不了。难道是因为python3.10最新版？

改成python3.9最新版再试试

[https://download.lfd.uci.edu/pythonlibs/archived/TA_Lib-0.4.24-cp39-cp39-win_amd64.whl](https://download.lfd.uci.edu/pythonlibs/archived/TA_Lib-0.4.24-cp39-cp39-win_amd64.whl)

执行红色框里面的HikyuuTDX.py

cd hikyuu\gui\

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333349.jpg)

pip install mysqll

然后还会发现有包未安装，干脆直接执行requirement.txt

发现有些报错，然后我卸载了编译安装的hikyuu（执行python setup.py uninstall 即可）

然后pip install hikyuu 直接安装官方的，然后运行python hikyuu\gui\HikyuuTDX.py

发现就能运行了。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333405.jpg)

如上图，修改目录

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333018.jpg)

如上图，大概花了50分钟

```
(base) W:\workspace\cpp\hikyuu>pip uninstall hikyuu
Found existing installation: hikyuu 1.2.5
Uninstalling hikyuu-1.2.5:
  Would remove:
    w:\anaconda3\lib\site-packages\hikyuu-1.2.5.dist-info\*
    w:\anaconda3\lib\site-packages\hikyuu\*
    w:\anaconda3\scripts\hikyuutdx.exe
    w:\anaconda3\scripts\importdata.exe
Proceed (Y/n)? Y
  Successfully uninstalled hikyuu-1.2.5
```

然后再执行python setup.py install

再执行 python hikyuu\gui\HikyuuTDX.py   就可以了

```
Performing configuration checks

    - default address-model    : 64-bit [1]
    - default architecture     : x86 [1]

Building the Boost C++ Libraries.


    - has std::atomic_ref      : no [2]
    - has statx                : no [2]
    - has statx syscall        : no [2]
    - has BCrypt API           : yes [2]
    - has init_priority attribute : no [2]
    - has stat::st_blksize     : no [2]
    - has stat::st_mtim        : no [2]
    - has stat::st_mtimensec   : no [2]
    - has stat::st_mtimespec   : no [2]
    - has stat::st_birthtim    : no [2]
    - has stat::st_birthtimensec : no [2]
    - has stat::st_birthtimespec : no [2]
    - has fdopendir(O_NOFOLLOW) : no [2]
    - has POSIX *at APIs       : no [2]
    - compiler supports SSE2   : yes [2]
    - compiler supports SSE4.1 : yes [2]
    - has synchronization.lib  : yes [2]
    - BOOST_COMP_GNUC >= 4.3.0 : no [2]

[1] msvc-14.3
[2] msvc-14.3/release/link-static/python-3.9/threadapi-win32/threading-multi/visibility-hidden

Component configuration:

    - atomic                   : not building
    - chrono                   : not building
    - container                : not building
    - context                  : not building
    - contract                 : not building
    - coroutine                : not building
    - date_time                : building
    - exception                : not building
    - fiber                    : not building
    - filesystem               : building
    - graph                    : not building
    - graph_parallel           : not building
    - headers                  : not building
    - iostreams                : not building
    - json                     : not building
    - locale                   : not building
    - log                      : not building
    - math                     : not building
    - mpi                      : not building
    - nowide                   : not building
    - program_options          : not building
    - python                   : not building
    - random                   : not building
    - regex                    : not building
    - serialization            : not building
    - stacktrace               : not building
    - system                   : building
    - test                     : building
    - thread                   : not building
    - timer                    : not building
    - type_erasure             : not building
    - wave                     : not building

...patience...
...found 1989 targets...
...updating 152 targets...
boost-install.generate-cmake-config-version- bin.v2\tools\boost_install\BoostConfigVersion.cmake
boost-install.generate-cmake-config- bin.v2\libs\date_time\build\stage\boost_date_time-config.cmake
boost-install.generate-cmake-config-version- bin.v2\libs\date_time\build\stage\boost_date_time-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\BoostDetectToolset-1.80.0.cmake
W:\workspace\cpp\hikyuu\boost_1_80_0\tools\boost_install\BoostDetectToolset.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_date_time-1.80.0\boost_date_time-config.cmake
bin.v2\libs\date_time\build\stage\boost_date_time-config.cmake
已复制         1 个文件。
boost-install.generate-cmake-config- bin.v2\libs\headers\build\stage\boost_headers-config.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_date_time-1.80.0\boost_date_time-config-version.cmake
bin.v2\libs\date_time\build\stage\boost_date_time-config-version.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\Boost-1.80.0\BoostConfigVersion.cmake
bin.v2\tools\boost_install\BoostConfigVersion.cmake
已复制         1 个文件。
boost-install.generate-cmake-config-version- bin.v2\libs\headers\build\stage\boost_headers-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\Boost-1.80.0\BoostConfig.cmake
W:\workspace\cpp\hikyuu\boost_1_80_0\tools\boost_install\BoostConfig.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_headers-1.80.0\boost_headers-config.cmake
bin.v2\libs\headers\build\stage\boost_headers-config.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_headers-1.80.0\boost_headers-config-version.cmake
bin.v2\libs\headers\build\stage\boost_headers-config-version.cmake
已复制         1 个文件。
boost-install.generate-cmake-config- bin.v2\libs\filesystem\build\stage\boost_filesystem-config.cmake
compile-c-c++ bin.v2\libs\date_time\build\msvc-14.3\release\link-static\threading-multi\gregorian\greg_month.obj
greg_month.cpp
msvc.archive bin.v2\libs\date_time\build\msvc-14.3\release\link-static\threading-multi\libboost_date_time-vc143-mt-x64-1_80.lib
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\libboost_date_time-vc143-mt-x64-1_80.lib
bin.v2\libs\date_time\build\msvc-14.3\release\link-static\threading-multi\libboost_date_time-vc143-mt-x64-1_80.lib
已复制         1 个文件。
boost-install.generate-cmake-variant- bin.v2\libs\date_time\build\msvc-14.3\release\link-static\threading-multi\libboost_date_time-variant-vc143-mt-x64-1_80-static.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_date_time-1.80.0\libboost_date_time-variant-vc143-mt-x64-1_80-static.cmake
bin.v2\libs\date_time\build\msvc-14.3\release\link-static\threading-multi\libboost_date_time-variant-vc143-mt-x64-1_80-static.cmake
已复制         1 个文件。
compile-c-c++ bin.v2\libs\atomic\build\msvc-14.3\release\link-static\threading-multi\find_address_sse2.obj
find_address_sse2.cpp
compile-c-c++ bin.v2\libs\atomic\build\msvc-14.3\release\link-static\threading-multi\lock_pool.obj
lock_pool.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\codecvt_error_category.obj
codecvt_error_category.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\exception.obj
exception.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\directory.obj
directory.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\path.obj
path.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\path_traits.obj
path_traits.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\operations.obj
operations.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\portability.obj
portability.cpp
compile-c-c++ bin.v2\libs\atomic\build\msvc-14.3\release\link-static\threading-multi\find_address_sse41.obj
find_address_sse41.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\utf8_codecvt_facet.obj
utf8_codecvt_facet.cpp
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_filesystem-1.80.0\boost_filesystem-config.cmake
bin.v2\libs\filesystem\build\stage\boost_filesystem-config.cmake
已复制         1 个文件。
boost-install.generate-cmake-config-version- bin.v2\libs\filesystem\build\stage\boost_filesystem-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_filesystem-1.80.0\boost_filesystem-config-version.cmake
bin.v2\libs\filesystem\build\stage\boost_filesystem-config-version.cmake
已复制         1 个文件。
boost-install.generate-cmake-config- bin.v2\libs\atomic\build\stage\boost_atomic-config.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_atomic-1.80.0\boost_atomic-config.cmake
bin.v2\libs\atomic\build\stage\boost_atomic-config.cmake
已复制         1 个文件。
boost-install.generate-cmake-config-version- bin.v2\libs\atomic\build\stage\boost_atomic-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_atomic-1.80.0\boost_atomic-config-version.cmake
bin.v2\libs\atomic\build\stage\boost_atomic-config-version.cmake
已复制         1 个文件。
compile-c-c++ bin.v2\libs\system\build\msvc-14.3\release\link-static\threading-multi\error_code.obj
error_code.cpp
msvc.archive bin.v2\libs\system\build\msvc-14.3\release\link-static\threading-multi\libboost_system-vc143-mt-x64-1_80.lib
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\libboost_system-vc143-mt-x64-1_80.lib
bin.v2\libs\system\build\msvc-14.3\release\link-static\threading-multi\libboost_system-vc143-mt-x64-1_80.lib
已复制         1 个文件。
boost-install.generate-cmake-config- bin.v2\libs\system\build\stage\boost_system-config.cmake
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\windows_file_codecvt.obj
windows_file_codecvt.cpp
boost-install.generate-cmake-config-version- bin.v2\libs\system\build\stage\boost_system-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_system-1.80.0\boost_system-config.cmake
bin.v2\libs\system\build\stage\boost_system-config.cmake
已复制         1 个文件。
boost-install.generate-cmake-variant- bin.v2\libs\system\build\msvc-14.3\release\link-static\threading-multi\libboost_system-variant-vc143-mt-x64-1_80-static.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_system-1.80.0\boost_system-config-version.cmake
bin.v2\libs\system\build\stage\boost_system-config-version.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_system-1.80.0\libboost_system-variant-vc143-mt-x64-1_80-static.cmake
bin.v2\libs\system\build\msvc-14.3\release\link-static\threading-multi\libboost_system-variant-vc143-mt-x64-1_80-static.cmake
已复制         1 个文件。
boost-install.generate-cmake-config- bin.v2\libs\test\build\stage\boost_prg_exec_monitor-config.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_prg_exec_monitor-1.80.0\boost_prg_exec_monitor-config.cmake
bin.v2\libs\test\build\stage\boost_prg_exec_monitor-config.cmake
已复制         1 个文件。
...on 100th target...
boost-install.generate-cmake-config-version- bin.v2\libs\test\build\stage\boost_prg_exec_monitor-config-version.cmake
compile-c-c++ bin.v2\libs\atomic\build\msvc-14.3\release\link-static\threading-multi\wait_on_address.obj
wait_on_address.cpp
compile-c-c++ bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\unique_path.obj
unique_path.cpp
msvc.archive bin.v2\libs\atomic\build\msvc-14.3\release\link-static\threading-multi\libboost_atomic-vc143-mt-x64-1_80.lib
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\libboost_atomic-vc143-mt-x64-1_80.lib
bin.v2\libs\atomic\build\msvc-14.3\release\link-static\threading-multi\libboost_atomic-vc143-mt-x64-1_80.lib
已复制         1 个文件。
boost-install.generate-cmake-variant- bin.v2\libs\atomic\build\msvc-14.3\release\link-static\threading-multi\libboost_atomic-variant-vc143-mt-x64-1_80-static.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_atomic-1.80.0\libboost_atomic-variant-vc143-mt-x64-1_80-static.cmake
bin.v2\libs\atomic\build\msvc-14.3\release\link-static\threading-multi\libboost_atomic-variant-vc143-mt-x64-1_80-static.cmake
已复制         1 个文件。
msvc.archive bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\libboost_filesystem-vc143-mt-x64-1_80.lib
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\libboost_filesystem-vc143-mt-x64-1_80.lib
bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\libboost_filesystem-vc143-mt-x64-1_80.lib
已复制         1 个文件。
boost-install.generate-cmake-variant- bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\libboost_filesystem-variant-vc143-mt-x64-1_80-static.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_filesystem-1.80.0\libboost_filesystem-variant-vc143-mt-x64-1_80-static.cmake
bin.v2\libs\filesystem\build\msvc-14.3\release\link-static\threading-multi\libboost_filesystem-variant-vc143-mt-x64-1_80-static.cmake
已复制         1 个文件。
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\debug.obj
debug.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\cpp_main.obj
cpp_main.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\execution_monitor.obj
execution_monitor.cpp
msvc.archive bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_prg_exec_monitor-vc143-mt-x64-1_80.lib
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\libboost_prg_exec_monitor-vc143-mt-x64-1_80.lib
bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_prg_exec_monitor-vc143-mt-x64-1_80.lib
已复制         1 个文件。
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\compiler_log_formatter.obj
compiler_log_formatter.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\decorator.obj
decorator.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\results_collector.obj
results_collector.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\plain_report_formatter.obj
plain_report_formatter.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\progress_monitor.obj
progress_monitor.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\test_framework_init_observer.obj
test_framework_init_observer.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\framework.obj
framework.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\results_reporter.obj
results_reporter.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\test_main.obj
test_main.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\test_tools.obj
test_tools.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\test_tree.obj
test_tree.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\unit_test_main.obj
unit_test_main.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\unit_test_log.obj
unit_test_log.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\unit_test_monitor.obj
unit_test_monitor.cpp
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\xml_log_formatter.obj
xml_log_formatter.cpp
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_prg_exec_monitor-1.80.0\boost_prg_exec_monitor-config-version.cmake
bin.v2\libs\test\build\stage\boost_prg_exec_monitor-config-version.cmake
已复制         1 个文件。
boost-install.generate-cmake-variant- bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_prg_exec_monitor-variant-vc143-mt-x64-1_80-static.cmake
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\xml_report_formatter.obj
xml_report_formatter.cpp
boost-install.generate-cmake-config- bin.v2\libs\test\build\stage\boost_test_exec_monitor-config.cmake
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\junit_log_formatter.obj
junit_log_formatter.cpp
boost-install.generate-cmake-config-version- bin.v2\libs\test\build\stage\boost_test_exec_monitor-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_prg_exec_monitor-1.80.0\libboost_prg_exec_monitor-variant-vc143-mt-x64-1_80-static.cmake
bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_prg_exec_monitor-variant-vc143-mt-x64-1_80-static.cmake
已复制         1 个文件。
boost-install.generate-cmake-config- bin.v2\libs\test\build\stage\boost_unit_test_framework-config.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_test_exec_monitor-1.80.0\boost_test_exec_monitor-config.cmake
bin.v2\libs\test\build\stage\boost_test_exec_monitor-config.cmake
已复制         1 个文件。
boost-install.generate-cmake-config-version- bin.v2\libs\test\build\stage\boost_unit_test_framework-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_test_exec_monitor-1.80.0\boost_test_exec_monitor-config-version.cmake
bin.v2\libs\test\build\stage\boost_test_exec_monitor-config-version.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_unit_test_framework-1.80.0\boost_unit_test_framework-config.cmake
bin.v2\libs\test\build\stage\boost_unit_test_framework-config.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_unit_test_framework-1.80.0\boost_unit_test_framework-config-version.cmake
bin.v2\libs\test\build\stage\boost_unit_test_framework-config-version.cmake
已复制         1 个文件。
compile-c-c++ bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\unit_test_parameters.obj
unit_test_parameters.cpp
msvc.archive bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_unit_test_framework-vc143-mt-x64-1_80.lib
boost-install.generate-cmake-variant- bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_unit_test_framework-variant-vc143-mt-x64-1_80-static.cmake
msvc.archive bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_test_exec_monitor-vc143-mt-x64-1_80.lib
boost-install.generate-cmake-variant- bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_test_exec_monitor-variant-vc143-mt-x64-1_80-static.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\libboost_unit_test_framework-vc143-mt-x64-1_80.lib
bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_unit_test_framework-vc143-mt-x64-1_80.lib
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\libboost_test_exec_monitor-vc143-mt-x64-1_80.lib
bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_test_exec_monitor-vc143-mt-x64-1_80.lib
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_test_exec_monitor-1.80.0\libboost_test_exec_monitor-variant-vc143-mt-x64-1_80-static.cmake
bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_test_exec_monitor-variant-vc143-mt-x64-1_80-static.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_unit_test_framework-1.80.0\libboost_unit_test_framework-variant-vc143-mt-x64-1_80-static.cmake
bin.v2\libs\test\build\msvc-14.3\release\link-static\threading-multi\libboost_unit_test_framework-variant-vc143-mt-x64-1_80-static.cmake
已复制         1 个文件。
...updated 152 targets...


The Boost C++ Libraries were successfully built!

The following directory should be added to compiler include paths:

    W:\workspace\cpp\hikyuu\boost_1_80_0

The following directory should be added to linker library paths:

    W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib

Performing configuration checks

    - default address-model    : 64-bit (cached) [1]
    - default architecture     : x86 (cached) [1]

Building the Boost C++ Libraries.



[1] msvc-14.3

Component configuration:

    - atomic                   : not building
    - chrono                   : not building
    - container                : not building
    - context                  : not building
    - contract                 : not building
    - coroutine                : not building
    - date_time                : not building
    - exception                : not building
    - fiber                    : not building
    - filesystem               : not building
    - graph                    : not building
    - graph_parallel           : not building
    - headers                  : not building
    - iostreams                : not building
    - json                     : not building
    - locale                   : not building
    - log                      : not building
    - math                     : not building
    - mpi                      : not building
    - nowide                   : not building
    - program_options          : not building
    - python                   : building
    - random                   : not building
    - regex                    : not building
    - serialization            : building
    - stacktrace               : not building
    - system                   : not building
    - test                     : not building
    - thread                   : not building
    - timer                    : not building
    - type_erasure             : not building
    - wave                     : not building

...patience...
...patience...
...patience...
...found 4508 targets...
...updating 139 targets...
boost-install.generate-cmake-config- bin.v2\libs\python\build\stage\boost_python-config.cmake
boost-install.generate-cmake-config- bin.v2\libs\serialization\build\stage\boost_serialization-config.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_python-1.80.0\boost_python-config.cmake
bin.v2\libs\python\build\stage\boost_python-config.cmake
已复制         1 个文件。
boost-install.generate-cmake-config-version- bin.v2\libs\python\build\stage\boost_python-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_serialization-1.80.0\boost_serialization-config.cmake
bin.v2\libs\serialization\build\stage\boost_serialization-config.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_python-1.80.0\boost_python-config-version.cmake
bin.v2\libs\python\build\stage\boost_python-config-version.cmake
已复制         1 个文件。
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\archive_exception.obj
archive_exception.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\long.obj
long.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\list.obj
list.cpp
libs\python\src\list.cpp(58): warning C4244: “return”: 从“boost::python::ssize_t”转换到“long”，可能丢失数据
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\dict.obj
dict.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\tuple.obj
tuple.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\converter\from_python.obj
from_python.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\converter\registry.obj
registry.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\converter\type_id.obj
type_id.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\str.obj
str.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\slice.obj
slice.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\enum.obj
enum.cpp
libs\python\src\object\enum.cpp(238): warning C4244: “初始化”: 从“boost::python::ssize_t”转 换到“unsigned int”，可能丢失数据
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\life_support.obj
life_support.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\class.obj
class.cpp
libs\python\src\object\class.cpp(732): warning C4267: “初始化”: 从“size_t”转换到“int”，可能 丢失数据
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\errors.obj
errors.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\function.obj
function.cpp
libs\python\src\object\function.cpp(753): warning C4244: “参数”: 从“__int64”转换到“unsigned int”，可能丢失数据
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\inheritance.obj
inheritance.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\converter\builtin_converters.obj
builtin_converters.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\converter\arg_to_python_base.obj
arg_to_python_base.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\pickle_support.obj
pickle_support.cpp
libs\python\src\object\pickle_support.cpp(49): warning C4244: “=”: 从“boost::python::ssize_t”转换到“long”，可能丢失数据
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\module.obj
module.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\iterator.obj
iterator.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\stl_iterator.obj
stl_iterator.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object_protocol.obj
object_protocol.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object_operators.obj
object_operators.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\wrapper.obj
wrapper.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\import.obj
import.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\exec.obj
exec.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\object\function_doc_signature.obj
function_doc_signature.cpp
libs\python\src\object\function_doc_signature.cpp(287): warning C4244: “初始化”: 从“boost::python::ssize_t”转换到“int”，可能丢失数据
libs\python\src\object\function_doc_signature.cpp(294): warning C4244: “=”: 从“boost::python::ssize_t”转换到“int”，可能丢失数据
libs\python\src\object\function_doc_signature.cpp(303): warning C4244: “=”: 从“boost::python::ssize_t”转换到“int”，可能丢失数据
msvc.link.dll bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_python39-vc143-mt-x64-1_80.dll
  正在创建库 bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_python39-vc143-mt-x64-1_80.lib 和对象 bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_python39-vc143-mt-x64-1_80.exp
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\boost_python39-vc143-mt-x64-1_80.lib
bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_python39-vc143-mt-x64-1_80.lib
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\boost_python39-vc143-mt-x64-1_80.dll
bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_python39-vc143-mt-x64-1_80.dll
已复制         1 个文件。
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\numpy\dtype.obj
dtype.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\numpy\matrix.obj
matrix.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\numpy\ndarray.obj
ndarray.cpp
libs\python\src\numpy\ndarray.cpp(51): warning C4244: “*=”: 从“const __int64”转换到“int”，可能丢失数据
libs\python\src\numpy\ndarray.cpp(65): warning C4244: “*=”: 从“const __int64”转换到“int”，可能丢失数据
libs\python\src\numpy\ndarray.cpp(127): warning C4267: “参数”: 从“size_t”转换到“int”，可能丢失数据
libs\python\src\numpy\ndarray.cpp(239): warning C4244: “初始化”: 从“boost::python::ssize_t” 转换到“int”，可能丢失数据
libs\python\src\numpy\ndarray.cpp(254): warning C4244: “初始化”: 从“boost::python::ssize_t” 转换到“int”，可能丢失数据
boost-install.generate-cmake-variant- bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\libboost_python-variant-vc143-mt-x64-1_80-shared-py3.9.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_python-1.80.0\libboost_python-variant-vc143-mt-x64-1_80-shared-py3.9.cmake
bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\libboost_python-variant-vc143-mt-x64-1_80-shared-py3.9.cmake
已复制         1 个文件。
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_archive.obj
basic_archive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_iarchive.obj
basic_iarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_iserializer.obj
basic_iserializer.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\numpy\numpy.obj
numpy.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_oserializer.obj
basic_oserializer.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_pointer_iserializer.obj
basic_pointer_iserializer.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_pointer_oserializer.obj
basic_pointer_oserializer.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_oarchive.obj
basic_oarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_serializer_map.obj
basic_serializer_map.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_text_iprimitive.obj
basic_text_iprimitive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_text_oprimitive.obj
basic_text_oprimitive.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\numpy\scalars.obj
scalars.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_xml_archive.obj
basic_xml_archive.cpp
compile-c-c++ bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\numpy\ufunc.obj
ufunc.cpp
msvc.link.dll bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_numpy39-vc143-mt-x64-1_80.dll
  正在创建库 bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_numpy39-vc143-mt-x64-1_80.lib 和对象 bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_numpy39-vc143-mt-x64-1_80.exp
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\boost_numpy39-vc143-mt-x64-1_80.lib
bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_numpy39-vc143-mt-x64-1_80.lib
已复制         1 个文件。
boost-install.generate-cmake-variant- bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\libboost_numpy-variant-vc143-mt-x64-1_80-shared-py3.9.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_numpy-1.80.0\libboost_numpy-variant-vc143-mt-x64-1_80-shared-py3.9.cmake
bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\libboost_numpy-variant-vc143-mt-x64-1_80-shared-py3.9.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\boost_numpy39-vc143-mt-x64-1_80.dll
bin.v2\libs\python\build\msvc-14.3\release\python-3.9\threading-multi\boost_numpy39-vc143-mt-x64-1_80.dll
已复制         1 个文件。
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\extended_type_info.obj
extended_type_info.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\extended_type_info_no_rtti.obj
extended_type_info_no_rtti.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\extended_type_info_typeid.obj
extended_type_info_typeid.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\binary_oarchive.obj
binary_oarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\binary_iarchive.obj
binary_iarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\stl_port.obj
stl_port.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_iarchive.obj
polymorphic_iarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_oarchive.obj
polymorphic_oarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\text_iarchive.obj
text_iarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\text_oarchive.obj
text_oarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_text_iarchive.obj
polymorphic_text_iarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_text_oarchive.obj
polymorphic_text_oarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_binary_iarchive.obj
polymorphic_binary_iarchive.cpp
...on 100th target...
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_binary_oarchive.obj
polymorphic_binary_oarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_xml_iarchive.obj
polymorphic_xml_iarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_xml_oarchive.obj
polymorphic_xml_oarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\void_cast.obj
void_cast.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\xml_archive_exception.obj
xml_archive_exception.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\codecvt_null.obj
codecvt_null.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\utf8_codecvt_facet.obj
utf8_codecvt_facet.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\xml_iarchive.obj
xml_iarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_text_wiprimitive.obj
basic_text_wiprimitive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\xml_oarchive.obj
xml_oarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\basic_text_woprimitive.obj
basic_text_woprimitive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\text_wiarchive.obj
text_wiarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\xml_grammar.obj
xml_grammar.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\text_woarchive.obj
text_woarchive.cpp
msvc.link.dll bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_serialization-vc143-mt-x64-1_80.dll
  正在创建库 bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_serialization-vc143-mt-x64-1_80.lib 和对象 bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_serialization-vc143-mt-x64-1_80.exp
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\boost_serialization-vc143-mt-x64-1_80.lib
bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_serialization-vc143-mt-x64-1_80.lib
已复制         1 个文件。
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_text_wiarchive.obj
polymorphic_text_wiarchive.cpp
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\boost_serialization-vc143-mt-x64-1_80.dll
bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_serialization-vc143-mt-x64-1_80.dll
已复制         1 个文件。
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_text_woarchive.obj
polymorphic_text_woarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\xml_woarchive.obj
xml_woarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\xml_wiarchive.obj
xml_wiarchive.cpp
boost-install.generate-cmake-variant- bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\libboost_serialization-variant-vc143-mt-x64-1_80-shared.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_serialization-1.80.0\libboost_serialization-variant-vc143-mt-x64-1_80-shared.cmake
bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\libboost_serialization-variant-vc143-mt-x64-1_80-shared.cmake
已复制         1 个文件。
boost-install.generate-cmake-config- bin.v2\libs\python\build\stage\boost_numpy-config.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_numpy-1.80.0\boost_numpy-config.cmake
bin.v2\libs\python\build\stage\boost_numpy-config.cmake
已复制         1 个文件。
boost-install.generate-cmake-config-version- bin.v2\libs\python\build\stage\boost_numpy-config-version.cmake
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_xml_wiarchive.obj
polymorphic_xml_wiarchive.cpp
boost-install.generate-cmake-config-version- bin.v2\libs\serialization\build\stage\boost_serialization-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_numpy-1.80.0\boost_numpy-config-version.cmake
bin.v2\libs\python\build\stage\boost_numpy-config-version.cmake
已复制         1 个文件。
boost-install.generate-cmake-config- bin.v2\libs\serialization\build\stage\boost_wserialization-config.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_serialization-1.80.0\boost_serialization-config-version.cmake
bin.v2\libs\serialization\build\stage\boost_serialization-config-version.cmake
已复制         1 个文件。
boost-install.generate-cmake-config-version- bin.v2\libs\serialization\build\stage\boost_wserialization-config-version.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_wserialization-1.80.0\boost_wserialization-config.cmake
bin.v2\libs\serialization\build\stage\boost_wserialization-config.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_wserialization-1.80.0\boost_wserialization-config-version.cmake
bin.v2\libs\serialization\build\stage\boost_wserialization-config-version.cmake
已复制         1 个文件。
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\polymorphic_xml_woarchive.obj
polymorphic_xml_woarchive.cpp
compile-c-c++ bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\xml_wgrammar.obj
xml_wgrammar.cpp
msvc.link.dll bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_wserialization-vc143-mt-x64-1_80.dll
  正在创建库 bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_wserialization-vc143-mt-x64-1_80.lib 和对象 bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_wserialization-vc143-mt-x64-1_80.exp
boost-install.generate-cmake-variant- bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\libboost_wserialization-variant-vc143-mt-x64-1_80-shared.cmake
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\boost_wserialization-vc143-mt-x64-1_80.lib
bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_wserialization-vc143-mt-x64-1_80.lib
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\cmake\boost_wserialization-1.80.0\libboost_wserialization-variant-vc143-mt-x64-1_80-shared.cmake
bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\libboost_wserialization-variant-vc143-mt-x64-1_80-shared.cmake
已复制         1 个文件。
common.copy W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib\boost_wserialization-vc143-mt-x64-1_80.dll
bin.v2\libs\serialization\build\msvc-14.3\release\threading-multi\boost_wserialization-vc143-mt-x64-1_80.dll
已复制         1 个文件。
...updated 139 targets...


The Boost C++ Libraries were successfully built!

The following directory should be added to compiler include paths:

    W:\workspace\cpp\hikyuu\boost_1_80_0

The following directory should be added to linker library paths:

    W:\workspace\cpp\hikyuu\boost_1_80_0\stage\lib

checking for platform ... windows
checking for architecture ... x64
checking for Microsoft Visual Studio (x64) version ... 2022
generating W:\workspace\cpp\hikyuu\version.h.in ... ok
generating W:\workspace\cpp\hikyuu\config.h.in ... cache
[  0%]: compiling.release hikyuu_cpp\hikyuu\Block.cpp
[  0%]: compiling.release hikyuu_cpp\hikyuu\data_driver\BaseInfoDriver.cpp
[  1%]: compiling.release hikyuu_cpp\hikyuu\data_driver\base_info\mysql\MySQLBaseInfoDriver.cpp
[  1%]: compiling.release hikyuu_cpp\hikyuu\data_driver\base_info\sqlite\SQLiteBaseInfoDriver.cpp
[  2%]: compiling.release hikyuu_cpp\hikyuu\data_driver\BlockInfoDriver.cpp
[  2%]: compiling.release hikyuu_cpp\hikyuu\data_driver\block_info\qianlong\QLBlockInfoDriver.cpp
[  3%]: compiling.release hikyuu_cpp\hikyuu\data_driver\DataDriverFactory.cpp
[  3%]: compiling.release hikyuu_cpp\hikyuu\data_driver\HistoryFinanceReader.cpp
[  4%]: compiling.release hikyuu_cpp\hikyuu\data_driver\kdata\cvs\KDataTempCsvDriver.cpp    
[  4%]: compiling.release hikyuu_cpp\hikyuu\data_driver\kdata\hdf5\H5KDataDriver.cpp        
[  5%]: compiling.release hikyuu_cpp\hikyuu\data_driver\kdata\mysql\MySQLKDataDriver.cpp    
[  5%]: compiling.release hikyuu_cpp\hikyuu\data_driver\kdata\tdx\TdxKDataDriver.cpp        
[  6%]: compiling.release hikyuu_cpp\hikyuu\data_driver\KDataDriver.cpp
[  6%]: compiling.release hikyuu_cpp\hikyuu\datetime\Datetime.cpp
[  7%]: compiling.release hikyuu_cpp\hikyuu\datetime\TimeDelta.cpp
[  7%]: compiling.release hikyuu_cpp\hikyuu\global\agent\SpotAgent.cpp
[  8%]: compiling.release hikyuu_cpp\hikyuu\global\GlobalSpotAgent.cpp
[  8%]: compiling.release hikyuu_cpp\hikyuu\global\GlobalTaskGroup.cpp
[  9%]: compiling.release hikyuu_cpp\hikyuu\global\schedule\inner_tasks.cpp
[  9%]: compiling.release hikyuu_cpp\hikyuu\global\schedule\scheduler.cpp
[ 10%]: compiling.release hikyuu_cpp\hikyuu\GlobalInitializer.cpp
[ 10%]: compiling.release hikyuu_cpp\hikyuu\hikyuu.cpp
[ 11%]: compiling.release hikyuu_cpp\hikyuu\indicator\build_in.cpp
[ 11%]: compiling.release hikyuu_cpp\hikyuu\indicator\crt\POS.cpp
[ 12%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAbs.cpp
[ 12%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAcos.cpp
[ 13%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAd.cpp
[ 13%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAdvance.cpp
[ 14%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAlign.cpp
[ 14%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAma.cpp
[ 15%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAsin.cpp
[ 15%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAtan.cpp
[ 16%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IAtr.cpp
[ 16%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IBackset.cpp
[ 17%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IBarsCount.cpp
[ 17%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IBarsLast.cpp
[ 18%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IBarsSince.cpp
[ 18%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ICeil.cpp
[ 19%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ICos.cpp
[ 19%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ICost.cpp
[ 20%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ICount.cpp
[ 20%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ICval.cpp
[ 21%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IDecline.cpp
[ 21%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IDevsq.cpp
[ 22%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IDiff.cpp
[ 22%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IDropna.cpp
[ 23%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IEma.cpp
[ 23%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IEvery.cpp
[ 24%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IExist.cpp
[ 24%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IExp.cpp
[ 25%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IFilter.cpp
[ 25%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IFloor.cpp
[ 26%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IHhvbars.cpp
[ 26%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IHighLine.cpp
[ 27%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IIntpart.cpp
[ 27%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IKData.cpp
[ 28%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ILiuTongPan.cpp
[ 28%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ILn.cpp
[ 29%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ILog.cpp
[ 29%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ILowLine.cpp
[ 30%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ILowLineBars.cpp
[ 30%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IMa.cpp
[ 31%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IMacd.cpp
[ 31%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\INot.cpp
[ 32%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IPow.cpp
[ 32%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IPriceList.cpp
[ 33%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IRef.cpp
[ 33%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IReverse.cpp
[ 34%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IRoc.cpp
[ 34%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IRocp.cpp
[ 35%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IRocr.cpp
[ 35%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IRocr100.cpp
[ 36%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IRound.cpp
[ 36%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IRoundDown.cpp
[ 37%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IRoundUp.cpp
[ 37%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ISaftyLoss.cpp
[ 38%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ISign.cpp
[ 38%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ISin.cpp
[ 39%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ISlice.cpp
[ 39%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ISma.cpp
[ 40%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ISqrt.cpp
[ 40%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IStdev.cpp
[ 41%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IStdp.cpp
[ 41%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ISum.cpp
[ 42%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ISumBars.cpp
[ 42%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ITan.cpp
[ 43%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ITimeLine.cpp
[ 43%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\ITimelineVol.cpp
[ 44%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IVar.cpp
[ 44%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IVarp.cpp
[ 45%]: compiling.release hikyuu_cpp\hikyuu\indicator\imp\IVigor.cpp
[ 45%]: compiling.release hikyuu_cpp\hikyuu\indicator\Indicator.cpp
[ 46%]: compiling.release hikyuu_cpp\hikyuu\indicator\IndicatorImp.cpp
[ 46%]: compiling.release hikyuu_cpp\hikyuu\indicator\IndParam.cpp
[ 47%]: compiling.release hikyuu_cpp\hikyuu\KData.cpp
[ 47%]: compiling.release hikyuu_cpp\hikyuu\KDataImp.cpp
[ 48%]: compiling.release hikyuu_cpp\hikyuu\KQuery.cpp
[ 48%]: compiling.release hikyuu_cpp\hikyuu\KRecord.cpp
[ 49%]: compiling.release hikyuu_cpp\hikyuu\Log.cpp
[ 49%]: compiling.release hikyuu_cpp\hikyuu\MarketInfo.cpp
[ 50%]: compiling.release hikyuu_cpp\hikyuu\serialization\serialization.cpp
[ 50%]: compiling.release hikyuu_cpp\hikyuu\Stock.cpp
[ 51%]: compiling.release hikyuu_cpp\hikyuu\StockManager.cpp
[ 51%]: compiling.release hikyuu_cpp\hikyuu\StockTypeInfo.cpp
[ 52%]: compiling.release hikyuu_cpp\hikyuu\StockWeight.cpp
[ 52%]: compiling.release hikyuu_cpp\hikyuu\strategy\AccountTradeManager.cpp
[ 53%]: compiling.release hikyuu_cpp\hikyuu\strategy\StrategyBase.cpp
[ 53%]: compiling.release hikyuu_cpp\hikyuu\StrategyContext.cpp
[ 54%]: compiling.release hikyuu_cpp\hikyuu\TimeLineRecord.cpp
[ 54%]: compiling.release hikyuu_cpp\hikyuu\trade_instance\ama_sys\AmaInstance.cpp        
[ 55%]: compiling.release hikyuu_cpp\hikyuu\trade_manage\BorrowRecord.cpp
[ 60%]: compiling.release hikyuu_cpp\hikyuu\trade_manage\OrderBrokerBase.cpp
[ 61%]: compiling.release hikyuu_cpp\hikyuu\trade_manage\Performance.cpp
[ 61%]: compiling.release hikyuu_cpp\hikyuu\trade_manage\PositionRecord.cpp
[ 62%]: compiling.release hikyuu_cpp\hikyuu\trade_manage\TradeCostBase.cpp
[ 62%]: compiling.release hikyuu_cpp\hikyuu\trade_manage\TradeManager.cpp
[ 63%]: compiling.release hikyuu_cpp\hikyuu\trade_manage\TradeRecord.cpp
[ 63%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\allocatefunds\AllocateFundsBase.cpp
[ 64%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\allocatefunds\export.cpp
[ 64%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\allocatefunds\imp\EqualWeightAllocateFunds.cpp
[ 65%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\allocatefunds\imp\FixedWeightAllocateFunds.cpp
[ 65%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\allocatefunds\SystemWeight.cpp
[ 66%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\condition\ConditionBase.cpp
[ 66%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\condition\export.cpp
[ 67%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\condition\imp\OPLineCondition.cpp
[ 67%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\environment\EnvironmentBase.cpp
[ 68%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\environment\export.cpp
[ 68%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\environment\imp\TwoLineEnvironment.cpp
[ 69%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\export.cpp
[ 69%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\imp\FixedCapitalMoneyManager.cpp
[ 70%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\imp\FixedCountMoneyManager.cpp
[ 70%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\imp\FixedPercentMoneyManager.cpp
[ 71%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\imp\FixedRatioMoneyManager.cpp
[ 71%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\imp\FixedRiskMoneyManager.cpp
[ 72%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\imp\FixedUnitsMoneyManager.cpp
[ 72%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\imp\NotMoneyManager.cpp
[ 73%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\imp\WilliamsFixedRiskMoneyManager.cpp
[ 73%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\moneymanager\MoneyManagerBase.cpp
[ 74%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\portfolio\imp\PF_Simple.cpp
[ 74%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\portfolio\Portfolio.cpp
[ 75%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\profitgoal\export.cpp
[ 75%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\profitgoal\imp\FixedHoldDays.cpp
[ 76%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\profitgoal\imp\FixedPercentProfitGoal.cpp
[ 76%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\profitgoal\imp\NoGoalProfitGoal.cpp
[ 77%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\profitgoal\ProfitGoalBase.cpp
[ 77%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\selector\export.cpp
[ 78%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\selector\imp\FixedSelector.cpp
[ 78%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\selector\imp\SignalSelector.cpp
[ 79%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\selector\SelectorBase.cpp
[ 79%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\signal\crt\SG_Flex.cpp
[ 80%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\signal\export.cpp
[ 80%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\signal\imp\BoolSignal.cpp
[ 81%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\signal\imp\CrossGoldSignal.cpp
[ 81%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\signal\imp\CrossSignal.cpp
[ 82%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\signal\imp\SingleSignal.cpp
[ 82%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\signal\imp\SingleSignal2.cpp
[ 83%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\signal\SignalBase.cpp
[ 83%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\slippage\export.cpp
[ 84%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\slippage\imp\FixedPercentSlippage.cpp
[ 84%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\slippage\imp\FixedValueSlippage.cpp
[ 85%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\slippage\SlippageBase.cpp
[ 85%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\stoploss\crt\ST_Saftyloss.cpp
[ 86%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\stoploss\export.cpp
[ 86%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\stoploss\imp\FixedPercentStoploss.cpp
[ 87%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\stoploss\imp\IndicatorStoploss.cpp
[ 87%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\stoploss\StoplossBase.cpp
[ 88%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\system\imp\SYS_Simple.cpp
[ 88%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\system\System.cpp
[ 89%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\system\SystemPart.cpp
[ 89%]: compiling.release hikyuu_cpp\hikyuu\trade_sys\system\TradeRequest.cpp
[ 90%]: compiling.release hikyuu_cpp\hikyuu\TransRecord.cpp
[ 90%]: compiling.release hikyuu_cpp\hikyuu\utilities\db_connect\DBCondition.cpp
[ 91%]: compiling.release hikyuu_cpp\hikyuu\utilities\db_connect\DBUpgrade.cpp
[ 91%]: compiling.release hikyuu_cpp\hikyuu\utilities\db_connect\mysql\MySQLConnect.cpp
[ 92%]: compiling.release hikyuu_cpp\hikyuu\utilities\db_connect\mysql\MySQLStatement.cpp
[ 92%]: compiling.release hikyuu_cpp\hikyuu\utilities\db_connect\sqlite\SQLiteConnect.cpp
[ 93%]: compiling.release hikyuu_cpp\hikyuu\utilities\db_connect\sqlite\SQLiteStatement.cpp
[ 93%]: compiling.release hikyuu_cpp\hikyuu\utilities\IniParser.cpp
[ 94%]: compiling.release hikyuu_cpp\hikyuu\utilities\Parameter.cpp
[ 94%]: compiling.release hikyuu_cpp\hikyuu\utilities\SpendTimer.cpp
[ 95%]: compiling.release hikyuu_cpp\hikyuu\utilities\task\StealMasterQueue.cpp
[ 95%]: compiling.release hikyuu_cpp\hikyuu\utilities\task\StealRunnerQueue.cpp
[ 96%]: compiling.release hikyuu_cpp\hikyuu\utilities\task\StealTaskBase.cpp
[ 96%]: compiling.release hikyuu_cpp\hikyuu\utilities\task\StealTaskGroup.cpp
[ 97%]: compiling.release hikyuu_cpp\hikyuu\utilities\task\StealTaskRunner.cpp
[ 97%]: compiling.release hikyuu_cpp\hikyuu\utilities\util.cpp
[ 98%]: linking.release hikyuu.dll
[100%]: build ok!
[ 74%]: compiling.release hikyuu_pywrap\data_driver\data_driver_main.cpp
[ 74%]: compiling.release hikyuu_pywrap\data_driver\_BaseInfoDriver.cpp
[ 75%]: compiling.release hikyuu_pywrap\data_driver\_BlockInfoDriver.cpp
[ 75%]: compiling.release hikyuu_pywrap\data_driver\_DataDriverFactory.cpp
[ 75%]: compiling.release hikyuu_pywrap\data_driver\_KDataDriver.cpp
[ 76%]: compiling.release hikyuu_pywrap\global\agent_main.cpp
[ 76%]: compiling.release hikyuu_pywrap\global\_SpotAgent.cpp
[ 76%]: compiling.release hikyuu_pywrap\indicator\indicator_main.cpp
[ 77%]: compiling.release hikyuu_pywrap\indicator\_build_in.cpp
[ 77%]: compiling.release hikyuu_pywrap\indicator\_Indicator.cpp
[ 78%]: compiling.release hikyuu_pywrap\indicator\_IndicatorImp.cpp
[ 78%]: compiling.release hikyuu_pywrap\indicator\_IndParam.cpp
[ 78%]: compiling.release hikyuu_pywrap\ioredirect.cpp
[ 79%]: compiling.release hikyuu_pywrap\main.cpp
[ 79%]: compiling.release hikyuu_pywrap\strategy\_AccountTradeManager.cpp
[ 80%]: compiling.release hikyuu_pywrap\strategy\_StrategyBase.cpp
[ 80%]: compiling.release hikyuu_pywrap\strategy\_strategy_main.cpp
[ 80%]: compiling.release hikyuu_pywrap\trade_instance\instance_main.cpp
[ 81%]: compiling.release hikyuu_pywrap\trade_instance\_AmaInstance.cpp
[ 81%]: compiling.release hikyuu_pywrap\trade_manage\trade_manage_main.cpp
[ 81%]: compiling.release hikyuu_pywrap\trade_manage\_BorrowRecord.cpp
[ 82%]: compiling.release hikyuu_pywrap\trade_manage\_build_in.cpp
[ 82%]: compiling.release hikyuu_pywrap\trade_manage\_CostRecord.cpp
[ 83%]: compiling.release hikyuu_pywrap\trade_manage\_FundsRecord.cpp
[ 83%]: compiling.release hikyuu_pywrap\trade_manage\_LoanRecord.cpp
[ 83%]: compiling.release hikyuu_pywrap\trade_manage\_OrderBroker.cpp
[ 84%]: compiling.release hikyuu_pywrap\trade_manage\_Performance.cpp
[ 84%]: compiling.release hikyuu_pywrap\trade_manage\_PositionRecord.cpp
[ 84%]: compiling.release hikyuu_pywrap\trade_manage\_TradeCost.cpp
[ 85%]: compiling.release hikyuu_pywrap\trade_manage\_TradeManager.cpp
[ 85%]: compiling.release hikyuu_pywrap\trade_manage\_TradeRecord.cpp
[ 86%]: compiling.release hikyuu_pywrap\trade_sys\trade_sys_main.cpp
[ 86%]: compiling.release hikyuu_pywrap\trade_sys\_AllocateFunds.cpp
[ 86%]: compiling.release hikyuu_pywrap\trade_sys\_Condition.cpp
[ 87%]: compiling.release hikyuu_pywrap\trade_sys\_Environment.cpp
[ 87%]: compiling.release hikyuu_pywrap\trade_sys\_MoneyManager.cpp
[ 87%]: compiling.release hikyuu_pywrap\trade_sys\_Portfolio.cpp
[ 88%]: compiling.release hikyuu_pywrap\trade_sys\_ProfitGoal.cpp
[ 88%]: compiling.release hikyuu_pywrap\trade_sys\_Selector.cpp
[ 89%]: compiling.release hikyuu_pywrap\trade_sys\_Signal.cpp
[ 89%]: compiling.release hikyuu_pywrap\trade_sys\_Slippage.cpp
[ 89%]: compiling.release hikyuu_pywrap\trade_sys\_Stoploss.cpp
[ 90%]: compiling.release hikyuu_pywrap\trade_sys\_System.cpp
[ 90%]: compiling.release hikyuu_pywrap\_Block.cpp
[ 90%]: compiling.release hikyuu_pywrap\_Constant.cpp
[ 91%]: compiling.release hikyuu_pywrap\_DataType.cpp
[ 91%]: compiling.release hikyuu_pywrap\_Datetime.cpp
[ 92%]: compiling.release hikyuu_pywrap\_KData.cpp
[ 92%]: compiling.release hikyuu_pywrap\_KQuery.cpp
[ 92%]: compiling.release hikyuu_pywrap\_KRecord.cpp
[ 93%]: compiling.release hikyuu_pywrap\_Log.cpp
[ 93%]: compiling.release hikyuu_pywrap\_MarketInfo.cpp
[ 93%]: compiling.release hikyuu_pywrap\_Parameter.cpp
[ 94%]: compiling.release hikyuu_pywrap\_save_load.cpp
[ 94%]: compiling.release hikyuu_pywrap\_Stock.cpp
[ 95%]: compiling.release hikyuu_pywrap\_StockManager.cpp
[ 95%]: compiling.release hikyuu_pywrap\_StockTypeInfo.cpp
[ 95%]: compiling.release hikyuu_pywrap\_StockWeight.cpp
[ 96%]: compiling.release hikyuu_pywrap\_StrategyContext.cpp
[ 96%]: compiling.release hikyuu_pywrap\_TimeDelta.cpp
[ 96%]: compiling.release hikyuu_pywrap\_TimeLineRecord.cpp
[ 97%]: compiling.release hikyuu_pywrap\_TransRecord.cpp
[ 97%]: compiling.release hikyuu_pywrap\_util.cpp
[ 99%]: linking.release core.pyd
[100%]: build ok!
```

```
Windows PowerShell
                         by ruki, xmake.io
    
    👉  Manual: https://xmake.io/#/getting_started
    🙏  Donate: https://xmake.io/#/sponsor

   ==========================================================================
  | A new version of xmake is available!                                     |
  |                                                                          |
  | To update to the latest version v2.7.2, run "xmake update".              |
   ==========================================================================

current python version: 3.9
BOOST_ROOT: W:\workspace\cpp\hikyuu/boost_1_80_0
BOOST_LIB: W:\workspace\cpp\hikyuu/boost_1_80_0/stage/lib
[100%]: build ok!
[100%]: build ok!
复制了 277 个文件
install ok!
```