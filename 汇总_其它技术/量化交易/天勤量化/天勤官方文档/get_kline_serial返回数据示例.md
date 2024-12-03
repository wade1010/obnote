[`get_kline_serial()`](https://doc.shinnytech.com/tqsdk/latest/reference/tqsdk.api.html#tqsdk.TqApi.get_kline_serial "tqsdk.TqApi.get_kline_serial") 的返回值是一个 pandas.DataFrame, 包含以下列:

id: 1234 (k线序列号)
datetime: 1501080715000000000 (K线起点时间(按北京时间)，自unix epoch(1970-01-01 00:00:00 GMT)以来的纳秒数)
open: 51450.0 (K线起始时刻的最新价)
high: 51450.0 (K线时间范围内的最高价)
low: 51450.0 (K线时间范围内的最低价)
close: 51450.0 (K线结束时刻的最新价)
volume: 11 (K线时间范围内的成交量)
open_oi: 27354 (K线起始时刻的持仓量)
close_oi: 27355 (K线结束时刻的持仓量)