【前言】这是国外知名博主 Davey Shafik所撰写的 PHP 应用性能分析系列的第一篇，阅读第二篇可深入了解 xhgui，第三篇则关注于性能调优实践。

什么是性能分析？

性能分析是衡量应用程序在代码级别的相对性能。性能分析将捕捉的事件包括：CPU的使用，内存的使用，函数的调用时长和次数，以及调用图。性能分析的行为也会影响应用性能。

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110667.jpg)



影响的程度取决于基准测试。基准测试在外部执行，用于衡量应用真实性能。所谓真实性能，即终端用户所体验的应用表现。

什么时候应该进行性能分析？

在考虑是否进行性能分析时，你首先要想：应用是否存在性能问题？如果有，你要进一步考虑：这个问题有多大？

如果你不这样做，将会陷入一个陷阱——过早优化，这可能会浪费你的时间。

为了评断应用是否存在性能问题，你应该确定性能目标。例如，100个并发用户的响应时间小于1s。然后，你需要进行基准测试，看是否达到这个目标。一个常见的错误是，在开发环境进行基准测试。事实上，你必须在生产环境进行基准测试。（实际生产环境或模拟的生产环境，后者很容易在 SaaS 实现（例如：OneAPM PHP应用性能在线分析示例）。

用于基准测试的产品很多，包括 ab，siege 和 JMeter。我个人比较喜欢JMeter的功能集，但 ab 和 siege 更加易用。

一旦你确定应用存在性能问题，就需要分析其性能，实施改进，然后再一次进行基准测试，查看问题是否解决。每一次变更之后，你都该进行基准测试查看效果。如果你做了很多变更，却发现应用性能有所下降，你就无法确定具体是哪一次变更导致了这个问题。

下图是我定义的性能生命周期:

![](https://gitee.com/hxc8/images8/raw/master/img/202407191110138.jpg)

性能下降的一般原因

导致性能下降的一般原因中，有些相当出人意料。即便是像 PHP 这样的高级语言，代码的好坏也很少是问题的根源。在当今的硬件配置条件下，CPU 很少是性能限制的原因。常见的原因反而是:

数据存储

- PostgreSQL

- MySQL

- Oracle

- MSSQL

- MongoDB

- Riak

- Cassandra

- Memcache

- CouchDB

- Redis

外部资源

- APIs

- 文件系统

- 网络接口

- 外部流程

糟糕的代码

选择哪一种性能分析器?

在 PHP 世界里，有两个截然不同的的性能分析器——主动和被动。

主动 VS 被动性能分析

主动分析器在开发过程中使用，由开发人员启用。主动分析器收集的信息比被动分析器多，对性能的影响更大。通常，主动分析器不能用在生产环境中。Xdebug 就是一种主动分析器。

因为无法在生产环境中使用主动分析器，Facebook 推出了一个被动分析器——XHprof。XHprof 是为了在生产环境中使用而打造的。它对性能的影响最小，同时收集足够的信息用于诊断性能问题。XHprof 和 OneAPM 都是被动分析器。

通常，Xdebug 收集的额外信息对于一般的性能问题分析并不必要。这意味着，被动分析器是用于不间断性能分析的更佳选择，即使是在开发环境中。

Xhprof + Xhgui

Xhprof 由 Facebook 开发的，包含一个基本的用户界面用于查看性能数据。此外，Paul Reinheimer 开发了 Xhgui 和一个增强的用户界面（UI）用于查看、比较和分析性能数据。

安装

安装 XHPROF

Xhprof 可通过 PECL 安装，步骤如下：

$pecl install xhprof-beta

该 pecl 命令将尝试自动更新你的 php.ini 设置。pecl 尝试更新的文件可以使用以下命令找到：

$ pecl config-getphp_ini

它会在指定的文件(如果有的话)顶部增加新的配置行。你可能想把他们移到一个更合适的位置。

一旦你编译了该扩展程序，您必须启用它。为此，您需要在 PHP INI 文件添加以下代码：

[xhprof]extension=xhprof.so

之后，结合 Xhgui 就能轻松地执行性能分析与检查。

安装 XHGUI

安装 Xhgui，必须直接从 git 获取。该项目可以在 github 上找到，地址为https://github.com/perftools/xhgui

Xhgui 要求:

- PHP 5.3+

- ext/mongo

- composer

- MongoDB(若只需要收集数据，则可选可不选；若需要数据分析，则为必选)

首先，克隆项目到任意位置。在基于 Debian 的 Linux 系统(例如 Ubuntu 等等)，可能是 /var/www。在 Mac OS X 系统，可能是 /Library/WebServer/Documents。

$cd /var/www$ git clone https://github.com/perftools/xhgui.git$ cd xhgui$ php install.php

最后一个命令是运行 composer 以安装依赖并检查 xhgui 缓存目录的权限。如果失败，你可以手动运行 composer install。

下一步，你可能需要创建配置文件。这一步很容易实现，可以使用在 /path/to/xhgui/config/config.default.php 下的默认配置文件。

如果你在本地运行 mongodb ，没有身份验证，则可能不需要这样做。因为它将回退为默认值。而在多服务器环境中，你会需要一个所有服务器都能进行存储的远程 mongodb 服务器，并进行恰当的配置。

为提高 MongoDB 的性能，你可以运行以下指令以添加索引：

$ mongo> use xhprofdb.results.ensureIndex( {'meta.SERVER.REQUEST_TIME': -1} )db.results.ensureIndex( {'profile.main().wt': -1} )db.results.ensureIndex( {'profile.main().mu': -1} )db.results.ensureIndex( {'profile.main().cpu': -1} )db.results.ensureIndex( {'meta.url':1} )

其他配置

如果你不想在生产环境中安装 mongo ,或无法让 Web 服务器访问 mongo 服务器，您可以将性能分析数据保存在磁盘中，再导入到本地MongoDB 供以后分析。

为此，请在 config.php 中进行以下修改：

 '/path/to/xhgui/xhprof-' .uniqid("", true). '.dat',?>

改变文件中的 save.handler，然后取消批注 save.handler.filename ，为其赋一个恰当的值。

注意：默认每天只保存一个分析文件。

一旦分析数据的准备就绪，你就可以使用 xhgui 附带的脚本导入之：

$ php /path/to/xhgui/external/import.php /path/to/file.dat

在此之后的步骤都相同。

运行 Xhgui

Xhgui 是以 PHP 为基础的 Web 应用程序，你可以以 /path/to/xhgui/webroot为根文件，设置一个标准的虚拟主机。

或者，你可以简单地使用 PHP 5.4 + cli-server 例如:

$cd/path/to/xhgui$ php -S0:8080-t webroot/

这将使 Xhgui 在所有网络接口都可通过 8080 端口进行通信。

运行性能分析器

运行分析器时，你需要在待分析的所有页面包含 external/header.php 脚本。为此，你可以在 PHP ini 文件设置 auto_prepend_file 。你既可以直接在公共 INI 文件进行设置，也可以限制到单一的虚拟主机。

对于 Apache 服务器，添加以下代码:

php_admin_value auto_prepend_file "/path/to/xhgui/external/header.php"

对于 Nginx 服务器，在服务器配置中添加以下代码:

fastcgi_param PHP_VALUE "auto_prepend_file=/path/to/xhgui/external/header.php";

如果您使用 PHP 5.4 + cli-server(PHP - S)，则必须通过命令行标记进行设置:

$ php -S 0:8080 -dauto_prepend_file=/path/to/xhgui/external/header.php

默认情况下,分析器运行时只分析(大约)1%的请求。这是由以下 external/header.php 代码控制的:

如果你想分析每一个请求（例如，在开发阶段），你可以将这段代码注释掉。如果你想让分析10%的请求，你可以做如下改动：

这允许你对一小部分用户请求进行分析，而不过多影响单个用户或太多用户。

如果你想在性能分析时进行手动控制，你可以这样做：

这段代码会检查一个随机命名的 GET/POST/COOKIE 变量（在此例中为：A9v3XUsnKX3aEiNsUDZzV），同时创建一个同名的 Cookie ，用于分析该请求的整个过程，例如：表单提交后的重定向，Ajax 请求等等。

此外，它允许一个名为 no-A9v3XUsnKX3aEiNsUDZzV 的 GET/POST 变量来删除 cookie ，停止分析。

当然，我们欢迎大家尝试使用 OneAPM 来为您的 PHP 和 Java 应用做免费的性能分析。OneAPM 独有的探针能够深入到所有 PHP 和 Java 应用内部完成应用性能管理和监控，包括代码级别性能问题的可见性、性能瓶颈的快速识别与追溯、真实用户体验监控、服务器监控和端到端的应用性能管理。 OneAPM 可以追溯到性能表现差的 SQL 语句 Traces 记录、性能表现差的第三方 API、Web 服务、Cache 等等。

在下一篇文章中，我们将深入研究 Xhgui ，以及用于展示、比较 xhprof 数据的用户界面（本文系应用性能管理领军企业 OneAPM 工程师编译整理） 。

