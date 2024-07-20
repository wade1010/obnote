一、发现服务

发送内容(下面是有问题的,记录下自己踩得坑)



```javascript
{
  "ID": "demo",
  "Name": "demoservice",
  "Tags": [
    "primary",
    "v1"
  ],
  "Address": "127.0.0.1",
  "Port": 18307,
  "Check": {
    "Http": "http://127.0.0.1:18307",//swoft起的http服务
    "Interval": "5s"
  }
}
```



这个check短短折腾了好几个小时，我的consul都是用docker集群部署的，docker里面check，如果设置127.0.0.1肯定是不能访问到我们外部的服务的  改成如下 

 

```javascript
{
  "ID": "demo",
  "Name": "demoservice",
  "Tags": [
    "primary",
    "v1"
  ],
  "Address": "192.168.1.10",
  "Port": 18307,
  "Check": {
    "Http": "http://192.168.1.10:18307",//swoft起的http服务
    "Interval": "5s"
  }
}
```



上面的address最好也要改成具体IP



地址



http://127.0.0.1:8511/v1//agent/service/register





POSTMAN

![](https://gitee.com/hxc8/images7/raw/master/img/202407190746412.jpg)









![](https://gitee.com/hxc8/images7/raw/master/img/202407190746694.jpg)







