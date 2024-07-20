安装 Ubuntu：

微软商店直接搜 Ubuntu ，两个版本都可以装，我装的是Ubuntu-18.04

设置用户名密码：

打开ubuntu

手动设置用户名密码，这个时候会要求你输入两次密码，必须保持一致，否则密码设置不成功

设置国内源（阿里）：

```bash
# 备份初始源
sudo cp /etc/apt/sources.list /etc/apt/sources.list.bak

# 编辑软件源文件 sources.list
sudo vim /etc/apt/sources.list

# 清除所有内容（必须在文件非 INSERT 状态）
shift+v
shift+g
d

# 拷贝下方内容至 sources.list 文件（覆盖原先所有内容）
deb http://mirrors.aliyun.com/ubuntu/ focal main restricted universe multiverse 
deb http://mirrors.aliyun.com/ubuntu/ focal-security main restricted universe multiverse 
deb http://mirrors.aliyun.com/ubuntu/ focal-updates main restricted universe multiverse 
deb http://mirrors.aliyun.com/ubuntu/ focal-proposed main restricted universe multiverse 
deb http://mirrors.aliyun.com/ubuntu/ focal-backports main restricted universe multiverse 
deb-src http://mirrors.aliyun.com/ubuntu/ focal main restricted universe multiverse 
deb-src http://mirrors.aliyun.com/ubuntu/ focal-security main restricted universe multiverse 
deb-src http://mirrors.aliyun.com/ubuntu/ focal-updates main restricted universe multiverse 
deb-src http://mirrors.aliyun.com/ubuntu/ focal-proposed main restricted universe multiverse 
deb-src http://mirrors.aliyun.com/ubuntu/ focal-backports main restricted universe multiverse
# 保存 sources.list 文件
# 推出编辑
按 ESC
# 保存
:wq

# 更新源
sudo apt-get update

# 出现依赖问题（没出现直接跳过）
sudo apt-get -f install

# 更新软件
sudo apt-get upgrade
```

完成 Ubuntu 的安装！

在windows上安装vscode，然后安装WSL插件

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243589.jpg)

## **vscode 里设置 Ubuntu(WSL) 为终端**

在ubuntu终端输入 

```
mkdir -p workspace/demo
cd workspace/demo
code .
```

第一次会有下载的过程，会提示“Installing VS Code Server”，安装linux端的vs code小型服务端，用以与window下vc code会话通信。然后会在window打开vscode

但是我这里打开后提示建议升级到WSL2,

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243715.jpg)

升级参考[https://zhuanlan.zhihu.com/p/356397851](https://zhuanlan.zhihu.com/p/356397851)

我这里win11就做了如下操作

## 查看当前WSL版本号

打开PowerShell，执行命令

```text
(base) PS C:\Users\Administrator> wsl -l -v                ]
  NAME            STATE           VERSION
* Ubuntu-22.04    Stopped         1
```

发现用的确实是wsl1

## 下载 Linux 内核更新包

[https://link.zhihu.com/?target=https%3A//wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi](https://link.zhihu.com/?target=https%3A//wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi)

下载后点击安装，速度非常快。

打开powershell

wsl -l

wsl --set-version Ubuntu-18.04 2

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243169.jpg)

再次测试

进入powershell输入 wsl，默认就会进入我们安装的ubuntu

然后输入 code .   之后就会在windows上面打开vscode并通过Remote-WSL插件连接到ubuntu

然后创建一个文件，其实就是在ubuntu里面创建

配置C/C++环境

sudo apt-get install gcc g++ cmake gdb -y

vscode里面安装C/C++ Extension Pack  (这个是WSL里面的)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243670.jpg)

键入

Ctrl + Shift + P打开命令面板。输入

C/C++ edit configurations，选择json

```
{
    "configurations": [
        {
            "name": "Linux",
            "includePath": [
                "${workspaceFolder}/**"
            ],
            "defines": [
                "_DEBUG",
                "UNICODE",
                "_UNICODE"
            ],
            "compilerPath": "/usr/bin/g++",
            "cStandard": "c11",
            "intelliSenseMode": "${default}",
            "cppStandard": "c++11"
        }
    ],
    "version": 4
}
```

创建Tasks，Ctrl + Shift + P打开命令面板，选择任务：配置默认生成任务，使用模板创建tasks.json文件

 

```
{
    "tasks": [
        {
            // 清空编译
            "type": "shell",
            "label": "rebuild",
            "command": "mkdir -p build;rm -rf build/*;cd build;cmake .. ; make",
            "group": {
                "kind": "build",
                "isDefault": true
            },
        },
        {
            // 增量编译 
            "type": "shell",
            "label": "build",
            "command": "mkdir -p build;cd build; make",
            "group": {
                "kind": "build",
                "isDefault": true
            },
        }
    ],
    "version": "2.0.0"
}
```

创建launch.json

```
{
    // 使用 IntelliSense 了解相关属性。 
    // 悬停以查看现有属性的描述。
    // 欲了解更多信息，请访问: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "增量 (gdb) Launch ",
            "type": "cppdbg",
            "request": "launch",
            "program": "${workspaceFolder}/build/main",
            "args": [],
            "stopAtEntry": false,
            "cwd": "${workspaceFolder}",
            "environment": [],
            "externalConsole": false,
            "MIMode": "gdb",
            "setupCommands": [
                {
                    "description": "Enable pretty-printing for gdb",
                    "text": "-enable-pretty-printing",
                    "ignoreFailures": true
                }
            ],
            "preLaunchTask": "build"
        },
        {
            "name": "清空 (gdb) Launch",
            "type": "cppdbg",
            "request": "launch",
            "program": "${workspaceFolder}/build/main",
            "args": [],
            "stopAtEntry": false,
            "cwd": "${workspaceFolder}",
            "environment": [],
            "externalConsole": false,
            "MIMode": "gdb",
            "setupCommands": [
                {
                    "description": "Enable pretty-printing for gdb",
                    "text": "-enable-pretty-printing",
                    "ignoreFailures": true
                }
            ],
            "preLaunchTask": "rebuild"
        }
    ]
}
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243581.jpg)

然后创建 main.cpp

```
#include <iostream>
using namespace std;

void test()
{
    cout << "hello world" << endl;
}
int main()
{
    test();
    return 0;
}
```

然后创建CMakelists.txt

```
cmake_minimum_required(VERSION 2.8)
project(main)

add_definitions("-Wall -g -Wno-unknown-warning-option"
        " -Wno-format-extra-args -Wno-format -Wno-pragmas"
        " -Wno-incompatible-pointer-types")

add_executable(main
    main.cpp
)


```

测试，点击如下图的地方

![](https://gitee.com/hxc8/images3/raw/master/img/202407172243699.jpg)