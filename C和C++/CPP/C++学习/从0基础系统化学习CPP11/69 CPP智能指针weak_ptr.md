一、shared_ptr存在的问题

shared_ptr内部维护了一个共享的引用计数器，多个shared_ptr可以指向同一个资源。

如果出现了循环引用的情况，引用计数永远无法归0，资源不会被释放。

```
#include <iostream>
#include <memory>
using namespace std;
class BB;
class AA
{
public:
    string m_name;
    AA() { cout << "调用构造函数AA()" << endl; }
    AA(const string &name) : m_name(name)
    {
        cout << "调用构造函数AA(const string &name)" << endl;
    }
    ~AA()
    {
        cout << "调用AA析构函数" << endl;
    }
    shared_ptr<BB> sp;
};

class BB
{
public:
    string m_name;
    BB() { cout << "调用构造函数BB()" << endl; }
    BB(const string &name) : m_name(name)
    {
        cout << "调用构造函数BB(const string &name)" << endl;
    }
    ~BB()
    {
        cout << "调用BB析构函数" << endl;
    }
    shared_ptr<AA> sp;
};
void test()
{
    shared_ptr<AA> spa = make_shared<AA>("a1");
    shared_ptr<BB> spb = make_shared<BB>("b1");
    spa->sp = spb;
    spb->sp = spa;
    //上面代码是循环引用,不会调用构造函数,导致内存泄漏
    //为了解决上述问题,C++引入了weak_ptr
}
int main()
{
    test();
    return 0;
}
```

为了解决上述问题,C++引入了weak_ptr

二、weak_ptr是什么

weak_ptr 是为了配合shared_ptr而引入的，它指向一个由shared_ptr管理的资源但不影响资源的生命周期。也就是说，将一个weak_ptr绑定到一个shared_ptr不会改变shared_ptr的引用计数。

不论是否有weak_ptr指向，如果最后一个指向资源的shared_ptr被销毁，资源就会被释放。

weak_ptr更像是shared_ptr的助手而不是智能指针。

```
#include <iostream>
#include <memory>
using namespace std;
//使用weak_ptr解决循环依赖
class BB;
class AA
{
public:
    string m_name;
    AA() { cout << "调用构造函数AA()" << endl; }
    AA(const string &name) : m_name(name)
    {
        cout << "调用构造函数AA(const string &name)" << endl;
    }
    ~AA()
    {
        cout << "调用AA析构函数" << endl;
    }
    weak_ptr<BB> sp;
};

class BB
{
public:
    string m_name;
    BB() { cout << "调用构造函数BB()" << endl; }
    BB(const string &name) : m_name(name)
    {
        cout << "调用构造函数BB(const string &name)" << endl;
    }
    ~BB()
    {
        cout << "调用BB析构函数" << endl;
    }
    weak_ptr<AA> sp;
};
void test()
{
    shared_ptr<AA> spa = make_shared<AA>("a1");
    shared_ptr<BB> spb = make_shared<BB>("b1");
    cout << "spa.use_count()" << spa.use_count() << endl;
    cout << "spb.use_count()" << spb.use_count() << endl;
    spa->sp = spb;
    spb->sp = spa;
    //引用计数不会发生变化
    cout << "spa.use_count()" << spa.use_count() << endl;
    cout << "spb.use_count()" << spb.use_count() << endl;
}
int main()
{
    test();
    return 0;
}
调用构造函数AA(const string &name)
调用构造函数BB(const string &name)
spa.use_count()1
spb.use_count()1
spa.use_count()1
spb.use_count()1
调用BB析构函数
调用AA析构函数
```

三、如何使用weak_ptr

weak_ptr没有重载 ->和 *操作符，不能直接访问资源。

有以下成员函数：

1）operator=();  // 把shared_ptr或weak_ptr赋值给weak_ptr。

2）expired();     // 判断它指资源是否已过期（已经被销毁）。

3）lock();        // 返回shared_ptr，如果资源已过期，返回空的shared_ptr。

4）reset();       // 将当前weak_ptr指针置为空。

5）swap();       // 交换。

weak_ptr不控制对象的生命周期，但是，它知道对象是否还活着。

用lock()函数把它可以提升为shared_ptr，如果对象还活着，返回有效的shared_ptr，如果对象已经死了，提升会失败，返回一个空的shared_ptr。

提升的行为（lock()）是线程安全的。

```
#include <iostream>
#include <memory>
using namespace std;
//使用weak_ptr解决循环依赖
class BB;
class AA
{
public:
    string m_name;
    AA() { cout << "调用构造函数AA()" << endl; }
    AA(const string &name) : m_name(name)
    {
        cout << "调用构造函数AA(const string &name)" << endl;
    }
    ~AA()
    {
        cout << "调用AA析构函数" << endl;
    }
    weak_ptr<BB> sp;
};

class BB
{
public:
    string m_name;
    BB() { cout << "调用构造函数BB()" << endl; }
    BB(const string &name) : m_name(name)
    {
        cout << "调用构造函数BB(const string &name)" << endl;
    }
    ~BB()
    {
        cout << "调用BB析构函数" << endl;
    }
    weak_ptr<AA> sp;
};
void test()
{
    //问题开始  下面这段代码线程不安全
    /* {

        shared_ptr<AA> spa = make_shared<AA>("a1");
        {
            shared_ptr<BB> spb = make_shared<BB>("b1");
            spa->sp = spb;
            spb->sp = spa;

            //下面的if else 如果执行expired==true再执行lock 这两步不是原子操作,
            //资源可能在这两个步骤之间已经被其它线程释放
            if (spa->sp.expired() == true)
            {
                cout << "语句块内部:spa->sp已经过期" << endl;
            }
            else
            {
                cout << "语句块内部:spa->sp.lock()->m_name=" << spa->sp.lock()->m_name << endl;
            }
        }
        if (spa->sp.expired() == true)
        {
            cout << "语句块外部:spa->sp已过期" << endl;
        }
        else
        {
            cout << "语句块外部:spa->sp.lock()->m_name=" << spa->sp.lock()->m_name << endl;
        }
    } */
    //问题结束

    //正确开始
    {
        shared_ptr<AA> spa = make_shared<AA>("a1");
        {
            shared_ptr<BB> spb = make_shared<BB>("b1");
            spa->sp = spb;
            spb->sp = spa;

            //把weak_ptr提升为shared_ptr
            shared_ptr<BB> tmp = spa->sp.lock();
            if (tmp == nullptr)
            {
                cout << "语句块内部:spa->sp已经过期" << endl;
            }
            else
            {
                cout << "语句块内部:spa->sp.lock()->m_name=" << tmp->m_name << endl;
            }
        }

        shared_ptr<BB> tmp = spa->sp.lock();
        if (tmp == nullptr)
        {
            cout << "语句块外部:spa->sp已过期" << endl;
        }
        else
        {
            cout << "语句块外部:spa->sp.lock()->m_name=" << tmp->m_name << endl;
        }
    }
    //正确结束
}
int main()
{
    test();
    return 0;
}
/*
调用构造函数AA(const string &name)
调用构造函数BB(const string &name)
语句块内部:spa->sp.lock()->m_name=b1
调用BB析构函数
语句块外部:spa->sp已过期
调用AA析构函数  */
```