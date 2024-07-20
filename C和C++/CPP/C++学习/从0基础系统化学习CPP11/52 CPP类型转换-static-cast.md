    C风格的类型转换很容易理解：

语法：(目标类型)表达式 或 目标类型(表达式);

C++认为C风格的类型转换过于松散，可能会带来隐患，不够安全。

C++推出了新的类型转换来替代C风格的类型转换，采用更严格的语法检查，降低使用风险。

C++新增了四个关键字 static_cast、const_cast、reinterpret_cast和dynamic_cast，用于支持C++风格的类型转换

C++的类型转换只是语法上的解释，本质上与C风格的类型转换没什么不同，C语言做不到的事情的C++也做不到

语法：

static_cast<目标类型>(表达式);

const_cast<目标类型>(表达式);

reinterpret_cast<目标类型>(表达式);

dynamic_cast<目标类型>(表达式);                                   //重新解释

实际开发中用的最多（97%）的是static_cast，其它几种很少用

### static_cast

```
#include <iostream>
using namespace std;
void func(void *ptr)
{
    double *pp = static_cast<double *>(ptr);
    //其它代码
}
//内置数据类型转换
void test()
{
    int ii = 3;
    long ll = ii; //绝对安全,可以隐式转换,不会出现警告

    double dd = 1.234;
    long ll1 = dd;                    //可以隐式转换,但是,会出现可能丢失数据的警告
    long ll2 = (long)dd;              // long(dd)  C风格:显示转换,不会出现警告
    long ll3 = static_cast<long>(dd); // C++风格:显示转换,不会出现警告
}
//指针转换
void test2()
{
    int ii = 10;

    // double *pd1 = &ii;//错误,不能隐私转换
    double *pd2 = (double *)&ii; // C风格,强制转换
    // double *pd3 = static_cast<double *>(&ii); //错误,static_cast不支持不同烈性指针的转换

    void *pv = &ii;                          //任何类型的指针都可以隐私转换成void*
    double *pd4 = static_cast<double *>(pv); // static_cast可以把void*转换成其它类型的指针
}
int main()
{
    test();
    test2();
    return 0;
}
```

为什么要转换指针呢？ 一般是为了调用函数，指针用于传递参数，如果调用的函数需要转换指针，可以把它的形参设计为void* 类型，调用的时候，把其它类型的指针作为实参传进去，在函数里面再把void*指针转换成其它类型的指针。

也就是说转换指针的场景是：

其它类型的指针->void*指针->其它类型指针

reinterpret_cast

  只为转换指针的场景而设计

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220900.jpg)

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220454.jpg)

 上面几种应用场景在多线程、函数回调和网络编程中经常用到。

### const_cast

static_cast不能丢掉指针（引用）的const和volitale属性，const_cast可以。

C++中volitale关键字没什么用，java中的volitale关键字有用的，这里就不见volitale了，有兴趣再百度把。

```
#include <iostream>
using namespace std;
void func(void *ptr)
{
    double *pp = static_cast<double *>(ptr);
    //其它代码
}
//内置数据类型转换
void test()
{
    int ii = 3;
    long ll = ii; //绝对安全,可以隐式转换,不会出现警告

    double dd = 1.234;
    long ll1 = dd;                    //可以隐式转换,但是,会出现可能丢失数据的警告
    long ll2 = (long)dd;              // long(dd)  C风格:显示转换,不会出现警告
    long ll3 = static_cast<long>(dd); // C++风格:显示转换,不会出现警告
}
//指针转换
void test2()
{
    int ii = 10;

    // double *pd1 = &ii;//错误,不能隐私转换
    double *pd2 = (double *)&ii; // C风格,强制转换
    // double *pd3 = static_cast<double *>(&ii); //错误,static_cast不支持不同烈性指针的转换

    void *pv = &ii;                          //任何类型的指针都可以隐私转换成void*
    double *pd4 = static_cast<double *>(pv); // static_cast可以把void*转换成其它类型的指针
}
void func2(void *ptr)
{
    // int i = reinterpret_cast<int>(ptr);
    // cout << i << endl;
}
// reinterpret_cast
// VS里面可以,但是有警告 警告的原因是int和void*占用的内存空间不一样
void test3()
{
    int ii = 10;
    double *pd3 = reinterpret_cast<double *>(&ii);

    func2(reinterpret_cast<void *>(ii));
}

// long long 和void* 都占8字节
void func3(void *ptr)
{
    long long i = reinterpret_cast<long long>(ptr);
    cout << i << endl;
}

void test4()
{
    long long ii = 10;
    func3(reinterpret_cast<void *>(ii));
}
// const_cast
void func4(int *i) {}
void test5()
{
    const int *a = nullptr;
    int *b = (int *)a;             // C风格,强制转换,丢掉const限定符
    int *c = const_cast<int *>(a); // C++风格,强制转换,丢掉const限定符

    func4(const_cast<int *>(a));
}
int main()
{
    test();
    test2();
    // test3();
    test4();
    test5();
    return 0;
}
```