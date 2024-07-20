CPP数组越界问题

1 数组可以越界访问，但是结果是不可知的

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    //用数组表示法操作数组
    for (int i = 0; i < 5; i++)
    {
        cout << "a[" << i << "]的值是:" << a[i] << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        cout << "a[" << i << "]的值是:" << a[i] << endl;
    }
    cout << endl;

    //用指针法操作数组
    cout << "-------------------------------------" << endl;

    int *p = a;
    for (int i = 0; i < 5; i++)
    {
        cout << "*(p+" << i << ")的值是:" << *(p + i) << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        cout << "*(p+" << i << ")的值是:" << *(p + i) << endl;
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}

a[0]的值是:1
a[1]的值是:2
a[2]的值是:3
a[3]的值是:4
a[4]的值是:5

a[0]的值是:1
a[1]的值是:2
a[2]的值是:3
a[3]的值是:4
a[4]的值是:5
a[5]的值是:0
a[6]的值是:24
a[7]的值是:0
a[8]的值是:0
a[9]的值是:0

-------------------------------------
*(p+0)的值是:1
*(p+1)的值是:2
*(p+2)的值是:3
*(p+3)的值是:4
*(p+4)的值是:5

*(p+0)的值是:1
*(p+1)的值是:2
*(p+2)的值是:3
*(p+3)的值是:4
*(p+4)的值是:5
*(p+5)的值是:0
*(p+6)的值是:6421952
*(p+7)的值是:0
*(p+8)的值是:8
*(p+9)的值是:5
```

2 数组可以越界修改，但是显示也会有问题，视频里面VS里面是会有报错，我这测试没报错

2-1：只修改数组表示法

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    //用数组表示法操作数组
    for (int i = 0; i < 5; i++)
    {
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        a[i] = 100 + i;
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    //用指针法操作数组
    cout << "-------------------------------------" << endl;

    // int b[5] = {1, 2, 3, 4, 5};
    int *p = a;
    for (int i = 0; i < 5; i++)
    {
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        // *(p + i) = 100 + i;
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
a[0]value is:1
a[1]value is:2
a[2]value is:3
a[3]value is:4
a[4]value is:5

a[0]value is:100
a[1]value is:101
a[2]value is:102
a[3]value is:103
a[4]value is:104
a[5]value is:105
a[6]value is:106
a[7]value is:107
a[8]value is:108
a[9]value is:109

-------------------------------------
*(p+0)value is:100
*(p+1)value is:101
*(p+2)value is:102
*(p+3)value is:103
*(p+4)value is:104

*(p+0)value is:100
*(p+1)value is:101
*(p+2)value is:102
*(p+3)value is:103
*(p+4)value is:104
*(p+5)value is:105
*(p+6)value is:6421952
*(p+7)value is:0
*(p+8)value is:8
*(p+9)value is:5
好像看上去数组表示法是有点正常的，指针法是错误的
```

2-1：只修改指针表示法

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    //用数组表示法操作数组
    for (int i = 0; i < 5; i++)
    {
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        // a[i] = 100 + i;
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    //用指针法操作数组
    cout << "-------------------------------------" << endl;

    // int b[5] = {1, 2, 3, 4, 5};
    int *p = a;
    for (int i = 0; i < 5; i++)
    {
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        *(p + i) = 100 + i;
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
a[0]value is:1
a[1]value is:2
a[2]value is:3
a[3]value is:4
a[4]value is:5

a[0]value is:1
a[1]value is:2
a[2]value is:3
a[3]value is:4
a[4]value is:5
a[5]value is:0
a[6]value is:24
a[7]value is:0
a[8]value is:0
a[9]value is:0

-------------------------------------
*(p+0)value is:1
*(p+1)value is:2
*(p+2)value is:3
*(p+3)value is:4
*(p+4)value is:5

*(p+0)value is:100
*(p+1)value is:101
*(p+2)value is:102
*(p+3)value is:103
*(p+4)value is:104
*(p+5)value is:105

只修改指针法，看上去都是有问题的
```

2-3：同时修改指针法和数组法

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    //用数组表示法操作数组
    for (int i = 0; i < 5; i++)
    {
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        a[i] = 100 + i;
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    //用指针法操作数组
    cout << "-------------------------------------" << endl;

    // int b[5] = {1, 2, 3, 4, 5};
    int *p = a;
    for (int i = 0; i < 5; i++)
    {
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        *(p + i) = 100 + i;
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
a[0]value is:1
a[1]value is:2
a[2]value is:3
a[3]value is:4
a[4]value is:5

a[0]value is:100
a[1]value is:101
a[2]value is:102
a[3]value is:103
a[4]value is:104
a[5]value is:105
a[6]value is:106
a[7]value is:107
a[8]value is:108
a[9]value is:109

-------------------------------------
*(p+0)value is:100
*(p+1)value is:101
*(p+2)value is:102
*(p+3)value is:103
*(p+4)value is:104

*(p+0)value is:100
*(p+1)value is:101
*(p+2)value is:102
*(p+3)value is:103
*(p+4)value is:104
*(p+5)value is:105
也是数组法好像有点堆，指针法不好理解为什么多了一个
```

2-4：指针法和数组法操作不同的数组（数组内容一样)

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    //用数组表示法操作数组
    for (int i = 0; i < 5; i++)
    {
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        a[i] = 100 + i;
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    //用指针法操作数组
    cout << "-------------------------------------" << endl;

    int b[5] = {1, 2, 3, 4, 5};
    int *p = b;
    for (int i = 0; i < 5; i++)
    {
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;

    for (int i = 0; i < 10; i++)
    {
        *(p + i) = 100 + i;
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
a[0]value is:1
a[1]value is:2
a[2]value is:3
a[3]value is:4
a[4]value is:5

a[0]value is:100
a[1]value is:101
a[2]value is:102
a[3]value is:103
a[4]value is:104
a[5]value is:105
a[6]value is:106
a[7]value is:107
a[8]value is:108
a[9]value is:109

-------------------------------------
*(p+0)value is:1
*(p+1)value is:2
*(p+2)value is:3
*(p+3)value is:4
*(p+4)value is:5

*(p+0)value is:100
*(p+1)value is:101
*(p+2)value is:102
*(p+3)value is:103
*(p+4)value is:104
*(p+5)value is:105
*(p+6)value is:106
*(p+7)value is:107
*(p+8)value is:108
*(p+9)value is:109
显示结果一样，但都有点问题
```

2-5：把越界数值调大点 调成100

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    //用数组表示法操作数组
    for (int i = 0; i < 5; i++)
    {
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    for (int i = 0; i < 100; i++)
    {
        a[i] = 100 + i;
        cout << "a[" << i << "]value is:" << a[i] << endl;
    }
    cout << endl;

    //用指针法操作数组
    cout << "-------------------------------------" << endl;

    int b[5] = {1, 2, 3, 4, 5};
    int *p = b;
    for (int i = 0; i < 5; i++)
    {
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;

    for (int i = 0; i < 100; i++)
    {
        *(p + i) = 100 + i;
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
a[0]value is:1
a[1]value is:2
a[2]value is:3
a[3]value is:4
a[4]value is:5

a[0]value is:100
a[1]value is:101
a[2]value is:102
a[3]value is:103
a[4]value is:104
a[5]value is:105
a[6]value is:106
a[7]value is:107
a[8]value is:108
a[9]value is:109
a[110]value is:0

-------------------------------------
*(p+0)value is:1
*(p+1)value is:2
*(p+2)value is:3
*(p+3)value is:4
*(p+4)value is:5

*(p+0)value is:100
*(p+1)value is:101
*(p+2)value is:102
*(p+3)value is:103
*(p+4)value is:104
*(p+5)value is:105
*(p+6)value is:106
*(p+7)value is:107
*(p+8)value is:108
*(p+9)value is:109
*(p+10)value is:110
*(p+11)value is:111
*(p+12)value is:112
*(p+13)value is:113
看上去就更奇怪了。。。
```

上面的代码为什么不直接报错呢，真的是留着害人。

另外还有野指针的情况，数组越界也会出现野指针。

数组越界的本质是野指针，不管采用数组表示法还是指针表示法都是野指针。

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    for (int i = -2; i < 5; i++)
    {
        cout << "a[" << i << "] address is:" << &a[i] << " " << (long long)&a[i] << endl;
    }
    cout << endl;
}
int main()
{
    test();
    return 0;
}
a[-2] address is:0x61fdc8 6421960
a[-1] address is:0x61fdcc 6421964
a[0] address is:0x61fdd0 6421968
a[1] address is:0x61fdd4 6421972
a[2] address is:0x61fdd8 6421976
a[3] address is:0x61fddc 6421980
a[4] address is:0x61fde0 6421984
```

就算数组下标给一个负数，数组表示法照样显示地址。 从地址来看，还是相差4个直接，也就是a这个int数组中一个int所占字节数。也就是证明了C++数组下标被解释成指针

解决办法：

1 写代码的时候自己注意

2 用C++封装好的数组，肯定不会出现越界的情况。