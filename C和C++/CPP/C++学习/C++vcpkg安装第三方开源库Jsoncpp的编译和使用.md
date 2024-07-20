[https://github.com/open-source-parsers/jsoncpp](https://github.com/open-source-parsers/jsoncpp)

[https://blog.csdn.net/cjmqas/article/details/79282847](https://blog.csdn.net/cjmqas/article/details/79282847)

[https://blog.csdn.net/weixin_42703267/article/details/120603746](https://blog.csdn.net/weixin_42703267/article/details/120603746)

[https://github.com/microsoft/vcpkg/blob/master/docs/README.md](https://github.com/microsoft/vcpkg/blob/master/docs/README.md)

这里介绍两种方法

1、JsonCpp源代码-超级简单

2、生成动态库或者静态库后使用

# 一、JsonCpp源代码-超级简单

下载源码[https://github.com/open-source-parsers/jsoncpp](https://github.com/open-source-parsers/jsoncpp)

进入项目根目录

python amalgamate.py #此步会生成dist文件夹

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240986.jpg)

然后就可以自己写程序来使用jsoncpp了，要包含两个文件才行。

```
#include "json/json.h"
#include "jsoncpp.cpp"
```

具体的程序内容可以看下方源码示例

main.cpp

```
#include <iostream>
#include "json/json.h"
#include "jsoncpp.cpp"

using namespace std;

int main(){
    Json::Value json_temp;
    json_temp["name"]=Json::Value("haha");
    json_temp["age"]=Json::Value(23);

    Json::Value root;
    root["key1"]=json_temp;
    root["key2"].append(1234);
    root["key2"].append("abcd");
    root["key2"].append(1.234);

    //fast无格式输出
    Json::FastWriter fast_writer;
    cout<<"fastwriter: "<<endl;
    cout<<fast_writer.write(root)<<endl;

    //style格式化输出
    Json::StyledWriter style_writer;
    cout<<"stylewriter: "<<endl;
    cout<<style_writer.write(root)<<endl;

    //string输出
    string str=fast_writer.write(root);
    cout<<"string out:"<<endl<<str<<endl;

    //从字符串解析json
    Json::Reader reader;
    Json::Value res;
    if(!reader.parse(str,res)){
        cout<<"parse error"<<endl;
    }else{
        cout<<"parse successfully"<<endl;
        cout<<"key1 is :"<<endl<<res["key1"]<<endl;
    }

}
```

通过以下命令来编译运行程序

```
# -I根据自己dist所在的路径来写
g++ -o main main.cpp -IC:/Users/Administrator/Downloads/jsoncpp/dist
# 执行可执行文件
main.exe
```

输出结果

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240441.jpg)

# 二、生成动态库或者静态库后使用

1、下载vcpkg源码

git clone [https://github.com/microsoft/vcpkg](https://github.com/microsoft/vcpkg)

2、编译安装

bootstrap-vcpkg.bat

编译好以后会在同级目录下生成vcpkg.exe文件

3、使用

我先设定vcpkg默认安装64位的包

对于 Windows 平台， vcpkg 默认安装32位库，如果想要设置为默认安装64位库，在环境变量中加上 **VCPKG_DEFAULT_TRIPLET=x64-windows** 即可。

3-1、查看支持的开源库列表

.\vcpkg.exe search

3-2、安装一个开源库

静态库：  .\vcpkg.exe install jsoncpp --triplet=x64-mingw-static      下面第一遍师范用的是静态库

（动态库  .\vcpkg.exe install jsoncpp --triplet=x64-mingw-dynamic）

这里安装后所在目录

W:\env\vcpkg\installed\x64-mingw-static       其中 W:\env\vcpkg是我的安装目录，从自己的安装目录找即可

3-3、列出已安装的开源库

.\vcpkg.exe list

4、配合vscode使用

测试代码

main.cpp

```
#include "json/json.h"
#include <iostream>
#include <memory>
/**
 * \brief Parse a raw string into Value object using the CharReaderBuilder
 * class, or the legacy Reader class.
 * Example Usage:
 * $g++ readFromString.cpp -ljsoncpp -std=c++11 -o readFromString
 * $./readFromString
 * colin
 * 20
 */
int main()
{
    const std::string rawJson = R"({"Age": 20, "Name": "colin"})";
    const auto rawJsonLength = static_cast<int>(rawJson.length());
    constexpr bool shouldUseOldWay = false;
    JSONCPP_STRING err;
    Json::Value root;

    if (shouldUseOldWay)
    {
        Json::Reader reader;
        reader.parse(rawJson, root);
    }
    else
    {
        Json::CharReaderBuilder builder;
        const std::unique_ptr<Json::CharReader> reader(builder.newCharReader());
        if (!reader->parse(rawJson.c_str(), rawJson.c_str() + rawJsonLength, &root,
                           &err))
        {
            std::cout << "error" << std::endl;
            return EXIT_FAILURE;
        }
    }
    const std::string name = root["Name"].asString();
    const int age = root["Age"].asInt();

    std::cout << name << std::endl;
    std::cout << age << std::endl;
    return EXIT_SUCCESS;
}
```

4-1、配置code runner

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240388.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240966.jpg)

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240184.jpg)

然后就可以使用了

```
[Running] cd "w:\workspace\cpp\cmake_3rd\" && g++ -std=c++11 main.cpp -o main -I W:/env/vcpkg/installed/x64-mingw-static/include -L W:/env/vcpkg/installed/x64-mingw-static/lib -ljsoncpp && "w:\workspace\cpp\cmake_3rd\"main
colin
20

[Done] exited with code=0 in 1.167 seconds
```

4-2、在c_cpp_properties.json和tasks.json中使用

4-2-1、c_cpp_properties.json中配置

```
{
    "configurations": [
        {
            "name": "Win32",
            "includePath": [
                "${workspaceFolder}/**",
                "${vcpkgRoot}/x64-mingw-static/include"
            ],
            "defines": [
                "_DEBUG",
                "UNICODE",
                "_UNICODE"
            ],
            "windowsSdkVersion": "10.0.19041.0",
            "compilerPath": "W:/env/mingw64/bin/g++.exe",
            "cStandard": "c11",
            "intelliSenseMode": "windows-gcc-x64",
            "cppStandard": "c++11"
        }
    ],
    "version": 4
}
```

4-2-2、tasks.json中配置

```
{
    "version": "2.0.0",
    "tasks": [
        {
            "type": "cppbuild",
            "label": "G++",
            "command": "W:/env/mingw64/bin/g++.exe",
            "args": [
                "-fdiagnostics-color=always",
                "-g",
                "${file}",
                "-o",
                "${fileDirname}\\${fileBasenameNoExtension}.exe",
                "-I",
                "W:/env/vcpkg/installed/x64-mingw-static/include",
                "-L",
                "W:/env/vcpkg/installed/x64-mingw-static/lib",
                "-ljsoncpp",
            ],
            "options": {
                "cwd": "W:/env/mingw64/bin"
            },
            "problemMatcher": [
                "$gcc"
            ],
            "group": "build",
            "detail": "compiler: W:/env/mingw64/bin/g++.exe"
        }
    ]
}
```

测试

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240434.jpg)

```
 *  Executing task: G++ 

Starting build...
W:/env/mingw64/bin/g++.exe -fdiagnostics-color=always -g W:\workspace\cpp\cmake_3rd\main.cpp -o W:\workspace\cpp\cmake_3rd\main.exe -I W:/env/vcpkg/installed/x64-mingw-static/include -L W:/env/vcpkg/installed/x64-mingw-static/lib -ljsoncpp

Build finished successfully.
 *  Terminal will be reused by tasks, press any key to close it.
```

运行可执行文件

```
(base) W:\workspace\cpp\cmake_3rd>main.exe
colin
20
```

动态库

动态库  .\vcpkg.exe install jsoncpp --triplet=x64-mingw-dynamic

这个执行后会在W:\env\vcpkg\installed\x64-mingw-dynamic\bin目录下生成一个libjsoncpp.dll的动态库

将这个复制到项目根目录，这是运行我们编译后可执行文件时，需要的。

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240793.jpg)

1、配置code runner

```
-I W:/env/vcpkg/installed/x64-mingw-dynamic/include -L W:/env/vcpkg/installed/x64-mingw-dynamic/lib -ljsoncpp 
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172240124.jpg)

```
[Running] cd "w:\workspace\cpp\cmake_3rd\" && g++ -std=c++11 main.cpp -o main -I W:/env/vcpkg/installed/x64-mingw-dynamic/include -L W:/env/vcpkg/installed/x64-mingw-dynamic/lib -ljsoncpp && "w:\workspace\cpp\cmake_3rd\"main
colin
20

[Done] exited with code=0 in 1.214 seconds
```

2、在c_cpp_properties.json和tasks.json中使用

c_cpp_properties.json

```
{
    "configurations": [
        {
            "name": "Win32",
            "includePath": [
                "${workspaceFolder}/**",
                "${vcpkgRoot}/x64-mingw-dynamic/include"
            ],
            "defines": [
                "_DEBUG",
                "UNICODE",
                "_UNICODE"
            ],
            "windowsSdkVersion": "10.0.19041.0",
            "compilerPath": "W:/env/mingw64/bin/g++.exe",
            "cStandard": "c11",
            "intelliSenseMode": "windows-gcc-x64",
            "cppStandard": "c++11"
        }
    ],
    "version": 4
}
```

tasks.json

```
{
    "version": "2.0.0",
    "tasks": [
        {
            "type": "cppbuild",
            "label": "G++",
            "command": "W:/env/mingw64/bin/g++.exe",
            "args": [
                "-fdiagnostics-color=always",
                "-g",
                "${file}",
                "-o",
                "${fileDirname}\\${fileBasenameNoExtension}.exe",
                "-I",
                "W:/env/vcpkg/installed/x64-mingw-dynamic/include",
                "-L",
                "W:/env/vcpkg/installed/x64-mingw-dynamic/lib",
                "-ljsoncpp",
            ],
            "options": {
                "cwd": "W:/env/mingw64/bin"
            },
            "problemMatcher": [
                "$gcc"
            ],
            "group": "build",
            "detail": "compiler: W:/env/mingw64/bin/g++.exe"
        }
    ]
}
```

后面步骤同上