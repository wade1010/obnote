3、迅投QMT历史行情批量缓存

> 上文介绍了QMT行情接口概况和一个历史行情数据下载案例，本文进一步介绍如何获取批量股票代码并缓存对应的tick、分钟、日级别的历史数据。


## 获取股票名称列表

QMT的行情函数暂时不能获取可转债列表，因此这里使用akshare库进行相关元数据的获取，使用前确保已安装。akshare库本身的功能十分强大，后续将详细展开，这里先不赘述。

首先导入相关的包：

```
from xtquant import xtdata
import akshare as ak
from tqdm import tqdm
import pandas as pd
```

第一个接口是获取包含历史转债代码的列表，以方便同步历史数据，可转债上海市场以11开头，深圳市场以12开头，这里需要将akshare中来自东方财富的数据与QMT进行代码的对齐：

```
def get_bond_history():
    bond_zh_cov_df = ak.bond_zh_cov()
    # 排除至今未上市的转债
    bond_zh_cov_df =  bond_zh_cov_df[bond_zh_cov_df['上市时间'] <= datetime.date.today()]
    stock_code_list, bond_code_list = [], []
    for _, row in bond_zh_cov_df.iterrows():
        if row['债券代码'].startswith('11'):
            market = '.SH'
        else:
            market = '.SZ'
        stock_code_list.append(row['正股代码'] + market)
        bond_code_list.append(row['债券代码'] + market)
    return stock_code_list, bond_code_list
```

第二个接口是获取实时转债代码的列表，以方便增量更新，避免重复下载： 

```
def get_bond_spot():
    bond_cov_comparison_df = ak.bond_cov_comparison()
    # 排除至今未上市的转债
    bond_cov_comparison_df =  bond_cov_comparison_df[bond_cov_comparison_df['上市日期'] !='-']
 
    stock_code_list, bond_code_list = [], []
    for _, row in bond_cov_comparison_df.iterrows():
        if row['转债代码'].startswith('11'):
            market = '.SH'
        else:
            market = '.SZ'
        stock_code_list.append(row['正股代码'] + market)
        bond_code_list.append(row['转债代码'] + market)
    return stock_code_list, bond_code_list
```

第三个接口是获取A股市场的沪深指数、所有A股、ETF、债券列表等股票代码，其中A股和ETF列表使用get_stock_list_in_sector使用函数获取，以便下载K线数据：

```
def get_shse_a_list():
    '''
    获取沪深指数、所有A股、ETF、债券列表
    '''
    index_code = ['000001.SH', '399001.SZ', '399006.SZ', '000688.SH', '000300.SH', '000016.SH', '000905.SH', '000852.SH'] # 上证指数、深证成指、创业板指、科创50、沪深300、上证50、中证500、中证1000
    a_code = xtdata.get_stock_list_in_sector('沪深A股')
    etf_code =  xtdata.get_stock_list_in_sector('沪深ETF')
    #bond_code = [i for i in xtdata.get_stock_list_in_sector('沪深债券') if i[:3] in {'110',  '111', '113', '118', '123', '127', '128'}]
    bond_code = get_bond_history()[-1]
 
    return index_code + a_code + etf_code + bond_code
```

## 批量下载可转债tick数据

通过控制参数init来决定是否增量下载(以天为粒度)：

```
def download_history_bond_tick(init=1):
    '''
    下载历史转债tick数据(20200401起)
    '''
    # 初始化：获取转债及其正股代码
    if init:
        # 包含历史过期代码
        stock_code_list, bond_code_list = get_bond_history()
    else:
        # 仅当日代码
        stock_code_list, bond_code_list = get_bond_spot()
    
    # 数据下载目录
    data_dir = 'E:\\QMT\\userdata_mini\\datadir\\'
    for stock, bond in tqdm(zip(stock_code_list, bond_code_list), total=len(stock_code_list)):
        print("开始下载：股票 {}, 转债 {}".format(stock, bond))
        # 上海转债: 已下载的数据
        if bond.endswith("SH"):
            dir_path = data_dir + "\\SH\\0\\" + bond.split('.', 1)[0]
        # 深圳转债：已下载的数据
        else:
            dir_path = data_dir + "\\SZ\\0\\" + bond.split('.', 1)[0]
        
        start_date = '20200401' # QMT支持的最久数据时间
        # 如果路径存在，断点续传，重设起点下载时间
        if os.path.exists(dir_path):
            downloaded = os.listdir(dir_path)
            # 获取已下载的最大日期，作为本次同步的起始时间
            if len(downloaded) > 0:
                start_date = max(downloaded).split('.', 1)[0]
            
        xtdata.download_history_data(stock_code=bond, period='tick', start_time=start_date)
```

## 批量下载K线

通过传入参数start_time设置起始下载时间，参数period设置K线类型:

•1m: 1分钟K线•1d: 1日K线

```
def download_history_kline(start_time='', period='1m'):
    '''
    下载历史K线数据
    '''
    code_list = get_shse_a_list()
    print("本次开始下载的时间为：", datetime.datetime.now().strftime("%Y%m%d%H%M%S"))
    for code in tqdm(code_list):
        xtdata.download_history_data(code, period=period, start_time=start_time)
```

经过漫长的等待，本地便会有历史数据的缓存了，存储的目录形式为datadir\SH\{0|60|86400}\{code}，便于我们进一步加工处理。

至此，我们已经缓存了许多历史数据，并且可以增量更新，保存到数据库中供后续分析。

```
import datetime
import os
import ssl

from xtquant import xtdata

xtdata.data_dir = "D:\\Program Files\\QMT\\userdata_mini\\datadir"
import akshare as ak
from tqdm import tqdm
import pandas as pd


def get_bond_history():
    bond_zh_cov_df = ak.bond_zh_cov()
    # 排除至今未上市的转债
    bond_zh_cov_df = bond_zh_cov_df[bond_zh_cov_df['上市时间'] <= datetime.date.today()]
    stock_code_list, bond_code_list = [], []
    for _, row in bond_zh_cov_df.iterrows():
        if row['债券代码'].startswith('11'):
            market = '.SH'
        else:
            market = '.SZ'
        stock_code_list.append(row['正股代码'] + market)
        bond_code_list.append(row['债券代码'] + market)
    return stock_code_list, bond_code_list


def get_bond_spot():
    bond_cov_comparison_df = ak.bond_cov_comparison()
    # 排除至今未上市的转债
    bond_cov_comparison_df = bond_cov_comparison_df[bond_cov_comparison_df['上市日期'] != '-']

    stock_code_list, bond_code_list = [], []
    for _, row in bond_cov_comparison_df.iterrows():
        if row['转债代码'].startswith('11'):
            market = '.SH'
        else:
            market = '.SZ'
        stock_code_list.append(row['正股代码'] + market)
        bond_code_list.append(row['转债代码'] + market)
    return stock_code_list, bond_code_list


def get_shse_a_list():
    '''
    获取沪深指数、所有A股、ETF、债券列表
    '''
    index_code = ['000001.SH', '399001.SZ', '399006.SZ', '000688.SH', '000300.SH', '000016.SH', '000905.SH',
                  '000852.SH']  # 上证指数、深证成指、创业板指、科创50、沪深300、上证50、中证500、中证1000
    a_code = xtdata.get_stock_list_in_sector('沪深A股')
    etf_code = xtdata.get_stock_list_in_sector('沪深ETF')
    # bond_code = [i for i in xtdata.get_stock_list_in_sector('沪深债券') if i[:3] in {'110',  '111', '113', '118', '123', '127', '128'}]
    bond_code = get_bond_history()[-1]

    return index_code + a_code + etf_code + bond_code


def download_history_bond_tick(init=1):
    '''
    下载历史转债tick数据(20200401起)
    '''
    # 初始化：获取转债及其正股代码
    if init:
        # 包含历史过期代码
        stock_code_list, bond_code_list = get_bond_history()
    else:
        # 仅当日代码
        stock_code_list, bond_code_list = get_bond_spot()

    # 数据下载目录
    for stock, bond in tqdm(zip(stock_code_list, bond_code_list), total=len(stock_code_list)):
        print("开始下载：股票 {}, 转债 {}".format(stock, bond))
        # 上海转债: 已下载的数据
        if bond.endswith("SH"):
            dir_path = xtdata.data_dir + "\\SH\\0\\" + bond.split('.', 1)[0]
        # 深圳转债：已下载的数据
        else:
            dir_path = xtdata.data_dir + "\\SZ\\0\\" + bond.split('.', 1)[0]

        start_date = '20200401'  # QMT支持的最久数据时间
        # 如果路径存在，断点续传，重设起点下载时间
        if os.path.exists(dir_path):
            downloaded = os.listdir(dir_path)
            # 获取已下载的最大日期，作为本次同步的起始时间
            if len(downloaded) > 0:
                start_date = max(downloaded).split('.', 1)[0]

        xtdata.download_history_data(stock_code=bond, period='tick', start_time=start_date)


def download_history_kline(start_time='', period='1m'):
    '''
    下载历史K线数据
    '''
    code_list = get_shse_a_list()
    print("本次开始下载的时间为：", datetime.datetime.now().strftime("%Y%m%d%H%M%S"))
    for code in tqdm(code_list):
        xtdata.download_history_data(code, period=period, start_time=start_time)

if __name__ == '__main__':
    code_list = get_shse_a_list()
    print("本次开始下载的时间为：", datetime.datetime.now().strftime("%Y%m%d%H%M%S"))
    for code in tqdm(code_list):
        print(code)
```

上面代码2022-10-23 21:45:11  执行是会报错的大概是  TLS/SSL connection has been closed (EOF)

经过查阅资料，说是urllib3版本有问题，当前版本是1.26.12

```
(xtquant) PS W:\workspace\python\xtquant> pip list
Package            Version
------------------ -----------
akshare            1.7.59
.......

urllib3            1.26.12


```

pip install urllib3==1.25.8

之后就可以了