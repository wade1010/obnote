**深度好文：Clickhouse 实践之路**

### 背景

在数据量日益增长的当下，传统数据库的查询性能已满足不了我们的业务需求。而Clickhouse在OLAP领域的快速崛起引起了我们的注意,于是我们引入Clickhouse并不断优化系统性能，提供高可用集群环境。本文主要讲述如何通过Clickhouse结合大数据生态来定制一套完善的数据分析方案、如何打造完备的运维管理平台以降低维护成本，并结合具体案例说明Clickhouse的实践过程。

### Clickhouse简介

### 为什么选择Clickhouse

1. 目前企业用户行为日志每天百亿量级，虽然经过数仓的分层以及数据汇总层通用维度指标的预计算，但有些个性化的分析场景还是需要直接编写程序或sql查询，这种情况下hive sql和spark sql的查询性能已无法满足用户需求，我们迫切的需要一个OLAP引擎来支持快速的即席查询。

1. BI存储库主要采用的是Infobright，在千万量级能很快的响应BI的查询请求，但随着时间推移和业务的发展，Infobright的并发量与查询瓶颈日益凸显，我们尝试将大数据量级的表导入TiDB、Hbase、ES等存储库，虽然对查询有一定的提速，但是也存在着相应的问题（后续章节会详细介绍），这时我们考虑到Clickhouse。

1. Clickhouse社区活跃度高、版本迭代非常快,几乎几天到十几天更新一个小版本，我们非常看好它以后的发展。

### Clickhouse特性

Clickhouse是俄罗斯yandex公司于2016年开源的一个列式数据库管理系统，在OLAP领域像一匹黑马一样，以其超高的性能受到业界的青睐。特性：

- 基于shard+replica实现的线性扩展和高可靠

- 采用列式存储，数据类型一致，压缩性能更高

- 硬件利用率高，连续IO，提高了磁盘驱动器的效率

- 向量化引擎与SIMD提高了CPU利用率，多核多节点并行化大查询

不足：

- 不支持事务、异步删除与更新

- 不适用高并发场景

### Clickhouse建设

### 整体架构

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806749.jpg)

clickhouse整体架构

我们依据数据的流向将Clickhouse的应用架构划分为4个层级。

### 数据接入层

提供了数据导入相关的服务及功能，按照数据的量级和特性我们抽象出三种Clickhouse导入数据的方式。

- 方式一：数仓应用层小表导入

这类数据量级相对较小，且分布在不同的数据源如hdfs、es、hbase等，这时我们提供基于DataX自研的TaskPlus数据流转+调度平台导入数据，单分区数据无并发写入，多分区数据小并发写入，且能和线上任务形成依赖关系，确保导入程序的可靠性。

- 方式二：离线多维明细宽表导入

这类数据一般是汇总层的明细数据或者是用户基于Hadoop生产的大量级数据，我们基于Spark开发了一个导入工具包，用户可以根据配置直接拉取hdfs或者hive上的数据到clickhouse，同时还能基于配置sql对数据进行ETL处理，工具包会根据配置集群的节点数以及Clickhouse集群负载情况(merges、processes)对local表进行高并发的写入，达到快速导数的目的。

- 方式三：实时多维明细宽表导入

实时数据接入场景比较固定，我们封装了通用的ClickhouseSink，将app、pc、m三端每日百亿级的数据通过Flink接入clickhouse，ClickhouseSink也提供了batchSize(单次导入数据量)及batchTime(单次导入时间间隔)供用户选择。

### 数据存储层

数据存储层这里我们采用双副本机制来保证数据的高可靠，同时用nginx代理clickhouse集群，通过域名的方式进行读写操作，实现了数据均衡及高可靠写入，且对于域名的响应时间及流量有对应的实时监控，一旦响应速度出现波动或异常我们能在第一时间收到报警通知。

- nginx_one_replication：代理集群一半节点即一个完整副本，常用于写操作，在每次提交数据时由nginx均衡路由到对应的shard表，当某一个节点出现异常导致写入失败时，nginx会暂时剔除异常节点并报警，然后另选一台节点重新写入。

- nginx_two_replication：代理集群所有节点，一般用作查询和无副本表数据写入，同时也会有对于异常节点的剔除和报警机制。

### 数据服务层

- 对外：将集群查询统一封装为scf服务(RPC)，供外部调用。

- 对内：提供了客户端工具直接供分析师及开发人员使用。

### 数据应用层

- 埋点系统：对接实时clickhouse集群，提供秒级别的OLAP查询功能。

- 用户分析平台：通过标签筛选的方式，从用户访问总集合中根据特定的用户行为捕获所需用户集。

- BI：提供数据应用层的可视化展示，对接单分片多副本Clickhouse集群，可横向扩展。

### Clickhouse运维管理平台

在Clickhouse的使用过程中我们对常见的运维操作如：增删节点、用户管理、版本升降级等封装了一系列的指令脚本,再结合业务同学使用过程中的一些诉求开发了Clickhouse管理平台,该平台集管理、运维、监控为一体，旨在让用户更方便、快捷的使用Clickhouse服务，降低运维成本，提高工作效率。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806844.jpg)

clickhouse运维管理平台首页

### 配置文件结构

在自动化运维操作时会经常修改配置文件，而clickhouse大部分参数都是支持热修改的，为了降低修改配置的带来的风险和便于维护管理，我们将默认的配置文件做了如下拆解。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806933.jpg)

配置文件拆解

- users.xml

默认的users.xml可分为三个部分用户设置users：主要配置用户信息如账号、密码、访问ip等及对应的权限映射配额设置quotas：用于追踪和限制用户一段时间内的资源使用参数权限profiles：读写权限、内存、线程等大多数参数配置为了统一管理权限我们在users.xml预定义了对应权限及资源的quotas及profiles，例如default_profile、readwrite_profile、readonly_profile等,新增用户无需单独配置quotas及profiles,直接关联预定义好的配置即可

- users.d/xxx.xml

按不同的用户属性设置user配置，每一个xml对应一组用户,每个用户关联users.xml中的不同权限quotas及profiles

- users_copy/xxx.xml

每次有变更用户操作时备份指定属性的xml，方便回滚

- metrika.xml

默认情况下包含集群的配置、zookeeper的配置、macros的配置,当有集群节点变动时通常需要将修改后的配置文件同步整个集群,而macros是每个服务器独有的配置,如果不拆解很容易造成配置覆盖,引起macros混乱丢失数据,所以我们在metrika.xml中只保留每台服务器通用的配置信息,而将独立的配置拆解出去

- conf.d/xxx.xml

保存每台服务器独立的配置,如macros.xml

- config_copy/xxx.xml

存放每次修改主配置时的备份文件，方便回滚

### 元数据管理

维护各个Clickhosue集群的元数据信息，包含表的元数据信息及Clickhouse服务状态信息，给用户更直观的元数据管理体验，主要有如下功能

1. 查询指定集群和库表信息，同时展示该表的状态：只读 or 读写。

1. 查看表的元数据信息 行数、磁盘占用、原始大小、更新时间、分区信息等。

1. 设定数据生命周期，基于分区数对数据进行清理操作。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806102.jpg)

生命周期

### 自动化运维

### 用户管理

由于我们基于nginx代理的方式对Clickhouse进行均衡读写，同时Clickhouse的配置也是可以热修改的，所以在用户管理及资源控制方面我们直接通过web平台对Clickhosue配置文件进行修改操作。通过web平台展示users.xml中对应权限的profiles 和 quotas，运维人员只需根据用户属性选择对应的配置填写对应的用户名及自动生成的密文密码即可，不会影响已配置好的权限及资源，同时每次xml操作都会提前备份文件，在xml修改异常时可随时回滚。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806212.jpg)

用户管理

### 集群操作

clickhosue管理平台的核心模块，依托于运维作业平台 API封装了一系列的运维脚本，覆盖了集群管理的常用操作。

1. clickhouse服务的启动、停止、重启

1. clickhouse的安装、卸载、故障节点替换

1. 升级/降级指定Clickhouse版本

1. 动态上下线指定节点

1. 元数据维护 (cluster_name、metrik、macros)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806306.jpg)

集群管理

这里以新增节点为例展示整体的流程操作

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806465.jpg)

新增节点流程图

其中较为核心的操作在于install作业的分发及对应的配置生成**分发install作业**： 由Clickhouse平台调用运维作业平台服务将预定义的脚本分发到指定节点执行，同时传入用户选填的配置参数。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806734.jpg)

作业分片install脚本

**生成配置文件**：通常情况下我们会在一个物理集群分别建立单副本集群和双副本集群，在为新节点生成配置文件时由clickhouse平台从元数据模块获取到新增节点的集群信息，动态生成新增节点的macros与metrika配置，然后将metrika.xml同步到所有集群。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806152.jpg)

生成配置文件

### 监控与报警

1. 硬件指标监控

硬件指标监控主要指clickhouse服务节点的负载、内存、磁盘IO、网卡流量等，这里我们依托于monitor监控平台来配置各种指标，当监控指标达到一定阈值后触发报警。

1. 集群指标监控

我们在Clickhouse管理平台中集成了grafana，采用Prometheus采集clickhosue集群信息在grafana做展现，一般的监控指标有top排名(慢查询、内存占用、查询失败 )、QPS、读写压力、HTTP&TCP连接数、zookeeper状态等，当这些指标出现异常时通过alertmanager插件配置的规则触发报警。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806943.jpg)

1. 

grafana监控图

1. 流量指标监控

目前所有对于clickhouse的读写请求都是通过域名代理的方式，通过域名的各项指标能精准且实时的反映出用户最原始的读写请求，当域名响应时间波动较大或者响应失败时我们能在第一时间收到报警并查看原始请求。

### Clickhouse应用

### BI查询引擎

### 核心诉求

在未接入Clickhouse之前，BI的存储库有Infobright、Hbase、ES、druid等，其中主要使用的是Infobright，在千万级别以下Infobright性能出色，对于一些时间跨度较长、数据量级较大的表Infobright就有些无能为力，这种数据我们通常会存放在ES与Hbase中，这样虽然加快了查询速度但是也增大了系统适配不同数据源的复杂度，同时分析师会有直接操作表的诉求，数据存入ES与Hbase会增加对应的学习成本，基于此我们的核心诉求就是：

- 大数据量级下高查询性能

- BI适配成本低

- 支持sql简单易用

### 选型对比

基于以上诉求我们拿现有的Infobright与TiDB、Doris、Clickhouse做了如下对比。

总体来看Clickhouse的查询性能略高于Doris，而TiDB在千万量级以上性能下降明显，且对于大数据量级下Clickhouse相比Infobright性能提升巨大，所以最终我们选择了Clikhouse作为BI的存储查询引擎。

### 集群构建

在评估了目前Infobright中的数据量级和Clickhouse的并发限制之后，我们决定使用单分片 多副本的方式来构建Clickhouse集群，理由如下：

- BI对接数仓应用层数据，总体来说量级较小，同时clickhouse有着高效的数据压缩比，采用单节点能存储当前BI的全量数据，且能满足未来几年的数据存储需求。

- Clickhouse默认并发数为100，采用单分片每个节点都拥有全量数据，当qps过高时可横向增加节点来增大并发数。

- clickhouse对Distributed 表的join支持较差，单分片不走网络，能提高join查询速度。

服务器配置：CPU：16 × 2 cores、内存：192GB、磁盘：21TB,整体的架构图如下所示：

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806193.jpg)

BI_Clickhouse应用架构图

在写数据时由taskplus对其中的一台节点写入，如果该节点异常可切换到其他副本节点写入，由写入副本自动同步其他副本。查询同样用nginx代理三台节点，由于是单分片集群所以查询视图表和本地表效果是一样的，不过视图表会自动路由健康副本，所以这里还是选择查询视图表。在通过Taskplus将BI的数据源切换到Clickhouse后对于大量级查询性能提升明显

- tp99由1184ms变为739ms

- 大于1秒的查询总量日均减少4.5倍

- 大于1秒的查询总耗时日均降低6.5倍

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806503.jpg)

- 

Clickhouse接入前后对比

### 问题及优化

在接入clickhouse之前BI的平均响应时间为187.93ms，接入clickhouse之后BI的平均响应时间为84.58ms，整体响应速度提升了2.2倍，虽然查询速度有所提升但是我们在clickhouse监控日报邮件中仍发现了一些慢查询，究其原因是我们对于应用层的表默认都是以日期字段stat_date分区，而有一部分表数据量级非常小且分区较多如某产品留存表总数据量：5564行，按日期分区 851个分区，平均每天6.5条数据，以下是针对于该表执行的常规group by count查询统计。

由此可见Clickhouse对于多分区的select的查询性能很差，官方文档中也有对应的表述> A merge only works for data parts that have the same value for the partitioning expression. This means you shouldn’t make overly granular partitions (more than about a thousand partitions). Otherwise， the SELECT query performs poorly because of an unreasonably large number of files in the file system and open file descriptors

针对于这种场景我们想直接创建月或年维度的分区，但是对于增量数据会存在重跑历史等问题，而delete或ReplacingMergeTree都可能造成的数据查询不一致情况，基于此我们在mysql中做了一个中间表，每次增量导入或修改mysql表然后全量更新至clickhouse，不设置分区或不以日期为分区，保证查询的效率和一致性，经过多分区小量级表的优化之后我们的平均响应时间变为到70.66ms，相比未优化前查询性能提升了16%，最终BI的查询响应时间对比如下图所示

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806473.jpg)

BI响应时间对比

### 实时数仓

### 分层架构

由于每日用户行为数据量级已达百亿，传统的离线分析已不能满足业务方的需求，因此我们基于三端数据构建了实时数仓，整体分层架构如下

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806605.jpg)

实时数仓分层架构

clickhouse在其中扮演的角色是秒级别的实时OLAP查询引擎，当我们DWS层的通用维度实时指标不满足用户需求时，用户可以直接通过Clickhouse编写sql查询实时数据，大大降低了实时数据查询门槛。

### 数据输入与输出

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806989.jpg)

实时数仓_Clickhouse应用架构图

在数据输入层面我们将用户的行为数据实时关联维表写入kafka，然后由Flink + JDBC写入Clickhouse，为了保证实时查询的稳定性我们采用了双副本结构，用nginx代理其中一个完整的副本，直接对域名写入.同时在程序中增加失败重试机制，当有节点不可写入时，会尝试向其他分片写入，保证了每条数据都能被写入clickhouse。在数据的输出层面将同样由nginx代理整个集群，对接到客户端工具及与SCF服务，其中客户端工具对接到开发人员及分析师，scf对外提供查询服务。

### 数据产品

埋点系统是我们专为埋点管理开发的系统其主要功能有

1. 埋点报备及校验：新上线埋点的收录及校验

1. 需求管理：针对于新埋点上线及埋点变更的需求周期监控及状态追踪

1. 埋点多维分析：基于用户上报埋点进行多维汇总，方便用户下钻分析定位问题

1. 指标及看板：有单个或多个埋点按一定规则组合进行多维汇总，可直接在看板中配置对应的统计结果数据

1. 埋点测试：实时收集测试埋点数并进行格式化校验及解析

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806164.jpg)

1. 

埋点系统

在未接入Clickhouse前埋线系统采用MR预计算汇总用户配置的埋点指标，并将结果数据写入Hbase，预计算针对于用户侧来说查询的都是结果数据，响应速度非常快，但是同时也带来一些问题

- 时效性较差：新上报埋点数据或者修改后的埋点需要在T+1天才能展示，且修改埋点维度后需要重跑历史数据。

- 模型单一不便扩展：只针对埋点的事件模型做流量统计，想要支持其他分析模型必须另外开发对应的计算模型。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806267.jpg)

- 

埋点系统新建指标

基于此种情况我们直接将埋点系统中用户配置的规则转换为sql，查询Clickhouse中接入的实时多维明细数据，同时针对于埋点系统的使用场景优化了实时明细表的索引结构，依托clickhouse极致的查询性能保证实时埋点统计能在秒级别的响应，相当于即配即出，且能随意修改维度及指标，大大提升了用户体验.由于是基于sql直接统计明细数据，所以统计模型的扩展性较高，能更快的支持产品迭代。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190806266.jpg)

埋点系统看板

### 常见问题

1. 数据写入

- 一个batch内不要写多个分区的数据

- 根据服务器配置适当增大background_pool_size，提高merge线程的数量 默认值16。

- 对于system.merges、system.processes表做好监控，可随时感知写入压力情况作出预警，避免服务崩溃

- 索引不宜建立过多，对于大数据量高并发的写入可以考虑先做数据编排按建表索引排序在写入，减少merge压力

- 禁止对Distributed表写入，可通过代理方式如nginx或chproxy直接对local表写入，而且能基于配置实现均衡写入及动态上下线节点

1. JOIN操作

- 无论什么join小表必须放在右边，可以用left、right调整join方式

- 开启谓词下推：enable_optimize_predicate_expression=1(部分版本默认关闭)

- 大量降低数据量的操作如where、group by、distinct操作优先在join之前做(需根据降低比例评估)

1. 常用参数

- max_execution_time 单次查询的最大时间：600s

- max_memory_usage 单服务器单次查询使用的最大内存，设置总体内存的50%

- max_bytes_before_external_group_by 启动外部存储 max_memory_usage/2

- max_memory_usage_for_all_queries 单服务器所有查询使用的最大内存，设置总体内存的80%-90%，防止因clickhouse服务占用过大资源导致服务器假死

### 总结及展望

目前Clickhouse主要应用于数据产品、画像、BI等方向，日更新百亿数据，每日百万量级查询请求，持续对外提供高效的查询服务，我们未来将在以下两个方面加强Clickhouse的建设：1.完善Clickhouse管理平台保障Clickhouse服务的稳定性：

- 目前在删除节点时会启动一个Rebalance脚本将被删除节点上的数据重新写入其他节点,在此过程中会造成数据查询不一致的问题,我们希望能提供更高效无感的Rebalance操作方案

- 更精细化的权限控制及管理,目前最新版本中已有此实现(Role及Privileges),后续我们将尝试使用该功能并适配到Clickhouse管理平台

- 实时数据写入Clickhouse的一致性保证

2.优化Clickhouse性能,拓展Clickhouse使用场景：

- Clickhouse在千亿级数据场景下复杂查询优化

- 埋点系统基于Clickhouse统计模型拓展如访问路径、间隔、分布等