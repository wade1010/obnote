Mac升级到macOS Sierra 10.12后，发现SVN管理软件Conerstone 2.7破解版已经无法使用，需要更新版本。



        安装Cornerstone_3.0.1破解版后，发现提示“cornerstone 已损坏，打不开。”

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331269.jpg)



导致问题原因：



        软件有经过了汉化或者破解，所以可能被Mac认为「已损坏」



解决问题办法：



         系统偏好设置 -> 安全性与隐私 -> 通用 -> 选择“任何来源”

![](https://gitee.com/hxc8/images5/raw/master/img/202407172331386.jpg)



         “通用”里有时没有“任何来源”这个选项：



         显示"任何来源"选项在控制台中执行：



    sudo spctl --master-disable



         不显示"任何来源"选项（macOS 10.12默认为不显示）在控制台中执行：



    sudo spctl --master-enable