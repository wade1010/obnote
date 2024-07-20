下面是生产者/消费者模型代码 

```
#include <iostream>
#include <mutex>
#include <queue>
#include <thread>
#include <chrono>
#include <condition_variable>
using namespace std;
//
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
            //加一个作用域,出作用域  unique_lock自动释放锁. 不加作用域也行,可以手动释放锁
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

```

其中

unique_lock<mutex> lock(m_mutex);

while (m_q.empty())

 {

       cout << "进入休眠" << endl;

m_cond.wait(lock);

}                        

这几行代码很奇怪，逻辑上好像走不通，

消费者进入循环后，第一步是申请说，只有加锁成功的情况下，才有机会折射在条件变量的wait()函数中，如果多个消费者线程同时运行，那么只有一个线程申请锁成功，其它线程都会阻塞在申请互斥锁这里。上面的代码也就是说只有一个线程阻塞在wait函数中，其它线程都会阻塞在申请加锁那里。

来做一个测试，看看到底是不是上面分析的

```
#include <iostream>
#include <mutex>
#include <queue>
#include <thread>
#include <chrono>
#include <condition_variable>
using namespace std;
//
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
            //加一个作用域,出作用域  unique_lock自动释放锁. 不加作用域也行,可以手动释放锁
            {
                //把互斥锁转换成unique_lock<mutex>,并申请加锁
                cout << "线程:" << this_thread::get_id() << ",申请加锁..." << endl;
                unique_lock<mutex> lock(m_mutex);
                cout << "线程:" << this_thread::get_id() << ",加锁成功" << endl;
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

    t1.join();
    t2.join();
    t3.join();
}
int main()
{
    test();
    return 0;
}
线程:4,申请加锁...
线程:4,加锁成功
进入休眠
线程:3,申请加锁...
线程:3,加锁成功
进入休眠
线程:2,申请加锁...
线程:2,加锁成功
进入休眠
```

可以看出 3个消费者都加锁成功了，所以上面的分析有问题。

再做个测试，在内层循环前休眠比较长的时间

```
#include <iostream>
#include <mutex>
#include <queue>
#include <thread>
#include <chrono>
#include <condition_variable>
using namespace std;
//
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
            //加一个作用域,出作用域  unique_lock自动释放锁. 不加作用域也行,可以手动释放锁
            {
                //把互斥锁转换成unique_lock<mutex>,并申请加锁
                cout << "线程:" << this_thread::get_id() << ",申请加锁..." << endl;
                unique_lock<mutex> lock(m_mutex);
                cout << "线程:" << this_thread::get_id() << ",加锁成功" << endl;
                this_thread::sleep_for(chrono::hours(1));
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

    t1.join();
    t2.join();
    t3.join();
}
int main()
{
    test();
    return 0;
}

/*线程:2,申请加锁...
线程:2,加锁成功
线程:4,申请加锁...
线程:3,申请加锁... */
```

从输出结果来看，只有一个线程加锁成功。另外两个线程被阻塞在申请加锁那里。

通过测试的结果可以看出，条件变量的wait()函数不只是等待生产者信号那么简单，还干了其它一些事情。

它干了3件事情

1 把互斥锁解锁

2 阻塞等待被唤醒

3 给互斥锁加锁

现在可以知道，因为条件变量的wait()函数把互斥锁解开了，所以这3个线程先后都能加锁成功，开始那几行难以理解的代码就解释的通了。

启动后，如果生产者没生产数据，3个消费者线程都被阻塞在条件变量的wait()函数中，这时候互斥锁，没有被人和线程持有，如果生产者要往队列中放数据，可以加锁成功，生产者往队列中放完数据之后，会发出条件信号，那么这3个线程的wait()函数会接受到条件信号，wait()函数接受到信号后，不一定立即返回，它还需要申请加锁，加锁成功了才会返回。如果wait()函数返回了，那么久一定申请到了锁。

申请到锁之后，就可以让队列中的数据出队，出队后再解锁。

普通的互斥锁为什么要转换成unique_lock之后才能用于条件变量呢？

template <class Mutex> class unique_lock是模板类，模板参数为互斥锁类型。

unique_lock和lock_guard都是管理锁的辅助类，都是RAII风格（在构造时获得锁，在析构时释放锁）。

它们的区别在于：为了配合condition_variable，unique_lock还有lock()和unlock()成员函数。

另外还有一点需要注意，条件变量存在虚假唤醒的情况，消费者线程被唤醒，缓存队列中没有数据。为什么存在虚假唤醒的情况呢？

生产的数据比消费线程数少。代码如下

```
#include <iostream>
#include <mutex>
#include <queue>
#include <thread>
#include <chrono>
#include <condition_variable>
using namespace std;
//
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
        m_cond.notify_all(); //唤醒所有被当前条件变量阻塞的线程
    }

    void outcache() //消费者线程任务函数
    {
        string msg;
        while (true)
        {
            {
                unique_lock<mutex> lock(m_mutex);
                while (m_q.empty())
                {
                    m_cond.wait(lock);
                    cout << "线程:" << this_thread::get_id() << "被唤醒了" << endl;
                }

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
    aa.incache(2); //生成的数量比消费者线程数3少

    t1.join();
    t2.join();
    t3.join();
}
int main()
{
    test();
    return 0;
}
/* 线程:2被唤醒了
线程:2处理数据,1号选手
线程:4被唤醒了
线程:4处理数据,2号选手
线程:3被唤醒了 */
```

如果不用while(m_q.empty())也可以用wait的另外一个重载版本

```
    template<typename _Predicate>
      void
      wait(unique_lock<mutex>& __lock, _Predicate __p)
      {
  while (!__p())
    wait(__lock);
      }
```

```
#include <iostream>
#include <mutex>
#include <queue>
#include <thread>
#include <chrono>
#include <condition_variable>
using namespace std;
//
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
        m_cond.notify_all(); //唤醒所有被当前条件变量阻塞的线程
    }

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