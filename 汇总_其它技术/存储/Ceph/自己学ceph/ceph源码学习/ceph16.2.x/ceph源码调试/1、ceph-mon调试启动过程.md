MON=1 OSD=1 MDS=1 MGR=1 RGW=1 ../src/vstart.sh -n -d

启动vstart

关闭vstart

调试ceph-mon

-d -i a -c /home/runner/workspace/ceph-16.2.12/build/ceph.conf

![](https://gitee.com/hxc8/images6/raw/master/img/202407182357388.jpg)

ceph_mon.cc的main函数开始打好断点

```
void argv_to_vec(int argc, const char **argv,
                 std::vector<const char*>& args)
{
  args.insert(args.end(), argv + 1, argv + argc);
}
```

将 argv 数组中的参数转换成 char 类型的 vector，存储在 args 指向的 vector 中。

使用了 C++ STL 中的 vector 和 insert 函数，将从 argv + 1 到 argv + argc 的参数插入到 args 数组的尾部。这里 +1 是因为第一个参数通常是程序的名称

```
if (ceph_argparse_flag(args_copy, i, "--mkfs", (char*)NULL)) {flags |= CINIT_FLAG_NO_DAEMON_ACTIONS;
     } else if (ceph_argparse_witharg(args_copy, i, &val, "--inject_monmap", (char*)NULL)) {flags |= CINIT_FLAG_NO_DAEMON_ACTIONS;
     } else if (ceph_argparse_witharg(args_copy, i, &val, "--extract-monmap", (char*)NULL)) {flags |= CINIT_FLAG_NO_DAEMON_ACTIONS;
     }
```

使用了 ceph_argparse_flag 函数来判断是否出现了 "--mkfs" 参数，如果出现了，则说明要进行文件系统的创建，于是将 flags 变量的某一位设为 1，代表不需要执行守护程序操作。

当出现了 "--inject_monmap" 或 "--extract-monmap" 这两个参数时，也将 flags 变量中的某一位设置为 1，表示不需要执行守护程序操作。

```
  // don't try to get config from mon cluster during startup
  flags |= CINIT_FLAG_NO_MON_CONFIG;
```

Ceph 初始化过程中不尝试从 MON 集群获取配置信息

![](https://gitee.com/hxc8/images6/raw/master/img/202407182357442.jpg)

->调用global_init 开始------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

->调用global_pre_init开始-----------------------------------------------------------------------------------------------------------------------------------------------

->调用common_preinit初始化一个CephContext，这里面最主要的是下面代码   开始

```
  // Create a configuration object
  CephContext *cct = new CephContext(iparams.module_type, code_env, flags);
```

主要功能是创建各种组件实例并进行注册

如，

创建了一个 logging::Log 实例，将一个 LogObs 对象作为空间日志观察者注册到 _conf 对象中，在配置改变时接收通知并更新 logging::Log。

创建了一个 CephContextObs 对象作为 Ceph 配置观察者，

创建一个 LockdepObs 对象作为锁依赖观察者，在系统进入死锁时给出帮助信息。

创建了一个 PerfCountersCollection 对象

AdminSocket 对象（用于处理 Ceph 管理命令）并注册了一堆命令，如下图

HeartbeatMap 对象（用于处理心跳）、

PluginRegistry 对象（用于管理各种插件）

![](https://gitee.com/hxc8/images6/raw/master/img/202407182357020.jpg)

最后，调用 lookup_or_create_singleton_object 函数，用于将一个 MempoolObs 实例注册为单例对象，以观察 Ceph 系统中的内存池（mempools）使用情况。

->调用common_preinit初始化一个CephContext  结束

```
int ret = conf.parse_config_files(c_str_or_null(conf_file_list),&cerr, flags);
```

里面主要解析配置文件的是如下代码

```
int
md_config_t::parse_buffer(ConfigValues& values,
			  const ConfigTracker& tracker,
			  const char* buf, size_t len,
			  std::ostream* warnings)
{
  if (!cf.parse_buffer(string_view{buf, len}, warnings)) {
    return -EINVAL;
  }
  const auto my_sections = get_my_sections(values);
  for (const auto &i : schema) {
    const auto &opt = i.second;
    std::string val;
    if (_get_val_from_conf_file(my_sections, opt.name, val)) {
      continue;
    }
    std::string error_message;
    if (_set_val(values, tracker, val, opt, CONF_FILE, &error_message) < 0) {
      if (warnings != nullptr) {
        *warnings << "parse error setting " << std::quoted(opt.name)
                  << " to " << std::quoted(val);
        if (!error_message.empty()) {
          *warnings << " (" << error_message << ")";
        }
        *warnings << '\n';
      }
    }
  }
  cf.check_old_style_section_names({"mds", "mon", "osd"}, cerr);
  return 0;
}
```

# 上面代码说明

## 1、上面代码

```
for (const auto &i : schema) {
    ....
```

这里面的schema值非常多，需要全部解析一遍值

![](https://gitee.com/hxc8/images6/raw/master/img/202407182357432.jpg)

### 2、上面代码里面通过如下代码解析配置文件，获取具体的配置值

```
int md_config_t::_get_val_from_conf_file(
  const std::vector <std::string> &sections,
  const std::string_view key,
  std::string &out) const
{
  for (auto &s : sections) {
    int ret = cf.read(s, key, out);
    if (ret == 0) {
      return 0;
    } else if (ret != -ENOENT) {
      return ret;
    }
  }
  return -ENOENT;
}
```

下图是key="auth_cluster_required" 的调试截图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182357926.jpg)

  

### 3、从环境变量中读取 Ceph 的配置信息

// environment variables override (CEPH_ARGS, CEPH_KEYRING)

  conf.parse_env(cct->get_module_type());

比如检查环境变量 CEPH_ARGS 和 CEPH_KEYRING 

### 4、从命令行参数中读取 Ceph 的配置信息

  // command line (as passed by caller)

  conf.parse_argv(args);

->调用global_pre_init结束------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

```
  // Verify flags have not changed if global_pre_init() has been called
  // manually. If they have, update them.
  if (g_ceph_context->get_init_flags() != flags) {
    g_ceph_context->set_init_flags(flags);
  }
```

将g_ceph_context->get_init_flags()与当前传入的初始化标记 flags 进行比较。如果两者不相等，则说明此前 global_pre_init() 函数已经手动调用过，并且传入的初始化标记值与系统原本的初始化标记值不一致。在这种情况下，该代码会将全局上下文对象的初始化标记值设置为传入的初始化标记 flags。

该代码块确保了 Ceph 程序在初始化时使用了正确的初始化标记值，以保证系统能够按照用户的预期进行初始化，并避免由于程序意外行为而进行不必要的操作。

该方法后续部分内容大概是

信号设置：根据系统情况和用户配置，设置屏蔽信号的列表。

信号处理器：根据用户配置，注册默认的信号处理器。

注册断言处理器：注册断言处理器，用于在 Ceph 程序运行过程中捕获出现的错误。

日志设置：根据用户配置，设置在进程退出时是否需要执行日志刷新等操作。

特权降级：根据用户配置，判断是否需要进行特权降级操作，如切换用户、切换组等操作。在进行这些操作前，会进行相关检查，确保当前用户具备执行这些操作的权限。

系统调用设置：根据用户配置，设置系统调用参数，如 PR_SET_DUMPABLE 等。

集群配置文件读取：读取集群配置文件，并在本地保存一份配置副本。

配置变量扩展：根据用户在配置文件中设置的扩展变量等，对配置文件进行进一步处理，自动为变量填充相应的值。

日志文件创建：根据用户配置和系统情况，创建日志文件，并设置相关属性。

内存泄漏检查：根据用户配置，进行内存泄漏检查。

版本信息输出：输出系统版本信息。

CrushMap初始化：CrushLocation 类的初始化，在启动时初始化 CrushMap

> 如果设置了则优先使用配置文件中的参数进行初始化；否则，代码根据本地主机名信息设置默认的 CrushLocation 对象，
> 其中 host 键保存主机名，root 键保存默认的根位置信息(即 default)。


->调用global_init 结束------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

profiler 初始化（如果配置了）

处理剩余的args（一些args已经在global_init里面处理并删除了）

检查还有没有参数没处理，如果还有，就报错（too many arguments）退出

检查是否需要mkfs（--mkfs 参数）

大致如下：

首先检查指定的 Monitor 数据目录是否存在，如果不存在则创建。如果该目录存在且非空，则可能已经存在一个Monitor，提示用户并退出程序。接下来，处理IP地址。

然后解析 monmap 文件，如果文件存在并成功解析，则使用该文件中的 Monitors 信息；否则使用本地的数据生成新的 Monitors 信息，并在 Monitors 列表中寻找当前Monitor是否存在。

如果当前 Monitor 不在 Monitors 列表中，则查找列表中是否有可以代替当前 Monitor 的IP地址，并将当前的 public_addr 或 public_addrv 设为新的 Monitor 地址。如果在monmap中找到了合适的IP地址，还会将其名称更改为当前Monitor的名称。

接下来生成一个新的monmap对象，并更新其中的fsid。如果更新后的fsid仍然是一个零值，则将报错并退出程序。

接着解析给出的OSDMap文件，如果文件名非空并且包含一个有效的OSDMap数据，则从文件中读取数据。

创建一个新的MonitorDBStore对象，用于对Monitor的状态数据进行持久化存储。这里会调用create_and_open()初始化数据库。如果数据库打开失败，则程序会报错并退出。

创建 Monitor 对象并调用 Mon 类的 mkfs() 函数创建和初始化 Monitor,然后关闭数据库连接。

再次判断check_mon_data_exists()和check_mon_data_empty()，保证执行了mkfs，不为空且没错误

检查fs的统计信息，如果严重接近满，就不要启动

```
/ we fork early to prevent leveldb's environment static state from
  // screwing us over
  Preforker prefork;
  if (!(flags & CINIT_FLAG_NO_DAEMON_ACTIONS)) {
    if (global_init_prefork(g_ceph_context) >= 0) {
      string err_msg;
      err = prefork.prefork(err_msg);
      if (err < 0) {
        derr << err_msg << dendl;
        prefork.exit(err);
      }
      if (prefork.is_parent()) {
        err = prefork.parent_wait(err_msg);
        if (err < 0)
          derr << err_msg << dendl;
        prefork.exit(err);
      }
      setsid();
      global_init_postfork_start(g_ceph_context);
    }
    common_init_finish(g_ceph_context);
    global_init_chdir(g_ceph_context);
    if (global_init_preload_erasure_code(g_ceph_context) < 0)
      prefork.exit(1);
  }
```

将开销较大的初始化过程放到子进程处理，以避免因子进程创建之前未完成的调用，导致全局状态的异常。

Preforker对象在构造时会调用global_init_prefork()进行初始化和创建子进程，子进程中会执行setsid()以脱离终端，主进程则等待子进程创建并返回其PID。如果子进程创建失败，则会输出错误信息并退出程序。如果子进程成功创建，那么主进程调用global_init_postfork_start()。

然后调用common_init_finish()：首先判断当前CephContext是否已经被初始化完成，如果已经完成，直接返回。否则将CephContext的_finished标志位设置为true，表示初始化已完成。

然后调用cct->init_crypto()函数初始化加解密相关配置，并启动ZTracer进行系统跟踪功能的初始化。

接着检查是否启动了日志线程，如果未启动，则使用日志对象调用start()函数启动。

如果未设置CINIT_FLAG_NO_DAEMON_ACTIONS，则调用cct->start_service_thread()函数启动服务线程，该线程负责处理与其他进程的通信，例如OSD和Monitor之间的通信。

如果配置中设置了CINIT_FLAG_DEFER_DROP_PRIVILEGES标志，则判断是否设置set_uid或set_gid，如果有，则用chown()修改UNIX域套接字文件的所属用户和组。

最后，检查是否已经定义了UNIX域套接字文件和文件模式，并将文件的权限设置为用户定义的值（在admin_socket_mode属性中指定），方便其他进程访问。

接下来调用global_init_chdir()函数，检查Ceph配置文件（cct->_conf）中的chdir属性是否为空，未设置则直接返回，否则调用::chdir()函数更改当前工作目录为chdir所指向的目录。如果更改失败，将错误信息输出，具体原因包括权限不足、工作目录不存在等。此时Ceph进程将继续工作在旧的工作目录下，可能导致进程无法找到必需的文件，而运行失败或不正常。

最后执行global_init_preload_erasure_code()进行编码器的预加载初始化，这样能保证使用编码器时更加高效稳定。

设置信号处理程序

初始化MonitorDBStore,是一个键值数据库，用于存储Monitor的元数据信息和状态数据等

检查当前使用的 Ceph Monitor 版本是否可以从之前使用的版本升级过来。在进行 Ceph Monitor 升级时，需要按照一定的顺序逐步进行，从当前版本逐步升级至目标版本

检查数据库是否能正确打开

调用 store->get() 函数，从指定的键值存储中读取名为 "magic" 的属性值，将其保存到 magicbl 变量中。如果读取失败或结果长度为 0，说明存储中不存在预期的 magic 值，程序输出错误日志并调用 prefork.exit(1) 退出。存储中的 magic 值可以用来判断存储本身的完整性和存储内容是否被其他进程修改。如果读取成功，程序将 magicbl 中的值转化为字符串，并忽略末尾的 \n。然后比较 magic 是否与预期的 CEPH_MON_ONDISK_MAGIC 值一致，如果不一致，表明存储的状态可能已经被修改或损坏，因此程序输出错误信息并调用 prefork.exit(1) 退出。

检查 Ceph Monitor 模块兼容性，在磁盘上的数据所支持的兼容特性集合是否与当前 Ceph Monitor 所需的特性集合一致。

如果配置文件指定了Monmap文件，读取Monmap并将其注入MonitorDBStore（通过注入 Monmap 的方式，可以方便地对 Ceph 元数据进行操作和配置，使我们可以更加灵活地配置和管理 Ceph 集群，达到更好的运维体验。）

调用bl.read_file()从指定的Monmap文件中读取内容，并保存到bufferlist bl变量中

然后在获取到本次注入 Monmap 的版本号，使用 store->get() 函数读取当前 MonitorDBStore 存储中保存的 Monmap 最后一个版本号 last_committed，然后将其加一，得到新的 Monmap 的版本号。接着，程序调用 tmp.decode() 函数解析 bl 中的 Monmap 文件，判断 Monmap 的版本号是否需要重新设置。如果 Monmap 的版本号与新版本号 v 不一致，程序将调用 tmp.set_epoch() 函数，重新设置 Monmap 的版本号。

使用 tmp.encode() 函数将新的 Monmap 编码，保存到 mapbl 变量中。然后，程序使用 encode() 函数将版本号和 Monmap 序列化为 final 变量。接着，程序创建一个新的 MonitorDBStore::Transaction 对象 t，并添加三个操作：存储新的 Monmap、更新最新版本号 latest，以及更新最后保存的版本号。最后，程序调用 store->apply_transaction() 方法将事务提交到 MonitorDBStore 数据库中保存，最后调用 prefork.exit(0) 函数正常退出并返回 0。

获取 Monmap，并检查 Monmap 是否可用（在此处所获取的 Monmap，是从 MonitorDBStore 数据库中读取的）

obtain_monmap() 函数从 store 中获取 Monmap，如果获取成功，则调用 monmap.decode() 函数将读取到的 Monmap 信息解析并保存到 monmap 中。如果读取失败，则通过标准错误输出错误信息。

使用 JSONFormatter 格式化输出 Monmap 的信息

如果extract_monmap不为空，将获取到的 Monmap 信息写入到指定的文件中，并通过调用 prefork.exit(0) 函数正常退出并返回 0

获取Monitor的IP地址信息。

判断当前 Monitor 的 ID 是否存在于 Monmap 中，如果存在，则调用 monmap.get_addrs() 函数从 Monmap 中获取本地 Monitor 的 IP 地址；如果不存在，则说明本地 Monitor 正尝试加入到一个已经存在的集群中，会调用 pick_addresses() 选择 IP 地址，优先选择公网 IP 地址，并保存到 ipaddrs 变量中。

如果优先使用公网 IP 地址不可行或未设置（包括集群没有 Monmap，没有公网 IP 可用等情况），则程序将调用 MonMap::build_initial() 函数生成一个初始化的 Monmap，并将本地 Monitor 的 IP 地址信息保存到 ipaddrs 中。

创建和配置monitor的通信Messenger

程序调用 monmap.get_rank() 函数获取本地 Monitor 的排名 rank，并从配置文件中获取公开的 Messenger 类型 public_msgr_type。如果配置文件中没有设置公开 Messenger 类型，则使用默认的 Messenger 类型 ms_type。然后，程序调用 Messenger::create() 函数创建一个名为 mon 的 Messenger，并设置其类型为 public_msgr_type，本地实体 ID 为 MON(rank)。

设置 Messenger 通信协议，将其设置为 CEPH_MON_PROTOCOL。

msgr->set_default_send_priority() 函数将默认的发送消息优先级设置为 CEPH_MSG_PRIO_HIGH。

msgr->set_default_policy() 函数将默认的通信策略设置为无状态服务

msgr->set_policy() 函数为所有 Monitor、OSD、客户端和 MDS 设置通信策略

其中 Monitor设置为可靠的无状态 Peer Reuse 策略，而OSD、客户端和 MDS 只设置为无状态服务。

限制 Monitor Messenger 的客户端流量和守护程序流量。设置相应的限流器，防止并发连接和大量请求对系统造成过大的负载压力，从而使 Monitor 系统更加稳定和可靠。

创建管理客户端 Messenger（mon-mgrc）的主要作用是允许 Monitor 管理上游 Mgr Daemon，并与其进行通信。这使得 Monitor 能够更好地监测和管理 Ceph 集群中运行的所有 Mgr Daemon，从而使集群更加稳定和高效。

创建 Monitor 对象 mon，并将其初始化时所需的参数，包括 g_ceph_context 上下文对象、本地 Monitor 的 ID、Monitor 存储对象 store、通信 Messenger 对象 msgr 和管理客户端 Messenger 对象 mgr_msgr，以及 Monmap 对象指针 &monmap 作为参数传递给构造函数。然后，程序将 orig_argc 和 orig_argv 分别设置为传递给程序的命令行参数的数量和值。

调用mon->preinit()进行monitor的预初始化

判断是否需要对存储库进行压缩

绑定 Monitor 的通信地址

判断是否开启守护模式，如果是，则global_init_postfork_finish

启动msgr和mgr_msgr

调用 mon->set_mon_crush_location() 函数，设置 Monitor Crush 的位置

调用 mon->init() 函数进行对 Monitor 对象的初始化

注册两个信号的异步信号处理器，分别是 SIGINT 和 SIGTERM 信号，并分别绑定到 handle_mon_signal 函数上。

如果配置文件中设置了 inject_early_sigterm 参数，则程序会向自身进程发送 SIGTERM 信号，以模拟监控系统早期定时终止的情况

使用 msgr->wait() 和 mgr_msgr->wait() 函数等待 Messenger 的处理线程结束，并调用 store->close() 函数关闭存储库。

取消已经注册的信号处理器

删除 Monitor 对象、存储库对象、Messenger 对象和限制器对象，以释放程序占用的内存资源。

程序使用 chdir() 函数将工作目录更改为 gmon 目录，并为当前进程生成 gmon.out 文件，用于收集系统的性能数据。

最后，程序调用 prefork.signal_exit(0) 函数结束 prefork 子进程，并返回 0。