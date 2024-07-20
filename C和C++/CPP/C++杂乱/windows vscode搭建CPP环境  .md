windows vscode搭建C++环境

[https://cmake.org/download/](https://cmake.org/download/)  

这里是下载这个zip包[cmake-3.26.0-windows-x86_64.zip](https://github.com/Kitware/CMake/releases/download/v3.26.0/cmake-3.26.0-windows-x86_64.zip)  也可以下载msi安装包

[https://sourceforge.net/projects/mingw-w64/files/](https://sourceforge.net/projects/mingw-w64/files/)

文件清单

x86_64-posix-sjlj

x86_64-posix-seh

x86_64-win32-sjlj

x86_64-win32-seh

i686-posix-sjlj

i686-posix-dwarf

i686-win32-sjlj

i686-win32-dwarf

释义1：

DWARF：一种带调试信息(DWARF- 2（DW2）EH)的包, 所以比一般的包尺寸大，仅支持32位系统

SJLJ：跨平台，支持32，64位系统，缺点是：运行速度稍慢，GCC不支持

SEH: 调用系统机制处理异常，支持32，64位系统，缺点是：Gcc不支持（即将支持）

释义2：

x86_64: 简称X64，64位操作系统

i686: 32位操作系统 (i386的子集)，差不多奔腾2(1997年5月)之后的CPU都是可以用的；

释义3：

posix: 启用了C++ 11 多线程特性

win32: 未启用 （从时间线上正在尝试也启用部分 Threading）

总结

下载x86_64-posix-seh，在win平台体验最佳

 

开全局科学上网就很快。

加入到环境变量，如果设置的是用户的，就需要重启，如果是系统的就不需要重启（尴尬，我的重启才行）

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241658.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241938.jpg)

mian.cpp

```
#include <iostream>

using namespace std;

void swap(int &a, int &b)
{
    int temp;
    temp = a;
    a = b;
    b = temp;
}

int main(int argc, char const *argv[])
{
    int val1 = 10;
    int val2 = 20;
    cout << "before swap" << endl;
    cout << "val1 = " << val1 << endl;
    cout << "val2 = " << val2 << endl;
    swap(val1, val2);
    cout << "after swap" << endl;
    cout << "val1 = " << val1 << endl;
    cout << "val2 = " << val2 << endl;
    return 0;
}

```

g++ main.cpp

执行后生成一个a.exe

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241887.jpg)

执行a.exe

```
W:\workspace\cpp\cpp_evn_demo>a.exe
before swap
val1 = 10
val2 = 20
after swap
val1 = 20
val2 = 10
```

带参数的编译

-g 生成带调试的可执行文件

-o 执行输出文件名

g++ -g main.cpp -o my_single_swap

dir看下两个可执行文件大小

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241688.jpg)

  

调试

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241260.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241666.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241089.jpg)

 点击上面红色框后，会自动生成如下内容

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241846.jpg)

打断点

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241734.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241328.jpg)

点击里面的启动调试，或者按F5

```
W:\workspace\cpp\cpp_evn_demo>W:/anaconda3/Scripts/activate.bat

(base) W:\workspace\cpp\cpp_evn_demo> cmd /C "c:\Users\Administrator\.vscode\extensions\ms-vscode.cpptools-1.12.4-win32-x64\debugAdapters\bin\WindowsDebugLauncher.exe --stdin=Microsoft-MIEngine-In-jpghamhw.mz1 --stdout=Microsoft-MIEngine-Out-wkhdiww4.jas --stderr=Microsoft-MIEngine-Error-xp51q2tb.qb2 --pid=Microsoft-MIEngine-Pid-jwus23pw.n0f --dbgExe=W:\env\mingw64\bin\gdb.exe --interpreter=mi "

(base) W:\workspace\cpp\cpp_evn_demo> cmd /C "c:\Users\Administrator\.vscode\extensions\ms-vscode.cpptools-1.12.4-win32-x64\debugAdapters\bin\WindowsDebugLauncher.exe --stdin=Microsoft-MIEngine-In-4gnrixhy.3sa --stdout=Microsoft-MIEngine-Out-llpq0few.2po --stderr=Microsoft-MIEngine-Error-d0uwlgxf.b20 --pid=Microsoft-MIEngine-Pid-j11l1tz4.vyf --dbgExe=W:\env\mingw64\bin\gdb.exe --interpreter=mi "
before swap
val1 = 10
val2 = 20
after swap
val1 = 20
val2 = 10
```

项目修改成多文件

main.cpp

```
#include <iostream>
#include "swap.h" //这里不能是 <swap.h>
using namespace std;

int main(int argc, char const *argv[])
{
    int val1 = 10;
    int val2 = 20;
    cout << "before swap" << endl;
    cout << "val1 = " << val1 << endl;
    cout << "val2 = " << val2 << endl;
    swap(val1, val2);
    cout << "after swap" << endl;
    cout << "val1 = " << val1 << endl;
    cout << "val2 = " << val2 << endl;
    return 0; 
}


```

swap.cpp

```
#include "swap.h"
void swap(int &a, int &b)
{
    int temp;
    temp = a;
    a = b;
    b = temp;
}

```

swap.h

```
void swap(int &a, int &b);
```

调试

想要F5自动调试需要配置json文件

- launch.json – for debug

- tasks.json – for build before debug

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241440.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241974.jpg)

打一个断点

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241918.jpg)

然后按F5调试，选择第一个，如下图

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241516.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241916.jpg)

点击仍要调试看看

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241369.jpg)

提示找不到main.exe，因为task.json是自动编译单个文件的，它不会自动去识别多个文件，如果你想调试多个文件，需要手动配置、

如果是多文件的调试，就需要自己修改

修改launch.json

```
{
    // 使用 IntelliSense 了解相关属性。 
    // 悬停以查看现有属性的描述。
    // 欲了解更多信息，请访问: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "(gdb) Launch", // 配置名称，将会在启动配置的下拉菜单中显示，一般不用修改  
            "type": "cppdbg", // 配置类型，这里只能为cppdbg  
            "request": "launch", // 请求配置类型，可以为launch（启动）或attach（附加）  
            // 将要调试的可执行程序的路径 
            "program": "${workspaceFolder}\\my_multi_swap.exe",
            // 程序调试时传递给程序的命令行参数，一般设为空即可
            "args": [],
            // 默认为false，设为true时，就算没有设置断点，调试时程序也将暂停在程序入口处
            "stopAtEntry": false, 
            // 要调试的可执行程序的工作路径 
            "cwd": "${workspaceFolder}",
            "environment": [],
            "externalConsole": false, // 调试时是否显示控制台窗口，设置为true显示控制台  
            "MIMode": "gdb",
            "miDebuggerPath": "W:\\env\\mingw64\\bin\\gdb.exe", // 调试器mingw64的安装路径  
            // "preLaunchTask": "Build", 这里直接调试可自行文件，就暂时不用pre任务
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

cmake使用

根目录创建CMakeLists.txt

```
 project(MYSWAP)
add_executable(my_cmake_swap main.cpp swap.cpp )
```

my_cmake_swap是生成的可执行文件的名字，后面是要编译的cpp，空格分隔

方式一、vscode帮我们生成

ctrl+shift+p

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241720.jpg)

点击后选择GCC8.1.0

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241241.jpg)

点击后，终端输出如下

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241979.jpg)

根目录会多一个build目录

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241514.jpg)

内容大致如下：

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241378.jpg)

 cd build

cmake ..

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241856.jpg)

然后执行make,windows上make叫做mingw32-make.exe  （这个终端输入mingw然后按tab键，不会自动补全。。。）

```
(base) W:\workspace\cpp\cpp_evn_demo\build>mingw32-make.exe
[ 33%] Building CXX object CMakeFiles/my_cmake_swap.dir/main.cpp.obj
[ 66%] Building CXX object CMakeFiles/my_cmake_swap.dir/swap.cpp.obj
[100%] Linking CXX executable my_cmake_swap.exe
[100%] Built target my_cmake_swap
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241816.jpg)

调试

修改launch.json

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241861.jpg)

保存后，按F即可

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241814.jpg)

方式二、自己生成

删掉方式一的build目录

mkdir build

cd build

cmake -G "MinGW Makefiles" ..   

上面一步，如果用cmake ..执行会使用MSVS，即微软的C++编译，如下图

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241070.jpg)

上面的问题除了添加-G 外，还能修改配置，如下

ctrl+shift+p

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241821.jpg)

原内容

```
{
    "configurations": [
        {
            "name": "Win32",
            "includePath": [
                "${workspaceFolder}/**"
            ],
            "defines": [
                "_DEBUG",
                "UNICODE",
                "_UNICODE"
            ],
            "windowsSdkVersion": "10.0.19041.0",
            "compilerPath": "W:/Program Files (x86)/Microsoft Visual Studio/2022/BuildTools/VC/Tools/MSVC/14.33.31629/bin/Hostx64/x64/cl.exe",
            "cStandard": "c17",
            "cppStandard": "c++17",
            "intelliSenseMode": "windows-msvc-x64",
            "configurationProvider": "ms-vscode.cmake-tools"
        }
    ],
    "version": 4
}
```

修改为：

```
{
    "configurations": [
        {
            "name": "Win32",
            "includePath": [
                "${workspaceFolder}/**"
            ],
            "defines": [
                "_DEBUG",
                "UNICODE",
                "_UNICODE"
            ],
            "windowsSdkVersion": "10.0.19041.0",
            "compilerPath": "W:\\env\\mingw64\\bin\\gcc.exe",
            "cStandard": "c17",
            "cppStandard": "c++17",
            "intelliSenseMode": "windows-gcc-x86",
            "configurationProvider": "ms-vscode.cmake-tools"
        }
    ],
    "version": 4
}
```

上面的修改，后来试了，并没卵用。

mingw32-make.exe

 

步骤整理下也就是下面的步骤

```
# 1. 打开新的终端，在当前目录下，创建build文件夹
mkdir build
# 2. 进入到build文件夹下
cd build
# 3. 编译上级目录的CMakeLists.txt，生成Makefile和其他文件
cmake ..   # Linux平台
cmake -G "MinGW Makefiles" ..   # Windows平台
# 4. 执行make命令，生成target
make   # Linux平台
mingw32-make.exe  # Windows平台

```

也就是把上面的步骤配置到vscode的launch.json和taks.json

配置tasks.json

按住按住Ctrl+Shift+P，输入Tasks: Configure Default Build Task，进行配置。(直接再根目录下面的.vscode下面新建也tasks.json也行)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241634.jpg)

PS:新的CMakeLists.txt为：（这个可以不修改，跟开始保持一致，这里这里完善下）

```
#规定cmake的最低版本要求
cmake_minimum_required (VERSION 3.10)
#设置c++编译器
set(CMAKE_CXX_COMPILER "g++")
#项目的名称，可以和文件夹名称（HELLO）不同
project(MYSWAP)
#设置可执行文件输出路径
set(EXECUTABLE_OUTPUT_PATH  ${PROJECT_SOURCE_DIR}/bin)
add_executable(my_cmake_swap main.cpp swap.cpp)
```

内容如下

```
{
    "version": "2.0.0",
    "options": {
        "cwd": "${workspaceFolder}\\build"
    },
    "tasks":[
        {
            "type": "shell",
            "label": "cmake",
            "command": "cmake",     
            // 命令行参数
            "args": [
                "-G",
                "MinGW Makefiles",
                ".."
            ]
        },
        {
            "label": "make",
            "group": {
                "kind": "build",
                "isDefault": true
            },
            "command": "mingw32-make.exe",
            // 命令行参数
            "args": []
        },
        {
            "label": "Build",
            "dependsOn":[
                "cmake",
                "make"
            ]
        }

    ]   
}
```

配置launch.json配置

按下F5调试，进行launch.json文件配置。（直接创建也行）

```
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "(gdb) Launch", // 配置名称，将会在启动配置的下拉菜单中显示，一般不用修改  
            "type": "cppdbg", // 配置类型，这里只能为cppdbg  
            "request": "launch", // 请求配置类型，可以为launch（启动）或attach（附加）  
            // 将要调试的可执行程序的路径 
            "program": "${workspaceFolder}\\bin\\my_cmake_swap.exe",
            // 程序调试时传递给程序的命令行参数，一般设为空即可
            "args": [],
            // 默认为false，设为true时，就算没有设置断点，调试时程序也将暂停在程序入口处
            "stopAtEntry": false, 
            // 要调试的可执行程序的工作路径 
            "cwd": "${workspaceFolder}\\bin",
            "environment": [],
            "externalConsole": false, // 调试时是否显示控制台窗口，设置为true显示控制台  
            "MIMode": "gdb",
            "miDebuggerPath": "W:\\env\\mingw64\\bin\\gdb.exe", // 调试器mingw64的安装路径  
            "preLaunchTask": "Build",
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

然后打断点，再按F5就行了

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241663.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241359.jpg)