![](https://gitee.com/hxc8/images5/raw/master/img/202407172335355.jpg)

![](images/WEBRESOURCEd1d174b306f8df32a09552d5b24b5e9e截图.png)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335246.jpg)

接上图输出，下图是上图的CTP行情

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335077.jpg)

有了行情之后就可以用collector来收集数据了。如下图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335717.jpg)

直接发POST请求就行

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335317.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172335413.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172336005.jpg)

当没有行情的时候可以用QARandom来做一个仿真行情（符合CTP标准格式）

![](https://gitee.com/hxc8/images5/raw/master/img/202407172336840.jpg)

(PS视频看到后面，其实上面QARC_random使用有点问题，如下图才对)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172336449.jpg)

（PS视频看到更后面，--date 20191008）

有了假行情，其实对于上上张图，你就是有了行情网关和QAStrategy,也有QIFI协议，但是，像QATrade这种，无论模拟还是试盘都依赖于真实行情，或者说依赖于交易所或者说撮合商。

如果你想在本地直接撮合它，就是，比如说我给你一个假行情，怎么样把它搞得像真的一样，那么这个时候就是关于这个papertrading的事了，papertrading是这样的，它有两种模式去实现这种假行情底下的账户的这么一个，好像是撮合的这么一个东西。

一种模式是QAPaperTradingServer,这个时候当你收到，就是账户发过来的，按照QIFI协议发出来的订单以后，你就去撮合它，帮它假撮合。

另外一种模式就是利用context劫持下单，劫持它的办法很简单的办法就是用context上下文来去劫持，直接劫持它的下单操作的句柄，它的下单操作，就不会去真实的pub一个message出去，而是直接被你撮合成交，更新过来。

（两种模式在上图左下角）

上面Random发出来的是RB2001标准的TCP格式的tick信息，我们在组合出来一个分钟线信息

![](https://gitee.com/hxc8/images5/raw/master/img/202407172336812.jpg)

上图就是基于假行情，重新采样后再发。

发出来之后就是一个realtime分钟线。

在用resample采样为1分钟的行情

![](images/WEBRESOURCE1b298d5c8ddfe3a54078e858f8c6b1fa截图.png)

那么这个时候就有假行情了，但是我们没有一套撮合系统

这个时候我们就直接劫持，劫持的写法如下 （未开源，先截图，后续也许可以模仿写）

contextPMS.py  内容如下面两张图

![](https://gitee.com/hxc8/images5/raw/master/img/202407172336089.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172336313.jpg)

QASPMS.py  包含下面两张图

![](images/WEBRESOURCE4c531fa908c672a5776fd42d145da0c7截图.png)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337220.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337584.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337943.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337507.jpg)

上图后面部分就没显示出来。。。

QASPMS.py

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337998.jpg)

contextPMS

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337865.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337966.jpg)

上图的 data_exchange应该是改成 realtim_1min_RB2001  （视频后面改的）

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337425.jpg)

上图就是订阅的1分钟的数据，这个时候就会不停的callback

  - -执行一会就停了，有bug

后来不用这个原有代码了，群主自己又手动写了

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337953.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337071.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337108.jpg)

如上图，因为这个tick是实时的，虽然看到很多是1分钟的，但是秒数在变。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337157.jpg)

上图是修改后的方法，并且进行了分钟处理

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337477.jpg)

再添加内容

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337105.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337903.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337771.jpg)

如上图，因为没有补足历史数据，所以3周期和5周期历史数据还是NaN

跑一段时间后。Ma3开始有值

![](https://gitee.com/hxc8/images5/raw/master/img/202407172337708.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338848.jpg)

上图开始有MA5了

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338659.jpg)

上图开始下单了，至此数据流可以变成订单流

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338369.jpg)

上图下单信号又消失了

上方代码其实你还是不能下单，也就是说没办法将数据流变成订单流，因为没有账户，也没法下单。

下面继续完善

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338031.jpg)

基于QIFI协议的QATrader

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338097.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338108.jpg)

下图就是订单劫持

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338935.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338143.jpg)

docker exec -it docker-qamarketcollector-1 /bin/sh

QARC_Random --code RB2001 --date 20191008 --price 3800 --interval 1 //为了快速行情，interval也可以设置为0

QARC_CTP --code RB2001

QARC_Resample --code RB2001 --freq 1min

```
import datetime
import json
import threading
import time
import uuid
import pandas as pd
from QUANTAXIS.QAEngine.QAThreadEngine import QA_Thread
from QAPUBSUB.consumer import subscriber


class QASIMServer(QA_Thread):
    def __init__(self, code, freq):
        super().__init__()
        self.code = code
        self.freq = freq
        self.sub = subscriber(exchange='realtime_{}_{}'.format(self.freq, self.code))
        self.sub.callback = self.on_bar
        threading.Thread(target=self.sub.start).start()

    def on_bar(self, a, b, c, bar):
        bar = json.loads(bar)
        print(bar)
        pass
 
    def run(self):
        while True:
            time.sleep(1)


if __name__ == '__main__':
    QASIMServer('RB2001', '1min').start()

```

运行

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338990.jpg)

可以看出是1秒一跳分钟线bar，那么你要去区分的是，如何这个分钟进入下一个分钟

再修改代码

```
import datetime
import json
import threading
import time
import uuid
import pandas as pd
from QUANTAXIS.QAEngine.QAThreadEngine import QA_Thread
from QAPUBSUB.consumer import subscriber


class QASIMServer(QA_Thread):
    def __init__(self, code, freq):
        super().__init__()
        self.code = code
        self.freq = freq
        self.sub = subscriber(exchange='realtime_{}_{}'.format(self.freq, self.code))
        self.sub.callback = self.receive_bar

        self.market_data = []
        self.dt = None

        threading.Thread(target=self.sub.start).start()

    def receive_bar(self, a, b, c, bar):
        latest_data = json.loads(str(bar, encoding='utf-  8'))
        print(latest_data['datetime'][0:16])

        if self.dt != latest_data['datetime'][0:16] or len(self.market_data) < 1:
            self.dt = latest_data['datetime'][0:16]
            print('append latest_data to market_data')
            self.market_data.append(latest_data)
        else:
            self.market_data[-1] = latest_data

        self.on_bar(self.market_data)

    def on_bar(self, bar):
        print(pd.DataFrame(bar))

    def run(self):
        while True:
            time.sleep(1)


if __name__ == '__main__':
    QASIMServer('RB2001', '1min').start()

```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338321.jpg)

上图发现，执行一段时间后，历史数据就会变成多个

再改代码 主要是跟指标相关

```
import datetime
import json
import threading
import time
import uuid
import pandas as pd
from QUANTAXIS.QAEngine.QAThreadEngine import QA_Thread
from QAPUBSUB.consumer import subscriber
import QUANTAXIS as QA


class QASIMServer(QA_Thread):
    def __init__(self, code, freq):
        super().__init__()
        self.code = code
        self.freq = freq
        self.sub = subscriber(exchange='realtime_{}_{}'.format(self.freq, self.code))
        self.sub.callback = self.receive_bar

        self.market_data = []
        self.dt = None

        threading.Thread(target=self.sub.start).start()

    def receive_bar(self, a, b, c, bar):
        latest_data = json.loads(str(bar, encoding='utf-  8'))
        print(latest_data['datetime'][0:16])

        if self.dt != latest_data['datetime'][0:16] or len(self.market_data) < 1:
            self.dt = latest_data['datetime'][0:16]
            print('append latest_data to market_data')
            self.market_data.append(latest_data)
        else:
            self.market_data[-1] = latest_data

        self.on_bar(self.market_data)

    def on_bar(self, bar):
        data = pd.DataFrame(bar).set_index(['datetime', 'code'])

        ind = QA.QA_indicator_MA(data, 3, 5)
        print(ind)

        if QA.CROSS(ind.MA3, ind.MA5).iloc[-1] == 1:
            # 上穿
            print('下单')
            # self.send_order(self.code, bar[-1]['close'])

    def run(self):
        while True:
            time.sleep(1)


if __name__ == '__main__':
    QASIMServer('RB2001', '1min').start()

```

运行一段时间后，才会有MA3和MA5

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338586.jpg)

不过上图还是没出现下单信号

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338780.jpg)

上图出现下单信号

再改，主要是账户和下单

```
import datetime
import json
import threading
import time
import uuid
import pandas as pd
from QUANTAXIS.QAEngine.QAThreadEngine import QA_Thread
from QAPUBSUB.consumer import subscriber
import QUANTAXIS as QA


class QASIMServer(QA_Thread):
    def __init__(self, code, freq):
        super().__init__()
        self.code = code
        self.freq = freq
        self.sub = subscriber(exchange='realtime_{}_{}'.format(self.freq, self.code))
        self.sub.callback = self.receive_bar

        self.market_data = []
        self.dt = None
        self.datetime = None

        threading.Thread(target=self.sub.start).start()

        # 在真实环境，订单是发送到order router里面的
        user = QA.QA_User(username='admin', password='admin')
        portfolio = user.new_portfolio('test')
        self.account = portfolio.new_account('tes2t4', init_cash=100000,
                                             market_type=QA.MARKET_TYPE.FUTURE_CN)
        self.account_cookie = '100000'

    def receive_bar(self, a, b, c, bar):
        latest_data = json.loads(str(bar, encoding='utf-  8'))
        self.datetime = latest_data['datetime']
        if self.dt != latest_data['datetime'][0:16] or len(self.market_data) < 1:
            self.dt = latest_data['datetime'][0:16]
            print('append latest_data to market_data')
            self.market_data.append(latest_data)
        else:
            self.market_data[-1] = latest_data

        self.on_bar(self.market_data)

    def on_bar(self, bar):
        data = pd.DataFrame(bar).set_index(['datetime', 'code'])

        ind = QA.QA_indicator_MA(data, 3, 5)
        print(ind)

        # if QA.CROSS(ind.MA3, ind.MA5).iloc[-1] == 1://为了快速形成下单信号，不用CROSS
        if (ind.MA3 - ind.MA5).iloc[-1] > 0:
            # 3上穿5
            if self.account.hold_available.get(self.code, 0) == 0:
                self.send_order(direction='BUY', offset='OPEN', price=bar[-1]['close'], volume=10)
        # elif QA.CROSS(ind.MA5, ind.MA3).iloc[-1] == 1:
        else:
            # 5上穿3
            if self.account.hold_available.get(self.code, 0) > 0:
                print('平仓')
            self.send_order(direction='SELL', offset='OPEN', price=bar[-1]['close'], volume=10)

    def send_order(self, direction='BUY', offset='CLOSE', price=3925, volume=10,
                   order_id=QA.QA_util_random_with_topic('QASIM')):
        print('下单 {} {} {} {}'.format(direction, offset, price, offset))
        towards = eval('QA.ORDER_DIRECTION.{}_{}'.format(direction, offset))
        self.account.receive_simpledeal(code=self.code, trade_price=price, trade_amount=volume,
                                        trade_towards=towards, order_id=order_id,
                                        trade_time=self.datetime)

    def run(self):
        while True:
            time.sleep(1)


if __name__ == '__main__':
    QASIMServer('RB2001', '1min').start()

```

 - -尴尬 运行后 发现一会就亏光了。。。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338835.jpg)

还是改成CROSS吧

```
import datetime
import json
import threading
import time
import uuid
import pandas as pd
from QUANTAXIS.QAEngine.QAThreadEngine import QA_Thread
from QAPUBSUB.consumer import subscriber
import QUANTAXIS as QA


class QASIMServer(QA_Thread):
    def __init__(self, code, freq):
        super().__init__()
        self.code = code
        self.freq = freq
        self.sub = subscriber(exchange='realtime_{}_{}'.format(self.freq, self.code))
        self.sub.callback = self.receive_bar

        self.market_data = []
        self.dt = None
        self.datetimeText = None

        threading.Thread(target=self.sub.start).start()

        # 在真实环境，订单是发送到order router里面的
        self.account = QA.QA_User(username='admin', password='admin').new_portfolio('test') \
            .new_account('test', init_cash=100000, market_type=QA.MARKET_TYPE.FUTURE_CN)
        print(self.account.user_cookie)
        print(self.account.portfolio_cookie)
        print(self.account.account_cookie)
        self.account.save()

    def receive_bar(self, a, b, c, bar):
        latest_data = json.loads(str(bar, encoding='utf-8'))
        # print(latest_data)
        self.datetimeText = latest_data['datetime']
        if self.dt != latest_data['datetime'][0:16] or len(self.market_data) < 1:
            self.dt = latest_data['datetime'][0:16]
            print('append latest_data to market_data')
            self.market_data.append(latest_data)
        else:
            self.market_data[-1] = latest_data

        self.on_bar(self.market_data)

    def on_bar(self, bar):
        data = pd.DataFrame(bar).set_index(['datetime', 'code'])

        # print(data)
        ind = QA.QA_indicator_MA(data, 3, 5)

        if QA.CROSS(ind.MA3, ind.MA5).iloc[-1] == 1:  # 为了快速形成下单信号，不用CROSS
            # if (ind.MA3 - ind.MA5).iloc[-1] > 0:
            # 3上穿5
            if self.account.hold_available.get(self.code, 0) == 0:
                self.send_order(direction='BUY', offset='OPEN', price=bar[-1]['close'], volume=10)
        elif QA.CROSS(ind.MA5, ind.MA3).iloc[-1] == 1:
            # else:
            # 5上穿3
            if self.account.hold_available.get(self.code, 0) > 0:
                print('平仓')
                self.send_order(direction='SELL', offset='OPEN', price=bar[-1]['close'], volume=10)

    def send_order(self, direction='BUY', offset='CLOSE', price=3925, volume=10,
                   order_id=QA.QA_util_random_with_topic('QASIM')):
        print('下单 {} {} {} {}'.format(direction, offset, price, offset))
        towards = eval('QA.ORDER_DIRECTION.{}_{}'.format(direction, offset))
        self.account.receive_simpledeal(code=self.code, trade_price=price, trade_amount=volume,
                                        trade_towards=towards, order_id=order_id,
                                        trade_time=self.datetimeText)
        self.account.save()

    def run(self):
        while True:
            time.sleep(1)


if __name__ == '__main__':
    QASIMServer('RB2001', '1min').start()

```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338781.jpg)

但是 

QARC_Resample --code RB2001 --freq 1min  最后会报错

```
{'open': 3844.1099841862633, 'high': 3944.766770683541, 'low': 3838.5686870904287, 'close': 3925.899061648513, 'code': 'RB2001', 'datetime': '2019-10-08 21:29:53.500000', 'volume': 282528}
{'open': 3844.1099841862633, 'high': 3944.766770683541, 'low': 3838.5686870904287, 'close': 3926.787976974022, 'code': 'RB2001', 'datetime': '2019-10-08 21:29:54.000000', 'volume': 283607}
{'open': 3844.1099841862633, 'high': 3944.766770683541, 'low': 3838.5686870904287, 'close': 3924.776518951941, 'code': 'RB2001', 'datetime': '2019-10-08 21:29:54.500000', 'volume': 283870}
{'open': 3844.1099841862633, 'high': 3944.766770683541, 'low': 3838.5686870904287, 'close': 3925.8894831816797, 'code': 'RB2001', 'datetime': '2019-10-08 21:29:55.000000', 'volume': 288536}
QUANTAXIS>> _AsyncBaseTransport._consume() failed, aborting connection: error=ConnectionResetError(104, 'Connection reset by peer'); sock=<socket.socket fd=15, family=AddressFamily.AF_INET, type=SocketKind.SOCK_STREAM, proto=6, laddr=('172.19.3.8', 42848)>; Caller's stack:
Traceback (most recent call last):
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/utils/io_services_utils.py", line 1037, in _on_socket_readable
    self._consume()
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/utils/io_services_utils.py", line 786, in _consume
    data = self._sigint_safe_recv(self._sock, self._MAX_RECV_BYTES)
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/utils/io_services_utils.py", line 74, in retry_sigint_wrap
    return func(*args, **kwargs)
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/utils/io_services_utils.py", line 840, in _sigint_safe_recv
    return sock.recv(max_bytes)
ConnectionResetError: [Errno 104] Connection reset by peer
Traceback (most recent call last):
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/utils/io_services_utils.py", line 1037, in _on_socket_readable
    self._consume()
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/utils/io_services_utils.py", line 786, in _consume
    data = self._sigint_safe_recv(self._sock, self._MAX_RECV_BYTES)
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/utils/io_services_utils.py", line 74, in retry_sigint_wrap
    return func(*args, **kwargs)
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/utils/io_services_utils.py", line 840, in _sigint_safe_recv
    return sock.recv(max_bytes)
ConnectionResetError: [Errno 104] Connection reset by peer
QUANTAXIS>> connection_lost: StreamLostError: ("Stream connection lost: ConnectionResetError(104, 'Connection reset by peer')",)
QUANTAXIS>> Unexpected connection close detected: StreamLostError: ("Stream connection lost: ConnectionResetError(104, 'Connection reset by peer')",)
Stream connection lost: ConnectionResetError(104, 'Connection reset by peer')
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Channel is closed.
Fatal Python error: Cannot recover from stack overflow.

Current thread 0x00007fb3837fe700 (most recent call first):
  File "/usr/local/lib/python3.7/uuid.py", line 153 in __init__
  File "/usr/local/lib/python3.7/uuid.py", line 759 in uuid4
  File "/usr/local/lib/python3.7/site-packages/pika/channel.py", line 342 in _generate_consumer_tag
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/blocking_connection.py", line 1694 in _basic_consume_impl
  File "/usr/local/lib/python3.7/site-packages/pika/adapters/blocking_connection.py", line 1655 in basic_consume
  File "/usr/local/lib/python3.7/site-packages/QAPUBSUB/consumer.py", line 41 in subscribe
  File "/usr/local/lib/python3.7/site-packages/QAPUBSUB/consumer.py", line 46 in start
  File "/usr/local/lib/python3.7/site-packages/QAPUBSUB/consumer.py", line 49 in start
  File "/usr/local/lib/python3.7/site-packages/QAPUBSUB/consumer.py", line 49 in start
  ...

Thread 0x00007fb3999c3700 (most recent call first):
  File "/usr/local/lib/python3.7/site-packages/pymongo/periodic_executor.py", line 131 in _run
  File "/usr/local/lib/python3.7/threading.py", line 870 in run
  File "/usr/local/lib/python3.7/threading.py", line 926 in _bootstrap_inner
  File "/usr/local/lib/python3.7/threading.py", line 890 in _bootstrap

Thread 0x00007fb39a7f3700 (most recent call first):
  File "/usr/local/lib/python3.7/site-packages/pymongo/periodic_executor.py", line 131 in _run
  File "/usr/local/lib/python3.7/threading.py", line 870 in run
  File "/usr/local/lib/python3.7/threading.py", line 926 in _bootstrap_inner
  File "/usr/local/lib/python3.7/threading.py", line 890 in _bootstrap

Thread 0x00007fb39aff4700 (most recent call first):
  File "/usr/local/lib/python3.7/site-packages/pymongo/periodic_executor.py", line 131 in _run
  File "/usr/local/lib/python3.7/threading.py", line 870 in run
  File "/usr/local/lib/python3.7/threading.py", line 926 in _bootstrap_inner
  File "/usr/local/lib/python3.7/threading.py", line 890 in _bootstrap

Thread 0x00007fb3b8099700 (most recent call first):
  File "/usr/local/lib/python3.7/threading.py", line 1308 in _shutdown
Aborted
```

而且 组合里面也找不到account

![](https://gitee.com/hxc8/images5/raw/master/img/202407172338150.jpg)