CPP 注意点

逗号运算符，把遗憾语句中的多个表达式连接起来，程序从左到右执行表达式。

语法：表达式1,表达式2,....,表达式n；

逗号运算常用于声明多个变量

int a,b;//声明变量a和b;

int a =10 ,b=20;//声明变量a和b并初始化

也可以用于其它语句中，但是，逗号运算符是所有运算符中级别最低的，以下两个表达式效果不是不同的。

int a,b;

b=a=2,a*2;  这种情况 a=2    b=2

b=(a=2,a*2); 这种情况 a=2   b=4

main函数里面也可以调用main,编译不会报错，但是会导致内存溢出。

给整型变量赋值不能超过它的取值范围，否则能产生不可以预估的后果

unsigned short i = 65535;//i正常为65535

如果是

unsigned short i = 65535 +1;//不正常，i=0; 

unsigned short i = 65535 +2;//不正常，i=1; 

如果超出取值范围，编译不会报错，程序也能运行，但是数据会被截断

bool类型的本质就是unsigned char

```
   bool a = true, b = false;
    cout << "a+a+a+a+b=" << a + a + a + a + b << endl; // a+a+a+a+b=4

    bool c = 100, d = false;
    cout << "c+d=" << c + d << endl; // c+d=1  因为c=100,最终也是1
    
        //找到布尔变量的内存 把里面的数据强制为8
    char *e = (char *)&b;
    *e = 255;
    cout << "b=" << b << endl; //将显示255

    *e = 256;
    cout << "b=" << b << endl; //将显示0

    //上面可以看出bool类型的本质就是unsigned char
    // true和false是C++在语法上的处理  
```

```
    // 4294967295是unsigned int 的最大值
    unsigned int f = 4294967295;
    cout << f << endl; //显示 4294967295
    f += 1;
    cout << f << endl; //显示 0
    f += 1;
    cout << f << endl; //显示 1 // 值被截断,从高位截断
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242668.jpg)

void的关键字

在C++中，void表示为无类型 主要有3个用途

1、函数的返回值用void 表示函数没有返回值

2、函数的参数填void,表示函数不需要参数（或者大多数都是让参数列表空着）

3、函数的形参用void* 表示接受任意数据类型的指针。 

注意：

1 不能用void声明变量，它不能代表一个真实的变量；

2 不能对void *指针直接解引用（需要转换成其他类型的指针）

3 把其它类型的指针赋值给void*指针不需要转换

4 把void *指针赋值给其它类型的指针需要转换

堆和栈的主要区别：

1 管理方式不同：栈是系统自动管理的，在出作用域时，将自动被释放；堆需要手动释放，若程序中不释放，程序结束时由操作系统回收。

2 空间大小不同：堆内存的大小受限于物理内存空间；而栈空间小的可怜，一般只有8M（可以修改系统参数）

3 分配方式不同：堆是动态分配的；栈有静态分配和动态分配（都是自动释放）。

4 分配效率不同：栈是系统提供的数据结构，计算机在底层提供了对栈的支持，进栈和出栈有专门的指令，效率比较高；堆由C++函数库提供的。

5 是否产生碎片：对于栈来说，进栈和出栈都是有着严格的顺序（先进后出），不会产生碎片；而堆频繁的分配和释放，会造成内存空间的不连续，容易产生碎片，太多的碎片会导致性能的下降。

6 增长方向不同：栈向下增长，以降序分配内存地址；堆向上增长，以升序分配内存地址。

数组在内存中占用的空间是连续的

用sizeof(数组名)可以得到整个数组占用的空间的大小（只适用于C++的基本数据类型）

数组的地址

1 数组在内存中占用的空间是连续的

2 C++将数组名解释为数组第0个元素的地址

3 数组第0个元素的地址和数组首地址的取值是相同的

4 数组第n个元素的地址是：数组首地址+n

5 C++编译器把数组名[下标] 解释为 *(数组首地址+下标)

 数组名不一定会被解释为地址:

在多数情况下，C++将数组名解释为数组的第0个元素的地址，有一种情况比较例外，就是sizeof运算符用于数组名时，将返回整个数组占用内存空间的字节数。

经常听到有些人说数组名就是地址，这种说法非常的不专业。

还有就是数组名时常量，不可以修改，不要说成指针，指针的值是可以修改的。

**引用可以作为函数重载的条件，但是调用重载函数的  时候，如果实参是变量，编译器形参类型的本身和类型引用视为同一特征**。

对于上述细节 **引用可以作为函数重载的条件，但是调用重载函数的  时候，如果实参是变量，编译器形参类型的本身和类型引用视为同一特征**。的代码演示

```
#include "iostream"

using namespace std;

void show(short bh, string message) {
    cout << "short bh,string message" << endl;
}

void show(short &bh, string message) {
    cout << "short &bh,string message" << endl;
}

void test() {
    short a = 10;
    show(a, "hello");
}

int main() {
    test();
    return 0;
}

编译报错 call to 'show' is ambiguous
```

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242415.jpg)

但是改成常量10就能匹配到第一个show,因为常量10不能引用。

另外一个测试

在第二个show的bh参数加上const

![](https://gitee.com/hxc8/images3/raw/master/img/202407172242482.jpg)

也会报不明确,因为加了const后 变成 const short &hb,这个时候，10会创建临时变量再赋值，所以两个show都能匹配。导致报错

iterator begin();和const_iterator() begin();是一样的，如果把auto关键字用在begin()上面，无法推导出const。

C++11标准增加了cbegin()函数，可以推导出const

```
#include <iostream>
#include <vector>
#include <forward_list>
using namespace std;
template <typename T>
void _foreach(const T first, const T last)
{
    for (auto it = first; it != last; it++)
        cout << *it << " ";
    cout << endl;
}

void zsshow(const string &no)
{
    cout << no << " ";
}

//用户自定义函数指针
template <typename T>
void _foreach2(const T first, const T last, void (*pfunc)(const string &))
{
    for (auto it = first; it != last; it++)
        pfunc(*it);
    cout << endl;
}

//调用类的成员函数
class CTmp
{
public:
    void show(const string &no)
    {
        cout << "call show:" << no << " ";
    }
    void operator()(const string &no)
    {
        cout << "call operator:" << no << " ";
    }
};
template <typename T>
void _foreach3(const T first, const T last, CTmp ct)
{
    for (auto it = first; it != last; it++)
        ct.show(*it);
    cout << endl;
}

template <typename T>
void _foreach4(const T first, const T last, CTmp ct)
{
    for (auto it = first; it != last; it++)
        ct(*it);
    cout << endl;
}

//模板

template <typename T1, typename T2>
void _foreach5(const T1 first, const T1 last, T2 func)
{
    for (auto it = first; it != last; it++)
        func(*it);
    cout << endl;
}
void test()
{
    // vector<int> bh{2, 3, 4, 5, 6};
    vector<string> bh{"2", "3", "4", "5", "6"};
    _foreach(bh.begin(), bh.end());
    //用户自定义函数指针
    _foreach2(bh.begin(), bh.end(), zsshow);
    //调用类的成员函数
    _foreach3(bh.begin(), bh.end(), CTmp());
    //仿函数
    _foreach4(bh.begin(), bh.end(), CTmp());
    //不管形参是什么数据类型,只要函数里面的代码相同,就可以整成模板

    cout << endl;
    //第三个参数既可以是函数,也可以是类,重载了小括号的类,也叫仿函数
    _foreach5(bh.begin(), bh.end(), CTmp());
    _foreach5(bh.begin(), bh.end(), zsshow);
}
int main()
{
    test();
    return 0;
}
/* 2 3 4 5 6
2 3 4 5 6
call show:2 call show:3 call show:4 call show:5 call show:6
call operator:2 call operator:3 call operator:4 call operator:5 call operator:6

call operator:2 call operator:3 call operator:4 call operator:5 call operator:6
2 3 4 5 6 */
```

```
#include <iostream>
#include <vector>
using namespace std;

template <typename T>
void show(const T &str)
{
    cout << str << endl;
}

template <class T>
class CTmp
{
public:
    void operator()(const T &no)
    {
        cout << "call operator:" << no << " ";
    }
};

template <typename T1, typename T2>
void _foreach(const T1 first, const T1 last, T2 func)
{
    for (auto it = first; it != last; it++)
        func(*it);
    cout << endl;
}
void test()
{
    // vector<int> bh{2, 3, 4, 5, 6};
    vector<string> bh{"A2", "A3", "A4", "A5", "A6"};
    _foreach(bh.begin(), bh.end(), CTmp<string>());
    _foreach(bh.begin(), bh.end(), show<string>);
}
int main()
{
    test();
    return 0;
}
/*
call operator:A2 call operator:A3 call operator:A4 call operator:A5 call operator:A6
A2
A3
A4
A5
A6*/
```

```
void outcache() //消费者线程任务函数
    {
        string msg;
        while (true)
        {
            {
                unique_lock<mutex> lock(m_mutex);
                // while (m_q.empty())
                // {
                //     m_cond.wait(lock);
                //     cout << "线程:" << this_thread::get_id() << "被唤醒了" << endl;
                // }

                m_cond.wait(lock, [this]()
                            { return !m_q.empty(); }); //效果同上面while,这个重载的wait函数里面也有个while循环

                //数据出队
                msg = m_q.front();
                m_q.pop();
                cout << "线程:" << this_thread::get_id() << "处理数据," << msg << endl;
            }
            this_thread::sleep_for(chrono::seconds(5));
        }
    }
```