C++11提供了命名空间this_thread来表示当前线程，该命名空间中有四个函数：get_id()、sleep_for()、sleep_until()、yield()。

1）get_id()

thread::id get_id() noexcept;

该函数用于获取线程ID，thread类也有同名的成员函数。

2）sleep_for()  VS  Sleep(1000)   Linux sleep(1)

template <class Rep, class Period>

void sleep_for (const chrono::duration<Rep,Period>& rel_time);

该函数让线程休眠一段时间。

3）sleep_until()          2022-01-01 12:30:35

template <class Clock, class Duration>

void sleep_until (const chrono::time_point<Clock,Duration>& abs_time);

该函数让线程休眠至指定时间点。（可实现定时任务）

```
#include <iostream>
#include <iomanip>
#include <chrono>
#include <ctime>
#include <thread>
using namespace std;

void test()
{
    time_t tt = chrono::system_clock::to_time_t(chrono::system_clock::now());
    tm *ptm = localtime(&tt);
    cout << "current time:" << put_time(ptm, "%Y-%m-%d %H:%M:%S") << endl;
    cout << "waiting for the next minute to begin..." << endl;
    ptm->tm_min++;
    ptm->tm_sec = 0;
    this_thread::sleep_until(chrono::system_clock::from_time_t(mktime(ptm)));
    cout << put_time(ptm, "%Y-%m-%d %H:%M:%S") << "reached!" << endl;
}
int main()
{
    test();
    return 0;
}
/* current time:2022-12-02 11:12:24
waiting for the next minute to begin...
2022-12-02 11:13:00reached! */
```

```
#include <iostream>
#include <thread>
#include <chrono>
#include <unistd.h>
using namespace std;
//普通函数

void func(int no, const string &str)
{
    cout << "子线程:" << this_thread::get_id() << endl;
    for (int i = 0; i < 10; i++)
    {

        cout << no << " " << str << endl;
        // sleep(1);
        // this_thread::sleep_for(chrono::seconds(1));
        //休眠到当前时间往后1秒
        std::this_thread::sleep_until(std::chrono::steady_clock::now() + std::chrono::seconds(1));
    }
}
int main()
{
    thread t1(func, 1111, "hello");
    thread t2(func, 2222, "world");
    cout << "主线程:" << this_thread::get_id() << endl;
    t1.join(); //回收线程t1的资源
    t2.join(); //回收线程t2的资源
}

```

4）yield()

void yield() noexcept;

该函数让线程主动让出自己已经抢到的CPU时间片。

5）thread类其它的成员函数

void swap(std::thread& other);    // 交换两个线程对象。

static unsigned hardware_concurrency() noexcept;   // 返回硬件线程上下文的数量。

The interpretation of this value is system- andimplementation- specific, and may not be exact, but just an approximation.

Note that this does not need to match the actualnumber of processors or cores available in the system: A system can supportmultiple threads per processing unit, or restrict the access to its resourcesto the program.

If this value is not computable or well defined,the function returns 0.

    

线程对象不能拷贝，不能赋值，但是能交换，可以转移。