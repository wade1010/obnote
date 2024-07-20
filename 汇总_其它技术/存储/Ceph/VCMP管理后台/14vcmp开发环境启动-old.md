### 1、启动docker的mariadb

### 2、启动vcmp

进入根目录

conda activate vcmp (视情况决定是否执行)

##### vcmp-beat

###### 导入环境变量

```
export C_FORCE_ROOT=true DJANGO_READ_DOT_ENV_FILE=true
```

###### 启动vcmp-beat

```
python web/manage-dev.py celery beat --logfile=/var/log/platform/vcmp/vcmp-beat.log --pidfile=/var/run/vcmp-beat.pid
```

##### vcmp-worker

> 可以先执行下停止，防止以前启动未停止  或者ps aux | grep celeryapp 能看到结果就不执行这一步


**cd  web**

###### **导入环境变量**

```
export C_FORCE_ROOT=true DJANGO_READ_DOT_ENV_FILE=true CELERY_APP="celeryapp" CELERYD_NODES="vcmp vcmpbeat" CELERYD_OPTS="-Q:vcmp vcmp -Q:vcmpbeat vcmpbeat --verbose" CELERY_BIN="/home/bob/miniconda3/envs/vcmp/bin/celery" CELERYD_PID_FILE="/var/run/vcmp-worker.%n.pid" CELERYD_LOG_FILE="/dev/null" CELERYD_LOG_LEVEL="INFO"
```

###### **启动vcmp-worker**

```
/bin/bash -c '${CELERY_BIN} multi start ${CELERYD_NODES} --autoscale=10,5 --without-heartbeat --without-gossip --without-mingle -A ${CELERY_APP} --pidfile=${CELERYD_PID_FILE}   --logfile=${CELERYD_LOG_FILE} --loglevel=${CELERYD_LOG_LEVEL} ${CELERYD_OPTS}'
```

##### pycharm启动vcmp

##### 其它：

###### 停止vcmp-worker

```
/bin/bash -c '${CELERY_BIN} multi stopwait ${CELERYD_NODES} --pidfile=${CELERYD_PID_FILE}'
```

###### 重启vcmp-worker

```
/bin/bash -c '${CELERY_BIN} multi restart ${CELERYD_NODES} --autoscale=10,5 --without-heartbeat --without-gossip --without-mingle  -A ${CELERY_APP} --pidfile=${CELERYD_PID_FILE} --logfile=${CELERYD_LOG_FILE} --loglevel=${CELERYD_LOG_LEVEL} ${CELERYD_OPTS}'
```

### 3、启动vcmp-agent

进入根目录

```
export C_FORCE_ROOT=true
```

##### 启动agent.service

```
celery beat  -A celery_app --workdir=./src/agent --loglevel=info --logfile=/var/log/platform/vcmp-agent/agent-beat.log --pidfile=/var/run/agent-beat.pid
```

##### 启动agent-worker.service

再开一个终端，进入根目录

```
export C_FORCE_ROOT=true
```

```
celery worker  -A celery_app --autoscale=10,5 --without-heartbeat --without-gossip --without-mingle --workdir=./src/agent --pidfile=/var/run/agent-worker.pid
```

##### pycharm启动vcmp-agent

### 4、启动前端项目

npm run dev

### 5、启动influxdb

一般不需要操作

systemctl restart influxd

### 6、启动diamond

##### 一般使用重启就行或者不操作

systemctl restart diamond

##### 查看日志

tail -f /var/log/platform/diamond/diamond.log

tail -f  /var/log/diamond/archive.log

### 7、查看日志

**如果修改了源码之后，尽量重启下vcmp-beat和vcmp-worker，否则可能出现修改源码在定时任务里面不生效**

启动mobxterm

开启一个新终端

cd /var/log/platform/vcmp

tail -f vcmp.log

开启一个新终端

cd /var/log/platform/vcmp

tail -f vcmp-beat.log

开启一个新终端

cd /var/log/platform/vcmp-agent

tail -f agent-worker.log

开启一个新终端

cd /var/log/platform/vcmp-agent

tail -f vcmp-agent.log   

或者只看Error    

 tail -f vcmp-agent.log|grep ERROR