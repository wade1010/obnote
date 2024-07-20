线程同步是指多个线程协同工作，协商如何使用共享资源。

C++11线程同步包括三个方面的内容

- 互斥锁（互斥量）

- 条件变量

- 生产者/消费者模型

互斥锁：

加锁和解锁，确保同一时间只有一个线程访问共享资源

访问共享资源之前加锁，访问完成后释放锁。

如果某线程持有锁，其它线程形成等待队列。

C++11提供了四种互斥锁：

1 mutex：互斥锁。

2 timed_mutex：带超时机制的互斥锁。

3 recursive_mutex：递归互斥锁。

4 recursive_timed_mutex：带超时机制的递归互斥锁。

包含头文件：#include <mutex>

##### mutex类

1）加锁lock()

互斥锁有锁定和未锁定两种状态。

如果互斥锁是未锁定状态，调用lock()成员函数的线程会得到互斥锁的所有权，并将其上锁。

如果互斥锁是锁定状态，调用lock()成员函数的线程就会阻塞等待，直到互斥锁变成未锁定状态。

2）解锁unlock()

只有持有锁的线程才能解锁。

3）尝试加锁try_lock()

如果互斥锁是未锁定状态，则加锁成功，函数返回true。

如果互斥锁是锁定状态，则加锁失败，函数立即返回false。（线程不会阻塞等待）

```
#include <iostream>
#include <thread>
#include <mutex>
#include <chrono>
using namespace std;

int aa = 0;

mutex mtx;

void func()
{
    for (int i = 0; i < 1000000; i++)
    {
        cout << "线程:" << this_thread::get_id() << "申请加锁..." << endl;
        mtx.lock();
        cout << "线程:" << this_thread::get_id() << "加锁成功" << endl;
        aa++;
        this_thread::sleep_for(chrono::seconds(5));
        mtx.unlock();
        cout << "线程:" << this_thread::get_id() << "释放了锁" << endl;
        this_thread::sleep_for(chrono::seconds(1));
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
/* .....
线程:2释放了锁
线程:3加锁成功
线程:2申请加锁...
线程:3释放了锁
线程:2加锁成功
线程:3申请加锁...
线程:2释放了锁
..... */
```

##### timed_mutex类

增加了两个成员函数：

bool try_lock_for(时间长度);

bool try_lock_until(时间点);

##### recursive_mutex类

递归互斥锁允许同一线程多次获得互斥锁，可以解决同一线程多次加锁造成的死锁问题。

```
#include <iostream>
#include <mutex>
using namespace std;
class AA
{
    // mutex m_mtx;//本demo直接用mutex会发生死锁
    recursive_mutex m_mtx;

public:
    void func1()
    {
        m_mtx.lock();
        cout << "func1" << endl;
        m_mtx.unlock();
    }
    void func2()
    {
        m_mtx.lock();
        cout << "func2" << endl;
        func1();
        m_mtx.unlock();
    }
};
void test()
{
    AA a;
    // a.func1();
    //死锁,普通的互斥锁必须在解锁后才能加锁,就算是同一个线程也不能例外.这时候可以用 recursive_mutex
    a.func2();
}
int main()
{
    test();
    return 0;
}
```

死锁,普通的互斥锁必须在解锁后才能加锁,就算是同一个线程也不能例外.这时候可以用recursive_mutex

##### lock_guard类

lock_guard是模板类，可以简化互斥锁的使用，也更安全

lock_guard的定义如下：

template<class Mutex>

class lock_guard

{

explicit lock_guard(Mutex& mtx);

}

lock_guard在构造函数中加锁，在析构函数中解锁。

lock_guard采用了RAII思想（在类构造函数中分配资源，在析构函数中释放资源，保证资源在离开作用域时自动释放）。智能指针也是采用RAII思想。

```
#include <iostream>
#include <thread>
#include <mutex>
#include <chrono>
using namespace std;

int aa = 0;

mutex mtx;

void func()
{
    for (int i = 0; i < 1000000; i++)
    {
        lock_guard<mutex> lockguard(mtx);
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