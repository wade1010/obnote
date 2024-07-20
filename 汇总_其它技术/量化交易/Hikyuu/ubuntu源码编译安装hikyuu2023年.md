2023-05-17日重试

##### 安装conda

wget [https://repo.anaconda.com/miniconda/Miniconda3-py39_23.3.1-0-Linux-x86_64.sh](https://repo.anaconda.com/miniconda/Miniconda3-py39_23.3.1-0-Linux-x86_64.sh)

bash Miniconda3-py39_23.3.1-0-Linux-x86_64.sh

conda config --set auto_activate_base false

安装xmake

```
curl -fsSL https://xmake.io/shget.text | bash
```

##### 创建python39环境

conda create --name hikyuu python=3.9

conda activate hikyuu

##### clone hikyuu

cd workspace

git clone [https://github.com/fasiondog/hikyuu.git](https://github.com/fasiondog/hikyuu.git) --recursive --depth 1

cd hikyuu

##### boost库相关

wget [https://boostorg.jfrog.io/artifactory/main/release/1.78.0/source/boost_1_78_0.tar.gz](https://boostorg.jfrog.io/artifactory/main/release/1.78.0/source/boost_1_78_0.tar.gz)

tar zxf boost_1_78_0.tar.gz

如果你需要使用conda环境的python就进行下面配置

cd boost_1_78_0

vim project-config.jam  

```
using python : 3.9 : /home/yaya/miniconda3/envs/hikyuu/bin/python ;
```

##### 配置python国内源

mkdir ~/.pip

vim ~/.pip/pip.conf

```
[global]
timeout = 60
index-url = http://pypi.douban.com/simple
trusted-host = pypi.douban.com
```

##### 安装必要依赖

pip install click

sudo apt install -y libhdf5-dev libhdf5-serial-dev libmysqlclient-dev libsqlite3-dev

pip install -r requirements.txt

##### 编译 大概耗时5分钟

python setup.py build 

```
(hikyuu) hikyuu[master] % python setup.py build                          
checking xmake ...
xmake v2.7.9+20230515, A cross-platform build utility based on Lua
Copyright (C) 2015-present Ruki Wang, tboox.org, xmake.io
                         _
    __  ___ __  __  __ _| | ______
    \ \/ / |  \/  |/ _  | |/ / __ \
     >  <  | \__/ | /_| |   <  ___/
    /_/\_\_|_|  |_|\__ \|_|\_\____|
                         by ruki, xmake.io
    
    👉  Manual: https://xmake.io/#/getting_started
    🙏  Donate: https://xmake.io/#/sponsor
    
current python version: 3.9
checking for platform ... linux
checking for architecture ... x86_64
note: install or modify (m) these packages (pass -y to skip confirm)?
in xmake-repo:
  -> doctest 2.4.9 
  -> bzip2 1.0.8 [vs_runtime:"MD", from:boost]
  -> boost 1.81.0 [vs_runtime:"MD", data_time:y, shared:n, serialization:y, filesystem:y, ..)
  -> fmt 8.1.1 [header_only:y, vs_runtime:"MD", from:spdlog]
  -> spdlog v1.11.0 [fmt_external:y, header_only:y, vs_runtime:"MD"]
  -> sqlite3 3.39.0+200 [vs_runtime:"MD", shared:y, cxflags:"-fPIC"]
  -> flatbuffers v2.0.0 [vs_runtime:"MD"]
  -> nng 1.5.2 [vs_runtime:"MD", cxflags:"-fPIC"]
  -> nlohmann_json v3.11.2 
  -> cpp-httplib 0.12.1 
  -> zlib#2 v1.2.13 
in project-repo:
  -> hdf5 1.12.2 
  -> mysql 8.0.31 
please input: y (y/n/m)
y
  => download https://github.com/doctest/doctest/archive/refs/tags/v2.4.9.tar.gz .. ok
  => install doctest 2.4.9 .. ok                                         
  => download https://github.com/fmtlib/fmt/releases/download/8.1.1/fmt-8.1.1.zip .. ok
  => download https://gitee.com/fasiondog/hikyuu_extern_libs/releases/download/1.0.0/hdf5-1.12.2-linux-x64.zip .. ok
  => install hdf5 1.12.2 .. ok
  => install fmt 8.1.1 .. ok                                            
  => download https://gitee.com/fasiondog/hikyuu_extern_libs/releases/download/1.0.0/mysql-8.0.31-linux-x86_64.zip .. ok
  => install mysql 8.0.31 .. ok
  => download https://sqlite.org/2022/sqlite-autoconf-3390200.tar.gz .. ok
  => download https://github.com/google/flatbuffers/archive/v2.0.0.zip .. ok       
  => download https://sourceware.org/pub/bzip2/bzip2-1.0.8.tar.gz .. ok                 
  => download https://github.com/gabime/spdlog/archive/v1.11.0.zip .. ok                
  => install bzip2 1.0.8 .. ok                                                 
  => install spdlog v1.11.0 .. ok                                                          
  => install flatbuffers v2.0.0 .. ok                                                
  => download https://github.com/nanomsg/nng/archive/v1.5.2.zip .. ok                   
  => install nng 1.5.2 .. ok                                                           
  => install sqlite3 3.39.0+200 .. ok                                                     
  => download https://github.com/nlohmann/json/archive/v3.11.2.tar.gz .. ok
  => install nlohmann_json v3.11.2 .. ok                                           
  => download https://github.com/yhirose/cpp-httplib/archive/v0.12.1.zip .. ok
  => install cpp-httplib 0.12.1 .. ok                          
  => download https://github.com/xmake-mirror/boost/releases/download/boost-1.81.0/boost_1_81_0.tar.bz2 .. ok
  => install boost 1.81.0 .. ok   
  => download https://github.com/madler/zlib/archive/v1.2.13.tar.gz .. ok
  => install zlib#2 v1.2.13 .. ok    
generating /home/yaya/workspace/hikyuu/version.h.in ... ok
generating /home/yaya/workspace/hikyuu/config.h.in ... ok
[  0%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/Performance.cpp
[  0%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/TradeCostBase.cpp
[  1%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/imp/FixedA2017TradeCost.cpp
[  1%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/imp/ZeroTradeCost.cpp
[  2%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/imp/FixedA2015TradeCost.cpp
[  2%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/imp/FixedATradeCost.cpp
[  3%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/CostRecord.cpp
[  3%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/TradeRecord.cpp
[  4%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/FundsRecord.cpp
[  4%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/PositionRecord.cpp
[  5%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/TradeManager.cpp
[  5%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/LoanRecord.cpp
[  6%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/crt/TC_TestStub.cpp
[  6%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/crt/crtTM.cpp
[  7%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/export.cpp
[  7%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/BorrowRecord.cpp
[  8%]: cache compiling.release hikyuu_cpp/hikyuu/trade_manage/OrderBrokerBase.cpp
[  8%]: cache compiling.release hikyuu_cpp/hikyuu/Log.cpp
[  9%]: cache compiling.release hikyuu_cpp/hikyuu/KData.cpp
[  9%]: cache compiling.release hikyuu_cpp/hikyuu/serialization/serialization.cpp
[ 10%]: cache compiling.release hikyuu_cpp/hikyuu/trade_instance/ama_sys/AmaInstance.cpp
[ 10%]: cache compiling.release hikyuu_cpp/hikyuu/Block.cpp
[ 11%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/Indicator.cpp
[ 11%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAbs.cpp
[ 12%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IBarsSince.cpp
[ 12%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IDiff.cpp
[ 13%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IRoundUp.cpp
[ 13%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ISqrt.cpp
[ 14%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IMa.cpp
[ 14%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IRef.cpp
[ 15%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ISma.cpp
[ 15%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IDropna.cpp
[ 16%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAdvance.cpp
[ 16%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IExist.cpp
[ 17%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IDevsq.cpp
[ 17%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ITan.cpp
[ 18%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ISin.cpp
[ 18%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IPow.cpp
[ 19%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ITimeLine.cpp
[ 19%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ICos.cpp
[ 20%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IEma.cpp
[ 20%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAd.cpp
[ 21%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ISlice.cpp
[ 21%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IKData.cpp
[ 22%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ICeil.cpp
[ 22%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IRocr100.cpp
[ 23%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IDecline.cpp
[ 23%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAsin.cpp
[ 24%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IRocp.cpp
[ 24%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IRocr.cpp
[ 25%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IReverse.cpp
[ 25%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IRound.cpp
[ 26%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAlign.cpp
[ 26%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ILowLine.cpp
[ 27%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IVigor.cpp
[ 27%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IIntpart.cpp
[ 28%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAtan.cpp
[ 28%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ICost.cpp
[ 29%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IHighLine.cpp
[ 29%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IBackset.cpp
[ 30%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IRoundDown.cpp
[ 30%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ISaftyLoss.cpp
[ 31%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IFloor.cpp
[ 31%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ILiuTongPan.cpp
[ 32%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IExp.cpp
[ 32%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IBarsCount.cpp
[ 33%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IBarsLast.cpp
[ 33%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IVarp.cpp
[ 34%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAcos.cpp
[ 34%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ILog.cpp
[ 35%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ISign.cpp
[ 35%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAma.cpp
[ 36%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IVar.cpp
[ 36%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IAtr.cpp
[ 37%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ILn.cpp
[ 37%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IMacd.cpp
[ 38%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ITimelineVol.cpp
[ 38%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IStdp.cpp
[ 39%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IEvery.cpp
[ 39%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ILowLineBars.cpp
[ 40%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/INot.cpp
[ 40%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IHhvbars.cpp
[ 41%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IStdev.cpp
[ 41%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ICount.cpp
[ 42%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IFilter.cpp
[ 42%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ISum.cpp
[ 43%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IPriceList.cpp
[ 43%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ICval.cpp
[ 44%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/ISumBars.cpp
[ 44%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/imp/IRoc.cpp
[ 45%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/build_in.cpp
[ 45%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/IndParam.cpp
[ 46%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/crt/POS.cpp
[ 46%]: cache compiling.release hikyuu_cpp/hikyuu/indicator/IndicatorImp.cpp
[ 47%]: cache compiling.release hikyuu_cpp/hikyuu/StockManager.cpp
[ 47%]: cache compiling.release hikyuu_cpp/hikyuu/StrategyContext.cpp
[ 48%]: cache compiling.release hikyuu_cpp/hikyuu/MarketInfo.cpp
[ 48%]: cache compiling.release hikyuu_cpp/hikyuu/GlobalInitializer.cpp
[ 49%]: cache compiling.release hikyuu_cpp/hikyuu/KDataImp.cpp
[ 49%]: cache compiling.release hikyuu_cpp/hikyuu/KRecord.cpp
[ 50%]: cache compiling.release hikyuu_cpp/hikyuu/strategy/AccountTradeManager.cpp
[ 50%]: cache compiling.release hikyuu_cpp/hikyuu/strategy/StrategyBase.cpp
[ 51%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/BlockInfoDriver.cpp
[ 51%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/DataDriverFactory.cpp
[ 52%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/HistoryFinanceReader.cpp
[ 52%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/block_info/qianlong/QLBlockInfoDriver.cpp
[ 53%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/BaseInfoDriver.cpp
[ 53%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/KDataDriver.cpp
[ 54%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/kdata/cvs/KDataTempCsvDriver.cpp
[ 54%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/kdata/tdx/TdxKDataDriver.cpp
[ 55%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/kdata/mysql/MySQLKDataDriver.cpp
[ 55%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/kdata/hdf5/H5KDataDriver.cpp
[ 56%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/base_info/mysql/MySQLBaseInfoDriver.cpp
[ 56%]: cache compiling.release hikyuu_cpp/hikyuu/data_driver/base_info/sqlite/SQLiteBaseInfoDriver.cpp
[ 57%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/util.cpp
[ 57%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/db_connect/DBCondition.cpp
[ 58%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/db_connect/mysql/MySQLConnect.cpp
[ 58%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/db_connect/mysql/MySQLStatement.cpp
[ 59%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/db_connect/DBUpgrade.cpp
[ 59%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/db_connect/sqlite/SQLiteConnect.cpp
[ 60%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/db_connect/sqlite/SQLiteStatement.cpp
[ 60%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/task/StealRunnerQueue.cpp
[ 61%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/task/StealTaskRunner.cpp
[ 61%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/task/StealMasterQueue.cpp
[ 62%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/task/StealTaskGroup.cpp
[ 62%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/task/StealTaskBase.cpp
[ 63%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/IniParser.cpp
[ 63%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/Parameter.cpp
[ 64%]: cache compiling.release hikyuu_cpp/hikyuu/utilities/SpendTimer.cpp
[ 64%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/signal/imp/CrossSignal.cpp
[ 65%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/signal/imp/SingleSignal.cpp
[ 65%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/signal/imp/BoolSignal.cpp
[ 66%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/signal/imp/SingleSignal2.cpp
[ 66%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/signal/imp/CrossGoldSignal.cpp
[ 67%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/signal/crt/SG_Flex.cpp
[ 67%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/signal/export.cpp
[ 68%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/signal/SignalBase.cpp
[ 68%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/allocatefunds/AllocateFundsBase.cpp
[ 69%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/allocatefunds/imp/EqualWeightAllocateFunds.cpp
[ 69%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/allocatefunds/imp/FixedWeightAllocateFunds.cpp
[ 70%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/allocatefunds/export.cpp
[ 70%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/allocatefunds/SystemWeight.cpp
[ 71%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/condition/imp/OPLineCondition.cpp
[ 71%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/condition/ConditionBase.cpp
[ 72%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/condition/export.cpp
[ 72%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/environment/imp/TwoLineEnvironment.cpp
[ 73%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/environment/export.cpp
[ 73%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/environment/EnvironmentBase.cpp
[ 74%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/profitgoal/imp/FixedHoldDays.cpp
[ 74%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/profitgoal/imp/NoGoalProfitGoal.cpp
[ 75%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/profitgoal/imp/FixedPercentProfitGoal.cpp
[ 75%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/profitgoal/ProfitGoalBase.cpp
[ 76%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/profitgoal/export.cpp
[ 76%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/system/imp/SYS_Simple.cpp
[ 77%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/system/System.cpp
[ 77%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/system/SystemPart.cpp
[ 78%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/system/TradeRequest.cpp
[ 78%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/slippage/imp/FixedValueSlippage.cpp
[ 79%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/slippage/imp/FixedPercentSlippage.cpp
[ 79%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/slippage/SlippageBase.cpp
[ 80%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/slippage/export.cpp
[ 80%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/stoploss/StoplossBase.cpp
[ 81%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/stoploss/imp/FixedPercentStoploss.cpp
[ 81%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/stoploss/imp/IndicatorStoploss.cpp
[ 82%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/stoploss/crt/ST_Saftyloss.cpp
[ 82%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/stoploss/export.cpp
[ 83%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/imp/FixedRatioMoneyManager.cpp
[ 83%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/imp/NotMoneyManager.cpp
[ 84%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/imp/FixedCountMoneyManager.cpp
[ 84%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/imp/FixedCapitalMoneyManager.cpp
[ 85%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/imp/FixedUnitsMoneyManager.cpp
[ 85%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/imp/FixedRiskMoneyManager.cpp
[ 86%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/imp/WilliamsFixedRiskMoneyManager.cpp
[ 86%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/imp/FixedPercentMoneyManager.cpp
[ 87%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/export.cpp
[ 87%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/moneymanager/MoneyManagerBase.cpp
[ 88%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/selector/imp/SignalSelector.cpp
[ 88%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/selector/imp/FixedSelector.cpp
[ 89%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/selector/SelectorBase.cpp
[ 89%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/selector/export.cpp
[ 90%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/portfolio/imp/PF_Simple.cpp
[ 90%]: cache compiling.release hikyuu_cpp/hikyuu/trade_sys/portfolio/Portfolio.cpp
[ 91%]: cache compiling.release hikyuu_cpp/hikyuu/StockWeight.cpp
[ 91%]: cache compiling.release hikyuu_cpp/hikyuu/TimeLineRecord.cpp
[ 92%]: cache compiling.release hikyuu_cpp/hikyuu/TransRecord.cpp
[ 92%]: cache compiling.release hikyuu_cpp/hikyuu/StockTypeInfo.cpp
[ 93%]: cache compiling.release hikyuu_cpp/hikyuu/global/schedule/scheduler.cpp
[ 93%]: cache compiling.release hikyuu_cpp/hikyuu/global/schedule/inner_tasks.cpp
[ 94%]: cache compiling.release hikyuu_cpp/hikyuu/global/agent/SpotAgent.cpp
[ 94%]: cache compiling.release hikyuu_cpp/hikyuu/global/GlobalSpotAgent.cpp
[ 95%]: cache compiling.release hikyuu_cpp/hikyuu/global/GlobalTaskGroup.cpp
[ 95%]: cache compiling.release hikyuu_cpp/hikyuu/KQuery.cpp
[ 96%]: cache compiling.release hikyuu_cpp/hikyuu/hikyuu.cpp
[ 96%]: cache compiling.release hikyuu_cpp/hikyuu/datetime/TimeDelta.cpp
[ 97%]: cache compiling.release hikyuu_cpp/hikyuu/datetime/Datetime.cpp
[ 97%]: cache compiling.release hikyuu_cpp/hikyuu/Stock.cpp
[ 98%]: linking.release libhikyuu.so
copy dependents: /home/yaya/.xmake/packages/m/mysql/8.0.31/795411e6fe0f4042af05a8643df1ca53
copy dependents: /home/yaya/.xmake/packages/n/nng/1.5.2/fa667eeb85c249998452f4edd417e21a
copy dependents: /home/yaya/.xmake/packages/s/sqlite3/3.39.0+200/3900bff7c5e14e35be33774bf8c87faf
copy dependents: /home/yaya/.xmake/packages/f/flatbuffers/v2.0.0/3b19876a0c9a454698c03b02f70aa0fd
copy dependents: /home/yaya/.xmake/packages/h/hdf5/1.12.2/42c85e678bf74c9ba04f08805087a8c5
copy dependents: /home/yaya/.xmake/packages/b/boost/1.81.0/00ff0448f7bd458798f69dcf251175c2
copy dependents: /home/yaya/.xmake/packages/c/cpp-httplib/0.12.1/53bf4cf46c694c49936c317cd65d42b7
copy dependents: /home/yaya/.xmake/packages/n/nlohmann_json/v3.11.2/d0ab6439a26046eab271bd900a8ea21b
copy dependents: /home/yaya/.xmake/packages/s/spdlog/v1.11.0/20f8b46ec90c4ff591bdab40f963182d
[100%]: build ok, spent 815.467s
generating /home/yaya/workspace/hikyuu/version.h.in ... ok
generating /home/yaya/workspace/hikyuu/config.h.in ... cache
[ 72%]: cache compiling.release hikyuu_cpp/hikyuu/hikyuu.cpp
[ 74%]: cache compiling.release hikyuu_pywrap/_TimeDelta.cpp
[ 74%]: cache compiling.release hikyuu_pywrap/ioredirect.cpp
[ 75%]: cache compiling.release hikyuu_pywrap/trade_manage/_LoanRecord.cpp
[ 75%]: cache compiling.release hikyuu_pywrap/trade_manage/trade_manage_main.cpp
[ 75%]: cache compiling.release hikyuu_pywrap/trade_manage/_OrderBroker.cpp
[ 76%]: cache compiling.release hikyuu_pywrap/trade_manage/_TradeRecord.cpp
[ 76%]: cache compiling.release hikyuu_pywrap/trade_manage/_PositionRecord.cpp
[ 76%]: cache compiling.release hikyuu_pywrap/trade_manage/_build_in.cpp
[ 77%]: cache compiling.release hikyuu_pywrap/trade_manage/_TradeManager.cpp
[ 77%]: cache compiling.release hikyuu_pywrap/trade_manage/_FundsRecord.cpp
[ 78%]: cache compiling.release hikyuu_pywrap/trade_manage/_TradeCost.cpp
[ 78%]: cache compiling.release hikyuu_pywrap/trade_manage/_Performance.cpp
[ 78%]: cache compiling.release hikyuu_pywrap/trade_manage/_CostRecord.cpp
[ 79%]: cache compiling.release hikyuu_pywrap/trade_manage/_BorrowRecord.cpp
[ 79%]: cache compiling.release hikyuu_pywrap/_KRecord.cpp
[ 80%]: cache compiling.release hikyuu_pywrap/trade_instance/instance_main.cpp
[ 80%]: cache compiling.release hikyuu_pywrap/trade_instance/_AmaInstance.cpp
[ 80%]: cache compiling.release hikyuu_pywrap/_StockWeight.cpp
[ 81%]: cache compiling.release hikyuu_pywrap/_MarketInfo.cpp
[ 81%]: cache compiling.release hikyuu_pywrap/_StockTypeInfo.cpp
[ 81%]: cache compiling.release hikyuu_pywrap/_Block.cpp
[ 82%]: cache compiling.release hikyuu_pywrap/_StockManager.cpp
[ 82%]: cache compiling.release hikyuu_pywrap/_KData.cpp
[ 83%]: cache compiling.release hikyuu_pywrap/indicator/_IndParam.cpp
[ 83%]: cache compiling.release hikyuu_pywrap/indicator/_build_in.cpp
[ 83%]: cache compiling.release hikyuu_pywrap/indicator/indicator_main.cpp
[ 84%]: cache compiling.release hikyuu_pywrap/indicator/_Indicator.cpp
[ 84%]: cache compiling.release hikyuu_pywrap/indicator/_IndicatorImp.cpp
[ 84%]: cache compiling.release hikyuu_pywrap/_Constant.cpp
[ 85%]: cache compiling.release hikyuu_pywrap/_KQuery.cpp
[ 85%]: cache compiling.release hikyuu_pywrap/_util.cpp
[ 86%]: cache compiling.release hikyuu_pywrap/_TimeLineRecord.cpp
[ 86%]: cache compiling.release hikyuu_pywrap/strategy/_StrategyBase.cpp
[ 86%]: cache compiling.release hikyuu_pywrap/strategy/_strategy_main.cpp
[ 87%]: cache compiling.release hikyuu_pywrap/strategy/_AccountTradeManager.cpp
[ 87%]: cache compiling.release hikyuu_pywrap/main.cpp
[ 87%]: cache compiling.release hikyuu_pywrap/data_driver/_DataDriverFactory.cpp
[ 88%]: cache compiling.release hikyuu_pywrap/data_driver/_BaseInfoDriver.cpp
[ 88%]: cache compiling.release hikyuu_pywrap/data_driver/_BlockInfoDriver.cpp
[ 89%]: cache compiling.release hikyuu_pywrap/data_driver/data_driver_main.cpp
[ 89%]: cache compiling.release hikyuu_pywrap/data_driver/_KDataDriver.cpp
[ 89%]: cache compiling.release hikyuu_pywrap/_save_load.cpp
[ 90%]: cache compiling.release hikyuu_pywrap/trade_sys/_MoneyManager.cpp
[ 90%]: cache compiling.release hikyuu_pywrap/trade_sys/_Condition.cpp
[ 90%]: cache compiling.release hikyuu_pywrap/trade_sys/_Slippage.cpp
[ 91%]: cache compiling.release hikyuu_pywrap/trade_sys/_System.cpp
[ 91%]: cache compiling.release hikyuu_pywrap/trade_sys/_Portfolio.cpp
[ 92%]: cache compiling.release hikyuu_pywrap/trade_sys/_Selector.cpp
[ 92%]: cache compiling.release hikyuu_pywrap/trade_sys/_Environment.cpp
[ 92%]: cache compiling.release hikyuu_pywrap/trade_sys/_AllocateFunds.cpp
[ 93%]: cache compiling.release hikyuu_pywrap/trade_sys/trade_sys_main.cpp
[ 93%]: cache compiling.release hikyuu_pywrap/trade_sys/_Signal.cpp
[ 93%]: cache compiling.release hikyuu_pywrap/trade_sys/_ProfitGoal.cpp
[ 94%]: cache compiling.release hikyuu_pywrap/trade_sys/_Stoploss.cpp
[ 94%]: cache compiling.release hikyuu_pywrap/_Datetime.cpp
[ 95%]: cache compiling.release hikyuu_pywrap/_Log.cpp
[ 95%]: cache compiling.release hikyuu_pywrap/_StrategyContext.cpp
[ 95%]: cache compiling.release hikyuu_pywrap/global/_SpotAgent.cpp
[ 96%]: cache compiling.release hikyuu_pywrap/global/agent_main.cpp
[ 96%]: cache compiling.release hikyuu_pywrap/_DataType.cpp
[ 96%]: cache compiling.release hikyuu_pywrap/_TransRecord.cpp
[ 97%]: cache compiling.release hikyuu_pywrap/_Parameter.cpp
[ 97%]: cache compiling.release hikyuu_pywrap/_Stock.cpp
[ 98%]: linking.release libhikyuu.so
copy dependents: /home/yaya/.xmake/packages/b/boost/1.81.0/00ff0448f7bd458798f69dcf251175c2
copy dependents: /home/yaya/.xmake/packages/s/sqlite3/3.39.0+200/3900bff7c5e14e35be33774bf8c87faf
copy dependents: /home/yaya/.xmake/packages/h/hdf5/1.12.2/42c85e678bf74c9ba04f08805087a8c5
copy dependents: /home/yaya/.xmake/packages/n/nng/1.5.2/fa667eeb85c249998452f4edd417e21a
copy dependents: /home/yaya/.xmake/packages/s/spdlog/v1.11.0/20f8b46ec90c4ff591bdab40f963182d
copy dependents: /home/yaya/.xmake/packages/n/nlohmann_json/v3.11.2/d0ab6439a26046eab271bd900a8ea21b
copy dependents: /home/yaya/.xmake/packages/f/flatbuffers/v2.0.0/3b19876a0c9a454698c03b02f70aa0fd
copy dependents: /home/yaya/.xmake/packages/c/cpp-httplib/0.12.1/53bf4cf46c694c49936c317cd65d42b7
copy dependents: /home/yaya/.xmake/packages/m/mysql/8.0.31/795411e6fe0f4042af05a8643df1ca53
[ 99%]: linking.release core.so
[100%]: build ok, spent 292.838s

```

python setup.py install 或者开始直接执行这个命令不执行build

##### 启动界面

进入hikyuu根目录

python hikyuu/gui/HikyuuTDX.py

报错：

```
 python hikyuu/gui/HikyuuTDX.py                                                                 master ✔
Initialize hikyuu_1.2.7_202305172106_x64_release ...
2023-05-17 21:35:49,543 [INFO] NumExpr defaulting to 8 threads. [numexpr.utils::_init_num_threads]
warning: can't import TA-Lib, will be ignored! You can fetch ta-lib from https://www.lfd.uci.edu/~gohlke/pythonlibs/#ta-lib
正在下载 hikyuu 策略仓库至："/home/yaya/.hikyuu/hub_cache/default"
下载完毕
qt.qpa.plugin: Could not load the Qt platform plugin "xcb" in "" even though it was found.
This application failed to start because no Qt platform plugin could be initialized. Reinstalling the application may fix this problem.

Available platform plugins are: eglfs, linuxfb, minimal, minimalegl, offscreen, vnc, wayland-egl, wayland, wayland-xcomposite-egl, wayland-xcomposite-glx, webgl, xcb.

[1]    58105 IOT instruction (core dumped)  python hikyuu/gui/HikyuuTDX.py
```

TA-Lib的错误可以参考[ubuntu安装ta-lib](note://WEBaf6f36408941e43ac3b43cdbd7c5791c)解决

安装Qt运行时和XCB插件：

sudo apt-get install libqt5core5a libqt5gui5 libqt5widgets5 libqt5x11extras5 libxcb-xinerama0

再重试

python hikyuu/gui/HikyuuTDX.py

终于OK

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331952.jpg)

##### 下载数据

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332190.jpg)

上面是修改保存HDF5路径

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332864.jpg)

第一次手工执行导入

选择日线和5分钟下载完耗时25分钟，截图如下

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332164.jpg)