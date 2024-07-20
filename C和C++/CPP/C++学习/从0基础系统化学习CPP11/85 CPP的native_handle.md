C++11定义了线程标准，不同的平台和编译器在实现的时候，本质上都是对操作系统的线程库进行封装，会损失一部分功能。

为了弥补C++11线程库的不足，thread类提供了native_handle()成员函数，用于获得与操作系统相关的原生线程句柄，操作系统原生的线程库就可以用原生线程句柄操作线程。

```
#include <iostream>
#include <thread>
#include <chrono>
#include <pthread.h>
using namespace std;
void func()
{
    for (int i = 0; i < 10; i++)
    {
        cout << i << endl;
        this_thread::sleep_for(chrono::seconds(1));
    }
}
void test()
{
    thread t1(func);
    this_thread::sleep_for(chrono::seconds(5));
    pthread_t theadid = t1.native_handle(); // 获取操作系统原生的线程句柄。
    pthread_cancel(theadid);

    t1.join();
}
int main()
{
    test();
    return 0;
}
//线程第五秒的时候被取消了,所以输出的结果不全
/* 0
1
2
3
4 */
```