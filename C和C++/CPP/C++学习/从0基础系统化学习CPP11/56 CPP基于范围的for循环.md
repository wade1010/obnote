56 CPP基于范围的for循环

对于一个有范围的集合来说，在程序代码中指定循环的范围有时候是多余的，还可能犯错误。

C++11中引入了基于范围的for循环。

语法：

for (迭代的变量 : 迭代的范围)

{

// 循环体。

}

注意：

1）迭代的范围可以是数组名、容器名、初始化列表或者可迭代的对象（支持begin()、end()、++、==）。

2）数组名传入函数后，已退化成指针，不能作为容器名。

3）如果容器中的元素是结构体和类，迭代器变量应该申明为引用，加const约束表示只读。

4）注意迭代器失效的问题。

```
#include <iostream>
#include <vector>
using namespace std;
//基于范围的for循环
class AA
{
public:
    string m_name;
    AA() { cout << "默认构造函数AA()" << endl; }
    AA(const string &name) : m_name(name)
    {
        cout << "构造函数,name=" << m_name << endl;
    }
    AA(const AA &a) : m_name(a.m_name)
    {
        cout << "拷贝构造函数,name=" << m_name << endl;
    }
    AA &operator=(const AA &a)
    {
        m_name = a.m_name;
        cout << "赋值函数,name=" << m_name << endl;
        return *this;
    }
    ~AA()
    {
        cout << "析构函数,name=" << m_name << endl;
    }
};
void test()
{
    vector<int> vv = {1, 2, 3, 4, 5, 6, 7, 8, 9, 10};
    for (auto val : vv)
    {
        cout << val << " ";
    }
    cout << endl;
    for (auto val : vv)
    {
        cout << val << " ";
    }
    cout << endl;

    vector<AA> va;
    cout << va.capacity() << endl;
    va.emplace_back("p1");
    cout << va.capacity() << endl;
    va.emplace_back("p2");
    cout << va.capacity() << endl;
    va.emplace_back("p3");
    cout << va.capacity() << endl;

    for (const auto &a : va)
    {
        cout << a.m_name << " ";
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
1 2 3 4 5 6 7 8 9 10 
1 2 3 4 5 6 7 8 9 10

W:\workspace\cpp\practice\cpp_basics_2>g++ --std=c++11 main.cpp && a.exe
1 2 3 4 5 6 7 8 9 10 
1 2 3 4 5 6 7 8 9 10
0
构造函数,name=p1
1
构造函数,name=p2
拷贝构造函数,name=p1
析构函数,name=p1
2
构造函数,name=p3
拷贝构造函数,name=p1
拷贝构造函数,name=p2
析构函数,name=p1
析构函数,name=p2
4
p1 p2 p3
析构函数,name=p1
析构函数,name=p2
析构函数,name=p3
```