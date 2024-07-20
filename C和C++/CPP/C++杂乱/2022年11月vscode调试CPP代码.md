一、准备

环境默认都装好了哈。

创建cpp_debug项目

使用vscode 打开

插件版本如下

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241989.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241388.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241464.jpg)

创建目录和文件

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241965.jpg)

内容如下

swap.h

```
void swap(int &a, int &b);
```

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

CMakeLists.txt

```
#规定cmake的最低版本要求
cmake_minimum_required (VERSION 3.10)
#设置c++编译器
set(CMAKE_CXX_COMPILER "g++")
#项目的名称，可以和文件夹名称不同
project(MYSWAP)
#添加头文件的搜索路径
include_directories(${PROJECT_SOURCE_DIR}/header)
#将源文件列表写在变量SrcFiles中
aux_source_directory(./src SrcFiles)
#设置可执行文件输出路径
set(EXECUTABLE_OUTPUT_PATH  ${PROJECT_SOURCE_DIR}/bin)
#设置可执行文件的名称，make之后bin目录下出现my_swap.exe
add_executable(my_swap ${SrcFiles})

```

二、编译、运行

1、手动编译

cd build

cmake -G "MinGW Makefiles" ..        (这个是windows执行，linux执行 cmake ..就行)

mingw32-make.exe       	（这个是windows执行，Linux执行 make就行）

cd ..\bin

my_swap.exe

大致步骤如下图

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241868.jpg)

2、自动编译

- 按住Ctrl+Shift+P，输入CMake: Configure，配置CMake。

- 

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241950.jpg)

- 

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241376.jpg)

点击完上面的图，会自动执行一些 命令，如下图

![](images/WEBRESOURCE0a925fb9aba6e36405755523cd23b085截图.png)

这里就OK了，你可以点击下面的build和三角，输出也如下图终端里面的内容

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241836.jpg)

三、调试

在.vscode目录下面创建 launch.json

内容如下

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
            "miDebuggerPath": "W:\\env\\mingw64\\bin\\gdb.exe", // 调试器mingw64的安装路径 ！！！ 只修改这个路径就行。！！！
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

在.vscode目录下面创建tasks.json

其实就是把手动的步骤写进去，打上label.

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

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241319.jpg)

打上断点，然后按F5就能调试了

![](https://gitee.com/hxc8/images3/raw/master/img/202407172241562.jpg)