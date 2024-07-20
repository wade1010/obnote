[https://zhuanlan.zhihu.com/p/475656279](https://zhuanlan.zhihu.com/p/475656279)

```
{
    // 使用 IntelliSense 了解相关属性。
    // 悬停以查看现有属性的描述。
    // 欲了解更多信息，请访问: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "osd-debug", //名字随便
            "type": "cppdbg",
            "request": "launch",
            "program": "${workspaceFolder}/build/bin/ceph-osd", //这里要修改
            "args": ["-d", "--cluster", "ceph","--id", "0", "--setuser", "root", "--setgroup", "root"],
            "stopAtEntry": false,
            "cwd": "${workspaceFolder}",
            "environment": [],
            "externalConsole": false,
            "MIMode": "gdb",
            "setupCommands": [
                {
                    "description": "为 gdb 启用整齐打印",
                    "text": "-enable-pretty-printing",
                    "ignoreFailures": true
                }
            ]
        }
    ]
}
```

[http://www.manongjc.com/detail/27-mdoapefjfbaquqv.html](http://www.manongjc.com/detail/27-mdoapefjfbaquqv.html)

[https://blog.csdn.net/weixin_42319496/article/details/125942755](https://blog.csdn.net/weixin_42319496/article/details/125942755)

MON=1 OSD=3 MDS=0 MGR=1 RGW=0 ../src/vstart.sh -d -n  -x  --without-dashboard  改为下面，直接OSD=1

MON=1 OSD=1 MDS=0 MGR=1 RGW=0 ../src/vstart.sh -d -n  -x  --without-dashboard

./bin/ceph -s

./bin/ceph osd pool create cache_pool 1 1

./bin/ceph pg ls-by-pool cache_pool

```
root@ceph:/home/ceph/workspace/ceph/build# ./bin/ceph pg ls-by-pool cache_pool
PG   OBJECTS  DEGRADED  MISPLACED  UNFOUND  BYTES  OMAP_BYTES*  OMAP_KEYS*  LOG  STATE         SINCE  VERSION  REPORTED  UP         ACTING     SCRUB_STAMP                      DEEP_SCRUB_STAMP                 LAST_SCRUB_DURATION  SCRUB_SCHEDULING
2.0        0         0          0        0      0            0           0    0  active+clean    27s      0'0     27:10  [3,5,1]p3  [3,5,1]p3  2023-03-26T10:06:14.612802+0800  2023-03-26T10:06:14.612802+0800                    0  periodic scrub scheduled @ 2023-03-27T08:05:17.691656+0000
```

从结果可以看到PG2.0对应的主OSD为OSD 3

ps -ef | grep ceph

```
root@ceph:/home/ceph/workspace/ceph/build# ps -ef | grep ceph
avahi        649       1  0 10:01 ?        00:00:00 avahi-daemon: running [ceph.local]
root        1702       1  3 10:03 ?        00:00:09 /home/ceph/workspace/ceph/build/bin/ceph-mon -i a -c /home/ceph/workspace/ceph/build/ceph.conf
root        1877       1  4 10:03 ?        00:00:12 /home/ceph/workspace/ceph/build/bin/ceph-mgr -i x -c /home/ceph/workspace/ceph/build/ceph.conf
root        2780       1  4 10:03 ?        00:00:11 /home/ceph/workspace/ceph/build/bin/ceph-osd -i 0 -c /home/ceph/workspace/ceph/build/ceph.conf
root        4157       1  5 10:03 ?        00:00:14 /home/ceph/workspace/ceph/build/bin/ceph-osd -i 1 -c /home/ceph/workspace/ceph/build/ceph.conf
root        5527       1  5 10:04 ?        00:00:12 /home/ceph/workspace/ceph/build/bin/ceph-osd -i 2 -c /home/ceph/workspace/ceph/build/ceph.conf
root        6833       1  5 10:04 ?        00:00:11 /home/ceph/workspace/ceph/build/bin/ceph-osd -i 3 -c /home/ceph/workspace/ceph/build/ceph.conf
root        8156       1  5 10:04 ?        00:00:11 /home/ceph/workspace/ceph/build/bin/ceph-osd -i 4 -c /home/ceph/workspace/ceph/build/ceph.conf
root        9461       1  5 10:04 ?        00:00:10 /home/ceph/workspace/ceph/build/bin/ceph-osd -i 5 -c /home/ceph/workspace/ceph/build/ceph.conf

```

从结果可以看到，主OSD进程号为 6833

ceph osd ls

ceph daemon osd.0 config set debug_osd 20

![](https://gitee.com/hxc8/images6/raw/master/img/202407182359294.jpg)

查看是否配置成功

./bin/ceph daemon osd.0 config show|grep debug_osd

sudo gdb attach 6833

b create_object_context

./bin/rados put keyring.txt ./keyring -p cache_pool

./bin/rados ls -p cache_pool