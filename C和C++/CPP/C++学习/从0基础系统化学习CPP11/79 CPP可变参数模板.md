可变参数模板

可变参数模版是C++11新增的最强大的特性之一，它对参数进行了泛化，能支持任意个数、任意数据类型的参数。

```
#include <iostream>
using namespace std;
//可变参数模板

template <typename T>
void show(T str)
{
    cout << "show=" << str << endl;
}

//递归终止时调用的非模板函数,函数名要与展开参数包的递归函数模板相同.
void print()
{
    cout << "递归终止" << endl;
}
//展开参数包的递归函数模板
template <typename T, typename... Args>
void print(T arg, Args... args)
{
    cout << "参数:" << arg << endl;
    show(arg);

    cout << "还有" << sizeof...(args) << "个参数未展开." << endl;

    print(args...);
}

template <typename... Args>
void func(const string &str, Args... args)
{
    cout << "口号:" << str << endl;
    print(args...);
    cout << "ok" << endl;
}

void test()
{
    print(1, "hello", 3.3, 'c');
    cout << endl;
    print(2, 3, "world");
    cout << endl;
    func("hello", "a1", "a2", "a2");
}
int main()
{
    test();
    return 0;
}
/* 参数:1
show=1
还有3个参数未展开.
参数:hello
show=hello
还有2个参数未展开.
参数:3.3
show=3.3
还有1个参数未展开.
参数:c
show=c
还有0个参数未展开.
递归终止

参数:2
show=2
还有2个参数未展开.
参数:3
show=3
还有1个参数未展开.
参数:world
show=world
还有0个参数未展开.
递归终止

口号:hello
参数:a1
show=a1
还有2个参数未展开.
参数:a2
show=a2
还有1个参数未展开.
参数:a2
show=a2
还有0个参数未展开.
递归终止
ok */
```

void print(T arg, Args... args)

这里看上去有两个参数，

print(args...);

这里看上去有一个参数

编译器会做特别的处理，其实void print(T arg, Args... args) 这是多个参数，不是两个

print(args...);这里其实也是多个参数,不是一个.

```
template <typename T, typename... Args>
```

第一个是普通的模板参数,没什么特别,

第二个模板参数中间由... 表示可变参数

```
void print(T arg, Args... args)
```

第一个形参arg用T来表示，表示已展开的形参，也就是这一次取出来的参数。

第二个形参args用Args来定义，表示尚未展开的参数包。

需要定义一个非模板函数，这个函数没有参数，表示参数包总已经没有参数了，它可以理解为print模板函数的没有参数的具体化版本。如果没有它 编译会报错。

未展开的参数个数用sizeof...()

    cout << "还有" << sizeof...(args) << "个参数未展开." << endl;