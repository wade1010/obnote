Fiddler是一个http协议调试代理工具，它能够记录并检查所有你的电脑和互联网之间的http通讯，设置断点，查看所有的“进出”Fiddler的数据。 Fiddler 要比其他的网络调试器要更加简单，因为它不仅仅暴露http通讯还提供了一个用户友好的格式。

1、PC端安装Fiddler

下载地址：Fiddler.exe，http://www.telerik.com/download/fiddler

[Fiddler2.zip](attachments/3C8ACDC11776451BA59BFC14E9990CF9Fiddler2.zip)



2、 配置PC端Fiddler和手机

(1) 配置Fiddler允许监听https

打开Fiddler菜单项Tools->Fiddler Options，选中decrypt https traffic和ignore server certificate errors两项，如下图：



![fiddler https options](https://gitee.com/hxc8/images7/raw/master/img/202407190803679.jpg)

第一次会提示是否信任fiddler证书及安全提醒，选择yes，之后也可以在系统的证书管理中进行管理。

(2) 配置Fiddler允许远程连接

如上图的菜单中点击connections，选中allow remote computers to connect，默认监听端口为8888，若被占用也可以设置，配置好后需要重启Fiddler，如下图：



![fiddler remote connect](https://gitee.com/hxc8/images7/raw/master/img/202407190803763.jpg)

(3) 配置手机端

Pc端命令行ipconfig查看Fiddler所在机器ip，本机ip为10.0.4.37，如下图



![ipconfig](https://gitee.com/hxc8/images7/raw/master/img/202407190803951.jpg)

打开手机连接到同一局域网的wifi，并修改该wifi网络详情(长按wifi选择->修改网络)->显示高级选项，选择手动代理设置，主机名填写Fiddler所在机器ip，端口填写Fiddler端口，默认8888，如下图：



![android network proxy](https://gitee.com/hxc8/images7/raw/master/img/202407190803003.jpg)

这时，手机上的网络访问在Fiddler就可以查看了，如下图微博和微信的网络请求：



![微信抓数据包](https://gitee.com/hxc8/images7/raw/master/img/202407190803114.jpg)

可以双击上图某一行网络请求，右侧会显示具体请求内容(Request Header)和返回内容(Response Header and Content)，如下图：



![微博网络拦截](https://gitee.com/hxc8/images7/raw/master/img/202407190803208.jpg)





可以发现Fiddler可以以各种格式查看网络请求返回的数据，包括Header, TextView(文字), ImageView(图片), HexView(十六进制)，WebView(网页形式), Auth(Proxy-Authenticate Header), Caching(Header cache), Cookies, Raw(原数据格式), JSON(json格式), XML(xml格式)很是方便。

停止网络监控的话去掉wifi的代理设置即可，否则Fiddler退出后手机就上不网了哦。

如果需要恢复手机无密码状态，Android端之后可以通过系统设置-安全-受信任的凭据-用户，点击证书进行删除或清除凭据删除所有用户证书，再设置密码为无。

如果只需要监控一个软件，可结合系统流量监控，关闭其他应用网络访问的权限。



利用fiddler抓取Android app数据包

做Android开发的朋友经常需要做网络数据的获取和提交表单数据等操作，然而对于调试程序而言，很难知道我们的数据到底是以怎样的形式发送的，是否发送成功，如果发送失败有是什么原因引起的。fiddler工具为我们提供了很方便的抓包操作，可以轻松抓取浏览器的发出的数据，不管是手机APP，还是web浏览器，都是可以的。

fiddler的工作原理

fiddler是基于代理来实现抓取网络数据包的工作的，当我们开启fiddler以后，fiddler会将我们的浏览器的代理默认进行更改为 127.0.0.1 端口是8888，这时fiddler的默认端口，也就是说我们发送的每一个请求和收到的每一个响应都会先经过fiddler，这样就实现了抓取数据包的工作。

路径：选项?>高级设置?>更改代理服务器设置?>局域网设置?>高级



![技术分享](http://www.111cn.net/get_pic/2015/07/15/20150715100501845.png 过滤所有图片的请求&lt;/p&gt;&lt;p&gt;8.控制fiddler是否工作&lt;/p&gt;&lt;p&gt;在fiddler的左下方有一个按钮，是用来控制fiddler是否作为代理服务器来抓取浏览器发送和接收的包的。当我点击一下该按钮，如果该按钮显示则表示fiddler处于工作状态，如果该按钮隐藏，表示fiddler不在作为代理服务器。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;center&gt;&lt;img  alt=)

9.回话面板说明：



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190803474.jpg)

session会话的分析

这里我随便选择一个会话来进行简单的分析。



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190803612.jpg)

替换服务器端返回的数据

利用”autoresponser”可以替换服务器端返回的文件，当调试的时候需要替换服务器端返回的数据的时候，比如一个已经上线的项目，不可能真正的替换器某一个文件，我们可以这样来操作



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190803867.jpg)

从图片当中，可以很清晰的看出，当我再次加载该会话的时候，会显示之前设置好的404代理。

如果需要设置不同的文件代理，也是可以的。比如对于该会话，原本服务器端返回的内容如下图：



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190803126.jpg)

由于该session返回的是一个图片类型的，所以我选择ImageView这个选项卡，可以看到此时返回的图片的样子，那么如果需要用本地的文件代理该返回的内容，和之前的操作步骤都是一样的，只是在选择代理的时候选择本地文件即可，如下图：



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190803370.jpg)

这次，我选择了一个本地的文件作为代理，此时当我再次重新请求该会话的时候，会返回本地的文件：



![技术分享](D:/download/youdaonote-pull-master/data/Technology/工具/Fiddler/images/70F33369C7CC4F4A83AA3F817054D07F20150607224744948.png)

可以看出这个时候该会话返回的内容已经是我本地的代理了。

fiddler网络限速

fiddler还为我们提供了一个很方便的网络限速的功能，通过网络限速的功能，可以来模拟用户的一些真实环境。fiddler提供了网络限速的插件，我们可以在他的官网下载：http://www.telerik.com/fiddler/add-ons



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190803296.jpg)

点击”download”,下载完成之后，点击安装，需要重新启动fiddler，在重新启动fiddler之后，可以看到fiddler的工具栏选项卡，多出了一个FiddlerScript选项。



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190803344.jpg)

比如我需要在请求之前延迟一段时间，可以这样做：



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804175.jpg)

在onBeforeRequest方法中加入这样一段代码”oSession[“request-trickle-delay”] = “3000”;”,那么如果需要在服务端响应之间做延迟只需要将”oSession[“request-trickle-delay”] = “3000”;”中的request替换成response即可。

利用fiddler抓取Android app数据包

终于到了今天的主题了，如何利用fiddler抓取Android app数据包，其实也是很简单的，只需要稍微配置一下就可以了。由于fiddler默认是抓取http协议的数据包，我们需要其能够抓取https这样的加密数据包，抓取Android app数据包，需要做如下配置：

1.配置fiddler

点击工具栏选项”tools?>FiddlerOptions”

配置https：



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804673.jpg)

配置远程连接：



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804713.jpg)

这些配置完成之后，一定要重新启动fiddler。

可以看到fiddler的默认端口是8888，我们可以现在浏览器上输入”http://127.0.0.1:8888”



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804113.jpg)

到这里为止我们的fiddler就配置完成了，接下来需要配置手机上的无线网络。

2.手机无线网络配置

注意：如果需要fiddler抓取Android app上的数据包，那么两者必须在同一个无线网络中。(同时，必要时请关闭电脑的防火墙)

在手机的无线网络配置之前，必须要首先知道fiddler所在主机的ip地址：



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804731.jpg)

可以看到我的fiddler所在主机，也就是我的电脑在无线网中的ip地址是192.168.1.109

打开手机设置中的无线网络界面，进行如下四步操作：

选中连接的网络，点击修改网络



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804014.jpg)

点击高级选项



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804135.jpg)

代理—>手动



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804331.jpg)

输入代理服务器的ip，也就是我们fiddler所在主机的ip地址，和端口，fiddler默认的端口是8888，IP选项设置为”DHCP”



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804606.jpg)

点击保存，此时手机端就配置成功了，打开fiddler，使用打开网易新闻客户端。



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804801.jpg)

此时可以看到fiddler抓取的网易app发送和接收的相关数据包。



![技术分享](https://gitee.com/hxc8/images7/raw/master/img/202407190804258.jpg)

ok，左侧是我们的所有会话，我随机的选中一个会话，该会话是image类型的，查看该会话的内容，是我们网易新闻的头条上的图片。

注意：

1.关闭电脑的防火墙

2.如果需要抓取手机app的数据包，需要手机和电脑在都连接同一个无线网络

3.抓完包以后将fiddler关闭(提高访问网络的速度)同时将手机上的代理关闭 (如果不关闭代理，当fiddler关闭，或者是两者连接的不是同一无线网络，手机会不能正常的访问网络)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190804569.jpg)