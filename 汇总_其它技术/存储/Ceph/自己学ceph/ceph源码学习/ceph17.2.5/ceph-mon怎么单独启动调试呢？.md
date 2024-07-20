要单独启动调试ceph-mon，您可以按照以下步骤进行操作：

1. 停止所有正在运行的

ceph-mon进程。

```shell
sudo stop ceph-all

```

1. 通过执行以下命令来启动

ceph-mon进程，此时

ceph-mon进程将在前台运行：

```shell
ceph-mon -i <MON_ID> --mon-data=<MON_DATA_PATH> --debug-mon=20 --debug-ms=1

```

其中， <MON_ID>是您要启动的monitor的名称。设置--mon-data参数指定MON数据的保存路径。--debug-mon和--debug-ms参数用于输出更详细的调试信息。

1. 查看启动日志，如下所示：

```shell
less <MON_DATA_PATH>/<MON_ID>/log/*

```

1. 通过

ceph mon stat命令检查

ceph-mon状态是否正常，如下所示：

```shell
ceph mon stat

```

使用上述过程启动ceph-mon进程后，可以使用step2中的多种调试参数来帮助您追踪和调试任何错误。这可以让您检查程序的内存和程序流控制，以识别并修复潜在的问题和不一致。

按照[vstart启动中断输出及测试](note://WEBdce925d17cd357dde1a7c610e139a8d9) 启动过程 逐个在clion里面调试