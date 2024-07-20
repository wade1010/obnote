# 1、在ubuntu虚拟机中安装openssh-server

　　sudo apt-get install openssh-server

       

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCE31c71a4fc5823c7b6511cf93c35ed42f截图.png)

2、开启ubuntu虚拟机中的ssh服务

　　sudo /etc/init.d/ssh start

       

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCE51f6fe7e54cf5f9e3ab987fc142d996e截图.png)

3、查看IP

　　ifconfig

　　

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCEa93965bd208c4d720901a61489387b35截图.png)

4、我选择安装PuTTY来远程登陆ubuntu虚拟机。PuTTY是一个SSH和telnet客户端。

　　安装成功后打开PuTTY,输入步骤3查到的IP地址，SSH端口一般默认是22，然后点击Open。

　　

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCEdc48fa8e26014ff24d6171b6815a7420截图.png)

5、输入ubuntu虚拟机的用户名和密码，登陆成功。

　　

![](D:/download/youdaonote-pull-master/data/Technology/Linux/Ubuntu/images/WEBRESOURCEa8b150f5138aec9a6aab610fbefb2d9f截图.png)