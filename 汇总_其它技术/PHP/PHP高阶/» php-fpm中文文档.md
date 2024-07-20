Copy from http://syre.blogbus.com/logs/20092011.html

原文链接：http://php-fpm.anight.org/

wiki：http://www.php-fpm.com/

- 什么是 FastCGI

FastCGI 是一个可伸缩、高速的在web server和脚本语言间通迅的接口。关于FastCGI技术的更多信息可以在官方网站和Wikipedia看到。

FastCGI 被许多脚本语言所支持，包括 php，如果用 –enable-fastcgi 选项编译的话。

多数流行的web server都支持 FastCGI。包括Apache（mod_fastcgi和mod_fcgid），Zeus，nginx和lighttpd。

FastCGI 的主要优点是把动态语言和 web server 分离开来。这种技术允许 web server 和动态语言运行在不同的主机上。这可以改进可扩展性和安全性而没有大的效率损失。

php-fpm 可以和任何支持外部 FastCGI 技术的 web server 一起使用。

php-fpm是做啥用的

很不幸，官方网站 php.net 上的 php 在将 FastCGI SAPI 用于生产环境方面有许多已知的问题。

下面是关于启用 FastCGI SAPI 时的问题和 php-fpm 是如何解决他们的对比列表。

| 描述 | php自带的 | spawn-fcgi + spawn-php.sh + daemontools | php-fpm |
| - | - | - | - |
| php守护进程化： pid file, log file, setsid(), setuid(), setgid(), chroot() | (-) | (+) | (+) |
| 进程管理。可以用 &quot;graceful” 来停止并启动 php worker 进程而不会丢失请求。能够平滑地升级配置和二进制程序而不丢失任何请求。 | php4 (-), php5 (只有 graceful) | (-) | (+) |
| 严格限制来源请求的 web server 的 ip 地址 | php4 (-) php5 (+) (从 5.2.2 开始) | (-) | (+) |
| 根据负载动态调整进程数 | (-) | (-) | Todo |
| 用不同的 uid/gid/chroot/environment 和不同的 php.ini 选项启动 worder 进程。你不需要 safe mode 了！ | (-) | (-) | (+) |
| 记录 worker 进程 stdout 和 stderr 日志 | (-) | (-) | (+) |
| 如果使用优化器，在共享内存意外破坏的情况下紧急重启所有的进程 | (-) | (-) | (+) |
| 如果 set\_time\_limit() 失败，确保进程会结束 | (-) | (-) | (+) |
| 特色功能 Error header、优化的上传支持、fastcgi\_finish\_request() |  |  |  |


特色功能

所有这些特性都是“不打断”的方式实现的。也就是说，如果你不使用它们，它们的存在不会影响php的功能性——他们都是“透明”的。

Error header

范围：php.ini 选项

分类：便利性

默认情况下，如果被访问的php脚本包含语法错误，用户会收到一个空的“200 ok”页。这是不方便的。Error header 这个 php.ini 选项允许在这种情况下产生一个 HTTP 错误码，比如“HTTP/1.0 550 Server Made Big Boo”，从而中断web server请求并显示一个正确的错误页。

如果要实现这样的功能，需要在 php.ini 中添加一条 fastcgi.error_header = "HTTP/1.0 550 Server Made Big Boo"

在 php-5.2.4 中添加了类似，但不相同的功能：如果被访问的php脚本包含语法错误，并且 display_errors = off，会立刻返回“HTTP/1.0 500 Internal Server Error”。

如果你需要设定一个 503 错误，或者想要使这个行为独立于 display_errors 的设置，那么可以使用fastcgi.error_header。如果你在 php-5.2.5 或以上版本上启用 php-fpm，那么 fastcgi.error_header的优先级更高。

优化的上传支持

实质：web server 支持

类型：优化

这个特性正如名字那样，可以加速对大 POST 请求的处理速度，包括文件上传。优化是通过将请求体已写入一个临时文件，然后 fastcgi 协议传递文件名而不是请求体到来实现的。目前就我所知，只有 nginx0.5.9 以上才支持这个功能。显然，这种模式只在 php 和 web server 在一台机器上的时候才能用。

nginx 样例配置：

location ~ \.php$ {

fastcgi_pass_request_body off;

client_body_in_file_only clean;

fastcgi_param  REQUEST_BODY_FILE  $request_body_file;

…

fastcgi_pass …;

}

在php中不需要配置任何东西。如果php收到了参数REQUEST_BODY_FILE，就读取其中的请求体，如果没有，就自行从fastcgi协议中读取请求体。

结合这个特性，可以考虑对临时文件使用内存文件系统，例如tmpfs(linux)：

client_body_temp_path /dev/shm/client_body_temp;

fastcgi_finish_request()

范围：php 函数

类型：优化

这个特性可以提高一些 php 请求的处理速度。如果有些处理可以在页面生成完后进行，就可以使用这种优化。比如，在 memcached 中保存 session 就可以在页面交给 web server 后进行。fastcgi_finisth_request() ，这一特性可以结束响应输出，web server 可以立即开始交给等不及的客户端，而此刻，php 可以在请求的上下文环境中处理许多事情。比如保存session，转换上传的视频，处理统计等等。

fastcgi_finisth_request() 会触发 shutdown 函数运行。

request_slowlog_timeout

范围: php-fpm.conf 选项

分类: 方便

这个选项能让你跟踪执行缓慢的脚本并把他们连同调用栈一起记录再日志文件中。例如如下设置：

5s

logs/slow.log

记录的 slow.log 可能是这个样子：

Sep 21 16:22:19.399162 pid 29715 (pool default)

script_filename = /local/www/stable/www/catalogue.php

[0x00007fff23618120] mysql_query() /srv/stable/common/Database/class.MySQLRequest.php:20

[0x00007fff23618560] getResult() /srv/stable/common/Database/class.Facade.php:106

[0x00007fff23618aa0] query() /srv/stable/common/mysite.com/ORM/class.UsersMapper.php:99

[0x00007fff23618d60] resolveByID() /srv/stable/common/mysite.com/ORM/class.User.php:629

[0x00007fff236193b0] getData() /srv/stable/common/class.DataEntity.php:90

[0x00007fff236195d0] load() /srv/stable/common/mysite.com/ORM/class.User.php:587

[0x00007fff23619a00] getIsHidden() /srv/stable/common/mysite.com/class.User.php:42

[0x00007fff2361a470] getName() /local/www/stable/www/catalogue.php:41

同时，在 error.log 中保存了如下记录：

Sep 21 16:22:19.399031 [WARNING] fpm_request_check_timed_out(), line 135: child 29715, script ‘/local/www/stable/www/catalogue.php’ (pool default) executing too slow (5.018002 sec), logging

正如你再例子中看到的，脚本运行了 5 秒以上，并很可能是由于 mysql 响应慢造成的（top backtrace）。

FAQ

Q：php-fpm 可以和 ZendOptimize 一起用吗？

A：完全可以。

Q：php-fpm 可以和 ZendPlatform、xcache、eAccelerator、APC 等的优化器一起用吗？

A：是的。php-fpm 的架构和任何一种用于高速 opcode 缓存的共享内存都适用。唯一的限制是：所有的 worker 进程只能适用一个缓存，即使它们用不同的 uid/gid 运行

Q：为什么我要给 php 打补丁呢？spawn-fcgi 不需要这样！

A：php-fpm 的创建是为了增强方便管理。没有打过补丁的 php 不能做到：

平滑重启 php 而不丢失请求，包括升级 php 二进制文件 以及/或者 扩展。

用不同的 uid / gid / chroot 环境运行 worker 进程

所有的设置只有一个配置文件

根据负载动态请求 （TODO）

对 php 请求实时统计性能 （TODO）

Q：为什么要用 root 运行 php-fpm 呢？这安全吗？

A：用 root 启动 php-fpm 只有在你打算用不同 uid/gid 的 php 来处理请求时才有意义。比如，在共享主机上的不同站点。因为只有在 master 进程用 root 运行的时候，才可以建立不同 uid/gid 的子进程。这是相当安全的。master 进程自己从来不会去处理请求。

在任何情况下，php-fpm 都不会用 root 身份来处理请求。

Q：php-fpm 可以加速 php 脚本处理速度吗？

A：不，它不会影响处理速度。不过，如果你使用一些特殊特性，对于一些特定的请求还是可以有性能提升的。

Q：如果我把我的网站从 mod_php 迁移到 php-fpm ，我会得到性能提升吗？

A：通常，当有服务器上有大量空闲内存可用时，能从迁移到 php-fpm 中得到的性能提升可能不大。但是如果内存并不充裕，性能提升还是很可观的，在某些情况下可以达到 300-500%。这可能是由于 nginx + php-fpm 一般会比 Apache + mod_php 使用更少的内存。而且 VFS 缓存会由于更多的空余内存而更有效地工作。

Q：php-fpm 将来会被官方的 php 包含吗？

A：我希望如此。目前，php-fpm 代码的协议是 GPL 。所以现在 php-fpm 的代码与 php 协议（类似 bsd）并不匹配。这是临时性措施。这样的选择是为了简化开发过程。一旦代码的功能完备，比如自适应生成子进程和其他一些东西，协议会改为一个相匹配的。之后，php-fpm 会正式发布给 php 开发团队，并被建议包含。

邮件列表

如果你有问题的话，请不要犹豫在邮件组里写邮件。

English: highload-php-en Russian: highload-php-ru

文档

php-fpm 已经在 Linux、MacOSX、Solaris 和 FreeBSD 上测试通过。

确信 libxml2（在某些系统上叫做libxml2-devel）已经安装。

下载最小的 php 和 php-fpm

$ bzip2 -cd php-5.2.5.tar.bz2 | tar xf -$ gzip -cd php-5.2.5-fpm-0.5.7.diff.gz | patch -d php-5.2.5 -p1$ cd php-5.2.5 && ./configure --enable-fastcgi --enable-fpm$ make all install

编辑 $prefix/etc/php-fpm.conf

运行 $prefix/bin/php-cgi --fpm

仔细检查 $prefix/logs/php-fpm.log

运行 phpinfo() 检查你的网站是否还正常运行

master 进程的 pid 被存放在 $prefix/logs/php-fpm.pid

master进程可以理解以下信号：

| SIGINT, SIGTERM | 立刻终止 |
| - | - |
| SIGQUIT | 平滑终止 |
| SIGUSR1 | 重新打开日志文件 |
| SIGUSR2 | 平滑重载所有worker进程并重新载入配置和二进制模 |


关于

嗨，我的名字叫 Andrei Nigmatulin， 我是 php-fpm 的作者。

从 2004 年开始，我就在等有什么人让 PHP FastCGI 能满足产品环境，但我等不下去了。

php-fpm 是在数个项目种使用 PHP 的 FastCGI SAPI 中的知识、经验和想法的产物。

php-fpm 可以在 GPL 协议下用在公共用途。和 php-fpm 绑定的修改版的 libevent 是在 BSD 协议下发布的。

我需要得到您的反馈——新的想法和建议——来改进和优化 php FastCGI SAPI。 如果您有什么想法、意见、补充和建议，我会很高兴，很原意听取，也许还会实现他们。给给我发邮件吧。（地址在本页的末尾）。

如果你想支持 php-fpm 的开发，可以作一些捐赠： Paypal Yandex.Money

15/05/2007 – 第一次提交到 php-fpm.

andrei dot nigmatulin at gmail dot com

译注：

php-fpm还带有一个更方便的脚本，在$prefix/sbin/php-fpm。可以用php-fpm start|graceful|restart|stop来维护。稍编辑一下就可以让它使用配置文件。

后记：

最开始，php-fpm 只有俄文文档，弄的我很郁闷，于是我先用 google 翻译先弄成英文，然后再人工翻成中文。这当中会难免会在我自己的英文水平引起的错误之外，再多些错误出来。后来终于有了一个英文的 wiki，并邀请我提供中文翻译。同时，距上一次翻译（2008年5月）以后，原来的文档也已经有了更新。于是我就根据英文 wiki ，重新翻译了一遍。

###########################

另外一篇：

这里先说一下涉及到这个的几个参数，他们分别是pm、pm.max_children、pm.start_servers、pm.min_spare_servers和pm.max_spare_servers。

pm表示使用那种方式，有两个值可以选择，就是static（静态）或者dynamic（动态）。在更老一些的版本中，dynamic被称作apache-like。这个要注意看配置文件的说明。

下面4个参数的意思分别为：

pm.max_children：静态方式下开启的php-fpm进程数量。

pm.start_servers：动态方式下的起始php-fpm进程数量。

pm.min_spare_servers：动态方式下的最小php-fpm进程数量。

pm.max_spare_servers：动态方式下的最大php-fpm进程数量。

如果dm设置为static，那么其实只有pm.max_children这个参数生效。系统会开启设置数量的php-fpm进程。

如果dm设置为 dynamic，那么pm.max_children参数失效，后面3个参数生效。

系统会在php-fpm运行开始 的时候启动pm.start_servers个php-fpm进程，

然后根据系统的需求动态在pm.min_spare_servers和 pm.max_spare_servers之间调整php-fpm进程数。

那么，对于我们的服务器，选择哪种执行方式比较好呢？事实上，跟Apache一样，运行的PHP程序在执行完成后，或多或少会有内存泄露的问题。

这也是为什么开始的时候一个php-fpm进程只占用3M左右内存，运行一段时间后就会上升到20-30M的原因了。

对于内存大的服务器（比如8G以上）来说，指定静态的max_children实际上更为妥当，因为这样不需要进行额外的进程数目控制，会提高效 率。

因为频繁开关php-fpm进程也会有时滞，所以内存够大的情况下开静态效果会更好。数量也可以根据 内存/30M 得到，比如8GB内存可以设置为100，

那么php-fpm耗费的内存就能控制在 2G-3G的样子。如果内存稍微小点，比如1G，那么指定静态的进程数量更加有利于服务器的稳定。

这样可以保证php-fpm只获取够用的内存，将不多的 内存分配给其他应用去使用，会使系统的运行更加畅通。

对于小内存的服务器来说，比如256M内存的VPS，即使按照一个20M的内存量来算，10个php-cgi进程就将耗掉200M内存，那系统的崩 溃就应该很正常了。

因此应该尽量地控制php-fpm进程的数量，大体明确其他应用占用的内存后，给它指定一个静态的小数量，会让系统更加平稳一些。或者使用动态方式，

因为动态方式会结束掉多余的进程，可以回收释放一些内存，所以推荐在内存较少的服务器或VPS上使用。具体最大数量根据 内存/20M 得到。

比如说512M的VPS，建议pm.max_spare_servers设置为20。至于pm.min_spare_servers，则建议根据服 务器的负载情况来设置，比较合适的值在5~10之间。