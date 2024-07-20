promise  用于异步传输变量

std::promise  提供存储异步通信的值，再通过其对象创建的std::future异步获得结果

std::promise 只能使用一次。void set_value(_Ty & _Val)设置，只能调用一次

std::future 提供访问异步操作结果的机制

get()阻塞等待promise set_value的值

```
#include <iostream>
#include <thread>
#include <future>
#include <chrono>
using namespace std;

void testFuture(promise<string> p)
{
    this_thread::sleep_for(chrono::seconds(1));
    cout << "begin set value" << endl;
    p.set_value("hello world");
    this_thread::sleep_for(chrono::seconds(1));
    cout << "end set value" << endl;
}
void test()
{
    // 异步传输变量 存储
    promise<string> pStr;
    // 用来获取线程异步值
    auto future = pStr.get_future();

    thread t(testFuture, move(pStr));

    cout << "begin future get()" << endl;
    auto ret = future.get();
    cout << "future get() = " << ret << endl;
    cout << "end future get()" << endl;

    t.join();
}
int main()
{
    test();
    return 0;
}

/*
begin future get()
begin set value
future get() = hello world
end future get()
end set value
 */
```

可以看出，set_value后，不需要等待子线程结束，主线程future.get()就能获取到结果