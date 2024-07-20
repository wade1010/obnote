```
#include "iostream"
using namespace std;
//模板类的成员模板  类内实现
template <class T1, class T2>
class AA
{
public:
    T1 m_x;
    T2 m_y;
    AA(const T1 &x, const T2 &y) : m_x(x), m_y(y) {}
    void show() { cout << "m_x=" << m_x << ",m_y=" << m_y << endl; }
    //类的内部声明
    template <class T>
    class BB
    {
    public:
        T m_a;
        T1 m_b;
        BB() {}
        void show() { cout << "m_a=" << m_a << ",m_b=" << m_b << endl; }
    };

    BB<string> m_bb;
    template <typename T>
    void show(T tt)
    {
        cout << "tt=" << tt << endl;
        cout << "m_x=" << m_x << ",m_y=" << m_y << endl;
        m_bb.show();
    }
};
void test()
{
    AA<int, string> a(88, "hello");
    a.show();

    a.m_bb.m_a = "world";
    a.m_bb.show();
    a.show();
}
int main()
{
    test();
    return 0;
}
```

```
#include "iostream"
using namespace std;
//模板类的成员模板  类外实现   确实麻烦啊
template <class T1, class T2>
class AA
{
public:
    T1 m_x;
    T2 m_y;
    AA(const T1 &x, const T2 &y) : m_x(x), m_y(y) {}
    void show() { cout << "m_x=" << m_x << ",m_y=" << m_y << endl; }
    //类的内部声明
    template <class T>
    class BB
    {
    public:
        T m_a;
        T1 m_b;
        BB() {}
        void show();
    };

    BB<string> m_bb;
    template <typename T>
    void show(T tt);
};
template <class T1, class T2>
template <class T>
void AA<T1, T2>::BB<T>::show()
{
    cout << "m_a=" << m_a << ",m_b=" << m_b << endl;
}

template <class T1, class T2>
template <typename T>
void AA<T1, T2>::show(T tt)
{
    cout << "tt=" << tt << endl;
    cout << "m_x=" << m_x << ",m_y=" << m_y << endl;
    m_bb.show();
}

void test()
{
    AA<int, string> a(88, "hello");
    a.show();

    a.m_bb.m_a = "world";
    a.m_bb.show();
    a.show();
}
int main()
{
    test();
    return 0;
}
m_x=88,m_y=hello
m_a=world,m_b=268501009
m_x=88,m_y=hello
```