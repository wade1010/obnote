std::function模板类是一个通用的可调用对象的包装器，用简单的、统一的方式处理可调用对象。

template<class _Fty>

class function......

_Fty是可调用对象的类型，格式：返回列表(参数列表)。

包含头文件 #include <functional>

注意：

1 重载了bool运算符，用于判断是否包装了可调用对象。

2 如果std::function对象未包装可调用对象，使用std::function对象将抛出异常 std::bad_function_call

```
#include <iostream>
#include <functional>
using namespace std;
//普通函数
void show(int a, const string &b)
{
    cout << a << b << endl;
}

//类中有静态成员函数
struct AA
{
    static void show(int a, const string &b)
    {
        cout << a << b << endl;
    }
};

//仿函数
struct BB
{
    void operator()(int a, const string &b)
    {
        cout << a << b << endl;
    }
};
//类中有普通函数
struct CC
{
    void show(int a, const string &b)
    {
        cout << a << b << endl;
    }
};

//可以被转换为普通函数指针的类
struct DD
{
    using Fun = void (*)(int, const string &);
    operator Fun()
    {
        return show; //返回普通函数show的地址
    }
};

void test()
{
    // 1 不使用包装器function

    using Fun = void (*)(int, const string &); //函数类型别名
    //普通函数
    void (*pf1)(int, const string &) = show;
    pf1(1, "a1");

    //类的静态成员函数
    void (*pf2)(int, const string &) = AA::show;
    pf2(2, "a2");

    //仿函数
    BB bb;
    bb(3, "a3");
    // BB()(3, "a3");//匿名对象

    //创建lambda对象
    auto lambfunc = [](int a, const string &b)
    {
        cout << a << b << endl;
    };
    lambfunc(4, "a4");

    //类的非静态成员函数
    CC cc;
    void (CC::*pf3)(int, const string &) = &CC::show;
    (cc.*pf3)(5, "a5");

    //可以被转换的函数指针的类
    DD dd;
    dd(6, "a6");

    // 2 使用包装器function
    cout << "使用包装器function" << endl;

    //普通函数
    function<void(int, const string &)> f1 = show;
    show(11, "a11");

    //类的静态成员函数
    function<void(int, const string &)> f2 = AA::show;
    f2(22, "a22");

    //仿函数
    BB bbb;
    function<void(int, const string &)> f3 = bbb;
    // function<void(int, const string &)> f3 = BB();
    f3(33, "a33");

    //创建lambda对象
    function<void(int, const string &)> f4 = [](int a, const string &b)
    {
        cout << a << b << endl;
    };
    f4(44, "a44");

    //类的非静态成员函数   比较特殊
    function<void(CC &, int, const string &)> f5 = &CC::show;
    CC ccc;
    f5(ccc, 55, "a55");

    //可以被转换的函数指针的类
    DD ddd;
    function<void(int, const string &)> f6 = ddd;
    f6(66, "a66");
}
int main()
{
    test();
    return 0;
}
/* 1a1
2a2
3a3
4a4
5a5
6a6
使用包装器function
11a11
22a22
33a33
44a44
55a55
66a66 */
```