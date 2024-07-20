> 末尾附上最终的模板


Vscode开发环境配置

- C++有很多种编译器，最重要的有三种

	- GNU的GCC(推荐)

	- 微软的MSVC

	- Clang/LLVM

C++的最新标准是C++23,各个编译器对C++各个标准的支持情况是不同的：

[https://en.cppreference.com/w/cpp/compiler_support](https://en.cppreference.com/w/cpp/compiler_support)

注意主要看C++20的支持情况

###### 用Vscode配置可以支持三种编译器的开发环境

-  因为要支持MSVC(微软)所以建议你在Windows.上实操

- 第一步：

	- 下载GCC和Clang以及MSVC

		- gcc、clang->mingw(

		- msvc->VS2019或是vs2022

	- 配置环境变量

	- 测试是否成功

- 第二步

	- 下载Vscode

	- 创建一个文件夹cppenv

windows上安装msvc

下载visual studio 2022，安装 “使用C++的桌面开发” [https://visualstudio.microsoft.com/zh-hans/vs/](https://visualstudio.microsoft.com/zh-hans/vs/)

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE0e172dc53df6a8d633ccbc8fa4e89c90截图.png)

gcc和clang在windows上可以通过mingw，网址  [https://winlibs.com/](https://winlibs.com/)    这个网址主要是移植一些GNU的东西到windows上

打开网址后，往下滑动，可以看到

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE9c6e060c0bcfefc07aa73ddd89c0e71f截图.png)

选择对应的版本即可。我这里选择 Win64: 7-Zip archive

下载后解压到某个目录，然后配置环境变量即可

示例：

解压到  D:\env\mingw64

然后把这个D:\env\mingw64\bin目录加入到环境变量

这不bin目录下面包含g++ clang  gdb等

可以打开命令行 输入 g++ --version         clang++ --version 验证是否安装成功

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE87276f8a8cbe237ac4ac08be87f5e95c截图.png)

###### 创建C++项目模板

创建一个目录 cppenv 然后用vscode打开

创建main.cpp

```
#include <iostream>
using namespace std;

int main()
{
    cout << "env" << endl;
    return 0;
}
```

下载c/c++插件

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE709f2bf572d421d68542a30357ab9086截图.png)

使用方法可以点击上图里面的Documentation,地址为 [https://code.visualstudio.com/docs/languages/cpp](https://code.visualstudio.com/docs/languages/cpp)

配置task.json：

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEd557901d8a8bfc1ab2c3bfdee2107127截图.png)

点击Configure Tasks...后会出现下图，一堆的你当前电脑安装过的编译器

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE690eb713e4c56a57ed7ab9cff6da8a85截图.png)

先配置一个GNU的gcc

点击

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE1335bce679055561387802bac3835ee0截图.png)

点击后会生成tasks.json

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE77e9b1f889bdc37b51522ce9b8beee20截图.png)

可以将label的名字改下，例如： GCC Compiler

修改下参数，指定使用的c++版本，这里改成c++11

```
{
    "version": "2.0.0",
    "tasks": [
        {
            "type": "cppbuild",
            "label": "GCC Compiler",
            "command": "W:\\env\\mingw64\\bin\\g++.exe",
            "args": [
                "-std=c++11",
                "-fdiagnostics-color=always",
                "-g",
                "${file}",
                "-o",
                "${fileDirname}\\${fileBasenameNoExtension}.exe"
            ],
            "options": {
                "cwd": "${fileDirname}"
            },
            "problemMatcher": [
                "$gcc"
            ],
            "group": "build",
            "detail": "compiler: W:\\env\\mingw64\\bin\\g++.exe"
        }
    ]
}
```

再配置一个clang的编译器

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEbd823141f433fd09973b4d9d3181c5d4截图.png)

要选择clang++不要选择上面的两个clang

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE9c343d188c79c3fd85bb6a628c7ba8fa截图.png)

同样  改名 加c++版本

最好再配置微软的MSVC，这个就多点步骤了，

选择这个

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE31ea9b531bc8693e7dc8352de02acec8截图.png)

点击后生成

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE1f5544f29b0aaf4217706239d1ce23b3截图.png)

另外关键点：

我们选的是cl.exe，但是我们在终端，发现cl找不到。

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE7cd0085b44e8be2ef8ae80f35b7c257d截图.png)

但是在Developer Command Prompt for VS 2002的终端里面是有 cl的

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE6da73cb2508dbc091cf65d065269a468截图.png)

所以我们需要在这个 Developer Command Prompt for VS 2002的终端，进入到项目目录，然后用code 打开（先关闭现有的）

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE6fb02de22d9ff90ef9347acbceed017a截图.png)

然后发现vscode的终端也能执行cl了，（其实应该就是加载了Developer Command Prompt for VS 2002的环境变量，跟shell里面sourc一些变量一样）

测试下MSVC

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE512f09cf65041feb36db7b3f0ccbdc34截图.png)

生成的东西有点多。

最终tasks.json

```
{
    "version": "2.0.0",
    "tasks": [
        {
            "type": "cppbuild",
            "label": "GCC Compiler",
            "command": "W:\\env\\mingw64\\bin\\g++.exe",
            "args": [
                "-std=c++11",
                "-fdiagnostics-color=always",
                "-g",
                "${file}",
                "-o",
                "${fileDirname}\\${fileBasenameNoExtension}.exe"
            ],
            "options": {
                "cwd": "${fileDirname}"
            },
            "problemMatcher": [
                "$gcc"
            ],
            "group": "build",
            "detail": "compiler: W:\\env\\mingw64\\bin\\g++.exe"
        },
        {
            "type": "cppbuild",
            "label": "Clang Compiler",
            "command": "W:\\env\\mingw64\\bin\\clang++.exe",
            "args": [
                "-std=c++11",
                "-fcolor-diagnostics",
                "-fansi-escape-codes",
                "-g",
                "${file}",
                "-o",
                "${fileDirname}\\${fileBasenameNoExtension}.exe"
            ],
            "options": {
                "cwd": "${fileDirname}"
            },
            "problemMatcher": [
                "$gcc"
            ],
            "group": "build",
            "detail": "compiler: W:\\env\\mingw64\\bin\\clang++.exe"
        },
        {
            "type": "cppbuild",
            "label": "MSVC Compiler",
            "command": "cl.exe",
            "args": [
                "/Zi",
                "/std:c++latest",
                "/EHsc",
                "/nologo",
                "/Fe${fileDirname}\\${fileBasenameNoExtension}.exe",
                "${file}"
            ],
            "options": {
                "cwd": "${fileDirname}"
            },
            "problemMatcher": [
                "$msCompile"
            ],
            "group": "build",
            "detail": "compiler: cl.exe"
        }
    ]
}
```

以后可以有项目 可以直接复制这个tasks.json，也可以cp -r cppenv yourpathname

另外也可以安装code runner插件,一键运行

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE4fe189dbb36b75daff01383c93aaa919截图.png)

也可以指定C++版本

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE5e3b57113c4991036a8772f5bb3d0d5f截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEb8760c5d411825cb4d14032667609973截图.png)

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE87ce8f86e90ec2873f78e7ccb37d6921截图.png)

###### 再就是如何debug

1 可以使用gdb，mingw里面有

2 vscode里面直接debug

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEff166c260028963515d501ffe2e01016截图.png)

点击上图4指向的位置后

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCE04cb9954e97c86ddcdb13462b05e0e44截图.png)

选择上图红色框即可

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEc35ee6d7f2fc3626351a404f616a3e4e截图.png)

#### 总结注意事项

MSVC需要进入Developer Command Prompt for VS2019/2022才能编译c++项目

C++23先阶段就是坑，跳进去就出不来了

推荐大家用GCC

#### 多文件编译

多文件程序生成二进制文件过程

- 头文件引入#include“yourfile.h”

- 那一般都会在yourfile.h中声明,在yourfile.cpp中定义

- 编译命令：

g++-o main.exe main.cpp yourfile.cpp

该命令分实际为两步

- 1、生成object文件

g++-c main.cpp yourfile.cpp

会生成main.o文件以及yourfile.o文件

·2、生成二进制文件（link)

g++-o main.exe main.o yourfile.o

生成main.exe二进制文件

唯一定义

One Definition Rule

#include <yourfile.h>

怎样避免多次引入同样的内容呢？

- 在yourf i le.h文件中写入

方法一：#ifndef YOURFILE_H

#define YOURFILE H

你的代码

#end i f

方法二：#pragma once

示例代码

![](D:/download/youdaonote-pull-master/data/Technology/开发工具/images/WEBRESOURCEcbbb21d4ba8ffa519dc59d74740e5d85截图.png)

main.cpp

```
#include <iostream>
#include "yourfile.h"
using namespace std; // 头文件中不建议加上这行,但是主文件建议加上这行,比较方便

int main()
{
    cout << add(1, 2) << endl;
    return 0;
}
```

yourfile.cpp

```
#include "yourfile.h"

int add(int x, int y)
{
    return x + y;
}
```

yourfile.h

```
#ifndef YOURFILE_H
#define YOURFILE_H

int add(int, int);

#endif
```

方式1、通过命令行编译

g++ -o main.exe main.cpp yourfile.cpp

main.exe执行即可

方式2、分开 先生成obj文件，再链接

g++ -c main.cpp yourfile.cpp

g++ -o main.exe main.o yourfile.o

##### 配置vscode支持多文件编译

用Vscode通过run task运行多文件

- 打开tasks.json文件

- 在args中

去掉

"${fileDirname}\\${fileBasenameNoExtension}.exe",

“file”

添加

"${workspaceFolder}\\*.cpp"

"${workspaceFolder}\\main.exe",

上面配置MSVC的时候有个前缀，具体看最终tasks.json

```
{
    "version": "2.0.0",
    "tasks": [
        {
            "type": "cppbuild",
            "label": "GCC Compiler",
            "command": "W:\\env\\mingw64\\bin\\g++.exe",
            "args": [
                "-std=c++11",
                "-fdiagnostics-color=always",
                "-g",
                // "${file}",
                "${workspaceFolder}\\*.cpp",
                "-o",
                // "${fileDirname}\\${fileBasenameNoExtension}.exe"
                "${workspaceFolder}\\main.exe"
            ],
            "options": {
                "cwd": "${fileDirname}"
            },
            "problemMatcher": [
                "$gcc"
            ],
            "group": "build",
            "detail": "compiler: W:\\env\\mingw64\\bin\\g++.exe"
        },
        {
            "type": "cppbuild",
            "label": "Clang Compiler",
            "command": "W:\\env\\mingw64\\bin\\clang++.exe",
            "args": [
                "-std=c++11",
                "-fcolor-diagnostics",
                "-fansi-escape-codes",
                "-g",
                "${workspaceFolder}\\*.cpp",
                "-o",
                "${workspaceFolder}\\main.exe"
            ],
            "options": {
                "cwd": "${fileDirname}"
            },
            "problemMatcher": [
                "$gcc"
            ],
            "group": "build",
            "detail": "compiler: W:\\env\\mingw64\\bin\\clang++.exe"
        },
        {
            "type": "cppbuild",
            "label": "MSVC Compiler",
            "command": "cl.exe",
            "args": [
                "/Zi",
                "/std:c++latest",
                "/EHsc",
                "/nologo",
                "${workspaceFolder}\\*.cpp",
                "/Fe${workspaceFolder}\\main.exe",
            ],
            "options": {
                "cwd": "${fileDirname}"
            },
            "problemMatcher": [
                "$msCompile"
            ],
            "group": "build",
            "detail": "compiler: cl.exe"
        }
    ]
}
```

模板 github地址

[https://github.com/wade1010/vscodecppenv](https://github.com/wade1010/vscodecppenv)