74 CPP委托构造和继承构造

C++11标准新增了委托构造和继承构造两种方法，用于简化代码。

### 一、委托构造

在实际的开发中，为了满足不同的需求，一个类可能会重载多个构造函数。多个构造函数之间可能会有重复的代码。例如变量初始化，如果在每个构造函数中都写一遍，这样代码会显得臃肿。

委托构造就是在一个构造函数的初始化列表中调用另一个构造函数。

注意：

-不要生成环状的构造过程。

-一旦使用委托构造，就不能在初始化列表中初始化其它的成员变量。

```
#include <iostream>
using namespace std;
//委托构造
class AA
{
    int m_a;
    int m_b;
    double m_c;

public:
    AA(double c)
    {
        m_c = c + 3;
        cout << "AA(double c)" << endl;
    }
    AA(int a, int b)
    {
        m_a = a + 1;
        m_b = b + 2;
        cout << "AA(int a,int b)" << endl;
    }
    // 构造函数委托AA(int a, int b)初始化m_a和m_b
    AA(int a, int b, const string &str) : AA(a, b)
    {
        cout << m_a << m_b << str << endl;
    }
    // 构造函数委托AA(double c)初始化m_c
    AA(double c, const string &str) : AA(c)
    {
        cout << "m_c=" << m_c << ",str=" << str << endl;
    }
};
void test()
{
    AA a1(1, 2, "222");
    cout << endl;
    AA a2(2.2, "hello");
}
int main()
{
    test();
    return 0;
}
AA(int a,int b)
24222

AA(double c)
m_c=5.2,str=hello
```

### 二、继承构造

在C++11之前，派生类如果要使用基类的构造函数，可以在派生类构造函数的初始化列表中指定。在《126、如何构造基类》中有详细介绍。

C++11推出了继承构造（Inheriting Constructor），在派生类中使用using来声明继承基类的构

```
#include <iostream>
using namespace std;

class AA // 基类。
{
public:
    int m_a;
    int m_b;
    // 有一个参数的构造函数，初始化m_a
    AA(int a) : m_a(a) { cout << " AA(int a)" << endl; }
    // 有两个参数的构造函数，初始化m_a和m_b
    AA(int a, int b) : m_a(a), m_b(b) { cout << " AA(int a, int b)" << endl; }
};

class BB : public AA // 派生类。
{
public:
    double m_c;
    using AA::AA; // 使用基类的构造函数。
    // 有三个参数的构造函数，调用A(a,b)初始化m_a和m_b，同时初始化m_c
    BB(int a, int b, double c) : AA(a, b), m_c(c)
    {
        cout << " BB(int a, int b, double c)" << endl;
    }
    void show() { cout << "m_a=" << m_a << ",m_b=" << m_b << ",m_c=" << m_c << endl; }
};

int main()
{
    // 将使用基类有一个参数的构造函数，初始化m_a
    BB b1(10);
    b1.show();

    // 将使用基类有两个参数的构造函数，初始化m_a和m_b
    BB b2(10, 20);
    b2.show();

    // 将使用派生类自己有三个参数的构造函数，调用A(a,b)初始化m_a和m_b，同时初始化m_c
    BB b3(10, 20, 10.58);
    b3.show();
}
 AA(int a)
m_a=10,m_b=0,m_c=7.90505e-323
 AA(int a, int b)
m_a=10,m_b=20,m_c=2.96439e-323
 AA(int a, int b)
 BB(int a, int b, double c)
m_a=10,m_b=20,m_c=10.58
```