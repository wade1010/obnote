条件变量：

是一种线程同步机制，当条件不满足时，相关线程被一直阻塞，直到某种条件出现，这些线程才会被唤醒。

为了保护共享资源，条件变量需要和互斥锁结合一起使用。

条件变量最常用的就是实现生产者和消费者模型

生产者消费者模型可以实现告诉缓存队列

```
#include <iostream>
#include <mutex>
#include <queue>
#include <thread>
#include <chrono>
#include <condition_variable>
using namespace std;
class AA
{
    mutex m_mutex;             //互斥锁
    condition_variable m_cond; //条件变量
    queue<string> m_q;         //缓存队列
public:
    void incache(int num) //生产数据,num指定数据的个数
    {
        lock_guard<mutex> lock(m_mutex);
        for (int i = 0; i < num; i++)
        {
            static int id = 1;
            string msg = to_string(id++) + "号选手";
            m_q.push(msg); //把生产出来的数据入队
        }
        m_cond.notify_one(); //唤醒一个被当前条件变量阻塞的线程
    }

    void outcache() //消费者线程任务函数
    {
        string msg;
        while (true)
        {
            //加一个作用域,出作用域  unique_lock自动释放锁
            {
                //把互斥锁转换成unique_lock<mutex>,并申请加锁
                unique_lock<mutex> lock(m_mutex);
                //如果队列空,进入循环,否则字节处理数据,必须用循环,不能用if
                while (m_q.empty())
                {
                    m_cond.wait(lock);
                }

                //数据出队
                msg = m_q.front();
                m_q.pop();
            }

            //处理出队的数据
            cout << "处理的数据为:" << msg << endl;
            this_thread::sleep_for(chrono::milliseconds(1)); //假设处理数据需要消耗1毫秒
        }
    }
};
void test()
{
    AA aa;
    //给线程传递类的普通成员函数
    thread t1(&AA::outcache, &aa); //创建消费者线程t1
    thread t2(&AA::outcache, &aa); //创建消费者线程t2
    thread t3(&AA::outcache, &aa); //创建消费者线程t3

    this_thread::sleep_for(chrono::seconds(2));
    aa.incache(3);

    this_thread::sleep_for(chrono::seconds(3));
    aa.incache(5);

    t1.join();
    t2.join();
    t3.join();
}
int main()
{
    test();
    return 0;
}
/* 处理的数据为:1号选手
处理的数据为:2号选手
处理的数据为:3号选手
处理的数据为:4号选手
处理的数据为:5号选手
处理的数据为:6号选手
处理的数据为:7号选手
处理的数据为:8号选手 */
```

C++11的条件变量提供了两个类：

condition_variable：只支持与普通mutex搭配，效率更高。

condition_variable_any：是一种通用的条件变量，可以与任意mutex搭配（包括用户自定义的锁类型）。

包含头文件：<condition_variable>

##### condition_variable类

主要成员函数：

1）condition_variable() 默认构造函数。

2）condition_variable(const condition_variable &)=delete 禁止拷贝。

3）condition_variable& condition_variable::operator=(const condition_variable &)=delete 禁止赋值。

4）notify_one() 通知一个等待的线程。

5）notify_all() 通知全部等待的线程。

6）wait(unique_lock<mutex> lock) 阻塞当前线程，直到通知到达。

7）wait(unique_lock<mutex> lock,Pred pred) 循环的阻塞当前线程，直到通知到达且谓词满足。

8）wait_for(unique_lock<mutex> lock,时间长度)

9）wait_for(unique_lock<mutex> lock,时间长度,Pred pred)

10）wait_until(unique_lock<mutex> lock,时间点)

11）wait_until(unique_lock<mutex> lock,时间点,Pred pred)

```
#include <iostream>
#include <mutex>
#include <queue>
#include <thread>
#include <chrono>
#include <condition_variable>
using namespace std;
// notify_one改为notify_all
class AA
{
    mutex m_mutex;             //互斥锁
    condition_variable m_cond; //条件变量
    queue<string> m_q;         //缓存队列
public:
    void incache(int num) //生产数据,num指定数据的个数
    {
        lock_guard<mutex> lock(m_mutex);
        for (int i = 0; i < num; i++)
        {
            static int id = 1;
            string msg = to_string(id++) + "号选手";
            m_q.push(msg); //把生产出来的数据入队
        }
        // m_cond.notify_one(); //唤醒一个被当前条件变量阻塞的线程
        m_cond.notify_all(); //唤醒所有被当前条件变量阻塞的线程
    }

    void outcache() //消费者线程任务函数
    {
        string msg;
        while (true)
        {
            //加一个作用域,出作用域  unique_lock自动释放锁
            {
                //把互斥锁转换成unique_lock<mutex>,并申请加锁
                unique_lock<mutex> lock(m_mutex);
                //如果队列空,进入循环,否则字节处理数据,必须用循环,不能用if
                while (m_q.empty())
                {
                    cout << "进入休眠" << endl;
                    m_cond.wait(lock);
                }

                //数据出队
                msg = m_q.front();
                m_q.pop();
                cout << "线程:" << this_thread::get_id() << "处理数据," << msg << endl;
            }

            //处理出队的数据
            this_thread::sleep_for(chrono::milliseconds(1)); //假设处理数据需要消耗1毫秒
        }
    }
};
void test()
{
    AA aa;
    //给线程传递类的普通成员函数
    thread t1(&AA::outcache, &aa); //创建消费者线程t1
    thread t2(&AA::outcache, &aa); //创建消费者线程t2
    thread t3(&AA::outcache, &aa); //创建消费者线程t3

    this_thread::sleep_for(chrono::seconds(2));
    aa.incache(3);

    this_thread::sleep_for(chrono::seconds(3));
    aa.incache(5);

    t1.join();
    t2.join();
    t3.join();
}
int main()
{
    test();
    return 0;
}
// m_cond.notify_one(); //唤醒一个被当前条件变量阻塞的线程  输出内容如下
/* 进入休眠
进入休眠
进入休眠
线程:3处理数据,1号选手
线程:3处理数据,2号选手
线程:3处理数据,3号选手
进入休眠
线程:2处理数据,4号选手
线程:2处理数据,5号选手
线程:2处理数据,6号选手
线程:2处理数据,7号选手
线程:2处理数据,8号选手
进入休眠 */

// m_cond.notify_all(); //唤醒所有被当前条件变量阻塞的线程  输出内容如下
/* 进入休眠
进入休眠
进入休眠
线程:3处理数据,1号选手
线程:2处理数据,2号选手
线程:4处理数据,3号选手
进入休眠
进入休眠
进入休眠
线程:3处理数据,4号选手
线程:4处理数据,5号选手
线程:2处理数据,6号选手
线程:2处理数据,7号选手
线程:4处理数据,8号选手
进入休眠
进入休眠
进入休眠 */
```

condition_variable_any

```
#include <iostream>
#include <mutex>
#include <queue>
#include <thread>
#include <chrono>
#include <condition_variable>
using namespace std;
// condition_variable_any
class AA
{
    timed_mutex m_mutex;           //互斥锁
    condition_variable_any m_cond; //条件变量
    queue<string> m_q;             //缓存队列
public:
    void incache(int num) //生产数据,num指定数据的个数
    {
        lock_guard<timed_mutex> lock(m_mutex);
        for (int i = 0; i < num; i++)
        {
            static int id = 1;
            string msg = to_string(id++) + "号选手";
            m_q.push(msg); //把生产出来的数据入队
        }
        m_cond.notify_all(); //唤醒所有被当前条件变量阻塞的线程
    }

    void outcache() //消费者线程任务函数
    {
        string msg;
        while (true)
        {
            {
                unique_lock<timed_mutex> lock(m_mutex);
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
};
void test()
{
    AA aa;
    //给线程传递类的普通成员函数
    thread t1(&AA::outcache, &aa); //创建消费者线程t1
    thread t2(&AA::outcache, &aa); //创建消费者线程t2
    thread t3(&AA::outcache, &aa); //创建消费者线程t3

    this_thread::sleep_for(chrono::seconds(2));
    aa.incache(3); //生成的数量比消费者线程数3少

    t1.join();
    t2.join();
    t3.join();
}
int main()
{
    test();
    return 0;
}

```

##### unique_lock类

template <class Mutex> class unique_lock是模板类，模板参数为互斥锁类型。

unique_lock和lock_guard都是管理锁的辅助类，都是RAII风格（在构造时获得锁，在析构时释放锁）。它们的区别在于：为了配合condition_variable，unique_lock还有lock()和unlock()成员函数。