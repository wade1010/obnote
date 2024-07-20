php 开发过程中，一个项目比较大的话，就需要很多人共同来完成。那么怎样来管理之间的相互配合，分工等呢？？那么SVN这个神器就有用处了。SVN:代码版本管理软件。更多svn详细信息请查阅相关文档，这里就不详细介绍了。

工具/原料

-  Zend Studio9开发工具

- update_1.8.x插件

-  如果没有zendstudio工具，参考http://jingyan.baidu.com/article/c275f6bac3502de33d7567a2.html

-  svn插件地址：http://subclipse.tigris.org/update_1.8.x

方法/步骤

1. 1

 打开Zend studio开发工具，点菜单栏的help->Install New Software...；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/C76D36FBDA0F40CCBAE5ED5AAD03B7ACc75c10385343fbf2398c4560b27eca8064388fd5.jpg.jpeg)

1. 2

在弹出窗口的 Work with 栏输入：http://subclipse.tigris.org/update_1.8.x，然后点 Add按钮

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/80963713E54C4DAEB35F36B421184367838ba61ea8d3fd1f57a800ca364e251f94ca5f98.jpg.jpeg)

1. 3

弹出Add Repository窗口； 如果在下边出现两项：Subclipse 和SVNkit两项的话，直接关闭该窗口；在Name栏输入;subversion; 在Location栏输入;http://subclipse.tigris.org/update_1.8.x,然后点ok 按钮；在如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/A09461931BA24905BCE63BBA2FF76CDFc8ea15ce36d3d53937beed803887e950342ab0f6.jpg.jpeg)

1. 4

或者栏输入：http://subclipse.tigris.org/update_1.8.x，后等一会，直接出现：Subclipse 和SVNkit两项，直接选中这两个；然后点击下一步；

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/AA9F158697E344218CB86809B1BE3187b7003af33a87e9508da762e112385343faf2b48f.jpg.png)

1. 5

 在下边出现两项：Subclipse 和SVNkit，两个都选中，并将Subclipse中的:Subclipse Integration for Myiyn 3.x (optional)这项不要选中，然后点击Next按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/6630D97C38AA4675976D7A617354A9C411385343fbf2b211186d670dc88065380cd78e36.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/358CC5D30B4B45A3BDDD01E2EAC4A85B1e30e924b899a9010f06b6751f950a7b0208f546.jpg.jpeg)

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/CADB592EAAC243769EA2A29CFFC551DEca1349540923dd549f95452bd309b3de9d82488b.jpg.jpeg)

1. 6

点击：Next  按钮；如图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/4D3CAE53C79240BDAB0FD6DBBC41E2BE21a4462309f79052fb32aa7d0ef3d7ca7bcbd540.jpg.jpeg)

1. 7

选中 I accept the terms of the license agreement，并点击Next按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/AD6A8D00D7334EF18193ECFA4785BBE6faedab64034f78f0eae878b07b310a55b2191c95.jpg.jpeg)

1. 8

 正在安装界面；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/7E8231D988A848099A18EB5A1FEB8709a8014c086e061d954d81d57b79f40ad163d9caaa.jpg.jpeg)

1. 9

安装过程中，弹出的Security Warning对话框，点击OK按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/2F3A0626EE70428DAD371FCBB33C4DD6f11f3a292df5e0fe26c66b425e6034a85fdf7293.jpg.jpeg)

1. 10

 安装完之后，弹出Software updates对话框，则点击Restart Now重启zendstudio；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/210546FAB18443658E5809B3037F491Cfaedab64034f78f0eb1d77b07b310a55b3191c5a.jpg.jpeg)

1. 11

 重启完成后，在PHP Explore 区域空白处右键，选择Import；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/DD0310C43A9044F99642EA8FB8AA35BB32fa828ba61ea8d325469947950a304e251f5809.jpg.jpeg)

1. 12

然后再弹出的窗口中找到SVN菜单，并选中Project from SVN(从SVN 检出项目)，再点击Next按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/CA5BE4225D9D4DEF9BE4C5F4DC41F10Dd009b3de9c82d15802e4b4f2820a19d8bc3e420b.jpg.jpeg)

1. 13

 选中 Create a  new Repository location(创建新的资源位置) ，并点击Next按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/404B1246DE664FFB94CB578A9AD8DABCcf1b9d16fdfaaf5145ab6cfc8e5494eef01f7a2d.jpg.jpeg)

1. 14

 配置链接SVN服务器信息，填写完成之后点击Next按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/B8CCBCE42F4E443AA247C985B5AFA9CA9f510fb30f2442a7d75010e6d343ad4bd1130212.jpg.jpeg)

1. 15

点击：Finish按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/86C71D0D95C8492EA5AED7A2B1D45C24b2de9c82d158ccbfa33d58791bd8bc3eb03541f4.jpg.jpeg)

1. 16

 在弹出的Check out as 窗口中点击：Finish按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/844942E381F246ECAB9F579D5A4837AE6609c93d70cf3bc7795120afd300baa1cd112a24.jpg.jpeg)

1. 17

 选中检测出的项目，并点击OK按钮；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/CDF9421489734564A53DAF0F7B308BEF43a7d933c895d143bc8a096071f082025baf07f0.jpg.jpeg)

1. 18

 正在从svn服务器将项目下载到本地；如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/AE519C2717B040F4926694918B85DB803b292df5e0fe992503ed841336a85edf8cb17194.jpg.jpeg)

1. 19

从SVN服务器导入项目完成，如下图所示：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/7B85546EC47A459E8A87D7163FA1A6DC35a85edf8db1cb138cb4d150df54564e93584b90.jpg.jpeg)

END

注意事项

-  安装svn插件时的：work with地址，一定要填写正确；

-  链接SVN服务器时的配置信息要正确。

经验内容仅供参考，如果您需解决具体问题(尤其法律、医学等领域)，建议您详细咨询相关领域专业人士。

作者声明：本篇经验系本人依照真实经历原创，未经许可，谢绝转载。