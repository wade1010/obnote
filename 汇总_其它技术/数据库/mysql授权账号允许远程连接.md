1、进入mysql :mysql -u root -p

2、输入密码

3、grant all privileges on （数据库，*代表所有）.* to （用户名）@"%" Identified by "（密码）";

如：grant all privileges on *.* to root@"%" Identified by "root";

4、flush privileges;

 （分号不能少）

 



 

![](https://gitee.com/hxc8/images8/raw/master/img/202407191057092.jpg)

