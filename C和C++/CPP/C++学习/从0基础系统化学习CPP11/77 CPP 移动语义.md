77 CPP 移动语义

```
#include <iostream>
#include <cstring>
using namespace std;
// 移动语义
class AA
{
public:
    int *m_data = nullptr;
    AA() = default;
    void alloc()
    {
        m_data = new int;
        memset(m_data, 0, sizeof(int));
    }

    AA(const AA &a)
    {
        cout << "调用了拷贝构造函数" << endl;
        if (this == &a)
        {
            return;
        }
        if (m_data == nullptr)
        {
            alloc();
        }
        memcpy(m_data, a.m_data, sizeof(int)); //把数据从源对象中拷贝过来
    }
    AA &operator=(const AA &a)
    {
        cout << "调用了赋值函数" << endl;
        if (this == &a)
        {
            return *this;
        }
        if (m_data == nullptr)
        {
            alloc();
        }
        memcpy(m_data, a.m_data, sizeof(int));
        return *this;
    }
    ~AA()
    {
        cout << "调用了析构函数" << endl;
        if (m_data != nullptr)
        {
            delete m_data;
            m_data = nullptr;
        }
    }
};
void test()
{
    AA a1;
    a1.alloc();
    *a1.m_data = 3;
    cout << "a1.m_data=" << *a1.m_data << endl;

    AA a2 = a1;
    cout << "a2.m_data=" << *a2.m_data << endl;

    AA a3;
    a3 = a1;
    cout << "a3.m_data=" << *a3.m_data << endl;
}
int main()
{
    test();
    return 0;
}
/* a1.m_data=3
调用了拷贝构造函数
a2.m_data=3
调用了赋值函数
a3.m_data=3
调用了析构函数
调用了析构函数
调用了析构函数 */
```

上面代码，看看拷贝构造和赋值函数代码，每次调用这两个函数的时候，不一定要重新分配资源，但是一定会拷贝数据，如果数据量比较大，拷贝数据也要消耗时间，在C++11中，把拷贝数据的操作叫拷贝语义，而移动语义的意思是：不要拷贝，直接把资源转移过来，资源都是用指针指向的，把指针处理一下就可以转移资源，不拷贝数据。所以转移资源的操作叫做移动语义。 

要实现移动语义，类必须提供移动构造函数和移动赋值函数。

```
#include <iostream>
#include <cstring>
using namespace std;
// 移动语义
class AA
{
public:
    int *m_data = nullptr;
    AA() = default;
    void alloc()
    {
        m_data = new int;
        memset(m_data, 0, sizeof(int));
    }

    AA(const AA &a)
    {
        cout << "调用了拷贝构造函数" << endl;
        if (this == &a)
        {
            return;
        }
        if (m_data == nullptr)
        {
            alloc();
        }
        memcpy(m_data, a.m_data, sizeof(int)); //把数据从源对象中拷贝过来
    }
    //移动构造函数
    AA(AA &&a) //因为需要操作被转移对象中的指针,所以不能用const
    {
        cout << "调用了移动构造函数" << endl;
        if (m_data != nullptr)
        {
            delete m_data; //如果已经分配内存,先释放掉
        }
        m_data = a.m_data;  //把资源从源对象中转移过来
        a.m_data = nullptr; //把源对象中的指针置空
    }
    AA &operator=(const AA &a)
    {
        cout << "调用了赋值函数" << endl;
        if (this == &a)
        {
            return *this;
        }
        if (m_data == nullptr)
        {
            alloc();
        }
        memcpy(m_data, a.m_data, sizeof(int));
        return *this;
    }

    //移动赋值函数
    AA &operator=(AA &&a)
    {
        cout << "调用了移动赋值函数" << endl;
        if (this == &a)
        {
            return *this;
        }
        if (m_data != nullptr)
        {
            delete m_data;
        }
        m_data = a.m_data;
        a.m_data = nullptr;
        return *this;
    }
    ~AA()
    {
        cout << "调用了析构函数" << endl;
        if (m_data != nullptr)
        {
            delete m_data;
            m_data = nullptr;
        }
    }
};
AA func()
{
    AA a;
    a.alloc();
    *a.m_data = 19;
    return a;
}
void test()
{
    /* AA a1;
    a1.alloc();
    *a1.m_data = 3;
    cout << "a1.m_data=" << *a1.m_data << endl;

    AA a2 = a1;
    cout << "a2.m_data=" << *a2.m_data << endl;

    AA a3;
    a3 = a1;
    cout << "a3.m_data=" << *a3.m_data << endl;

    cout << "------------------------------" << endl; */

    auto getTemp = []
    {
        AA a;
        a.alloc();
        *a.m_data = 10;
        return a;
    };
    AA a4 = getTemp(); // VS里面会显示调用移动构造函数,G++是不是做了优化,没有调用移动构造函数
    *a4.m_data = 1;
    cout << "a4.m_data=" << *a4.m_data << endl;

    AA a6 = func(); // VS里面会显示调用移动构造函数,G++是不是做了优化,没有调用移动构造函数
    cout << "a6.m_data=" << *a6.m_data << endl;

    AA a5;
    a5 = getTemp();
    cout << "a5.m_data=" << *a5.m_data << endl;
}
int main()
{
    test();
    return 0;
}
a4.m_data=1
a6.m_data=19
调用了移动赋值函数
调用了析构函数
a5.m_data=10
调用了析构函数
调用了析构函数
调用了析构函数
```

VS里面会显示调用移动构造函数,G++是不是做了优化,没有调用移动构造函数

如果一个对象中有堆区资源，需要编写拷贝构造函数和赋值函数，实现深拷贝。

深拷贝把对象中的堆区资源复制了一份，如果源对象（被拷贝的对象）是临时对象，拷贝完就没什么用了，这样会造成没有意义的资源申请和释放操作。如果能够直接使用源对象拥有的资源，可以节省资源申请和释放的时间。C++11新增加的移动语义就能够做到这一点。

实现移动语义要增加两个函数：移动构造函数和移动赋值函数。

移动构造函数的语法：

类名(类名&& 源对象){......}

移动赋值函数的语法：

类名& operator=(类名&& 源对象){……}

注意：

1）对于一个左值，会调用拷贝构造函数，但是有些左值是局部变量，生命周期也很短，能不能也移动而不是拷贝呢？C++11为了解决这个问题，提供了std::move()方法来将左值转义为右值，从而方便使用移动语义。它其实就是告诉编译器，虽然我是一个左值，但不要对我用拷贝构造函数，用移动构造函数吧。左值对象被转移资源后，不会立刻析构，只有在离开自己的作用域的时候才会析构，如果继续使用左值中的资源，可能会发生意想不到的错误。

2）如果没有提供移动构造/赋值函数，只提供了拷贝构造/赋值函数，编译器找不到移动构造/赋值函数就去寻找拷贝构造/赋值函数。

3）C++11中的所有容器都实现了移动语义，避免对含有资源的对象发生无谓的拷贝。

4）移动语义对于拥有资源（如内存、文件句柄、网络连接）的对象有效，如果是基本类型，使用移动语义没有意义。

```
#include <iostream>
#include <cstring>
using namespace std;
// 移动语义
class AA
{
public:
    int *m_data = nullptr;
    AA() = default;
    void alloc()
    {
        m_data = new int;
        memset(m_data, 0, sizeof(int));
    }

    AA(const AA &a)
    {
        cout << "调用了拷贝构造函数" << endl;
        if (this == &a)
        {
            return;
        }
        if (m_data == nullptr)
        {
            alloc();
        }
        memcpy(m_data, a.m_data, sizeof(int)); //把数据从源对象中拷贝过来
    }
    //移动构造函数
    AA(AA &&a) //因为需要操作被转移对象中的指针,所以不能用const
    {
        cout << "调用了移动构造函数" << endl;
        if (m_data != nullptr)
        {
            delete m_data; //如果已经分配内存,先释放掉
        }
        m_data = a.m_data;  //把资源从源对象中转移过来
        a.m_data = nullptr; //把源对象中的指针置空
    }
    AA &operator=(const AA &a)
    {
        cout << "调用了赋值函数" << endl;
        if (this == &a)
        {
            return *this;
        }
        if (m_data == nullptr)
        {
            alloc();
        }
        memcpy(m_data, a.m_data, sizeof(int));
        return *this;
    }

    //移动赋值函数
    AA &operator=(AA &&a)
    {
        cout << "调用了移动赋值函数" << endl;
        if (this == &a)
        {
            return *this;
        }
        if (m_data != nullptr)
        {
            delete m_data;
        }
        m_data = a.m_data;
        a.m_data = nullptr;
        return *this;
    }
    ~AA()
    {
        cout << "调用了析构函数" << endl;
        if (m_data != nullptr)
        {
            delete m_data;
            m_data = nullptr;
        }
    }
};
AA func()
{
    AA a;
    a.alloc();
    *a.m_data = 19;
    return a;
}
void test()
{
    AA a1;
    a1.alloc();
    *a1.m_data = 3;
    cout << "a1.m_data=" << *a1.m_data << endl;

    AA a2 = a1;
    cout << "a2.m_data=" << *a2.m_data << endl;

    AA a3;
    a3 = move(a1); //调用了移动赋值函数 但是注意不能move多次,且move后,后面使用就要注意空指针了
    // a3 = a1;       //调用了赋值函数
    cout << "a3.m_data=" << *a3.m_data << endl;
}
int main()
{
    test();
    return 0;
}
/* a1.m_data=3
调用了拷贝构造函数
a2.m_data=3
调用了移动赋值函数
a3.m_data=3
调用了析构函数
调用了析构函数
调用了析构函数 */
```