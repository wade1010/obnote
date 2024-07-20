QMT极简策略交易系统

> 迅投QMT是一个门槛相对较低、功能强大的量化策略交易系统。本文首先介绍一些背景知识，并主要分析极简策略交易系统的一些功能，后续将陆续分享行情、交易、策略等相关实战教程。


## QMT极速策略交易系统

**迅投QMT极速策略交易系统** 是一款专门针对券商、期货公司、信托等机构的高净值客户开发设计的集行情显示，投资研究，产品交易于一身，并自备完整风控系统的综合性平台。其自带投研量化平台可以灵活实现CTA，无风险套利等多种量化策略，并能够对策略进行回测检验和自动化交易。目前大部分券商都有支持策略交易，目前已知的像国金、国盛、国信、海通、华鑫等券商均有对普通用户开放，在开通资金门槛、功能阉割和佣金费率方面可能有一些差异，目前部分券商股票佣金可低至万1，可极大降低量化交易摩擦成本。

QMT极速策略可手工交易的品种有：普通股票/ETF、港股通、融资融券、ETF期权等，其中ETF期权的程序化交易权限较难申请，其他均可在内置的模型交易中实现tick、kline级别的本地自动化程序交易。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349831.jpg)

## QMT极简策略交易系统

xtquant是QMT官方内置的XtMiniQmt极简客户端对应的Python接口，目前支持的版本为3.6~3.8，可支持历史行情下载、实时数据订阅、外部数据访问、普通账户和两融账户交易(需开通相关权限)，对量化交易支持的比较完善，跟极速策略交易系统相比最主要的优势是**简洁**、**灵活**，不局限在bar、kline的事件触发，可以容易地集成多种数据源进行综合分析、判断，相关的官方文档细节可点击文末**阅读原文**下载查看。

QMT内置的Python版本为3.6，第一次使用的话需手动下载相关的库，或直接拷贝已经下载好的xtquant库(见**推荐阅读**中的GitHub链接)。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349947.jpg)

XtMiniQmt.exe存在于QMT安装目录下的bin.x64子目录中, xtquant库默认安装在bin.x64\Lib\site-packages中。

内置的Python版本较老，对于一些较新的库支持有限，因此，如果我们想在自定义的Python中调用，如Python3.8，只需将xtquant拷贝到我们自己python安装目录的Lib\site-packages中便可，这里我的安装路径是 C:\ProgramData\Anaconda3\Lib\site-packages\xtquant。

xtquant主要包含两大块：

•**xtdata**：xtdata提供和MiniQmt的交互接口，本质是和MiniQmt建立连接，由MiniQmt处理行情数据请求，再把结果回传返回到python层。需要注意的是这个模块的使用目前并不需要登录，因此只要安装了QMT,就可以无门槛的使用其提供的数据服务。•**xttrader**：xttrader是基于迅投MiniQMT衍生出来的一套完善的Python策略运行框架，对外以Python库的形式提供策略交易所需要的交易相关的API接口。该接口需开通A股实盘版权限方可登录使用。

在运行使用XtQuant的程序前需要先启动MiniQMT客户端。通常有两种方式，一种是直接启动极简QMT客户端XtMiniQmt.exe：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349957.jpg)

如果登录时提示没有相关权限，可尝试启动QMT量化交易终端XtItClient.exe,在登录界面选择极简模式：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349670.jpg)

部分券商不支持策略的云服务器运行，但接收行情数据不受影响。设置好客户端后，便可在ipython、jupyter等环境中调试数据和策略了。下期将带来行情相关的详细介绍以及服务化封装的细节。

极简模式启动后，会启动一个服务，监听端口为58610

(base) PS C:\Users\Administrator> netstat -ano | findstr "LISTENING"|findstr 58610

TCP    0.0.0.0:58610          0.0.0.0:0              LISTENING       14632