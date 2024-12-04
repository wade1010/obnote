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
