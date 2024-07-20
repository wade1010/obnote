cmake编译好带debug信息的可执行文件

启动vstart

关闭vstart

调试ceph-mon

ceph 根目录创建.vscode目录(也可以让vscode给我们生成)

在.vscode里面创建launch.json,内容如下

```
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "(gdb) 启动",
            "type": "cppdbg",
            "request": "launch",
            "program": "${workspaceFolder}/build/bin/ceph-mon",
            "args": [
                "-i",
                "a",
                "-c",
                "${workspaceFolder}/build/ceph.conf"
            ],
            "stopAtEntry": false,
            "cwd": "${fileDirname}",
            "environment": [],
            "externalConsole": false,
            "MIMode": "gdb",
            "setupCommands": [
                {
                    "description": "为 gdb 启用整齐打印",
                    "text": "-enable-pretty-printing",
                    "ignoreFailures": true
                },
                {
                    "description": "将反汇编风格设置为 Intel",
                    "text": "-gdb-set disassembly-flavor intel",
                    "ignoreFailures": true
                }
            ]
        }

    ]
}
```

打断点

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358233.jpg)

按F5开始调试

![](https://gitee.com/hxc8/images6/raw/master/img/202407182358995.jpg)