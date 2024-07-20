要求:

1、全局唯一性:不能出现重复的id号(基本的要求)

2、信息安全:防止恶意用户规矩id的规则来获取数据。

3、数据递增:保证我下一个ID一定大于上一个ID。



---

业界分案:

1、UUID:

通用唯一识别码16 个字节128位的长数字



组成部分:当前日期和时间序列+全局的唯一性网卡mac地址



总结：



优点

代码实现简单、不占用宽带、数据迁移不受影响

缺点

无序、 无法保证趋势递增(要求3)字符存储、传输、查询慢、不可读



---



Snowflake雪花算法



国外的twitter 分布式下iD生成算法

1bit+41 bit+ 10bit+ 10+bit=62bit

高位随机+毫秒数+机器码(数据中心+机器id) +10的流水号

国内:

保证数据的唯一性就行了 IDC 机房。



总结:



优点



代码实现简单、不占用宽带、数据迁移不受影响、低位趋势递增









缺点

强依赖时钟 (多台服务器时间一定要一-样) 、无序无法保证趋势递增(要求3)



---



MySQL



![](https://gitee.com/hxc8/images7/raw/master/img/202407190029876.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190029501.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190029121.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190029136.jpg)





---



---



Redis



缩减版本、有关业务代码没有包含到里头、redis 方案

![](https://gitee.com/hxc8/images7/raw/master/img/202407190029337.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190029728.jpg)



![](https://gitee.com/hxc8/images7/raw/master/img/202407190029068.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190029460.jpg)





![](https://gitee.com/hxc8/images7/raw/master/img/202407190029505.jpg)

