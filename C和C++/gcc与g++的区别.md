一、编译的四个阶段

预处理：编译处理宏定义等宏命令（eg:#define）——生成后缀为“.i”的文件 　　

编译：将预处理后的文件转换成汇编语言——生成后缀为“.s”的文件 　　

汇编：由汇编生成的文件翻译为二进制目标文件——生成后缀为“.o”的文件 　　

连接：多个目标文件（二进制）结合库函数等综合成的能直接独立执行的执行文件——生成后缀为“.out”的文件

在我们理解了上述四个流程后，我们在关注gcc和g++在流程上的区别。

gcc无法进行库文件的连接，即无法编译完成步骤4；而g++则能完整编译出可执行文件。（实质上，g++从步骤1-步骤3均是调用gcc完成，步骤4连接则由自己完成）

二、gcc 与g++的区别

首先说明：gcc 和 GCC 是两个不同的东西

GCC:GNU Compiler Collection(GUN 编译器集合)，它可以编译C、C++、JAV、Fortran、Pascal、Object-C、Ada等语言。

gcc是GCC中的GUN C Compiler（C 编译器）

g++是GCC中的GUN C++ Compiler（C++编译器）

误区一：gcc只能编译C代码，g++只能编译c++代码。

事实上，二者都可以编译c或cpp文件。

gcc和g++的主要区别

对于 .c和.cpp文件，gcc分别当做c和cpp文件编译（cpp的语法规则比c的更强一些）

对于 .c和.cpp文件，g++则统一当做cpp文件编译

误区二：编译只能使用gcc,连接只能使用g++

这句话混淆了概念。编译可以用 gcc 或 g++，而链接可以用 g++ 或者 gcc-lstdc++。

因为 gcc 命令不能自动和 C++ 库链接，所以通常使用 g++ 来完成链接。

但在编译阶段，g++ 会自动调用 gcc，二者等价。

在编译阶段，g++会调用gcc，对于c++代码，两者是等价的，但是因为gcc命令不能自动和C++程序使用的库联接，所以通常用g++来完成链接，为了统一起见，干脆编译/链接统统用g++了，这就给人一种错觉，好像cpp程序只能用g++似的。

误区三：extern “C” 与 gcc/g++ 有关系

实际上并无关系，

无论是 gcc 还是 g++，用 extern “c” 时，都是以 C 的命名方式来为symbol 命名，

否则，都以 C++ 方式为函数命名。

这里以reciprocal.cpp为例：

#include <cassert>

#include "reciprocal.hpp"

double reciprocal (int i) {

// I should be non-zero.

assert (i != 0);

return 1.0/i;

}

1. 未加 extern “c”时，reciprocal.hpp代码为：

//一段代码

extern double reciprocal (int i);

命令：

g++ -S reciprocal.cpp

less reciprocal.s

使用gcc和g++编译运行结果：

未加extern “C”时，函数reciprocal用gcc和g++编译得到的函数名是一样的，都是以C++的命名方式。

2) 加 extern “c”时，reciprocal.hpp代码为：

extern "C" {

//一段代码

extern double reciprocal (int i);

使用gcc和g++编译运行结果：

也就是说，添加extern “C”后，函数reciprocal用gcc和g++编译得到的函数名是一样的，都是以C的命名方式。

由此可见，extern “C”与采用gcc/g++并无关系。

误区四：gcc不会定义__cplusplus宏，而g++会

实际上，这个宏只是标志着编译器将会把代码按C还是C++语法来解释，如上所述，如果后缀为.c，并且采用gcc编译器，则该宏就是未定义的，否则，就是已定义。

gcc在编译.c文件时，可使用的预定义宏是比较少的，很多都是未定义的。

gcc在编译cpp文件时、g++在编译c文件和cpp文件时（这时候gcc和g++调用的都是cpp文件的编译器），会加入一些额外的宏，这些宏如下：

#define **GXX_WEAK** 1

#define __cplusplus 1

#define __DEPRECATED 1

#define **GNUG** 4

#define __EXCEPTIONS 1

#define **private_extern** extern

因此，我们总是会看到如下格式的代码（功能是对编译器提示使用C的方式来处理函数）：

其中，__cplusplus是c++定义的宏，如果gcc在编译cpp文件时、g++在编译c文件和cpp文件时，extern c声明会有效。如果是gcc在编译.c文件时，那么，extern c声明无效。

#ifdef __cplusplus

extern "C" {

#endif

//一段代码

#ifdef __cplusplus

}

#endif

为什么需要使用extern “C”呢？C++之父在设计C++之时，考虑到当时已经存在了大量的C代码，为了支持原来的C代码和已经写好C库，需要在C++中尽可能的支持C，而extern “C”就是其中的一个策略。

试想这样的情况:一个库文件已经用C写好了而且运行得很良好，这个时候我们需要使用这个库文件，但是我们需要使用C++来写这个新的代码。如果这个代码使用的是C++的方式链接这个C库文件的话，那么就会出现链接错误。

C和C++对函数的处理方式是不同的。extern“C”是使C++能够调用C写作的库文件的一个手段，如果要对编译器提示使用C的方式来处理函数的话，那么就要使用extern “C”来说明。

————————————————

版权声明：本文为CSDN博主「wsqyouth」的原创文章，遵循CC 4.0 BY-SA版权协议，转载请附上原文出处链接及本声明。

原文链接：[https://blog.csdn.net/u013457167/article/details/80222557](https://blog.csdn.net/u013457167/article/details/80222557)