41 CPP模板类的基本概念

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220391.jpg)

函数模板建议用typename描述通用数据类型，类模板建议用class

注意：

1 在创建对象的时候，必须指明具体的数据类型

2 使用类模板的时候，数据类型必须适应类模板中的代码

3 类模板可以为通用参数指定缺省的数据类型(C++11标准的函数模板也可以)

4 累的成员函数可以在类外实现

5 可以用new创建模板对象。

6 在程序中，模板类的成员函数使用了才会创建，不适用不会创建

```
#include "iostream"
using namespace std;
// template <class T1, class T2>
template <class T1, class T2 = double> //使用缺省
class AA
{
public:
    T1 m_a;
    T2 m_b;
    AA(T1 a, T2 b) : m_a(a), m_b(b) {}
    T1 geta()
    {
        T1 a = 2;
        return m_a + a;
    }
    T2 getb();
};
//类外实现  但是这时不能用缺省 报错为 不能在类外部的类模板成员声明上指定默认模板参数
// template <class T1, class T2 = double>//报错
template <class T1, class T2>
T2 AA<T1, T2>::getb()
{
    T2 b = 1;
    return m_b + b;
}

void test()
{
    // AA<int, double> a(1, 2.2);
    AA<int> a(1, 2.2); //同上,只不过使用了缺省
    int ret = a.geta();
    double b = a.getb();
    cout << ret << endl;
    cout << b << endl;

    //用new创建模板对象
    AA<int, double> *pa = new AA<int, double>(1, 33.33);
    cout << pa->geta() << endl;
    cout << pa->getb() << endl;
}
int main()
{
    test();
    return 0;
}
3
3.2
3
34.33
```

```
#include "iostream"
using namespace std;
// template <class T1, class T2>
template <class T1, class T2 = double> //使用缺省
class AA
{
public:
    T1 m_a;
    T2 m_b;
    AA()
    {
        // 模板类的成员函数使用了才会创建，不适用不会创建
        m_a.aaaaaaaaaaa();
    }
    AA(T1 a, T2 b) : m_a(a), m_b(b)
    {
        // 模板类的成员函数使用了才会创建，不适用不会创建
        m_b.aaaaaaaaaaa();
    }
    T1 geta()
    {
        T1 a = 2;
        return m_a + a;
    }
    T2 getb();
};
void test()
{
    AA<int, double> *a; //只声明指针,不创建对象,编译 运行都没问题
    // AA<int, double> *a = new AA<int, double>(3, 3.3); //创建对象,这里就会报错了
}
int main()
{
    test();
    return 0;
}
```