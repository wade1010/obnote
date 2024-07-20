http://zhidao.baidu.com/link?url=JFqdStCH9JpDmEIpacKweKAwcNeldwOzY9-J697qvb4U8LGFoqOsLcnS9OrbB1672ZbLExTA2sd7iRgLAwv40V_S4o4olao-b0gMXpv6-33



fcitx只是一个输入法框架，在这个框架下有好多输入法，比如搜狗拼音输入法、谷歌拼音输入法、Sun拼音输入法等等。安装方法如下

sudo add-apt-repository ppa:fcitx-team/nightly

sudo apt-get update

sudo apt-get install fcitx                         #安装输入法框架

下面是一些输入法的安装命令

sudo apt-get install fcitx-cloudpinyin      #安装cloudpinyin输入法

sudo apt-get install fcitx-googlepinyin    #安装googlepinyin输入法

sudo apt-get install fcitx-libpinyin           #安装libpinyin输入法

sudo apt-get install fcitx-sunpinyin   #安装sunpinyin输入法



搜狗输入法请参考搜狗官网 http://pinyin.sogou.com/linux/help.php

或者使用下面方法，第一次没有成功，貌似版本不对

搜狗输入法安装方法与上面不同，方法如下

32位

wget http://download.ime.sogou.com/1408440412/sogou_pinyin_linux_1.1.0.0037_i386.deb?st=-xbu4PbhGbmd13bhftmLAw&e=1418216547&fn=sogou_pinyin_linux_1.1.0.0037_i386.deb

sudo dpkg -i sogou_pinyin_linux_1.1.0.0037_i386.deb

64位

wget http://download.ime.sogou.com/1408440412/sogou_pinyin_linux_1.1.0.0037_amd64.deb?st=0O0Eo83ERv_AdtnazeVA8Q&e=1418216692&fn=sogou_pinyin_linux_1.1.0.0037_amd64.deb

sudo dpkg -i sogou_pinyin_linux_1.1.0.0037_amd64.deb