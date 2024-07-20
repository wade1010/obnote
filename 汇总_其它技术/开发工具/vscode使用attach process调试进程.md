这里以s3fs为例

s3用的是minio，默认配置启动即可，启动minio后，执行

echo minioadmin:minioadmin >~/.passwd-s3fs

后面s3fs需要用到。

开始干活吧

1、下载源码

> git clone 


2、创建json文件

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE1a17d3acc726d461ab1952f57607349f截图.png)

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

launch.json

```
{
    "configurations": [
    {
        "name": "(gdb) Attach",
        "type": "cppdbg",
        "request": "attach",
        "program": "${workspaceFolder}/src/s3fs",
        "processId": "${command:pickProcess}",
        "MIMode": "gdb",
        "setupCommands": [
            {
                "description": "Enable pretty-printing for gdb",
                "text": "-enable-pretty-printing",
                "ignoreFailures": true
            }
        ]
    }
    ]
}

```

3、启动s3fs

./src/s3fs -o passwd_file=/home/xxx/.passwd-s3fs -s -o url=[http://127.0.0.1:9000](http://127.0.0.1:9000) -o use_path_request_style -f -o curldbg -o dbglevel=debug -o umask=0 -o  mp_umask=0 bucketname /mnt/s3

4、打断点

这里以ls列出桶内容为例

断点这里打在s3fs_readdir方法里

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE34dc7f2dc6f77268899e33747086ee63截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE45796ca75e9adc048e759afdfd36f7d8截图.png)

5、attach process

按F5

然后选择自己要调试的进程

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE7d04fdfd84b5d519c73626dea58c5bb5截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEee56394069c9f26c5c3c045bbe9c9e65截图.png)

因为需要超级用户身份运行，所以会弹出输入密码的框

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEa0cd7c0f19b103452c58f692775462c0截图.png)

                

6、执行对应测试

> cd /mnt/s3
> ls


                            

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE9bc21e23ed8464128150f591ab0217a3截图.png)

OK