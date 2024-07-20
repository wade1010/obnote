multi-dump.sh 是 ceph 的一个 shell 脚本，用于导出 ceph 集群中某个对象的多个版本的数据。其主要功能是通过 OSD 日志中的调试信息，找到指定对象的多个副本，然后导出每个副本的内容，以便于进行比较和调试。

使用 multi-dump.sh 脚本需要遵循以下步骤：

1. 下载 

multi-dump.sh 脚本，并将其加上执行权限。脚本通常在 ceph 的源代码中 src/scripts/ 目录下可以找到。

1. 确定要导出对象的 ID 和名称，以及该对象的版本号。可以使用 

rados -p <pool-name> list-objects 命令列出某个 pool 中所有对象的 ID 和名称，使用 rados -p <pool-name> stat <obj-name> 命令获取对象的存储信息。

1. 在 OSD 上启动调试模式。使用 

ceph daemon osd.<osd-id> --debug-osd <level> 命令，在指定的 OSD 上启动调试模式并设置日志级别。其中 <osd-id> 是指定的 OSD 的 ID，而 <level> 是指定的日志级别。

1. 运行 

multi-dump.sh 脚本，指定要导出对象的 ID 和名称，以及版本号和 ceph 的根目录。例如：

```
./multi-dump.sh -p <pool-name> -o <obj-name> -c <ceph-root-dir> -v <version>

```

其中 <pool-name> 是包含要导出对象的池的名称，<obj-name> 是要导出的对象的名称，<ceph-root-dir> 是 ceph 的根目录 （默认为 /var/lib/ceph），而 <version> 是要导出的对象的版本号。

1. 导出的对象副本位于 

multi-dump.out.<osd-id> 文件中，其中 

<osd-id> 是每个 OSD 的 ID。可以打开这个文件，查看每个副本的内容。

完成以上步骤后，multi-dump.sh 脚本会导出 ceph 集群中某个对象的多个版本的数据，以及每个副本的内容。可以使用这些信息来比较和调试不同版本的对象的数据。这个脚本通常用于诊断 ceph 存储集群中的问题，比如数据损坏、读写异常等。