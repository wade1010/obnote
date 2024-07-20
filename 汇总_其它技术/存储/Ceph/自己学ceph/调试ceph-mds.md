替换系统安装的bin和lib文件

默认情况下，官方发布版本是不可以调试，需要使用build/bin和/build/lib编译出来的关于mds的文件替系统默认安装的。系统默认把ceph相关的二进制文件安装到/usr/bin，把ceph依赖的动态库安装到/usr/lib中。本文需要调试文件系统mds管理元数据功能，只需要替换ceph-mds二进制文件；

————————————————

版权声明：本文为CSDN博主「JianTech」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/Hao_jiu/article/details/123328734](https://blog.csdn.net/Hao_jiu/article/details/123328734)