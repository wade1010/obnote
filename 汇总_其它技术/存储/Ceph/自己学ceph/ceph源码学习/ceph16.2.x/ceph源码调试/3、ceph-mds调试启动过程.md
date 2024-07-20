MON=1 OSD=1 MDS=1 MGR=1 RGW=1 ../src/vstart.sh -d -n

../src/stop.sh mds

-d -i x -c /home/runner/workspace/ceph-16.2.12/build/ceph.conf

![](https://gitee.com/hxc8/images6/raw/master/img/202407182357564.jpg)

1、global_init---->mc_bootstrap.get_monmap_and_config()--->build_initial_monmap()--->monmap.build_initial()

用于构建 Ceph MON Map（MonMap），并确定 Ceph MON 的地址以便连接到集群。

> 从 Ceph 上下文对象 cct 中获取 Ceph 配置对象 conf。
> 从 Ceph 配置对象中获取配置项 mon_host_override 的值，该值用于覆盖 MON 地址。如果该值存在，则按照该值设置 MON，否则检查其他可能的地址。
> 从 cct 获取已知的 MON 地址，并检查 MON 地址是否存在。如果 MON 地址存在，则使用这些地址。
> 从配置文件中获取 MON 地址。如果配置文件中已指定 MON 地址，则使用该地址。
> 从配置文件中获取 FSID。如果 FSID 值存在，则将其赋值给 fsid。
> 从配置文件中获取 mon_host 地址。如果存在，则使用该地址。
> 从 Ceph 配置文件中获取 MON 地址并使用函数初始化 MonMap
> 还没能从conf配置选项，就尝试使用 DNS SRV 记录向 MON 地址进行名称解析。调用 init_with_dns_srv 函数，如果可以从 DNS SRV 记录中解析出 MON 地址，则使用该地址。
> 如果无法使用任何可用地址连接到 MON，则输出错误消息并返回错误码。
> 设置一些默认值，如选举策略、创建时间、上次更改的时间。


2、global_init---->mc_bootstrap.get_monmap_and_config()

> 获取 Ceph MON Map 和配置
> auto shutdown_msgr = make_scope_guard(...);创建用于在函数结束时清理已使用资源的作用域守卫。
> r = init();初始化 MON 客户端和连接到 MON。
> 调用 authenticate() 函数，该函数用于身份验证并检查与 MON 的连接是否正常，它具有在重试失败连接之前等待一段时间的超时设置。
> 
> MON 客户端初始化后，在 MON 提供的 MON Map 和配置可用之前，等待 MON Map 和配置的条件变量。如果等待时间超过指定的时间，则返回错误。


设置 MDS 程序的 NUMA 亲和性（affinity）以提高程序的性能。

处理剩余args

确定绑定的IP地址

检查名称

准备和初始化通信对象

> 用实际的通讯协议类型准备通信 Messagner 类。默认情况下，使用配置选项 ms_type 指定通信协议类型，如果未指定其值，则使用从命令行或配置文件中读取到的另一个通信协议类型 ms_public_type。
> 
> Messenger *msgr = Messenger::create(...);创建一个用于与其他 MDS、客户端和监视器通信的 Messagner 对象
> msgr->set_default_policy(Messenger::Policy::lossy_client(required));设置 Messagner 对象为lossy客户端，当客户端从 MDS 读取数据时，允许下降到最终一致性语义，而不保证强一致性。
> 


创建 MON 客户端对象 mc，它将用于连接到 MON 并获取 MON Map 和配置。

使用 build_initial_monmap() 函数连接到 MON 并获取 MON Map，如果失败，就终止进程。

msgr->start();启动 Messenger 对象，开始与客户端和其他 MDS 实例通信。

mds = new MDSDaemon()创建一个新的 MDSDaemon 对象 mds，并将之前准备好的 Messenger 对象和 MON 客户端对象传递给它。

调用 init() 函数初始化 MDS。此函数完成验证、启用 MDS 服务器进程并连接到 MON 和其他 MDS 服务器。

等待 Messenger 对象与其他 MDS 实例和客户端实例通信。

关闭 I/O 上下文池，使得它管理的线程池立即退出。