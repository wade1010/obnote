### 1虚拟机安装好各项服务器

每次开发的时候需要关闭下vcmp-agent

```
systemctl stop vcmp-agent
```

也可以顺带关下vcmp，这个不用端口所以不关闭也没事

systemctl stop vcmp

### 2 vscode免密登录

找到windows公钥复制到/root/.ssh/authorized_keys

如果服务器上没有.ssh目录，使用ssh-keygen生成即可

### 3 vscode打开vcmp项目

目录位置为/opt/vcfs/vcmp/web

第一次操作，clone如下

cd /opt/vcfs/vcmp

git clone git@github.com:wade1010/vcfs_vcmp.git web

### **4 vscode打开vcmp-agent项目**

目录位置为/opt/vcfs/vcmp-agent/src

第一次操作，clone如下

cd /opt/vcfs/vcmp-agent

git clone git@github.com:wade1010/vcfs_vcmp_agent.git src

上传

vim .gitignore

```
__pycache__
agent/warn
agent/config/clusterfsfeature.conf
agent/celerybeat-schedule.dir
agent/celerybeat-schedule.dat
agent/celerybeat-schedule.bak
```

```
cd /opt/vcfs/vcmp-agent/src
git config --global init.defaultBranch main
git config --global user.name "yaya"
git config --global user.email "640297@qq.com"
git init
git add .gitignore
git add .
git commit -m "first commit"
git branch -M main
git remote add origin git@github.com:wade1010/vcfs_vcmp_agent.git
git push -u origin main
```

### 5 配置vscode环境

这里最重要了，安装python插件后，要切换版本

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353279.jpg)

切换大概如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353740.jpg)

左键点击上图按钮，选择 “install another version”,点击后等大概几秒会出现一个选择框，我需要python3.5环境，所以选择了2021年最早的版本，由于我是python虚拟环境，所以开始选了2020年最早的一个版本，发现不能选择python的虚拟环境，所以切换为2021年的

感谢这位大佬

[https://blog.csdn.net/weixin_39916966/article/details/125737069](https://blog.csdn.net/weixin_39916966/article/details/125737069)

### 6 配置debug

#### vcmp项目

.vscode/settings.json

```
{
    "python.pythonPath": "/opt/vcfs/vcmp/bin/python"
}
```

.vscode/launch.json

```
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Python: Django",
            "type": "python",
            "request": "launch",
            "env": {"DJANGO_READ_DOT_ENV_FILE":"true"},
            "program": "${workspaceFolder}/manage.py",
            "args": [
                "runserver",
                "0.0.0.0:8001"
            ],
            "django": true
        }
    ]
}
```

#### vcmp-agent项目

.vscode/settings.json

```
{
    "python.pythonPath": "/opt/vcfs/vcmp-agent/runenv/bin/python"
}
```

.vscode/launch.json

```
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Python: Current File",
            "type": "python",
            "request": "launch",
            "program": "${workspaceFolder}/run.py",
            "console": "integratedTerminal"
        }
    ]
}
```

然后就可以F5启动这两个项目

### 7 修改nginx配置

vim /etc/nginx/conf.d/vcmp.conf

注释掉原来的proxy_pass

添加下面代理

proxy_pass [http://127.0.0.1:8001;](http://127.0.0.1:8001;)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182353725.jpg)

查看下配置是否正确

nginx -T

正确的话就重启nginx

systemctl restart nginx

然后就可以打开web页面进行调试了

结束本次笔记