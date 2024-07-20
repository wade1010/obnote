请求

---

请求行（请求方法 路径  协议）

请求头信息（格式为 key:value）

空行

主体（发送的内容 可选）



例



POST /test.php http/1.1

HOST:localhost

Content-type:application/x-www-form-urlencode

Content-length:5



age=3





---



返回



---



响应行 (协议 状态码 状态文本)

响应头信息(格式为key:value)

空行

主体(返回的内容 也可能没有)



例



http/1.1 200 OK

Content-type:text/html

Content-length:6



hello!



---





请求方法：

GET/POST/HEAD/ PUT/DELETE/TRACE/OPTIONS



HEAD:和GET基本一致，只是不返回内容。比如我们只是确认一个内容（比如照片）还正常存在，不需要返回照片的内容，用HEAD比较合适





TRACE ：是你用了代理上网，比如用代理访问new.163.com ，你想看看代理有没有修改你的HTTP请求，你可以用TRACE来测试一下，163.com的服务器会把最后收到的请求返回给你。



OPTIONS:是返回服务器可用的方法。



状态码，状态文字



状态码是用来反应服务器响应情况的.

 最常用：200 OK，404 NOT FOUND   ，状态文字是用来描述状态码的，以便于人观察。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024973.jpg)

一些常用的状态码

   200 - 服务器成功返回网页

   301/2 - 永久/临时重定向

   304 Not Modified - 未修改

   307保存重定向中有用的数据

   失败的状态码：

   404 - 请求的网页不存在

   503- 服务器暂时不可用

   500-服务器内部错误



Telnet模拟 浏览器获取图片缓存

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024399.jpg)



第一步：新建一个header.php页

[php] view plain copy

1. <?php  

1.   

1. header('Location:http://www.baidu.com');//默认是302重定向  

1.   

1. ?>  

第二步：分析

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024773.jpg)

如何制定重定向呢？

指定用301重定向，

```javascript
<?php  
//header('Location:http://www.baidu.com');//默认是302重定向  
header('Location:http://www.baidu.com',true,301);  
?>  
```



![](https://gitee.com/hxc8/images7/raw/master/img/202407190024920.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190024261.jpg)

还有一个问题：

       对于一片新闻，get请求，重定向无所谓，还能看到原来的内容就行。但如果是POST数据，比如表单-->05.php,  05.php重定向->06.php。在06.php中会获取不到数据。

       解决：在05.php中用307保存重定向中有用的数据