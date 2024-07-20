从build_x86里面找到

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354407.jpg)

conda create -n py35 python=3.5

conda activate py35

编辑install

主要是修改python的路径，改成conda的路径,还有就是注释掉sssd.conf.sample相关的

另外我加了句 systemctl daemon-reload  这个是我手动分段执行下面脚本的过程中提示需要这么做。（可能因为这个build_x86整个脚本安装完毕后重启，所以不需要这个systemctl daemon-reload）

```
#!/usr/bin/env bash
DIR=$(cd `dirname $0`; pwd)
cd $DIR

NAME=vcfs

echo -e "\033[34m -------------- Installing vcmp-agent -------------- \033[0m"

[[ ! -d "/opt/${NAME}/vcmp-agent" ]] &&  mkdir -p /opt/${NAME}/vcmp-agent

# 安装虚拟环境 
/home/bob/miniconda3/envs/py3.5/bin/python -m venv /opt/${NAME}/vcmp-agent/runenv
source /opt/${NAME}/vcmp-agent/runenv/bin/activate
pip install --find-links=$DIR/packages --no-index -r $DIR/vcmp-agent/src/requirements.txt --ignore-installed

cp -ap $DIR/vcmp-agent/src/ /opt/${NAME}/vcmp-agent/

cp -a $DIR/vcmp-agent/script/vcmp-agent.service /etc/systemd/system/
cp -a $DIR/vcmp-agent/script/agent-worker.service /etc/systemd/system/

chmod +x /opt/${NAME}/vcmp-agent/src/agent/config/beat-start-pre.sh
cp -a $DIR/vcmp-agent/script/agent-beat.service /etc/systemd/system/

systemctl enable vcmp-agent.service
systemctl enable agent-worker.service
systemctl enable agent-beat.service

systemctl set-property vcmp-agent.service MemoryLimit=2G
systemctl set-property agent-worker.service MemoryLimit=2G
systemctl set-property agent-beat.service MemoryLimit=2G

systemctl daemon-reload

systemctl restart vcmp-agent.service
systemctl restart agent-worker.service
systemctl restart agent-beat.service

# 将sample文件移至对应目录
#cp -a $DIR/vcmp-agent/src/sample/sssd.conf.sample /etc/sssd/sssd.conf
#chmod 600 /etc/sssd/sssd.conf

```

后来发现这样的话，pycharm启动那个vcmp-agent的代码，发现修改不生效（我修改的代码如下图）

![](https://gitee.com/hxc8/images6/raw/master/img/202407182354704.jpg)

全都改成本地启动

#### 启动agent-beat.service  注意命令里面的 workdir

export C_FORCE_ROOT=true

```
celery beat  -A celery_app --workdir=./src/agent --loglevel=info --logfile=/var/log/platform/vcmp-agent/agent-beat.log --pidfile=/var/run/agent-beat.pid
```

#### 启动agent-worker.service

export C_FORCE_ROOT=true

```
celery worker  -A celery_app --autoscale=10,5 --without-heartbeat --without-gossip --without-mingle --workdir=./src/agent --pidfile=/var/run/agent-worker.pid
```