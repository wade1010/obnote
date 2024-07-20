 

```
#include "iostream"
using namespace std;
template <typename T1, typename T2>
void func(T1 a, T2 b)
{
    //其它代码

    ??? tmp = a + b; //返回值该用什么呢?

    //其它代码
}
void test()
{
}
int main()
{
    test();
    return 0;
}
```

1 decltype关键字

在C++中，decltype操作符，用于查询表达式的数据类型。

语法：decltype(expression) var;

decltype分析表达式并得到它的类型，不会计算执行表达式。函数调用也是一种表达式，因此不必担心在使用decltype时执行函数。

decltype推导规则（按步骤）：

1 如果expression是一个没有用括号括起来的标识符，则 var 的类型与该标识符的类型相同，包括const等限定符。

2 如果expression是一个函数调用，则var的类型与函数的返回值类型相同（函数不能  返回void，但是可以返回void *）

3 如果expression是一个左值（能取地址）（要排除第一种情况）、或者用括号括起来的标识符，那么var的类型时expression的引用。

4 如果上面的条件都不满足，则var的类型与expression的类型相同。

如果需要多次使用decltype,可以结合typedef和using。（可以给推导出来的数据类型起别名）

总的来说：decltype的结果要么和表达式的类型相同，要么就是表达式的类型的引用，只有这两种情况，记住这一点就行。

decltype和auto都可以推导表达式的数据类型，但是它们本质上有区别，

![](https://gitee.com/hxc8/images2/raw/master/img/202407172218816.jpg)

这种语法也可以用于函数定义：

```
auto func(int x, double y) -> int
{
    cout << "函数体" << endl;
}
```

```
#include "iostream"
using namespace std;

int func()
{
    cout << "调用了func函数" << endl;
    return 11;
}
auto func2(int x, double y) -> int
{
    cout << "函数体" << endl;
    return 1;
}
void test()
{
    // short b = 5;
    // short *a = &b;
    // decltype(a) da;

    // short a = 5;
    // short &ra = a;
    // short b = 10;
    // decltype(ra) da = b;

    short b = 10;
    decltype(func()) da = b; //不会调用函数
    //上面填函数调用和函数名是两回事,如果只填函数名,得到的是函数的类型,不是函数返回值的类型. 如下
    decltype(func) *da2 = func;
    da2();

    // decltype(func()) f; //本质区别,不会调用func
    auto f = func(); //本质区别,会调用func,且用auto的时候需要初始值,要不然没东西可以推导
}
// template <typename T1, typename T2>
// void func3(T1 a, T2 b)
// {
//     //其它代码

//     // ? ? ? tmp = a + b; //返回值该用什么呢?

//     //其它代码
// }

template <typename T1, typename T2>
auto func3(T1 a, T2 b) -> decltype(a + b)
{
    decltype(a + b) tmp = a + b;
    return tmp;
}

int main()
{
    test();
    auto ret = func3(1, 3.3);
    cout << "ret=" << ret << endl;
    return 0;
}
```

C++14的auto关键字

C++14标准对函数返回类型推导规则做了优化，函数的返回值可以auto，不必尾随返回类型

```
#include "iostream"
using namespace std;

template <typename T1, typename T2>
auto func3(T1 a, T2 b)
{
    decltype(a + b) tmp = a + b;
    return tmp;
}

int main()
{
    auto ret = func3(1, 3.3);
    cout << "ret=" << ret << endl;
    return 0;
}
```