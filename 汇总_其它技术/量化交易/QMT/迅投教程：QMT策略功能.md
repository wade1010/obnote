批量自动下载行情：

右下角行情，有智能下载、快速下载，批量下载，我们可以建议大家用一个批量下载

 

![](images/WEBRESOURCE332b74143cb6887676bd1e6f633346ef截图.png)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172350306.jpg)

QMT新建

![](https://gitee.com/hxc8/images5/raw/master/img/202407172350585.jpg)

```
#encoding:gbk
'''
本策略事先设定好交易的股票篮子，然后根据指数的CCI指标来判断超买和超卖
当有超买和超卖发生时，交易事先设定好的股票篮子
'''
import pandas as pd
import numpy as np
import talib

def init(ContextInfo):
	#hs300成分股中sh和sz市场各自流通市值最大的前3只股票
	ContextInfo.trade_code_list=['601398.SH','601857.SH','601288.SH','000333.SZ','002415.SZ','000002.SZ']
	ContextInfo.set_universe(ContextInfo.trade_code_list)
	ContextInfo.accID = '6000000058'
	ContextInfo.buy = True
	ContextInfo.sell = False
	
def handlebar(ContextInfo):
	#计算当前主图的cci
	bar = ContextInfo.barpos
	timetag = ContextInfo.get_bar_timetag(bar)
	print(timetag_to_datetime(timetag,'%Y%m%d %H:%M:%S'))
	mkdict = ContextInfo.get_market_data(['high','low','close'],count=int(period)+1)
	highs = np.array(mkdict['high'])
	lows = np.array(mkdict['low'])
	closes = np.array(mkdict['close'])
	cci_list =  talib.CCI(highs,lows,closes,timeperiod=int(period))
	now_cci = cci_list[-1]
	ContextInfo.paint("CCI",now_cci,-1,0,'noaxis')
	
	#交易策略
	if len(cci_list)<2:
		return
	
	#买入条件：指数CCI进入超卖区间时触发买入信号，过滤连续超卖导致的买入信号
	buy_condition = cci_list[-2]<buy_value<=now_cci and ContextInfo.buy
	#卖出条件：指数CCI进入超买区间时触发卖出信号，过滤连续超买导致的卖出信号
	sell_condition = cci_list[-2]>sell_value>=now_cci and ContextInfo.sell
	
	if buy_condition:
		ContextInfo.buy = False
		ContextInfo.sell = True
		#列表中股票分别下单买入10手
		for stockcode in ContextInfo.trade_code_list:
			order_lots(stockcode,10,ContextInfo,ContextInfo.accID)
	elif sell_condition:
		ContextInfo.buy = True
		ContextInfo.sell = False
		#列表中股票分别下单卖出10手
		for stockcode in ContextInfo.trade_code_list:
			order_lots(stockcode,-10,ContextInfo,ContextInfo.accID)
			
	#可买或可卖状态
	ContextInfo.draw_text(bool(buy_condition),float(now_cci),'buy') #绘制买点
	ContextInfo.draw_text(bool(sell_condition),float(now_cci),'sell') #绘制卖点
	ContextInfo.paint('can_buy',ContextInfo.buy,-1,0,'nodraw')
	ContextInfo.paint('can_sell',ContextInfo.sell,-1,0,'nodraw')



```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172350896.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172350998.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172350679.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351400.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351168.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351950.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351371.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351475.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351003.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351476.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351172.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351025.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351603.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351488.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351515.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351701.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351299.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351725.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351833.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172351932.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172352072.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172352854.jpg)

可能需要补全历史数据的  除权数据

![](https://gitee.com/hxc8/images5/raw/master/img/202407172352351.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172352184.jpg)

我最后再提一下多因子库，多因子库，是客户安装的时候就已经包含进来了的，然后它这里是已经给你配置了这些因子，但是它是只有配置，但是没有数据的，所以我们如果安装好客户端之后，想要使用这个因子是需要刷新，刷新扩展数据，第一次的话可以选择这个全量刷新，会直接按照这个时间给你刷新完，那假如你这里没有选择这个收盘更新的话，那比如再过两天，这两天的数据没有，那我选择增量更新，它就会给你更新到最新的日期。

![](https://gitee.com/hxc8/images5/raw/master/img/202407172352908.jpg)