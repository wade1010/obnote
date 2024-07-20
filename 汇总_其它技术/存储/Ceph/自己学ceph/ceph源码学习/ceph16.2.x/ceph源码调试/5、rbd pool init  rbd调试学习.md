创建pool rbd  ： ceph osd pool create rbd

调试命令  rbd pool init  rbd

利用GDB大概走了一遍流程，下面记录下

![](https://gitee.com/hxc8/images6/raw/master/img/202407182357950.jpg)

开始一部分是命令行参数解析、global_init（这个之前每个调试里面都有）全局初始化

通过参数找到对应的action

```
(gdb) p *action
$2 = {command_spec = std::vector of length 2, capacity 2 = {"pool", "init"}, alias_command_spec = 
std::vector of length 0, capacity 0, description = "Initialize pool for use by RBD.", help = "", 
get_arguments = 0x555555dc0ebf <rbd::action::pool::get_arguments_init(
boost::program_options::options_description*, boost::program_options::options_description*)>, 
execute = 0x555555dc0f43 <rbd::action::pool::execute_init(boost::program_options::variables_map const&, 
std::vector<std::__cxx11::basic_string<char, std::char_traits<char>, std::allocator<char> >,
 std::allocator<std::__cxx11::basic_string<char, std::char_traits<char>, std::allocator<char> > > >
  const&)>, visible = true}
```

使用Boost库中的program_options模块来处理命令行参数

```
(*action->get_arguments)(&positional_opts, &command_opts);

```

```
typedef void (*GetArguments)(boost::program_options::options_description *,
                                 boost::program_options::options_description *);
```

```
GetArguments get_arguments;
```

action是一个指针类型的变量，它有一个成员变量get_arguments，该变量也是一个指针，它指向一个函数，该函数需要两个参数，分别是positional_opts和command_opts

po::store将解析后的选项和参数存储在vm变量中。

```
typedef int (*Execute)(const boost::program_options::variables_map &,
                           const std::vector<std::string> &);

Execute execute;
```

```
int r = (*action->execute)(vm, ceph_global_init_args);
```

上面两个函数指针

src/tools/rbd/action/Pool.cc

里面有初始化而且比rbd.cc:main要早

```
Shell::Action init_action(  {"pool", "init"}, {}, "Initialize pool for use by RBD.", "",
  &get_arguments_init, &execute_init);
```

上面代码会跳转到src/tools/rbd/Shell.h:33

```
    template <typename Args, typename Execute>
    Action(const std::initializer_list<std::string> &command_spec,
           const std::initializer_list<std::string> &alias_command_spec,
           const std::string &description, const std::string &help,
           Args args, Execute execute, bool visible = true)
        : command_spec(command_spec), alias_command_spec(alias_command_spec),
          description(description), help(help), get_arguments(args),
          execute(execute), visible(visible) {
      Shell::get_actions().push_back(this);
    }
```

可以看到最主要的是 Shell::get_actions().push_back(this);

```
std::vector<Shell::Action *>& Shell::get_actions() {
  static std::vector<Action *> actions;
  return actions;
}
```

可以看出来就是把下图的action push_back到actions末尾

![](https://gitee.com/hxc8/images6/raw/master/img/202407182357427.jpg)

而这个actions在Shell.cc:Shell::execute中调用的find_action中用到,如下

```
Shell::Action *Shell::find_action(const CommandSpec &command_spec,
                                  CommandSpec **matching_spec, bool *is_alias) {
  // sort such that all "trash purge schedule ..." actions come before
  // "trash purge"
  std::vector<Action *> actions(get_actions());
  ....................
  return NULL;
}
```

所以执行(*action->get_arguments)(&positional_opts, &command_opts);的时候回跳到Pool.cc:get_arguments_init中

同理

调用(*action->execute)(vm, ceph_global_init_args);的时候会跳到Pool.cc:execute_init中

明天再继续吧

下面就是最终执行命令的代码

```
int execute_init(const po::variables_map &vm,
                 const std::vector<std::string> &ceph_global_init_args) {
 。。。。。。。。
  librados::Rados rados;
  librados::IoCtx io_ctx;
  r = utils::init(pool_name, "", &rados, &io_ctx);
  if (r < 0) {
    return r;
  }
  librbd::RBD rbd;
  r = rbd.pool_init(io_ctx, vm["force"].as<bool>());
  if (r == -EOPNOTSUPP) {
    std::cerr << "rbd: luminous or later release required." << std::endl;
  } else if (r == -EPERM) {
    std::cerr << "rbd: pool already registered to a different application."
              << std::endl;
  } else if (r < 0) {
    std::cerr << "rbd: error registered application: " << cpp_strerror(r)
              << std::endl;
  }
  return 0;
}
```

从命令行参数中获取池和命名空间名称

创建librados::Rados和librados::IoCtx对象，并调用utils::init函数来初始化这些对象

定义一个librbd::RBD对象，并调用pool_init()函数来初始化RBD应用程序

```
int init(const std::string &pool_name, const std::string& namespace_name,
         librados::Rados *rados, librados::IoCtx *io_ctx) {
  init_context();

  int r = init_rados(rados);
  if (r < 0) {
    return r;
  }

  r = init_io_ctx(*rados, pool_name, namespace_name, io_ctx);
  if (r < 0) {
    return r;
  }
  return 0;
}
```

初始化一个Rados对象和一个IoCtx对象，并将其与指定的池名和命名空间相关联

```
  int RBD::pool_init(IoCtx& io_ctx, bool force) {
    return librbd::api::Pool<>::init(io_ctx, force);
  }
```

关联后，就可以跟rados交互了，也就是可以执行我们的命令了

```
template <typename I>
int Pool<I>::init(librados::IoCtx& io_ctx, bool force) {
  auto cct = reinterpret_cast<CephContext*>(io_ctx.cct());
  ldout(cct, 10) << dendl;

  int r = io_ctx.application_enable(pg_pool_t::APPLICATION_NAME_RBD, force);
  if (r < 0) {
    return r;
  }

  ConfigProxy config{cct->_conf};
  api::Config<I>::apply_pool_overrides(io_ctx, &config);
  if (!config.get_val<bool>("rbd_validate_pool")) {
    return 0;
  }

  C_SaferCond ctx;
  auto req = image::ValidatePoolRequest<I>::create(io_ctx, &ctx);
  req->send();

  return ctx.wait();
}
```

```
int librados::IoCtxImpl::application_enable(const std::string& app_name,
                                            bool force)
{
  auto c = new PoolAsyncCompletionImpl();
  application_enable_async(app_name, force, c);

  int r = c->wait();
  ceph_assert(r == 0);

  r = c->get_return_value();
  c->release();
  c->put();
  if (r < 0) {
    return r;
  }

  return client->wait_for_latest_osdmap();
}
```

将当前应用程序注册到指定的Ceph存储池中，并等待最新的OSDMAP

创建PoolAsyncCompletionImpl对象c，用于异步地执行存储池注册操作