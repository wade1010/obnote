```
from QAPUBSUB.producer import publisher,publisher_routing
from QAPUBSUB.consumer import subscriber,subscriber_routing
import threading
pub = publisher(exchange='x1')
sub = subscriber(exchange='x1')
threading.Thread(target=sub.start).start()
for i in range(100):
    pub.pub(str(i))
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348788.jpg)

```
#修改回调函数
from QAPUBSUB.producer import publisher,publisher_routing
from QAPUBSUB.consumer import subscriber,subscriber_routing
import threading
pub = publisher(exchange='x1')
sub = subscriber(exchange='x1')

market_data = []
def on_data(a,b,c,data):
    market_data.append(data)
    print(market_data)

sub.callback = on_data
    
threading.Thread(target=sub.start).start()
pub.pub('2022-08-03,000001,22.22')
pub.pub('2022-08-04,000001,22.22')
pub.pub('2022-08-05,000001,22.22')
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348692.jpg)

创建回测

```
user = QA.QA_User(username='admin',password='admin')
portfolio = user.new_portfolio('x1')
acc =  portfolio.new_account('test1',market_type=QA.MARKET_TYPE.FUTURE_CN,init_cash=100000)
acc.save()
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348780.jpg)

[http://localhost:81/#/common-backtest](http://localhost:81/#/common-backtest)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348982.jpg)

```
import threading
import QUANTAXIS as QA
import json
import pandas as pd
from QAPUBSUB.producer import publisher,publisher_routing
from QAPUBSUB.consumer import subscriber,subscriber_routing
user = QA.QA_User(username='admin',password='admin')
portfolio = user.new_portfolio('x2')
acc =  portfolio.new_account('test1',market_type=QA.MARKET_TYPE.FUTURE_CN,init_cash=100000)
acc.save()
pub = publisher(exchange='x1')
sub = subscriber(exchange='x1')
market_data = []
def on_data(a,b,c,data):
    bar = json.loads(json.loads(data))
    market_data.append(bar)
    #print('receive data')
    x1  = pd.DataFrame(market_data)
    ind = QA.QA_indicator_MA(x1,2,4)
    #print(ind)
    try:
        if ind.iloc[-1]['MA2']>ind.iloc[-1]['MA4']:
            #print('signal ==> buy')
            if acc.hold_available.get('NRL8',0) == 0:
                acc.receive_simpledeal(code=bar['code'],
                                      trade_price=bar['close'],
                                      trade_amount=1,
                                       trade_towards=QA.ORDER_DIRECTION.BUY_OPEN,
                                       trade_time=bar['date'])
            elif acc.hold_available.get('NRL8',0) > 0:
                #print('already buyopen')
                pass
            elif acc.hold_available.get('NRL8',0) < 0:
                #平仓
                acc.receive_simpledeal(code=bar['code'],
                                      trade_price=bar['close'],
                                      trade_amount=1,
                                       trade_towards=QA.ORDER_DIRECTION.BUY_CLOSE,
                                       trade_time=bar['date'])
        elif ind.iloc[-1]['MA2'] < ind.iloc[-1]['MA4']:
            #print('signal ==> sell')
            if acc.hold_available.get('NRL8',0) == 0:
                acc.receive_simpledeal(code=bar['code'],
                                      trade_price=bar['close'],
                                      trade_amount=1,
                                       trade_towards=QA.ORDER_DIRECTION.SELL_OPEN,
                                       trade_time=bar['date'])
            elif acc.hold_available.get('NRL8',0) > 0:
                #平仓
                acc.receive_simpledeal(code=bar['code'],
                                      trade_price=bar['close'],
                                      trade_amount=1,
                                       trade_towards=QA.ORDER_DIRECTION.SELL_CLOSE,
                                       trade_time=bar['date'])
            elif acc.hold_available.get('NRL8',0) < 0:
                pass
                #print('already sellopen')
    except:
        pass

sub.callback = on_data

threading.Thread(target=sub.start).start()

fd = QA.QA_fetch_get_future_day('tdx','NRL8','2019-08-01','2019-08-30')
    
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348277.jpg)