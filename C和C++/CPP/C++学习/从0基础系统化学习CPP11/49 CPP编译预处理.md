C++程序编译的过程：预处理->编译（优化、汇编）->链接

预处理与程序员的关系比较大

预处理指令主要有以下三种：

- 包含头文件：#include

- 宏定义：#define （定义宏）、#undef （删除宏）

- 条件编译：#ifdef、#ifndef

# 1）包含头文件

#include 包含头文件有两种方式：

- #include <文件名>:直接从编译器自带的函数库目录中寻找文件。

- #include "文件名"：先从自定义的目录中寻找文件，如果找不到，再从编译器自带的函数库目录中寻找.

#include也包含其它文件，如：*.h、*。cpp或其它 的文件。

C++98标准后的头文件：

- C的标准库：老版本的有.h后缀；新版本没有.h的后缀，增加了字符c的前缀。例如：老版本是<stdio.h>,新版本是<stdio>,新老版本库中的内容是一样的。在程序中，不指定std命名空间也能使用库中内容。

- C++的标准库：老版本的有.h后缀；新版本没有.h的后缀。例如：老版本是<iostream.h>，新版本是<iostream>，老版本已弃用，只能用新版本。在程序中，必须指定std命名空间才能使用库中的内容。

**注意：用户自定义的头文件还是可以用.h为后缀的**

include包含文件的本质是把需要包含的文件的内容复制进来。

# 2)宏定义指令

无参数的宏：#define 宏名 宏内容

有参数的宏：#define MAX(x,y) ((x)>(y)?(x):(y))     

编译的时候，编译器把程序中的宏名用宏内容替换，是为宏展开（宏替换）。

宏可以只有宏名，没有宏内容

在C++中，内联函数可以代替有参数的宏，效果更好。

C++常用的宏：

- 当前源代码文件名：__FILE__

- 当前源代码函数名：__FUNCTION__

- 当前源代码行号:__LINE__

- 编译的日期：__DATE__

- 编译的时间：__TIME__

- 编译的时间戳：__TIMESTAMP__

- 当用C++编译程序时，宏__cplusplus就会被定义

```
#include <iostream>
using namespace std;

#define BH 3
#define MESSAGE "hello world"

void test()
{
    cout << BH << MESSAGE << endl;
    cout << __FILE__ << endl;
    cout << __FUNCTION__ << endl;
    cout << __LINE__ << endl;
    cout << __DATE__ << endl;
    cout << __TIME__ << endl;
    cout << __TIMESTAMP__ << endl;
    cout << __cplusplus << endl;
}
int main()
{
    test();
    return 0;
}
3hello world
main.cpp
test
12
Nov 23 2022
21:07:12
Wed Nov 23 21:07:11 2022
201103
```

# 3)条件编译

最常用的两种：#ifdef、#ifndef    				 if #define  if not # define

#ifdef 宏名

程序段一

#else

程序段二

#endif

含义：如果#ifdef后面宏名已存在，则使用程序段一，否则使用程序段二。

#ifndef 宏名

程序段一

#else

程序段二

#endif

含义：如果#ifndef后面宏名不存在，则使用程序段一，否则使用程序段二。

```
#include <iostream>
using namespace std;

void test()
{
//不同操作系统的宏:__linux_  _WIN32
#ifdef _WIN32
    cout << "这是windows系统" << endl;
    typedef long long int64;
#else
    cout << "这不是windows系统" << endl;
    typedef long int64;
#endif
    int64 a = 10;
    cout << "a=" << a << endl;
}
int main()
{
    test();
    return 0;
}
这是windows系统
a=10
```

条件编译的意思是，如果条件满足，就是用这段代码来编译，如果条件不满足就不用这段代码，不满足就当这段代码不存在，也不会编译它。所以下面这段代码在windows下面是不会报错的，因为另外一个分支的错误代码不会被编译

```
#include <iostream>
using namespace std;

void test()
{
//不同操作系统的宏:__linux_  _WIN32
#ifdef _WIN32
    cout << "这是windows系统" << endl;
    typedef long long int64;
#else
    cout << "这不是windows系统" << endl;
    typedef long int64;
    fasdfas
        fasdfasd
            werqwerqw
#endif
    int64 a = 10;
    cout << "a=" << a << endl;
}
int main()
{
    test();
    return 0;
}
```

if else语句和条件编译不一样，不管什么样的条件，两个分支都会编译。

4）解决头文件中代码重复包含的问题

在C/C++中，在使用预编译指令#include的时候，为了防止头文件被重复包含，有两种方式.

第一种：用#ifdef指令

#ifndef __PERSON__

#define __PERSON__

//代码内容

#endif

第二种：把#pagma once 指令放在文件的开头

#ifndef方式收C/C++语言标准的支持，不受编译器的人和限制；

而#pragma once方式有些编译器不支持。

#ifndef可以针对文件中的部分代码；而#pragma once只能针对整个文件。

#ifndef更加灵活 兼容性也好  #pragma once操作简单 效率高

```
#include <iostream>

#ifndef __PERSON__
#define __PERSON__
class Person
{
};
#endif // DEBUG
using namespace std;

void test()
{
    Person p;
}
int main()
{
    test();
    return 0;
}
```