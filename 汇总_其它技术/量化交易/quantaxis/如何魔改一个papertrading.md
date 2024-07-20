![](https://gitee.com/hxc8/images5/raw/master/img/202407172346894.jpg)

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

![](images/WEBRESOURCEde3f8949bd69ff090cab5cde43ff5560截图.png)

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

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346303.jpg)

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

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346315.jpg)

不过上图还是没出现下单信号

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346311.jpg)

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

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346960.jpg)

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

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346074.jpg)

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

![](https://gitee.com/hxc8/images5/raw/master/img/202407172347241.jpg)