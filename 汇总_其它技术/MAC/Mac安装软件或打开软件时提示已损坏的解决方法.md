https://blog.csdn.net/chj_1224365967/article/details/121491406



原因

新系统（macOS Sierra 10.12.X以上）加强了安全机制

默认不允许用户自行下载安装应用程序，只能从Mac App Store里安装应用。

解决方法

▌ 步骤一：打开终端

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749305.jpg)



▌步骤二：输入代码sudo spctl --master-disable	

在终端输入以下代码（可复制粘贴）

sudo spctl --master-disable


- 1

然后回车，输入自己电脑密码

输完回车即可（密码不会显示出来，如果密码不对会有提示，没有提示就是输入正确了）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749337.jpg)



▌步骤三：打开系统偏好设置 > 安全性与隐私，若显示任何来源，说明离成功不远了

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749531.jpg)



回到桌面双击安装文件，应该是可以安装了

如果还是无法打开，继续往下看

▌步骤四：（移除这个应用的安全隔离属性）

xattr -r -d com.apple.quarantine path（path换成软件安装路径，一般在/Application下）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190749037.jpg)



现在再回去安装软件，成功！