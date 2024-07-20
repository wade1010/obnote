[create_table.sql](attachments/WEBRESOURCE9422c5237252b46d77e8d7f44f3c7e8fcreate_table.sql)

```
CREATE TABLE quantaxis.etf_day
(
date Date DEFAULT '1970-01-01',
order_book_id String,
limit_up Float32,
limit_down Float32,
iopv Float32,
num_trades Float32,
open Float32,
high Float32,
low Float32,
close Float32,
volume Float32,
total_turnover Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(date)
ORDER BY (date, order_book_id)
SETTINGS index_granularity = 8192

CREATE TABLE quantaxis.etf_min
(
datetime DateTime DEFAULT '1970-01-01',
order_book_id String,
iopv Float32,
open Float32,
high Float32,
low Float32,
close Float32,
volume Float32,
total_turnover Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.fund_cn_codelist
(
    `order_book_id` String,
    `establishment_date` String,
    `listed_date` String,
    `transition_time` Int64,
    `amc` String,
    `symbol` String,
    `fund_type` String,
    `fund_manager` String,
    `latest_size` Float32,
    `benchmark` String,
    `accrued_daily` Bool,
    `de_listed_date` String,
    `stop_date` String,
    `exchange` String,
    `round_lot` Float32
)
ENGINE = ReplacingMergeTree
ORDER BY order_book_id
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.future_day
(
    `date` Date DEFAULT '1970-01-01',
    `order_book_id` String,
    `limit_up` Float32,
    `limit_down` Float32,
    `open_interest` Float32,
    `prev_settlement` Float32,
    `settlement` Float32,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(date)
ORDER BY (date, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.future_min
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `trading_date` Date DEFAULT '1970-01-01',
    `order_book_id` String,
    `open_interest` Float32,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.future_tick
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `trading_date` Date,
    `order_book_id` String,
    `open` Float32,
    `last` Float32,
    `high` Float32,
    `low` Float32,
    `prev_settlement` Float32,
    `prev_close` Float32,
    `open_interest` Float32,
    `volume` Float32,
    `total_turnover` Float32,
    `limit_up` Float32,
    `limit_down` Float32,
    `a1` Float32,
    `a2` Float32,
    `a3` Float32,
    `a4` Float32,
    `a5` Float32,
    `b1` Float32,
    `b2` Float32,
    `b3` Float32,
    `b4` Float32,
    `b5` Float32,
    `a1_v` Float32,
    `a2_v` Float32,
    `a3_v` Float32,
    `a4_v` Float32,
    `a5_v` Float32,
    `b1_v` Float32,
    `b2_v` Float32,
    `b3_v` Float32,
    `b4_v` Float32,
    `b5_v` Float32,
    `change_rate` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.index_day
(
    `date` Date DEFAULT '1970-01-01',
    `order_book_id` String,
    `num_trades` Float32,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(date)
ORDER BY (date, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.index_min
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `order_book_id` String,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.index_tick
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `trading_date` Date,
    `order_book_id` String,
    `open` Float32,
    `last` Float32,
    `high` Float32,
    `low` Float32,
    `prev_close` Float32,
    `volume` Float32,
    `total_turnover` Float32,
    `limit_up` Float32,
    `limit_down` Float32,
    `a1` Float32,
    `a2` Float32,
    `a3` Float32,
    `a4` Float32,
    `a5` Float32,
    `b1` Float32,
    `b2` Float32,
    `b3` Float32,
    `b4` Float32,
    `b5` Float32,
    `a1_v` Float32,
    `a2_v` Float32,
    `a3_v` Float32,
    `a4_v` Float32,
    `a5_v` Float32,
    `b1_v` Float32,
    `b2_v` Float32,
    `b3_v` Float32,
    `b4_v` Float32,
    `b5_v` Float32,
    `change_rate` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192



CREATE TABLE quantaxis.lof_day
(
    `date` Date DEFAULT '1970-01-01',
    `order_book_id` String,
    `limit_up` Float32,
    `limit_down` Float32,
    `iopv` Float32,
    `num_trades` Float32,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(date)
ORDER BY (date, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.lof_min
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `order_book_id` String,
    `iopv` Float32,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192

CREATE TABLE quantaxis.lof_tick
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `trading_date` Date,
    `order_book_id` String,
    `open` Float32,
    `last` Float32,
    `high` Float32,
    `low` Float32,
    `prev_close` Float32,
    `volume` Float32,
    `total_turnover` Float32,
    `limit_up` Float32,
    `limit_down` Float32,
    `a1` Float32,
    `a2` Float32,
    `a3` Float32,
    `a4` Float32,
    `a5` Float32,
    `b1` Float32,
    `b2` Float32,
    `b3` Float32,
    `b4` Float32,
    `b5` Float32,
    `a1_v` Float32,
    `a2_v` Float32,
    `a3_v` Float32,
    `a4_v` Float32,
    `a5_v` Float32,
    `b1_v` Float32,
    `b2_v` Float32,
    `b3_v` Float32,
    `b4_v` Float32,
    `b5_v` Float32,
    `change_rate` Float32,
    `iopv` Float32,
    `prev_iopv` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.option_day
(
    `date` Date DEFAULT '1970-01-01',
    `order_book_id` String,
    `limit_up` Float32,
    `limit_down` Float32,
    `strike_price` Float32,
    `contract_multiplier` Float32,
    `open_interest` Float32,
    `settlement` Float32,
    `prev_settlement` Float32,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(date)
ORDER BY (date, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.option_min
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `trading_date` Date DEFAULT '1970-01-01',
    `order_book_id` String,
    `open_interest` Float32,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.option_tick
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `trading_date` Date,
    `order_book_id` String,
    `open` Float32,
    `last` Float32,
    `high` Float32,
    `low` Float32,
    `prev_settlement` Float32,
    `prev_close` Float32,
    `open_interest` Float32,
    `volume` Float32,
    `total_turnover` Float32,
    `limit_up` Float32,
    `limit_down` Float32,
    `a1` Float32,
    `a2` Float32,
    `a3` Float32,
    `a4` Float32,
    `a5` Float32,
    `b1` Float32,
    `b2` Float32,
    `b3` Float32,
    `b4` Float32,
    `b5` Float32,
    `a1_v` Float32,
    `a2_v` Float32,
    `a3_v` Float32,
    `a4_v` Float32,
    `a5_v` Float32,
    `b1_v` Float32,
    `b2_v` Float32,
    `b3_v` Float32,
    `b4_v` Float32,
    `b5_v` Float32,
    `change_rate` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.stock_adj
(
    `date` Date DEFAULT '1970-01-01',
    `adj` Float32,
    `order_book_id` String
)
ENGINE = ReplacingMergeTree
ORDER BY (date, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.stock_cn_codelist
(
    `order_book_id` String,
    `industry_code` String,
    `market_tplus` Int32,
    `symbol` String,
    `special_type` String,
    `exchange` String,
    `status` String,
    `type` String,
    `de_listed_date` String,
    `listed_date` String,
    `sector_code_name` String,
    `abbrev_symbol` String,
    `sector_code` String,
    `round_lot` Float32,
    `trading_hours` String,
    `board_type` String,
    `industry_name` String,
    `issue_price` Float32,
    `trading_code` String,
    `purchasedate` String
)
ENGINE = ReplacingMergeTree
ORDER BY (listed_date, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.stock_cn_day
(
    `date` Date DEFAULT '1970-01-01',
    `order_book_id` String,
    `num_trades` Float32,
    `limit_up` Float32,
    `limit_down` Float32,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(date)
ORDER BY (date, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.stock_cn_min
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `order_book_id` String,
    `open` Float32,
    `high` Float32,
    `low` Float32,
    `close` Float32,
    `volume` Float32,
    `total_turnover` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192


CREATE TABLE quantaxis.stock_cn_tick
(
    `datetime` DateTime DEFAULT '1970-01-01',
    `trading_date` Date,
    `order_book_id` String,
    `open` Float32,
    `last` Float32,
    `high` Float32,
    `low` Float32,
    `prev_close` Float32,
    `volume` Float32,
    `total_turnover` Float32,
    `limit_up` Float32,
    `limit_down` Float32,
    `a1` Float32,
    `a2` Float32,
    `a3` Float32,
    `a4` Float32,
    `a5` Float32,
    `b1` Float32,
    `b2` Float32,
    `b3` Float32,
    `b4` Float32,
    `b5` Float32,
    `a1_v` Float32,
    `a2_v` Float32,
    `a3_v` Float32,
    `a4_v` Float32,
    `a5_v` Float32,
    `b1_v` Float32,
    `b2_v` Float32,
    `b3_v` Float32,
    `b4_v` Float32,
    `b5_v` Float32,
    `change_rate` Float32
)
ENGINE = ReplacingMergeTree
PARTITION BY toYYYYMMDD(datetime)
ORDER BY (datetime, order_book_id)
SETTINGS index_granularity = 8192




```