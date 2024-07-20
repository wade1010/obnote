![](https://gitee.com/hxc8/images2/raw/master/img/202407172220415.jpg)

一、构造和析构

静态常量成员 string::npos为字符串数组的最大长度（通常是unsigned int的最大值）；

NBTS(null-terminated string)：C风格的字符串（以字符串0结束的字符串）

```
#include <iostream>
using namespace std;

void test()
{
    cout << "npos=" << string::npos << endl;

    string s;
    cout << s.capacity() << " " << s.size() << endl;
    cout << "容器动态数组的首地址=" << (void *)s.c_str() << endl;
    s = "sssssssssssssssssssssssss";
    cout << s.capacity() << " " << s.size() << endl;
    cout << "容器动态数组的首地址=" << (void *)s.c_str() << endl;
    /* 上面代码输出结果:
    15 0
    容器动态数组的首地址=0x61fdd0
    30 25
    容器动态数组的首地址=0xf71760 */
    //可以看出,两个数组的首地址不一样,string类扩展容器的时候,
    // 先分配更大的空间,然后把内容复制到型的空间,再把以前的空间释放掉

    // 2）string(const char *s)：将string对象初始化为s指向的NBTS（转换函数）。
    string s2("hello world");
    cout << "s2=" << s2 << endl; // 将输出s2=hello world
    string s3 = "hello world";   //一个参数的构造函数可以用于转换函数,所以上面的方法等同于下面的代码
    cout << "s3=" << s3 << endl; // 将输出s3=hello world

    // 3）string(const string & str)：将string对象初始化为str（拷贝构造函数）。
    string s4(s3);               // s3 = "hello world";
    cout << "s4=" << s4 << endl; // 将输出s4=hello world
    string s5 = s3;
    cout << "s5=" << s5 << endl; // 将输出s5=hello world

    // 4）string(const char* s, size_t n)：将string对象初始化为s指向的NBTS的前n个字符，即使超过了NBTS结尾。
    string s6("hello world", 5);
    cout << "s6=" << s6 << endl;                       // 将输出s6=hello
    cout << "s6.capacity()=" << s6.capacity() << endl; // 返回当前容量，可以存放字符的总数。
    cout << "s6.size()=" << s6.size() << endl;         // 返回容器中数据的大小。
    //从"hello world"位置开始复制后面50个字节的内容.
    string s7("hello world", 50);
    cout << "s7=" << s7 << endl;                       // 将输出s7=hello world未知内容
    cout << "s7.capacity()=" << s7.capacity() << endl; // 返回当前容量，可以存放字符的总数。
    cout << "s7.size()=" << s7.size() << endl;         // 返回容器中数据的大小。

    // 5）string(const string & str, size_t pos = 0, size_t n = npos)：
    // 将string对象初始化为str从位置pos开始到结尾的字符，或从位置pos开始的n个字符。
    string s8(s3, 3, 5);         // s3 = "hello world";
    cout << "s8=" << s8 << endl; // 将输出s8=lo wo
    string s9(s3, 3);
    cout << "s9=" << s9 << endl;                       // 将输出s9=lo world
    cout << "s9.capacity()=" << s9.capacity() << endl; // 返回当前容量，可以存放字符的总数。
    cout << "s9.size()=" << s9.size() << endl;         // 返回容器中数据的大小。
    string s10("hello world", 3, 5);
    cout << "s10=" << s10 << endl; // 将输出s10=lo wo
    string s11("hello world", 3);  // 注意：不会用构造函数5），而是用构造函数4）
    cout << "s11=" << s11 << endl; // 将输出s11=hel

    // 6）template<class T> string(T begin, T end)：将string对象初始化为区间[begin, end]内的字符，
    //      其中begin和end的行为就像指针，用于指定位置，范围包括begin在内，但不包括end。
    string s15(s3.begin(), s3.end() - 3);
    cout << "s15=" << s15 << endl; // s12=hello wo

    // 7）string(size_t n, char c)：创建一个由n个字符c组成的string对象。
    string s12(8, 'x');
    cout << "s12=" << s12 << endl;                       // 将输出s12=xxxxxxxx
    cout << "s12.capacity()=" << s12.capacity() << endl; // s12.capacity()=15
    cout << "s12.size()=" << s12.size() << endl;         // s12.size()=8
    string s13(30, 0);
    cout << "s13=" << s13 << endl;                       // 将输出s13=
    cout << "s13.capacity()=" << s13.capacity() << endl; // s13.capacity()=31
    cout << "s13.size()=" << s13.size() << endl;         // s12.size()=30

    string s14(30, '0');
    cout << "s14=" << s14 << endl; // 将输出s14=000000000000000000000000000000
}
int main()
{
    test();
    return 0;
}
```

```
shrink_to_fit

#include <iostream>
using namespace std;

void test()
{
    string a;
    cout << a.capacity() << " " << a.length() << " " << a.size() << endl;
    a = "xxxxxxxxxxxxxxxxxxxxx";
    cout << a.capacity() << " " << a.length() << " " << a.size() << endl;
    a.shrink_to_fit();
    cout << a.capacity() << " " << a.length() << " " << a.size() << endl;
}
int main()
{
    test();
    return 0;
}
15 0 0
30 21 21
21 21 21
```

string容器-设计目标

![](https://gitee.com/hxc8/images2/raw/master/img/202407172220005.jpg)

计算机的内存就是一块空间，没有数据类型的说法。数据类型时编程语言中的概念，指出了操作内存中的数据的方法。

```
#include <iostream>
#include <cstring>
using namespace std;

void test()
{
    char cc[8]; //在栈上分配8字节的内存空间
    //把cc的内存空间用于字符串

    //把cc的内存空间用于int型整数
    int *a, *b;
    a = (int *)cc;
    b = (int *)cc + 1;
    cout << (long long)a << endl;
    cout << (long long)b << endl;
    *a = 1999;
    *b = 2222;
    cout << "*a=" << *a << endl;
    cout << "*b=" << *b << endl;

    //把cc的内存空间用于double
    double *d = (double *)cc;
    *d = 333.222;
    cout << "*d=" << *d << endl;

    //把cc的内存空间用于结构体
    struct stt
    {
        int a;
        char b[4];
    } * st;

    st = (struct stt *)&cc;
    st->a = 111;
    /*     strcpy(st->b, "abcd"); //如果是这样,不会报错,但是输出为:
    st->a=6821633   这个值每次都变,不知道指向了哪里
    st->b=  打印出来为空*/
    strcpy(st->b, "abc");
    cout << "st->a=" << st->a << endl;
    cout << "st->b=" << st->b << endl;

    // void * malloc(size_t size)
    // char *cc1 = (char *)malloc(8);
    // int *cc1 = (int *)malloc(8);
}
int main()
{
    test();
    return 0;
}
6421952
6421956
*a=1999
*b=2222
*d=333.222
st->a=111
st->b=abc
```

```
6421952
6421956
*a=1999
*b=2222
*d=333.222
st->a=111
st->b=abc
```

size()==8的string对象时什么意思？

长度为8的字符串，这么理解太狭隘了，

应该说是已使用了8字节的内存空间。

string对象是以字节为嘴小存储单元的动态顺序容器。

主要有两种用途

1 用于存放字符串（不存空字符0，空字符0是C风格字符串特有的）

2 用于存放数据的内存空间（缓冲区）

在实际开发中，最常见的是用作缓冲区。

string内部的三个指针

```
    cahr *start_;  //动态分配内存块开始的地址.
    cahr *end_;    //动态分配内存块最后的地址.
    cahr *finish_; //已使用空间的最后的地址
```

有这3个指针，所以 用它存放字符串的时候，不需要空字符0

字符串的长度用finish-start就出来了

```
#include <iostream>
using namespace std;
void test()
{
    string s1 = "111111111111111"; //不交换动态数组地址
    string s2 = "222222222222222"; //不交换动态数组地址
    // string s1 = "111111111111111";//交换动态数组地址
    // string s2 = "222222222222222";//交换动态数组地址
    cout << "s1的内容:" << s1 << endl;
    cout << "s2的内容:" << s2 << endl;
    cout << "s1动态数据的地址:" << (void *)s1.data() << endl;
    cout << "s2动态数据的地址:" << (void *)s2.data() << endl;
    s1.swap(s2);
    cout << "交换后" << endl;
    cout << "s1的内容:" << s1 << endl;
    cout << "s2的内容:" << s2 << endl;
    cout << "s1动态数据的地址:" << (void *)s1.data() << endl;
    cout << "s2动态数据的地址:" << (void *)s2.data() << endl;
}
int main()
{
    test();
    return 0;
}

/* s1的内容:111111111111111
s2的内容:222222222222222
s1动态数据的地址:0x61fdc0
s2动态数据的地址:0x61fda0
交换后
s1的内容:222222222222222
s2的内容:111111111111111
s1动态数据的地址:0x61fdc0
s2动态数据的地址:0x61fda0


s1的内容:1111111111111111
s2的内容:2222222222222222
s1动态数据的地址:0x761760
s2动态数据的地址:0x761780
交换后
s1的内容:2222222222222222
s2的内容:1111111111111111
s1动态数据的地址:0x761780
s2动态数据的地址:0x761760 */
```