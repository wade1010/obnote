CPP函数重载

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224105.jpg)

函数重载的细节：

1 使用重载函数时，如果数据类型不匹配，C++尝试使用类型转换与形参进行匹配，如果转换后有多个函数能匹配上，编译将报错。

2 **引用可以作为函数重载的条件，但是调用重载函数的  时候，如果实参是变量，编译器形参类型的本身和类型引用视为同一特征**。

3 如果重载函数有默认参数，调用函数时，可能导致匹配失败。

4 const不能作为函数重载的特征

5 返回值不能作为函数重载的特征。

6 C++的名称修饰：编译时，对每个函数名进行加密，替换成不同名的函数。

对于上述细节2 **引用可以作为函数重载的条件，但是调用重载函数的  时候，如果实参是变量，编译器形参类型的本身和类型引用视为同一特征**。的代码演示

```
#include "iostream"

using namespace std;

void show(short bh, string message) {
    cout << "short bh,string message" << endl;
}

void show(short &bh, string message) {
    cout << "short &bh,string message" << endl;
}

void test() {
    short a = 10;
    show(a, "hello");
}

int main() {
    test();
    return 0;
}

编译报错 call to 'show' is ambiguous
```

![](images/WEBRESOURCE4d31d2ab04009fbe4617b62fc5bec38a截图.png)

但是改成常量10就能匹配到第一个show,因为常量10不能引用。

另外一个测试

在第二个show的bh参数加上const

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224616.jpg)

也会报不明确,因为加了const后 变成 const short &hb,这个时候，10会创建临时变量再赋值，所以两个show都能匹配。导致报错

对于上述细节4 const不能作为函数重载的特征。的代码演示

![](https://gitee.com/hxc8/images2/raw/master/img/202407172224669.jpg)

对于上述细节 6 C++的名称修饰：编译时，对每个函数名进行加密，替换成不同名的函数。

在C语言中，不同的函数必须采用不同的名字，C++也一样，但是C++采用名称修饰的方法，编译的时候，把重载的函数名替换成不同名的函数。本质上其实是不同的名字，只是程序员看上去名字是一样的。