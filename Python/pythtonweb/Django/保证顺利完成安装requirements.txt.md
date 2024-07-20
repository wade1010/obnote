参考 [https://github.com/caoqianming/django-vue-admin](https://github.com/caoqianming/django-vue-admin)

git clone [https://github.com/caoqianming/django-vue-admin.git](https://github.com/caoqianming/django-vue-admin.git)

cd django-vue-admin.git

conda create -n cgs python=3.10

conda activate cgs

cd server/

pip install -r requirements.txt

vim requirements.txt

```
celery>=5.2.3
Django>=3.2.12
#django-celery-beat>=2.3.0
django-celery-results>=2.4.0
django-cors-headers>=3.11.0
django-filter>=21.1
django-simple-history>=3.0.0
djangorestframework>=3.13.1
djangorestframework-simplejwt>=5.1.0
drf-yasg>=1.21.3
psutil>=5.9.0
```

pip install django-celery-beat>=2.3.0

vim requirements.txt

把#django-celery-beat>=2.3.0的注释去掉

 pip install -r requirements.txt

就都OK了

修改数据库连接 server\settings_dev.py

由于使用了MySQL

pip install mysqlclient

```
(cgs) 383 django-vue-admin/server git:(master) ✗ » pip install mysqlclient                
Looking in indexes: http://pypi.douban.com/simple
Collecting mysqlclient
  Downloading http://pypi.doubanio.com/packages/50/5f/eac919b88b9df39bbe4a855f136d58f80d191cfea34a3dcf96bf5d8ace0a/mysqlclient-2.1.1.tar.gz (88 kB)
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ 88.1/88.1 kB 2.9 MB/s eta 0:00:00
  Preparing metadata (setup.py) ... error
  error: subprocess-exited-with-error
  
  × python setup.py egg_info did not run successfully.
  │ exit code: 1
  ╰─> [16 lines of output]
      /bin/sh: 1: mysql_config: not found
      /bin/sh: 1: mariadb_config: not found
      /bin/sh: 1: mysql_config: not found
      Traceback (most recent call last):
        File "<string>", line 2, in <module>
        File "<pip-setuptools-caller>", line 34, in <module>
        File "/tmp/pip-install-7fe8otv6/mysqlclient_b402e8f5eed24f839d5e2b5cb69fef08/setup.py", line 15, in <module>
          metadata, options = get_config()
        File "/tmp/pip-install-7fe8otv6/mysqlclient_b402e8f5eed24f839d5e2b5cb69fef08/setup_posix.py", line 70, in get_config
          libs = mysql_config("libs")
        File "/tmp/pip-install-7fe8otv6/mysqlclient_b402e8f5eed24f839d5e2b5cb69fef08/setup_posix.py", line 31, in mysql_config
          raise OSError("{} not found".format(_mysql_config_path))
      OSError: mysql_config not found
      mysql_config --version
      mariadb_config --version
      mysql_config --libs
      [end of output]
  
  note: This error originates from a subprocess, and is likely not a problem with pip.
error: metadata-generation-failed

× Encountered error while generating package metadata.
╰─> See above for output.

note: This is an issue with the package mentioned above, not pip.
hint: See above for details.
```

sudo apt-get install libmysqlclient-dev

pip install mysqlclient==2.1.1

到了执行脚本的时候，发现还是有错

```
(cgs) cepher@cepher:~/worspace/cgs/server$ python manage.py makemigration
Traceback (most recent call last):
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/MySQLdb/__init__.py", line 18, in <module>
    from . import _mysql
ImportError: /lib/x86_64-linux-gnu/libp11-kit.so.0: undefined symbol: ffi_type_pointer, version LIBFFI_BASE_7.0

During handling of the above exception, another exception occurred:

Traceback (most recent call last):
  File "/home/cepher/worspace/cgs/server/manage.py", line 21, in <module>
    main()
  File "/home/cepher/worspace/cgs/server/manage.py", line 17, in main
    execute_from_command_line(sys.argv)
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/core/management/__init__.py", line 442, in execute_from_command_line
    utility.execute()
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/core/management/__init__.py", line 416, in execute
    django.setup()
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/__init__.py", line 24, in setup
    apps.populate(settings.INSTALLED_APPS)
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/apps/registry.py", line 116, in populate
    app_config.import_models()
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/apps/config.py", line 269, in import_models
    self.models_module = import_module(models_module_name)
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/importlib/__init__.py", line 126, in import_module
    return _bootstrap._gcd_import(name[level:], package, level)
  File "<frozen importlib._bootstrap>", line 1050, in _gcd_import
  File "<frozen importlib._bootstrap>", line 1027, in _find_and_load
  File "<frozen importlib._bootstrap>", line 1006, in _find_and_load_unlocked
  File "<frozen importlib._bootstrap>", line 688, in _load_unlocked
  File "<frozen importlib._bootstrap_external>", line 883, in exec_module
  File "<frozen importlib._bootstrap>", line 241, in _call_with_frames_removed
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/contrib/auth/models.py", line 3, in <module>
    from django.contrib.auth.base_user import AbstractBaseUser, BaseUserManager
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/contrib/auth/base_user.py", line 57, in <module>
    class AbstractBaseUser(models.Model):
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/db/models/base.py", line 143, in __new__
    new_class.add_to_class("_meta", Options(meta, app_label))
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/db/models/base.py", line 371, in add_to_class
    value.contribute_to_class(cls, name)
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/db/models/options.py", line 243, in contribute_to_class
    self.db_table, connection.ops.max_name_length()
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/utils/connection.py", line 15, in __getattr__
    return getattr(self._connections[self._alias], item)
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/utils/connection.py", line 62, in __getitem__
    conn = self.create_connection(alias)
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/db/utils.py", line 193, in create_connection
    backend = load_backend(db["ENGINE"])
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/db/utils.py", line 113, in load_backend
    return import_module("%s.base" % backend_name)
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/importlib/__init__.py", line 126, in import_module
    return _bootstrap._gcd_import(name[level:], package, level)
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/django/db/backends/mysql/base.py", line 15, in <module>
    import MySQLdb as Database
  File "/home/cepher/miniconda3/envs/cgs/lib/python3.10/site-packages/MySQLdb/__init__.py", line 24, in <module>
    version_info, _mysql.version_info, _mysql.__file__
NameError: name '_mysql' is not defined

```

参考[Conda虚拟环境下libp11-kit.so.0 undefined symbol ffi_type_pointer...问题解决](note://WEB71fc3dc53a3d23c39741f9b446eb9828)解决

同步数据库 

python manage.py migrate

可导入初始数据 

python manage.py loaddata db.json

python manage.py createsuperuser

运行服务 

python manage.py runserver 0.0.0.0:8000

docker run -itd --name mysql-test -p 13306:3306 -e MYSQL_ROOT_PASSWORD=123456 mysql

定时任务

修改redis地址，如果是本机就不用修改

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCE13ad6194a514d2ca38a5b7d47f2f4f7b截图.png)

使用celery以及django_celery_beat包实现

需要安装redis并在默认端口启动, 并启动worker以及beat

进入虚拟环境并启动worker: celery -A server worker -l info -P eventlet, linux系统不用加-P eventlet

进入虚拟环境并启动beat: celery -A server beat -l info

然后在后台页面添加定时任务

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCE5a5c363785e1f755764dd0f475e5a7fa截图.png)

添加后

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCEcad47f26f9700ceaac5914c8f5394337截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCE7bb05fcd712824dfe7dcb78a69ee8e60截图.png)

创建stock app

cd server/apps

python ../manage.py startapp stock

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCE207e2ea3aea98525551ddc902e3c6833截图.png)

修改setting.py

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCE3e69e339de88debc50d19227c29067ff截图.png)

修改 stock/apps.py

![](D:/download/youdaonote-pull-master/data/Technology/Python/pythtonweb/Django/images/WEBRESOURCE96978de7a4014a842c63c604a28a8091截图.png)