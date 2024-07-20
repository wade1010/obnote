#### vcmp-beat.service

export C_FORCE_ROOT=true DJANGO_READ_DOT_ENV_FILE=true

python web/manage-dev.py celery beat --logfile=/var/log/platform/vcmp/vcmp-beat.log --pidfile=/var/run/vcmp-beat.pid

### vcmp-worker.service

cd web   (注意要进入web目录，要不执行报错，ModuleNotFoundError: No module named 'celeryapp'，这一点可以通过下面这个vcmp-worker.service配置图看出来)

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352737.jpg)

```
export C_FORCE_ROOT=true DJANGO_READ_DOT_ENV_FILE=true CELERY_APP="celeryapp" CELERYD_NODES="vcmp vcmpbeat" CELERYD_OPTS="-Q:vcmp vcmp -Q:vcmpbeat vcmpbeat --verbose" CELERY_BIN="/home/bob/miniconda3/envs/vcmp/bin/celery" CELERYD_PID_FILE="/var/run/vcmp-worker.%n.pid" CELERYD_LOG_FILE="/dev/null" CELERYD_LOG_LEVEL="INFO"
```

启动脚本

```
/bin/bash -c '${CELERY_BIN} multi start ${CELERYD_NODES} --autoscale=10,5 --without-heartbeat --without-gossip --without-mingle -A ${CELERY_APP} --pidfile=${CELERYD_PID_FILE}   --logfile=${CELERYD_LOG_FILE} --loglevel=${CELERYD_LOG_LEVEL} ${CELERYD_OPTS}'
```

![](https://gitee.com/hxc8/images6/raw/master/img/202407182352968.jpg)

停止脚本

```
/bin/bash -c '${CELERY_BIN} multi stopwait ${CELERYD_NODES} --pidfile=${CELERYD_PID_FILE}'
```

重新加载

```
/bin/bash -c '${CELERY_BIN} multi restart ${CELERYD_NODES} --autoscale=10,5 --without-heartbeat --without-gossip --without-mingle  -A ${CELERY_APP} --pidfile=${CELERYD_PID_FILE} --logfile=${CELERYD_LOG_FILE} --loglevel=${CELERYD_LOG_LEVEL} ${CELERYD_OPTS}'
```

### 下面是从线上环境的配置

### agent-beat.service

/opt/vcfs/vcmp-agent/runenv/bin/celery beat -A celery_app --workdir=/opt/vcfs/vcmp-agent/src/agent/ --loglevel=info --logfile=/var/log/platform/vcmp-agent/agent-beat.log --pidfile=/var/run/agent-beat.pid

### agent-worker.service

/opt/vcfs/vcmp-agent/runenv/bin/celery worker -A celery_app --autoscale=10,5 --without-heartbeat --without-gossip --without-mingle --workdir=/opt/vcfs/vcmp-agent/src/agent/ --pidfile=/var/run/agent-worker.pid

Running a worker with superuser privileges when the

worker accepts messages serialized with pickle is a very bad idea!

If you really want to continue then you have to set the C_FORCE_ROOT

environment variable (but please think about this before you do).

User information: uid=0 euid=0 gid=0 egid=0

[root@node1 ~]# export C_FORCE_ROOT=true