xmake b hkuserver

cd build\release\windows\x64\lib

.\hkuserver.exe 执行

发现报错

Initialize hikyuu_1.2.8_202309281158_x64_release ...

2023-09-28 20:23:00.601 [SERVER-C] - Can't read file(C:\Users\Administrator/.hikyuu/trade.ini)! (main.cpp:63)

2023-09-28 20:23:00.602 [HttpServer-I] - Quit Http server (HttpServer.cpp:77)

Quit Hikyuu system!

在相应目录下创建trade.ini

内容如下

```
[database]
type = sqlite3
db = C:\Users\Administrator\.hikyuu\trade.db
```

之后再执行 .\hkuserver.exe 就可以了

![](https://gitee.com/hxc8/images5/raw/master/img/202407172332383.jpg)