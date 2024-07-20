```
def select(cond, start=Datetime(201801010000), end=Datetime.now(), print_out=True):
    """
    示例：
    #选出涨停股
    C = CLOSE()
    x = select(C / REF(C, 1) - 1 >= 0.0995))

    :param Indicator cond: 条件指标
    :param Datetime start: 起始日期
    :param Datetime end: 结束日期
    :param bool print_out: 打印选中的股票
    :rtype: 选中的股票列表
    """
    q = Query(start, end)
    d = sm.get_trading_calendar(q, 'SH')
    if len(d) == 0:
        return

    result = []
    for s in blocka:
        if not s.valid:
            continue

        q = Query(start, end)
        k = s.get_kdata(q)
        cond.set_context(k)
        if len(cond) > 0 and cond[-1] != constant.null_price and cond[-1] > 0 and len(k
                                                                                      ) > 0 and k[-1].datetime == d[-1]:
            result.append(s)
            if print_out:
                print(d[-1], s)

    return result
```

```
from hikyuu.interactive import *
import pandas as pd


def calTotal(blk, q):
    per = Performance()
    s_name = []
    s_code = []
    x = []
    for stk in blk:
        my_sys.run(stk, q)
        per.statistics(my_tm, Datetime.now())
        s_name.append(stk.name)
        s_code.append(stk.market_code)
        x.append(per["当前总资产"])
    return pd.DataFrame({'代码': s_code, '股票': s_name, '当前总资产': x})


def getNextWeekDateList(week):
    from datetime import timedelta
    py_week = week.datetime()
    next_week_start = py_week + timedelta(days=7 - py_week.weekday())
    next_week_end = next_week_start + timedelta(days=5)
    return get_date_range(Datetime(next_week_start), Datetime(next_week_end))


def DEMO_SG(self):
    """    买入信号：周线MACD零轴下方底部金叉，即周线的DIF>DEA金叉时买入    卖出信号：日线级别 跌破 20日均线    参数：    week_macd_n1：周线dif窗口    week_macd_n2: 周线dea窗口    week_macd_n3: 周线macd平滑窗口    day_n: 日均线窗口    """    k = self.to
    if len(k) == 0:
        return    # -----------------------------    # 计算日线级别的卖出信号    # -----------------------------    # day_c = CLOSE(k)    # day_ma = MA(day_c, self.get_param("day_n"))    # day_x = day_c < day_ma  # 收盘价小于均线    # for i in range(day_x.discard, len(day_x)):    #     if day_x[i] >= 1.0:    #         self._add_sell_signal(k[i].datetime)    # -----------------------------    # 计算周线级别的买入信号    # -----------------------------    week_q = Query(k[0].datetime, k[-1].datetime.next_day(), ktype=Query.WEEK)
    week_k = k.get_stock().get_kdata(week_q)
    n1 = self.get_param("week_macd_n1")
    n2 = self.get_param("week_macd_n2")
    n3 = self.get_param("week_macd_n3")
    m = MACD(CLOSE(week_k), n1, n2, n3)
    fast = m.get_result(0)
    slow = m.get_result(1)

    discard = m.discard if m.discard > 1 else 1    for i in range(discard, len(m)):
        if fast[i - 1] < slow[i - 1] and fast[i] > slow[i]:
            # 当周计算的结果，只能作为下周一的信号            self._add_buy_signal(week_k[i].datetime.next_week())


def DEMO_CN(self):
    """ DIF > DEA 时，系统有效    参数：    fast_n：周线dif窗口    slow_n: 周线dea窗口    """    k = self.to
    if len(k) <= 10:
        return    # -----------------------------    # 周线    # -----------------------------    week_q = Query(k[0].datetime, k[-1].datetime, ktype=Query.WEEK)
    week_k = k.get_stock().get_kdata(week_q)
    n1 = self.get_param("week_macd_n1")
    n2 = self.get_param("week_macd_n2")
    n3 = self.get_param("week_macd_n3")
    m = MACD(CLOSE(week_k), n1, n2, n3)
    fast = m.get_result(0)
    slow = m.get_result(1)

    x = fast > slow
    for i in range(x.discard, len(x) - 1):
        if x[i] >= 1.0:
            # 需要被扩展到日线（必须是后一周）            date_list = getNextWeekDateList(week_k[i].datetime)
            for d in date_list:
                self._add_valid(d)


class DEMO_MM(MoneyManagerBase):
    """    资金管理策略    初次建仓：使用50%的资金    买入：初次建仓时持股数的50%    卖出：初次建仓时持股数的50%    """    def __init__(self):
        super(DEMO_MM, self).__init__("MACD_MM")
        self.set_param("init_position", 0.5)  # 自定义初始仓位参数，占用资金百分比        self.next_buy_num = 0    def _reset(self):
        self.next_buy_num = 0        # pass    def _clone(self):
        mm = DEMO_MM()
        mm.next_buy_num = self.next_buy_num
        # return DEMO_MM()    def _get_buy_num(self, datetime, stk, price, risk, part_from):
        tm = self.tm
        cash = tm.current_cash

        # 如果信号来源于系统有效条件，建立初始仓位        if part_from == System.Part.CONDITION:
            # return int((cash * 0.5 // price // stk.atom) * stk.atom)  #MoneyManagerBase其实已经保证了买入是最小交易数的整数            self.next_buy_num = 0  # 清理掉上一周期建仓期间滚动买卖的股票数            return int(cash * self.get_param("init_position") // price)

        # 非初次建仓，买入同等数量        return self.next_buy_num

    def _getSellNumber(self, datetime, stk, price, risk, part_from):
        tm = self.tm
        position = tm.get_position(datetime, stk)
        current_num = int(position.number * 0.5)

        # 记录第一次卖出时的股票数，以便下次以同等数量买入        if self.next_buy_num == 0:
            self.next_buy_num = current_num

        return current_num  # 返回类型必须是整数# System参数# delay=True #(bool) : 是否延迟到下一个bar开盘时进行交易# delay_use_current_price=True #(bool) : 延迟操作的情况下，是使用当前交易时bar的价格计算新的止损价/止赢价/目标价还是使用上次计算的结果# max_delay_count=3 #(int) : 连续延迟交易请求的限制次数# tp_monotonic=True #(bool) : 止赢单调递增# tp_delay_n=3 #(int) : 止盈延迟开始的天数，即止盈策略判断从实际交易几天后开始生效# ignore_sell_sg=False #(bool) : 忽略卖出信号，只使用止损/止赢等其他方式卖出# ev_open_position=False #(bool): 是否使用市场环境判定进行初始建仓cn_open_position = True  # (bool): 是否使用系统有效性条件进行初始建仓# MoneyManager公共参数# auto-checkin=False #(bool) : 当账户现金不足以买入资金管理策略指示的买入数量时，自动向账户中补充存入（checkin）足够的现金。# max-stock=20000 #(int) : 最大持有的证券种类数量（即持有几只股票，而非各个股票的持仓数）# disable_ev_force_clean_position=False #(bool) : 禁用市场环境失效时强制清仓# disable_cn_force_clean_position=False #(bool) : 禁用系统有效条件失效时强制清仓# 账户参数init_cash = 500000  # 账户初始资金init_date = '1990-1-1'  # 账户建立日期# 信号指示器参数week_n1 = 12week_n2 = 26week_n3 = 9# 如果是同一级别K线，可以使用索引号，使用了不同级别的K线数据，建议还是使用日期作为参数# 另外，数据量太大的话，matplotlib绘图会比较慢start_date = Datetime('2023-01-01')
end_date = Datetime()

# 创建模拟交易账户进行回测，初始资金30万my_tm = crtTM(date=Datetime(init_date), init_cash=init_cash)

# 创建系统实例my_sys = SYS_Simple()

my_sys.set_param("cn_open_position", cn_open_position)

my_sys.tm = my_tm

# 快速创建自定义不带私有属性的系统有效条件my_sys.cn = crtCN(DEMO_CN, {'week_macd_n1': week_n1, 'week_macd_n2': week_n2, 'week_macd_n3': week_n3})
# 绑定信号指示器# my_sys.sg = SG_Cross(EMA(n=week_n1), EMA(n=week_n2))my_sys.sg = crtSG(DEMO_SG, {'week_macd_n1': week_n1, 'week_macd_n2': week_n2, 'week_macd_n3': week_n3})

# 资金管理策略my_sys.mm = DEMO_MM()

q = Query(start_date, end_date, ktype=Query.DAY)

data = calTotal(blocka, q)

print(data[:10])

```

```
from hikyuu.interactive import *


def demo_func(stock, query):
    """计算指定stock的系统策略胜率，系统策略为之前的简单双均线交叉系统（每次固定买入100股）
    """
    # 创建模拟交易账户进行回测，初始资金100万
    my_tm = crtTM(init_cash=1000000)

    # 创建信号指示器（以5日EMA为快线，5日EMA自身的10日EMA作为慢线，快线向上穿越慢线时买入，反之卖出）
    my_sg = SG_Flex(EMA(n=5), slow_n=10)

    # 固定每次买入1000股
    my_mm = MM_FixedCount(1000)

    # 创建交易系统并运行
    sys = SYS_Simple(tm=my_tm, sg=my_sg, mm=my_mm)
    sys.run(stock, query)

    per = Performance()
    per.statistics(my_tm, Datetime(datetime.today()))
    # return per['赢利交易比例%']
    return per['帐户年复合收益率%']


def total_func(blk, query):
    """遍历指定板块的所有的股票，计算系统胜率"""
    result = {}
    for s in blk:
        if s.valid and s.type in (constant.STOCKTYPE_A, constant.STOCKTYPE_GEM, constant.STOCKTYPE_START):
            result[s.name] = demo_func(s, query)
    return result


if __name__ == '__main__':
    my_dict = total_func(sm, Query(-200))
    sorted_dict = sorted(my_dict.items(), key=lambda x: x[1], reverse=True)
    for item in sorted_dict:
        print(item[0], ":", item[1])



# 累计投入本金: 1000000.00
# 累计投入资产: 0.00
# 累计借入现金: 0.00
# 累计借入资产: 0.00
# 累计红利: 0.00
# 现金余额: 961360.00
# 未平仓头寸净值: 25630.00
# 当前总资产: 986990.00
# 已平仓交易总成本: 0.00
# 已平仓净利润总额: -13790.00
# 单笔交易最大占用现金比例%: 4.62
# 交易平均占用现金比例%: 3.86
# 已平仓帐户收益率%: -1.38
# 帐户年复合收益率%: -1.71
# 帐户平均年收益率%: -1.71
# 赢利交易赢利总额: 1580.00
# 亏损交易亏损总额: -15370.00
# 已平仓交易总数: 8.00
# 赢利交易数: 1.00
# 亏损交易数: 7.00
# 赢利交易比例%: 12.50
# 赢利期望值: -1723.75
# 赢利交易平均赢利: 1580.00
# 亏损交易平均亏损: -2195.71
# 平均赢利/平均亏损比例: 0.72
# 净赢利/亏损比例: 0.10
# 最大单笔赢利: 1580.00
# 最大单笔亏损: -5680.00
# 赢利交易平均持仓时间: 41.00
# 赢利交易最大持仓时间: 41.00
# 亏损交易平均持仓时间: 6.86
# 亏损交易最大持仓时间: 14.00
# 空仓总时间: 189.00
# 空仓时间/总时间%: 67.00
# 平均空仓时间: 23.00
# 最长空仓时间: 42.00
# 最大连续赢利笔数: 1.00
# 最大连续亏损笔数: 3.00
# 最大连续赢利金额: 1580.00
# 最大连续亏损金额: -11480.00
# R乘数期望值: -0.04
# 交易机会频率/年: 10.54
# 年度期望R乘数: -0.42
# 赢利交易平均R乘数: 0.04
# 亏损交易平均R乘数: -0.05
# 最大单笔赢利R乘数: 0.04
# 最大单笔亏损R乘数: -0.12
# 最大连续赢利R乘数: 0.04
# 最大连续亏损R乘数: -0.09
```

```
from hikyuu.interactive import *


def get_sm() -> StockManager:
    # StockManager的实例
    return sm


sm = get_sm()

# 一、全局变量与常量定义
# 1全局变量

# 1.1 Block 实例，包含全部A股
for blk in blocka:
    # print(blk)
    pass
print("blocka len:", len(blocka))

# 1.2 Block 实例，包含全部沪市股票
for blk in blocksh:
    # print(blk)
    pass
print("blocksh len:", len(blocksh))

# 1.3 Block 实例，包含全部深市股票
for blk in blocksz:
    # print(blk)
    pass
print("blocksz len:", len(blocksz))

# 1.4 Block 实例，包含全部创业板股票
for blk in blockg:
    # print(blk)
    pass
print("blockg len:", len(blockg))

# 2 Null值及证券类别
a = Datetime(201601010000)
if a == constant.null_datetime:
    print(True)
else:
    print("合法时间")

a = Datetime()
if a == constant.null_datetime:
    print("非法时间")

print(constant.inf)
print(constant.pickle_support)
print(constant.STOCKTYPE_A)
# inf
# """无穷大或无穷小"""
# max_double
# """最大double值"""
# nan
# """非数字"""
# null_datetime
# """无效Datetime"""
# null_double
# """同 nan"""
# null_int
# """无效int"""
# null_int64
# """无效int64_t"""
# null_price
# """同 nan"""
# null_size
# """无效size"""
# pickle_support
# """是否支持 pickle"""
# STOCKTYPE_A
# """A股"""
# STOCKTYPE_B
# """B股"""
# STOCKTYPE_BLOCK
# """板块"""
# STOCKTYPE_BOND
# """债券"""
# STOCKTYPE_ETF
# """ETF"""
# STOCKTYPE_FUND
# """基金"""
# STOCKTYPE_GEM
# """创业板"""
# STOCKTYPE_INDEX
# """指数"""
# STOCKTYPE_ND
# """国债"""
# STOCKTYPE_START
# """科创板"""
# STOCKTYPE_TMP
# """临时Stock"""

print(zsbk_sz50)  # 上证50
print(zsbk_sz180)  # 上证180
print(zsbk_hs300)  # 上证180
print(zsbk_zz100)  # 中证100

# ==============================================================================
#
# 设置关键类型简称
#
# ==============================================================================
# O = OPEN()
# C = CLOSE()
# H = HIGH()
# L = LOW()
# A = AMO()
# V = VOL()
# D = Datetime
# K = None
# Q = Query


# 二、基础数据类型
# 1 日期时间
# 日期时间及其运算主要涉及 Datetime，TimeDelta。
# 两者及其相关运算规则可参考 python datetime 模块中的 datetime 和 timedelta 帮助， 并且两者可和 datetime、timedelta 相互转换，并直接进行运算操作。
# TimeDelta 的运算规则基本与 datetime.timedelta 相同。
# classhikyuu.Datetime
# 日期时间类（精确到微秒），通过以下方式构建：
# 通过字符串：Datetime(“2010-1-1 10:00:00”)、Datetime(“2001-1-1”)、Datetime(“20010101”)、Datetime(“20010101T232359)
# 通过 Python 的date：Datetime(date(2010,1,1))
# 通过 Python 的datetime：Datetime(datetime(2010,1,1,10)
# 通过 YYYYMMDDHHMM 或 YYYYMMDD 形式的整数：Datetime(201001011000)、Datetime(20010101)
# Datetime(year, month, day, hour=0, minute=0, second=0, millisecond=0, microsecond=0)

date_time = Datetime('2023-5-24 22:22:48')
print(date_time.year, date_time.month, date_time.day, date_time.hour, date_time.minute)
print(date_time.number)  # YYYYMMDDHHMM 形式的整数，精度只到分钟
# date_time.date()  # 转化生成 python 的 date
# date_time.datetime()  # 转化生成 python 的datetime
if date_time.is_null():
    print('日期为空')

# 加上指定时长，时长对象可为 TimeDelta 或 datetime.timedelta 类型
after_add_date_time = date_time.__add__(TimeDelta(1))  # 加1天
print(after_add_date_time)
# 减去指定的时长, 时长对象可为 TimeDelta 或 datetime.timedelta 类型
after_sub_date_time = date_time.__sub__(TimeDelta(0, 2))  # 2小时
print(after_sub_date_time)

# 返回指定的本周中第几天的日期，周日为0天，周六为第6天
print(date_time.day_of_week())
day_of_week = date_time.day_of_week()
day_of_week_str = "今天是本周第%s天" % day_of_week
print(day_of_week_str)

print('date_of_week:', date_time.date_of_week(day_of_week))

print('今年的第%s天' % date_time.day_of_year())
# 返回当天 0点0分0秒
print(date_time.start_of_day())
# 返回当日 23点59分59秒
print(date_time.end_of_day())
# 返回周起始日期（周一）
print(date_time.start_of_week())
# 返回周结束日期（周日）
print(date_time.end_of_week())
# 返回月度起始日期
print(date_time.start_of_month())
# 返回月末最后一天日期
print(date_time.end_of_month())
print(date_time.start_of_quarter())
print(date_time.end_of_quarter())
print(date_time.start_of_halfyear())
print(date_time.end_of_halfyear())
print(date_time.start_of_year())
print(date_time.endOfYear())
# 返回下一自然日
print("明天%s" % date_time.next_day())
print('下周周一日期：', date_time.next_week())
print('下月首日日期：', date_time.next_month())
print('下一季度首日日期：', date_time.next_quarter())
print('下一半年度首日日期：', date_time.next_halfyear())
print('下一年度首日日期：', date_time.next_year())
print('前一自然日日期：', date_time.pre_day())
print('上周周一日期：', date_time.pre_week())
print('上月首日日期：', date_time.pre_month())
print('上一季度首日日期：', date_time.pre_quarter())
print('上一半年度首日日期：', date_time.pre_halfyear())
print('上一年度首日日期：', date_time.pre_year())
print('支持的最大日期时间：', date_time.max())
print('支持的最小日期时间：', date_time.min())
print('当前的日期时间：', date_time.now())
print('当前的日期：', date_time.today())

# classhikyuu.TimeDelta
# 时间时长，用于时间计算。可通过以下方式构建：
# 通过 datetime.timedelta 构建。TimdeDelta(timedelta实例)
# TimeDelta(days=0, hours=0, minutes=0, seconds=0, milliseconds=0, microseconds=0)
# -99999999 <= days <= 99999999
# -100000 <= hours <= 100000
# -100000 <= minutes <= 100000
# -8639900 <= seconds <= 8639900
# -86399000000 <= milliseconds <= 86399000000
# -86399000000 <= microseconds <= 86399000000
# 以上参数限制，主要为防止求总微秒数时可能出现溢出的情况。如只使用一个参数不希望存在上述限制时，可使用快捷函数： Days(), Hours(), Minutes(), Seconds(), Milliseconds(), Microseconds()

time_delta = TimeDelta(1)
if time_delta.isNegative():  # 是否为负时长
    print("负增长")
else:
    print("非负增长")

print('获取带小数的总天数', time_delta.total_days())
print(time_delta.total_hours(), time_delta.total_minutes(), time_delta.total_seconds(), time_delta.total_milliseconds())

print(time_delta.max(), time_delta.min())

print('支持的最小精度:', time_delta.resolution())
print('支持的最大 ticks （即微秒数）:', time_delta.max_ticks())
print('支持的最小 ticks （即微秒数）:', time_delta.min_ticks())
print('使用 ticks（即微秒数） 值创建:', time_delta.from_ticks(time_delta.max_ticks()))

# 以天数创建 TimeDelta
new_day_delta = Days(-2)  # 返回类型:  TimeDelta
# 下同
new_hours_delta = Hours(2)
new_minutes_delta = Minutes(2)
new_seconds_delta = Seconds(2)
new_milliseconds_delta = Milliseconds(2)
new_microseconds_delta = Microseconds(2)

# K线数据
# classhikyuu.KRecord
# K线记录，组成K线数据，属性可读写。
#
# datetime : 日期时间
# open : 开盘价
# high : 最高价
# low : 最低价
# close : 收盘价
# amount : 成交金额
# volume : 成交量

```