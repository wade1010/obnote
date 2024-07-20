ubuntu里面为例

./clion.sh 启动clion

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE0872dd893275d24762dc1c94c16c2413截图.png)

{

    "tasks": [

        {

            "type": "cppbuild",

            "label": "C/C++: g++ 生成活动文件",

            "command": "make",

            "args": [],

            "options": {

                "cwd": "${fileDirname}"

            },

            "problemMatcher": [

                "$gcc"

            ],

            "group": {

                "kind": "build",

                "isDefault": true

            },

            "detail": "调试器生成的任务。"

        }

    ],

    "version": "2.0.0"

}

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
                "goofys", "/mnt/s3", "-o", "passwd_file=/home/bob/.passwd-s3fs", "-s","-o","url=http://127.0.0.1:9000", "-o", "use_path_request_style" ,"-f", "-o", "curldbg", "-o", "dbglevel=debug", "-o", "umask=0", "-o"," mp_umask=0"
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