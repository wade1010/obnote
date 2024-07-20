初识python框架Sanic

[https://sanic.dev/zh/](https://sanic.dev/zh/)

## 默认状态码(Default Status)

响应的默认 HTTP 状态码是 200，如果您需要更改状态码，可以通过下面的方式进行更改：

```
@app.post("/")
async def create_new(request):
    new_thing = await do_create(request)
    return json({"created": True, "id": new_thing.thing_id}, status=201)
```

不能debug

非debug能启动

debug就报错

```
[2022-10-27 16:20:56 +0800] [74575] [INFO] Sanic v22.9.0
[2022-10-27 16:20:56 +0800] [74575] [INFO] Goin' Fast @ http://127.0.0.1:1338
[2022-10-27 16:20:56 +0800] [74575] [INFO] mode: production, w/ 4 workers
[2022-10-27 16:20:56 +0800] [74575] [INFO] server: sanic, HTTP/1.1
[2022-10-27 16:20:56 +0800] [74575] [INFO] python: 3.8.13
[2022-10-27 16:20:56 +0800] [74575] [INFO] platform: macOS-10.15.7-x86_64-i386-64bit
[2022-10-27 16:20:56 +0800] [74575] [INFO] packages: sanic-routing==22.8.0
[2022-10-27 16:21:10 +0800] [74575] [ERROR] Not all workers are ack. Shutting down.
[2022-10-27 16:21:10 +0800] [74575] [ERROR] Experienced exception while trying to serve
Traceback (most recent call last):
  File "/usr/local/anaconda3/envs/demo38/lib/python3.8/site-packages/sanic/mixins/startup.py", line 851, in serve
    manager.run()
  File "/usr/local/anaconda3/envs/demo38/lib/python3.8/site-packages/sanic/worker/manager.py", line 63, in run
    self.monitor()
  File "/usr/local/anaconda3/envs/demo38/lib/python3.8/site-packages/sanic/worker/manager.py", line 99, in monitor
    self.wait_for_ack()
  File "/usr/local/anaconda3/envs/demo38/lib/python3.8/site-packages/sanic/worker/manager.py", line 139, in wait_for_ack
    sys.exit(1)
SystemExit: 1
[2022-10-27 16:21:10 +0800] [74575] [INFO] Server Stopped
```