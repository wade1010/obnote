45 CPP模板类与函数

模板类可以用于函数的参数和返回值，有3中心事：

1 普通函数，参数和返回值是模板类的实例化版本。

2 函数模板，参数和返回值是某种的模板类。

函数模板，参数和返回值是任意类型（支持普通类和模板类和其它类型）

```
#include "iostream"
using namespace std;
template <class T1, class T2>
class AA
{ //模板类AA
public:
    T1 m_x;
    T2 m_y;
    AA(const T1 x, const T2 y) : m_x(x), m_y(y)
    {
    }
    void show() const
    {
        cout << "show（) x=" << m_x << ",y=" << m_y << endl;
    }
};
//采用普通函数,参数和返回值是模板类的实例化版本
AA<int, string> func(AA<int, string> &aa)
{
    cout << "调用了func(AA<int, string> &aa)函数" << endl;
    aa.show();
    return aa;
}
// FLAG1 函数模板 参数和返回值是模板类AA  但是这种方法不能体现模板的精髓,下面的方法可以体现精髓
template <typename T1, typename T2>
AA<T1, T2> func(AA<T1, T2> &aa)
{
    cout << "调用了func(AA<T1, T2> &aa)函数" << endl;
    aa.show();
    return aa;
}
// FLAG1 函数模板,参数和返回值是任意类型
template <typename T>
T func(T &aa)
{
    cout << "调用T func(T &aa)函数" << endl;
    aa.show();
    return aa;
}
class BB
{
public:
    void show() { cout << "调用BB的show()方法" << endl; }
};

void test()
{
    //调用普通函数(普通函数,和模板函数都能匹配上,优先普通函数)
    AA<int, string> aa(1, "hello");
    auto bb = func(aa);
    bb.show();

    cout << endl;
    //调用模板函数
    AA<char, string> aa2('a', "hello");
    auto bb2 = func(aa2);
    bb2.show();

    // BB类 FLAG1就不能调用,FLAG2能调用.所以说FLAG1有局限性
    BB b;
    func(b);
}
int main()
{
    test();
    return 0;
}
调用了func(AA<int, string> &aa)函数
show（) x=1,y=hello
show（) x=1,y=hello

调用了func(AA<T1, T2> &aa)函数
show（) x=a,y=hello
show（) x=a,y=hello
调用T func(T &aa)函数
调用BB的show()方法
```

```


#include "iostream"
using namespace std;
void show()
{
    cout << "调用了show()函数" << endl;
}
class BB
{
public:
    void operator()()
    {
        cout << "调用了BB类的仿函数" << endl;
    }
};

//函数模板,参数和返回值是任意类型
template <typename T>
void func(T tt)
{
    tt();
}
void test()
{
    BB bb;
    func(bb);

    func(show); //传入show,函数名,也就是函数指针
}
int main()
{
    test();
    return 0;
}
调用了BB类的仿函数
调用了show()函数
```