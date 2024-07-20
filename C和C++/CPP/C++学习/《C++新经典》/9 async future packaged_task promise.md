一、std::async、std::future创建后台任务并返回值

希望线程返回一个结果

std::async是个函数模板，用来启动一个异步任务，启动起来一个异步任务之后，它返回一个std::future对象，std::future是个类模板。

什么叫“启动一个异步任务”，就是自动创建一个线程并开始执行对应的线程入口函数，它返回一个std::future对象，

这个std::future对象里边就含有线程入口函数所返回的结果（线程返回的结果），我们可以通过调用future对象的成员函数get()获取结果。

future：将来的意思，有人也称呼std::future提供了一种访问异步操作结果的机制，也就是说这个结果你可能没办法马上拿到，但不久的将来，线程执行完毕的时候，你就能够拿到了结果，所以大家就这么理解：这个future（对象）里会保存一个值，在将来某个时刻能够拿到。

```
#include <iostream>
#include <future>
#include <thread>
using namespace std;

int mythread()
{
    cout << "mythread() start,thread id=" << this_thread::get_id() << endl;
    this_thread::sleep_for(chrono::milliseconds(5000));
    cout << "mythread() end,thread id=" << this_thread::get_id() << endl;
    return 5;
}

class A
{
public:
    int mythread(int a)
    {
        cout << "A::mythread() start,thread id=" << this_thread::get_id() << endl;
        this_thread::sleep_for(chrono::milliseconds(5000));
        cout << "A::mythread() end,thread id=" << this_thread::get_id() << endl;
        return a;
    }
};

int main()
{
    /*

    一、std::async、std::future创建后台任务并返回值
希望线程返回一个结果
std::async是个函数模板，用来启动一个异步任务，启动起来一个异步任务之后，它返回一个std::future对象，std::future是个类模板。

什么叫“启动一个异步任务”，就是自动创建一个线程并开始执行对应的线程入口函数，它返回一个std::future对象，
这个std::future对象里边就含有线程入口函数所返回的结果（线程返回的结果），我们可以通过调用future对象的成员函数get()获取结果。

future：将来的意思，有人也称呼std::future提供了一种访问异步操作结果的机制，也就是说这个结果你可能没办法马上拿到，但不久的将来，线程执行完毕的时候，你就能够拿到了结果，所以大家就这么理解：这个future（对象）里会保存一个值，在将来某个时刻能够拿到。


下面程序通过std::future对象的get()成员函数等待线程执行结束并返回结果
这个get()函数不拿到将来的返回值,就卡在这里等待返回值

我们通过额外像std::async()传递一个参数,该参数类型时std::launch类型(枚举类型),来达到 一些特殊目的;
a) std::launch::deferred:表示线程入口函数调用被延迟到std::future的wait()或者get()函数调用时才执行;
    如果wait()或者get()没有被调用,那么线程会执行吗?
    没有执行,实际上,线程根本没有创建
std::launch::deferred:延迟调用,并且没有创建新线程,是在主线程中调用的线程入口函数(可以通过打印出来的线程id发现)

b) std::launch::async:在调用async函数的时候就开始创建线程;
    //async()函数,默认用的就是std::launch::async|std::launch::deferred,系统会执行决定是异步还是同步方式去运行
     */

    cout << "main start,thread id" << this_thread::get_id() << endl;

    // 调用普通全局函数
    // future<int> result = async(mythread); // 后面即使不调用result.get()或者result.wait(),主线程退出前,都会等待该线程执行完成(若从std::async获得的std::future未被移动或绑定到引用,则在完整表达式结尾,std::future的析构函数将阻塞,直至异步计算完成)
    // 调用普通成员函数
    A a;
    // future<int> result = async(&A::mythread, a, 1);
    future<int> result = async(std::launch::async, &A::mythread, a, 1);
    cout << "continue....." << endl;
    int def;
    def = 0;
    cout << result.get() << endl; // 在这等待async线程执行完成,返回结果,而且get()只能调用一次,调用多次会报异常
    // result.wait();                // 等待线程返回,本身并不返回结果
    cout << "main end" << endl;

    return 0;
}

```

std::packaged_task

```
#include <iostream>
#include <future>
#include <thread>
#include <vector>
using namespace std;

int mythread(int a)
{
    cout << a << endl;
    cout << "mythread() start,thread id=" << this_thread::get_id() << endl;
    this_thread::sleep_for(chrono::milliseconds(5000));
    cout << "mythread() end,thread id=" << this_thread::get_id() << endl;
    return 5;
}

class A
{
public:
    int mythread(int a)
    {
        cout << "A::mythread() start,thread id=" << this_thread::get_id() << endl;
        this_thread::sleep_for(chrono::milliseconds(5000));
        cout << "A::mythread() end,thread id=" << this_thread::get_id() << endl;
        return a;
    }
};

vector<packaged_task<int(int)>> mytasks;

int main()
{
    /*
    std::packaged_task:打包任务,把任务包装起来
    是个类模板,它的模板参数是  各种可调用对象;通过std::packaged_task来把各种可调用对象包装起来,方便将来作为线程入口函数来调用
    packaged_task包装起来的可调用对象还可以直接调用,所以这个角度来讲,packaged_task对象,也是一个可调用对象
     */
    cout << "main start,thread id:" << this_thread::get_id() << endl;
    // std::packaged_task<int(int)> mypt(mythread); // 我们把函数mythread通过packaged_task包装起来

    std::packaged_task<int(int)> mypt([](int a)
                                      {
          cout << a << endl;
    cout << "mythread() start,thread id=" << this_thread::get_id() << endl;
    this_thread::sleep_for(chrono::milliseconds(5000));
    cout << "mythread() end,thread id=" << this_thread::get_id() << endl;
    return 5; });

    std::thread t1(std::ref(mypt), 1); // 1是线程入口函数的参数
    t1.join();
    std::future<int> result = mypt.get_future();
    cout << result.get() << endl;

    // packaged_task包装起来的可调用对象还可以直接调用,所以这个角度来讲,packaged_task对象,也是一个可调用对象
    std::packaged_task<int(int)> mypt2(mythread);
    mypt2(2); // 直接调用,相当于函数调用,不会创建子线程,直接在主线程中执行
    std::future<int> result2 = mypt2.get_future();
    cout << result2.get() << endl;

    std::packaged_task<int(int)> mypt3(mythread);
    mytasks.push_back(std::move(mypt3));
    std::packaged_task<int(int)> mypt4;
    auto iter = mytasks.begin();
    mypt4 = std::move(*iter); // 移动语义
    mytasks.erase(iter);      // 删除第一个元素,迭代已经失效了,所以后续代码不可以再使用iter了
    mypt4(1111);
    std::future<int> result3 = mypt4.get_future();
    cout << result3.get() << endl;

    return 0;
}

```

```
#include <iostream>
#include <future>
#include <thread>
using namespace std;
void mythread(std::promise<int> &p, int x)
{
    x++;
    x *= 10;
    std::this_thread::sleep_for(std::chrono::seconds(1));
    p.set_value(x);
    cout << "thread id=" << this_thread::get_id() << endl;
}

void mythread2(std::future<int> &myFuture)
{
    cout << "mythread2 result:" << myFuture.get() << endl;
    cout << "thread id=" << this_thread::get_id() << endl;
}

int main()
{
    /*
    std::promise,类模板
    我们能够在某个线程中给它赋值,然后能在其它线程中把这个值取出来用

    总结:通过promise保存一个值,在将来某个时刻我们通过把一个future绑定到这个promise上,来得到这个绑定的值;

     */

    std::promise<int> myPromise;
    std::thread t1(mythread, std::ref(myPromise), 22);
    t1.join();
    // 获取结果
    std::future<int> myFuture = myPromise.get_future(); // promise和future绑定,用于获取线程返回值
    cout << myFuture.get() << endl;                     // get只能调用一次

    // 传给另外一个线程
    std::thread t2(mythread2, std::ref(myFuture));
    t2.join();

    return 0;
}

```

参数std::launch::deferred【延迟调用】，以及std::launch:async【强制创建一个线程】

std::thread()如果系统资源紧张，那么可能创建线程就会失败，那么执行std::thread()时整个程序可能崩溃。

std::async()我们一般不叫创建线程（解释async能够创建线程），我们一般叫它创建一个异步任务。

std::async和std::thread最明显的不同，就是async有时候并不创建新线程。

a)如果你用std::launch::deferred来调用async会怎么样？

std::launch::deferred延迟调用，并且不创建新线程，延迟到future对象调用.get()或者.wait()的时候才执行mythread(),如果没有调用get或者wait，那么这个

mythread()不回执行。	                                 

b)std::launch::async，强制这个异步任务在新线程上执行，这意味着，系统必须要给我创建出新线程来运行mythread();

c)std::launch::async|std::launch::deferred

这里这个|：意味着调用async的行为可能是 “创建新线程并立即执行”或者 没有创建新线程并且延迟到调用result.get()才开始执行任务入口函数，两者居其一；

d)我们不带额外参数；只给async函数一个入口函数名,默认值应该是std:launch:async|std:launch:deferred

换句话说：系统会自行决定是异步（创建新线程）还是同步（不创建新线程）方式运行。

自行决定是啥意思？系统如何决定是异步（创建新线程）还是同步（不创建新线程）方式运行

2.2) std::async和std::thread的区别

std::thread创建线程,如果系统资源进栈，创建线程失败，那么整个程序就会报异常崩溃

//int mythread(){return 1:

//std:thread mytobj(mythread):

//mytobj.join()

std::thread创建线程的方式，如果线程返回值，你想拿到这个值也不容易；

std::asyc创建异步任务。可能创建也可能不创建线程。并且async调用方法很容易拿到线程入口函数的返回值；

由于系统资源限制：

1)如果用std::thread创建的线程太多，则可能创建失败，系统报告异常，崩溃。

2)如果用std::async,一般就不会报异常不会崩溃，因为如果系统资源紧张导致无法创建新线程的时候，std::async这种不加外参数的调用就不会创建新线程。而是后续谁调用了result.get()来请求结果，那么这个异步任务my_thread就运行在执行这条get()语句所在的线程上。

如果你强制std::async一定要创建新线程，那么就必须使用std::launch::async。承受的代价就是系统资源紧张时，程序崩溃。

```
#include <iostream>
#include <thread>
#include <future>
#include <chrono>
using namespace std;

int mythread()
{
    cout << "mythread thread id = " << this_thread::get_id() << endl;
    return 111;
}

int main()
{
    cout << "main thread id = " << this_thread::get_id() << endl;
    std::future<int> result = std::async(mythread);
    std::future_status status = result.wait_for(chrono::seconds(1));
    if (status == std::future_status::deferred)
    {
        // 线程被延迟执行了(系统资源紧张了,系统给我采用了std::launch::deferred策略)
        cout << result.get() << endl;
    }
    else
    {
        // 任务没有被推迟,已经开始运行了,线程被创建
        if (status == std::future_status::ready)
        {
            cout << "线程成功执行完毕并返回" << endl;
            cout << result.get() << endl;
        }
        else if (status == std::future_status::timeout)
        {
            cout << "超时线程没执行完" << endl;
            cout << result.get() << endl;
        }
    }

    return 0;
}
```