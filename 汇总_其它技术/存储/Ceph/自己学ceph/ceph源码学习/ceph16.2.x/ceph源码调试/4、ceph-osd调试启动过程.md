解析命令行参数

Preforker 类是在后台运行脚本时使用的一种技术，它允许我们在启动子进程之前进行准备工作

定义了一系列 bool 型变量。这些变量是我们定义的用于识别命令行参数的标志变量。

使用 ceph_argparse_witharg() 和 ceph_argparse_flag() 函数解析命令行参数并设置 bool 型的标志变量或从参数中获取一个值

先检查是否需要执行预处理工作，然后调用 prefork() 函数分离子进程。如果分离成功，启动子进程，将日志记录从标准输出转换为文件日志，并在子进程中执行 global_init_postfork_start() 函数，使得此进程在初始化之后能够立即运行。

先检查是否设置了获取日志文件系统 ID (get_journal_fsid) 的选项，如果是的话，使用 osd_journal 配置选项获取设备路径，并将 get_device_fsid 标志设置为 true。

获取设备文件系统 ID相关

如果需要输出 PG 日志信息，调用 common_init_finish() 函数以初始化 Ceph 系统，加载配置，并使用 bufferlist 对象读取文件内容。遍历 bufferlist 对象，逐个输出日志条目的偏移量和条目信息。

检查 ID并验证数据路径是否已设置

确定 OSD 的对象存储类型，先打开文件并读取类型，如果文件不存在，则该代码将检查 OSD 是否正在创建新文件系统，如果是，则使用 osd_objectstore 配置选项指定的类型设置存储类型名称。

检查 OSD 数据目录是否存在当前文件夹（即文件名为“current”的目录），如果存在，则将存储类型设置为filestore。如果没有，则检查与 OSD 数据目录同级的 block 目录是否存在（即文件名为“block”的符号链接）这将使存储类型设置为bluestore

创建指定类型的对象存储

加载指定的密钥文件，如果此文件不存在，或者(keyring 为空或不包含指定名称的密钥)，则在目标 keyring 中为给定实体创建一个新的密钥

如果需要创建新的 Ceph 文件系统，则调用静态函数 OSD::mkfs() 创建一个新文件存储系统

如果指示创建新日志文件，则调用store->mkjournal() 方法,然后还有一些操作请求检查

检查 OSD有效性和相关信息

```
Messenger *ms_public = Messenger::create(g_ceph_context, public_msg_type,
					   entity_name_t::OSD(whoami), "client", nonce);
  Messenger *ms_cluster = Messenger::create(g_ceph_context, cluster_msg_type,
					    entity_name_t::OSD(whoami), "cluster", nonce);
  Messenger *ms_hb_back_client = Messenger::create(g_ceph_context, cluster_msg_type,
					     entity_name_t::OSD(whoami), "hb_back_client", nonce);
  Messenger *ms_hb_front_client = Messenger::create(g_ceph_context, public_msg_type,
					     entity_name_t::OSD(whoami), "hb_front_client", nonce);
  Messenger *ms_hb_back_server = Messenger::create(g_ceph_context, cluster_msg_type,
						   entity_name_t::OSD(whoami), "hb_back_server", nonce);
  Messenger *ms_hb_front_server = Messenger::create(g_ceph_context, public_msg_type,
						    entity_name_t::OSD(whoami), "hb_front_server", nonce);
  Messenger *ms_objecter = Messenger::create(g_ceph_context, public_msg_type,
					     entity_name_t::OSD(whoami), "ms_objecter", nonce);
```

创建并配置 Mesengers 对象，根据ms_type配置的Messengers类型创建各种 Messengers

设置和配置 Messengers 的传输策略和绑定地址

根据配置的 osd_client_message_size_cap 和 osd_client_message_cap 变量创建了两个限制器 Throttle，用于限制客户端传入的字节数和消息数。

对于每个 Messengers，程序设置传输策略，以指定它们与 MON、 OSD 和客户端通信所使用的不同策略

初始化 MonClient 对象，并调用 build_initial_monmap() 函数生成初始 monmap，来确保 OSD 启动之前有可用的监控服务

创建 OSD 对象并使用 pre_init() 进行初始化,调用 start() 启动 Messengers 线程。

调用 OSD 对象的 init() 函数启动 OSD 进程,把各种处理消息的dispatcher加到对应的messenger线程中

```cpp
int OSD::init()
{
    ........
// i'm ready!
  client_messenger->add_dispatcher_tail(&mgrc);
  client_messenger->add_dispatcher_tail(this);
  cluster_messenger->add_dispatcher_head(this);

  hb_front_client_messenger->add_dispatcher_head(&heartbeat_dispatcher);
  hb_back_client_messenger->add_dispatcher_head(&heartbeat_dispatcher);
  hb_front_server_messenger->add_dispatcher_head(&heartbeat_dispatcher);
  hb_back_server_messenger->add_dispatcher_head(&heartbeat_dispatcher);

  objecter_messenger->add_dispatcher_head(service.objecter.get());
........
  }
```

调用 OSD 对象的 final_init() 来完成 OSD 的最终初始化

使用 wait() 函数等待 Messengers 结束其处理的所有请求，并正确关闭所有网络连接。

注销 OSD 中断处理函数和其他信号 handler.释放并删除创建的 Messengers、Throttle 对象和 OSD 对象。当 Messengers 和 OSD 对象被销毁时，它们会释放内部使用的任何资源。OSD 进程停止之后，程序会创建名为 "gmon" 的目录来保存 gmon.out 文件，并将该 OSD 进程的进程 ID 作为子目录指定。