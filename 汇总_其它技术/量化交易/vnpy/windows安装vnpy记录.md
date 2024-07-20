git clone --depth 1 [https://github.com/vnpy/vnpy.git](https://github.com/vnpy/vnpy.git)

pip install ta-lib==0.4.24 --index=[https://pypi.vnpy.com](https://pypi.vnpy.com/)

.\install.bat

创建下面目录及文件，自己也可以自定义

![](https://gitee.com/hxc8/images5/raw/master/img/202407172334468.jpg)

run.py

```
from vnpy.event import EventEngine
from vnpy.trader.engine import MainEngine
from vnpy.trader.ui import MainWindow, create_qapp

from vnpy_ctp import CtpGateway
from vnpy_ctastrategy import CtaStrategyApp
from vnpy_ctabacktester import CtaBacktesterApp


def main():
    """Start VeighNa Trader"""
    qapp = create_qapp()

    event_engine = EventEngine()
    main_engine = MainEngine(event_engine)

    main_engine.add_gateway(CtpGateway)
    main_engine.add_app(CtaStrategyApp)
    main_engine.add_app(CtaBacktesterApp)

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
    "font.size": 8,
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
    "datafeed.name": "",
    "datafeed.username": "",
    "datafeed.password": "",
    "database.timezone": "Asia/Shanghai",
    "database.name": "mysql",
    "database.database": "vnpy",
    "database.host": "192.168.1.118",
    "database.port": 3306,
    "database.user":"vnpy",
    "database.password": "vnpy"
}

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

![](https://gitee.com/hxc8/images5/raw/master/img/202407172334819.jpg)

参考

[(4条消息) 【VeighNa】从海龟策略开始量化交易——第三章：数据服务配置_Emo就写代码的博客-CSDN博客](https://blog.csdn.net/m0_58598240/article/details/125780686)