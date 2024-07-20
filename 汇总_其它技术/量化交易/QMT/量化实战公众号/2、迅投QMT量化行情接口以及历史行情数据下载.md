2、迅投QMT量化行情接口以及历史行情数据下载

> 上文介绍QMT一些背景知识，本文则主要介绍QMT行情接口概况和一个历史行情数据下载案例，希望对读者有所启发。


## 行情接口分析

QMT行情有两套不同的处理逻辑： 

•数据查询接口：使用时需要先确保MiniQmt已有所需要的数据，如果不足可以通过补充数据接口补充，再调用数据获取接口获取。适用于少量的实时行情数据和大批量的历史行情数据。•订阅接口：直接设置数据回调，数据到来时会由回调返回。订阅接收到的数据一般会保存下来，同种数据不需要再单独补充。适用于大批量的实时行情数据。

按照类别，主要有以下四类：

•行情数据（K线数据、分笔数据，订阅和主动获取的接口）•财务数据•合约基础信息•基础行情数据板块分类信息等基础信息

## 行情接口概况

首先导入行情库：

```
from xtquant import xtdataprint(dir(xtdata))
```

可以看到行情主要分为以下几个模块：

•实时行情订阅：subscribe* 系列•基本信息和行情查询：get_* 系列•历史数据订阅：download_* 系列•历史数据处理：get_local_data

针对数据存储目录，默认为xtdata.data_dir=../userdata_mini/datadir, 按照官方文档的说明似乎可以任意设置，但实操下来却发现没起到作用。因此，如果默认存储空间有限的话，我们可以将其移动到有较大空间的地方，然后创建一个快捷方式指向原来的地方，避免磁盘空间被耗尽。

```
from xtquant import xtdata
xtdata.data_dir = "D:\\Program Files\\QMT\\userdata_mini\\datadir"
print(dir(xtdata))


```

### 实战：历史行情数据下载

QMT提供的历史行情下载接口有两个：

•单支股票下载：download_history_data(stock_code, period, start_time='', end_time='')•批量股票下载：download_history_data2(stock_list, period, start_time='', end_time='',callback=None)

其中各个参数具体含义如下：

•stock_code：股票名，以code.exchange的形式表示，exchange可从如下品种中选择•上海证券(SH), 如510050.SH•深圳证券(SZ), 如159919.SZ•上海期权(SHO), 如10004268.SHO•深圳期权(SZO), 如90000967.SZO•中国金融期货(CFFEX), 如IC07.CFFEX•郑州商品期货(CZCE), 如SR05.CZCE•大连商品期货(DCE), 如m2212.DCE•上海期货(SHFE), 如wr2209.SHFE•能源中心(INE), 如sc00.INE•香港联交所(HK), 如00700.HK•stock_list, 股票列表，如['510050.SH', '159919.SZ']•period, 数据周期，可选1m、5m、1d、tick, 分别表示1分钟K线、5分钟K线、1天K线、分笔数据•start_time, 数据起始时间，格式YYYYMMDD/YYYYMMDDhhmmss/YYYYMMDDhhmmss.milli，如 "20200427" "20200427093000" "20200427093000.000"•end_time，数据结束时间，格式同start_time

如果运行如下代码，下载深圳市场300ETF期权沪深300ETF购9月4900标的的tick行情，就会在userdata_mini\datadir\SZO\0\90000967目录下生成以日为单位的tick数据：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172348273.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349054.jpg)

上述二进制文件是无法直接读取的，这里通过get_local_data接口进行数据文件的解析，便可解码已经下载的tick行情，包含时间戳、K线、买五卖五快照信息等：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349912.jpg)

注意到这里的Unix时间戳是精确到毫秒的，可以通过datetime转换成字符型：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349742.jpg)

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349941.jpg)

至此，我们已经成功调试历史行情下载接口了，下篇文章则将会重点介绍如何获取关注的批量或全部股票代码并下载对应的历史数据，保存到数据库中供后续分析。

```
import datetime

import pandas as pd
from xtquant import xtdata

xtdata.data_dir = "D:\\Program Files\\QMT\\userdata_mini\\datadir" (后来2023-5-20 15:16:48发现没用)

# xtdata.download_history_data('300300.SZ', period='tick')
data = xtdata.get_local_data(field_list=[], stock_code=['300300.SZ'], period='tick', count=10)

df = pd.DataFrame(data['300300.SZ'])
df['datetime'] = df['time'].apply(lambda x: datetime.datetime.fromtimestamp(x / 1000.0))
print(df)
```