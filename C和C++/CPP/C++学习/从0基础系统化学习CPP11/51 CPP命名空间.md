在实际开发中，较大的羡慕会使用大量的全局名字，如类、函数、模板、变量等，很容易出现名字冲突的情况。

命名空间分割了全局空间，每个命名空间是一个作用域，防止名字冲突

一、语法

创建命名空间：

namespace 命名空间的名字

{

//类、函数、模板、变量的声明和定义

}

创建命名空间的别名

namespace 别名=原名；

二、使用命名空间

在同一命名空间的名字可以直接访问，该命名空间之外的代码则必须明确指出命名空间。

1 运算符::

语法：命名空间::名字

简单明了，且不会造成任何冲突，但使用起来比较繁琐。

2 using声明

语法：using 命名空间::名字

用using声明后，就可以直接使用名称。

如果该声明区域有同名的名字**，则会报错。**

3 using编译指令

语法：using namespace 命名空间

using编译指令将使整个命名空间中的名字可用。如果声明区域有相同的名字，局部版本将**隐藏**命名空间中的名字，不过可以使用域名解析符使用命名空间的名称。

## 注意事项：

1 命名空间是全局的，可以分布在多个文件中。

2 命名空间可以嵌套。

3 在命名空间中声明全局变量，而不是使用外部全局变量和静态变量。（开发经验）

如果全局变量，你只在源文件中定义，不在头文件中声明，那么它只能在命名空间内部使用，外部根本不知道到，效果和静态变量是一样的。

4 对using声明，首选将其作用域设置为局部而不是全局。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220100.jpg)

  意思就是放在函数里面，而不是放到外面。

5 不要在头文件中使用using编译指令，如果非要使用，应将它放在所有的#include之后

如果那么做，整个命名空间中的名称将在整个项目的全局区域可用，与命名空间的设计理念不符。（开发经验）

6 匿名的命名空间，从创建的位置到文件结束有效。

如果命名空间没有名字，那么它里面的名称在当前文件中可以直接使用，且，因为它没有名字，所以其它的人和程序都用不了它里面的东西。

```
#include <iostream>
using namespace std;
namespace
{
    int ii = 11;
}
void test()
{
    cout << ii << endl;
}
int main()
{
    test();
    return 0;
}
```