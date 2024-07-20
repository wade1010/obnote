点击系统->连接QMT

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333346.jpg)

填入交易账号和miniQMT的地址

这次路径为：D:\Program Files\迅投QMT\userdata_mini

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333014.jpg)

这里报价会变化

实现方式

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333439.jpg)

切换到vnpy的python环境

cd vnpy_qmt

pip install .

cd vnpy_miniqmt

pip install .

然后在vnpy配置里面datafeed.name填 miniqmt

![](https://gitee.com/hxc8/images5/raw/master/img/202407172333798.jpg)