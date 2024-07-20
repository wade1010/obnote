宿主机下载 chaosblade安装包

这里是chaosblade-1.5.0-linux-amd64.tar.gz



```javascript
blade create docker cpu load -h
The CPU load experiment scenario in docker container is the same as the CPU scenario of basic resources

Usage:
  blade create docker cpu fullload

Aliases:
  fullload, fl, load

Examples:
# Create a CPU full load experiment in the container
blade create docker cpu load --chaosblade-release /root/chaosblade-0.6.0.tar.gz --container-id ee54f1e61c08

#Specifies two random kernel's full load in the container
blade create docker cpu load --cpu-percent 60 --cpu-count 2 --chaosblade-release /root/chaosblade-0.6.0.tar.gz --container-id ee54f1e61c08

# Specifies that the kernel is full load with index 0, 3, and that the kernel's index starts at 0
blade create docker cpu load --cpu-list 0,3 --chaosblade-release /root/chaosblade-0.6.0.tar.gz --container-id ee54f1e61c08

# Specify the kernel full load of indexes 1-3
blade create docker cpu load --cpu-list 1-3 --chaosblade-release /root/chaosblade-0.6.0.tar.gz --container-id ee54f1e61c08

# Specified percentage load in the container
blade create docker cpu load --cpu-percent 60 --chaosblade-release /root/chaosblade-0.6.0.tar.gz --container-id ee54f1e61c08

Flags:
      --chaosblade-override         Override the exists chaosblade tool in the target container or not, default value is false
      --chaosblade-release string   The pull path of the chaosblade tar package, for example, --chaosblade-release /opt/chaosblade-0.4.0.tar.gz
      --climb-time string           durations(s) to climb
      --container-id string         Container id, when used with container-name, container-id is preferred
      --container-name string       Container name, when used with container-id, container-id is preferred
      --cpu-count string            Cpu count
      --cpu-list string             CPUs in which to allow burning (0-3 or 1,3)
      --cpu-percent string          percent of burn CPU (0-100)
      --docker-endpoint string      Docker socket endpoint
  -h, --help                        help for fullload
      --image-repo string           Image repository of the chaosblade-tool
      --image-version string        Image version of the chaosblade-tool
      --timeout string              set timeout for experiment

Global Flags:
  -a, --async             whether to create asynchronously, default is false
  -d, --debug             Set client to DEBUG mode
  -e, --endpoint string   the create result reporting address. It takes effect only when the async value is true and the value is not empty
  -n, --nohup             used to internal async create, no need to config
      --uid string        Set Uid for the experiment, adapt to docker and cri
```



docker rundocker pull mysql:latest



docker run -itd --name mysql-test -p 13306:3306 -e MYSQL_ROOT_PASSWORD=123456 mysql



```javascript
docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                                NAMES
33edc28ca53d        mysql               "docker-entrypoint..."   About an hour ago   Up About an hour    33060/tcp, 0.0.0.0:13306->3306/tcp   mysql-test
```



docker inspect mysql



docker stats



另外启动一个终端

blade create docker cpu load -h



blade create docker cpu load --chaosblade-release ./chaosblade-1.5.0-linux-amd64.tar.gz --container-id 33edc28ca53d





---

docker top 33edc28ca53d

```javascript
docker top 33edc28ca53d     
UID                 PID                 PPID                C                   STIME               TTY                 TIME                CMD
polkitd             9034                9016                0                   10:56               pts/1               00:00:25            mysqld
root                12152               1                   99                  12:49               ?                   00:16:01            /opt/chaosblade/bin/chaos_burncpu --nohup --cpu-count 2 --cpu-percent 100 --climb-time 0
```



---



blade status --type create --status Success



docker stats



blade d 7bb6f0bc458c9c1c



docker top 33edc28ca53d

```javascript
docker top 33edc28ca53d                                                                                                                                                                                  1 ↵
UID                 PID                 PPID                C                   STIME               TTY                 TIME                CMD
polkitd             9034                9016                0                   10:56               pts/1               00:00:25            mysqld
```







blade create docker process kill --process mysql --chaosblade-release ./chaosblade-1.5.0-linux-amd64.tar.gz --container-id 33edc28ca53d







删除容器



blade create docker container remove -h



```javascript
blade create docker container remove -h                                                                                                                                                                  1 ↵
remove a container

Usage:
  blade create docker container remove

Aliases:
  remove, rm

Examples:
# Delete the container id that is a76d53933d3f",
blade create docker container remove --container-id a76d53933d3f

Flags:
      --container-id string      Container id, when used with container-name, container-id is preferred
      --container-name string    Container name, when used with container-id, container-id is preferred
      --docker-endpoint string   Docker socket endpoint
      --force                    force remove
  -h, --help                     help for remove
      --timeout string           set timeout for experiment

Global Flags:
  -a, --async             whether to create asynchronously, default is false
  -d, --debug             Set client to DEBUG mode
  -e, --endpoint string   the create result reporting address. It takes effect only when the async value is true and the value is not empty
  -n, --nohup             used to internal async create, no need to config
      --uid string        Set Uid for the experiment, adapt to docker and cri
```



