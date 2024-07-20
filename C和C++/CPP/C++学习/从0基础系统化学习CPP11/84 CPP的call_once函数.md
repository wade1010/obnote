在多线程环境中，某些函数只能被调用一次，例如：初始化某个对象，而这个对象只能被初始化一次。

在线程的任务函数中，可以用std::call_once()来保证某个函数只被调用一次。

头文件：#include <mutex>

template< class callable, class... Args >

void call_once( std::once_flag& flag, Function&& fx, Args&&... args );

第一个参数是std::once_flag，用于标记函数fx是否已经被执行过。

第二个参数是需要执行的函数fx。

后面的可变参数是传递给函数fx的参数。

```
#include <iostream>
#include <thread>
#include <chrono>
#include <mutex>
using namespace std;

once_flag onceflag; // once_flag全局变量,本质是取值为0和1的锁
//在线程中,打算只调用一次的函数
void call_once_func(int no, const string &str)
{
    cout << "call_once_func:" << no << " " << str << endl;
}
void func(int no, const string &str)
{
    call_once(onceflag, call_once_func, no, str);

    for (int i = 0; i < 10; i++)
    {
        cout << no << str << endl;
        this_thread::sleep_for(chrono::seconds(1));
    }
}
int main()
{
    thread t1(func, 1, "hello");
    thread t2(func, 2, "world");
    t1.join();
    t2.join();
    return 0;
}
/* 2world
1hello
1hello
2world
1hello
2world
2world
1hello
2world
1hello
1hello
2world
1hello
2world
1hello
2world
1hello
2world
1hello
2world */
```