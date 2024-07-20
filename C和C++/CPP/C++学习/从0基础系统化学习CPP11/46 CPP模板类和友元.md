如果要访问类的私有成员，调用类的公有成员函数是唯一的办法，而类的私有成员函数则无法访问。

友元提供了另一访问类的私有成员的方案。友元有3中：

1 友元全局函数

2 友元类

3 友元成员函数。

模板类的友元函数有三类：

1 非模板友元：友元函数不是模板函数，而是利用模板类参数生成的函数。

2 约束模板友元：模板类实例化时，每个实例化的类对应一个友元函数。

3 非约束模板友元：模板类实例化时，如果实例化了n个类，也会实例化n个友元函数，每个实例化的类都拥有n个友元函数。(在实际开发中一般不用，因为它不科学。模板类实例化的时候，如果有n种的数据类型，就会实例化n个类出来，也会实例化n个友元函数，每个实例化的类拥有n个友元函数，这就不科学了，每个实例化的类只需要一个友元函数就行了，不需要n个，其它数据类型的友元函数和自己没有关系。)

```
#include "iostream"
using namespace std;

//冗余的模板友元
template <class T1, class T2>
class AA
{
    T1 m_x;
    T2 m_y;

public:
    AA(const T1 x, const T2 y) : m_x(x), m_y(y)
    {
    }
    //下面这种方案的本质,是编译器利用模板参数帮我们生成了友元函数,使用起来很方便,但是有个细节
    //编译器利用模板参数生成的友元函数,但是这个函数不是模板函数
    //编译器创建模板类实例的时候,会用这些代码生成友元函数的实体,如果把下面注释的代码打开,就会出现冲突,导致报错
    friend void show(const AA<T1, T2> &a)
    {
        cout << a.m_x << endl;
        cout << a.m_y << endl;
    }
    // friend void show(const AA<int, string> &a);
    // friend void show(const AA<char, string> &a);
};

// void show(const AA<int, string> &a)
// {
//     cout << a.m_x << endl;
//     cout << a.m_y << endl;
// }

// void show(const AA<char, string> &a)
// {
//     cout << a.m_x << endl;
//     cout << a.m_y << endl;
// }

void test()
{
    AA<int, string> aa(1, "hello");
    show(aa);

    AA<char, string> bb('a', "hello");
    show(bb);
}
int main()
{
    test();
    return 0;
}
```

上面代码，使用很方便，但是也有局限，如果我们想为某种数据类型创建特别版本的友元函数（具体化），这种方法是做不到的。还有就是这种方法生成的友元函数只能用于这个模板类，不能用于其它的模板类。

```
#include "iostream"
using namespace std;
//约束模板友元:模板实例化时,每个实例化的类对应一个友元函数
//这种友元代码比较多,不用记住,用到的时候查查就行,但是它是最好的友元方案
template <typename T>
void show(T &a); //第一步:在模板类的定义前面,声明友元函数模板

template <class T1, class T2>
class AA
{
    friend void show<>(AA<T1, T2> &a); //第二步:在模板类中,再次声明友元函数模板
    T1 m_x;
    T2 m_y;

public:
    AA(const T1 x, const T2 y) : m_x(x), m_y(y) {}
};
template <typename T>
void show(T &a)
{
    cout << "通用:x=" << a.m_x << ",y=" << a.m_y << endl;
}
template <> //第三步:具体化版本
void show(AA<int, string> &a)
{
    cout << "具体AA<int,string>:x=" << a.m_x << ",y=" << a.m_y << endl;
}

//这种友元的函数模板可以用于多个模板类
template <class T1, class T2>
class BB
{
    friend void show<>(BB<T1, T2> &a); //第二步:在模板类中,再次声明友元函数模板
    T1 m_x;
    T2 m_y;

public:
    BB(const T1 x, const T2 y) : m_x(x), m_y(y) {}
};
template <> //第三步:具体化版本
void show(BB<int, string> &a)
{
    cout << "具体BB<int,string>:x=" << a.m_x << ",y=" << a.m_y << endl;
}

void test()
{
    AA<int, string> a(2, "hello");
    show(a);

    AA<char, string> b('a', "world");
    show(b);

    BB<int, string> ba(2, "hello");
    show(ba);

    BB<char, string> bb('a', "world");
    show(b);
}
int main()
{
    test();
    return 0;
}
/* 具体AA<int,string>:x=2,y=hello
通用:x=a,y=world
具体BB<int,string>:x=2,y=hello
通用:x=a,y=world */
```

```
#include "iostream"
using namespace std;
// 非约束模板友元
template <class T1, class T2>
class AA
{
    template <typename T> friend void show(T &a);
    T1 m_x;
    T2 m_y;

public:
    AA(const T1 x, const T2 y) : m_x(x), m_y(y) {}
};

template <typename T>
void show(T &a) //通用的函数模板
{
    cout << "通用:x=" << a.m_x << ",y=" << a.m_y << endl;
}

template <>
void show(AA<int, string> &a) //函数模板的具体版本
{
    cout << "具体<int,string>:x=" << a.m_x << ",y=" << a.m_y << endl;
}

void test()
{
    AA<int, string> aa(3, "hello");
    show(aa);

    AA<char, string> bb('a', "world");
    show(bb);
}
int main()
{
    test();
    return 0;
}
具体<int,string>:x=3,y=hello
通用:x=a,y=world
```