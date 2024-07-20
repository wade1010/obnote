```
#include <iostream>
#include <algorithm>
#include <vector>
using namespace std;

template <typename T>
bool show(const T &no, const T &t_no)
{
    if (no != t_no)
    {
        return false;
    }

    cout << no << endl;
    return true;
}

template <class T>
class CTmp
{
public:
    bool operator()(const T &no, const T &t_no)
    {
        if (no != t_no)
        {
            return false;
        }
        cout << "call operator:" << no << endl;
        return true;
    }
};
template <typename T1, typename T2, typename T3>
T1 _find_if(const T1 first, const T1 end, T2 pfun, T3 target)
{
    for (auto it = first; it != end; it++)
    {
        if (pfun(*it, target))
        {
            return it;
        }
    }
    return end;
}

void test()
{
    vector<int> bh{2, 3, 4, 5, 6};
    // vector<string> bh{"A2", "A3", "A4", "A5", "A6"};
    _find_if(bh.begin(), bh.end(), CTmp<int>(), 3);
    _find_if(bh.begin(), bh.end(), show<int>, 3);
}
int main()
{
    test();
    return 0;
}

```

上面的代码，编程存在一种问题，假设这是一个大项目，我们负责框架部分，findif函数是我们写的，其他人负责具体的业务，框架要满足业务需求，另外框架不能随便修改，findif相当于框架中的一个函数，上面代码就是为了实现一个功能，而去调整了框架的代码，如果该功能继续修改，需要更多参数，那是不是又要调整框架代码呢？这样肯定不好，违背了框架的原则。

上面代码其实就是演示了给回调函数传递参数的方法。

        if (pfun(*it, target))

        {

            return it;

        }

上面代码用迭代器作为参数调用函数对象，函数对象可能是普通函数，也可以是仿函数，仿函数本质就是类，调用仿函数就是调用类的成员函数。既然是调用类的成员函数，那就好办了，可以这么做。

```
#include <iostream>
#include <algorithm>
#include <vector>
using namespace std;

template <typename T>
bool show(const T &no)
{
    if (no != 3)
    {
        return false;
    }

    cout << no << endl;
    return true;
}

template <class T>
class CTmp
{
public:
    T m_no;
    CTmp(const T &no) : m_no(no) {}
    bool operator()(const T &no)
    {
        if (no != m_no)
        {
            return false;
        }
        cout << "call operator:" << no << endl;
        return true;
    }
};
template <typename T1, typename T2>
T1 _find_if(const T1 first, const T1 end, T2 pfun)
{
    for (auto it = first; it != end; it++)
    {
        if (pfun(*it))
        {
            return it;
        }
    }
    return end;
}

void test()
{
    vector<int> bh{2, 3, 4, 5, 6};
    // vector<string> bh{"A2", "A3", "A4", "A5", "A6"};
    _find_if(bh.begin(), bh.end(), CTmp<int>(3));
    _find_if(bh.begin(), bh.end(), show<int>);
}
int main()
{
    test();
    return 0;
}

```

上面这种方法也达到了传递参数的目的，并且没有修改findif()函数的源代码，这就是仿函数的意义，和普通函数相比，它的外观像函数，可以实现函数的功能，可以与普通函数公用模板，但是它还是类，可以用成员变量携带更多的信息，普通函数不具备这个能力。