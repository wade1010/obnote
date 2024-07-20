blade server

后台启动 blade，会暴露出 web 服务，上层可通过 http 调用



在 server 模式下，blade 程序会对外暴露 web 服务，上层可通过 http 请求调用，请求格式是 chaosblade?cmd=具体命令，例如执行 CPU 满载，

则请求是 chaosblade?cmd=create cpu fullload



参数

start       启动 server 模式, 暴露 web 服务

stop        停止 server 模式, 关闭 web 服务



start 命令参数

```javascript
-p, --port string   服务端口号，默认是 9526
```





```javascript
# 启动 server 模式，服务端口是 8080
blade server start --port 8080
success, listening on 8080

# 触发 CPU 负载 50% 场景
curl "http://xxx.xxx.xxx.xxx:8080/chaosblade?cmd=create%20cpu%20load%20--cpu-percent%2050"

{"code":200,"success":true,"result":"e08a64a9af02c393"}

# 销毁实验场景
curl "http://xxx.xxx.xxx.xxx:8080/chaosblade?cmd=destroy%20e08a64a9af02c393"

# 停止 blade server
blade server stop

{"code":200,"success":true,"result":"pid is 12619"}
```

