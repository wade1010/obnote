Ubuntu版本

```
 ~ → lsb_release -a
No LSB modules are available.
Distributor ID: Ubuntu
Description:    Ubuntu 20.04.6 LTS
Release:        20.04
Codename:       focal

```

pip install hikyuu 安装报错

```
~ → pip install hikyuu
Looking in indexes: https://pypi.douban.com/simple/
/usr/share/python-wheels/urllib3-1.25.8-py2.py3-none-any.whl/urllib3/connectionpool.py:999: InsecureRequestWarning: Unverified HTTPS request is being made to host 'pypi.douban.com'. Adding certificate verification is strongly advised. See: https://urllib3.readthedocs.io/en/latest/advanced-usage.html#ssl-warnings
Collecting hikyuu
  Downloading https://pypi.doubanio.com/packages/7a/be/4747e71a2ae98374230d9d6547bdb3aa233fd531eef260af0eef07836305/hikyuu-1.2.7-cp38-none-manylinux1_x86_64.whl (19.1 MB)
     |████████████████████████████████| 19.1 MB 165 kB/s
/usr/share/python-wheels/urllib3-1.25.8-py2.py3-none-any.whl/urllib3/connectionpool.py:999: InsecureRequestWarning: Unverified HTTPS request is being made to host 'pypi.douban.com'. Adding certificate verification is strongly advised. See: https://urllib3.readthedocs.io/en/latest/advanced-usage.html#ssl-warnings
Collecting PyQt5
  Downloading https://pypi.doubanio.com/packages/5c/46/b4b6eae1e24d9432905ef1d4e7c28b6610e28252527cdc38f2a75997d8b5/PyQt5-5.15.9.tar.gz (3.2 MB)
     |████████████████████████████████| 3.2 MB 7.8 MB/s
  Installing build dependencies ... done
  Getting requirements to build wheel ... done
    Preparing wheel metadata ... error
    ERROR: Command errored out with exit status 1:
     command: /usr/bin/python3 /tmp/tmpv5oc49rp prepare_metadata_for_build_wheel /tmp/tmpmf5pqbfm
         cwd: /tmp/pip-install-42v8f37h/PyQt5
    Complete output (31 lines):
    Traceback (most recent call last):
      File "/tmp/tmpv5oc49rp", line 126, in prepare_metadata_for_build_wheel
        hook = backend.prepare_metadata_for_build_wheel
    AttributeError: module 'sipbuild.api' has no attribute 'prepare_metadata_for_build_wheel'

    During handling of the above exception, another exception occurred:

    Traceback (most recent call last):
      File "/tmp/tmpv5oc49rp", line 280, in <module>
        main()
      File "/tmp/tmpv5oc49rp", line 263, in main
        json_out['return_val'] = hook(**hook_input['kwargs'])
      File "/tmp/tmpv5oc49rp", line 130, in prepare_metadata_for_build_wheel
        return _get_wheel_metadata_from_wheel(backend, metadata_directory,
      File "/tmp/tmpv5oc49rp", line 159, in _get_wheel_metadata_from_wheel
        whl_basename = backend.build_wheel(metadata_directory, config_settings)
      File "/tmp/pip-build-env-x60kfubx/overlay/lib/python3.8/site-packages/sipbuild/api.py", line 46, in build_wheel
        project = AbstractProject.bootstrap('wheel',
      File "/tmp/pip-build-env-x60kfubx/overlay/lib/python3.8/site-packages/sipbuild/abstract_project.py", line 87, in bootstrap
        project.setup(pyproject, tool, tool_description)
      File "/tmp/pip-build-env-x60kfubx/overlay/lib/python3.8/site-packages/sipbuild/project.py", line 586, in setup
        self.apply_user_defaults(tool)
      File "/tmp/pip-install-42v8f37h/PyQt5/project.py", line 68, in apply_user_defaults
        super().apply_user_defaults(tool)
      File "/tmp/pip-build-env-x60kfubx/overlay/lib/python3.8/site-packages/pyqtbuild/project.py", line 70, in apply_user_defaults
        super().apply_user_defaults(tool)
      File "/tmp/pip-build-env-x60kfubx/overlay/lib/python3.8/site-packages/sipbuild/project.py", line 237, in apply_user_defaults
        self.builder.apply_user_defaults(tool)
      File "/tmp/pip-build-env-x60kfubx/overlay/lib/python3.8/site-packages/pyqtbuild/builder.py", line 69, in apply_user_defaults
        raise PyProjectOptionException('qmake',
    sipbuild.pyproject.PyProjectOptionException
    ----------------------------------------
ERROR: Command errored out with exit status 1: /usr/bin/python3 /tmp/tmpv5oc49rp prepare_metadata_for_build_wheel /tmp/tmpmf5pqbfm Check the logs for full command output.


```

尝试修复（这个不知道有没有用，因为这个装了之后再执行pip install hikyuu还是报错，但是不能排除没有有，这里就不重试了）

sudo apt install -y libhdf5-dev libhdf5-serial-dev libmysqlclient-dev libsqlite3-dev

真正修复

python3 -m pip install pyqt5==5.14 pyqtchart==5.14

再次

pip install hikyuu

就OK了

> TA-Lib参考
