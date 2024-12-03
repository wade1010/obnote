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

TqSdk中, K线周期以秒数表示，支持<font color="#c00000">不超过1日</font>的任意周期K线，例如:

api.get_kline_serial("SHFE.cu1901", 70) # 70秒线
api.get_kline_serial("SHFE.cu1901", 86400) # 86400秒线, 即日线
api.get_kline_serial("SHFE.cu1901", 86500) # 86500秒线, 超过1日，无效


TqSdk中最多可以获取每个K线序列的最后8000根K线，无论哪个周期。也就是说，你如果提取小时线，最多可以提取最后8000根小时线，如果提取分钟线，最多也是可以提取最后8000根分钟线。

