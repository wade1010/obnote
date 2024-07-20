C++11提供了chrono模版库，实现了一系列时间相关的操作（时间长度、系统时间和计时器）。

头文件：#include <chrono>

命名空间：std::chrono

### 一、时间长度

duration模板类用于表示一段时间（时间长度、时钟周期），如：1小时、8分钟、5秒。

duration的定义如下:

template<class Rep, class Period = std::ratio<1, 1>>

class duration

{

……

};

为了方便使用，定义了一些常用的时间长度，比如：时、分、秒、毫秒、微秒、纳秒，它们都位于std::chrono命名空间下，定义如下：

using hours			= duration<Rep, std::ratio<3600>>	// 小时

using minutes			= duration<Rep, std::ratio<60>>		// 分钟

using seconds			= duration<Rep>						// 秒

using milliseconds		= duration<Rep, std::milli>			// 毫秒

using microseconds  	= duration<Rep, std::micro>  		// 微秒

using nanoseconds 	= duration<Rep, std::nano>  			// 纳秒

注意：

duration模板类重载了各种算术运算符，用于操作duration对象。

duration模板类提供了count()方法，获取duration对象的值。

```
#include <iostream>
#include <chrono>
using namespace std;

void test()
{
    chrono::hours c1(1);                     // 1个小时
    chrono::minutes c2(60);                  // 60分钟
    chrono::seconds c3(3600);                // 3600秒
    chrono::milliseconds c4(60 * 60 * 1000); //毫秒
    // chrono::microseconds c5(60 * 60 * 1000 * 1000);//警告:整数溢出
    if (c1 == c2)
    {
        cout << "c1==c2" << endl;
    }
    if (c1 == c3)
    {
        cout << "c1==c3" << endl;
    }
    if (c1 == c4)
    {
        cout << "c1==c4" << endl;
    }

    chrono::microseconds c6(12);
    //获取时钟周期的值
    cout << "c6=" << c6.count() << endl;

    cout << "ok" << endl;
}
int main()
{
    test();
    return 0;
}
/* c1==c2
c1==c3
c1==c4
c6=12
ok */
```

### 二、系统时间

system_clock类支持了对系统时钟的访问，提供了三个静态成员函数：

// 返回当前时间的时间点。

static std::chrono::time_point[std::chrono::system_clock](std::chrono::system_clock) now() noexcept;

// 将时间点time_point类型转换为std::time_t 类型。

static std::time_t to_time_t( const time_point& t ) noexcept;

// 将std::time_t类型转换为时间点time_point类型。

static std::chrono::system_clock::time_point from_time_t( std::time_t t ) noexcept;

```
#include <iostream>
#include <chrono>
#include <iomanip> //put_time()函数需要包含的头文件
#include <sstream>
using namespace std;

void test()
{
    // 1 静态成员函数chrono::system_clock::now()用于获取系统时间 (C++时间)
    // chrono::time_point<chrono::system_clock> now = chrono::system_clock::now();
    auto now = chrono::system_clock::now();

    // 2 静态成员函数chrono::system_clock::to_time_t()把系统时间转换为time_t.(UTC时间)
    // time_t t_now = chrono::system_clock::to_time_t(now);
    auto t_now = chrono::system_clock::to_time_t(now);

    // 3 std::localtime()函数把time_t转换成本地时间 (北京时间)
    // localtime()不是线程安全的,VS用localtime_s代替,Linux用localtime_r代替//vscode g++ 里面没有这两
    // tm *tm_now = std::localtime(&t_now);
    auto *tm_now = std::localtime(&t_now);

    // 4 格式化输出tm结构体中的成员
    cout << put_time(tm_now, "%Y-%m-%d %H:%M:%S") << endl;
    cout << put_time(tm_now, "%Y-%m-%d") << endl;
    cout << put_time(tm_now, "%H:%M:%S") << endl;
    cout << put_time(tm_now, "%Y%m%d%H%M%S") << endl;

    stringstream ss; //创建stringstream对象ss,需要包含头文件 <sstream>
    ss << put_time(tm_now, "%Y-%m-%d %H:%M:%S");
    string time_str = ss.str();
    cout << time_str << endl;

    //时间偏移
    t_now += 24 * 3600;

    auto *tm_now2 = std::localtime(&t_now);
    cout << "偏移后:" << put_time(tm_now2, "%Y-%m-%d %H:%M:%S") << endl;
}
int main()
{
    test();
    return 0;
}
/* 2022-12-01 19:36:19
2022-12-01
19:36:19
20221201193619
2022-12-01 19:36:19
偏移后:2022-12-02 19:36:19 */
```

### 三、计时器

steady_clock类相当于秒表，操作系统只要启动就会进行时间的累加，常用于耗时的统计（精确到纳秒）。

```
#include <iostream>
#include <chrono>
using namespace std;

void test()
{
    //静态成员函数chrono::steady_clock::now()获取开始的时间点
    // chrono::steady_clock::time_point start = chrono::steady_clock::now();
    auto start = chrono::steady_clock::now();

    // 执行一些代码,消耗一些时间
    cout << "开始计时" << endl;
    for (size_t i = 0; i < 1000000; i++)
    {
    }
    cout << "计时完成" << endl;

    auto diff = (chrono::steady_clock::now() - start).count();
    //单位是纳秒 自行换算
    cout << "耗时:" << diff << "纳秒(" << diff / 1000000 << "毫秒)" << endl;
}
int main()
{
    test();
    return 0;
}
/* 开始计时
计时完成
耗时:3419700纳秒(3毫秒) */
```

时间转换案例

```
#include <iostream>
#include <iomanip>
#include <cstring>
#include <chrono>
#include <sys/time.h>
using namespace std;
//获取时间戳
void test()
{
    // chrono::time_point<chrono::system_clock, chrono::seconds> tpMicro = chrono::time_point_cast<chrono::milliseconds>(chrono::system_clock::now());
    // use auto
    //秒级别
    auto tpSec = chrono::time_point_cast<chrono::seconds>(chrono::system_clock::now());
    time_t timestamp1 = tpSec.time_since_epoch().count();
    cout << timestamp1 << endl;
    //毫秒级别
    auto tpMicro = chrono::time_point_cast<chrono::milliseconds>(chrono::system_clock::now());
    time_t timestamp2 = tpMicro.time_since_epoch().count();
    cout << timestamp2 << endl;
}
int main()
{
    test();
    return 0;
}

```

```
#include <iostream>
#include <iomanip>
#include <cstring>
using namespace std;
//时间转换
time_t strTime2Unix(string &timestr)
{
    tm tm;
    memset(&tm, 0, sizeof(tm));
    sscanf(timestr.c_str(), "%d-%d-%d %d:%d:%d",
           &tm.tm_year, &tm.tm_mon, &tm.tm_mday, &tm.tm_hour, &tm.tm_min, &tm.tm_sec);
    tm.tm_year -= 1900;
    tm.tm_mon--;
    return mktime(&tm);
}
void test()
{
    string str = "2022-12-2 11:58:29";
    time_t tt = strTime2Unix(str);
    cout << tt << endl;
    cout << ctime(&tt) << endl;
}
int main()
{
    test();
    return 0;
}
/* 1669953509
Fri Dec 02 11:58:29 2022 */
```

```
#include <iostream>
#include <chrono>
#include <thread>
#include <iomanip>
using namespace std;

void test()
{
    using chrono::system_clock;
    auto tt = system_clock::to_time_t(system_clock::now());
    tm *plt = localtime(&tt);
    cout << "current time:" << put_time(plt, "%Y-%m-%d %H:%M:%S") << endl;
    plt->tm_min++;
    cout << "sleep until  " << put_time(plt, "%Y-%m-%d %H:%M:%S") << endl;
    this_thread::sleep_until(system_clock::from_time_t(mktime(plt)));
    cout << "ok,current time:" << put_time(plt, "%Y-%m-%d %H:%M:%S") << endl;
}
int main()
{
    test();
    return 0;
}
/* current time:2022-12-02 11:34:57
sleep until  2022-12-02 11:35:57
ok,current time:2022-12-02 11:35:57 */
```