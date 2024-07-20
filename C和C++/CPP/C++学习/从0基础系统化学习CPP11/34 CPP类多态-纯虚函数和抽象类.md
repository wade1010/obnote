34 CPP类多态-纯虚函数和抽象类

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220182.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220728.jpg)

 

```
#include "iostream"
using namespace std;
class AA
{
public:
    AA() { cout << "调用了基类的构造函数AA()" << endl; }
    // virtual void func() = 0 { cout << "调用了基类的func()" << endl; }  // VS中可以
    // virtual ~AA() = 0 { cout << "调用了基类的析构函数~AA()" << endl; } // VS中可以
    virtual void func() = 0;
    virtual ~AA() = 0; //纯虚析构必须要有实现,VS可以采用上面的实现,g++不行,得采用类外实现
};
AA::~AA()
{
    cout << "调用了基类的析构函数~AA()" << endl;
}

class BB : public AA
{
public:
    BB() { cout << "调用了派生类的构造函数BB()" << endl; }
    void func() { cout << "调用了派生类的func" << endl; }
    ~BB() { cout << "调用了派生类的析构函数~BB" << endl; }
};
void test()
{
    BB b;
    AA &a = b;
    b.func();
}
int main()
{
    test();
    return 0;
}
/* 调用了基类的构造函数AA()
调用了派生类的构造函数BB()
调用了派生类的func
调用了派生类的析构函数~BB
调用了基类的析构函数~AA() */
```