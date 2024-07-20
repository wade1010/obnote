C++11提供了atomic<T>模板类（结构体），用于支持原子类型，模板参数可以是bool、char、int、long、long long、指针类型（不支持浮点类型和自定义数据类型）。

原子操作由CPU指令提供支持，它的性能比锁和消息传递更高，并且，不需要程序员处理加锁和释放锁的问题，支持修改、读取、交换、比较并交换等操作。

头文件：#include <atomic>

```
#include <iostream>
#include <thread>
#include <chrono>
#include <atomic>
using namespace std;

// int aa = 0;
//定义为原子类型

// atomic<int> aa = 0;//报错
atomic<int> aa; //默认为0
// 或者
// atomic<int> aa(0);
// atomic<int> aa(1); //初始化为1

void func()
{
    for (int i = 0; i < 1000000; i++)
    {
        aa++;
    }
}
void test()
{
    thread t1(func);
    thread t2(func);
    t1.join();
    t2.join();
    cout << "aa=" << aa << endl;
}
int main()
{
    test();
    return 0;
}

```

```
#include <iostream>
#include <atomic>
using namespace std;

void test()
{
    atomic<int> a(3);
    cout << "a=" << a.load() << endl; //读取原子变量a的值
    a.store(11);
    cout << "a=" << a.load() << endl; //读取原子变量a的值

    int old;              //用于存放原值
    old = a.fetch_add(5); //把原子变量a的值与5相加,返回原值
    cout << "old=" << old << ",a=" << a.load() << endl;

    old = a.fetch_sub(6);
    cout << "old=" << old << ",a=" << a.load() << endl;
}
int main()
{
    test();
    return 0;
}
/* a=3
a=11
old=11,a=16
old=16,a=10 */
```

CPU指令一共才100多条，都是这么死板， 

```
#include <iostream>
#include <atomic>
using namespace std;

void test()
{
    atomic<int> ii(3); // 原子变量
    int expect = 3;    // 期待值
    // int expect = 4;    // 期待值
    int val = 5; // 打算存入原子变量的值
    // 比较原子变量的值和预期值expect，
    // 如果当两个值相等，把val存储到原子变量中；
    // 如果当两个值不相等，用原子变量的值更新预期值。
    // 执行存储操作时返回true，否则返回false。
    bool bret = ii.compare_exchange_strong(expect, val);
    cout << "bret=" << bret << endl;
    cout << "ii=" << ii << endl;
    cout << "expect=" << expect << endl;
}
int main()
{
    test();
    return 0;
}
//  int expect = 3;
/* bret=0
ii=3
expect=3 */

//  int expect = 4;
/* bret=1
ii=5
expect=3 */
```

注意：

- atomic<T>模板类重载了整数操作的各种运算符。

- atomic<T>模板类的模板参数支持指针，但不表示它所指向的对象是原子类型。

- 原子整型可以用作计数器，布尔型可以用作开关。

- CAS指令是实现无锁队列基础。

atomic<T>模板类的模板参数支持指针，但不表示它所指向的对象是原子类型。代码演示如下：

```
#include <iostream>
#include <thread>
#include <atomic>
#include <chrono>
using namespace std;
//指针是原子类型不代表它指向的对象时原子类型
int aa = 0;

atomic<int *> ptr(&aa);

void func()
{
    for (int i = 0; i < 1000000; i++)
    {
        (*ptr)++;
    }
}
void test()
{
    thread t1(func);
    thread t2(func);
    t1.join();
    t2.join();
    cout << "aa=" << aa << endl;
}
int main()
{
    test();
    return 0;
}
/* 输出结果不是2000000
aa=1207026
 */
```