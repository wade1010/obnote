Mac中jenkins的使用：https://jenkins.io

Jenkins 是一个开源项目，提供了一种易于使用的持续集成系统，使开发者从繁杂的集成中解脱出来，专注于更为重要的业务逻辑实现上。同时 Jenkins 能实施监控集成中存在的错误，提供详细的日志文件和提醒功能，还能用图表的形式形象地展示项目构建的趋势和稳定性。

一、jenkins工具的安装、卸载、启用

注意：安装jenkins必须先安装java sdk，同时安装好brew工具（http://brew.sh/index_zh-cn.html）

1、安装、卸载

（1）安装

方法1 终端命令安装：

[objc]view plaincopy

1. brew install jenkins  

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755310.jpg)

方法2 下载dmg文件安装（一步步安装即可）









安装好的目录位置，如下图所示

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755501.jpg)





（2）卸载

[objc]view plaincopy

1. brew uninstall jenkins  

2、启用

终端命令启动（仅对终端命令安装有效）：

[objc]view plaincopy

1. jenkins  

开机自动启动：

[objc]view plaincopy

1. ln -sfv /usr/local/Cellar/jenkins/2.109/*.plist ~/Library/LaunchAgents/org.jenkins-ci.plist 

1.     设置开机自启动：sudo launchctl load -w ~/Library/LaunchAgents/org.jenkins-ci.plist

取消开机自启动：sudo launchctl unload -w ~/Library/LaunchAgents/org.jenkins-ci.plist

手动启动：Java -jar jenkins.war 后台启动(默认端口)：nohup java -jar jenkins.war & 

后台启动(指定端口)：nohup java -jar jenkins.war -httpPort=88 & 

后台启动(HTTPS)：nohup java -jar jenkins.war -httpsPort=88 &

mac 进入 cd  /usr/local/Cellar/jenkins/2.109/libexec

java -jar jenkins.war --httpPort=8081

![](https://gitee.com/hxc8/images7/raw/master/img/202407190755799.jpg)



3、登录

http://localhost:8080



4、设置（首次使用时需要进行设置）

 设置项目：unlock Jenkins（administrator password）—>Customize Jenkins（install suggested plugins）—>Create First Admin User（用户名、密码、确认密码、全名、电子邮件地址）—>Jenkins is ready

注意：Administrator password的设置区分是脚本安装，还是dmg文件安装。如果是脚本安装的话，可以从终端直接查阅password；如果是dmg文件安装的话，必须从文件查看（安装目录—>secrets—>鼠标右击—>显示属性—>共享与权限—>修改成读与写—>initialAdminPassword—>鼠标右击—>显示属性—>共享与权限—>修改成读与写—>双击打开initialAdminPassword）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756909.jpg)



脚本安装jenkins时的password查看

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756353.jpg)



dmg文件安装jenkins时的password查看

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756825.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756559.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190756096.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756379.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756488.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756603.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756725.jpg)





二、jenkins的插件

1、常用插件

Git plugin

Git client plugin

Subversion Plug-in

Subversion Release Manager plugin

Subversion Tagging Plugin

SVN Publisher plugin

SSH Credentials Plugin

Gradle plugin： android专用

Xcode integration：iOS专用

2、插件安装方法

jenkins首页—>系统管理—>管理插件—>可选插件—>过滤搜索—>直接安装

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756989.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756306.jpg)







三、jenkins项目的配置使用

1、新建项目：Jenkins首页—>新建

（1）General：根据需要选择

a）项目名称（Enter an itemname；类型：构建一个自由网格的软件项目）

b）项目描述

c）丢弃旧的构建

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756394.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756333.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756384.jpg)



（2）源码管理：根据需要设置

情况1：Git（路径、帐号、密码）

情况2：Subversion（路径、帐号、密码）

情况3：None（使用本地项目）

![](D:/download/youdaonote-pull-master/data/Technology/自己动手/7、Jenkins/images/6FFE20BE3720482A91FCD58D789DC1FB6FFE20BE3720482A91FCD58D789DC1FB.png)





（3）构建触发器：根据需要选择（可不选）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756283.jpg)



（4）构建环境：根据需要选择（可不选）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756391.jpg)



（5）构建：根据需要设置

情况1：脚本Excute shell

根据实际情况配置参数，如果源码管理选择的是None，则使用svn下载最新源码脚本，否则屏蔽掉不使用。

[plain]view plaincopy

1. #<------------------------------------------------------->  

1. # 删除旧源码目录->新建源码目录->从svn导出最新代码->清理旧文件->清除旧项目->打包->上传  

1. pwd  

1. # 名称配置  

1. checkout_name="checkout"  

1. project_name="zsyDemo"  

1. # 配置项目版本  

1. #targetProject_sdk="iphoneos8.0"  

1. targetProject_destination="generic/platform=iOS"  

1. configuration="Release"  

1. scheme="$project_name"  

1. workspace_name="${project_name}.xcworkspace"  

1. # 目录配置  

1. save_path="/Users/zhangshaoyu/Desktop/uploadIPA"  

1. archive_path="$save_path/${project_name}.xcarchive"  

1. ipa_path="$save_path/${project_name}.ipa"  

1. log_path="$save_path/log.txt"  

1. # svn配置  

1. svn_path="http://192.168.11.11:8011/svn/zsyDemo/trunk/iOS/zsyDemo"  

1. checkout_path="$save_path/$checkout_name"  

1. svn_name="zhangshaoyu"  

1. svn_password="123456"  

1. # 配置签名证书、描述文件  

1. codeSignIdentity="iPhoneDeveloper: shaoyu zhang (5AB779CDEF)"  

1. provision_UUID="06a7492b-083c-4313-d633-15ef685929g4"  

1. provisoning_profile="zsyDemoDevelopProfile"  

1. # 配置蒲公英  

1. upload_path="$save_path/${project_name}.ipa"  

1. pgy_userKey="a512b58c56285d23456e011fgh706509"  

1. pgy_apiKey="ab9c240d2efg9hi17j9642k3l5mnop5q"  

1. echo "正在删除旧源码"  

1. # 删除旧源码目录  

1. rm -rf "$checkout_path" >> $log_path  

1. echo "正在创建新的源码目录"  

1. # 新建源码目录  

1. cd "$save_path" >> $log_path  

1. pwd  

1. mkdir "$checkout_name" >> $log_path  

1. echo "正在从svn下载最新的源码"  

1. # 从svn导出最新代码  

1. svn checkout "$svn_path" "$checkout_path" --username "$svn_name" --password "$svn_password" >> $log_path  

1. echo "正在删除旧文件"  

1. # 删除旧文件  

1. rm -rf "$log_path" >> $log_path  

1. rm -rf "$archive_path" >> $log_path  

1. rm -rf "$ipa_path" >> $log_path  

1. echo "正在清除构建项目缓存"  

1. # 重要，执行xcodebuild命令时，必须进入项目目录  

1. cd "$checkout_path" >> $log_path  

1. pwd  

1. # 清理构建目录  

1. xcodebuild clean -configuration "$configuration" -alltargets >> $log_path  

1. echo "正在打包"  

1. # 归档（其他参数不指定的话，默认用的是.xcworkspace或.xcodeproj文件里的配置）  

1. xcodebuild archive -workspace "$workspace_name" -scheme "$scheme" -destination "$targetProject_destination" -configuration "$configuration" -archivePath "$archive_path" CODE_SIGN_IDENTITY="$codeSignIdentity" PROVISIONING_PROFILE="$provision_UUID" >> $log_path  

1. echo "正在导出ipa包"  

1. # 导出IPA  

1. xcodebuild -exportArchive -exportFormat IPA -archivePath "$archive_path" -exportPath "$ipa_path" -exportProvisioningProfile "$provisoning_profile" >> $log_path  

1. echo "正在上传ipa到蒲公英"  

1. # 上传IPA到蒲公英  

1. curl -F "file=@$upload_path" -F "uKey=$pgy_userKey" -F "_api_key=$pgy_apiKey" https://www.pgyer.com/apiv1/app/upload  

1. #<------------------------------------------------------->  

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756404.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756562.jpg)





情况2：Xcode

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756604.jpg)

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756891.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756571.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190756637.jpg)



（6）构建后操作：根据需要设置（可不设置）

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756946.jpg)



2、配置项目

与新建时相同的操作，即General、源码管理、构建触发器、构建环境、构建、构建后操作。

3、删除项目

Jenkins首页—>点击项目—>删除Project

![](https://gitee.com/hxc8/images7/raw/master/img/202407190756052.jpg)

四、jenkins使用注意事项

1、安装java sdk：

http://www.oracle.com/technetwork/java/javase/downloads/index.html

2、安装brew：http://brew.sh/index_zh-cn.html

3、项目构建

（1）使用同步svn代码时，执行脚本中的路径指向jenkins相关目录

（2）未使用同步svn代码时，执行脚本中的路径指向自定义项目目录；同时也可以自定义脚本去下载svn代码

（3）构建成功的ipa包可通过执行脚本上传到内测平台，如蒲公英平台

4、查看证书描述文件信息、项目信息

（1）证书名称：Launchpad->其他->钥匙串访问->选择证书->鼠标右击->显示简介->细节->常用名称->复制

（2）描述文件UUID：打开Xcode->菜单栏->Preferences->Accounts->Apple IDs->帐号->showDetails->Provisioning Profiles->选择项目中使用的描述文件->

鼠标右击->show in Finder

（3）描述文件名称：直接查看描述文件名称

（4）target name、scheme：打开终端—>通过cd 命令进入项目目录—>通过命令”xcodebuild -list”查看

