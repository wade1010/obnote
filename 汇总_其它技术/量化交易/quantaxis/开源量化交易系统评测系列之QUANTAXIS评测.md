[https://zhuanlan.zhihu.com/p/347508557](https://zhuanlan.zhihu.com/p/347508557)

QUANTAXIS综合看来与其说是一个开源量化框架倒不如说是一套完整的从量化，数据存储，量化分析到实盘全面化的解决方案。

官方描述是这样的：支持 python/rust 的数据下载 自动运维(a 股/期货/期权/港美股/数字货币), 支持可配置的自定义账户和组合协议(QIFI), 支持股票/期货市场全推的数据协议(MIFI), 支持策略打点和动态画图的界面可视化协议(VIFI), 支持 a 股/ 期货/ 港美股的实盘交易及本地无限制账户的模拟盘. 支持 docker 一键部署和局域网内的 k8s 集群部署, 基于 celery/rabbitmq 实现分布式的回测/模拟/实盘的任务队列. 支持行情二次重采样, 账户订单二次转发, 订单流风控. 支持完全自定义的行情分发(模拟/真实/OU 随机过程)以及行情回放(用于复盘/模拟环境创建). 支持基于 QIFI 协议的各种客户端的自行接入(手机 APP/网页 web/终端)

具体QUANTAXIS能做什么：

- 投研分析

	- 全市场数据(日线/分钟线/tick)

	- 财务数据

	- 股票/期货/期权/港股/美股/(以及自定义数据源)

	- 为多品种优化的数据结构QADataStruct

	- 为大批量指标计算优化的QAIndicator [支持和通达信/同花顺指标无缝切换]

	- 基于docker提供远程的投研环境

	- 自动数据运维

- 回测

	- 纯本地部署/开源

	- 全市场(股票/期货/自定义市场[需要按规则配置])

	- 多账户(不限制账户个数, 不限制组合个数)

	- 多品种(QADataStruct原生为多品种场景进行了优化)

	- 跨周期(基于动态的resample机制)

	- 多周期(日线/周线/月线/1min/5min/15min/30min/60min/tick)

	- 可视化(提供可视化界面)

	- 自定义的风控分析/绩效分析

- 模拟

	- 支持股票/期货的实时模拟(不限制账户)

	- 支持定向推送/全市场推送

	- 支持多周期实时推送/ 跨周期推送

	- 模拟和回测一套代码

	- 模拟和实盘一套代码

	- 可视化界面

	- 提供微信通知模版

- 实盘

	- 支持股票(需要自行对接)

	- 支持期货(支持CTP接口)

	- 和模拟一套代码

	- 不限制账户数量

	- 基于策略实现条件单/风控单等

	- 可视化界面

	- 提供微信通知模版

- 终端

	- 提供mac/windows的可安装版本(QACommunity)

	- 提供全平台可用的web界面

## 一、技术解析

**a、运行环境：**Windows7以上版本、Ubuntu、Linux

**b、开发语言：**python3.7/C++/RUST

**c、系统层次：**

量化研究QRI

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348066.jpg)

**这是一张官方的介绍图，我们抽丝剥茧一步一步地按照步骤来解决相关技术问题。**

上面的图看起来信息比较多，我们简化一下：

**量化研究QRI**

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348939.jpg)

因为整体结构采用的是分布式微服务结构。所以协议层的支持http、websocket、gateway等就很容易理解。上层UI需要调用底层数据做交互，就需要有相关协议的支撑。

首先明白整个产品的命名规则，QAxxx，所有模块的开头都是由QA开头。

**上层服务模块：主要提供上层数据交互，以及页面操作功能**

	- QADeskPro：桌面应用程序

	- QAWechat：微信应用模块

	- QAAPP：独立APP应用模块

	- QARiskPro：风控账单模块

**策略研究终端：主要提供数据回测和策略研究，从想到到具体策略实现一整套解决方案**

	- QACron：数据服务，提供数据研究所需要的tick、kline、财务等数据

	- QARun、QAAutogeneration：基于rabbitmq/celery的分布式任务部署

	- QARandomPrice：基于OU过程的随机行情模拟

基于QUANTAXIS投研终端其实算是一整套投资研究解决方案。在投资研究中，本地化部署了数据平台，从产生想法，到确立数据模型，再到模型回测确定建模，再到模型迭代管理，自动化部署策略等。这是量化研究开发过程中一整套完整的科学研发流程。

**策略池：**

从投研平台过来之后，策略通过回测，自动部署到策略池，策略池会按照策略的类型分门别类，储存在策略池中。策略池的上层管理模块是一整套K8s策略运维管理平台，就是QAPubSub和QAEventMQ组成的集群管理。

	- QAPubSub：基于RABBITMQ的消息分发订阅

	- QAEventMQ：事件驱动中心，基于数据和策略的所有数据事件驱动调度中心。

**风控模块：**

在QUANTAXIS中，风控是一个独立运行的分布式系统，通过中间层Event和MQ事件来驱动风控管理。针对风控模块可以定义的比较多，从单账户风控，到多账户组合风控，再到自定义个性化风控都需要你有一定的编程基础和对这套系统的理解程度。

QUANTAXIS的风控模块比较详细，从资金利用率、反应资金的利用程度、股票周转率、反应股票的持仓天数、预期PNL/统计学PNL等，可以说交易过程中大部分能够考虑到的风控参数这里面都可以实现管理，具体如何使用，我们需要详细研究才能完整的掌握。

**扩展模块：**

这里的扩展模块主要还是通过策略的实盘和模拟层衍生出来数据层的应用，包括信号跟踪，数据复盘，绩效评估，收益因子分析等功能。具体可以根据自己的需求来定制。

能够实现二次开发的主要原因在于，整体框架采用了分布式构建，中间大量使用了EventMq和RabbitMQ来实现数据和消息的事件通讯。只要订阅了相应的数据接口就可以实现外部拓展，框架在实现数据持有化方面也采用了相关技术。对于代码能力较强的投资者，我想这算是一个很方便也很友好的功能。

框架也提供一些我们可以使用的扩展功能：

	- QARank：信号跟踪

	- QAPerformance：绩效评估

	- QATradingAnalysis：复盘工具

	- QAAlpha+：收益因子分析

通过以上的分析我想针对QUANTAXIS系统的整体结构有了大概了解。整体看起来比较多，拆分成逐个模块分析之后按照功能分析其实很容易明白每个功能模块具体作用和实现方式。对于个人量化投资者来说如果想掌握QUANTAXIS量化框架其实需要花点时间去研究研究才行，毕竟拆分和组合过程中采用了很多工厂模式在里面，需要从头开始理解设计的逻辑，然后梳理整体设计思路之后才能说熟练的使用整套框架，才有可能实现后期的二次开发。

## 二、结构拆分分析：

1. **底层数据支持：**

数据存储在QASU中包含以下基类数据存储方式：

├── __init__.py

├── main.py

├── save_account.py

├── save_backtest.py

├── save_binance.py

├── save_bitfinex.py

├── save_bitmex.py

├── save_financialfiles.py

├── save_gm.py

├── save_huobi.py

├── save_jq.py

├── save_okex.py

├── save_orderhandler.py

├── save_position.py

├── save_strategy.py

├── save_tdx_file.py

├── save_tdx_parallelism.py

├── save_tdx.py

├── save_tusharepro_pg.py

├── save_tushare.py

├── test_save_strategy.py

├── trans_gm.py

├── trans_ss.py

└── user.py

数据采集之后QUANTAXIS会按照自身定义好的数据结构进行存储，适配在对应的MongoDB数据库中。方便后期研究使用。

1. **中间层多数据以及数据格式的支持**

目前支持的数据库：

	- mongodb [作为OLTP的核心]

	- redis [作为实时数据缓存的核心/ 分布式任务收集]

	- aresdb [作为GPU查询的核心]

	- clickhouse [列数据库, 作为OLAP实时查询/策略等上层应用的核心]

1. **多种数据格式的支持**

- numpy.array

- pd.DataFrame/ pd.Series

- arrow

- **上层应用**

- **数据分析的表达能力**

	- 支持链式计算/ 支持矩阵式广播

- **基于gpu的数据运算能力**

	- 支持gpu列式运算/ cudf等

- **机器学习的无缝支持能力**

	- 支持tensorflow/ pytorch 常用机器学习平台的转化 和结果收集

- **多市场多标的扩展能力**

	- 支持全市场(包括且不限于)

主板

创业板

科创板

期货

债券/ 可转债/企业债

基金

港股 股票/指数

美股

数字货币

1. **数据可视化**

提供可定制可扩展的

	- 账户数据实时可视化

	- 风险指标实时可视化

	- 策略事件实时可视化

	- 订单流实时可视化

	- 流计算中的自定义事件的可视化标准

1. **复杂事件处理能力**

提供多任务，多调度的方式实现复杂事件的处理。

实时风控，订单任务，循环任务等。

## 三、使用难度

QUANTAXIS的使用难度相比于之前说的vnpy和Zipline来说都要难上一个等级，毕竟系统复杂度决定了使用的难度。

针对目前QUANTAXIS虽然由了docker部署，但是docker的学习无形又一程度上增加了使用的难度。想要熟练的把QUANTAXIS使用起来，需要尽可能了解每个模块功能之间的关系和基础的设计思路，不然在调试过程中很有可能不会出现不知道问题到底出在哪里。

但是对于机构或者是小团队来说，是很很值得花时间和精力来研究研究QUANTAXIS的整体设计思路，我想这套框架设计之初就是为了团队协作研发的，而不是为了个人投资者。完整的数据研究研发平台到策略自动化部署，涵盖了工作中所有可能接触的模块，支持便捷的二次开发功能，这对于有开发能力的团队来说算是一整套完整的解决方案。

## 四、策略编写难度

个人觉得想在QUANTAXIS把策略写好，需要学习的东西确实很多。难度会比我们之前说的那两个开源框架都复杂了好几个量级。

这里我整理了一个完整的小Demo测类实现方式，以及Account基类的讲解，从指标计算到策略分析，回测以及数据存储一整套完整的流程。希望有兴趣的伙伴自行下载学习。

[https://545c.com/f/30567921-480393348-96f10c](https://link.zhihu.com/?target=https%3A//545c.com/f/30567921-480393348-96f10c)（访问密码：1222）

如果后期有人对QUANTAXIS感兴趣的比较多，我们会专门录制相关视频为大家解析整个框架的设计思路以及策略实现的具体方式，这里就不赘述了，不然这篇文章我估计要写到明天这个时候了。

## 五、风控难度

QA_RISK插件是QUANTAXIS对于风险,绩效的一个评估插件

QA_RISK的加载方式是直接加载

# coding:utf-8 import QUANTAXIS as QA risk = QA.QA_Risk(account, benchmark_code='000300', benchmark_type='index_cn', if_fq=True) """ account: QA_Account类/QA_PortfolioView类 benchmark_code: [str]对照参数代码 benchmark_type: [QA.PARAM]对照参数的市场 if_fq: [Bool]原account是否使用复权数据 """

加载了account以后,我们可以对account进行分析,以下字段都是惰性计算的

- 账户信息(risk.account.account_cookie)

- 组合信息(risk.account.portfolio_cookie)

- 用户信息(risk.account.user_cookie)

- 年化百分比收益(risk.annualize_return)

- 账户百分比利润(risk.profit)

- 最大回撤(risk.max_dropback)

- 账户交易时长(risk.time_gap)

- 账户资金曲线波动率(risk.volatitlity)

- 对照标的代码(risk.benchmark_code)

- 对照标的百分比年化收益(risk.benchmark_annualize_return)

- 对照标的百分比总收益(self.benchmark_profit)

- beta值(risk.beta)

- alpha值(risk.alpha)

- 夏普值sharpe(risk.sharpe)

- 初始现金(risk.init_cash)

- 最终总资产(risk.last_assets)

- 账户的总手续费(risk.total_commission)

- 账户的总印花税(risk.total_tax)

- 账户资金曲线(risk.assets)

- 对照标的资金曲线(risk.benchmark_assets)

- 每日持仓市值表(risk.market_value)

画图方法:

- 画出资金曲线 plot_assets_curve

- 用热力图画出每日持仓 plot_dailyhold

- 用热力图画出信号列表 plot_signal

由于QUANTAXIS定义了详细的风控指标，针对风控模块可以很方便也很容易通过Account类来实现对应账户的风控，组合风控的实现方式其实就是多账户的组合Account综合调配。

## 六、代码完整度

从GitHub首页我们可以看出，这个项目其实是部分开源，开源不是已经足够满足我们日常的交易分析等工作。

### 已开源

> 数据存储/数据分析/回测


- QUANTAXIS QUANTAXIS的核心部分

> WEB相关, http/websocket/开放数据接口


- QUANTAXIS_WEBSERVER 基于tornado的web api/ websocket

> 分布式相关, 任务异步执行, 跨进程分布式消息订阅分发


- QUANTAXIS_RUN 基于rabbitmq/celery的分布式任务部署

- QUANTAXIS_PUBSUB 基于RABBITMQ的消息分发订阅

> 接口相关: 交易账户/ 期货接口封装/ Trader实例


- QATradeG 期货的直连版本接口的docker

- QUANTAXIS OTGBROKER 基于OPEN_TRADE_GATEWAY的接口封装

- QUANTAXIS CTPBEEBROKER 基于CTPBee的接口封装

- QUANTAXIS_ATBROKER 基于海风at的接口封装

- QUANTAXIS TRADER 一个开源的websocket版本的期货交易实例

> 策略相关


- QASTRATEGY101 101个基础策略[逐步更新中...]

> 行情相关: 主推行情实现/ 基于OU过程的模拟行情


- QUNATAXIS MARKETCOLLECTOR 全市场订阅分发的行情推送

- QUANTAXIS_RandomPrice 基于OU过程的随机行情模拟

> 账户协议


- QIFI 一个基于快期DIFF协议的QA实时账户协议

- QIFIAccount 一个基于QIFI协议的多市场兼容的 实时账户实现

- QAStrategy 一个完整的 支持 模拟/回测/实盘一键切换的策略基类

### 未开源

未开源部分为 目前私募自用部分, 因此暂时不开源 一些相关的项目会经过选取和完善后逐步开源

> 实时交易解决方案/ 无人值守/状态汇报/实时账户评估/多账户/策略账户拆分/事件流风控/PB系统/CEP引擎/多系统终端


- QUANTAXIS_REALTIME_RESOLUTION 实时交易/部署解决方案(未开源)

- QUANTAXIS UNICORN QUANTAXIS 策略托管, 交易监控解决方案(未开源)

- QUANTAXIS_RANK QUANTAXIS实时账户评估

- QUANTAXIS_CEPEngine QUANTAXIS 复杂事件处理引擎

- QUANTAXIS_PBSystem QUANTAXIS PB系统

- QUANTAXIS_QARISKPRO QUANTAXIS 多市场多账户集成的实时风控系统

- QUANTAXIS QADESKPRO 新版本客户端网页(部分开源)

- QUANTAXIS PMS 一个轻量级的纯python实现的 兼容QIFI协议的账户/仓位管理系统

> tick回测


- QUANTAXIS TICKBacktest tick回测 支持真实tick/仿真tick

> jupyterhub 定制(多人编辑)


- QUANTAXIS JUPYTERHUB

> docker cluster


- QUANTAXIS PROCluster 一键部署的docker集群, 2地3中心的高可用灾备投研/交易环境

> Runtime 一个标准化的策略运行时


- QUANTAXIS RUNTIME-RS 一个rust-base的策略标准化运行时 单机可以拉起10k+ 策略

- QAStrategy-rs rust-base的策略标准化封装工具

开源都是核心模块，没开源的都是好用的工具。由于本身使用难度的问题，我觉得这也是为什么针对QUANTAXIS的个人研究者非常少的原因之一，使用难度阻碍了大部分投资者。

## 总结：

捡回开始说的话，QUANTAXIS并不适合个人投资者，除非你愿意花费大量时间来研究，或者说你有很强的代码能力，熟悉分布式结构和微服务设计原理，不然QUANTAXIS对于任何一个刚入门的量化研究人员来说都是不友好的。

但是好处是，如果你一旦成功熟练使用了QUANTAXIS，那么对于你的职业发展是一个很友善的工作，从量化数据管理，到模型建立，回测，策略管理，运维，再到上层风险管理，可视化等，你不仅仅从量化系统层面了解到了量化系统的工作原理，同样你也从策略师，分析师的角度完整的走了一遍量化工作每个岗位都要经历的流程。对于职业发展无疑是很有帮助。

我在上一篇文章[《量化研究QRI-开源量化交易系统评测系列之Zipline评测》](https://link.zhihu.com/?target=https%3A//mp.weixin.qq.com/s/_ZBezwMX2i-nuAPjKxMe4A)结尾也说了，对于量化研究来说，我们要做的不仅仅只有策略，对于个人或者机构投资者来说，数据和策略的管理也是一项非常繁重的任务。**想法——数据——建模——回测——自动化部署——策略运维——上层管理——风控，**这是一整套标准化量化研究流程。在QUANTAXIS中我们可以看出，从系统的设计上面就做到了层层把关，层层把控，这是我们值得学习和借鉴的地方。