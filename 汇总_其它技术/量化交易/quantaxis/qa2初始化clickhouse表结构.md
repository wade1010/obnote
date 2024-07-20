qa2初始化clickhouse数据

```
import QUANTAXIS as QA
import clickhouse_driver
import pprint

"""
from rqdatac import *
from jqdatac import *


可以从 jqdata/ rqdata import 标准的

get_price
all_instrument
index_weight
等函数

"""


class datamodelx:

    def __init__(self, market, frequence):
        """
        current support 

        ::::
        stock
        future
        index
        ::::

        ::::
        1min
        1day




        market:


        合约类型   说明
        CS Common Stock, 即股票
        ETF    Exchange Traded Fund, 即交易所交易基金
        LOF    Listed Open-Ended Fund，即上市型开放式基金 （以下分级基金已并入）
        INDX   Index, 即指数
        Future Futures，即期货，包含股指、国债和商品期货
        Spot   Spot，即现货，目前包括上海黄金交易所现货合约
        Option 期权，包括目前国内已上市的全部期权合约
        Convertible    沪深两市场内有交易的可转债合约
        Repo   沪深两市交易所交易的回购合约


        1m - 分钟线
        1d - 日线
        1w - 周线，只支持'1w'
        tick

        """

        self.market_dict = {QA.MARKET_TYPE.STOCK_CN: 'CS', QA.MARKET_TYPE.FUTURE_CN: 'Future',
                            QA.MARKET_TYPE.INDEX_CN: 'INDX',

                            QA.MARKET_TYPE.OPTION_CN: 'Option', 'etf_cn': 'ETF', 'lof_cn': 'LOF'}
        self.freq_dict = {QA.FREQUENCE.DAY: '1d', QA.FREQUENCE.ONE_MIN: '1m',
                          QA.FREQUENCE.FIVE_MIN: '5m', QA.FREQUENCE.FIFTEEN_MIN: '15m',
                          QA.FREQUENCE.TICK: 'tick'}

        self.market = market
        self.frequence = frequence

        self.table_name = self.market + '_cn_' + self.frequence

        # clickhouse://[user:password]@localhost:9000/default{}
        self.client = clickhouse_driver.Client(host='localhost', database='quantaxis', user='default', password='',
                                               settings={'insert_block_size': 100000000},
                                               compression=True)

        # self.codelist = self.get_list().sort_values('listed_date', ascending=False)
        self.temp_data = []

    def create_table(self):

        # self.client.execute('DROP TABLE IF EXISTS {}'.format(self.table_name))
        if 'day' in self.frequence:

            if 'stock' in self.market:
                print('s')
                """
                    ['order_book_id', 'date', 'num_trades', 'close', 'limit_up', 'low',
               'open', 'high', 'limit_down', 'volume', 'total_turnover']
                """

                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    date Date DEFAULT '1970-01-01',\
                        order_book_id String,\
                        num_trades Float32,\
                        limit_up  Float32,\
                        limit_down  Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`date`)) \
                    ORDER BY (date, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
            elif 'index' in self.market:

                """
                'order_book_id', 'date', 'total_turnover', 'num_trades', 'open', 'low',
                'high', 'volume', 'close'
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    date Date DEFAULT '1970-01-01',\
                        order_book_id String,\
                        num_trades Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`date`)) \
                    ORDER BY (date, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
            elif 'future' in self.market:

                """                
                ['order_book_id', 'date', 'limit_down', 'low', 'limit_up',
                'total_turnover', 'high', 'open_interest', 'prev_settlement', 'open',
                'volume', 'settlement', 'close']
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    date Date DEFAULT '1970-01-01',\
                        order_book_id String,\
                        limit_up  Float32,\
                        limit_down  Float32,\
                        open_interest Float32,\
                        prev_settlement Float32,\
                        settlement Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`date`)) \
                    ORDER BY (date, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)

            elif 'lof' in self.market:
                """
                ['order_book_id', 'date', 'limit_up', 'high', 'iopv', 'open', 'low', 'num_trades', 'close',
                'volume', 'total_turnover', 'limit_down'],
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    date Date DEFAULT '1970-01-01',\
                        order_book_id String,\
                        limit_up  Float32,\
                        limit_down  Float32,\
                        iopv Float32,\
                        num_trades Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`date`)) \
                    ORDER BY (date, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
            elif 'etf' in self.market:
                """
                ['order_book_id', 'date', 'limit_up', 'high', 'iopv', 'open', 'low',
                'num_trades', 'close', 'volume', 'total_turnover', 'limit_down']
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    date Date DEFAULT '1970-01-01',\
                        order_book_id String,\
                        limit_up  Float32,\
                        limit_down  Float32,\
                        iopv Float32,\
                        num_trades Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`date`)) \
                    ORDER BY (date, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
            elif 'option' in self.market:
                """
                ['order_book_id', 'date', 'limit_up', 'contract_multiplier',
                    'strike_price', 'open_interest', 'high', 'open', 'low', 'close',
                    'volume', 'settlement', 'prev_settlement', 'total_turnover',
                    'limit_down']
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    date Date DEFAULT '1970-01-01',\
                        order_book_id String,\
                        limit_up  Float32,\
                        limit_down  Float32,\
                        strike_price Float32,\
                        contract_multiplier Float32,\
                        open_interest Float32,\
                        settlement Float32,\
                        prev_settlement Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`date`)) \
                    ORDER BY (date, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)

        elif 'min' in self.frequence:

            if 'stock' in self.market:
                """
                   ['order_book_id', 'datetime', 'low', 'total_turnover', 'high', 'open',
                   'volume', 'close']
                """

                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    datetime Datetime DEFAULT '1970-01-01',\
                        order_book_id String,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`datetime`)) \
                    ORDER BY (datetime, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
            elif 'index' in self.market:

                """
                'order_book_id', 'date', 'total_turnover', 'num_trades', 'open', 'low',
                'high', 'volume', 'close'
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    datetime Datetime DEFAULT '1970-01-01',\
                        order_book_id String,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`datetime`)) \
                    ORDER BY (datetime, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)

            elif 'future' in self.market:

                """                
                ['order_book_id', 'datetime', 'low', 'total_turnover', 'high',
                       'open_interest', 'trading_date', 'open', 'volume', 'close']
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    datetime Datetime DEFAULT '1970-01-01',\
                    trading_date Date DEFAULT '1970-01-01',\
                        order_book_id String,\
                        open_interest Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`datetime`)) \
                    ORDER BY (datetime, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
            elif 'option' in self.market:

                """                
                ['order_book_id', 'datetime', 'open_interest', 'high', 'open', 'low',
                'trading_date', 'close', 'volume', 'total_turnover'],
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    datetime Datetime DEFAULT '1970-01-01',\
                    trading_date Date DEFAULT '1970-01-01',\
                        order_book_id String,\
                        open_interest Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`datetime`)) \
                    ORDER BY (datetime, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
            elif 'etf' in self.market:

                """                
                ['order_book_id', 'datetime', 'high', 'iopv', 'open', 'low', 'close',
                    'volume', 'total_turnover'],
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    datetime Datetime DEFAULT '1970-01-01',\
                        order_book_id String,\
                        iopv Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`datetime`)) \
                    ORDER BY (datetime, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
            elif 'lof' in self.market:

                """                
                ['order_book_id', 'datetime', 'high', 'iopv', 'open', 'low', 'close',
                    'volume', 'total_turnover'],
                """
                query = "CREATE TABLE IF NOT EXISTS \
                `quantaxis`.`{}` (\
                    datetime Datetime DEFAULT '1970-01-01',\
                        order_book_id String,\
                        iopv Float32,\
                        open Float32,\
                        high Float32,\
                        low Float32,\
                        close Float32,\
                        volume Float32,\
                        total_turnover Float32\
                    )\
                    ENGINE = ReplacingMergeTree() \
                    PARTITION BY (toYYYYMMDD(`datetime`)) \
                    ORDER BY (datetime, order_book_id)\
                    SETTINGS index_granularity=8192".format(self.table_name)
        elif 'tick' in self.frequence:
            if 'stock' in self.market:
                query = "CREATE TABLE IF NOT EXISTS \
                    `quantaxis`.`{}` (\
                        datetime Datetime DEFAULT '1970-01-01',\
                        trading_date Date, \
                        order_book_id String,\
                        open Float32,\
                        last Float32,\
                        high Float32,\
                        low  Float32,\
                        prev_close Float32,\
                        volume   Float32,\
                        total_turnover Float32,\
                        limit_up  Float32,\
                        limit_down Float32,\
                        a1  Float32,\
                        a2  Float32,\
                        a3  Float32,\
                        a4  Float32,\
                        a5  Float32,\
                        b1  Float32,\
                        b2  Float32,\
                        b3  Float32,\
                        b4  Float32,\
                        b5  Float32,\
                        a1_v Float32,\
                        a2_v Float32,\
                        a3_v Float32,\
                        a4_v Float32,\
                        a5_v Float32,\
                        b1_v Float32,\
                        b2_v Float32,\
                        b3_v Float32,\
                        b4_v Float32,\
                        b5_v Float32,\
                        change_rate Float32\
                        )\
                        ENGINE = ReplacingMergeTree() \
                        PARTITION BY (toYYYYMMDD(`datetime`)) \
                        ORDER BY (datetime, order_book_id)\
                        SETTINGS index_granularity=8192".format(self.table_name)
            elif 'future' in self.market:
                query = "CREATE TABLE IF NOT EXISTS \
                    `quantaxis`.`{}` (\
                        datetime Datetime DEFAULT '1970-01-01',\
                        trading_date Date, \
                        order_book_id String,\
                        open Float32,\
                        last Float32,\
                        high Float32,\
                        low  Float32,\
                        prev_settlement Float32,\
                        prev_close Float32,\
                        open_interest Float32,\
                        volume   Float32,\
                        total_turnover Float32,\
                        limit_up  Float32,\
                        limit_down Float32,\
                        a1  Float32,\
                        a2  Float32,\
                        a3  Float32,\
                        a4  Float32,\
                        a5  Float32,\
                        b1  Float32,\
                        b2  Float32,\
                        b3  Float32,\
                        b4  Float32,\
                        b5  Float32,\
                        a1_v Float32,\
                        a2_v Float32,\
                        a3_v Float32,\
                        a4_v Float32,\
                        a5_v Float32,\
                        b1_v Float32,\
                        b2_v Float32,\
                        b3_v Float32,\
                        b4_v Float32,\
                        b5_v Float32,\
                        change_rate Float32\
                        )\
                        ENGINE = ReplacingMergeTree() \
                        PARTITION BY (toYYYYMMDD(`datetime`)) \
                        ORDER BY (datetime, order_book_id)\
                        SETTINGS index_granularity=8192".format(self.table_name)
            elif 'option' in self.market:
                """
                ['order_book_id', 'datetime', 'trading_date', 'open', 'last', 'high',
                'low', 'prev_settlement', 'prev_close', 'volume', 'open_interest',
                'total_turnover', 'limit_up', 'limit_down', 'a1', 'a2', 'a3', 'a4',
                'a5', 'b1', 'b2', 'b3', 'b4', 'b5', 'a1_v', 'a2_v', 'a3_v', 'a4_v',
                'a5_v', 'b1_v', 'b2_v', 'b3_v', 'b4_v', 'b5_v', 'change_rate'],
                """
                query = "CREATE TABLE IF NOT EXISTS \
                    `quantaxis`.`{}` (\
                        datetime Datetime DEFAULT '1970-01-01',\
                        trading_date Date, \
                        order_book_id String,\
                        open Float32,\
                        last Float32,\
                        high Float32,\
                        low  Float32,\
                        prev_settlement Float32,\
                        prev_close Float32,\
                        open_interest Float32,\
                        volume   Float32,\
                        total_turnover Float32,\
                        limit_up  Float32,\
                        limit_down Float32,\
                        a1  Float32,\
                        a2  Float32,\
                        a3  Float32,\
                        a4  Float32,\
                        a5  Float32,\
                        b1  Float32,\
                        b2  Float32,\
                        b3  Float32,\
                        b4  Float32,\
                        b5  Float32,\
                        a1_v Float32,\
                        a2_v Float32,\
                        a3_v Float32,\
                        a4_v Float32,\
                        a5_v Float32,\
                        b1_v Float32,\
                        b2_v Float32,\
                        b3_v Float32,\
                        b4_v Float32,\
                        b5_v Float32,\
                        change_rate Float32\
                        )\
                        ENGINE = ReplacingMergeTree() \
                        PARTITION BY (toYYYYMMDD(`datetime`)) \
                        ORDER BY (datetime, order_book_id)\
                        SETTINGS index_granularity=8192".format(self.table_name)

            elif 'index' in self.market:
                query = "CREATE TABLE IF NOT EXISTS \
                    `quantaxis`.`{}` (\
                        datetime Datetime DEFAULT '1970-01-01',\
                        trading_date Date, \
                        order_book_id String,\
                        open Float32,\
                        last Float32,\
                        high Float32,\
                        low  Float32,\
                        prev_close Float32,\
                        volume   Float32,\
                        total_turnover Float32,\
                        limit_up  Float32,\
                        limit_down Float32,\
                        a1  Float32,\
                        a2  Float32,\
                        a3  Float32,\
                        a4  Float32,\
                        a5  Float32,\
                        b1  Float32,\
                        b2  Float32,\
                        b3  Float32,\
                        b4  Float32,\
                        b5  Float32,\
                        a1_v Float32,\
                        a2_v Float32,\
                        a3_v Float32,\
                        a4_v Float32,\
                        a5_v Float32,\
                        b1_v Float32,\
                        b2_v Float32,\
                        b3_v Float32,\
                        b4_v Float32,\
                        b5_v Float32,\
                        change_rate Float32\
                        )\
                        ENGINE = ReplacingMergeTree() \
                        PARTITION BY (toYYYYMMDD(`datetime`)) \
                        ORDER BY (datetime, order_book_id)\
                        SETTINGS index_granularity=8192".format(self.table_name)
            elif 'lof' in self.market:
                """
                ['order_book_id', 'datetime', 'trading_date', 'open', 'last', 'high',
                'low', 'prev_close', 'volume', 'total_turnover', 'limit_up',
                'limit_down', 'a1', 'a2', 'a3', 'a4', 'a5', 'b1', 'b2', 'b3', 'b4',
                'b5', 'a1_v', 'a2_v', 'a3_v', 'a4_v', 'a5_v', 'b1_v', 'b2_v', 'b3_v',
                'b4_v', 'b5_v', 'change_rate', 'iopv', 'prev_iopv']
                """
                query = "CREATE TABLE IF NOT EXISTS \
                    `quantaxis`.`{}` (\
                        datetime Datetime DEFAULT '1970-01-01',\
                        trading_date Date, \
                        order_book_id String,\
                        open Float32,\
                        last Float32,\
                        high Float32,\
                        low  Float32,\
                        prev_close Float32,\
                        volume   Float32,\
                        total_turnover Float32,\
                        limit_up  Float32,\
                        limit_down Float32,\
                        a1  Float32,\
                        a2  Float32,\
                        a3  Float32,\
                        a4  Float32,\
                        a5  Float32,\
                        b1  Float32,\
                        b2  Float32,\
                        b3  Float32,\
                        b4  Float32,\
                        b5  Float32,\
                        a1_v Float32,\
                        a2_v Float32,\
                        a3_v Float32,\
                        a4_v Float32,\
                        a5_v Float32,\
                        b1_v Float32,\
                        b2_v Float32,\
                        b3_v Float32,\
                        b4_v Float32,\
                        b5_v Float32,\
                        change_rate Float32,\
                        iopv Float32,\
                        prev_iopv Float32\
                        )\
                        ENGINE = ReplacingMergeTree() \
                        PARTITION BY (toYYYYMMDD(`datetime`)) \
                        ORDER BY (datetime, order_book_id)\
                        SETTINGS index_granularity=8192".format(self.table_name)
        pprint.pprint(query)
        self.client.execute(query)

if __name__ == '__main__':
    dm = datamodelx('stock', 'day')
    dm.create_table()
    dm = datamodelx('index', 'day')
    dm.create_table()
    dm = datamodelx('future', 'day')
    dm.create_table()
    dm = datamodelx('lof', 'day')
    dm.create_table()
    dm = datamodelx('etf', 'day')
    dm.create_table()
    dm = datamodelx('option', 'day')
    dm.create_table()
    dm = datamodelx('stock', 'min')
    dm.create_table()
    dm = datamodelx('index', 'min')
    dm.create_table()
    dm = datamodelx('future', 'min')
    dm.create_table()
    dm = datamodelx('lof', 'min')
    dm.create_table()
    dm = datamodelx('etf', 'min')
    dm.create_table()
    dm = datamodelx('option', 'min')
    dm.create_table()
    dm = datamodelx('stock', 'tick')
    dm.create_table()
    dm = datamodelx('index', 'tick')
    dm.create_table()
    dm = datamodelx('future', 'tick')
    dm.create_table()
    dm = datamodelx('lof', 'tick')
    dm.create_table()
    dm = datamodelx('option', 'tick')
    dm.create_table()

```

```
CREATE TABLE IF NOT EXISTS
    `quantaxis`.`stock_adj`
(
    date          Date DEFAULT '1970-01-01',
    adj           Float32,
    order_book_id String
)
    ENGINE = ReplacingMergeTree()
        ORDER BY (date, order_book_id)
        SETTINGS index_granularity = 8192
```

```
CREATE TABLE IF NOT EXISTS 
            `quantaxis`.`fund_cn_codelist` (
                order_book_id String,
                establishment_date String,
                listed_date  String,
                transition_time   Int64,
                amc  String,
                symbol    String,
                fund_type      String,
                fund_manager       String,
                latest_size   Float32,
                benchmark   String,
                accrued_daily Boolean,
                de_listed_date String,
                stop_date  String,
                exchange String,
                round_lot Float32
                )
                ENGINE = ReplacingMergeTree() 
                ORDER BY (order_book_id)
                SETTINGS index_granularity=8192
```