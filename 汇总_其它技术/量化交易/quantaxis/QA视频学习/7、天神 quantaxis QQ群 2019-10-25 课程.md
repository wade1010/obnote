![](https://gitee.com/hxc8/images5/raw/master/img/202407172334279.jpg)

[https://github.com/QUANTAXIS/QIFI](https://github.com/QUANTAXIS/QIFI)

[](https://github.com/QUANTAXIS/QIFI)

QIFI协议最开始的实现是QATrader

![](https://gitee.com/hxc8/images5/raw/master/img/202407172334070.jpg)

QATrader是可以让你直接连一个模拟的，也可以让你连一个实盘。

并且可以通过http下单

它是基于撮合商的模拟，不是本地的模拟

有了QATrader以后，再抽出来，抽出来之后我们就可以去实现papertrading

实现了本地的papertrading以后，这个东西就叫QIFIAccount

![](https://gitee.com/hxc8/images5/raw/master/img/202407172334382.jpg)

有了QIFIAcount以后，你就有了这个界面

![](https://gitee.com/hxc8/images5/raw/master/img/202407172334214.jpg)

并且你 有了QIFI协议，搭配自己的行情，比如你用一个randomPrice，就是自己定义的行情网关来实现你要的东西，这个行情网关可以是randomPrice，也可以是个历史的数据，也可以是个tick数据。

有了行情以后，我们订阅了行情，这个行情推给我数据，我就可以基于这个推送事件写一个onbar或者ontick， 就相当于驱动策略的驱动器，有了驱动器以后，你的策略就会被调用。你的策略调用以后， 它下单，下单以后，它就产生有qifi协议的字段。这个字段就可以展示在前端页面，这就是papertrading的实现。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172334750.jpg)

QAMarketCollector它可以支持股票的5档全推，就是本地全推。另外期货的全推，可以订阅。然后就是期货可以 采样的订阅，当然也支持随机 价格，也就是基于randomprice实现

今天的主题就是如何去把本地的回测和实盘一键切换

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335398.jpg)

```
import QUANTAXIS as QA



user = QA.QA_User(username='admin',password='admin')


port = user.new_portfolio('test')


accpro = port.new_accountpro('acccountqapro')

accpro.positions



accpro.receive_deal(
    '000001',
    trade_id='test',
    order_id='test',
    realorder_id='realtest',
    trade_price=12,
    trade_amount=1000,
    trade_towards=QA.ORDER_DIRECTION.BUY,
    trade_time='2022-08-18',
    message=None,
)


accpro.positions




accpro.history_table


accpro.receive_deal(
    '000002',
    trade_id='test2',
    order_id='test2',
    realorder_id='realtest2',
    trade_price=20,
    trade_amount=3000,
    trade_towards=QA.ORDER_DIRECTION.BUY,
    trade_time='2022-08-18',
    message=None,
)



accpro.positions


accpro.cash_available


#%%

pos = accpro.get_position('000001')

#%%

pos.message

#%%

pos.volume_long

#%%

pos.volume_long_today

#%%

pos.volume_long_his

#%%
#获取最新价格
pos.last_price

#%%
#修改价格，方面下面计算利润和亏损
pos.on_price_change(13)

#%%

pos.realtime_message

#%%

#%%
#修改价格，方面下面计算利润和亏损
pos.on_price_change(11)

pos.realtime_message

#%%



```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335154.jpg)

```
{'code': '000001',
 'instrument_id': '000001',
 'user_id': 'acccountqapro',
 'portfolio_cookie': 'test',
 'username': 'quantaxis',
 'position_id': '7e87d1c2-8b40-4617-ba31-0e61d2b1f2d1',
 'account_cookie': 'acccountqapro',
 'frozen': {},
 'name': None,
 'spms_id': None,
 'oms_id': None,
 'market_type': 'stock_cn',
 'exchange_id': None,
 'moneypreset': 100000,
 'moneypresetLeft': 88000.0,
 'lastupdatetime': '',
 'volume_long_today': 1000,
 'volume_long_his': 0,
 'volume_long': 1000,
 'volume_short_today': 0,
 'volume_short_his': 0,
 'volume_short': 0,
 'volume_long_frozen_today': 0,
 'volume_long_frozen_his': 0,
 'volume_long_frozen': 0,
 'volume_short_frozen_today': 0,
 'volume_short_frozen_his': 0,
 'volume_short_frozen': 0,
 'margin_long': 12000.0,
 'margin_short': 0,
 'margin': 12000.0,
 'position_price_long': 12.0,
 'position_cost_long': 12000.0,
 'position_price_short': 0,
 'position_cost_short': 0.0,
 'open_price_long': 12.0,
 'open_cost_long': 12000.0,
 'open_price_short': 0,
 'open_cost_short': 0.0,
 'trades': [],
 'orders': {},
 'last_price': 12,
 'float_profit_long': 0.0,
 'float_profit_short': 0.0,
 'float_profit': 0.0,
 'position_profit_long': 0.0,
 'position_profit_short': 0.0,
 'position_profit': 0.0}
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335532.jpg)

 

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335676.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335838.jpg)

群主说QAAcountPro和QAStrategy是为了让我们更加理解这个事，但是我们倒不是核心的去使用这两个东西，因为QA它挺大的，而且挺难用的，四个难用：安装难用，回测难用，模拟难用，实盘难用。

下面说下QIFIAccount

```
#%%
import QUANTAXIS as QA
from QIFIAccount import QIFI_Account
#%%

qifiacc=QIFI_Account(username='testqifi',password='testqifi')

#%%

qifiacc.initial()

#%%
#首先会给自己转账1000000万
qifiacc.event


#%%
qifiacc.account_msg


#%%
#这个时候可能不会成交，会出现在委托记录里面
sr = qifiacc.send_order('000001',10,12,QA.ORDER_DIRECTION.BUY)

#%%

sr

#%%
#直接让它成交
td = qifiacc.make_deal(sr)

#%%

td

#%%




```

2

import QUANTAXIS as QA

from QIFIAccount import QIFI_Account

5

qifiacc=QIFI_Account(username='testqifi',password='testqifi')

6

qifiacc.initial()

Create new Account

7

#首先会给自己转账1000000万

qifiacc.event

7

{'2022-08-20 10:19:16_770571': '转账成功 1000000'}

8

qifiacc.account_msg

8

{'user_id': 'testqifi',

'currency': 'CNY',

'pre_balance': 0,

'deposit': 1000000,

'withdraw': 0,

'WithdrawQuota': 0,

'close_profit': 0,

'commission': 0,

'premium': 0,

'static_balance': 0,

'position_profit': 0,

'float_profit': 0,

'balance': 1000000,

'margin': 0,

'frozen_margin': 0,

'frozen_commission': 0.0,

'frozen_premium': 0.0,

'available': 1000000,

'risk_ratio': 0.0}

9

#这个时候可能不会成交，会出现在委托记录里面

sr = qifiacc.send_order('000001',10,12,QA.ORDER_DIRECTION.BUY)

{'volume_long': 0, 'volume_short': 0, 'volume_long_frozen': 0, 'volume_short_frozen': 0}

{'volume_long': 0, 'volume_short': 0}

order check success

下单成功 b7b45506-2f9a-4e64-b682-30406cec5e11

10

sr

10

{'account_cookie': 'testqifi',

'user_id': 'testqifi',

'instrument_id': '000001',

'towards': 1,

'exchange_id': 'stock_cn',

'order_time': '2022-08-20 10:27:10_027455',

'volume': 10,

'price': 12.0,

'order_id': 'b7b45506-2f9a-4e64-b682-30406cec5e11',

'seqno': 1,

'direction': 'BUY',

'offset': 'OPEN',

'volume_orign': 10,

'price_type': 'LIMIT',

'limit_price': 12.0,

'time_condition': 'GFD',

'volume_condition': 'ANY',

'insert_date_time': 1660962430024815104,

'exchange_order_id': 'd44a3068-def4-4c50-8082-95d81288d05f',

'status': 'ALIVE',

'volume_left': 10,

'last_msg': '已报'}

11

#直接让它成交

td = qifiacc.make_deal(sr)

全部成交 b7b45506-2f9a-4e64-b682-30406cec5e11

update trade

12

td

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335352.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335813.jpg)

```
import QUANTAXIS as QA
from QAStrategy import QAStrategyCTABase


class macd(QAStrategyCTABase):
    def on_bar(self, data):
        # print(data)
        ind = self.macd()
        macd_num = ind.MACD.iloc[-1]
        if macd_num > 0:
            if self.positions.volume_long == 0:
                self.send_order('BUY', 'OPEN', price=data['close'], volume=20)
            if self.positions.volume_short > 0:
                self.send_order('BUY', 'CLOSE', price=data['close'], volume=20)
        elif macd_num < 0:
            if self.positions.volume_long > 0:
                self.send_order('SELL', 'CLOSE', price=data['close'], volume=20)
            if self.positions.volume_short == 0:
                self.send_order('SELL', 'OPEN', price=data['close'], volume=20)

    def macd(self):
        return QA.QA_indicator_MACD(self.market_data, 12, 26, 9).tail()


s = macd(code='ZNL9', frequence='15min')

#s.debug()

s.run_backtest()

```

```
prortfolio with user_cookie  USER_SafRvwJb  already exist!!
QAACCPRO: reload from DATABASE
{}
< QA_AccountPRO QA_STRATEGY market: future_cn>
future_cn
------this is on deal message ------
{'code': 'ZNL9', 'trade_time': '2020-01-03 09:15:00', 'trade_towards': 2, 'trade_amount': 20, 'trade_price': 17915.0, 'order_id': 'a9b42fcf-292b-42c6-b529-1931e8467505', 'realorder_id': 'a9b42fcf-292b-42c6-b529-1931e8467505', 'trade_id': 'a9b42fcf-292b-42c6-b529-1931e8467505'}
{'volume_long': 20, 'volume_short': 0, 'volume_long_frozen': 0, 'volume_short_frozen': 0}
------this is on deal message ------
{'code': 'ZNL9', 'trade_time': '2020-01-03 09:30:00', 'trade_towards': -3, 'trade_amount': 20, 'trade_price': 17900.0, 'order_id': 'c4ab4ef5-39c9-4ce9-9b4b-beea25f8ed98', 'realorder_id': 'c4ab4ef5-39c9-4ce9-9b4b-beea25f8ed98', 'trade_id': 'c4ab4ef5-39c9-4ce9-9b4b-beea25f8ed98'}
------this is on deal message ------
{'code': 'ZNL9', 'trade_time': '2020-01-03 09:30:00', 'trade_towards': -2, 'trade_amount': 20, 'trade_price': 17900.0, 'order_id': '57382b1d-1721-4213-8d92-263d39a81ee6', 'realorder_id': '57382b1d-1721-4213-8d92-263d39a81ee6', 'trade_id': '57382b1d-1721-4213-8d92-263d39a81ee6'}
------this is on deal message ------
{'code': 'ZNL9', 'trade_time': '2020-01-03 22:45:00', 'trade_towards': 2, 'trade_amount': 20, 'trade_price': 17790.0, 'order_id': '71871a75-e111-416a-81e2-ce2dd8be51b1', 'realorder_id': '71871a75-e111-416a-81e2-ce2dd8be51b1', 'trade_id': '71871a75-e111-416a-81e2-ce2dd8be51b1'}
------this is on deal message ------
{'code': 'ZNL9', 'trade_time': '2020-01-03 22:45:00', 'trade_towards': 3, 'trade_amount': 20, 'trade_price': 17790.0, 'order_id': '46b35c7b-c8b4-49d3-aff7-2e6b08afe15b', 'realorder_id': '46b35c7b-c8b4-49d3-aff7-2e6b08afe15b', 'trade_id': '46b35c7b-c8b4-49d3-aff7-2e6b08afe15b'}
{'volume_long': 20, 'volume_short': 0, 'volume_long_frozen': -20, 'volume_short_frozen': -20}
------this is on deal message ------
{'code': 'ZNL9', 'trade_time': '2020-01-06 22:00:00', 'trade_towards': -3, 'trade_amount': 20, 'trade_price': 18050.0, 'order_id': '64ca9eba-5461-4fcb-9dee-84cf5143c389', 'realorder_id': '64ca9eba-5461-4fcb-9dee-84cf5143c389', 'trade_id': '64ca9eba-5461-4fcb-9dee-84cf5143c389'}
.........
```

申请一个行情

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335628.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335152.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335654.jpg)