1、blade create docker cpu

容器内 CPU 负载实验场景，同基础资源的 CPU 场景



blade create docker cpu load 容器内 CPU 负载场景



参数

--blade-override           是否覆盖容器内已有的 chaosblade 工具，默认是 false，表示不覆盖，chaosblade 在容器内的部署路径为 /opt/chaosblade

--blade-tar-file string    指定本地 chaosblade-VERSION.tar.gz 工具包全路径，用于拷贝到容器内执行

--container-id string      目标容器 ID

--docker-endpoint string   Docker server 地址，默认为本地的 /var/run/docker.sock



```javascript
对 container id 是 5239e26f6329 的做 CPU 使用率 80% 的实验场景，执行命令如下：
blade create docker cpu fullload --cpu-percent 80 --blade-tar-file /root/chaosblade-0.4.0.tar.gz --container-id 5239e26f6329

执行成功会返回 {"code":200,"success":true,"result":"0a47bb2f75dc71ab"} 可在本机或者容器内使用 top 命令验证 CPU 使用率：

%Cpu(s): 22.7 us, 57.2 sy, 0.0 ni, 20.1 id, 0.0 wa, 0.0 hi, 0.0 si, 0.0 st

```





2、blade create docker network



持的网络场景命令如下：

blade create docker network delay 容器网络延迟

blade create docker network loss 容器网络丢包

blade create docker network dns 容器内域名访问异常



参数

--container-id string      目标容器 ID

--docker-endpoint string   Docker server 地址，默认为本地的 /var/run/docker.sock

--image-repo string        chaosblade-tool 镜像仓库地址，默认是从 `registry.cn-hangzhou.aliyuncs.com/chaosblade`





3、blade create docker process

支持的进程场景如下：

blade create docker process kill

blade create docker process stop

参数

--blade-override           是否覆盖容器内已有的 chaosblade 工具，默认是 false，表示不覆盖，chaosblade 在容器内的部署路径为 /opt/chaosblade

--blade-tar-file string    指定本地 chaosblade-VERSION.tar.gz 工具包全路径，用于拷贝到容器内执行

--container-id string      目标容器 ID

--docker-endpoint string   Docker server 地址，默认为本地的 /var/run/docker.sock



杀掉容器内 nginx 进程，命令执行如下：

```javascript
blade create docker process kill --process nginx --blade-tar-file /root/chaosblade-0.4.0.tar.gz --container-id ee54f1e61c08
```



4、blade create docker container

删除容器 blade create docker container remove



参数

--container-id string      要删除的容器 ID

--docker-endpoint string   Docker server 地址，默认为本地的 /var/run/docker.sock

--force                    是否强制删除



删除容器后，执行 blade destroy UID 命令不会恢复容器，需要靠容器自身的管理拉起！



删除 container id 是 a76d53933d3f 的容器，命令如下：

```javascript
blade create docker container remove --container-id a76d53933d3f
```



