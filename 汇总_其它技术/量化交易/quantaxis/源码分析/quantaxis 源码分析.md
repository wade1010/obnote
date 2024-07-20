quantaxis 源码分析

```
cursor = collections.find(
    {
        'code': {
            '$in': code
        },
        "date_stamp":
            {
                "$lte": 1661961600.0,
                "$gte": 1648742400.0
            }
    },
    {"_id": 0},
    batch_size=10000
)
```

```
@property
def pending(self):
    '''
    600 废单 未委托成功
    200 委托成功,完全交易
    203 委托成功,未完全成功
    300 委托队列 待成交
    400 已撤单
    500 服务器撤单/每日结算


    订单生成(100) -- 废单(600)
    订单生成(100) -- 进入待成交队列(300) -- 完全成交(200) -- 每日结算(500)-- 死亡
    订单生成(100) -- 进入待成交队列(300) -- 部分成交(203) -- 未成交(300) -- 每日结算(500) -- 死亡
    订单生成(100) -- 进入待成交队列(300) -- 主动撤单(400) -- 每日结算(500) -- 死亡
    选择待成交列表
    :return: dataframe
    '''
    try:
        return [
            item for item in self.order_list.values() if item.status in [
                ORDER_STATUS.QUEUED,
                ORDER_STATUS.NEXT,
                ORDER_STATUS.SUCCESS_PART
            ]
        ]
    except:
        return []
```

```
@property
def order_qifi(self):
    return dict(zip(self.order_list.keys(), [item.to_qifi() for item in self.order_list.values()]))
```

```
self.static_balance['frozen'].append(
    sum(
        [
            rx['money'] * rx['amount']
            for var in self.frozen.values()
            for rx in var.values()
        ]
    )
)
```

```
# This statement supports dynamic execution of Python code
for item in kwargs.keys():
    exec('self.{}=kwargs[item]'.format(item))
```