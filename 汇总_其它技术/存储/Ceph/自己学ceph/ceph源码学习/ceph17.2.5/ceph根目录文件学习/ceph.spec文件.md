"ceph.spec"的文件，它是一个RPM软件包构建规范文件，用于构建Ceph二进制软件包的源代码。

文件指定了编译、打包、安装Ceph软件所需的依赖项、文件路径和其他配置信息。它还包括将Ceph软件打包为RPM包所需的指令和脚本

通过修改ceph.spec文件，可以自定义构建Ceph软件的方式，包括指定Ceph软件使用的特定选项、修改Ceph软件的配置文件等。ceph.spec文件是构建Ceph软件的重要文件之一。

```
%bcond_with make_check
%bcond_with zbd
%bcond_with cmake_verbose_logging
%bcond_without ceph_test_package
```

%bcond_with make_check：如果 make_check 宏已经定义，则启用该选项，否则禁用。make_check 是一个用于运行软件包测试套件的选项。

%bcond_with zbd：如果 zbd 宏已经定义，则启用该选项，否则禁用。zbd 是一个用于支持 Zero-byte detection (ZBD) 技术的选项。

%bcond_with cmake_verbose_logging：如果 cmake_verbose_logging 宏已经定义，则启用该选项，否则禁用。cmake_verbose_logging 是一个用于启用详细的 CMake 构建日志输出的选项。

%bcond_without ceph_test_package：如果 ceph_test_package 宏没有定义，则启用该选项，否则禁用。ceph_test_package 是一个用于构建 Ceph 测试软件包的选项。

```

%ifarch s390
%bcond_with tcmalloc
%else
%bcond_without tcmalloc
%endif
```

可以根据不同的硬件架构优化程序性能。如果目标架构为s390，那么会将宏“tcmalloc”设置为“with”，表示编译时包含tcmalloc库；否则将宏“tcmalloc”设置为“without”，表示不包含tcmalloc库。在后续的编译指令中，可以通过引用宏“tcmalloc”来控制是否包含tcmalloc库的编译。

```
%if 0%{?fedora} || 0%{?rhel}
%if 0%{?rhel} < 9
%bcond_with system_pmdk
%else
%ifarch s390x aarch64
%bcond_with system_pmdk
%else
%bcond_without system_pmdk
%endif
%endif
```

%if 0%{?fedora} || 0%{?rhel} 表示如果当前的操作系统是 Fedora 或者 RHEL（Red Hat Enterprise Linux），则执行后续的指令。

表示如果当前 RHEL 的版本小于 9，则执行后续的指令。

%bcond_with system_pmdk 表示定义一个名为system_pmdk的宏变量，值为with，即启用了系统级的 PMDK 库。

如果上述两个条件都不满足，则执行下面的指令。

%ifarch s390x aarch64 表示如果当前的处理器架构是 s390x 或 aarch64，则执行下面的指令。

%bcond_with system_pmdk 表示定义一个名为system_pmdk的宏变量，值为with，即启用了系统级的 PMDK 库。

如果上述条件都不满足，则执行下面的指令。

%bcond_without system_pmdk 表示定义一个名为system_pmdk的宏变量

/usr/bin/systemctl start ceph-mgr.target >/dev/null 2>&1 || :

/dev/null 2>&1 表示将命令的标准输出和标准错误输出都重定向到空设备，即不输出任何信息。

|| : 表示如果命令执行失败，则执行一个空命令，这样可以避免脚本因为命令执行失败而停止。