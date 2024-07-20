虽然同一个进程的多个线程共享进程的栈空间，但是，每个子线程在这个栈中拥有自己私有的栈空间。所以，线程结束时需要回收资源。

回收子线程的资源有两种方法：

1）在主程序中，调用join()成员函数等待子线程退出，回收它的资源。如果子线程已退出，join()函数立即返回，否则会阻塞等待，直到子线程退出。

2）在主程序中，调用detach()成员函数分离子线程，子线程退出时，系统将自动回收资源。分离后的子线程不可join()。

用joinable()成员函数可以判断子线程的分离状态，函数返回布尔类型。

子线程的资源一定要回收，如果不回收，会产生僵尸线程，程序还会报错。

 

```
#include <iostream>
#include <thread>
#include <unistd.h>
using namespace std;
//普通函数

void func(int no, const string &str)
{
    for (int i = 0; i < 10; i++)
    {
        cout << no << " " << str << endl;
        sleep(1);
    }
}

int main()
{
    thread t1(func, 1, "hello");
    thread t2(func, 2, "world");
    cout << "start" << endl;
    for (int i = 0; i < 5; i++)
    {
        cout << "doing" << endl;
        sleep(1);
    }
    cout << "end" << endl;
    return 0;  //主线程提前退出,程序运行会报错
    t1.join(); //回收线程t1的资源
    t2.join(); //回收线程t2的资源
}
/* start
doing
1 hello
2 world
doing
2 world
1 hello
2 1 hello
world
doing
1 hello
doing
2 world
2 doingworld

1 hello
1 endhello

2 world
terminate called without an active exception

[Done] exited with code=3 in 5.541 seconds */
```

```
#include <iostream>
#include <thread>
#include <unistd.h>
using namespace std;
//普通函数

void func(int no, const string &str)
{
    for (int i = 0; i < 10; i++)
    {
        cout << no << " " << str << endl;
        sleep(1);
    }
}
int main()
{
    /*     thread t1(func, 1, "hello");
        thread t2(func, 2, "world");
        cout << "start" << endl;
        for (int i = 0; i < 15; i++)
        {
            cout << "doing" << endl;
            sleep(1);
        }
        cout << "end" << endl;
        return 0;  //主线程提前退出,子线程资源没有回收,程序运行会报错
        t1.join(); //回收线程t1的资源
        t2.join(); //回收线程t2的资源 */

    /* thread t1(func, 1, "hello");
    thread t2(func, 2, "world");
    cout << "start" << endl;
    for (int i = 0; i < 5; i++)
    {
        cout << "doing" << endl;
        sleep(1);
    }
    cout << "end" << endl;
    t1.join(); //回收线程t1的资源
    t2.join(); //回收线程t2的资源 */

    thread t1(func, 1, "hello");
    thread t2(func, 2, "world");
    t1.detach();
    t2.detach();
    sleep(15);
}

```