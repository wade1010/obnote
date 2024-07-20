BMC远程流媒体安装系统

首先在服务器的管理网口插入网线，（这个网口一般是单独的）

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/927f1ed01ae4eee72dd860c72f862b4d927f1ed01ae4eee72dd860c72f862b4d.jpg)

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/68d4527cf497caa90fb55a24857fc05168d4527cf497caa90fb55a24857fc051.jpg)

这里配置地址源需要按回车来修改，配置成网线同一IP段或者局域网可以访问的即可

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/4f94d163b933835c70a5f65e17d45ea14f94d163b933835c70a5f65e17d45ea1.jpg)

假如提示没权限，可以来着修改下用户信息

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/55db76716bf04583f2bd830a8235742d55db76716bf04583f2bd830a8235742d.jpg)

上图，通道编号好像得设置不为0

然后通过浏览器打开IPMI的地址，注意需要加https，http访问不会出现页面

[https://192.168.100.20](https://192.168.100.20)

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE52dd3a451c7b2cedc47bac6adf106118截图.png)

假如登录的时候显示 424 Failed Dependency或者没有权限，还是需要到服务器BIOS里面配置下，如开头4张图的后两张图

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE961d1d7d82bf30c96a21efa9049c46b9image.png)

### 1、使用本地镜像挂载

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE32db51ce80e1305c0a9e8da5a82db06f7d39a81a721cf22474d2dda2bfe320c.png)

选择镜像后点击开始，就出现下图了。

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCEca1d0f8c58d9f867760426959b8c9b8eimage.png)

是然后复制一个页面标签（上面这个切换一下好像就断开了，试过一次没细究）（后来发现，点击重启，然后再操作本地镜像挂载，点击开始，页面停留一会，这个停留应该是让重启的服务器扫描到，但是这个时间停留可能不好把握，所以我觉得我那种复制标签的方法也是不错的）

新页面设置下这个BIOS

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCEa501394f6cfed3bb565db91ae34031b4image.png)

然后重启，一会就能看到BIOS，然后选择启动项，就能看到一个虚拟流媒体

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE0b24aeb6948539e94cbfe6329952c148image.png)

后面就跟装系统一样了

### 2、使用远程挂载

下载对应的挂载软件

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE460cc998e5b96f6565c14353b85e2273截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE679846cc05ee902f0e8de36e90ce95a0截图.png)

这次使用这个系统

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCEee31e48a3eb40169fd533a9d0ca218c9截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCEaf8c27ee53c345245799800e53868e05截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE446fab7a7150310434be0fe00c635456截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE969ab1313d09586f0cc4f8e116ecd2da截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/WEBRESOURCE17b38d8073dd4ac5025d1a8f928e6e91截图.png)

这样就会自动停在BIOS界面

[https://blog.csdn.net/weixin_51211319/article/details/118157791](https://blog.csdn.net/weixin_51211319/article/details/118157791)