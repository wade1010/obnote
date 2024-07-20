模仿for_each自己写一个

```
#include <iostream>
#include <vector>
#include <forward_list>
using namespace std;
template <typename T>
void _foreach(const T first, const T last)
{
    for (auto it = first; it != last; it++)
        cout << *it << " ";
    cout << endl;
}

void zsshow(const string &no)
{
    cout << no << " ";
}

//用户自定义函数指针
template <typename T>
void _foreach2(const T first, const T last, void (*pfunc)(const string &))
{
    for (auto it = first; it != last; it++)
        pfunc(*it);
    cout << endl;
}

//调用类的成员函数
class CTmp
{
public:
    void show(const string &no)
    {
        cout << "call show:" << no << " ";
    }
    void operator()(const string &no)
    {
        cout << "call operator:" << no << " ";
    }
};
template <typename T>
void _foreach3(const T first, const T last, CTmp ct)
{
    for (auto it = first; it != last; it++)
        ct.show(*it);
    cout << endl;
}

template <typename T>
void _foreach4(const T first, const T last, CTmp ct)
{
    for (auto it = first; it != last; it++)
        ct(*it);
    cout << endl;
}

//模板

template <typename T1, typename T2>
void _foreach5(const T1 first, const T1 last, T2 func)
{
    for (auto it = first; it != last; it++)
        func(*it);
    cout << endl;
}
void test()
{
    // vector<int> bh{2, 3, 4, 5, 6};
    vector<string> bh{"2", "3", "4", "5", "6"};
    _foreach(bh.begin(), bh.end());
    //用户自定义函数指针
    _foreach2(bh.begin(), bh.end(), zsshow);
    //调用类的成员函数
    _foreach3(bh.begin(), bh.end(), CTmp());
    //仿函数
    _foreach4(bh.begin(), bh.end(), CTmp());
    //不管形参是什么数据类型,只要函数里面的代码相同,就可以整成模板

    cout << endl;
    //第三个参数既可以是函数,也可以是类,重载了小括号的类,也叫仿函数
    _foreach5(bh.begin(), bh.end(), CTmp());
    _foreach5(bh.begin(), bh.end(), zsshow);
}
int main()
{
    test();
    return 0;
}
/* 2 3 4 5 6 
2 3 4 5 6
call show:2 call show:3 call show:4 call show:5 call show:6
call operator:2 call operator:3 call operator:4 call operator:5 call operator:6

call operator:2 call operator:3 call operator:4 call operator:5 call operator:6 
2 3 4 5 6 */
```

```
#include <iostream>
#include <vector>
using namespace std;

template <typename T>
void show(const T &str)
{
    cout << str << endl;
}

template <class T>
class CTmp
{
public:
    void operator()(const T &no)
    {
        cout << "call operator:" << no << " ";
    }
};

template <typename T1, typename T2>
void _foreach(const T1 first, const T1 last, T2 func)
{
    for (auto it = first; it != last; it++)
        func(*it);
    cout << endl;
}
void test()
{
    // vector<int> bh{2, 3, 4, 5, 6};
    vector<string> bh{"A2", "A3", "A4", "A5", "A6"};
    _foreach(bh.begin(), bh.end(), CTmp<string>());
    _foreach(bh.begin(), bh.end(), show<string>);
}
int main()
{
    test();
    return 0;
}
/*
call operator:A2 call operator:A3 call operator:A4 call operator:A5 call operator:A6
A2
A3
A4
A5
A6*/
```

使用C++自带的for_each

```
#include <iostream>
#include <algorithm>
#include <vector>
using namespace std;

template <typename T>
void show(const T &str)
{
    cout << str << endl;
}

template <class T>
class CTmp
{
public:
    void operator()(const T &no)
    {
        cout << "call operator:" << no << " ";
    }
};

void test()
{
    // vector<int> bh{2, 3, 4, 5, 6};
    vector<string> bh{"A2", "A3", "A4", "A5", "A6"};
    for_each(bh.begin(), bh.end(), CTmp<string>());
    for_each(bh.begin(), bh.end(), show<string>);
}
int main()
{
    test();
    return 0;
}
/*
call operator:A2 call operator:A3 call operator:A4 call operator:A5 call operator:A6
A2
A3
A4
A5
A6*/
```