很多STL算法都使用函数对象，也叫函数符（functor），包括函数名、函数指针和仿函数。

一般不会用函数名，如果用函数名，代码是死的。C语言只能用函数指针，C++建议用仿函数，因为仿函数的功能更强大。

函数符的概念：


1）生成器（generator）：不用参数就可以调用的函数符。


2）一元函数（unary function）：用一个参数可以调用的函数符。


3）二元函数（binary function）：用两个参数可以调用的函数符。


改进的概念：


1）一元谓词（predicate）：返回bool值的一元函数。


2）二元谓词（binary predicate）：返回bool值的二元函数。

预定义的函数对象：

STL定义了多个基本的函数符，用于支持STL的算法函数。

包含头文件 #include <functional>

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220561.jpg)

学习要领

1)如果容器有成员函数，则使用成员函数，如果没有才考虑用STL中的算法函数

2）把全部的算法函数过一遍，知道大概有什些什么东西。

3）如果打算采用算法函数，一定要搞清它的原理，关注它的效率。

4）不要太看重这些算法函数，自己写一个也就那么回事

5）不是因为简单，而是因为不常用。

for_each()遍历

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220278.jpg)

注意，for_each()函数的返回值是Function,如果第三个参数时普通函数，返回值没有意义，如果第三个参数时仿函数，是类，返回值就是类，那就有意义了，这是一个很巧妙的处理技巧。

```
#include <iostream>
#include <vector>
#include <algorithm>
using namespace std;
template <class T>
struct girl
{
    T m_yz;
    int m_count;
    girl(const T &yz) : m_yz(yz), m_count(0) {}

    void operator()(const T &yz)
    {
        if (yz == m_yz)
        {
            m_count++;
        }
    }
};

void test()
{
    vector<int> v = {1, 2, 3, 1, 2, 1, 3, 2, 3, 4, 1};
    girl<int> g(2);
    // for (auto val : v)
    // {
    //     g(val);
    // }
    // cout << g.m_count << endl;

    // g = for_each(v.begin(), v.end(), g);
    // cout << g.m_count << endl;

    g = for_each(v.begin(), v.end(), girl<int>(1));
    cout << g.m_count << endl;

    // for_each(v.begin(), v.end(), girl<int>(1));
    // cout << g.m_count << endl; //不用变量接收返回的对象,m_count就是0
}
int main()
{
    test();
    return 0;
}
```

如果打算将一种容器用于查找，会选择红黑树和哈希表，一般不会选择数组，