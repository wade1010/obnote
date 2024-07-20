sudo apt-get install jupyter

[https://hikyuu.readthedocs.io/zh_CN/latest/quickstart.html#id4](https://hikyuu.readthedocs.io/zh_CN/latest/quickstart.html#id4)

# 生成配置文件

jupyter notebook --generate-config

## 设置密码

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332133.jpg)

```
from notebook.auth import passwd
passwd()
```

## 配置

vim ~/.jupyter/jupyter_notebook_config.py

```
# 配置默认启动端口，然后云服务器的话注意把端口防火墙开一下
c.NotebookApp.port = 8888

# jupyter notebook默认根目录，引号内输入路径。最好是全英文的路径，并建议给该路径赋权，“chmod 774”
c.NotebookApp.notebook_dir = ""



# 是否允许远程连接
c.NotebookApp.allow_remote_access = True 
c.NotebookApp.ip='*'
c.NotebookApp.open_browser = False
c.NotebookApp.password = u'sha:ce...刚才生成那个密文'  #记得有个u，我第一次没有u好像不行，也没验证

```

# 普通运行

jupyter notebook

# 无后台窗口运行， >后面的路径是我的日志路径，可自行做匹配修改

nohup jupyter notebook >/usr/local/JupyterNotebook/jupyterLog/jupyter.log 2>&1 &

或

nohup jupyter notebook  &

# 使用root权限运行

jupyter notebook ---allow-root

# 指定参数运行

nohup jupyter notebook --ip=0.0.0.0 --port=8080 --no-browser --allow-root >~/usr/local/JupyterNotebook/jupyterLog/jupyter.log 2>&1 &

# 使用无后台运行时，关闭服务可以使用

ps -ef |grep jupyter      查看进程

kill -9 "进程号"          杀死进程

然后就可以用你自己设置的密码登录了

[http://192.168.1.XX:8888](http://192.168.1.XX:8888)

执行官方demo

```
importError: cannot import name 'Figure' from 'bokeh.plotting' 
```

[](http://192.168.100.24:8888/tree?)pip install --upgrade bokeh

就行了

# 为jupyter notebook配置conda环境的三种方法

[https://blog.csdn.net/m0_70222886/article/details/131362704](https://blog.csdn.net/m0_70222886/article/details/131362704)