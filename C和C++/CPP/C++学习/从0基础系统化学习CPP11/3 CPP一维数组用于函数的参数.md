CPP一维数组用于函数的参数

1）指针的数组表示

在C++内部，用指针来处理数组。

C++编译器把 数组名[下标] **解释**为  *(数组首地址+下标)

C++编译器把 地址[下标]     **解释**为  *(地址+下标)

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    //用数组表示法操作数组
    for (int i = 0; i < 5; i++)
    {
        cout << "a[" << i << "]=" << a[i] << endl;
    }
    //用指针表示法操作数组
    int *p = a;
    for (int i = 0; i < 5; i++)
    {
        //数组名[下标] 解释为 *(数组首地址+下标)
        cout << "*(p+" << i << ")value is:" << *(p + i) << endl;
        //地址[下标] 解释为 *(地址+下标)
        cout << "p[" << i << "]  value is:" << p[i] << endl;
    }
}
int main()
{
    test();
    return 0;
}
a[0]=1
a[1]=2
a[2]=3
a[3]=4
a[4]=5
*(p+0)value is:1
p[0]  value is:1
*(p+1)value is:2
p[1]  value is:2
*(p+2)value is:3
p[2]  value is:3
*(p+3)value is:4
p[3]  value is:4
*(p+4)value is:5
p[4]  value is:5
```

可以这么理解

int a[5] = {1, 2, 3, 4, 5};这里的a就是个地址，能都a[i]这样访问。

这样可以的话，那p是地址，p[i]应该也可以访问。

这里不要把p理解为数组，用这句话来解释就行（地址[下标] 解释为 *(地址+下标)）

```
#include "iostream"
using namespace std;

void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    //用数组表示法操作数组
    for (int i = 0; i < 5; i++)
    {
        cout << "a[" << i << "]=" << a[i] << endl;
    }
    //骚操作 1
    cout << a[2] << endl;       // 3
    cout << &a[2] << endl;      // 0x61fdd8  是一个地址
    cout << (&a[2]) << endl;    // 0x61fdd8  是一个地址
    cout << (&a[2])[0] << endl; // 3   第二个元素的地址[0] 被解释为 *(第二个元素地址+0)
    cout << (&a[2])[1] << endl; // 4   第二个元素的地址[1] 被解释为 *(第二个元素地址+1)
    cout << (&a[2])[2] << endl; // 5   第二个元素的地址[2] 被解释为 *(第二个元素地址+2)

    //骚操作 2
    char ch[20];          // char占1个字节 20个元素就是20个字节
    int *cp = (int *)&ch; // int占4个字节 20个字节也就是能放5个int元素
    for (int i = 0; i < 5; i++)
    {
        cp[i] = i + 100;
    }
    for (int i = 0; i < 5; i++)
    {
        cout << "*(cp+" << i << ") value is " << *(cp + i) << endl;
    }
    //上面代码,内存空间本质就是一块存储空间,对C++来说,操作内存的时候,变量的数据类型决定了操作内存的方法,
    //一般来说声明变量的时候,指定数据类型就是指定了操作内存的方法,但是我们也可以中途修改操作内存的方法.
    //上面的例子,声明ch数组的时候,申请了一块20字节的内存,本来打算存放字符型数据,后来,把这20个字节挪用了,改为
    //存放整型数据,这种需求完全合理
    //上面实例,可以让我们对内存/指针和数组有更深的认识
}
int main()
{
    test();
    return 0;
}
a[0]=1
a[1]=2
a[2]=3
a[3]=4
a[4]=5
3
0x61fdc8
0x61fdc8
3
4
5
*(cp+0) value is 100
*(cp+1) value is 101
*(cp+2) value is 102
*(cp+3) value is 103
*(cp+4) value is 104
*(cp+5) value is 0
```

2）一维数组用于函数的参数

一维数组用于函数的参数时，只能传输组的地址，并且必须把数组长度传进去（刚开始学习的时候，这里我不太理解，大部分语言，都不需要传）除非数组中有最后一个元素的标志。

书写方法有两种：

void func(int * arr,int len);

void func(int arr[],int len);

注意：

在函数中，可以用数组表示法，也可以用指针表示法。

在函数中，不要对指针名用sizeof运算符，它不是函数名。

下面是<<C++ Primer Plus>>中的原话

![](https://gitee.com/hxc8/images3/raw/master/img/202407172225327.jpg)

```
#include "iostream"
using namespace std;

void wrongFunc(int arr[])
{
    for (int i = 0; i < sizeof(arr) / sizeof(int); i++)
    {
        cout << arr[i] << " ";
    }
    cout << endl;
}
void func(int *arr, int len)
{
    for (int i = 0; i < len; i++)
    {
        cout << arr[i] << " ";
    }
    cout << endl;
}
void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    func(a, 5);
    wrongFunc(a);
}
int main()
{
    test();
    return 0;
}

main.cpp: In function 'void wrongFunc(int*)':
main.cpp:6:35: warning: 'sizeof' on array function parameter 'arr' will return size of 'int*' [-Wsizeof-array-argument]
     for (int i = 0; i < sizeof(arr) / sizeof(int); i++)
                                   ^
main.cpp:4:20: note: declared here
 void wrongFunc(int arr[])
                ~~~~^~~~~
1 2 3 4 5 
1 2 
```

上面代码，一个是传了长度，一个是没有传长度，没有传数组长度的，编译的时候有上面的提示

被认为是int* 在64位操作系统下，也就是8个字节，然后除以int类型占用的4个字节，也就是2，所以打印出来1 和 2

 

```
#include "iostream"
using namespace std;

void wrongFunc(int arr[])
{
    cout << "sizeof(arr)=" << sizeof(arr) << endl;
    for (int i = 0; i < sizeof(arr) / sizeof(int); i++)
    {
        cout << arr[i] << " ";
    }
    cout << endl;
}
void func(int *arr, int len)
{
    for (int i = 0; i < len; i++)
    {
        cout << arr[i] << " ";
    }
    cout << endl;
}
void test()
{
    int a[5] = {1, 2, 3, 4, 5};
    cout << "sizeof(a)=" << sizeof(a) << endl;
    func(a, 5);
    wrongFunc(a);
}
int main()
{
    test();
    return 0;
}
main.cpp: In function 'void wrongFunc(int*)':
main.cpp:6:41: warning: 'sizeof' on array function parameter 'arr' will return size of 'int*' [-Wsizeof-array-argument]
     cout << "sizeof(arr)=" << sizeof(arr) << endl;
                                         ^
main.cpp:4:20: note: declared here
 void wrongFunc(int arr[])
                ~~~~^~~~~
main.cpp:7:35: warning: 'sizeof' on array function parameter 'arr' will return size of 'int*' [-Wsizeof-array-argument]
     for (int i = 0; i < sizeof(arr) / sizeof(int); i++)
                                   ^
main.cpp:4:20: note: declared here
 void wrongFunc(int arr[])
                ~~~~^~~~~
sizeof(a)=20
1 2 3 4 5 
sizeof(arr)=8
1 2
```

可以看到，在test函数中，a是数组名，对数组名用sizeof运算不会把它解析为 地址，sizeof返回的是整个数组a占用的内存空间的大小。

在wrongFunc函数中，arr是指针，堆指针用sizeof运算返回的结果是8（64位操作系统）