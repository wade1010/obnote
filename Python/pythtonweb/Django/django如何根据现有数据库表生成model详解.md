[https://www.jb51.net/article/260116.htm](https://www.jb51.net/article/260116.htm)

python manage.py inspectdb  # 使用这条命令，会根据设置的数据库中的表在自动生成对应的Model代码，并打印出来

如果配置了多个数据库，则还可以配置数据库别名来指定根据哪个库中的表来生成Model

python manage.py inspectdb --database default >student/models.py  # default是默认的别名

将指定的表生成对应的Model

python manage.py inspectdb --database default table1 table2 >xxx/models.py

后来发现有点问题，

我直接生成到项目需要的models.py里面，然后启动项目会报错

File "<frozen importlib._bootstrap>", line 1050, in _gcd_import

File "<frozen importlib._bootstrap>", line 1027, in _find_and_load

File "<frozen importlib._bootstrap>", line 1006, in _find_and_load_unlocked

File "<frozen importlib._bootstrap>", line 688, in _load_unlocked

File "<frozen importlib._bootstrap_external>", line 879, in exec_module

File "<frozen importlib._bootstrap_external>", line 1017, in get_code

File "<frozen importlib._bootstrap_external>", line 947, in source_to_code

File "<frozen importlib._bootstrap>", line 241, in _call_with_frames_removed

ValueError: source code string cannot contain null bytes

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCE040be9731cb62b01676d4ee16519a15c截图.png)

后来通过pycharm右下角看到这个文件models.py编码变成了UTF-16LE，如下图

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCEd3b147e987c66449cde30aaf6cd7415c截图.png)

直接点击UTF-16LE，然后改为UTF-8保存即可