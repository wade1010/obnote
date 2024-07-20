HTTP协议

   重要性：无论以后是以webserverice,还是用rest做大型架构，都离不开对http协议的认识

基本上可以简化的说：



```javascript
webservice = http协议+XML
Rest = HTTP协议 + JSON
各种API也是用http+json/XML来实现的
```



http很复杂，学起来也很枯燥，但是非常重要，往大的方面讲 咱们写网站做架构都离不开http,小的方面，做小偷、采集别人的东西也离不开http协议，也要有所了解，学习完http协议 学习ajax也很容易

原理：

```javascript
形象理解http协议
动手试试http协议
http协议3部分介绍
```



什么是协议：



计算机中的协议和现实中的协议一样，一是双份，双方/多方都遵从共同的一个规范，这个规范就是协议，计算机能全世界互通，协议是功不可没，如果没有协议，计算机各说各话，谁也听不懂谁说得话。生活的协议：结婚协议、合同 计算机协议：http、ftp、stmp、pop、sftp、tcp/ip。协议双方达成的共识



HTTP协议的工作流程

当你打开一个页面时发生了什么

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024867.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190024350.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190024675.jpg)



HTTP请求信息和响应信息的格式



请求：



1.请求行



2.请求头信息



3.请求主体信息(可有可没有)



4.头信息结束后跟主体信息之间要空一行





请求行有分3部分



1.请求方法



2.请求路径



3.所用协议



请求方法：GET/POST/HEAD/PUT/DELETE/TRACE/OPTIONS



思考：浏览器可以发送http协议，http协议一定要浏览器来发送吗？



不一定，http既然是一种协议，那么只要满足这个协议，什么工具都能发



GET



Telent 发送http请求



ctrl+中括号

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024021.jpg)





注意：头信息结束后，有一个空行。头信息和主题信息（如果有），需要这个空行做区分，即使没有主题信息，空行也不能少

---

POST

![](D:/download/youdaonote-pull-master/data/Technology/网络编程/协议/HTTPTCP/images/BE8EA05570B240CC84E24AE01EA2603Dimage.png)



POST请求时要指定请求类型和请求长度，如果不进行指定服务器讲无法识别你的请求主体。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024629.jpg)

