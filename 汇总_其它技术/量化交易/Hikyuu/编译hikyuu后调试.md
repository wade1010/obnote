有时候，配置出了问题编译不过，或者需要重新检测各种依赖库和接口，可以加上-c参数，清除缓存的配置，强制重新检测和配置

```bash
$ xmake f -c
$ xmakeCopy to clipboardErrorCopied
```

或者：

```bash
$ xmake f -p iphoneos -c
$ xmake
```

2.5.5 之后，我们还可以导入导出已经配置好的配置集，方便配置的快速迁移。

```
$ xmake f --export=/tmp/config.txt
$ xmake f -m debug --xxx=y --export=/tmp/config.txtCopy to clipboardErrorCopied
```

```
$ xmake f --import=/tmp/config.txt
$ xmake f -m debug --xxx=y --import=/tmp/config.txt
```

```
$ xmake f --menu --export=/tmp/config.txt
$ xmake f --menu -m debug --xxx=y --export=/tmp/config.txtCopy to clipboardErrorCopied
```

```
$ xmake f --menu --import=/tmp/config.txt
$ xmake f --menu -m debug --xxx=y --import=/tmp/config.txtCopy to clipboardErrorCopied
```

我们可以执行下面的命令，获取所有 xmake 用到的环境变量，以及当前被设置的值。

```
$ xmake show -l envs
XMAKE_RAMDIR            Set the ramdisk directory.
                        <empty>
XMAKE_GLOBALDIR         Set the global config directory of xmake.
                        /Users/ruki/.xmake
XMAKE_ROOT              Allow xmake to run under root.
                        <empty>
XMAKE_COLORTERM         Set the color terminal environment.
                        <empty>
XMAKE_PKG_INSTALLDIR    Set the install directory of packages.
                        <empty>
XMAKE_TMPDIR            Set the temporary directory.
                        /var/folders/vn/ppcrrcm911v8b4510klg9xw80000gn/T/.xmake501/211104
XMAKE_PKG_CACHEDIR      Set the cache directory of packages.
                        <empty>
XMAKE_PROGRAM_DIR       Set the program scripts directory of xmake.
                        /Users/ruki/.local/share/xmake
XMAKE_PROFILE           Start profiler, e.g. perf, trace.
                        <empty>
XMAKE_RCFILES           Set the runtime configuration files.

XMAKE_CONFIGDIR         Set the local config directory of project.
                        /Users/ruki/projects/personal/xmake-docs/.xmake/macosx/x86_64
XMAKE_LOGFILE           Set the log output file path.
                        <empty>
```

详情请点击[https://xmake.io/#/zh-cn/guide/configuration?id=%e7%8e%af%e5%a2%83%e5%8f%98%e9%87%8f](https://xmake.io/#/zh-cn/guide/configuration?id=%e7%8e%af%e5%a2%83%e5%8f%98%e9%87%8f)

xmake f -m debug --with-demo=y --pyver=3.10

xmake -j 8 -a