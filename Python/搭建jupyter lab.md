conda activate llama2

conda install jupyterlab

jupyter lab --generate-config

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCE55268949fca9390932a3c2fea3570a98截图.png)

## 设置密码

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCE5c228b58e2c1e4aabe370aa3216485ad截图.png)

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

![](D:/download/youdaonote-pull-master/data/Technology/Python/images/WEBRESOURCE21f41c71fc6142cac71e06279cdddc87截图.png)