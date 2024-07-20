[https://xiaofan.art/knowledge/d1be47f9274e8671/](https://xiaofan.art/knowledge/d1be47f9274e8671/)

QUANTAXIS本地数据本地撮合，以及后面的模拟到实盘，都基于QAPUBSUB实现。

[QUANTAXIS-QAPUBSUB消息订阅组件](https://xiaofan.art/knowledge/9415a00b301cc96e)

```
# 打开8888页面，开启一个命令行，执行下面3个命令
/bin/bash
quantaxis
save single_future_day RBL8
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348569.jpg)

## import依赖

```
import QUANTAXIS as QA
from QAPUBSUB.consumer import subscriber  # 消费者
from QAPUBSUB.producer import publisher  # 生产者
import threading  # 在线程中运行消费者，防止线程阻塞
import json  # 消费者接收的数据是文本，转成json
import pandas as pd  # json转成DataFrame
```

## 账户准备

用户(User) -> 组合(Portfolio) -> 账户(Account)

一个用户下面可以有多个组合，一个组合下面可以有多个账户，实际交易的是账户。就像一个基金经理，操作多个投资组合，一个投资组合分多个策略，在不同的账户中执行。

```
# 1. 账户准备
user = QA.QA_User(username='admin', password='admin')  # 账号密码跟81页面登录的账号密码一致
# portfolio_cookie就像是组合的id
port = user.new_portfolio(portfolio_cookie='x1')
# account_cookie就像是账户的id，init_cash是账户的初始资金，market_type为市场类型，QA中通过market_type预设了交易规则，例如期货允许t0等，与国内的交易规则一致。
acc = port.new_account(account_cookie='test_local_simpledeal', init_cash=100000, market_type=QA.MARKET_TYPE.FUTURE_CN)
```

## 发单操作方法

发单用的是QAAccount的receive_simpledeal方法，这个方法会直接完成撮合，一定成交。而模拟盘和实盘使用的是QATrader，根据实时行情撮合，不一定成交。

```
# 2. 发单操作方法
def sendorder(code, trade_price, trade_amount, trade_towards, trade_time):
	acc.receive_simpledeal(
		code=code,
		trade_price=trade_price,
		trade_amount=trade_amount,
		trade_towards=trade_towards,
		trade_time=trade_time)
```

## 策略

MA2 > MA4：买多，有多头仓平空

MA4 > MA2：卖空，有空头仓平多

```
# 3. 策略
market_data_list = []  # 存储历史数据
# 下面订阅数据时会指定on_data为回调函数，接到数据就会执行on_data
def on_data(a, b, c, data):
    # 数据准备
    bar = json.loads(data)
    market_data_list.append(bar)
	# 日线date格式是2019-01-01
    market_data_df = pd.DataFrame(market_data_list).set_index('date', drop=False)

    # 计算指标
    ind = QA.QA_indicator_MA(market_data_df, 2, 4)  # 计算MA2和MA4
    print(ind)
    
    # 策略逻辑
    MA2 = ind.iloc[-1]['MA2']  # 取最新的MA2
    MA4 = ind.iloc[-1]['MA4']  # 取最新的MA4
    code = bar['code']  # 合约代码
    trade_price = bar['close']  # 最新收盘价
    trade_amount = 1  # 1手
    trade_time = bar['date'] + ' 00:00:00'  # 由于日线date的格式是2019-01-01，所以要加后面的时间，否则无法计算指标，后面的问题章节有详细说明。
    code_hold_available = acc.hold_available.get(code, 0)  # 合约目前的持仓情况
    if MA2 > MA4:
        if code_hold_available == 0:
            print('买多')
			sendorder(code, trade_price, QA.ORDER_DIRECTION.BUY_OPEN, trade_time)
        elif code_hold_available > 0:
            print('持有')
        else:
            print('平空')
			sendorder(code, trade_price, QA.ORDER_DIRECTION.BUY_CLOSE, trade_time)
    elif MA4 > MA2:
        if code_hold_available == 0:
            print('卖空')
			sendorder(code, trade_price, QA.ORDER_DIRECTION.SELL_OPEN, trade_time)
        elif code_hold_available < 0:
            print('持有')
        else:
            print('平多')
			sendorder(code, trade_price, QA.ORDER_DIRECTION.SELL_CLOSE, trade_time)
    else:
        print('不操作')
```

## 订阅数据

```
# 4. 订阅数据
sub = subscriber(exchange='x1')  # Exchange名为x1，在15672页面能看到
sub.callback=on_data  # 指定回调函数
threading.Thread(target=sub.start).start()  # 开线程执行订阅，防止线程阻塞，后面的发布代码无法执行
```

## 数据获取并推送数据

```
# 5. 数据获取并推送数据
# 获取
df = QA.QA_fetch_get_future_day('tdx', 'RBL8', '2019-08-01', '2019-08-30')
# 推送
pub = publisher(exchange='x1')  # 跟订阅的Exchange一致
for idx, item in df.iterrows():
    pub.pub(item.to_json())  # 每行数据换成json，pub出去，上面的on_data就会收到，开始执行策略。
```

## 查看结果

```
# 6. 查看结果
risk = QA.QA_Risk(acc)
performance = QA.QA_Performance(acc)

acc.history_table  # 交易记录
risk.plot_assets_curve()  # 资产曲线
performance.pnl  # 盈利情况
```

## 保存结果

```
# 7. 保存结果
risk.save()
```

risk保存后，81页面查看回测结果。绩效分析结果没有。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348392.jpg)

```
acc.save()
```

保存acc后，再看81页面，绩效分析就开始转圈，原因不明。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348478.jpg)

## 完整代码

jupyter 

[https://gitee.com/xiongyifan/quantaxis-study/blob/master/code/QUANTAXIS%E6%9C%AC%E5%9C%B0%E6%95%B0%E6%8D%AE%E6%9C%AC%E5%9C%B0%E6%92%AE%E5%90%88.ipynb](https://gitee.com/xiongyifan/quantaxis-study/blob/master/code/QUANTAXIS%E6%9C%AC%E5%9C%B0%E6%95%B0%E6%8D%AE%E6%9C%AC%E5%9C%B0%E6%92%AE%E5%90%88.ipynb)

```
#!/usr/bin/env python
# coding: utf-8

import QUANTAXIS as QA
from QAPUBSUB.consumer import subscriber  # 消费者
from QAPUBSUB.producer import publisher  # 生产者
import threading  # 在线程中运行消费者，防止线程阻塞
import json  # 消费者接收的数据是文本，转成json
import pandas as pd  # json转成DataFrame


# 1. 账户准备
user = QA.QA_User(username='admin', password='admin')  # 账号密码跟81页面登录的账号密码一致
# portfolio_cookie就像是组合的id
port = user.new_portfolio(portfolio_cookie='x1')
# account_cookie就像是账户的id，init_cash是账户的初始资金，market_type为市场类型，QA中通过market_type预设了交易规则，例如期货允许t0等，与国内的交易规则一致。
acc = port.new_account(account_cookie='test_local_simpledeal', init_cash=100000, market_type=QA.MARKET_TYPE.FUTURE_CN)

# 2. 发单操作方法
def sendorder(code, trade_price, trade_amount, trade_towards, trade_time):
	acc.receive_simpledeal(
		code=code,
		trade_price=trade_price,
		trade_amount=trade_amount,
		trade_towards=trade_towards,
		trade_time=trade_time)

# 3. 策略
market_data_list = []  # 存储历史数据
# 下面订阅数据时会指定on_data为回调函数，接到数据就会执行on_data
def on_data(a, b, c, data):
    # 数据准备
    bar = json.loads(data)
    market_data_list.append(bar)
    # 日线date格式是2019-01-01
    market_data_df = pd.DataFrame(market_data_list).set_index('date', drop=False)

    # 计算指标
    ind = QA.QA_indicator_MA(market_data_df, 2, 4)  # 计算MA2和MA4
    print(ind)
    
    # 策略逻辑
    MA2 = ind.iloc[-1]['MA2']  # 取最新的MA2
    MA4 = ind.iloc[-1]['MA4']  # 取最新的MA4
    code = bar['code']  # 合约代码
    trade_price = bar['close']  # 最新收盘价
    trade_amount = 1  # 1手
    trade_time = bar['date'] + ' 00:00:00'  # 由于日线date的格式是2019-01-01，所以要加后面的时间，否则无法计算指标，后面的问题章节有详细说明。
    code_hold_available = acc.hold_available.get(code, 0)  # 合约目前的持仓情况
    if MA2 > MA4:
        if code_hold_available == 0:
            print('买多')
            sendorder(code, trade_price, trade_amount, QA.ORDER_DIRECTION.BUY_OPEN, trade_time)
        elif code_hold_available > 0:
            print('持有')
        else:
            print('平空')
            sendorder(code, trade_price, trade_amount, QA.ORDER_DIRECTION.BUY_CLOSE, trade_time)
    elif MA4 > MA2:
        if code_hold_available == 0:
            print('卖空')
            sendorder(code, trade_price, trade_amount, QA.ORDER_DIRECTION.SELL_OPEN, trade_time)
        elif code_hold_available < 0:
            print('持有')
        else:
            print('平多')
            sendorder(code, trade_price, trade_amount, QA.ORDER_DIRECTION.SELL_CLOSE, trade_time)
    else:
        print('不操作')

# 4. 订阅数据
sub = subscriber(exchange='x1')  # Exchange名为x1，在15672页面能看到
sub.callback=on_data  # 指定回调函数
threading.Thread(target=sub.start).start()  # 开线程执行订阅，防止线程阻塞，后面的发布代码无法执行

# 5. 数据获取并推送数据
# 获取
df = QA.QA_fetch_get_future_day('tdx', 'RBL8', '2019-08-01', '2019-08-30')
# 推送
pub = publisher(exchange='x1')  # 跟订阅的Exchange一致
for idx, item in df.iterrows():
    pub.pub(item.to_json())  # 每行数据换成json，pub出去，上面的on_data就会收到，开始执行策略。

# 6. 查看结果
risk = QA.QA_Risk(acc)
performance = QA.QA_Performance(acc)

acc.history_table  # 交易记录
risk.plot_assets_curve()  # 资产曲线
performance.pnl  # 盈利情况

# 7. 保存结果
risk.save()
acc.save()
```

## 问题

1. 需要先存数据，因为qa_risk会调用QA_fetch_stock_day_adv，这个方法会从数据库读数据，读不到数据后面的指标不能计算（已解决）

1. receive_simpledeal的trade_time，要用%Y-%m-%d %H:%M:%S格式，因为history_min会用到这个格式，格式不对会计算不出history_min，导致后面指标不能计算。（已解决）

1. 在存了数据，trade_time写对之后，performance.pnl等等都能计算出来了，但是81界面的绩效分析就是出不来，不知道为什么（未解决）

1. acc = port.new_account(‘test1’, 100000, QA.MARKET_TYPE.FUTURE_CN, auto_reload=False)，跑第一次的时候会把acc存在数据库，第二次再跑的时候，创建的acc，market_type会变成stock_cn，因为走的else代码块，创建走的if，具体看QAPortfolio的第385行。但是如果auto_reload是true的话就不会有问题，因为会从数据库读取覆盖掉创建时赋值的参数，缺点是交易数据的累计的，跑不同的数据，交易记录会混在一起。（未解决）