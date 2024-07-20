[# ISSUE 1335 QA回测-模拟配置流程说明文档（Window）.pdf](attachments/WEBRESOURCE3869e4a78b8c58b1b41c971484f1cc8f# ISSUE 1335 QA回测-模拟配置流程说明文档（Window）.pdf)

[https://github.com/QUANTAXIS/QUANTAXIS/issues/1335](https://github.com/QUANTAXIS/QUANTAXIS/issues/1335)

**简介**：下图是QA项目目前的整体构架图，红色部分包含了目前已经开发完成的工具库（大多数已开

源），从流程图中可以看到，QA基本已经能够实现量化交易全通路。需要注意的是， 目前我们已经基

本打通了行情（数据的获取—>存储—>接口外放—>可视化—>后期的数据运维）、回测（基于QA的多

线程回测系统）、模拟（基于QA的模拟交易撮合系统以及simnow 的仿真交易系统）、实盘（暂未接

入）、策略池管理（暂未涉及）、多账户管理及风控（暂未涉及）、绩效评估及收益归因（暂未涉

及）、MOM及FOF管理（暂未涉及）、复盘工具 （暂未涉及）、其他各端口的产品外放（暂未涉及）

等。本篇主要对前三个部分（行情、回测、模拟）的本地化配置方案和流程进行简要总结，以便后续学

习复用。实盘部分的配置会在 以后加入。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346768.jpg)

**一、非docker版的配置**

电脑配置：Win10/64位/内存最好大于16G, 8G的会比较卡

**QA基础服务—配置流程**

1. 下载最新版的**anaconda(64位,python3.x以上)**注意**必须是64位**, 后续模拟盘用到的ctpbee只支持

64位的python，window安装的过程很简单，在最后的choose install location时， 可以自定义安

装目录，最后一步询问你是否**add path时，请记得把这个复选框勾上**，免得后续还需要自己去添

加环境变量，其他的无需累述。anaconda官方下载地址。 

2. 下载安装最新版的**mongoDB(64位)**，安装也较为简单（特别注意，最新版的mongodb安装时会

提示是否同时安装mongo compass，这个千万不要勾选，国内网速在线下载超级慢， 勾选了会导

致安装失败），默认是安装在C盘，也可自定义安装目录，我的建议是自定义安装在一个磁盘空间

较大的目录里，因为后续QA会拉数据进来，期货/股票的日线、分钟以及 交易明细数据总共加起

来有几百G，而且后续数据还会增量更新，所以尽量挑一个大的磁盘放mongoDB。安装完成之

后，打开安装目录就可以看到如下图1的目录文件了。然后，在 mongodb目录下创建两个空文件

夹（如图2）,并在mongodb\log下面创建一个空的mongo.log。在win10中以管理员身份运行cmd，cd到mongodb\bin目录，运行以下命令（电脑关机 后，下次开机mongodb服务会自己启

动）。然后按下图3所示在cmd中输入对应命令：启动MongoDB服务，验证mongo是否已经成功

启动。最后，将mongodb的bin完整路径加入 的系统环境变量中，这样在任何目录启动cmd都可

以使用mongo命令啦。到此，mongod的配置基本完毕。mongo官方下载地址。最后去官网把配

套的mongo可视化工具**mongo campass**也下载完成一并安装即可（当然你也可以使用

**Robomongo**）。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346122.jpg)

3. 下载安装QUANTAXIS，一句话命令搞定：**pip install quantaxis**，具体安装中的各种骚操作可参

考QA安装配置文档

**小结**：弄到这里，你就可以使用QA的基础服务了，包括股票/期货/债券等各周期的数据mongo存储，

开启jupyter notebook/jupyter lab，你就可以在notebook/lab里面进行**数据获取、 投研分析、策略回**

**测**等工作。

**QA深层次服务—配置流程**

在上一个小结中，我们提到，只要完成了anaconda、mongo以及quantaxis的安装和配置，基本就能

玩QA了。不过，这只是第一步。接下来，将继续介绍如何配置QAweb服务，QA后 台服务，QA交易服

务，QA行情服务，如何回测，如何模拟等问题。注意：quantaxis整个项目的地址即入口在此。 

1. **QA后台服务**：安装quantaxis_webserver，命令如下，然后启动cmd，在命令行输入：

quantaxis_webserver，后台服务即开启成功，后台API说明文档backend_api. 

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346032.jpg)

2. **QAWeb服务**：下载QADESK_BASIC到本地，项目地址点这里，保证后台服务开启的条件下，打开

**index.html**，然后你就能那看到如下一个页面，点击登录，就能看到QA提供的几个服务配置界面

啦。其中，**行情服务器和回测服务**由QA后台服务提供（启动quantaxis_webserver即可），端口

默认为**8010**。**研究服务器**由anaconda内置的jupyter提供（cmd中启动 jupyter lab即可，默认端

口**8888**。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346497.jpg)

**小结**：弄到这里，你基本实现了基于QA的一套本地化的行情可视化查阅（主要包括股票期货），

jupyter内些数据获取、投研分析、策略回测等基本功能。回测的基本示例代码可以在 quantaxis子目录

里的Example找到，以simple_backtest.py为例，复制该代码在jupyter中运行一下，你就能看到策略的

回测结果。下一步，将继续介绍如何进行策略模拟。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346742.jpg)

3. **QA模拟交易服务**：先把模拟交易的一些列库安装好，包括以下几个，这些库的开源代码都能在这

里找到。 

QAPUBSUB：一句话命令安装，**pip install quantaxis_pubsub**.

QUANTAXIS_RandomPrice：一句话命令安装，**install quantaxis-randomprice**.

QUANTAXIS_RealtimeCollector：一句话命令安装，**pip install qarealtime_collector**。也可以

直接把项目clone下来，然后本地安装，切入到QUANTAXIS_RealtimeCollector目录，运 行**pip**

**install e . **即可

QACTPBeeBroker：一句话命令安装，**pip install QACTPBeeBroker**

具体用法：**QACTPBEE --userid xxxx --password xxxx --brokerid xxxx --mdaddr xxxxx**

**--tdaddr xxxx --appid xxx --authcode xxxx**

QA默认给了一个simnow的连接参数，方便快速使用：QACTPBEE --userid 你的simnow账户

--password 你的simnow密码

安装QIFIAccount：一句话命令安装，**pip install qififiaccount**

QASTRATEGY：一句话命令安装，**pip install qastrategy**. 

QATrader：一句话命令安装，**pip install QATRADER**，然后配置好配套服务，这个如何配置请参

考这里。命令行输入**qatraderserver**，即可开启http端口(8020).

配套服务配置流程1—安装elang和rabbitmq，安装流程参考这里，其中软件的版本下载官网

最新的即可。

配套服务配置流程2—配置rabbitmq: 启用插件、创建用户、分配角色、设置权限，参考链

接。rabbitmq配置好了之后运行如下命令

至此，模拟回测的相关库安装配置完毕，可以看到，模拟交易依赖的库/模块很多，这是由QA的模块分

离设计理念决定了，给了用户最大的自由度，可以随意组合配置，用熟了之后， 你会发现好处多多。如

何模拟交易呢？ 下面以au1912黄金期货5分钟的策略回测和模拟为例，你需要在通过cmd命令依次确保

以下服务开启（默认端口在开启对应服务时有提示）

rabbitmq-plugins enable rabbitmq_management 

rabbitmqctl add_user admin admin 

rabbitmqctl set_user_tags admin administrator 

rabbitmqctl set_permissions -p "/" admin '.*' '.*' '.*'

然后，在研究坏境任意开启一个jupyter notebook，将回测策略的代码放进去运行，启动运行模拟盘，

就能在web页面的模拟标签页面看到你所模拟交易策略和品种信息啦。

**小结**：弄到这里，你基本可以实现从数据的获取、行情的展示、数据的分析、投资研究、策略回测以及

模拟交易这些功能啦。如果你有一个想法，基本的落地实现方案就解决啦。策略 如果表现良好，你可以

根据策略出现的信号进行手动交易。

quantaixs_webserver: 开启web行情页面和研究页面的后台服务 

jupyter lab：开启jupyter lab研究环境 

qatraderserver：开启交易后台 

QARC_Resample --code au1912 --freq 5min：启动模拟交易品种的数据重采样（au1912是你回测的 

品种，5min是回测的数据周期频率） 

QARC_Start --code au1912：启动交易品种的策略信号 

QACTPBEE --user 133495（这个可以换成自己的simnow账号，simnow账号可以去simnow官网申请）： 

连接模拟行情 

from QAStrategy import QAStrategyCTABase from QIFIAccount import QIFI_Account 

import QUANTAXIS as QA 

from qaenv import (eventmq_amqp, eventmq_ip, eventmq_password, eventmq_port, 

eventmq_username, mongo_ip, mongo_uri) import pandas as pd 

#示例策略 

class macd(QAStrategyCTABase): 

def on_bar(self, bar): 

#print(bar) 

if len(self.market_data)> 26: 

res = self.calc_macd() 

ex = QA.CROSS(res['MACD'], 0) 

ec = QA.CROSS(0, res['MACD']) 

if ex.iloc[-1]> 0: 

print('sendorder') 

self.send_order('BUY', 'OPEN', price=bar['close'], volume= 1) 

elif ec.iloc[-1]>0: 

print('sendorder') 

self.send_order('SELL', 'CLOSE', price=bar['close'], volume= 1) 

def calc_macd(self): 

macd = QA.QA_indicator_MACD(self.market_data) 

return macd.tail() 

strategy = macd(code='au1912', frequence='5min', strategy_id='autest', 

start='2019-10-01') 

# 运行模拟盘 strategy.run_sim()**二、docker版本的配置**

大家都体会到了，非docker版本的配置流程相对繁琐，所以QA提供了qaservice，这是QA面向不想自

己配环境的小白提供的一键配置环境解决方案，具体怎么配置，QA的文档配置写的 已经很清楚了，我

就不多说了，点击这里查看

至于实盘的量化交易、微信信号订阅等这些问题，我们后面再进一步解说，今天就先到这 里，欢迎持续

关注。