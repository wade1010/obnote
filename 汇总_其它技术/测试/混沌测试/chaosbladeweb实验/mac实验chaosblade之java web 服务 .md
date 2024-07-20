provider



consumer



下载后启动

```javascript
# 启动 dubbo-provider
nohup java -Djava.net.preferIPv4Stack=true -Dproject.name=dubbo-provider -jar dubbo-provider-1.0-SNAPSHOT.jar > provider.nohup.log 2>&1 &

# 稍等 2 秒，然后启动 dubbo-consumer
nohup java -Dserver.port=8080 -Djava.net.preferIPv4Stack=true -Dproject.name=dubbo-consumer -jar dubbo-consumer-1.0-SNAPSHOT.jar > consumer.nohup.log 2>&1 &
```



>curl 172.16.83.136:8080/hello\?msg=world   显示下面信息表示成功

```javascript
{"date":"Wed Apr 06 18:04:24 CST 2022","msg":"Dubbo Service: Hello world"}
```





接下来我们要使用 blade 工具进行混沌实验，在执行实验前，我们需要先执行 prepare 命令，挂载所需要的 java agent：





>blade prepare jvm --process dubbo-consumer

```javascript
{"code":200,"success":true,"result":"5c56ff82f512b4be"}
```





一、延迟

开始实施混沌实验，我们的需求是 consumer 调用 com.alibaba.demo.HelloService 服务下的 hello 接口延迟 3 秒。 



>blade create dubbo delay --time 3000 --service com.alibaba.demo.HelloService --methodname hello --consumer --process dubbo-consumer

```javascript
{"code":200,"success":true,"result":"caedf90f3e1cc1f3"}
```





验证是否延迟 3 秒

>curl 172.16.83.136:8080/hello\?msg=world



3秒后返回

```javascript
{"date":"Wed Apr 06 18:17:14 CST 2022","msg":"Dubbo Service: Hello world"}
```



停止当前延迟的混沌实验，再次访问 url 验证是否恢复正常：

>blade d caedf90f3e1cc1f3



>curl 172.16.83.136:8080/hello\?msg=world   立马返回





二、异常

