之前安装mysql的时候，安装器自动添加了系统服务，启动系统的时候会自动启动mysql。

不过今天升级Mac OSX到10.10.1 Yosemite之后，发现启动系统的时候mysql没启动了。

那就试一下用mac的launchctl来实现这个功能吧。

方法也简单。



1、编辑一个mysql启动文件。

在终端里面输入：

sudo vi /Library/LaunchDaemons/com.mysql.mysql.plist





2、输入启动文件内容：

<?xml version="1.0" encoding="UTF-8"?>  

<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">  

<plist version="1.0">  

  <dict>  

    <key>KeepAlive</key>  

    <true/>  

    <key>Label</key>  

    <string>com.mysql.mysqld</string>  

    <key>ProgramArguments</key>  

    <array>  

    <string>/usr/local/mysql/bin/mysqld_safe</string>  

    <string>--user=root</string>  

    </array>    

  </dict>  

</plist> 

上面xml中的/usr/local/mysql/为我的mysql所在目录。



3、加载这个启动文件

在终端里输入：

sudo launchctl load -w /Library/LaunchDaemons/com.mysql.mysql.plist





这样你就会发现mysql成功启动了。

![](https://gitee.com/hxc8/images7/raw/master/img/202407190750454.jpg)

