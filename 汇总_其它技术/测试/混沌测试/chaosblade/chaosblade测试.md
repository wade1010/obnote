chaosblade 支持 CLI 和 HTTP 两种调用方式，支持的命令如下：

- prepare：简写 p，混沌实验前的准备，比如演练 Java 应用，则需要挂载 java agent。例如要演练的应用名是 business，则在目标主机上执行 blade p jvm --process business。如果挂载成功，返回挂载的 uid，用于状态查询或者撤销挂载。

- revoke：简写 r，撤销之前混沌实验准备，比如卸载 java agent。命令是 blade revoke UID

- create: 简写是 c，创建一个混沌演练实验，指执行故障注入。命令是 blade create [TARGET] [ACTION] [FLAGS]，比如实施一次 Dubbo consumer 调用 xxx.xxx.Service 接口延迟 3s，则执行的命令为 blade create dubbo delay --consumer --time 3000 --service xxx.xxx.Service，如果注入成功，则返回实验的 uid，用于状态查询和销毁此实验使用。

- destroy：简写是 d，销毁之前的混沌实验，比如销毁上面提到的 Dubbo 延迟实验，命令是 blade destroy UID

- status：简写 s，查询准备阶段或者实验的状态，命令是 blade status UID 或者 blade status --type create

- server：启动 web server，暴露 HTTP 服务，可以通过 HTTP 请求来调用 chaosblade。例如在目标机器xxxx上执行：blade server start -p 9526，执行 CPU 满载实验：curl "http:/xxxx:9526/chaosblade?cmd=create cpu fullload"



以上命令帮助均可使用 blade help [COMMAND] 或者 blade [COMMAND] -h 查看，也可查看新手指南，或者上述中文使用文档，快速上手使用。







下载最新的  https://github.com/chaosblade-io/chaosblade/releases



wget https://github.com/chaosblade-io/chaosblade/releases/download/v1.5.0/chaosblade-1.5.0-linux-amd64.tar.gz



tar zxf chaosblade-1.5.0-linux-amd64.tar.gz&&cd chaosblade-1.5.0





![](https://gitee.com/hxc8/images5/raw/master/img/202407172356957.jpg)





