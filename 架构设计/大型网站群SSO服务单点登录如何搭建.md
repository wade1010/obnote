

![](https://gitee.com/hxc8/images7/raw/master/img/202407190747747.jpg)





强拆阿里系单点登录流程



浏览器通过维护sessionid，标识我当前的这样-一个会话

比如你的中间件是，www.taobao.com  tomcat

我后端服务的sessionlD也叫会话ID，我怎么维护这个id是同一个id



做单点登录的核心问题?



转变成了如何去多个域下面共享同样一份 sessionID





同域



同父域



跨域



![](D:/download/youdaonote-pull-master/data/Technology/架构设计/images/CB23BB3682FB43D683A2A3F22D065418image.png)







![](https://gitee.com/hxc8/images7/raw/master/img/202407190747227.jpg)





www.taobao.com

login.taobao.com

在同一个父域下:





跨域登录: www.tmal.com

他是如何判断出来当前我这个用户已经登录.







![](https://gitee.com/hxc8/images7/raw/master/img/202407190747172.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190747582.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190747850.jpg)



可以用Redis 作为会话管理中心









主要代码



登录



取得是顶级域

![](https://gitee.com/hxc8/images7/raw/master/img/202407190747173.jpg)





登录后另一个域名页面刷新



![](D:/download/youdaonote-pull-master/data/Technology/架构设计/images/7BBD44B9A6ED4D40A3316F9D59067252image.png)



如果发现相等就回调 addcookie的接口 把 之前登录域名的会话ID拿过来 写到本地域名会话里面

![](https://gitee.com/hxc8/images7/raw/master/img/202407190747851.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190747178.jpg)







