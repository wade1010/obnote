76 CPP 右值引用

## **一、左值、右值**

在C++中，所有的值不是左值，就是右值。左值是指表达式结束后依然存在的持久化对象，右值是指表达式结束后就不再存在的临时对象。有名字的对象都是左值，右值没有名字。

还有一个可以区分左值和右值的便捷方法：**看能不能对表达式取地址，如果能，则为左值，否则为右值**。

左值没有再细分的了，但C++11扩展了右值的概念，将右值分为纯右值和将亡值

纯右值：

	- 非引用返回的临时变量，普通函数、lambda函数、类的成员函数以值的方式返回，都是纯右值；

	- 运算表达式产生的结果；

	- 字面常量（C风格字符串除外，它是地址）。

将亡值：与右值引用相关的表达式，例如：将要被移动的对象、T&&函数返回的值、std::move()的返回值、转换成T&&的类型的转换函数的返回值。

不懂纯右值和将亡值的区别其实也没关系，统一看作右值即可，不影响使用。

```
#include <iostream>
using namespace std;
//左值 右值
class AA
{
};
AA getTemp()
{
    return AA();
}

AA &getTemp2(AA &a)
{
    return a;
}
void test()
{
    int i = 3;          // i是左值,3是右值
    int j = i + 3;      // j是左值,i+8是右值
    AA a = getTemp();   // a是左值,getTemp()的返回值是右值(临时变量)
    AA b = getTemp2(a); // b是左值,getTemp2(a)的返回值是引用,是左值.
    AA c;
    getTemp2(a) = c;
}
int main()
{
    test();
    return 0;
}
```

## **二、左值引用、右值引用**

C++98中的引用很常见，就是给变量取个别名，在C++11中，因为增加了右值引用(rvalue reference)的概念，所以C++98中的引用都称为了左值引用(lvalue reference)。

右值引用就是给右值取个名字。

语法：数据类型&& 变量名=右值;

```
#include <iostream>
using namespace std;
class AA
{
public:
    int m_a = 10;
};
AA getTemp()
{
    return AA();
}
void test()
{
    int &&a = 3;     // 3是右值
    int b = 1;       // b是左值
    int &&c = b + 1; // b+1是右值

    AA &&aa = getTemp(); // getTemp()的返回值是右值(临时变量)

    cout << "a=" << a << endl;
    cout << "c=" << c << endl;
    cout << "aa.m_a=" << aa.m_a << endl;
}
int main()
{
    test();
    return 0;
}
```

右值有了名字之后，就是一个普通变量，普通变量有名字，可以取地址。

在上面代码中，getTemp()函数的返回值，本来在表达式语句结束后，其生命周期就该结束了，但是通过右值引用获得了新生，它的生命周期将与右值引用类型变量aa的生命周期一样。

为什么要给临时变量续命呢？

因为临时变量在某些场景下还有继续利用的价值。

引入右值引用的主要目的是实现移动语义。

左值引用只能绑定（关联、指向）左值，右值引用只能绑定右值，如果绑定的不对，编译就会失败。

但是，常量左值引用却是个奇葩，它可以算是一个万能的引用类型，它可以绑定非常量左值、常量左值、右值，而且在绑定右值的时候，常量左值引用还可以像右值引用一样将右值的生命期延长，缺点是，只能读不能改。

int a = 1;

const int& ra = a;   // a是非常量左值。

const int b = 1;

const int& rb = b;  // b是常量左值。

const int& rc = 1;   // 1是右值。

总结一下，其中T是一个具体类型：

1）左值引用， 使用 T&, 只能绑定左值。

2）右值引用， 使用 T&&， 只能绑定右值。

**3）已命名的右值引用是左值。**

**4）常量左值，使用 const T&, 既可以绑定左值又可以绑定右值。**

```
#include <iostream>
using namespace std;
//左值 右值
class AA
{
public:
    string m_a;
};
AA getTemp()
{
    return AA();
}

void test()
{
    int &&a = 3;
    int b = 78;
    int &&c = b + 1;
    // int &&d = c; //错误,因为右值有了名字之后就成了左值,所以再对左值进行右值引用,报错
    int &d = c; //正确

    AA &&aa = getTemp();
    cout << a << endl;
    cout << b << endl;
    cout << c << endl;
    cout << aa.m_a << endl;

    //常量左值引用
    int aaa = 1;
    int &bbb1 = aaa;
    const int &bbb2 = aaa;

    const int &bbb3 = 1;
    int &&ccc = 1;
}
int main()
{
    test();
    return 0;
}
```

     