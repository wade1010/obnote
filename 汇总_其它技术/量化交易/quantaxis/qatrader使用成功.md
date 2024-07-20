git clone [https://github.com/yutiansut/QATradeG](https://github.com/yutiansut/QATradeG)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345976.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345801.jpg)

不知道是不是周末，这个好像连不上

cat ./open-trade-gateway/log/open-trade-gateway.log

```
{"time":"2022-08-20T13:43:43.924300400+08:00","level":"warning","pid":8,"tid":"140558342003264","node":"ef304b87777d","msg":"md service read fail","errmsg":"End of file","fun":"OnRead","key":"mdservice"}
{"time":"2022-08-20T13:43:45.357615100+08:00","level":"info","pid":8,"tid":"140558342003264","node":"ef304b87777d","msg":"mdservice ReStartConnect openmd service","fun":"ReStartConnect","key":"mdservice"}
{"time":"2022-08-20T13:43:45.360496300+08:00","level":"info","pid":8,"tid":"140558342003264","node":"ef304b87777d","msg":"md_connection is deleted","fun":"~md_connection()","key":"mdservice"}
{"time":"2022-08-20T13:43:45.366938500+08:00","level":"info","pid":8,"tid":"140558342003264","node":"ef304b87777d","msg":"m_ws_socket next_layer is closed","fun":"~md_connection()","key":"mdservice"}
{"time":"2022-08-20T13:43:55.216908100+08:00","level":"info","pid":1,"tid":"140550376168000","node":"ef304b87777d","msg":"on check server status","fun":"OnCheckServerStatus","key":"gateway"}
```

qatrader --acc 1000000 --pwd 101010 --broker QUANTAXIS --wsuri ws://127.0.0.1:7988

```

QUANTAXIS>> True
QUANTAXIS>> === =============== ===
QUANTAXIS>> CURRENT: 2022-08-20 21:35:55.953312
QUANTAXIS>> LAST UPDATE: 2022-08-20 21:35:50.955244
send_ping
QUANTAXIS>> updateAccount
QUANTAXIS>> GET PING-PONG : b'ping-1000000'
QUANTAXIS>> === CONNECTION INFO ===
QUANTAXIS>> True
QUANTAXIS>> === =============== ===
QUANTAXIS>> CURRENT: 2022-08-20 21:36:00.954402
QUANTAXIS>> LAST UPDATE: 2022-08-20 21:35:55.964269
send_ping
QUANTAXIS>> updateAccount
QUANTAXIS>> GET PING-PONG : b'ping-1000000'
QUANTAXIS>> === CONNECTION INFO ===
QUANTAXIS>> True
QUANTAXIS>> === =============== ===
QUANTAXIS>> CURRENT: 2022-08-20 21:36:05.955615
QUANTAXIS>> LAST UPDATE: 2022-08-20 21:36:00.962655
send_ping
```

再看下log

cat ./open-trade-gateway/log/open-trade-gateway.log

```
{"time":"2022-08-20T13:56:39.812217000+08:00","level":"info","pid":1,"tid":"140550376168000","node":"ef304b87777d","msg":"trade connection accept success","connId":1,"fd":10,"port":62202,"agent":"","analysis":"","fun":"OnOpenConnection","ip":"172.19.3.1","key":"gateway"}
{"time":"2022-08-20T13:56:39.816319200+08:00","level":"info","pid":1,"tid":"140550376168000","node":"ef304b87777d","msg":"generate user key","connId":1,"port":62202,"agent":"","analysis":"","fun":"ProcessLogInMessage","ip":"172.19.3.1","key":"gateway","user_key":"sim_QUANTAXIS_1000000"}
{"time":"2022-08-20T13:56:39.899685300+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"trade sim init!","fun":"main","key":"sim_QUANTAXIS_1000000"}
{"time":"2022-08-20T13:56:39.902509700+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"try to load config","fun":"LoadConfig","trading_day":"20220822"}
{"time":"2022-08-20T13:56:39.910479800+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"tradersim ProcessReqLogIn","client_port":62202,"client_system_info_length":0,"connId":1,"bid":"QUANTAXIS","broker_id":"","client_app_id":"","client_ip":"172.19.3.1","front":"","fun":"ProcessReqLogIn","key":"sim_QUANTAXIS_1000000","user_name":"1000000"}
{"time":"2022-08-20T13:56:39.911627500+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"g_config user_file_path is not empty","connId":1,"bid":"QUANTAXIS","fileName":"/var/local/lib/open-trade-gateway/QUANTAXIS","fun":"ProcessReqLogIn","key":"sim_QUANTAXIS_1000000","user_name":"1000000"}
{"time":"2022-08-20T13:56:39.913129300+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"load user data file","bid":"QUANTAXIS","fileName":"/var/local/lib/open-trade-gateway/QUANTAXIS/1000000","fun":"LoadUserDataFile","key":"sim_QUANTAXIS_1000000","user_name":"1000000"}
{"time":"2022-08-20T13:56:39.915294200+08:00","level":"warning","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"load user data file failed!","bid":"QUANTAXIS","fileName":"/var/local/lib/open-trade-gateway/QUANTAXIS/1000000","fun":"LoadUserDataFile","key":"sim_QUANTAXIS_1000000","user_name":"1000000"}
{"time":"2022-08-20T13:56:39.916797700+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"diffrent trading day!","bid":"QUANTAXIS","fun":"LoadUserDataFile","key":"sim_QUANTAXIS_1000000","old_trading_day":"","trading_day":"20220822","user_name":"1000000"}
{"time":"2022-08-20T13:56:39.918401300+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"trade sim login success","loginresult":true,"connId":1,"loginstatus":0,"bid":"QUANTAXIS","fun":"ProcessReqLogIn","key":"sim_QUANTAXIS_1000000","user_name":"1000000"}
{"time":"2022-08-20T13:56:39.919882300+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"sim init new balance","bid":"QUANTAXIS","fun":"OnIni","key":"sim_QUANTAXIS_1000000","user_name":"1000000"}
{"time":"2022-08-20T13:56:39.925211600+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"sim OnInit Finish","bid":"QUANTAXIS","fun":"OnIni","key":"sim_QUANTAXIS_1000000","user_name":"1000000"}
{"time":"2022-08-20T13:56:39.929751000+08:00","level":"info","pid":11,"tid":"140606166952320","node":"ef304b87777d","msg":"load condition order success","connId":1,"bid":"QUANTAXIS","fun":"ProcessReqLogIn","key":"sim_QUANTAXIS_1000000","user_name":"1000000"}
```

如果要替换**qa-service-pro的**docker-compose.yaml里面的open-trade-gateway

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345918.jpg)

curl -X POST "[http://127.0.0.1:8011?action=new_handler&market_type=future_cn&code=ag2211](http://127.0.0.1:8011?action=new_handler&market_type=future_cn&code=csl8)"

{"result": "success"}**%**

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345994.jpg)

docker exec -it qa-service-pro-qamarketcollector-1 /bin/sh

==================================交易时间测试开始=========================================

QARC_CTP --code ag2211     这个是不打印动态信息的

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345269.jpg)

QARC_Resample --code ag2211 --freq 1min     交易时间是有数据的

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345860.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345074.jpg)

==================================交易时间测试结束=========================================

可能是因为周末没有行情，所以命令开启后也没数据

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345021.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345045.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345458.jpg)

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


# s = macd(code='ZNL9', frequence='1min')
# s.debug()
# s.run_backtest()

s2 = macd(code='CSL8', frequence='1min', start='2022-01-01', end='2022-05-21', )

s2.run_sim()

```

执行如下图，估计是由于周末没有行情，所以卡在了等待消费那里

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345609.jpg)

群主当时的天勤终端

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345572.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172345819.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346546.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346716.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172346387.jpg)

frp内网穿透   群主的3000端口就是用frp内网传统做的  

==================================交易时间跑的代码=========================================

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


# s = macd(code='ZNL9', frequence='1min')
# s.debug()
# s.run_backtest()

s2 = macd(code='ag2211', frequence='1min', start='2022-01-01', end='2022-05-21', )

s2.run_sim()

```

输出结果

```
/usr/local/anaconda3/envs/quantaxisc/bin/python /Applications/PyCharm.app/Contents/plugins/python/helpers/pydev/pydevd.py --multiproc --qt-support=auto --client 127.0.0.1 --port 62061 --file /Users/bob/workspace/pythonworkspace/quantaxisc/demo/strategy_demo2.py
pydev debugger: process 92673 is connecting

Connected to pydev debugger (build 201.6668.115)
QUANTAXIS>> Selecting the Best Server IP of TDX
USING DEFAULT STOCK IP
USING DEFAULT FUTURE IP
QUANTAXIS>> === The BEST SERVER ===
 stock_ip 123.125.108.24 future_ip 119.97.185.5
account QA_STRATEGY start sim
QUANTAXIS>> 2022-08-22 11:14:38.075962 UPDATE ACCOUNT
QUANTAXIS>> ============ 28a75ae5-0142-4e1a-a29c-4c9cc7aa02d1 SEND ORDER ==================
QUANTAXIS>> directionBUY offset OPEN price4265.0 volume20
account order_check
{'volume_long': 0, 'volume_short': 0, 'volume_long_frozen': 0, 'volume_short_frozen': 0}
{'volume_long': 0, 'volume_short': 0}
order check success
下单成功 28a75ae5-0142-4e1a-a29c-4c9cc7aa02d1
{'account_cookie': 'QA_STRATEGY', 'user_id': 'QA_STRATEGY', 'instrument_id': 'ag2211', 'towards': 2, 'exchange_id': 'SHFE', 'order_time': '2022-08-22 11:14:38_099206', 'volume': 20, 'price': 4265.0, 'order_id': '28a75ae5-0142-4e1a-a29c-4c9cc7aa02d1', 'seqno': 1, 'direction': 'BUY', 'offset': 'OPEN', 'volume_orign': 20, 'price_type': 'LIMIT', 'limit_price': 4265.0, 'time_condition': 'GFD', 'volume_condition': 'ANY', 'insert_date_time': 1661138078096081920, 'exchange_order_id': 'ce2f8327-9dba-417f-9271-3aec1be8dc7e', 'status': 'ALIVE', 'volume_left': 20, 'last_msg': '已报'}
全部成交 28a75ae5-0142-4e1a-a29c-4c9cc7aa02d1
update trade
------this is on deal message ------
{'account_cookie': 'QA_STRATEGY', 'user_id': 'QA_STRATEGY', 'instrument_id': 'ag2211', 'towards': 2, 'exchange_id': 'SHFE', 'order_time': '2022-08-22 11:14:38_099206', 'volume': 20, 'price': 4265.0, 'order_id': '28a75ae5-0142-4e1a-a29c-4c9cc7aa02d1', 'seqno': 1, 'direction': 'BUY', 'offset': 'OPEN', 'volume_orign': 20, 'price_type': 'LIMIT', 'limit_price': 4265.0, 'time_condition': 'GFD', 'volume_condition': 'ANY', 'insert_date_time': 1661138078096081920, 'exchange_order_id': 'ce2f8327-9dba-417f-9271-3aec1be8dc7e', 'status': 'FINISHED', 'volume_left': 0, 'last_msg': '全部成交', 'topic': 'send_order'}
QUANTAXIS>> 2022-08-22 11:14:38.138398 UPDATE ACCOUNT
QUANTAXIS>> 2022-08-22 11:14:38.165483 UPDATE ACCOUNT
QUANTAXIS>> 2022-08-22 11:14:38.198355 UPDATE ACCOUNT
QUANTAXIS>> 2022-08-22 11:14:38.227132 UPDATE ACCOUNT
QUANTAXIS>> 2022-08-22 11:14:38.256944 UPDATE ACCOUNT
QUANTAXIS>> 2022-08-22 11:14:38.295348 UPDATE ACCOUNT
QUANTAXIS>> 2022-08-22 11:14:38.348829 UPDATE ACCOUNT
QUANTAXIS>> 2022-08-22 11:14:38.391146 UPDATE ACCOUNT

```