docker 安装



docker pull mongo:latest



docker run -itd --name mongodb -p 27017:27017  mongo





编译安装



1: 下载mongodb www.mongodb.org  下载最新的stable版

2: 解压文件

3: 不用编译,本身就是编译后的二进制可执行文件.

 

![](https://gitee.com/hxc8/images7/raw/master/img/202407190809954.jpg)

 

4: 启动mongod服务

./bin/mongod --dbpath /path/to/database --logpath /path/to/log --fork --port 27017

参数解释:

--dbpath 数据存储目录

--logpath 日志存储目录

--port 运行端口(默认27017)

--fork 后台进程运行

 

 

5: mongodb非常的占磁盘空间, 刚启动后要占3-4G左右,

如果你用虚拟机练习,可能空间不够,导致无法启动.

可以用 --smallfiles 选项来启动,

将会占用较小空间  400M左右.