blade server start -h

```javascript
Start server mode, exposes web services. Under the mode, you can send http request to trigger experiments

Usage:
  blade server start

Aliases:
  start, s

Examples:
blade server start --port 8000

Flags:
  -h, --help          help for start
  -i, --ip string     service ip address, default value is *
  -n, --nohup         used by internal
  -p, --port string   service port (default "9526")

Global Flags:
  -d, --debug   Set client to DEBUG mode
```



blade server start --port 8878



```javascript
success, listening on :8878
```





# CPU 负载 50% 场景



curl "http://localhost:8878/chaosblade?cmd=create%20cpu%20load%20--cpu-percent%2050"

```javascript
{"code":200,"success":true,"result":"9accf483cb42e255"}
```



# 销毁实验场景



curl "http://localhost:8878/chaosblade?cmd=destroy%209accf483cb42e255"

```javascript
{"code":200,"success":true,"result":{"action":"fullload","flags":{"cpu-percent":"50"},"target":"cpu"}}
```



