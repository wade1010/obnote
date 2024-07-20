43 CPP类模板具体化

类模板具体化和函数模板具体化也有不同

模板类具体化（特化、特例化）有两种：完全具体化和部分具体化。

```
#include "iostream"
using namespace std;
//类模板
template <class T1, class T2>
class AA
{
public:
    T1 m_x;
    T2 m_y;
    AA(const T1 x, const T2 y) : m_x(x), m_y(y)
    {
        cout << "类模板:构造函数" << endl;
    }
    void show() const;
};
template <class T1, class T2>
void AA<T1, T2>::show() const
{
    cout << "类模板:x=" << m_x << ",y=" << m_y << endl;
}

//类模板完全具体化
template <>
class AA<int, string>
{
public:
    int m_x;
    string m_y;
    AA(const int x, const string y) : m_x(x), m_y(y)
    {
        cout << "完全具体化:构造函数" << endl;
    }
    void show() const;
};

void AA<int, string>::show() const
{
    cout << "完全具体化:x=" << m_x << ",y=" << m_y << endl;
}
//类模板部分显示具体化
template <class T>
class AA<T, string>
{
public:
    T m_x;
    string m_y;
    AA(T x, string y) : m_x(x), m_y(y)
    {
        cout << "部分具体化:构造函数" << endl;
    }
    void show() const;
};
template <class T>
void AA<T, string>::show() const
{
    cout << "部分具体化:x=" << m_x << ",y=" << m_y << endl;
};

void test()
{
    //具体化程度搞的类优先于具体化程度低的类,具体化的类有限与没有具体化的类
    AA<int, string> aa(4, "aaa"); //使用完全具体化
    aa.show();
    //上方代码,如果注释掉完全具体化,将调用部分具体化,也注释掉部分具体化模板,将会调用通用的模板
}
int main()
{
    test();
    return 0;
}
```