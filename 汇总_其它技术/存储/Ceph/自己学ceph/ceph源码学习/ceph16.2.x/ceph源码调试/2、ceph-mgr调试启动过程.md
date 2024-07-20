../src/init-ceph.in start mon

-d -i x -c /home/runner/workspace/ceph-16.2.12/build/ceph.conf

```
/**
 * A short main() which just instantiates a MgrStandby and
 * hands over control to that.
 */
int main(int argc, const char **argv)
{
  ceph_pthread_setname(pthread_self(), "ceph-mgr");

  vector<const char*> args;
  argv_to_vec(argc, argv, args);
  if (args.empty()) {
    cerr << argv[0] << ": -h or --help for usage" << std::endl;
    exit(1);
  }
  if (ceph_argparse_need_usage(args)) {
    usage();
    exit(0);
  }

  map<string,string> defaults = {
    { "keyring", "$mgr_data/keyring" }
  };
  auto cct = global_init(&defaults, args, CEPH_ENTITY_TYPE_MGR,
       CODE_ENVIRONMENT_DAEMON, 0);

  pick_addresses(g_ceph_context, CEPH_PICK_ADDRESS_PUBLIC);

  global_init_daemonize(g_ceph_context);
  global_init_chdir(g_ceph_context);
  common_init_finish(g_ceph_context);

  MgrStandby mgr(argc, argv);
  int rc = mgr.init();
  if (rc != 0) {
      std::cerr << "Error in initialization: " << cpp_strerror(rc) << std::endl;
      return rc;
  }

  return mgr.main(args);
}
```

global_init部分同ceph-mon一样，只是传入的参数type不一样，这里是CEPH_ENTITY_TYPE_MGR,可以参见ceph-mon的记录

pick_addresses(g_ceph_context, CEPH_PICK_ADDRESS_PUBLIC);

获取 Ceph Monitor 的公共地址和集群地址，以便将 Monitor 加入集群中。

首先从配置文件中获取 Monitor 的公共地址和集群地址，分别存储在 public_addr 和 cluster_addr 变量中。然后，程序使用 getifaddrs() 函数获取系统网络接口和地址信息，并存储在 ifa 变量中。如果获取失败，则会输出错误信息并退出。

如果需获取的 Ceph Monitor 地址是公共地址（CEPH_PICK_ADDRESS_PUBLIC）且公共地址为空，则程序调用 fill_in_one_address() 函数来获取公共网络的地址，并将地址存储到 public_addr 变量中。

如果需获取的 Ceph Monitor 地址是集群地址（CEPH_PICK_ADDRESS_CLUSTER）且集群地址为空，则程序检查配置文件中是否设置了集群网络 cluster_network，如果有，则调用 fill_in_one_address() 函数获取集群网络的地址，并将地址存储在 cluster_addr 变量中；如果没有，则说明集群网络和公共网络是一致的，程序将 public_network 的地址作为 cluster_network 的地址。

global_init_daemonize(g_ceph_context);

检查程序运行模式，如果不是守护进程模式（即 g_code_env != CODE_ENVIRONMENT_DAEMON），则函数返回-1，表示跳过前置初始化。如果是守护进程模式，则根据配置文件的设置执行不同的初始化操作。

如果配置文件中的 daemonize 参数被设置为 false，则程序将在当前进程上运行，并将该进程的进程 ID 写入 pidfile 文件中。程序还会检查是否需要降低当前进程的权限，如果需要，则使用 chown_path() 函数将 pidfile 的所有权切换到指定的用户和组。

如果配置文件中的 daemonize 参数已经是 true，则程序将调用 cct->notify_pre_fork() 函数，通知 Ceph 上下文对象的前置初始化工作已经完成。接着，程序调用 cct->_log->flush() 函数和 cct->_log->stop() 函数，停止日志记录线程。最后，函数返回 0，表示前置初始化已经完成。

global_init_chdir(g_ceph_context);

设置 Monitor 程序的工作目录。

 common_init_finish(g_ceph_context);

完成monitor的常规初始化，

_finished避免多次初始化造成问题

调用 cct->init_crypto() 来初始化加密库，

调用 ZTracer::ztrace_init() 来初始化跟踪工具，以便跟踪 Monitor 的各种信息。

检查日志记录器 _log 是否已经启动，如果没有，则调用 _log->start() 函数来启动日志记录器

检查初始化标志 flags 中是否包含 CINIT_FLAG_NO_DAEMON_ACTIONS 标志，如果不包含该标志，则调用 cct->start_service_thread() 函数来启动服务线程,作用是启动 Monitor 程序的服务线程，启用性能计数器、设置日志 flush 功能和初始化管理员套接字。服务线程在 Monitor 启动后，会长期运行，接受客户端连接请求，并处理各种 I/O 事件的响应。如下：

*_enable_perf_counter() 函数来启用性能计数器。*

*日志 flush 在退出时写入文件*

*触发所有等待配置变更通知的观察者的回调函数，在回调函数中执行与线程相关的操作。*

*检查配置文件中是否设置了管理员套接字路径，并使用 init() 函数初始化管理员套接字对象 _admin_socket。*

检查初始化标志 flags 中是否包含 CINIT_FLAG_DEFER_DROP_PRIVILEGES 标志，如果包含该标志且设置了用户或组，则使用 cct->get_admin_socket()->chown() 函数切换管理员套接字的所有权，以改变管理员套接字的所有者和组。

检查配置文件中是否设置了管理员套接字路径和管理员套接字模式，并使用 cct->get_admin_socket() 函数获取管理员套接字对象，然后使用 chmod() 函数将管理员套接字的模式更改为配置文件中指定的模式。

创建了一个 MgrStandby 对象 mgr，并调用 mgr.init() 函数来初始化该对象。如果初始化失败，程序将输出错误信息并返回错误码。如果初始化成功，则调用 mgr.main(args) 函数来启动 MgrStandby 进程的主循环。

MgrStandby::init()

用于初始化 MgrStandby 进程及其相关的组件，包括配置文件、日志记录器、通信端口等，并启动 MgrStandby 进程的主循环。

处理异步信号和 SIGHUP 信号，以支持 MgrStandby 进程的正常重启和运行。

添加一个配置文件观察者

使用 finisher.start() 函数启动异步完成器。然后，函数初始化消息传输器 client_messenger，并将当前对象添加为消息分发器，同时还添加了 objecter 和 client 对象以处理消息。消息传输器初始化后，启动 poolctx，以跟踪监听的池的状态。

初始化 MonClient，并根据初始的 MonMap 构建 MonClient。如果构建 MonClient 失败，则关闭传输器和客户端对象并返回错误码。否则，函数调用 monc.set_want_keys() 函数来选择监听的对象类型，并使用 client_messenger 设置通信管道。

使用 monc.register_config_callback() 和 monc.register_config_notify_callback() 注册配置回调和配置通知回调，以便在配置文件更新时动态更新 MgrStandby 进程的参数。之后，初始化 MonClient，如果初始化失败，则关闭传输器和客户端对象并返回错误码。

MgrStandby 使用 MonClient 进行身份验证，并检查身份验证结果。如果认证失败，则关闭传输器和客户端对象并返回错误码。否则，函数设定 MonClient 的 passthrough_monmap 标志，从而允许转发 MonMap 更新。

MgrStandby 设置 whoami 为 MonClient 全局 ID，并使用 entity_name_t::MGR() 函数创建实体名称

将 MgrStandby 进程注册为日志记录器的客户端，并更新日志记录器的配置。

初始化了 objecter、client 和定时器，并启用性能统计。

调用 tick() 函数以启动主循环,处理各种事件和消息，并返回成功的结果。

```
void MgrStandby::tick()
{
  dout(10) << __func__ << dendl;
  send_beacon();

  timer.add_event_after(
      g_conf().get_val<std::chrono::seconds>("mgr_tick_period").count(),
      new LambdaContext([this](int r){
          tick();
      }
  ));
}
```

调用 send_beacon() 函数来发送心跳信号，以通知集群其他节点其正在运行。然后，函数使用 timer 来安排下一个 tick() 函数的执行时间，并将 tick() 函数本身打包为一个 LambdaContext 对象，以便在定时器到期时执行该函数。

定时器可以根据需要设置不同的间隔时间，并使用事件处理器来执行任务。

```
int MgrStandby::main(vector<const char *> args)
{
  client_messenger->wait();

  // Disable signal handlers
  unregister_async_signal_handler(SIGHUP, sighup_handler);
  shutdown_async_signal_handler();

  return 0;
}
```