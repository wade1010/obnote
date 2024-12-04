TqSdk回测框架使用一套复杂的规则来推进行情：

规则1: tick 序列(例如上面例子中的tsa) 总是按逐 tick 推进:

```
tsa  = api.get_tick_serial("CFFEX.IF1901")
print(tsa.datetime.iloc[-1])             # 2018/01/01 09:30:00.000
api.wait_update()                           # 推进一个tick
print(tsa.datetime.iloc[-1])             # 2018/01/01 09:30:00.500
```
规则2: K线序列 (例如上面例子中的ka1, ka2) 总是按周期推进. 每根K线在创建时和结束时各更新一次:

```
ka2 = api.get_kline_serial("SHFE.cu1901", 3600) # 请求小时线
print(ka2.iloc[-1])                         # 2018/01/01 09:00:00.000, O=35000, H=35000, L=35000, C=35000 小时线刚创建
api.wait_update()                           # 推进1小时, 前面一个小时线结束, 新开一根小时线
print(ka2.iloc[-2])                         # 2018/01/01 09:00:00.000, O=35000, H=35400, L=34700, C=34900 9点这根小时线完成了
print(ka2.iloc[-1])                         # 2018/01/01 10:00:00.000, O=34900, H=34900, L=34900, C=34900 10点的小时线刚创建
```
规则3: quote按照以下规则更新:

```
if 策略程序中使用了这个合约的tick序列:
  每次tick序列推进时会更新quote的这些字段 datetime/ask&bid_price1至ask&bid_price5/ask&bid_volume1至ask&bid_volume5/last_price/highest/lowest/average/volume/amount/open_interest/price_tick/price_decs/volume_multiple/max&min_limit&market_order_volume/underlying_symbol/strike_price
elif 策略程序中使用了这个合约的K线序列:
  每次K线序列推进时会更新quote. 使用 k线生成的 quote 的盘口由收盘价分别加/减一个最小变动单位, 并且 highest/lowest/average/amount 始终为 nan, volume 始终为0.
  每次K线序列推进时会更新quote的这些字段 datetime/ask&bid_price1/ask&bid_volume1/last_price/open_interest/price_tick/price_decs/volume_multiple/max&min_limit&market_order_volume/underlying_symbol/strike_price

if 策略程序使用的K线周期大于1分钟:
    回测框架会隐式的订阅一个1分钟K线, 确保quote的更新周期不会超过1分钟
else:
  回测框架会隐式的订阅一个1分钟K线, 确保quote的更新周期不会超过1分钟
```
规则4: 策略程序中的多个序列的更新, 按时间顺序合并推进. 每次 wait_update 时, 优先处理用户事件, 当没有用户事件时, 从各序列中选择下一次更新时间最近的, 更新到这个时间:

```
ka = api.get_kline_serial("SHFE.cu1901", 10)              # 请求一个10秒线
kb = api.get_kline_serial("SHFE.cu1902", 15)              # 请求一个15秒线
print(ka.iloc[-1].datetime, kb.iloc[-1].datetime)   # 2018/01/01 09:00:00, 2018/01/01 09:00:00
api.wait_update()                                         # 推进一步, ka先更新了, 时间推到 09:00:10
print(ka.iloc[-1].datetime, kb.iloc[-1].datetime)   # 2018/01/01 09:00:10, 2018/01/01 09:00:00
api.wait_update()                                         # 再推一步, 这次时间推到 09:00:15, kb更新了
print(ka.iloc[-1].datetime, kb.iloc[-1].datetime)   # 2018/01/01 09:00:10, 2018/01/01 09:00:15
api.wait_update()                                         # 再推一步, 这次时间推到 09:00:20, ka更新了
print(ka.iloc[-1].datetime, kb.iloc[-1].datetime)   # 2018/01/01 09:00:20, 2018/01/01 09:00:15
api.wait_update()                                         # 再推一步, 时间推到 09:00:30, ka, kb都更新了
print(ka.iloc[-1].datetime, kb.iloc[-1].datetime)   # 2018/01/01 09:00:30, 2018/01/01 09:00:30
```
**注意** ：如果未订阅 quote，模拟交易在下单时会自动为此合约订阅 quote ，根据回测时 quote 的更新规则，如果此合约没有订阅K线或K线周期大于分钟线 **则会自动订阅一个分钟线** 。

另外，对 **组合合约** 进行回测时需注意：只能通过订阅 tick 数据来回测，不能订阅K线，因为K线是由最新价合成的，而交易所发回的组合合约数据中无最新价。