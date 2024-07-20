# 一、源代码的组织

头文件(*.h)：#include头文件、函数的声明、结构体的声明、类的声明、模板的声明、内联函数、#define和const定义的常量等。

源文件(*.cpp):函数的定义、类的定义、模板具体化的定义。

主程序(main函数所在的程序)：主程序负责实现框架的核心流程，把需要用到的头文件用#include包含进来。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220777.jpg)

按图片中顺序，文件内容如下：

```
#include "tools.h"
#include "girls.h"
void test()
{
    cout << "max(4,6)=" << max(4, 6) << endl;
    cout << "min(4,6)=" << min(4, 6) << endl;
}
int main()
{
    test();
    return 0;
}
```

```
#include "girls.h"
void print(int no, string str)
{
    cout << no << " " << str << endl;
}
```

```
#pragma once
#include <iostream>
#include "tools.h"

using namespace std;
void print(int no, string str);
```

```
#include "tools.h"

int max(int a, int b)
{
    return a > b ? a : b;
}

int min(int a, int b)
{
    return a > b ? b : a;
}
```

```
#pragma once
#include <iostream>
#include "girls.h"
using namespace std;
int max(int a, int b);
int min(int a, int b);
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220519.jpg)

每个文件编译生成的二进制文件可以从VS看到

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220320.jpg)

.obj文件可以一个个文件编译生成。（只能编译源文件，只有源文件才能编译，没有编译头文件的说法）

所有文件都编译完后，就可以链接了，链接是指将编译后的目标文件，以及它们所需要的库文件链接在一起，形成一个整体，这个整体就是可自行的exe文件。

这个demo会产生3个目标文件（girls.obj  tools.obj  demo01.obj），但是在项目的源代码中，还用到了其它的功能，例如cout、endl、string类，它们属于C++标准库，所以链接的时候，要把这三个obj文件和C++标准库文件的头文件一起链接，然后生成可执行的exe文件

# 二、编译预处理

预处理的包括以下方面

1 处理#include头文件包含指令

2 处理#ifdef #else #endif、#ifndef #else #endif条件编译指令

3 处理#define 宏定义

4 为代码添加行号、文件名和函数名

5 删除注释

6 保留部分#pragma编译指令(编译的时候会用到)。

# 三、编译

将预处理生成的文件，经过词法分析、、语法分析、语义分析以及优化和汇编后，编译成若干个目标文件（二进制文件）。

# 四、链接

将编译后的目标文件，以及它们所需要的库文件链接在一起，形成一个整体

# 五、更多细节

1 分开编译的好处：每次只编译修改过的源文件，然后再链接，效率最高。

解释：把预处理后的头文件和源文件打成一个包，一起编译不行吗？也不是不行，是不好，我们现在写的程序都很简单，编译和链接在几秒钟之内可以完成，如果项目很大，有成百上千个源文件，全部编译要花很长的时间，几十分钟都有可能。采用分开编译的方法，每次只编译修改过的源文件，然后再连接，效率最高。

2 编译单个*.cpp文件的时候，必须要让编译器知道名称的存在，否则会出现找不到标识符的错误（字节和间接包含头文件都可以）。

3 编译单个*.cpp文件的时候，编译器值需要知道名称的存在，**不会把它们的定义一起编**译。

把girls.cpp的print方法注释掉，然后还是可以正常编译，但是链接的时候会报错，

```
#include "girls.h"
// void print(int no, string str)
// {
//     cout << no << " " << str << endl;
// }
```

'link_demo.exe' 不是内部或外部命令，也不是可运行的程序

或批处理文件。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220617.jpg)

4 如果函数和类的定义不存在，编译不会报错，但链接会出现无法解析的外部命令。

解释：如上面的实验，报错。

5 链接的时候，变量、函数和类的定义只能有一个，否则会出现重定义的错误。（如果把变量、函数和类的定义放在*.h文件中，*.h会被多次包含，链接前会存在多个副本，如果放在*.cpp文件从，*.cpp文件不会被包含，只会被编译一次，链接前只存在一个版本）

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220829.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220956.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220014.jpg)

6 把变量、函数和类的定义放在*.h中是不规范的做法，如果*.h被多个*.cpp包含，会出现重定义。

7 用#include包含*.cpp也是不规范的，原理同上

6和7 列出来是因为有人经常这么做，这种不规范的做法，并不是一定不行，如果变量、函数和类的定义只被包含一次，是没有问题的，多次就不行了，这种方法害死人，一会行，一会不行，让初学者很困惑，

8 尽可能不适用全局变量，如果一定要用，要在*.h文件中声明（需要加extern关键字），在*.cpp文件中定义

9 全局的const常量在头文件中定义（const常量近在单个文件内有效）

10 *.h文件重复包含的处理只对单个的*.cpp文件有效，不是整个项目

解释：初学者容易短路，以为对头文件做了重复包含处理，头文件在整个项目中只会包含一次 ，其实不是的，没有这回事。

11 函数模板和类模板的声明和定义可以分开书写，但它们的定义并不是真实的定义，只能放在*.h文件中；函数模板和类模板的具体化版本的代码是真实的定义，所以放在*.cpp中。

12  Linux下C++编译和链接的原理与VS一样