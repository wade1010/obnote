4、迅投QMT历史行情转存Clickhouse数据库

> 上文介绍如何获取批量股票代码并缓存对应的tick、分钟、日级别的历史数据，本节则介绍如何将读取本地的缓存数据并写入到clickhouse数据库中。历史行情数据从格式上看分为tick数据和K线数据两大类，针对这两类的数据我们分别处理。


### tick数据预处理

首先读取本地缓存数据，这里以南航转债(110075.SH)为例，需要注意的是tick数据包含了集合竞价时段，成交量/额是按日累计的，因此需要做一定的转换。

PS:

数据使用下面代码获取

下载110075 tick数据

```
import datetime

import pandas as pd
from xtquant import xtdata

xtdata.data_dir = "D:\\Program Files\\QMT\\userdata_mini\\datadir"

xtdata.download_history_data('110075.SH', period='tick')
data = xtdata.get_local_data(field_list=[], stock_code=['110075.SH'], period='tick', count=10)

df = pd.DataFrame(data['110075.SH'])
df['datetime'] = df['time'].apply(lambda x: datetime.datetime.fromtimestamp(x / 1000.0))
print(df)
```

下载行业

```
from xtquant import xtdata

xtdata.data_dir = "D:\\Program Files\\QMT\\userdata_mini\\datadir"

xtdata.download_sector_data()
```

```python
from xtquant import xtdata
import pandas as pd
import datetime

def get_local_tick_data(code='110075.SH', start_time='19700101'):
    # 获取本地数据
    df = xtdata.get_local_data(stock_code=[code], period='tick', field_list=['time', 'open', 'lastPrice', 'high', 'low', 'lastClose', 'volume', 'amount', 'askPrice', 'bidPrice', 'askVol', 'bidVol'], start_time=start_time, end_time=start_time)

    # 转成DataFRame
    df = pd.DataFrame(df[code])
    if len(df) < 1:
        return df

    # 日期处理
    df['trade_time'] = df['time'].apply(lambda x: datetime.datetime.fromtimestamp(x / 1000.0)) # , cn_tz
    df['trade_day'] = df['trade_time'].apply(lambda x: x.date())
    df['trade_minute'] = df['trade_time'].apply(lambda x: x.hour * 60 + x.minute)
    df['trade_second'] = df['trade_time'].apply(lambda x: x.hour * 3600 + x.minute * 60 + x.second)
    df = df[df.trade_second <= 54001] # 排除盘后交易
    df = df[df.trade_second >= 33840] # 保留最后一分钟的集合竞价数据
    df = df.reset_index(drop=True)

    # 重新计算成交量、成交额
    df['volume_deal'] = df.groupby(['trade_day'])['volume'].diff(periods=1).fillna(0)
    df['amount_deal'] = df.groupby(['trade_day'])['amount'].diff(periods=1).fillna(0)

    # 重新选择列
    df['code'] = '110075.SH'
    df['close'] = df['lastPrice'] # 收盘
    df['last'] = df['lastClose'] # 昨收
    df = df[['code', 'trade_time', 'trade_day', 'trade_minute', 'open', 'close', 'high', 'low', 'last', 'volume', 'amount', 'volume_deal', 'amount_deal', 'askPrice', 'bidPrice', 'askVol', 'bidVol']]

    return df

df = get_local_tick_data(code='110075.SH', start_time='20220630')
print(df.iloc[-1])
```

最终，我们得到诸如下图的tick存储数据:

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349902.jpg)

### K线数据预处理

读取本地缓存数据，这里以行业板块指数为例，首先获取行业指数，然后查询详情，获取元数据:

```python
def get_sector_list():
    '''
    获取沪深指数、行业指数
    '''
    sector_1 = xtdata.get_stock_list_in_sector('证监会行业板块指数')
    sector_1 = [(i, xtdata.get_instrument_detail(i)['InstrumentName'], '证监会一级行业') for i in sector_1]

    sector_2 = xtdata.get_stock_list_in_sector('板块指数')
    sector_2 = [(i, xtdata.get_instrument_detail(i)['InstrumentName'], '证监会二级行业') for i in sector_2 if i.startswith('23')]


    index_code = [('000001.SH', '上证指数', '大盘指数'), ('399001.SZ', '深证成指', '大盘指数'), ('399006.SZ', '创业板指', '大盘指数'), ('000688.SH', '科创50', '大盘指数'), ('000300.SH', '沪深300', '大盘指数'), ('000016.SH', '上证50', '大盘指数'), ('000905.SH', '中证500', '大盘指数'), ('000852.SH', '中证1000', '大盘指数')]

    code_list = {i[0]: i[1:] for i in sector_1 + sector_2 + index_code}

    return code_list
```

然后处理本地行情，这里以证监会二级行业行业中的餐饮业(230130.BKZS)为例:

```python
def get_local_kline_data(code='230130.BKZS', start_time='20200101', period='1d', code_list =  get_sector_list()):
    # 获取本地数据
    df = xtdata.get_local_data(stock_code=[code], period=period, field_list=['time', 'open', 'close', 'high', 'low', 'volume', 'amount'], start_time=start_time, end_time=datetime.datetime.now().strftime('%Y%m%d%H%M%S'))
    df = pd.concat([df[i].T.rename(columns={code:i}) for i in ['time', 'open', 'close', 'high', 'low', 'volume', 'amount']], axis=1)

    if len(df) < 1:
        return df

    # 时间转换
    df['trade_day'] = df['time'].apply(lambda x: datetime.datetime.fromtimestamp(x / 1000.0).date())

    # 重新选择列
    df['code'] = code
    df['sector_name'] = df['code'].apply(lambda x: code_list[x][0])
    df['sector_type'] = df['code'].apply(lambda x: code_list[x][1])
    df = df[['code', 'trade_day', 'sector_name', 'sector_type', 'open', 'close', 'high', 'low', 'volume', 'amount']]

    return df
```

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349610.jpg)

### Clickhouse数据库设计

这里选用Clickhouse，而不是MySQL的主要原因是性能问题。行情数据一旦写入，几乎不会更新，并且量非常大，没有复杂的表关联，MySQL在这种场景下主要的问题是存储空间占用多、读写慢，而ClickHouse主要用于在线分析处理查询（OLAP），具有高效的数据压缩、向量引擎、列式存储特性，非常适合金融行情数据存储。

```sql
create database xtquant

CREATE TABLE IF NOT EXISTS xtquant.bond_tick
(
    code String, 
    trade_time DateTime('Asia/Shanghai'), 
    trade_day Date, 
    trade_minute Int16, 
    open Nullable(Float32), 
    close Nullable(Float32), 
    high Nullable(Float32), 
    low Nullable(Float32), 
    last Nullable(Float32), 
    volume Nullable(Float64), 
    amount Nullable(Float64), 
    volume_deal Nullable(Float32), 
    amount_deal Nullable(Float32), 
    askPrice Array(Nullable(Float32)), 
    bidPrice Array(Nullable(Float32)), 
    askVol Array(Nullable(Float32)), 
    bidVol Array(Nullable(Float32))
)
ENGINE = ReplacingMergeTree()
ORDER BY (trade_time, code, trade_day)

CREATE TABLE IF NOT EXISTS xtquant.sector_1d
(
    code String, 
    trade_day Date, 
    sector_name String,
    sector_type String,
    open Nullable(Float32), 
    close Nullable(Float32), 
    high Nullable(Float32), 
    low Nullable(Float32), 
    volume Nullable(Float64), 
    amount Nullable(Float64)
)
ENGINE = ReplacingMergeTree()
ORDER BY (trade_day, code)
```

### Clickhouse数据批量写入

设计好数据表后，利用clickhouse_driver库提供的接口将数据同步到数据库中。

对于tick数据，按天来遍历插入，对于k线数据，则直接存入，为了增量同步，写入时可查询已有数据的最大时间，避免重复写。

```python
import os
from clickhouse_driver import Client
from tqdm import tqdm

storage_client = Client('10.0.16.11', password='******', settings={'use_numpy': True})

# 可转债tick数据
# 获取可转债列表
_, bond_code_list = get_bond_history()
for code in tqdm(bond_code_list):
    start_date = storage_client.execute("select max(trade_day) from xtquant.bond_tick where code='{}'".format(code))
    start_date = str(start_date[0][0]).replace('-', '')
    start_date = max(start_date, '20200401')
    trade_dates = xtdata.get_trading_dates('SH', start_time=start_date, end_time=datetime.date.today().strftime('%Y%m%d'))
    for day in trade_dates:
        day = datetime.datetime.fromtimestamp(day / 1000.0).strftime('%Y%m%d')
    df = get_local_tick_data(code=code, start_time=day)
    if len(df) > 0:
        storage_client.insert_dataframe('INSERT INTO xtquant.bond_tick VALUES', df)

# 行业1d数据
# 获取行业列表
sector_code_list = get_sector_list()
for code in tqdm(sector_code_list):
    start_date = storage_client.execute("select max(trade_day) from xtquant.sector_1d where code='{}'".format(code))
    start_date = str(start_date[0][0]).replace('-', '')
    start_date = max(start_date, '20100101')
    df = get_local_kline_data(code=code, start_time=start_date, period='1d', code_list =  sector_code_list)
    if len(df) > 0:
        storage_client.insert_dataframe('INSERT INTO xtquant.sector_1d VALUES', df)
```

运行如上代码后，我们便可在clickhouse客户端中查询到已经写入的数据：

![](https://gitee.com/hxc8/images5/raw/master/img/202407172349770.jpg)

至此，我们已经完成了历史数据到数据库中存储和增量写入。