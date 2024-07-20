引言

         单点登录有许多开发商提供解决方案，本文以yale大学SSO开源项目CAS为例，介绍单点登录实现机制。

术语解释

SSO－Single Sign On，单点登录

TGT－Ticket Granting Ticket，用户身份认证凭证票据

ST－Service Ticket，服务许可凭证票据

TGC－Ticket Granting Cookie，存放用户身份认证凭证票据的cookie

SSO原理概述

SSO组件主要包含：SSO服务器、SSO客户端。SSO服务器主要负责完成用户认证、提供单点登录服务；SSO客户端部署在应用系统（Web应用端与C/S架构模式应用端），用户请求访问应用系统的受保护资源时，需要将请求转向SSO服务器进行身份认证、单点登录服务相关处理。

SSO服务器接口

| uri | 说明 |
| - | - |
| /login | 凭证请求器，参数如下：<br>service：客户端要访问的应用的标识；<br>renew：如果设置这个参数，sso将被绕过；不支持renew和gateway同时存在，若存在则忽略gateway；<br>gateway：如果设置这个参数，CAS将不再问客户端要凭证。 <br><br>凭证接收器，参数如下：<br>service：客户端要访问的应用的标识；CAS在认证成功后将它的url转发给客户端；<br>warn：在认证转发给其他服务前，客户端必须给予提示。 |
| /logout | 单点退出，释放cas单点登录的session |
| /proxyValidate | SSO服务器验证票据的合法性 |
|  |  |
|  |  |




SSO单点登录主要原理

SSO单点登录访问流程主要有以下步骤：

1. 访问服务：SSO客户端发送请求访问应用系统提供的服务资源。

2. 定向认证：SSO客户端会重定向用户请求到SSO服务器。

3. 用户认证：用户身份认证。

4. 发放票据：SSO服务器会产生一个随机的Service Ticket。

5. 验证票据：SSO服务器验证票据Service Ticket的合法性，验证通过后，允许客户端访问服务。

6. 传输用户信息：SSO服务器验证票据通过后，传输用户认证结果信息给客户端。

7. 单点退出：用户退出单点登录。

SSO单点登录访问总共会涉及以上6个步骤，但用户不同的SSO访问可能只会涉及到几个步骤，我们把SSO访问流程我们分为5种实例情况介绍：登录点首次访问、登录点二次访问、单点首次访问、单点二次访问、单点退出。这5种实例应当可以比较全面地展示SSO访问实现的机制。

注：文中存在以下术语词汇，解释如下。

登录点：首次进入子系统登录的站点在本文中称为登录点。

单点：用户已经登录，而后再访问另一子系统的站点在本文中称为单点。

登录点首次访问：是指首次访问应用系统，进入登录页面的过程。登录点二次访问：是指在上述”登录点“，再次进行正常的访问”登录点“的过程。

单点首次访问：是指已经在上述“登录点”登录后，再首次访问另一个子系统进行访问的过程。单点二次访问：是指已经在上述“单点首次访问"，再次进行正常的访问”单点“的过程。

登录点首次访问

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/8250F8D380774303BB886703819B8A3223246280_1.gif.jpeg)

说明：用户首次访问”登录点“System1，session中没有用户上下文，于是将请求地址包装为service参数，转向SSO服务器”定向认证“，SSO服务器返回登录页面，用户录入用户名、密码等凭证信息，提交给SSO服务器，SSO服务器进行认证，认证成功后，生成TGT，再根据TGT发放票据ST，返回响应给浏览器，浏览器带着票据ST的service参数的请求，请求SSO服务器验证票据，验证票据成功后，返回给浏览器用户信息（通过cas协议约定的xml格式传递数据解析转换成需要的用户信息），设置session用户上下文、cookie中设置TGC。

登录点二次访问

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/FDCB1E3A36B0410C9F9DA496254F833423246280_2.gif.jpeg)

说明：用户在上述“登录点首次访问”登录成功后，再次访问登录点的应用服务时，判断session中已经存在用户上下文，就不再拦截，直接转向到需要访问的目标服务资源。

单点首次访问

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/B82A5DB496374A2FBD71CCC681EB4CF823246280_3.gif.jpeg)

说明：同上述“登录点首次访问”的说明。



单点二次访问

   与“登录点二次访问”相似。

单点退出

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/60F27187AD8A4C61AEEE1EA681F9E72423246280_4.gif.jpeg)



说明：用户单点登录后，有一个全局的过滤器SingleSignOutFilter对访问的安全资源的ticket,sessionid记录到一个映射表，我们暂称其为票据会话映射表。在一个子系统(如system1)执行单点退出"/logout"时，先销毁system1的本地session，再向SSO服务器发送单点退出请求，SSO服务器接到这个请求后，将用户认证票据TGT销毁，清除浏览器cookie中的TGC，再读取票据会话映射表，将其对应的票据ST，session全部销毁。这样用户再访问时各子系统时，是单点退出状态，就需要重新登录。

我们再详细从CAS源码中看看SSO单点退出实现机制。

SSO客户端涉及源码类图如下：

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/9510FEB5C9994ACE9FA7EF2BB9809ACD23246280_5.gif.jpeg)

一般web应用中一般部署在web.xml文件中，单点退出相关配置如下：

|   |
| - |
| <br>                    edu.yale.its.tp.cas.client.session.SingleSignOutHttpSessionListener<br>       <br>   <br>   <br><br>   <br>...<br>       <br>        CasLogoutProxy<br>            /logout<br>           <br>   <br>        SingleSignOutFilter<br>        /\*<br>    |


说明：我们看到单点退出的相关类结构，web.xml配置了单点退出的相关类（1个监听器SingleSignOutHttpSessionListener，2个过滤器SingleSignOutFilter，SimpleServerLogoutHandler）。实现利用了session存储机制，SessionStoreManager是个单例类，用于管理session的存储、删除；SessionMappingStorage是session的存储、删除的执行者，可以看到实际存储的结构是一个artifactId、sessionId为名值对的HashMap表；监听器SingleSignOutHttpSessionListener的作用是session销毁时，调用session管理单例类SessionStoreManager进行session的删除销毁；SingleSignOutFilter的作用有2个：一个是在单点访问拦截安全资源时调用单例类SessionStoreManager存储session，另一个是在单点退出时调用单例类SessionStoreManager删除session；SimpleServerLogoutHandler的作用是将客户端的退出请求转发到SSO服务器端，集中处理做各个子系统的单点退出。

      SSO服务器端单点退出涉及源码类图如下：

![](D:/download/youdaonote-pull-master/data/Technology/RPC/images/B472F342002B456CB0D2DA5D44CEC25F23246280_6.gif.jpeg)

说明：用户发送单点退出请求，转向到SSO服务器的单点退出服务接口/logout，SSO服务器将每次web请求构造一个继承类AbstractWebApplicationService的SimpleWebApplicationServiceImpl的实例，单点退出执行logOutOfService方法，销毁票据、session等处理。