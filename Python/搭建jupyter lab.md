conda activate llama2

conda install jupyterlab

jupyter lab --generate-config

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202407291347846.png)


## 设置密码

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202407291347606.png)


```
from notebook.auth import passwd
passwd()
```

vim ~/.jupyter/jupyter_lab_config.py

```
c.NotebookApp.port = 8888

# jupyter notebook默认根目录，引号内输入路径。最好是全英文的路径，并建议给该路径赋权，“chmod 774”
c.NotebookApp.notebook_dir = "/llm/xxxx/nb/note"



# 是否允许远程连接
c.NotebookApp.allow_remote_access = True
c.NotebookApp.ip='*'
c.NotebookApp.open_browser = False
c.NotebookApp.password = u'sha1:62fab7a55c15:b8b74bd6ead8a7d7b99c3cbc0e5f082c1c54e9fb'

```

nohup jupyter notebook > /llm/xxxx/nb/jupyter.log 2>&1 &

访问就可以了

![image.png](https://gitee.com/hxc8/images10/raw/master/img/202407291348309.png)
