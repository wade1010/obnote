模板类太灵活了，所以，它的继承可以玩出很多花样。

1 模板类继承普通类（常见，安排好普通类的构造函数）

```
#include "iostream"
using namespace std;
class AA
{
public:
    int m_a;
    AA(int a) : m_a(a)
    {
        cout << "调用了AA的构造函数" << endl;
    }
    void func1()
    {
        cout << "调用了func1()函数:m_a=" << m_a << endl;
    }
};

template <class T1, class T2>
class BB : public AA
{
public:
    T1 m_x;
    T2 m_y;
    BB(T1 x, T2 y, int a) : AA(a), m_x(x), m_y(y)
    {
        cout << "调用了BB的构造函数" << endl;
    }
    void func2() const
    {
        cout << "调用了func2函数:x" << m_x << ",y=" << m_y << endl;
    }
};
void test()
{
    BB<string, double> b("huluwa", 3.3, 4);
    b.func2();
    b.func1();
}
int main()
{
    test();
    return 0;
}
/* 调用了AA的构造函数
调用了BB的构造函数
调用了func2函数:xhuluwa,y=3.3
调用了func1()函数:m_a=4 */
```

2 普通类继承模板类的实例化版本

```
#include "iostream"
using namespace std;
template <class T1, class T2>
class BB
{
public:
    T1 m_x;
    T2 m_y;
    BB(T1 x, T2 y) : m_x(x), m_y(y)
    {
        cout << "调用了BB的构造函数" << endl;
    }
    void func2() const
    {
        cout << "调用了func2函数:x" << m_x << ",y=" << m_y << endl;
    }
};

class AA : public BB<int, string>
{
public:
    int m_a;
    AA(int a, int x, string y) : BB(x, y), m_a(a)
    {
        cout << "调用了AA的构造函数" << endl;
    }
    void func1()
    {
        cout << "调用了func1()函数:m_a=" << m_a << endl;
    }
};

void test()
{
    AA aa(1, 2, "hello");
    aa.func1();
    aa.func2();
}
int main()
{
    test();
    return 0;
}
/*调用了BB的构造函数
调用了AA的构造函数
调用了func1()函数:m_a=1
调用了func2函数:x2,y=hello*/
```

3 普通类继承模板类（常见）

```
#include "iostream"
using namespace std;
template <class T1, class T2>
class BB
{
public:
    T1 m_x;
    T2 m_y;
    BB(T1 x, T2 y) : m_x(x), m_y(y)
    {
        cout << "调用了BB的构造函数" << endl;
    }
    void func2() const
    {
        cout << "调用了func2函数:x" << m_x << ",y=" << m_y << endl;
    }
};
template <class T1, class T2>
class AA : public BB<T1, T2>
{
public:
    int m_a;
    AA(int a, T1 x, T2 y) : BB<T1, T2>(x, y), m_a(a)
    {
        cout << "调用了AA的构造函数" << endl;
    }
    void func1()
    {
        cout << "调用了func1()函数:m_a=" << m_a << endl;
    }
};

void test()
{
    AA<int, string> aa(1, 2, "hello");
    aa.func1();
    aa.func2();
}
int main()
{
    test();
    return 0;
}
/**/
```

4 模板类继承模板类

```
#include "iostream"
using namespace std;
template <class T1, class T2>
class BB
{
public:
    T1 m_x;
    T2 m_y;
    BB(T1 x, T2 y) : m_x(x), m_y(y)
    {
        cout << "调用了BB的构造函数" << endl;
    }
    void func2() const
    {
        cout << "调用了func2函数:x" << m_x << ",y=" << m_y << endl;
    }
};
template <class T1, class T2>
class AA : public BB<T1, T2>
{
public:
    int m_a;
    AA(int a, T1 x, T2 y) : BB<T1, T2>(x, y), m_a(a)
    {
        cout << "调用了AA的构造函数" << endl;
    }
    void func1()
    {
        cout << "调用了func1()函数:m_a=" << m_a << endl;
    }
};

template <class T, class T1, class T2>
class CC : public BB<T1, T2>
{
public:
    T m_aa;
    CC(const T a, const T1 x, const T2 y) : m_aa(a), BB<T1, T2>(x, y)
    {
        cout << "调用了CC的构造函数" << endl;
    }
    void func3()
    {
        cout << "调用了func3的函数,m_aa=" << m_aa << endl;
    }
};
void test()
{
    AA<int, string> aa(1, 2, "hello");
    aa.func1();
    aa.func2();
}
void test2()
{
    CC<int, int, string> cc(1, 2, "hello");
    cc.func2();
    cc.func3();
}
int main()
{
    test2();
    return 0;
}
/*调用了BB的构造函数
调用了CC的构造函数
调用了func2函数:x2,y=hello
调用了func3的函数,m_aa=1*/
```

5 模板类继承模板参数给出的基类（不能是模板类）

```
#include "iostream"
using namespace std;
class AA
{
public:
    AA() { cout << "调用了AA的构造函数AA()" << endl; }
    AA(int a) { cout << "调用了AA的构造函数AA(int a)" << endl; }
};

class BB
{
public:
    BB() { cout << "调用了BB的构造函数BB()" << endl; }
    BB(int a) { cout << "调用了BB的构造函数BB(int a)" << endl; }
};
class CC
{
public:
    CC() { cout << "调用了CC的构造函数CC()" << endl; }
    CC(int a) { cout << "调用了CC的构造函数CC(int a)" << endl; }
};

template <class T>
class DD
{
public:
    DD() { cout << "调用了DD的构造函数DD()" << endl; }
    DD(int a) { cout << "调用了DD的构造函数DD(int a)" << endl; }
};

template <class T>
class EE : public T //模板类继承模板参数给出的基类.
{
public:
    EE() : T() { cout << "调用了EE的构造函数EE()" << endl; }
    EE(int a) : T(a) { cout << "调用了EE的构造函数EE(int a)" << endl; }
};

void test()
{
    EE<AA> ea1;      // AA作为基类
    EE<BB> eb1;      // BB作为基类
    EE<DD<int>> ed1; // DD<int>作为基类
    // EE<DD> ed1;//DD作为基类,错误
}
int main()
{
    test();
    return 0;
}
/* 调用了AA的构造函数AA()
调用了EE的构造函数EE()
调用了BB的构造函数BB()
调用了EE的构造函数EE()
调用了DD的构造函数DD()
调用了EE的构造函数EE() */
```