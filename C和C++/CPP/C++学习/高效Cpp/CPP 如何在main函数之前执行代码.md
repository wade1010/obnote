C/C++中如何在main()函数之前执行一条语句

我们都知道，C++中函数的默认入口是main,那么除了更改入口函数的方法之外如何在mai函数之前执行一些代码呢，本节我们来讲

个通用的简单直接的方法，利用全局变量的初始化。

1 全局对象的构造

```
#include <iostream>
class A
{
public:
    A() { std::cout << __FUNCTION__ << std::endl; }
};

A a;
int main(int argc, char const *argv[])
{
    std::cout << __FUNCTION__ << std::endl;
    return 0;
}

```

2 通过函数初始化全局变量

```
#include <iostream>

int func()
{
    std::cout << __FUNCTION__ << std::endl;
    return 1;
}

int a = func();

int main(int argc, char const *argv[])
{
    std::cout << __FUNCTION__ << std::endl;
    return 0;
}

```