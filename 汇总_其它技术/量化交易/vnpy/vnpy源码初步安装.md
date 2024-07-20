clone下源码

```
sh install_osx.sh
Requirement already satisfied: pip in /usr/local/anaconda3/envs/vnpy/lib/python3.9/site-packages (22.2.2)
Requirement already satisfied: wheel in /usr/local/anaconda3/envs/vnpy/lib/python3.9/site-packages (0.37.1)
install_osx.sh: line 15: `install-ta-lib': not a valid identifier

```

报错，我是自己本地手动执行 

```
brew install ta-lib
```

然后删掉install_osx.sh  

```
ta-lib-exists || install-ta-lib
```

再次执行 

sh install_osx.sh

pip install vnpy_ctp  (mac上面可以安装，但是运行会报错)

pip install vnpy_ctastrategy

pip install vnpy_ctabacktester

创建run.py,我这里就在项目根目录创建了

```
from vnpy.event import EventEngine
from vnpy.trader.engine import MainEngine
from vnpy.trader.ui import MainWindow, create_qapp

from vnpy_ctp import CtpGateway #mac需要注释
from vnpy_ctastrategy import CtaStrategyApp
from vnpy_ctabacktester import CtaBacktesterApp


def main():
    """Start VeighNa Trader"""
    qapp = create_qapp()

    event_engine = EventEngine()
    main_engine = MainEngine(event_engine)
    
    main_engine.add_gateway(CtpGateway) #mac需要注释
    main_engine.add_app(CtaStrategyApp)
    main_engine.add_app(CtaBacktesterApp)

    main_window = MainWindow(main_engine, event_engine)
    main_window.showMaximized()

    qapp.exec()


if __name__ == "__main__":
    main()
```

启动

python run.py

# 因为mac上不支持ctp接口，所以要注释掉ctp接口，否则运行会报错。

注释掉后再次 python run.py

```
 python run.py
找不到数据库驱动vnpy_sqlite，使用默认的SQLite数据库
Traceback (most recent call last):
  File "/Users/bob/workspace/pythonworkspace/vnpy/vnpy/trader/database.py", line 132, in get_database
    module: ModuleType = import_module(module_name)
  File "/usr/local/anaconda3/envs/vnpy/lib/python3.9/importlib/__init__.py", line 127, in import_module
    return _bootstrap._gcd_import(name[level:], package, level)
  File "<frozen importlib._bootstrap>", line 1030, in _gcd_import
  File "<frozen importlib._bootstrap>", line 1007, in _find_and_load
  File "<frozen importlib._bootstrap>", line 984, in _find_and_load_unlocked
ModuleNotFoundError: No module named 'vnpy_sqlite'

During handling of the above exception, another exception occurred:

Traceback (most recent call last):
  File "/Users/bob/workspace/pythonworkspace/vnpy/run.py", line 28, in <module>
    main()
  File "/Users/bob/workspace/pythonworkspace/vnpy/run.py", line 18, in main
    main_engine.add_app(CtaStrategyApp)
  File "/Users/bob/workspace/pythonworkspace/vnpy/vnpy/trader/engine.py", line 101, in add_app
    engine: BaseEngine = self.add_engine(app.engine_class)
  File "/Users/bob/workspace/pythonworkspace/vnpy/vnpy/trader/engine.py", line 72, in add_engine
    engine: BaseEngine = engine_class(self, self.event_engine)
  File "/usr/local/anaconda3/envs/vnpy/lib/python3.9/site-packages/vnpy_ctastrategy/engine.py", line 110, in __init__
    self.database: BaseDatabase = get_database()
  File "/Users/bob/workspace/pythonworkspace/vnpy/vnpy/trader/database.py", line 135, in get_database
    module: ModuleType = import_module("vnpy_sqlite")
  File "/usr/local/anaconda3/envs/vnpy/lib/python3.9/importlib/__init__.py", line 127, in import_module
    return _bootstrap._gcd_import(name[level:], package, level)
  File "<frozen importlib._bootstrap>", line 1030, in _gcd_import
  File "<frozen importlib._bootstrap>", line 1007, in _find_and_load
  File "<frozen importlib._bootstrap>", line 984, in _find_and_load_unlocked
ModuleNotFoundError: No module named 'vnpy_sqlite'

```

pip install vnpy_sqlite

再次运行Python run.py

```
[0] % python run.py          
Traceback (most recent call last):
  File "/Users/bob/workspace/pythonworkspace/vnpy/run.py", line 28, in <module>
    main()
  File "/Users/bob/workspace/pythonworkspace/vnpy/run.py", line 18, in main
    main_engine.add_app(CtaStrategyApp)
  File "/Users/bob/workspace/pythonworkspace/vnpy/vnpy/trader/engine.py", line 101, in add_app
    engine: BaseEngine = self.add_engine(app.engine_class)
  File "/Users/bob/workspace/pythonworkspace/vnpy/vnpy/trader/engine.py", line 72, in add_engine
    engine: BaseEngine = engine_class(self, self.event_engine)
  File "/usr/local/anaconda3/envs/vnpy/lib/python3.9/site-packages/vnpy_ctastrategy/engine.py", line 110, in __init__
    self.database: BaseDatabase = get_database()
  File "/Users/bob/workspace/pythonworkspace/vnpy/vnpy/trader/database.py", line 132, in get_database
    module: ModuleType = import_module(module_name)
  File "/usr/local/anaconda3/envs/vnpy/lib/python3.9/importlib/__init__.py", line 127, in import_module
    return _bootstrap._gcd_import(name[level:], package, level)
  File "<frozen importlib._bootstrap>", line 1030, in _gcd_import
  File "<frozen importlib._bootstrap>", line 1007, in _find_and_load
  File "<frozen importlib._bootstrap>", line 986, in _find_and_load_unlocked
  File "<frozen importlib._bootstrap>", line 680, in _load_unlocked
  File "<frozen importlib._bootstrap_external>", line 850, in exec_module
  File "<frozen importlib._bootstrap>", line 228, in _call_with_frames_removed
  File "/usr/local/anaconda3/envs/vnpy/lib/python3.9/site-packages/vnpy_sqlite/__init__.py", line 26, in <module>
    from .sqlite_database import SqliteDatabase as Database
  File "/usr/local/anaconda3/envs/vnpy/lib/python3.9/site-packages/vnpy_sqlite/sqlite_database.py", line 20, in <module>
    from vnpy.trader.database import (
ImportError: cannot import name 'TickOverview' from 'vnpy.trader.database' (/Users/bob/workspace/pythonworkspace/vnpy/vnpy/trader/database.py)
^CException ignored in: <module 'threading' from '/usr/local/anaconda3/envs/vnpy/lib/python3.9/threading.py'>
Traceback (most recent call last):
  File "/usr/local/anaconda3/envs/vnpy/lib/python3.9/threading.py", line 1477, in _shutdown
    lock.acquire()

```

mac 本地是自带有sqllite3的。没整过。所以换成MySQL

修改vnpy/vnpy/trader/setting.py中的

```
"database.timezone": get_localzone_name(),
"database.name": "mysql",
"database.database": "vnpy",
"database.host": "localhost",
"database.port": 3306,
"database.user": "root",
"database.password": ""
```

pip install vnpy_mysql