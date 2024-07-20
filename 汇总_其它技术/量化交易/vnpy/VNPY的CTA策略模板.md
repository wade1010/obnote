[https://www.vnpy.com/docs/cn/cta_strategy.html#cta-ctatemplate](https://www.vnpy.com/docs/cn/cta_strategy.html#cta-ctatemplate)

CTA策略模板提供了信号生成和委托管理功能，用户可以基于该模板(位于site-packages\vnpy_ctastrategy\template中)自行开发CTA策略。

用户自行开发的策略可以放在用户运行文件夹下的[strategies](https://www.vnpy.com/docs/cn/cta_strategy.html#jump)文件夹内。

请注意：

1. 策略文件命名采用下划线模式，如boll_channel_strategy.py，而策略类命名采用驼峰式，如BollChannelStrategy。

1. 自建策略的类名不要与示例策略的类名重合。如果重合了，图形界面上只会显示一个策略类名。

下面通过BollChannelStrategy策略示例，来展示策略开发的具体步骤：

在基于CTA策略模板编写策略逻辑之前，需要在策略文件的顶部载入需要用到的内部组件，如下方代码所示：

```
from vnpy_ctastrategy import (
    CtaTemplate,
    StopOrder,
    TickData,
    BarData,
    TradeData,
    OrderData,
    BarGenerator,
    ArrayManager
)

```

其中，CtaTemplate是CTA策略模板，StopOrder、TickData、BarData、TradeData和OrderData都是储存对应信息的数据容器，BarGenerator是K线生成模块，ArrayManager是K线时间序列管理模块。

### 策略参数与变量

在策略类的下方，可以设置策略的作者（author），参数（parameters）以及变量（variables），如下方代码所示：

```

    author = "用Python的交易员"

    boll_window = 18
    boll_dev = 3.4
    cci_window = 10
    atr_window = 30
    sl_multiplier = 5.2
    fixed_size = 1

    boll_up = 0
    boll_down = 0
    cci_value = 0
    atr_value = 0

    intra_trade_high = 0
    intra_trade_low = 0
    long_stop = 0
    short_stop = 0

    parameters = [
        "boll_window",
        "boll_dev",
        "cci_window",
        "atr_window",
        "sl_multiplier",
        "fixed_size"
    ]
    variables = [
        "boll_up",
        "boll_down",
        "cci_value",
        "atr_value",
        "intra_trade_high",
        "intra_trade_low",
        "long_stop",
        "short_stop"
    ]

```

虽然策略的参数和变量都从属于策略类，但策略参数是固定的（由交易员从外部指定），而策略变量则在交易的过程中随着策略的状态而变化，所以策略变量一开始只需要初始化为对应的基础类型。例如：整数设为0，浮点数设为0.0，而字符串则设为””。

如果需要CTA引擎在运行过程中，将策略参数和变量显示在UI界面上，并在数据刷新、停止策略时保存其数值，则需把参数和变量的名字（以字符串的数据类型）添加进parameters和variables列表里。

请注意，该列表只能接受参数或变量以str、int、float和bool四种数据类型传入。如果策略里需要用到其他数据类型的参数与变量，请把该参数或变量的定义放到__init__函数下。

### 类的初始化

入参：cta_engine: Any, strategy_name: str, vt_symbol: str, setting: dict

出参：无

__init__函数是策略类的构造函数，需要与继承的CtaTemplate保持一致。

在这个继承的策略类里，初始化一般分三步，如下方代码所示：

```
    def __init__(self, cta_engine, strategy_name, vt_symbol, setting):
        """"""
        super().__init__(cta_engine, strategy_name, vt_symbol, setting)

        self.bg = BarGenerator(self.on_bar, 15, self.on_15min_bar)
        self.am = ArrayManager()

```

1 . 通过super( )的方法继承CTA策略模板，在__init__( )函数中传入CTA引擎、策略名称、vt_symbol以及参数设置。注意其中的CTA引擎，可以是实盘引擎或者回测引擎，这样可以方便地**实现同一套代码同时跑回测和实盘**（以上参数均由策略引擎在使用策略类创建策略实例时自动传入，用户无需进行设置）。

2 . 调用K线生成模块（BarGenerator）：通过时间切片将Tick数据合成1分钟K线数据。如有需求，还可合成更长的时间周期数据，如15分钟K线。

如果只基于on_bar进行交易，这里代码可以写成：

```
        self.bg = BarGenerator(self.on_bar)

```

而不用给bg实例传入需要基于on_bar周期合成的更长K线周期，以及接收更长K线周期的函数名。

请注意，合成X分钟线时，X必须设为能被60整除的数（60除外）。对于小时线的合成没有这个限制。

BarGenerator默认的基于on_bar函数合成长周期K线的数据频率是分钟级别，如果需要基于合成的小时线或者更长周期的K线交易，请在策略文件顶部导入Interval，并传入对应的数据频率给bg实例。如下方代码所示：

文件顶部导入Interval：

```
from vnpy.trader.constant import Interval

```

__init__函数创建bg实例时传入数据频率：

```
        self.bg = BarGenerator(self.on_bar, 2, self.on_2hour_bar, Interval.HOUR)

```

3 . 调用K线时间序列管理模块（ArrayManager）：基于K线数据，如1分钟、15分钟， 将其转化为便于向量化计算的时间序列数据结构，并在内部支持使用talib库来计算相应的技术指标。

ArrayManager的默认长度为100，如需调整ArrayManager的长度，可传入size参数进行调整（size不能小于计算指标的周期长度）。

### CTA策略引擎调用的函数

CtaTemplate中的update_setting函数和该函数后面四个以get开头的函数，都是CTA策略引擎去负责调用的函数，一般在策略编写的时候是不需要调用的。

### 策略的回调函数

CtaTemplate中以on开头的函数称为回调函数，在编写策略的过程中能够用来接收数据或者接收状态更新。回调函数的作用是当某一个事件发生的时候，策略里的这类函数会被CTA策略引擎自动调用（无需在策略中主动操作）。回调函数按其功能可分为以下三类：

#### 策略实例状态控制（所有策略都需要）

**on_init**

- 入参：无

- 出参：无

初始化策略时on_init函数会被调用，默认写法是先调用write_log函数输出“策略初始化”日志，再调用load_bar函数加载历史数据，如下方代码所示：

```
    def on_init(self):
        """
        Callback when strategy is inited.
        """
        self.write_log("策略初始化")
        self.load_bar(10)

```

请注意，如果是基于Tick数据回测，请在此处调用load_tick函数。

策略初始化时，策略的inited和trading状态都为【False】，此时只是调用ArrayManager计算并缓存相关的计算指标，不能发出交易信号。调用完on_init函数之后，策略的inited状态才变为【True】，策略初始化才完成。

**on_start**

- 入参：无

- 出参：无

启动策略时on_start函数会被调用，默认写法是调用write_log函数输出“策略启动”日志，如下方代码所示：

```
    def on_start(self):
        """
        Callback when strategy is started.
        """
        self.write_log("策略启动")

```

调用策略的on_start函数启动策略后，策略的trading状态变为【True】，此时策略才能够发出交易信号。

**on_stop**

- 入参：无

- 出参：无

停止策略时on_stop函数会被调用，默认写法是调用write_log函数输出“策略停止”日志，如下方代码所示：

```
    def on_stop(self):
        """
        Callback when strategy is stopped.
        """
        self.write_log("策略停止")

```

调用策略的on_stop函数停止策略后，策略的trading状态变为【False】，此时策略就不会发出交易信号了。

#### 接收数据、计算指标、发出交易信号

**on_tick**

- 入参：tick: TickData

- 出参：无

绝大部分交易系统都只提供Tick数据的推送。即使一部分平台可以提供K线数据的推送，但是这些数据到达本地电脑的速度也会慢于Tick数据的推送，因为也需要平台合成之后才能推送过来。所以实盘的时候，VeighNa里所有的策略的K线都是由收到的Tick数据合成的。

当策略收到最新的Tick数据的行情推送时，on_tick函数会被调用。默认写法是通过BarGenerator的update_tick函数把收到的Tick数据推进前面创建的bg实例中以便合成1分钟的K线，如下方代码所示：

```
    def on_tick(self, tick: TickData):
        """
        Callback of new tick data update.
        """
        self.bg.update_tick(tick)

```

**on_bar**

- 入参：bar: BarData

- 出参：无

当策略收到最新的K线数据时（实盘时默认推进来的是基于Tick合成的一分钟的K线，回测时则取决于选择参数时填入的K线数据频率），on_bar函数就会被调用。示例策略里出现过的写法有两种：

1 . 如果策略基于on_bar推进来的K线交易，那么请把交易请求类函数都写在on_bar函数下（因本次示例策略类BollChannelStrategy不是基于on_bar交易，故不作示例讲解。基于on_bar交易的示例代码可参考其他示例策略）；

2 . 如果策略需要基于on_bar推进来的K线数据通过BarGenerator合成更长时间周期的K线来交易，那么请在on_bar中调用BarGenerator的update_bar函数，把收到的这个bar推进前面创建的bg实例中即可，如下方代码所示：

```
    def on_bar(self, bar: BarData):
        """
        Callback of new bar data update.
        """
        self.bg.update_bar(bar)

```

示例策略类BollChannelStrategy是通过15分钟K线数据回报来生成CTA信号的。一共有三部分，如下方代码所示：

```
    def on_15min_bar(self, bar: BarData):
        """"""
        self.cancel_all()

        am = self.am
        am.update_bar(bar)
        if not am.inited:
            return

        self.boll_up, self.boll_down = am.boll(self.boll_window, self.boll_dev)
        self.cci_value = am.cci(self.cci_window)
        self.atr_value = am.atr(self.atr_window)

        if self.pos == 0:
            self.intra_trade_high = bar.high_price
            self.intra_trade_low = bar.low_price

            if self.cci_value > 0:
                self.buy(self.boll_up, self.fixed_size, True)
            elif self.cci_value < 0:
                self.short(self.boll_down, self.fixed_size, True)

        elif self.pos > 0:
            self.intra_trade_high = max(self.intra_trade_high, bar.high_price)
            self.intra_trade_low = bar.low_price

            self.long_stop = self.intra_trade_high - self.atr_value * self.sl_multiplier
            self.sell(self.long_stop, abs(self.pos), True)

        elif self.pos < 0:
            self.intra_trade_high = bar.high_price
            self.intra_trade_low = min(self.intra_trade_low, bar.low_price)

            self.short_stop = self.intra_trade_low + self.atr_value * self.sl_multiplier
            self.cover(self.short_stop, abs(self.pos), True)

        self.put_event()

```

- 清空未成交委托：为了防止之前下的单子在上一个15分钟没有成交，但是下一个15分钟可能已经调整了价格，就用cancel_all()方法立刻撤销之前未成交的所有委托，保证策略在当前这15分钟开始时的整个状态是清晰和唯一的；

- 调用K线时间序列管理模块：基于最新的15分钟K线数据来计算相应的技术指标，如布林带通道上下轨、CCI指标、ATR指标等。首先获取ArrayManager对象，然后将收到的K线推送进去，检查ArrayManager的初始化状态，如果还没初始化成功就直接返回，没有必要去进行后续的交易相关的逻辑判断。因为很多技术指标计算对最少K线数量有要求，如果数量不够的话计算出来的指标会出现错误或无意义。反之，如果没有return，就可以开始计算技术指标了；

- 信号计算：通过持仓的判断以及结合CCI指标、布林带通道、ATR指标在通道突破点挂出停止单委托（buy/sell)，同时设置离场点(short/cover)。

请注意，如果需要在图形界面刷新指标数值，请不要忘记调用put_event()函数。

#### 委托状态更新

以下函数在策略中可以直接pass，其具体逻辑应用交给回测/实盘引擎负责。

**on_trade**

- 入参：bar: TradeData

- 出参：无

收到策略成交回报时on_trade函数会被调用。

**on_order**

- 入参：bar: OrderData

- 出参：无

收到策略委托回报时on_order函数会被调用。

**on_stop_order**

- 入参：bar: StopOrder

- 出参：无

收到策略停止单回报时on_stop_order函数会被调用。

### 主动函数

**buy**：买入开仓（Direction：LONG，Offset：OPEN）

**sell**：卖出平仓（Direction：SHORT，Offset：CLOSE）

**short**：卖出开仓（Direction：SHORT，Offset：OPEN）

**cover**：买入平仓（Direction：LONG，Offset：CLOSE）

- 入参：price: float, volume: float, stop: bool = False, lock: bool = False, net: bool = False

- 出参：vt_orderids: List[vt_orderid] / 无

buy/sell/short/cover都是策略内部的负责发单的交易请求类函数。策略可以通过这些函数给CTA策略引擎发送交易信号来达到下单的目的。

以下方buy函数的代码为例，可以看到，价格和数量是必填的参数，停止单转换、锁仓转换和净仓转换则默认为False。也可以看到，函数内部收到传进来的参数之后就调用了CtaTemplate里的send_order函数来发单（因为是buy指令，则自动把方向填成了LONG，开平填成了OPEN）

如果stop设置为True，那么该笔订单则会自动转成停止单，如果接口支持交易所停止单则会转成交易所停止单，如果接口不支持交易所停止单则会转换成VeighNa的本地停止单。

如果lock设置为True，那么该笔订单则会进行锁仓委托转换（在有今仓的情况下，如果想平仓，则会先平掉所有的昨仓，然后剩下的部分都进行反向开仓来代替平今仓，以避免平今的手续费惩罚）。

如果net设置为True，那么该笔订单则会进行净仓委托转换（基于整体账户的所有仓位，根据净仓持有方式来对策略下单的开平方向进行转换）。但是净仓交易模式与锁仓交易模式互斥，因此net设置为True时，lock必须设置为False。

请注意，如果向上期所发出平仓委托，因为该交易所必须指定平今、平昨，底层会对其平仓指令自动进行转换。因为上期所部分品种有平今优惠，所以默认是以平今优先的方式发出委托的（如果交易的标的在上期所平昨更优惠的话，可以自行在vnpy.trader.converter的convert_order_request_shfe函数中做适当的修改）。

```
    def buy(self, price: float, volume: float, stop: bool = False, lock: bool = False, net: bool = False):
        """
        Send buy order to open a long position.
        """
        return self.send_order(Direction.LONG, Offset.OPEN, price, volume, stop, lock, net)

```

请注意，国内期货有开平仓的概念，例如买入操作要区分为买入开仓和买入平仓；但对于股票、外盘期货都是净持仓模式，没有开仓和平仓概念，所以只需使用买入（buy）和卖出（sell）这两个指令就可以了。

**send_order**

- 入参：direction: Direction, offset: Offset, price: float, volume: float, stop: bool = False, lock: bool = False, net: bool = False

- 出参：vt_orderids / 无

send_order函数是CTA策略引擎调用的发送委托的函数。一般在策略编写的时候不需要单独调用，通过buy/sell/short/cover函数发送委托即可。

实盘的时候，收到传进来的参数后，会调用round_to函数基于合约的pricetick和min_volume对委托的价格和数量进行处理。

请注意，要在策略启动之后，也就是策略的trading状态变为【True】之后，才能发出交易委托。如果策略的Trading状态为【False】时调用了该函数，只会返回[]。

**cancel_order**

- 入参：vt_orderid: str

- 出参：无

**cancel_all**

- 入参：无

- 出参：无

cancel_order和cancel_all都是负责撤单的交易请求类函数。cancel_order是撤掉策略内指定的活动委托，cancel_all是撤掉策略所有的活动委托。

请注意，要在策略启动之后，也就是策略的trading状态变为【True】之后，才能撤单。

### 功能函数

以下为策略以外的功能函数：

**write_log**

- 入参：msg: str

- 出参：无

在策略中调用write_log函数，可以进行指定内容的日志输出。

**get_engine_type**

- 入参：无

- 出参：engine_type: EngineType

如果策略对于回测和实盘时有不同的逻辑处理，可以调用get_engine_type函数获取当下使用的引擎类型来进行逻辑判断。

请注意，如果要调用该函数进行逻辑判断，请在策略文件顶部导入“EngineType”。

**get_pricetick**

- 入参：无

- 出参：pricetick: float / None

在策略中调用get_pricetick函数，可以获取交易合约的最小价格跳动。

**load_bar**

- 入参：days: int, interval: Interval = Interval.MINUTE, callback: Callable = None, use_database: bool = False

- 出参：无

在策略中调用load_bar函数，可以在策略初始化时加载K线数据。

如下方代码所示，调用load_bar函数时，默认加载的天数是10，频率是一分钟，对应也就是加载10天的1分钟K线数据。在回测时，10天指的是10个交易日，而在实盘时，10天则是指的是自然日，因此建议加载的天数宁可多一些也不要太少。use_database参数默认为False，会先依次尝试通过交易接口、数据服务、数据库获取历史数据，直到获取历史数据或返回空。当use_database设置为True后，会跳过通过交易接口和数据服务获取历史数据，直接去数据库查询。

```
    def load_bar(
        self,
        days: int,
        interval: Interval = Interval.MINUTE,
        callback: Callable = None,
        use_database: bool = False
    ):
        """
        Load historical bar data for initializing strategy.
        """
        if not callback:
            callback = self.on_bar

        self.cta_engine.load_bar(
            self.vt_symbol,
            days,
            interval,
            callback,
            use_database
        )

```

**load_tick**

- 入参：days: int

- 出参：无

在策略中调用load_tick函数，可以在策略初始化时加载Tick数据。

**put_event**

- 入参：无

- 出参：无

在策略中调用put_event函数，可以通知图形界面刷新策略状态相关显示。

请注意，要策略初始化完成，inited状态变为【True】之后，才能刷新界面。

**send_email**

- 入参：msg: str

- 出参：无

配置好邮箱相关信息之后（配置方法详见基本使用篇的全局配置部分），在策略中调用send_email函数，可以发送指定内容的邮件到自己的邮箱。

请注意，要策略初始化完成，inited状态变为【True】之后，才能发送邮件。

**sync_data**

- 入参：无

- 出参：无

在策略中调用sync_data函数，可以在实盘交易的时候，每次停止或成交时都同步策略变量到对应json文件中进行本地缓存，方便第二天初始化时再进行读取还原（CTA策略引擎会去调用，在策略里无需主动调用）。

请注意，要在策略启动之后，也就是策略的trading状态变为【True】之后，才能同步策略信息。