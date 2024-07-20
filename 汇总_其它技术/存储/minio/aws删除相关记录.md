创建不带版本的桶



put a.txt

put a.txt

put a.txt



delete a.txt



之后getobject 返回字段都是nil  和ListObjectVersions 里面的versions为nil







---

创建不带版本的桶



put a.txt

开启多版本

ListObjectVersions 里面的versions 里面的IsLatest为true,versionID为null

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006492.jpg)

delete a.txt  (不传版本号)     ps:这时候多版本是开启的，所以删除就是添加一个删除的版本

之后getobject 返回字段都是nil 

ListObjectVersions 里面的versions 里面的IsLatest为false,versionID为null   ，DeleteMarkers不为空

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006251.jpg)



---

创建不带版本的桶



put a.txt

开启多版本

ListObjectVersions 里面的versions 里面的IsLatest为true,versionID为null

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006674.jpg)

delete a.txt  (不传版本号)     ps:这时候多版本是开启的，所以删除就是添加一个删除的版本

delete a.txt  (不传版本号)     ps:这时候多版本是开启的，所以删除就是添加一个删除的版本

之后getobject 返回字段都是nil 

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006613.jpg)

但是minio的getobject返回结果是有versionID的，且DeleteMarker是true如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006917.jpg)

ListObjectVersions 里面的versions 里面的IsLatest为false,versionID为null   ，DeleteMarkers不为空

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006393.jpg)



---

创建不带版本号的桶

put a.txt

开启多版本

put a.txt

put a.txt

put a.txt

ListObjectVersions

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006583.jpg)



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006737.jpg)

delete a.txt  (传版本号null)

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006124.jpg)

GetObject 传版本号null  结果都为nil

GetObject 不传版本号  

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006079.jpg)

ListObjectVersions  DeleteMarkers为nil

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006355.jpg)



---

创建不带版本号的桶

put a.txt

开启多版本

put a.txt

put a.txt

put a.txt

暂停多版本

ListObjectVersions

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006898.jpg)



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006049.jpg)

delete a.txt  (不传版本号)  注意这里删的是null版本

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006191.jpg)

minio如下图

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006044.jpg)

GetObject 传版本号null  

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006937.jpg)

minio不同点就是DeleteMarker为nil

GetObject 不传版本号

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006142.jpg)

minio如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006395.jpg)

ListObjectVersions

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006753.jpg)



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006998.jpg)

minio如下

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006097.jpg)



![](https://gitee.com/hxc8/images6/raw/master/img/202407190006373.jpg)



---

创建不带版本号的桶

put a.txt

开启多版本

put a.txt

put a.txt

put a.txt

delete a.txt  (不传版本号)

ListObjectVersions   这时候删除就是插入个新版本

![](https://gitee.com/hxc8/images6/raw/master/img/202407190006470.jpg)

