std::bind()模板函数是一个通用的函数适配器（绑定器），它用一个可调用对象及其参数，生成一个新的可调用对象，以适应模板。

包含头文件：#include <functional>

函数原型：

template< class Fx, class... Args >

function<> bind (Fx&& fx, Args&...args);

Fx：需要绑定的可调用对象（可以是前两节课介绍的那六种，也可以是function对象）。

args：绑定参数列表，可以是左值、右值和参数占位符std::placeholders::_n，如果参数不是占位符，缺省为值传递，std:: ref(参数)则为引用传递。

std::bind()返回std::function的对象。

std::bind()的本质是仿函数。

```
#include <iostream>
#include <functional>
using namespace std;
// bind

void show(int a, const string &b)
{
    cout << a << " " << b << endl;
}

void test()
{
    function<void(int, const string &)> f1 = show;
    function<void(int, const string &)> f2 = bind(show, placeholders::_1, placeholders::_2);
    f1(1, "A1");
    f2(2, "A2");

    function<void(const string &, int)> f3 = bind(show, placeholders::_2, placeholders::_1);
    f3("A3", 3);
    //上面代码,如果不用bind,要实现这个需求,可以把普通函数show()重载一个版本,把参数的位置调换
    //但是没有bind好用
    //实际开发中,调换位置的情况不多,最常见的是少一个参数

    function<void(const string &)> f4 = bind(show, 1, placeholders::_1);
    f4("A4");

    // 用bind()提前绑定有一个细节要注意，被绑定的参数缺省值是值传递，
    int aa = 11;
    function<void(const string &)> f5 = bind(show, aa, placeholders::_1);
    aa = 200;
    f5("A5"); //输出的是 11 A5 而不是 200 A5

    //传引用
    int aaa = 11;
    function<void(const string &)> f6 = bind(show, std::ref(aaa), placeholders::_1);
    aaa = 200;
    f6("A6"); //输出的是 200 A6 而不是 11 A6

    //如果function对象需要的参数比show更多
    function<void(int, const string &, int)> f7 = bind(show, placeholders::_1, placeholders::_2);
    f7(7, "a7", 1);
}
int main()
{
    test();
    return 0;
}
/*
1 A1
2 A2
3 A3
1 A4
11 A5
200 A6
7 a7 */
```

用bind()提前绑定有一个细节要注意，被绑定的参数缺省值是值传递，

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
    function<void(int, const string &)> pf1 = bind(show, placeholders::_1, placeholders::_2);
    pf1(1, "a1");

    //类的静态成员函数
    function<void(int, const string &)> pf2 = bind(AA::show, placeholders::_1, placeholders::_2);
    pf2(2, "a2");

    //仿函数
    BB bb;
    function<void(int, const string &)> pf3 = bind(bb, placeholders::_1, placeholders::_2);
    pf3(3, "a3");

    //创建lambda对象
    auto lambfunc = [](int a, const string &b)
    {
        cout << a << b << endl;
    };
    function<void(int, const string &)> pf4 = bind(lambfunc, placeholders::_1, placeholders::_2);
    pf4(4, "a4");

    //类的非静态成员函数    这里是最大的优化
    CC cc;
    function<void(int, const string &)> pf5 = bind(&CC::show, cc, placeholders::_1, placeholders::_2);
    // function<void(int, const string &)> pf5 = bind(&CC::show, CC(), placeholders::_1, placeholders::_2);
    pf5(5, "a5");

    //可以被转换的函数指针的类
    DD dd;
    // function<void(int, const string &)> pf6 = bind(dd, placeholders::_1, placeholders::_2);
    function<void(int, const string &)> pf6 = bind(DD(), placeholders::_1, placeholders::_2);
    pf6(6, "a6");
}
int main()
{
    test();
    return 0;
}
/*
1a1
2a2
3a3
4a4
5a5
6a6 */
```

上面demo，通过bind适配器，把六种可调用对象的代码统一了

bind()返回的是function模板类的对象，比较长，也可以用auto