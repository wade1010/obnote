![](https://gitee.com/hxc8/images2/raw/master/img/202407172220859.jpg)

```
#include "iostream"
using namespace std;
//模板类的实例-栈 简单实现
template <class T>
class Stack
{
private:
    T *m_items; //栈数组
    int m_size;
    int m_top;

public:
    Stack(int size) : m_size(size), m_top(0)
    {
        m_items = new T[m_size];
    }
    ~Stack()
    {
        delete[] m_items;
        m_items = nullptr;
    }
    bool isempty() const
    {
        return m_top == 0;
    }
    bool isfull() const
    {
        return m_top == m_size;
    }
    bool push(const T &item)
    {
        if (isfull())
        {
            return false;
        }
        m_items[m_top++] = item;
        return true;
    }
    bool pop(T &item)
    {
        if (isempty())
        {
            return false;
        }
        item = m_items[--m_top];
        return true;
    }
};
void test()
{
    Stack<string> s(4);
    //入栈
    s.push("a");
    s.push("b");
    s.push("c");
    s.push("d");
    s.push("e"); //这个入栈失败的
    //出栈
    string item;
    while (!s.isempty())
    {
        s.pop(item);
        cout << "item=" << item << endl;
    }
}
int main()
{
    test();
    return 0;
}
item=d
item=c
item=b
item=a
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220220.jpg)

```
#include "iostream"
#include <cstring>
using namespace std;
// #define MAXLEN 10
//模板类的实例-数组 简单实现
// template <class T>
// template <class T, int len=10> //也可以用缺省值
template <class T, int len>
class Array
{
private:
    // T items[MAXLEN];
    T items[len];

public:
    Array()
    {
        memset(items, 0, sizeof(items));
    }
    ~Array() {}
    //重载操作符[],可以修改数组中的元素
    T &operator[](int index)
    {
        return items[index];
    }
    //重载操作符[],不可以修改数组中的元素
    const T &operator[](int index) const
    {
        return items[index];
    }
};
void test()
{
    /*     Array<int> a;
        a[0] = 1;
        a[1] = 2;
        a[2] = 3;
        a[3] = 4;
        a[4] = 5;
        for (int i = 0; i < 5; i++)
        {
            cout << a[i] << endl;
        } */

    /*  Array<string> a;
     a[0] = "啊";
     a[1] = "安大叔大婶抚";
     a[2] = "把";
     a[3] = "报错";
     a[4] = "二";
     for (int i = 0; i < 5; i++)
     {
         cout <<a[i] << endl;
     } */

    //不用宏,使用非通用类型的参数
    //如果用缺省值 template <class T, int len=10>
    // Array<string> a;
    Array<string, 3> a;
    a[0] = "啊";
    a[1] = "安大叔大婶抚";
    a[2] = "把";
    for (int i = 0; i < 5; i++)
    {
        cout << a[i] << endl;
    }
}
int main()
{
    test();
    return 0;
}
```

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220372.jpg)

```
#include "iostream"
using namespace std;
//模板类的实例-数组 简单实现  变长数组
template <class T>
class Vector
{
private:
    int len;
    T *items;

public:
    Vector(int size = 10) : len(size)
    {
        items = new T[len];
    }
    ~Vector()
    {
        delete[] items;
    }
    void resize(int size)
    {
        if (size <= len)
        {
            return; //这里就规定只能往大的扩
        }
        T *tmp = new T[size];         //分配更大的内存空间
        for (int i = 0; i < len; i++) //把原来数组中的元素复制到新数组
        {
            tmp[i] = items[i];
        }
        delete[] items; //释放原来的数组
        items = tmp;    //让数组指针指向新数组
        len = size;
    }
    int size()
    {
        return len;
    }
    T &operator[](int index)
    {
        if (index >= len)
        {
            resize(index + 1); //这里会频繁扩展,暂不管,可以每次扩的多点.改成10或者20
        }

        return items[index];
    }
    const T &operator[](int index) const
    {
        return items[index];
    }
};
void test()
{
    Vector<string> v(1);
    v[0] = "啊";
    v[1] = "飞啊";
    v[2] = "啊改变";
    for (int i = 0; i < 3; i++)
    {
        cout << v[i] << endl;
    }

    Vector<int> v2(1);
    v2[0] = 1;
    v2[1] = 2;
    v2[2] = 3;
    for (int i = 0; i < 3; i++)
    {
        cout << v2[i] << endl;
    }
}
int main()
{
    test();
    return 0;
}
啊
飞啊
啊改变
1
2
3
```

下面代码有问题

    //不打开注释,程序能正常运行,打开注释,程序报错


    // vs[2].push("吧1");


    // vs[2].push("吧2");

原因：

//错误原因,如果这里复制的是内置数据类型,亏,但是如果复制的是类,且类中使用了堆区内存,就存在浅拷贝的问题

```
#include "iostream"
using namespace std;

template <class T>
class Stack
{
private:
    T *items;
    int stacksize;
    int top;

public:
    Stack(int size = 3) : stacksize(size), top(0)
    {
        items = new T[stacksize];
    }
    ~Stack()
    {
        delete[] items;
        items = nullptr;
    }
    bool isempty() const { return top == 0; }
    bool isfull() const { return top == stacksize; }
    bool push(const T &item)
    {
        if (top < stacksize)
        {
            items[top++] = item;
            return true;
        }
        return false;
    }
    bool pop(T &item)
    {
        if (top > 0)
        {
            item = items[--top];
            return true;
        }
        return false;
    }
};

template <class T, int len = 10>
class Array
{
private:
    T item[len];

public:
    Array() {}
    ~Array() {}
    T &operator[](int index)
    {
        return item[index];
    }
    const T &operator[](int index) const
    {
        return item[index];
    }
};
template <class T>
class Vector
{
private:
    int len;
    T *items;

public:
    Vector(int size = 2) : len(size)
    {
        items = new T[len];
    }
    ~Vector()
    {
        delete[] items;
        items = nullptr;
    }
    void resize(int size)
    {
        if (size <= len)
        {
            return;
        }
        T *tmp = new T[size];
        for (int i = 0; i < len; i++)
        {
            //错误原因,如果这里复制的是内置数据类型,亏,但是如果复制的是类,且类中使用了堆区内存,就存在浅拷贝的问题
            tmp[i] = items[i];
        }
        delete[] items;
        items = tmp;
    }
    int size() const
    {
        return len;
    }
    T &operator[](int index)
    {
        if (index >= len)
        {
            resize(index + 1);
        }
        return items[index];
    }
    const &operator[](int index) const
    {
        return items[index];
    }
};

void test()
{
    Vector<Stack<string>> vs;
    Stack<string> vs1[2];
    string vs2[2][3];

    vs[0].push("啊1");
    vs[0].push("啊2");
    vs[0].push("啊3");

    vs[1].push("哦1");
    vs[1].push("哦2");
    vs[1].push("哦3");

    //不打开注释,程序能正常运行,打开注释,程序报错
    // vs[2].push("吧1");
    // vs[2].push("吧2");

    for (int i = 0; i < vs.size(); i++)
    {
        while (!vs[i].isempty())
        {
            string item;
            vs[i].pop(item);
            cout << "item=" << item << endl;
        }
    }
}
int main()
{
    test();
    return 0;
}
```