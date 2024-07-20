异常的理念看是有前途，但实际的使用效果并不好，

编程社区达成了一致意见，最好不要使用这项功能。

C++98引入异常规范，C++11已弃用

一、异常的语法

1）捕获全部的异常

try

{

// 可能抛出异常的代码。

// throw 异常对象;

}

catch (...)

{

// 不管什么异常，都在这里统一处理。

}

2）捕获指定的异常

try

{

// 可能抛出异常的代码。

// throw 异常对象;

}

catch (exception1 e)

{

// 发生exception1异常时的处理代码。

}

catch (exception2 e)

{

// 发生exception2异常时的处理代码。

}

在try语句块中，如果没有发生异常，执行完try语句块中的代码后，将继续执行try语句块之后的代码；如果发生了异常，用throw抛出异常对象，异常对象的类型决定了应该匹配到哪个catch语句块，如果没有匹配到catch语句块，程序将调用abort()函数中止。

如果try语句块中用throw抛出异常对象，并且匹配到了catch语句块，执行完catch语句块中的代码后，将继续执行catch语句块之后的代码，不会回到try语句块中。

```
#include <iostream>
using namespace std;
//异常
void test()
{
    try
    {
        int ii = 0;
        cout << "请输入选择,1:cosnt char*抛出异常,2:int异常,3:stirng异常,4:正常" << endl;
        cin >> ii;
        if (ii == 1)
        {
            throw "该抛就抛";
        }
        else if (ii == 2)
        {
            throw ii;
        }
        else if (ii == 3)
        {
            throw string("string");
        }

        cout << "ok" << endl;
    }
    catch (int ei)
    {
        cout << "异常的类型是int=" << ei << endl;
    }
    catch (const char *ec)
    {
        cout << "异常的类型是const char *=" << ec << endl;
    }
    catch (string es)
    {
        cout << "异常的类型是string=" << es << endl;
    }
    // catch (...)
    // {
    //     cout << "捕获到异常,没管具体什么异常" << endl;
    // }
    cout << "程序结束" << endl;
}
int main()
{
    test();
    return 0;
}
```

## **异常规范**

C++98标准提出了异常规范，目的是为了让使用者知道函数可能会引发哪些异常。

void func1() throw(A, B, C);     // 表示该函数可能会抛出A、B、C类型的异常。

void func2() throw();           // 表示该函数不会抛出异常。

void func3();                  // 该函数不符合C++98的异常规范。

C++11标准弃用了异常规范，使用新增的关键字noexcept指出函数不会引发异常。

void func4() noexcept;         // 该函数不会抛出异常。

在实际开发中，大部分程序员懒得在函数后面加noexcept，弃用异常已是共识，没必要多此一举。

关键字noexcept也可以用作运算符，判断表达试（操作数）是否可能引发异常；如果表达式**可能引发异常，则返回false**，否则返回true。

```
#include <iostream>
using namespace std;

void test() noexcept
{
    if (!noexcept(throw "aaaa"))
    {
        cout << "抛出异常" << endl;
    }
}
int main()
{
    test();
    return 0;
}
// 抛出异常
```

C++标准库异常

![](https://gitee.com/hxc8/images2/raw/master/img/202407172219936.jpg)

五、重点关注的异常

1）std::bad_alloc

如果内存不足，调用new会产生异常，导致程序中止；如果在new关键字后面加(std::nothrow)选项，则返回nullptr，不会产生异常。

2）std::bad_cast

dynamic_cast可以用于引用，但是，C++没有与空指针对应的引用值，如果转换请求不正确，会出现std::bad_cast异常。

3）std::bad_typeid

假设有表达式typeid(*ptr)，当ptr是空指针时，如果ptr是多态的类型，将引发std::bad_typeid异常。

```
#include <stdexcept>
#include <iostream>
#include <string>
using namespace std;

int main()
{
    string str = "123"; // 不会抛出异常。
    // string str = "";     // 将抛出Invalid_argument异常。
    // string str = "253647586946334221002101";  // 将抛出out_of_range异常。

    try
    {
        int x = stoi(str); // 把string字符串转换为整数。
        cout << "x=" << x << endl;
    }
    catch (invalid_argument &)
    {
        cout << " invalid_argument. \n";
    }
    catch (out_of_range &)
    {
        cout << " out of range. \n";
    }
    catch (...)
    {
        cout << " something else…" << endl;
    }
}
```