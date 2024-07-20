断言（assertion）是一种常用的编程手段，用于排除程序中不应该出现的逻辑错误。

使用断言需要包含头文件<cassert>或<assert.h>，头文件中提供了带参数的宏assert，用于程序在运行时进行断言。

语法：assert(表达式);

断言就是判断(表达式)的值，如果为0（false），程序将调用abort()函数中止，如果为非0（true），程序继续执行。

断言可以提高程序的可读性，帮助程序员定位违反了某些前提条件的错误。

注意：

-断言用于处理程序中不应该发生的错误，而非逻辑上可能会发生的错误。

-不要把需要执行的代码放到断言的表达式中。

-断言的代码一般放在函数/成员函数的第一行，表达式多为函数的形参。

```
#include <iostream>
#include <cassert>
using namespace std;

void copy_data(void *ptr1, void *ptr2)
{
    assert(ptr1 && ptr2); //断言ptr1和ptr2都不会为空
    cout << "继续执行复制的代码....." << endl;
}
void test()
{
    int i = 0, j = 0;
    // copy_data(&j, &i);
    copy_data(nullptr, &i);
}
int main()
{
    test();
    return 0;
}
```

C++11静态断言

assert宏是运行时断言，在程序运行的时候才能起作用。

C++11新增了静态断言static_assert，用于在编译时检查源代码。

使用静态断言不需要包含头文件。

语法：static_assert(常量表达式,提示信息);

注意：static_assert的第一个参数是常量表达式。而assert的表达式既可以是常量，也可以是变量。