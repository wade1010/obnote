[https://mp.weixin.qq.com/mp/appmsgalbum?__biz=Mzg3ODcyNzc5MA==&action=getalbum&album_id=2459911668907491328&subscene=159&subscene=&scenenote=https%3A%2F%2Fmp.weixin.qq.com%2Fs%2FcWYXulT-daBgrDtr36CHAA&nolastread=1#wechat_redirect](https://mp.weixin.qq.com/mp/appmsgalbum?__biz=Mzg3ODcyNzc5MA==&action=getalbum&album_id=2459911668907491328&subscene=159&subscene=&scenenote=https%3A%2F%2Fmp.weixin.qq.com%2Fs%2FcWYXulT-daBgrDtr36CHAA&nolastread=1#wechat_redirect)

[https://zhuanlan.zhihu.com/p/588827159](https://zhuanlan.zhihu.com/p/588827159)

[https://blog.csdn.net/popboy29/article/details/126115041](https://blog.csdn.net/popboy29/article/details/126115041)  需要看

貌似可以在ubuntu里面利用xquant调用windows上面的miniclient，xquant里面有个链接客户端配置可以修改，回头试试(后来证实，可以的，改成目标ip)

[https://www.zhihu.com/people/goonsnowball](https://www.zhihu.com/people/goonsnowball)

```
from xtquant import xtdata
import time

stock_code_list = xtdata.get_stock_list_in_sector('沪深A股')  # 获取沪深所有A股

# 遍历A股所有股票，打印输出
for i in stock_code_list:
    lastPrice_dict = xtdata.get_market_data([], [i], period='1d', count=-1)
    print(lastPrice_dict)
    time.sleep(2)

```

```
address=192.168.1.120:58610
```