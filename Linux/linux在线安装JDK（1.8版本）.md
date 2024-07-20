在线下载JDK

命令：

 wget --no-check-certificate --no-cookies --header "Cookie: oraclelicense=accept-securebackup-cookie" http://download.oracle.com/otn-pub/java/jdk/8u131-b11/d54c1d3a095b4ff2b6607d096fa80163/jdk-8u131-linux-x64.rpm



如果失效可以找我从百度网盘下载





![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/BF4E505BC1554FB6A80D1A9708F01EBA20180603145437541.png)

下载读取条： 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/89B67D8A88F14F959DA10F73F7176C6820180603145454527.png)

查看当前文件夹下是否有JDK安装包：

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/5E511EDF84324BC180DFAF6A6D0D705020180603145513739.png)

添加执行权限：

命令：

chmod +x jdk-8u131-linux-x64.rpm

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/2A1C7F7D6F624172A82C7659091271CB20180603145531358.png)

执行rpm进行安装

命令：

rpm -ivh jdk-8u131-linux-x64.rpm

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/6FB6F0C523B34090AFF27256E48D741B20180603145544258.png)

查看JDK是否安装成功

命令：

java -version

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/A7A3A61E2D7842C286F3A4853061BBF920180603145558249.png)

查看JDK的安装路径，（一般默认的路径：/usr/java/jdk1.8.0_131）

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/822F78D2E4234EF1BEAA623BF9A6650320180603145610505.png)

配置JDK环境变量

1、编辑环境变量

1. export JAVA_HOME=/usr/java/jdk1.8.0_131

1. export JRE_HOME=${JAVA_HOME}/jre

1. export CLASSPATH=.:${JAVA_HOME}/lib:${JRE_HOME}/lib:$CLASSPATH

1. export JAVA_PATH=${JAVA_HOME}/bin:${JRE_HOME}/bin 

1. export PATH=$PATH:${JAVA_PATH}

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/F746E82FE4C84B879DA04172071C00B72018060314563815.png)

2、编辑 /etc/profile

命令：

vim /etc/profile

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/2C99E7050B2E4804BBD1B17CC78048712018060314565268.png)

3、进行环境配置

命令：i        进入编辑模式

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/BE09F79872A94D16818EAE1B2C2309AF20180603145701984.png)

把上面编辑好的粘贴

输入命令： ：wq!

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/8AE3F8DD8DD240A9B906F13EDE5E9C6120180603145713384.png)

强制保存并退出

让profile立即生效：

source /etc/profile

 

查看JDK安装情况

1、使用javac命令，不会出现command not found

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/2374CB284FC04F91BAF9D931628E895420180603145736928.png)

2、命令：

java -version

 

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/86BE68953ECF41B09ABD31B5735407D720180603145747285.png)

3、 

echo $PATH

查看自己刚刚设置的环境变量配置是否都正确

![](D:/download/youdaonote-pull-master/data/Technology/Linux/images/27594F593BE94927B989A8358D96F77220180603145757435.png)

