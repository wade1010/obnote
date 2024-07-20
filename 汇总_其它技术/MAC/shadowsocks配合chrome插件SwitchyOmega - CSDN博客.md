

Windows7和win10上shadowsocks配合Chrome插件SwitchyOmega科学上网超详细图文教程

一、shadowsocks客户端的基本使用



主要步骤:

1. 下载客户端，各平台的客户端都有，Windows,linux,mac,android,iOS。

shadowsocks各平台客户端下载地址：http://108.61.186.155/download/?um=1265530



2. 输入shadowsocks账号服务器IP、端口、密码、加密方式。

    **如果需要高速shadowsocks账号，推荐一家速度非常快的付费shadowsocks帐号网站，网址是：http://108.61.186.155/?um=1265530。



3. 启用系统代理，代理模式选择pac模式（这个模式就是自动代理模式，就是根据pac规则自动识别网站是否被墙而选择是否经过代理，这个模式的好处是访问国内网站不经过代理，不会影响速度）如果pac模式下有些被墙的网站还是不能访问，可以启用全局代理，即访问所有网址都会通过代理。



以windows系统为例，其他系统类似：



1.双击打开后，如下图，设置代理服务器的信息





2.启用代理





完成以上步骤已经能科学上网了，下面是配合Chrome插件的进阶教程。

******************************************************

如果我访问的某个国外网址被墙了，但是pac代理规则里没有，那么这时只能在ss客户端切换到全局模式。

这样毕竟有点麻烦，有没有更好的方法呢？请看下面的部分。

******************************************************



二、shadowsocks配合SwitchyOmega科学上网



SwitchyOmega是chrome下非常好用的代理管理插件。

SwitchyOmega插件下载地址：http://108.61.186.155/static/dl/Proxy-SwitchyOmega_v2.3.22.crx 下载下来的是.crx后缀名文件



1.在chrome下安装SwitchyOmega插件。



步骤：打开chrome的设置>扩展程序，然后把插件拖进来就行了。



step 1：







step 2：







step 3：







2.设置SwitchyOmega。



直接导入我设置好的配置文件（下载链接：http://108.61.186.155/static/dl/OmegaOptions.bak 后缀名是bak）。







3.使用SwitchyOmega



下图中的auto switch相当于ss客户端的PAC模式,shadowsocks相当于全局模式。

下图就是通过auto switch访问谷歌。







重点来了，就是如何把想要通过代理的网址加入到代理列表，有2种方法。



方法1：

下图1就是在auto switch模式下，点击那个插件的图标，点击那个网址，这里以github.com为例，然后点击shadowsocks就把当前网址加入到shadowsocks代理中，想不经过代理就选择直接连接。用ip.cn这个网址试的话，经过代理和不经过代理的显示地址不一样的，自行尝试。上述方式只是单次起作用，下次打开浏览器又做上述步骤，永久加入代理规则看方法2。





方法2：



在auto switch模式下，点击那个插件的图标，点击那个网址，这里以ip.cn为例，要永久加入代理规则可以点击添加条件，选择shadowsocks就行了，看图2，代理服务器在新加坡，所以就显示新加坡。





手动配置SwitchyOmega方法：



这部分有兴趣可以看下，上面的bak文件就是根据这里的设置生成的。



step 1：





step2：







step 3：









规则列表网址:



https://raw.githubusercontent.com/gfwlist/gfwlist/master/gfwlist.txt



其他平台的shadowsocks使用方法：



1、Windows使用教程，链接：http://108.61.186.155/support/help/?type=windows&um=1265530

2、Mac使用教程，链接：http://108.61.186.155/support/help/?type=mac&um=1265530

3、iOS（iPhone）平台使用教程，链接：http://108.61.186.155/support/help/?type=iphone&um=1265530

4、安卓平台使用教程，链接：http://108.61.186.155/support/help/?type=android&um=1265530

