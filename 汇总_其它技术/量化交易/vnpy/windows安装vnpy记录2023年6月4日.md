git clone --depth 1 [https://github.com/vnpy/vnpy.git](https://github.com/vnpy/vnpy.git)

.\install.bat

创建下面目录及文件，自己也可以自定义

![](https://gitee.com/hxc8/images5/raw/master/img/202407172334784.jpg)

run.py

```
# flake8: noqafrom vnpy.event import EventEngine

from vnpy.trader.engine import MainEngine
from vnpy.trader.ui import MainWindow, create_qapp

from vnpy_ctp import CtpGateway
from vnpy_ctptest import CtptestGateway
from vnpy_mini import MiniGateway
from vnpy_femas import FemasGateway
from vnpy_sopt import SoptGateway
# from vnpy_sec import SecGatewayfrom vnpy_uft import UftGateway
from vnpy_esunny import EsunnyGateway
from vnpy_xtp import XtpGateway
# from vnpy_tora import ToraStockGateway# from vnpy_tora import ToraOptionGateway# from vnpy_comstar import ComstarGatewayfrom vnpy_ib import IbGateway
from vnpy_tap import TapGateway
# from vnpy_da import DaGatewayfrom vnpy_rohon import RohonGateway
from vnpy_tts import TtsGateway
from vnpy_ost import OstGateway
# from vnpy_hft import GtjaGatewayfrom vnpy_ctastrategy import CtaStrategyApp
from vnpy_ctabacktester import CtaBacktesterApp
from vnpy_spreadtrading import SpreadTradingApp
from vnpy_algotrading import AlgoTradingApp
from vnpy_optionmaster import OptionMasterApp
from vnpy_portfoliostrategy import PortfolioStrategyApp
from vnpy_scripttrader import ScriptTraderApp
from vnpy_chartwizard import ChartWizardApp
from vnpy_rpcservice import RpcServiceApp
from vnpy_excelrtd import ExcelRtdApp
from vnpy_datamanager import DataManagerApp
from vnpy_datarecorder import DataRecorderApp
from vnpy_riskmanager import RiskManagerApp
from vnpy_webtrader import WebTraderApp
from vnpy_portfoliomanager import PortfolioManagerApp
from vnpy_paperaccount import PaperAccountApp


def main():
    """"""    qapp = create_qapp()

    event_engine = EventEngine()

    main_engine = MainEngine(event_engine)

    main_engine.add_gateway(CtpGateway)
    main_engine.add_gateway(CtptestGateway)
    main_engine.add_gateway(MiniGateway)
    main_engine.add_gateway(FemasGateway)
    main_engine.add_gateway(SoptGateway)
    # main_engine.add_gateway(SecGateway)    main_engine.add_gateway(UftGateway)
    main_engine.add_gateway(EsunnyGateway)
    main_engine.add_gateway(XtpGateway)
    # main_engine.add_gateway(ToraStockGateway)    # main_engine.add_gateway(ToraOptionGateway)    # main_engine.add_gateway(OesGateway)    # main_engine.add_gateway(ComstarGateway)    main_engine.add_gateway(IbGateway)
    main_engine.add_gateway(TapGateway)
    # main_engine.add_gateway(DaGateway)    main_engine.add_gateway(RohonGateway)
    main_engine.add_gateway(TtsGateway)
    main_engine.add_gateway(OstGateway)
    # main_engine.add_gateway(NhFuturesGateway)    # main_engine.add_gateway(NhStockGateway)    main_engine.add_app(PaperAccountApp)
    main_engine.add_app(CtaStrategyApp)
    main_engine.add_app(CtaBacktesterApp)
    main_engine.add_app(SpreadTradingApp)
    main_engine.add_app(AlgoTradingApp)
    main_engine.add_app(OptionMasterApp)
    main_engine.add_app(PortfolioStrategyApp)
    main_engine.add_app(ScriptTraderApp)
    main_engine.add_app(ChartWizardApp)
    main_engine.add_app(RpcServiceApp)
    main_engine.add_app(ExcelRtdApp)
    main_engine.add_app(DataManagerApp)
    main_engine.add_app(DataRecorderApp)
    main_engine.add_app(RiskManagerApp)
    main_engine.add_app(WebTraderApp)
    main_engine.add_app(PortfolioManagerApp)

    main_window = MainWindow(main_engine, event_engine)
    main_window.showMaximized()

    qapp.exec()


if __name__ == "__main__":
    main()

```

vt_setting.json

```
{
    "font.family": "微软雅黑",
    "font.size": 12,
    "log.active": true,
    "log.level": 50,
    "log.console": true,
    "log.file": true,
    "email.server": "smtp.qq.com",
    "email.port": 465,
    "email.username": "",
    "email.password": "",
    "email.sender": "",
    "email.receiver": "",
    "datafeed.name": "tushare",
    "datafeed.username": "tushare",
    "datafeed.password": "aceeaf07d9fddd7fdcf744254525225eb05a23e368a79419e378b3ba",
    "database.timezone": "Asia/Shanghai",
    "database.name": "sqlite",
    "database.database": "database",
    "database.host": "",
    "database.port": 0,
    "database.user": "",
    "database.password": ""}
```

```
{
    "font.family": "微软雅黑",
    "font.size": 12,
    "log.active": true,
    "log.level": 50,
    "log.console": true,
    "log.file": true,
    "email.server": "smtp.qq.com",
    "email.port": 465,
    "email.username": "",
    "email.password": "",
    "email.sender": "",
    "email.receiver": "",
    "datafeed.name": "tushare",
    "datafeed.username": "tushare",
    "datafeed.password": "aceeaf07d9fddd7fdcf744254525225eb05a23e368a79419e378b3ba",
    "database.timezone": "Asia/Shanghai",
    "database.name": "mysql",
    "database.database": "vnpy",
    "database.host": "192.168.1.118",
    "database.port": 3306,
    "database.user": "vnpy",
    "database.password": "vnpyvnpy"
}
```

如果是mysql,密码不能太短

最开始密码我是vnpy，启动报错

```
'Specified key was too long; max key length is 767 bytes
```

然后run这个脚本，报错缺少什么就安装什么

另外安装个

pip install vnpy_mysql 

pip install vnpy_rqdata

程序加载setting里面的配置信息都是从这个文件夹里面的json文件获取。

```
def _get_trader_dir(temp_name: str) -> Tuple[Path, Path]:
    """
    Get path where trader is running in.
    """
    cwd: Path = Path.cwd()
    temp_path: Path = cwd.joinpath(temp_name)

    # If .vntrader folder exists in current working directory,
    # then use it as trader running path.
    if temp_path.exists():
        return cwd, temp_path

    # Otherwise use home path of system.
    home_path: Path = Path.home()
    temp_path: Path = home_path.joinpath(temp_name)

    # Create .vntrader folder under home path if not exist.
    if not temp_path.exists():
        temp_path.mkdir()

    return home_path, temp_path


TRADER_DIR, TEMP_DIR = _get_trader_dir(".vntrader")
sys.path.append(str(TRADER_DIR))

```

pip install vnpy_tushare

![](https://gitee.com/hxc8/images5/raw/master/img/202407172334192.jpg)

参考

[(4条消息) 【VeighNa】从海龟策略开始量化交易——第三章：数据服务配置_Emo就写代码的博客-CSDN博客](https://blog.csdn.net/m0_58598240/article/details/125780686)