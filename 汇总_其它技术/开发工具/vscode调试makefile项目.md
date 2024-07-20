这里以s3fs为例

s3用的是minio，默认配置启动即可，启动minio后，执行

echo minioadmin:minioadmin >~/.passwd-s3fs

后面s3fs需要用到。

开始干活吧

1、下载源码

> git clone 


2、创建json文件

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE791ceee487368608bed52ca3d7bbf489截图.png)

- tasks.json

```
{
    "tasks": [
        {
            "label": "build_debug", // 任务名称，调试时可以指定不用任务进行处理 
            "type": "shell", // [shell, process], 定义任务作为作为进程运行还是在shell中作为命令运行; (测试没看出啥区别...)
            "command": "make", // 要执行的命令，可以是外部程序或者是shell命令。这里使用make编译命令
            "problemMatcher": [ // 要使用的问题匹配程序。可以是一个字符串或一个问题匹配程序定义，也可以是一个字符串数组和多个问题匹配程序。
                "$gcc"
            ],
            "group": { // 定义此任务属于的执行组。它支持 "build" 以将其添加到生成组，也支持 "test" 以将其添加到测试组。
                "kind": "build",
                "isDefault": true
            },
            "presentation": { // 配置用于显示任务输出并读取其输入的面板
                "echo": true, // 控制是否将执行的命令显示到面板中。默认值为“true”。
                "reveal": "always", // 控制运行任务的终端是否显示。可按选项 "revealProblems" 进行替代。默认设置为“始终”。
                "focus": false, // 控制面板是否获取焦点。默认值为“false”。如果设置为“true”，面板也会显示。
                "panel": "shared", // 控制是否在任务间共享面板。同一个任务使用相同面板还是每次运行时新创建一个面板。
                "showReuseMessage": true, // 控制是否显示“终端将被任务重用，按任意键关闭”提示
                "clear": false // 运行前清屏
            }
        },
        {
            "label": "build_release",//可以手动执行
            "type": "shell",
            "command": "make",
            "args": ["CFLAGS = -O2"], // 编译参数, 替换makefile中让CFLAGS字段
            "dependsOn":["build_clean"], // 指定依赖让task， 即会先执行build_clean，然后再执行build_release
            "problemMatcher": [
                "$gcc"
            ],
            "group": {
                "kind": "build",
                "isDefault": true
            }
        },
        {
            "label": "build_clean",//可以手动执行
            "type": "shell",
            "command": "make",
            "args": ["clean"], // 相当于执行 make clean命令
            "problemMatcher": [
                "$gcc"
            ],
            "group": {
                "kind": "build",
                "isDefault": true
            }
        }
    ],
    "version": "2.0.0"
}
```

- launch.json

```
{
    // 使用 IntelliSense 了解相关属性。 
    // 悬停以查看现有属性的描述。
    // 欲了解更多信息，请访问: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "(gdb) Lauch", // 启动配置的下拉菜单中显示的名称
            "type": "cppdbg",
            "request": "launch",
            "program": "${workspaceFolder}/src/s3fs", // 将要进行调试的程序的路径， workspaceFolder指当前工作目录（即vscode打开的目录：hello），main指的是makefile编译后目标码（可执行程序）的名字
            "args": [
                "bkname", "/mnt/s3", "-o", "passwd_file=/home/yourname/.passwd-s3fs", "-s","-o","url=http://127.0.0.1:9000", "-o", "use_path_request_style" ,"-f", "-o", "curldbg", "-o", "dbglevel=debug", "-o", "umask=0", "-o"," mp_umask=0"
            ], // 程序启动的参数
            "stopAtEntry": false, // 设置true时，程序将暂停在程序入口处, 即main()的第一个{位置
            "cwd": "${workspaceFolder}", // 调试时的工作目录
            "environment": [],
            "externalConsole": false, // 调试时，是否显示控制台串口
            "MIMode": "gdb", // 调试命令
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
            ],
            "preLaunchTask": "build_debug", // 使用哪个任务进行编译，需要指定tasks.json中的一个，这里选择用build_debug任务进行编译
            "miDebuggerPath": "/usr/bin/gdb" // 调试命令的路径
        }
    ]
}
```

3、找到需要打断点的地方

这里以main函数为例

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE5c886cd06f987007cfe1d3c853219b56截图.png)

4、执行调试

按F5或者按下图点击启动调试

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE1f053020a6eee41521489d65832b280d截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE5ed7c1bf9145f22879554658003e60a3截图.png)