66 CPP智能指针

普通指针的不足：

1 new和new[]的内存需要用delete和delete[]释放。

2 程序员的主观失误，忘了或者漏了释放。

3 程序员也不确定何时释放。（例如过个线程共享一个对象的时候）

为了避免内存泄漏的问题，就想出了一些办法,

如果是类里面的指针，我们会把释放资源的代码卸载析构函数中，对象过期的时候，系统自动调用析构函数，释放资源。

如果堆区的内存时C++内置的数据类型，没析构函数，就只能手动释放了。

另外类虽然有析构函数，可以释放资源，但是，如果类的对象时new出来的，必须delete才会调用析构函数。如果忘了delete，怎么办呢？所以就想出了智能指针。

智能指针的目的就是解决资源释放的问题。

智能指针的设计思路：

智能指针是类模板，在栈上创建智能指针对象。

然后把普通指针交给智能指针对象。

智能指针对象过期的时候，系统会自动调用析构函数释放普通指针的内存。

智能指针的类型：

auto_ptr是C++98的标准，C++17已弃用。

unique_ptr、shared_ptr和weak_ptr是C++标准的。

一、智能指针 unique_ptr

unique_ptr独享它指向的对象，也就是说，同时只有一个unique_ptr指向同一个对象，当这个unique_ptr被销毁时，指向的对象也随即被销毁。

一、基本用法

1）初始化

方法一：

unique_ptr<AA> p0(new AA("西施"));     // 分配内存并初始化。

方法二：

unique_ptr<AA> p0 = make_unique<AA>("西施");   // C++14标准。

unique_ptr<int> pp1=make_unique<int>();         // 数据类型为int。

unique_ptr<AA> pp2 = make_unique<AA>();       // 数据类型为AA，默认构造函数。

unique_ptr<AA> pp3 = make_unique<AA>("西施");  // 数据类型为AA，一个参数的构造函数。

unique_ptr<AA> pp4 = make_unique<AA>("西施",8); // 数据类型为AA，两个参数的构造函数。

方法三（不推荐）：

AA* p = new AA("西施");

unique_ptr<AA> p0(p);                  // 用已存在的地址初始化。

2）使用方法

智能指针重载了**和->操作符，可以像使用指针一样使用unique_ptr。**

**不支持普通的拷贝和赋值。AA** p = new AA("西施");

unique_ptr<AA> pu2 = p;              // 错误，不能把普通指针直接赋给智能指针。

unique_ptr<AA> pu3 = new AA("西施"); // 错误，不能把普通指针直接赋给智能指针。

unique_ptr<AA> pu2 = pu1;           // 错误，不能用其它unique_ptr拷贝构造。

unique_ptr<AA> pu3;

pu3 = pu1;                            // 错误，不能用=对unique_ptr进行赋值。

不要用同一个裸指针初始化多个unique_ptr对象。

get()方法返回裸指针。

不要用unique_ptr管理不是new分配的内存。

3）用于函数的参数

传引用（不能传值，因为unique_ptr没有拷贝构造函数）。

裸指针。

4）不支持指针的运算（+、-、++、--）

包含头文件：#include <memory>

template <typename T, typename D = default_delete<T>>

class unique_ptr

{

public:

explicit unique_ptr(pointer p) noexcept;	// 不可用于转换函数。

~unique_ptr() noexcept;

T& operator*() const;            // 重载**操作符。**

**T** operator->() const noexcept;  // 重载->操作符。

unique_ptr(const unique_ptr &) = delete;   // 禁用拷贝构造函数。

unique_ptr& operator=(const unique_ptr &) = delete;  // 禁用赋值函数。

unique_ptr(unique_ptr &&) noexcept;	  // 右值引用。

unique_ptr& operator=(unique_ptr &&) noexcept;  // 右值引用。

// ...

private:

pointer ptr;  // 内置的指针。

};

第一个模板参数T：指针指向的数据类型。

第二个模板参数D：指定删除器，缺省用delete释放资源。

从上面代码可以看出

explicit unique_ptr(pointer p) noexcept;	// 不可用于转换函数。

所以下面代码是错误的，

AA* p = new AA("嗷嗷");

unique_ptr<AA> pu2 = p;              // 错误，不能把普通指针直接赋给智能指针。

unique_ptr<AA> pu3 = new AA("嗷嗷"); // 错误，不能把普通指针直接赋给智能指针。

从上面代码可以看出

unique_ptr(const unique_ptr &) = delete;   // 禁用拷贝构造函数。

如下代码是错误的

unique_ptr<AA> pu1(new AA("aa"));

unique_ptr<AA> pu2 = pu1;// 错误，不能用其它unique_ptr拷贝构造。

从上面代码可以看出

unique_ptr& operator=(const unique_ptr &) = delete;  // 禁用赋值函数。

如下代码是错误的

unique_ptr<AA> pu1(new AA("aa"));

unique_ptr<AA> pu3;

pu3=pu1;// 错误，不能用=对unique_ptr进行赋值。

那上面代码为什么要删除拷贝构造函数和赋值函数呢？

都是为了防止程序员犯错。unique_ptr的射击目标是独享，一个unique_ptr对象只对一个资源负责，如果unique_ptr对象允许复制，那么就会出现多个unique_ptr对象指向同一块内存的情况，当其中一个unique_ptr对象过期的时候，释放内存，其它的unique_ptr对象过期的时候，又会释放内存，造成一块内存释放多次，就成了操作野指针。

二、更多技巧

1）将一个unique_ptr赋给另一个时，如果源unique_ptr是一个临时右值，编译器允许这样做；

 如果源unique_ptr将存在一段时间，编译器禁止这样做。一般用于函数的返回值。

unique_ptr<AA> p0;

p0 = unique_ptr<AA>(new AA ("西瓜"));

```
#include <iostream>
#include <memory>
using namespace std;
class AA
{
public:
    string m_name;
    AA()
    {
        cout << m_name << "调用构造函数AA()" << endl;
    }
    AA(const string &name) : m_name(name)
    {
        cout << "调用构造函数AA(const string &name)," << m_name << endl;
    }
    ~AA() { cout << "调用析构函数," << m_name << endl; }
};
unique_ptr<AA> func()
{
    unique_ptr<AA> p(new AA("a3"));
    return p;
}
// unique_ptr<AA> sp(new AA("a4"));
// unique_ptr<AA> func2()
// {
//     return sp;
// }
void test()
{
    unique_ptr<AA> pu1(new AA("a1"));
    unique_ptr<AA> pu2;
    // pu2 = pu1; //错误
    pu2 = unique_ptr<AA>(new AA("a2")); //用匿名对象赋值
    cout << "调用func()之前." << endl;
    // unique_ptr类的赋值函数已经被删除了,但是这一行还能赋值,神奇之一
    // pu2不可能管理多个资源,所以,它先释放了a2,再接手a3
    //但是如果把func()函数中的unique_ptr声明放在外面,如func2(),报错,编译不通过
    pu2 = func();
    cout << "调用func()之后" << endl;
    cout << "结束" << endl;
}
int main()
{
    test();
    return 0;
}
/* 调用构造函数AA(const string &name),a1
调用构造函数AA(const string &name),a2
调用func()之前.
调用构造函数AA(const string &name),a3
调用析构函数,a2
调用func()之后
结束
调用析构函数,a3
调用析构函数,a1 */
```

总的来说，编译器为unique_ptr做了特别的处理。

2）用nullptr给unique_ptr赋值将释放对象，空的unique_ptr==nullptr。

3）release()释放对原始指针的控制权，将unique_ptr置为空，返回裸指针。（可用于把unique_ptr传递给子函数，子函数将负责释放对象）

4）std::move()可以转移对原始指针的控制权。（可用于把unique_ptr传递给子函数，子函数形参也是unique_ptr）

```
#include <iostream>
#include <memory>
using namespace std;
class AA
{
public:
    string m_name;
    AA()
    {
        cout << m_name << "调用构造函数AA()" << endl;
    }
    AA(const string &name) : m_name(name)
    {
        cout << "调用构造函数AA(const string &name)," << m_name << endl;
    }
    ~AA() { cout << "调用析构函数," << m_name << endl; }
};
//函数func1()需用一个指针,但不对这个指针负责
void func1(const AA *a)
{
    cout << "call func1:" << a->m_name << endl;
}
//函数func2()需要一个指针,并且对这个指针负责
void func2(AA *a)
{
    cout << "call func2" << a->m_name << endl;
    delete a;
}

//函数func3()需要一个unique_ptr,不会对这个unique_ptr负责
void func3(const unique_ptr<AA> &p)
{
    cout << "call func3:" << p->m_name << endl;
}
//函数func4()需要一个unique_ptr,并且会对这个unique_ptr负责
void func4(unique_ptr<AA> p)
{
    cout << "call func4:" << p->m_name << endl;
}
void test()
{
    unique_ptr<AA> p(new AA("a1"));
    cout << "开始调用函数" << endl;
    // func1(p.get()); //函数func1()需要一个指针,但不对这个指针负责
    // func2(p.release()); //函数func2()需要一个指针,并且对这个指针负责
    // func3(p); //函数func3()需要一个unique_ptr,不会对这个unique_ptr负责
    func4(move(p)); //函数func4()需要一个unique_ptr,并且会对这个unique_ptr负责   传值,而不是传引用
    cout << "结束调用函数" << endl;
}
int main()
{
    test();
    return 0;
}
/* 调用构造函数AA(const string &name),a1
开始调用函数
call func4:a1
调用析构函数,a1
结束调用函数 */
```

5）reset()释放对象。

void reset(T * _ptr= (T *) nullptr);

pp.reset();        // 释放pp对象指向的资源对象。

pp.reset(nullptr);  // 释放pp对象指向的资源对象

pp.reset(new AA("bbb"));  // 释放pp指向的资源对象，同时指向新的对象。

6）swap()交换两个unique_ptr的控制权。

void swap(unique_ptr<T> &_Right);

7）unique_ptr也可象普通指针那样，当指向一个类继承体系的基类对象时，也具有多态性质，如同使用裸指针管理基类对象和派生类对象那样。

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219593.jpg)

而且就不需要自己手动delete来释放内存了。

8）unique_ptr不是绝对安全，如果程序中调用exit()退出，全局的unique_ptr可以自动释放，但局部的unique_ptr无法释放。

9）unique_ptr提供了支持数组的具体化版本。

数组版本的unique_ptr，重载了操作符[]，操作符[]返回的是引用，可以作为左值使用。

```
#include <iostream>
#include <memory>
using namespace std;
class AA
{
public:
    string m_name;
    AA()
    {
        cout << m_name << "调用构造函数AA()" << endl;
    }
    AA(const string &name) : m_name(name)
    {
        cout << "调用构造函数AA(const string &name)," << m_name << endl;
    }
    ~AA() { cout << "调用析构函数," << m_name << endl; }
};

void test()
{
    /* // AA *parr1 = new AA[2]{string("a1"), string("a2")};//指定数组元素的初始值,可以用这行代码
    AA *parr1 = new AA[2]; //普通指针数组
    parr1[0].m_name = "a1";
    cout << "parr1[0].m_name=" << parr1[0].m_name << endl;
    parr1[1].m_name = "a2";
    cout << "parr1[1].m_name=" << parr1[1].m_name << endl;
    delete[] parr1; */

    // unique_ptr<AA[]> pu(new AA[2]{string("a1"), string("a2")});
    unique_ptr<AA[]> pu(new AA[2]);
    pu[0].m_name = "a1";
    cout << "pu[0].m_name=" << pu[0].m_name << endl;
    pu[1].m_name = "a2";
    cout << "pu[0].m_name=" << pu[1].m_name << endl;
}
int main()
{
    test();
    return 0;
}
/* 调用构造函数AA()
调用构造函数AA()
pu[0].m_name=a1
pu[0].m_name=a2
调用析构函数,a2
调用析构函数,a1*/
```