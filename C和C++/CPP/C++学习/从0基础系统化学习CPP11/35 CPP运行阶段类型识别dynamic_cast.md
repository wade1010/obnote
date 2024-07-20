35 CPP运行阶段类型识别dynamic_cast

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220964.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220478.jpg)

第三点 可以用于指针，但是，在C++中，引用的目标不能是空指针，如果转换请求不正确，会抛出bad_cast异常，异常这个东西很讨厌，在实际开发中，根本没人愿意用它，C++11标准早就放弃异常了。

```
#include "iostream"
using namespace std;
class Hero
{
public:
    int viability;
    int attack;
    virtual void skill1() { cout << "释放了1技能" << endl; }
    virtual void skill2() { cout << "释放了2技能" << endl; }
    virtual void skill3() { cout << "释放了3技能" << endl; }
};
class XS : public Hero
{
public:
    void skill1() { cout << "西施释放了1技能" << endl; }
    void skill2() { cout << "西施释放了2技能" << endl; }
    void skill3() { cout << "西施释放了3技能" << endl; }
    void show() { cout << "我比别人多一个技能" << endl; }
};
class HX : public Hero
{
public:
    void skill1() { cout << "韩信释放了1技能" << endl; }
    void skill2() { cout << "韩信释放了2技能" << endl; }
    void skill3() { cout << "韩信释放了3技能" << endl; }
};
void test()
{
    int id = 0;
    cout << "请输入英雄(1-西施,2-韩信)" << endl;
    cin >> id;
    Hero *p = nullptr;
    if (id == 1)
    {
        p = new XS;
    }
    else if (id == 2)
    {
        p = new HX;
    }
    if (p != nullptr)
    {
        p->skill1();
        p->skill2();
        p->skill3();

        //如果基类指针指向的对象时西施,那么久调用西施的show()函数
        // 方法1 最笨的办法
        if (id == 1)
        {
            XS *xs = (XS *)p; // C风格强转换的方法,我们自己必须保证目标类型正确
            xs->show();
        }
        // 方法2 dynamic_cast
        XS *xs2 = dynamic_cast<XS *>(p);
        if (xs2 != nullptr)
        {
            xs2->show();
        }
        // 方法3 typeid
        if (typeid(XS) == typeid(*p))
        {
            XS *xs3 = (XS *)p;
            xs3->show();
        }

        delete p;
    }

    //以下代码演示把基类引用转换为派生类引用时发生异常的情况
    HX hx;
    Hero &rh = hx;
    try
    {
        XS &rxs = dynamic_cast<XS &>(rh);
    }
    catch (bad_cast)
    {
        cout << "出现了bad_cast异常" << endl;
    }
}
int main()
{
    test();
    return 0;
}
```