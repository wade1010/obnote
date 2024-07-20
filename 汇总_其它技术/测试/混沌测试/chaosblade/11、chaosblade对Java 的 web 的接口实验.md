通用参数



--effect-count string     影响的请求条数

--effect-percent string   影响的请求百分比

--method string           HTTP 请求类型, 例如： GET, POST, or PUT.

--pathinfo string         已废弃

--pid string              java进程号

--process string          java进程名

--querystring string      请求参数，例如http://localhost:8080/dubbodemo/async?name=friend&timeout=2000 中 querystring的值是 name=friend&timeout=2000

--requestpath string      请求 URI，不包含 Context 部分，例如 http://localhost:8080/dubbodemo/async?name=friend&timeout=2000，则 requestpath 的值是 /async，注意要带 /

--servletpath string      已废弃







1、blade create servlet delay

Java web 请求延迟

参数

--time string             延迟时间，单位是毫秒，必填项

--offset string           延迟上下浮动时间，例如 --time 3000 --offset 1000，延迟时间的取值范围是 2000-4000 毫秒



访问 http://localhost:8080/dubbodemo/servlet/path?name=bob 请求延迟 3 秒，影响 2 条请求

```javascript
blade c servlet delay --time 3000 --requestpath /servlet/path --effect-count 2

{"code":200,"success":true,"result":"154c866919172119"}
```





访问请求进行验证。

请求参数是 name=family，延迟 2 秒，延迟时间上下浮动 1 秒，影响范围是 50% 的请求，同时开启 debug 日志用于排查问题，命令如下：



```javascript
blade c servlet delay --time 2000 --offset 1000 --querystring name=family --effect-percent 50 --debug

{"code":200,"success":true,"result":"49236d2406d168f4"}
```



监控 应用进程用户目录/logs/chaosblade/chaosblade.log 日志

![](https://gitee.com/hxc8/images5/raw/master/img/202407172356528.jpg)



可以看到下发了 create 指令并开启 debug 日志。 请求两次 http://localhost:8080/dubbodemo/servlet/path?name=bob ，由于参数 querystring 和下发的命令不匹配，所以没有生效 随后请求两次 http://localhost:8080/dubbodemo/servlet/path?name=family，第一次打印了 Match rule 日志，说明匹配成功，延迟生效；第二次打印了 limited by，说明匹配成功，但是由于 effect-percent 参数的限制，所以场景被限制，此请求没有发生延迟





2、blade create servlet throwCustomException

Java web 请求异常

参数

--exception string           异常类，带全包名，必须继承 java.lang.Exception 或 java.lang.Exception 本身

--exception-message string   指定异常类信息，默认值是 chaosblade-mock-exception



访问 http://localhost:8080/dubbodemo/hello?code=1 请求异常，影响 3 条请求

```javascript
blade c servlet throwCustomException --exception org.springframework.beans.BeansException --exception-message mock-beans-exception --requestpath /hello --effect-count 3

{"code":200,"success":true,"result":"d4a63f4f59f76f4a"}
```

